window.paypalParty.request = {};

window.paypalParty.request.init = function() {};

window.paypalParty.request.send = function( element, data ) {
	jQuery.post( window.ajaxurl, data, function( response ) {
		element.closest( '.loading' ).removeClass( 'loading' );

		if ( response && response.success ) {
			if ( response.data.module && response.data.callback_success ) {
				window.paypalParty[response.data.module][response.data.callback_success]( element, response );
			}
		} else {
			if ( response.data.module && response.data.callback_error ) {
				window.paypalParty[response.data.module][response.data.callback_error]( element, response );
			}
		}
	}, 'json' );
};

window.paypalParty.request.get = function( url, data ) {
	jQuery.get( url, data, function( response ) {
		if ( response && response.success ) {
			if ( response.data.module && response.data.callback_success ) {
				window.paypalParty[response.data.module][response.data.callback_success]( response );
			}
		} else {
			if ( response.data.module && response.data.callback_error ) {
				window.paypalParty[response.data.module][response.data.callback_error]( response );
			}
		}
	}, 'json' );
};
