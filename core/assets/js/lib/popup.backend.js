window.paypalParty.popup = {};

window.paypalParty.popup.init = function() {
	window.paypalParty.popup.event();
};

window.paypalParty.popup.event = function() {
	jQuery( document ).on( 'keyup', window.paypalParty.popup.keyup );
  jQuery( document ).on( 'click', '.open-popup, .open-popup i', window.paypalParty.popup.open );
  jQuery( document ).on( 'click', '.open-popup-ajax', window.paypalParty.popup.openAjax );
  jQuery( document ).on( 'click', '.popup .container, .digi-popup-propagation', window.paypalParty.popup.stop );
  jQuery( document ).on( 'click', '.popup .container .button.green', window.paypalParty.popup.confirm );
  jQuery( document ).on( 'click', '.popup .close', window.paypalParty.popup.close );
  jQuery( document ).on( 'click', 'body', window.paypalParty.popup.close );
};

window.paypalParty.popup.keyup = function( event ) {
	if ( 27 === event.keyCode ) {
		jQuery( '.popup .close' ).click();
	}
};

window.paypalParty.popup.open = function( event ) {
	var triggeredElement = jQuery( this );

	if ( triggeredElement.is( 'i' ) ) {
		triggeredElement = triggeredElement.parents( '.open-popup' );
	}

	var target = triggeredElement.closest(  '.' + triggeredElement.data( 'parent' ) ).find( '.' + triggeredElement.data( 'target' ) );
	var cbObject, cbFunc = undefined;
	target.addClass( 'active' );

	if ( target.is( ':visible' ) && triggeredElement.data( 'cb-object' ) && triggeredElement.data( 'cb-func' ) ) {
		cbObject = triggeredElement.data( 'cb-object' );
		cbFunc = triggeredElement.data( 'cb-func' );

		// On récupères les "data" sur l'élement en tant qu'args.
		triggeredElement.get_data( function( data ) {
			window.paypalParty[cbObject][cbFunc]( triggeredElement, target, event, data );
		} );
	}

  event.stopPropagation();
};

/**
 * Ouvre la popup en envoyant une requête AJAX.
 * Les paramètres de la requête doivent être configurer directement sur l'élement
 * Ex: data-action="load-workunit" data-id="190"
 *
 * @param  {[type]} event [description]
 * @return {[type]}       [description]
 */
window.paypalParty.popup.openAjax = function( event ) {
	var element = jQuery( this );
	var target = jQuery( this ).closest(  '.' + jQuery( this ).data( 'parent' ) ).find( '.' + jQuery( this ).data( 'target' ) );
	target.addClass( 'active' );

	jQuery( this ).get_data( function( data ) {
		delete data.parent;
		delete data.target;
		window.paypalParty.request.send( element, data );
	});

	event.stopPropagation();
};

window.paypalParty.popup.confirm = function( event ) {
	var triggeredElement = jQuery( this );
	var cbObject, cbFunc = undefined;

	if ( ! jQuery( '.popup' ).hasClass( 'no-close' ) ) {
		jQuery( '.popup' ).removeClass( 'active' );

		if ( triggeredElement.attr( 'data-cb-object' ) && triggeredElement.attr( 'data-cb-func' ) ) {
			cbObject = triggeredElement.attr( 'data-cb-object' );
			cbFunc = triggeredElement.attr( 'data-cb-func' );

			// On récupères les "data" sur l'élement en tant qu'args.
			triggeredElement.get_data( function( data ) {
				window.paypalParty[cbObject][cbFunc]( triggeredElement, event, data );
			} );
		}
	}
};

window.paypalParty.popup.stop = function( event ) {
	event.stopPropagation();
};

window.paypalParty.popup.close = function( event ) {
	jQuery( '.popup:not(.no-close)' ).removeClass( 'active' );
	jQuery( '.digi-popup:not(.no-close)' ).removeClass( 'active' );
};
