window.paypalParty.date = {};

window.paypalParty.date.init = function() {
	jQuery( document ).on( 'click', '.date', function( e ) {
		jQuery( this ).datepicker( {
			dateFormat: 'dd/mm/yy'
		} );

		jQuery( this ).datepicker( 'show' );
	} );
};
