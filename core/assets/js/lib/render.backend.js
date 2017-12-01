window.paypalParty.render = {};

window.paypalParty.render.init = function() {
	window.paypalParty.render.event();
};

window.paypalParty.render.event = function() {};

window.paypalParty.render.callRenderChanged = function() {
	var key = undefined;

	for ( key in window.paypalParty ) {
		if ( window.paypalParty[key].renderChanged ) {
			window.paypalParty[key].renderChanged();
		}
	}
};
