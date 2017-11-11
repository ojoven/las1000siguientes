<?php
define('ROOT', realpath(dirname(__FILE__)));
require_once 'models/WordHtml.php';
require_once 'lib/simple_html_dom.php';

$stringHtml = WordHtml::saveHTMLAfterJavascriptLoaded('http://dle.rae.es/srv/search?m=30&w=desdeñoso');
echo $stringHtml;

$html = str_get_html($stringHtml);
foreach ($html->find('p') as $p) {
	echo $p->plaintext . PHP_EOL;
}