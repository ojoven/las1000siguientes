var webPage = require('webpage');
var page = webPage.create();

var system = require('system');
var url = encodeURI(system.args[1]);

page.open(url, function(status) {

	setTimeout(function() {
		console.log(page.content);
		phantom.exit();
	}, 1000);
});

page.onError = function(msg, trace) {
	// Do nothing
	// uncomment to log into the console
	// console.error(msgStack.join('\n'));
};