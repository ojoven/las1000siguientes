/** CARDS **/
function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function appendStylesToIframe($selector) {
	var $head = $selector.find("head");
	console.log($head);
	$head.append($("<link/>",
		{ rel: "stylesheet", href: "/css/iframe.css", type: "text/css" }));
}