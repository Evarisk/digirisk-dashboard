window.eoxiaJS.digiriskDashboard.site = {};

window.eoxiaJS.digiriskDashboard.site.init = function() {};
window.eoxiaJS.digiriskDashboard.site.event = function() {};

window.eoxiaJS.digiriskDashboard.site.addedSiteSuccess = function( triggeredElement, response ) {
	console.log('addedSiteSuccess');
};

window.eoxiaJS.digiriskDashboard.site.deletedSiteSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( 'tr' ).fadeOut();
};
