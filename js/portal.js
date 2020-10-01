// PORTAL
// ======

// CLIENT
// ------

Portal = (function(){

	var data = portalInitialData.data;
	
	function getData( key ){

		if ( !key ) return data;

		return data[ key ];

	}

	return {
		getData: getData
	};

}());

// CALL
// ----

PortalCall = function( config ) {

	// CONFIG
	this.url          = config.url      || Portal.getData('restURL');
	this.method       = config.method   || '';
	this.body         = config.body     || null;
	this.callback     = config.callback || false;
	this.silent       = config.silent   || false;
	this.timeout      = config.timeout  || 300000;

	// RESULTS
	this.sent         = false;
	this.success      = null;
	this.status       = null;
	this.code         = null;
	this.message      = null;
	this.data         = {};
	
	// RAW
	this.response     = null;
	this.xhr          = null;

	this.send = function() {

		var self = this;

		if ( this.sent ) return false;

		if ( !this.silent ) this.showLoading();

		var settings = {
			type:     'GET',
			url:      this.url + this.method,
			timeout:  this.timeout,
			dataType: 'json',
			complete: function( xhr, textStatus ) {

				// Save raw data from jQuery.
				self.xhr      = xhr;
				self.response = xhr.responseJSON || {};
				
				// Use status info from WP response object, if available. Else, default to info from jQuery.
				self.success  = ( textStatus == 'success' ); // simple true-or-false check for a successful call. If not successful, look at "status" and "code" for more detailed diagnostics.
				self.status   = self.response.status  || xhr.status;
				self.code     = self.response.code    || textStatus; // "success", "notmodified", "nocontent", "error", "timeout", "abort", or "parsererror"
				self.message  = self.response.message || xhr.statusText;
				self.data     = self.response.data    || {};

				if ( !self.silent ) self.hideLoading();

				if ( self.callback ) self.callback( self );

			},
		}

		// Switch to POST method automatically if body data is present.
		if ( this.body ) {
			settings.type    = 'POST';
			settings.headers = { 'Content-Type': 'application/json' };
			settings.data    = JSON.stringify( this.body );
		}
		
		jQuery.ajax( settings );

		this.sent = true;
		return      true;

	}

	// Require explicit command to re-send a call that has already been sent.
	this.resend = function() {
		this.sent = false;
		this.send();
	}

	this.showLoading = function() {
		jQuery('body').addClass('portal-is-loading');
	}

	this.hideLoading = function() {
		jQuery('body').removeClass('portal-is-loading');
	}

	return this;

}

// VUE EVENT BUS
Vue.prototype.$portal = new Vue();
