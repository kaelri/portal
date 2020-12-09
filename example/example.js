portalExample = new Vue({
	
	el: '#portal-example',

	data: {
		myInput: '',
	},

	template: `<form id="portal-example" class="form-inline" v-on:submit.native.prevent="ping()">

		<div class="form-row">

			<div class="col">

				<input v-model="myInput" class="form-control mb-2">

				<button v-on:click.prevent="callPing" class="btn btn-primary mb-2">Ping</button>

				<button v-on:click.prevent="callError" class="btn btn-secondary mb-2">Error</button>

			</div>

		</div>

	</form>`,

	methods: {

		// CALL: PING
		callPing: function() {

			var body = {
				myInput: this.myInput,
				testData: { 
					aString: 'Zephyrs and stuff.',
					anInt: 17,
					aFloat: 3.59,
					anArray: [
						1,
						2,
						4,
						'Excellent',
						{
							alpha: 'beta',
							gamma: 'delta',
							epsilon: 9,
						}
					]
				}
			}

			console.log( 'sent: ', body );
			
			var call = new PortalCall({
				endpoint: 'portal/v1/ping',
				body:     body,
				callback: this.callback
			}).send();

		},

		// CALL: ERROR
		callError: function() {

			var call = new PortalCall({
				endpoint: 'portal/v1/error',
				callback: this.callback
			}).send();

		},

		callback: function( call ) {
			window.alert( call.message );
			console.log( 'received: ', call );
		},

	}

});
