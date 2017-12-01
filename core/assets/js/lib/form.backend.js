window.paypalParty.form = {};

window.paypalParty.form.init = function() {
    window.paypalParty.form.event();
};
window.paypalParty.form.event = function() {
    jQuery( document ).on( 'click', '.submit-form', window.paypalParty.form.submitForm );
};

window.paypalParty.form.submitForm = function( event ) {
	var element = jQuery( this );

	element.closest( 'form' ).addClass( 'loading' );

	event.preventDefault();
	element.closest( 'form' ).ajaxSubmit( {
		success: function( response ) {
			element.closest( 'form' ).removeClass( 'loading' );

			if ( response && response.success ) {
				if ( response.data.module && response.data.callback_success ) {
					window.paypalParty[response.data.module][response.data.callback_success]( element, response );
				}
			} else {
				if ( response.data.module && response.data.callback_error ) {
					window.paypalParty[response.data.module][response.data.callback_error]( element, response );
				}
			}
		}
	} );
};
