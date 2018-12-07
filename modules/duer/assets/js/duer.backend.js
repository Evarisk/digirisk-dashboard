window.eoxiaJS.digiriskDashboard.duer = {};

window.eoxiaJS.digiriskDashboard.duer.init = function() {
	window.eoxiaJS.digiriskDashboard.duer.event();
};
window.eoxiaJS.digiriskDashboard.duer.event = function() {
	jQuery( document ).on( 'modal-opened', '.duer-modal', window.eoxiaJS.digiriskDashboard.duer.modalOpened );
	jQuery( document ).on( 'change', '.duer-modal-site select', window.eoxiaJS.digiriskDashboard.duer.selectModel );
	jQuery( document ).on( 'click', '.duer-modal .button-main', window.eoxiaJS.digiriskDashboard.duer.applyValueToTextarea );
	jQuery( document ).on( 'keyup', '.duer-modal-site .filter-site', window.eoxiaJS.digiriskDashboard.duer.filterSite );
};


/**
 * @todo
 * @param  {[type]} event [description]
 * @param  {[type]} data  [description]
 * @return {[type]}       [description]
 */
window.eoxiaJS.digiriskDashboard.duer.modalOpened = function( event, triggeredElement ) {
	jQuery( this ).find( '.modal-content' ).html( '' );

	if ( 'view' !== jQuery( triggeredElement ).data( 'type' ) ) {
		var textareaContent = jQuery( triggeredElement ).closest( 'tr' ).find( '.textarea-content-' + jQuery( triggeredElement ).data( 'src' ) ).val();
		jQuery( this ).find( '.modal-content' ).html( '<textarea data-to="' + jQuery( triggeredElement ).data( 'src' ) + '" rows="8" style="width: 100%; display: inline-block;"></textarea>' );

		jQuery( '.duer-modal' ).find( 'textarea' ).val( textareaContent );

	} else {
		var content = jQuery( triggeredElement ).closest( 'tr' ).find( '.text-content-' + jQuery( triggeredElement ).data( 'src' ) ).html();
		jQuery( this ).find( '.modal-content' ).html( '<p></p>' );

		jQuery( '.duer-modal' ).find( 'p' ).html( content );
	}
};

window.eoxiaJS.digiriskDashboard.duer.selectModel = function( event ) {
	var optionSelect = jQuery( 'option:selected', this );
	var id = optionSelect.val();

	jQuery( '.duer-modal-site .list-sites .selected-model label span' ).hide();
	jQuery( '.duer-modal-site .list-sites li[data-id="' + id + '"] label span' ).show();
};

/**
 * [description]
 *
 * @since 7.0.0
 *
 * @param  {[type]} triggeredElement [description]
 */
window.eoxiaJS.digiriskDashboard.duer.viewInPopup = function( triggeredElement ) {
	return true;
};

/**
 * @todo
 * @param  {[type]} event [description]
 * @return {[type]}       [description]
 */
window.eoxiaJS.digiriskDashboard.duer.applyValueToTextarea = function( event ) {
	var textarea =  jQuery( '.duer-modal' ).find( 'textarea' );

	jQuery( '#duer table tr:last .textarea-content-' + textarea.attr( 'data-to' ) ).val( textarea.val() );
};

window.eoxiaJS.digiriskDashboard.duer.filterSite = function( event ) {
	var sites = jQuery( '.duer-modal-site ul.list-sites li.form-element label' );
	sites.show();

	for ( var i = 0; i < sites.length; i++ ) {
		if ( jQuery( sites[i] ).text().indexOf( jQuery( this ).val() ) == -1 ) {
			jQuery( sites[i] ).hide();
		}
	}
};

window.eoxiaJS.digiriskDashboard.duer.loadedModalGenerateDuerSuccess = function( triggeredElement, response ) {
	jQuery( '.duer-modal-generate' ).replaceWith( response.data.view );
	jQuery( '.duer-modal-generate' ).addClass( 'modal-active' );
	window.eoxiaJS.digiriskDashboard.duer.generate( response.data.args );
};

window.eoxiaJS.digiriskDashboard.duer.generate = function( args ) {
	var data = {
		action: 'digi_dashboard_generate'
	};

	var line = jQuery( '.duer-modal-generate li:not(.completed):first' );

	data.id   = line.data( 'id' );
	data.type = line.data( 'type' );

	if ( args ) {
		data.args = args;
	}

	window.eoxiaJS.request.send( line, data );
}

window.eoxiaJS.digiriskDashboard.duer.generatedSuccess = function( triggeredElement, response ) {
	jQuery( '.duer-modal-generate li:not(.completed):first' ).find( 'img' ).remove();
	jQuery( '.duer-modal-generate li:not(.completed):first' ).append( '<span class="dashicons dashicons-yes"></span>' );
	jQuery( '.duer-modal-generate li:not(.completed):first' ).addClass( 'completed' );

	if ( jQuery( '.duer-modal-generate li:not(.completed):first' ).length > 0 ) {
		window.eoxiaJS.digiriskDashboard.duer.generate( response.data.args );
	} else {
		console.log('end');
	}
};
