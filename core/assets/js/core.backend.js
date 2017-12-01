window.paypalParty.core = {};

window.paypalParty.core.init = function() {
	console.log(paypal.Button.render);
	paypal.Button.render( {
		env: 'sandbox', // Specify 'sandbox' for the test environment

		client: {
			sandbox: 'Aak9dxPs6gPsdFIFpPlzAJFpBnPJl3aGozmMdinpikN4_XjLwzhGRQIPWBAUk-6cgVxbeHjb4pocQYqe'
		},

		payment: function( resolve, reject ) {
			paypal.request.post( ajaxurl + '?create_payment=1' )
				.then( function( data ) { resolve( data.TOKEN ); })
				.catch( function( err ) { reject( err ); });
		},

		commit: true,

		onAuthorize: function() {
			alert('ok');
		}

	}, '#paypal-button' );
};
