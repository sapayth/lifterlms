<?php
/**
 * User Account Edit Forms
 *
 * @since   3.7.0
 * @version [version]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class LLMS_Controller_Account {

	public function __construct() {

		add_action( 'init', array( $this, 'update' ) );
		add_action( 'init', array( $this, 'lost_password' ), 15 ); // wait until template functions are loaded since were sending an email
		add_action( 'init', array( $this, 'reset_password' ) );

	}

	/**
	 * Handle submission of user account edit form
	 * @return   void
	 * @since    3.7.0
	 * @version  3.7.0
	 */
	public function update() {

		if ( 'POST' !== strtoupper( getenv( 'REQUEST_METHOD' ) ) || empty( $_POST['action'] ) || 'llms_update_person' !== $_POST['action'] || empty( $_POST['_wpnonce'] ) ) { return; }

		wp_verify_nonce( $_POST['_wpnonce'], 'llms_update_person' );

		// no user logged in, can't update!
		// this shouldn't happen but let's check anyway
		if ( ! get_current_user_id() ) {
			return llms_add_notice( __( 'Please log in and try again.', 'lifterlms' ), 'error' );
		} // attempt to update new user (performs validations)
		else {
			$person_id = llms_update_user( $_POST, 'account' );
		}

		// validation or update issues
		if ( is_wp_error( $person_id ) ) {
			foreach ( $person_id->get_error_messages() as $msg ) {
				llms_add_notice( $msg, 'error' );
			}
			return;
		} // update should be a user_id at this point, if we're not numeric we have a problem...
		elseif ( ! is_numeric( $person_id ) ) {

			return llms_add_notice( __( 'An unknown error occurred when attempting to create an account, please try again.', 'lirterlms' ), 'error' );

		} else {

			llms_add_notice( __( 'Your account information has been saved.', 'lifterlms' ), 'success' );

			// handle redirect
			wp_safe_redirect( apply_filters( 'lifterlms_update_account_redirect', llms_get_endpoint_url( 'edit-account', '', llms_get_page_url( 'myaccount' ) ) ) );
			exit;

		}

	}

	/**
	 * Handle form submission of the Lost Password form
	 * This is the form that sends a password recovery email with a link to reset the password
	 * @return   void
	 * @since    [version]
	 * @version  [version]
	 */
	public function lost_password() {

		// invalid nonce or the form wasn't submitted
		if ( ! llms_verify_nonce( '_lost_password_nonce', 'llms_lost_password', 'POST' ) ) {
			return;
		}

		// verify required field
		if ( empty( $_POST['llms_login'] ) ) {
			return llms_add_notice( __( 'Enter a username or e-mail address.', 'lifterlms' ), 'error' );
		}

		$login = trim( $_POST['llms_login'] );
		$get_by = ( 'yes' === get_option( 'lifterlms_registration_generate_username' ) ) ? 'email' : 'login';

		// make sure user exists
		$user = get_user_by( $get_by, $login );
		if ( ! $user ) {
			return llms_add_notice( __( 'Invalid username or e-mail address.', 'lifterlms' ), 'error' );
		}

		do_action( 'retrieve_password', $user->user_login ); // wp core hook

		// ensure that password resetting is allowed based on core filters & settings
		$allow = apply_filters( 'allow_password_reset', true, $user->ID ); // wp core filter

		if ( ! $allow ) {

			return llms_add_notice( __( 'Password reset is not allowed for this user', 'lifterlms' ), 'error' );

		} elseif ( is_wp_error( $allow ) ) {

			return llms_add_notice( $allow->get_error_message(), 'error' );

		}

		// generate an activation key
		$key = wp_generate_password( 20, false );

		do_action( 'retrieve_password_key', $user->user_login, $key ); // wp core hook

		// insert the hashed key into the db
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = $wp_hasher->HashPassword( $key );

		global $wpdb;
		$wpdb->update(
			$wpdb->users,
			array( 'user_activation_key' => $hashed ),
			array( 'user_login' => $user->user_login )
		);

		// send the email
		// setup the email
		$email = LLMS()->mailer()->get_email( 'reset_password', array(
			'key' => $key,
			'user' => $user,
			'login_display' => 'email' === $get_by ? $user->user_email : $user->user_login,
		) );


		if ( $email ) {

			if ( $email->send() ) {
				return llms_add_notice( __( 'Check your e-mail for the confirmation link.', 'lifterlms' ) );
			}

		}

		return llms_add_notice( __( 'Unable to reset password due to an unknown error. Please try again.', 'lifterlms' ), 'error' );

	}

	/**
	 * Handle form submission of the Reset Password form
	 * This is the form that actually updates a users password
	 * @return   void
	 * @since    [version]
	 * @version  [version]
	 */
	public function reset_password() {

		// invalid nonce or the form wasn't submitted
		if ( ! llms_verify_nonce( '_reset_password_nonce', 'llms_reset_password', 'POST' ) ) {
			return;
		}

		$valid = LLMS_Person_Handler::validate_fields( $_POST, 'reset_password' );

		// validation or registration issues
		if ( is_wp_error( $valid ) ) {
			foreach ( $valid->get_error_messages() as $msg ) {
				llms_add_notice( $msg, 'error' );
			}
			return;
		}

		$login = trim( sanitize_text_field( $_POST['llms_reset_login'] ) );

		if ( ! llms_verify_password_reset_key( trim( sanitize_text_field( $_POST['llms_reset_key'] ) ), $login ) ) {
			return llms_add_notice( __( 'Invalid Key', 'lifterlms' ), 'error' );
		}

		$pass = $_POST['password'];
		$user = get_user_by( 'login', $login );

		if ( ! $user ) {
			return llms_add_notice( __( 'Invalid Key', 'lifterlms' ), 'error' );
		}

		do_action( 'password_reset', $user, $pass );

		wp_set_password( $pass, $user->ID );

		wp_password_change_notification( $user );

		llms_add_notice( sprintf( __( 'Your password has been updated. %1$sClick here to login%2$s', 'lifterlms' ), '<a href="' . esc_url( llms_get_page_url( 'myaccount' ) ) . '">', '</a>' ) );

	}

}

return new LLMS_Controller_Account();
