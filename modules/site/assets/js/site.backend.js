window.eoxiaJS.digiriskDashboard.site = {};

window.eoxiaJS.digiriskDashboard.site.init = function() {
	window.eoxiaJS.digiriskDashboard.site.event();
};

window.eoxiaJS.digiriskDashboard.site.event = function() {
	jQuery( document ).on( 'keyup', '.edit-site input', window.eoxiaJS.digiriskDashboard.site.checkInput );
	jQuery( document ).on( 'click', '.manage-htpasswd i', window.eoxiaJS.digiriskDashboard.site.updateToggle );

	jQuery( '.table-sites .statut' ).each( function() {
		var data = {
			action: 'sites_child_check_statut',
			id: jQuery( this ).data( 'id' ),
		};

		var _this = jQuery( this );

		jQuery.post( ajaxurl, data, function( response ) {
			if ( response.data.success ) {
				_this.css( 'color', 'green' );
			} else {
				_this.css( 'color', 'red' );
			}
		} );
	} );
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

window.eoxiaJS.digiriskDashboard.site.updateToggle = function( event ) {
	if ( jQuery( this ).hasClass( 'fa-toggle-off' ) ) {
		jQuery( this ).removeClass( 'fa-toggle-off' ).addClass( 'fa-toggle-on' );
		jQuery( '.bloc-htpasswd' ).slideDown();
		jQuery( this ).closest( '.form-element' ).find( '.manage-htpasswd' ).val(true);
	} else {
		jQuery( this ).removeClass( 'fa-toggle-on' ).addClass( 'fa-toggle-off' );
		jQuery( '.bloc-htpasswd' ).slideUp();
		jQuery( this ).closest( '.form-element' ).find( '.manage-htpasswd' ).val(false);
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
	triggeredElement.closest( '.table-row' ).fadeOut();
};
