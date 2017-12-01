window.paypalParty.input = {};

window.paypalParty.input.init = function() {
	window.paypalParty.input.event();
};

window.paypalParty.input.event = function() {
  jQuery( document ).on( 'keyup', '.digirisk-wrap .form-element input, .digirisk-wrap .form-element textarea', window.paypalParty.input.keyUp );
};

window.paypalParty.input.keyUp = function( event ) {
	if ( 0 < jQuery( this ).val().length ) {
		jQuery( this ).closest( '.form-element' ).addClass( 'active' );
	} else {
		jQuery( this ).closest( '.form-element' ).removeClass( 'active' );
	}
};
