/** CARDS **/
function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function appendStylesToIframe($selector) {
	var $head = $selector.contents().find("head");
	console.log('works?');
	$head.append($("<link/>",
		{ rel: "stylesheet", href: "/iframe.css", type: "text/css" }));
}