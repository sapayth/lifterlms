/**
 * UI & UX for the Admin add-ons management screen
 *
 * @package LifterLMS/Scripts
 *
 * @since 3.22.0
 * @version [version]
 */

import { _n, sprintf } from '@wordpress/i18n';
import $ from 'jquery';
import '../scss/admin-addons.scss';

( function() {

	/**
	 * Tracks current # of each bulk action to be run upon form submission
	 *
	 * @type {Object}
	 */
	var actions = {
		update: 0,
		install: 0,
		activate: 0,
		deactivate: 0,
	};

	/**
	 * When the bulk action modal is closed, clear all existing staged actions
	 *
	 * @since 3.22.0
	 */
	$( '.llms-bulk-close' ).on( 'click', function( e ) {
		e.preventDefault();
		$( 'input.llms-bulk-check' ).filter( ':checked' ).prop( 'checked', false ).trigger( 'change' );
	} );

	/**
	 * Update the UI and counters when a checkbox action is changed
	 *
	 * @since 3.22.0
	 */
	$( 'input.llms-bulk-check' ).on( 'change', function() {

		var action = $( this ).attr( 'data-action' );

		if ( $( this ).is( ':checked' ) ) {
			actions[ action ]++;
		} else {
			actions[ action ]--;
		}

		update_ui();

	} );

	/**
	 * Updates the UI when bulk actions are changed.
	 *
	 * Shows # of each action to be applied & shows the form submission / cancel buttons
	 *
	 * @since 3.22.0
	 * @since [version] Use `wp.i18n` functions in favor of `LLMS.l10n` and use `$.text()` in favor of `$.html()`.
	 *
	 * @return void
	 */
	function update_ui() {

		var $el = $( '#llms-addons-bulk-actions' );
		if ( actions.update || actions.install || actions.activate || actions.deactivate ) {
			$el.addClass( 'active' );
		} else {
			$el.removeClass( 'active' );
		}

		$.each( actions, function( key, count ) {

			var text  = '',
				$desc = $el.find( '.llms-bulk-desc.' + key );

			if ( count ) {
				// Translators: %d = Number of add-ons to perform the specified action against.
				text = sprintf( _n( '%d add-on', '%d add-ons', count, 'lifterlms' ), count )
				$desc.show();
			} else {
				$desc.hide();
			}
			$desc.find( 'span' ).text( text );

		} );

	}

	/**
	 * Show the keys management dropdown on click of the "My License Keys" button
	 *
	 * @since 3.22.0
	 */
	$( '#llms-active-keys-toggle' ).on( 'click', function() {
		$( '#llms-key-field-form' ).toggle();
	} );

} )();
