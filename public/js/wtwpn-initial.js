jQuery( document ).ready(function() {
	var config = {
		apiKey: wtwpnfirebase.WTWPN_Setting_api,
		authDomain: wtwpnfirebase.WTWPN_Setting_project + ".firebaseapp.com",
		databaseURL: "https://" + wtwpnfirebase.WTWPN_Setting_project + ".firebaseio.com",
		storageBucket: wtwpnfirebase.WTWPN_Setting_project + ".appspot.com",
		messagingSenderId: wtwpnfirebase.WTWPN_Setting_sender,
	};
	firebase.initializeApp(config);
	// Retrieve Firebase Messaging object.
	const messaging = firebase.messaging();
	
	// [START refresh_token]
    // Callback fired if Instance ID token is updated.
	messaging.onTokenRefresh(() => {
		messaging.getToken().then((refreshedToken) => {
			console.log('Token refreshed.');
			// Indicate that the new Instance ID token has not yet been sent to the
			// app server.
			setTokenSentToServer(false);
			// Send Instance ID token to app server.
			sendTokenToServer(refreshedToken);
			// [START_EXCLUDE]
			jpInit();
			// [END_EXCLUDE]
		}).catch((err) => {
			console.log('Unable to retrieve refreshed token ', err);
			showToken('Unable to retrieve refreshed token ', err);
		});
	});
	
	// Initialize script 
	
	jpInit();
	
	// Main Function 
	
	function jpInit() { 
		if (!isTokenSentToServer()) {
			// On load register service worker
			if ('serviceWorker' in navigator) { 
				navigator.serviceWorker.register(wtwpnfirebase.sw_url).then((registration) => {
				  // Successfully registers service worker
				  console.log('ServiceWorker registration successful with scope: ', registration.scope);
				  messaging.useServiceWorker(registration);
				})
				.then(() => {
				  // Requests user browser permission
				  return messaging.requestPermission();
				})
				.then(() => { 
				  // Gets token
				  return messaging.getToken();
				})
				.then((token) => {
					if (token) {
						sendTokenToServer(token);
					} else {
						setTokenSentToServer(false);
					}
				})
				.catch((err) => {
				  console.log('ServiceWorker registration failed: ', err);
				});
			}
		}
	}
});
	
function sendTokenToServer(token) {
	if (!isTokenSentToServer()) {
	  console.log('Sending token to server...');
	  var storeurl = wtwpnfirebase.baseurl;
	  jQuery.ajax({
		type: 'post',
		url: storeurl,
		data: {key: token, browser: wtwpnfirebase.browser, Userid: wtwpnfirebase.userid, action: 'wtwpn_subscriber_save'},
		success: (data) => {
		  console.log('Success ', data);
		  setTokenSentToServer(true);
		},
		error: (err) => {
		  console.log('Error ', err);
		}
	  })
	} else {
	  console.log('Token already sent to server so won\'t send it again ' +
		  'unless it changes');
	}
}

function isTokenSentToServer() {
	return window.localStorage.getItem('sentToServer') === '1';
}

function setTokenSentToServer(sent) {
	window.localStorage.setItem('sentToServer', sent ? '1' : '0');
}
