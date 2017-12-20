<?php
/** BUILDER **/
// We'll use this file to build the database
// We'll retrieve the data from Wiktionary; we'll first store the HTMLs so we don't need to make requests every time we need to scrape the Wiktionary

// Define builder ROOT path
define('ROOT', realpath(dirname(__FILE__)));

// Let's load all the necessary libraries and models

// LIBRARIES
require_once 'lib/simple_html_dom.php'; // Simple HTML Dom: Scraping functionalities
require_once 'lib/Functions.php';
require_once 'lib/database.php';

// MODELS
require_once 'models/Builder.php';
require_once 'models/WordHtml.php';
require_once 'models/Word.php';

// Build the database
$builderModel = new Builder();
$builderModel->saveHtmlWords();
$builderModel->saveWordsToDatabase();