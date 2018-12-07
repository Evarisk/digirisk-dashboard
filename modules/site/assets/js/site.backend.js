window.eoxiaJS.digiriskDashboard.site = {};

window.eoxiaJS.digiriskDashboard.site.init = function() {
	window.eoxiaJS.digiriskDashboard.site.event();
};

window.eoxiaJS.digiriskDashboard.site.event = function() {
	jQuery( document ).on( 'keyup', '.edit-site input', window.eoxiaJS.digiriskDashboard.site.checkInput );
};

window.eoxiaJS.digiriskDashboard.site.checkInput = function( event ) {
	var empty = true;

	if ( jQuery( '.edit-site input[name="url"]').val().length > 0 && jQuery( '.edit-site input[name="unique_key"]' ).val().length > 0 ) {
		empty = false;
	}

	if ( empty ) {
		jQuery ( '.edit-site .wpeo-button' ).addClass( 'button-disable' );
	} else {
		jQuery ( '.edit-site .wpeo-button' ).removeClass( 'button-disable' );
	}
};

window.eoxiaJS.digiriskDashboard.site.addedSiteSuccess = function( triggeredElement, response ) {
	jQuery( '.edit-site .notice.notice-error').hide()
	jQuery( '.edit-site .notice.notice-success').show().find( 'p' ).text( response.data.message );
};

window.eoxiaJS.digiriskDashboard.site.addedSiteError = function( triggeredElement, response ) {
	jQuery( '.edit-site .notice.notice-success').hide()
	jQuery( '.edit-site .notice.notice-error').show().find( 'p' ).text( response.data.message );
};

window.eoxiaJS.digiriskDashboard.site.deletedSiteSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( 'tr' ).fadeOut();
};
