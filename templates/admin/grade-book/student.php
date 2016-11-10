<?php
/**
 * Single Student View
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! is_admin() ) { exit; }
?>

<section class="llms-gb-student">

	<header class="llms-gb-student-header">

		<?php echo $student->get_avatar( 64 ); ?>
		<div class="llms-gb-student-info">
			<h2><a href="<?php echo get_edit_user_link( $student->get_id() ); ?>"><?php echo $student->get_name(); ?></a></h2>
			<h5><a href="mailto:<?php echo $student->get( 'user_email' ); ?>"><?php echo $student->get( 'user_email' ); ?></a></h5>
		</div>

	</header>

	<nav class="llms-nav-tab-wrapper llms-nav-secondary">
		<ul class="llms-nav-items">
		<?php foreach ( $tabs as $name => $label ) : ?>
			<li class="llms-nav-item<?php echo ( $current_tab === $name ) ? ' llms-active' : ''; ?>">
				<a class="llms-nav-link" href="<?php echo LLMS_Admin_Grade_Book::get_stab_url( $name ) ?>">
					<?php echo $label; ?>
				</a>
		<?php endforeach; ?>
		</ul>
	</nav>

	<section class="llms-gb-tab">
		<?php llms_get_template( 'admin/grade-book/student/' . $current_tab . '.php', array( 'student' => $student ) ); ?>
	</section>

	<footer class="llms-gb-footer">
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=llms-grade-book' ) ); ?>"><?php _e( 'Back to all students', 'lifterlms' ); ?></a>
	</footer>

</section>
