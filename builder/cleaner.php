<?php
/** CLEANER **/
// We'll use this file to clean the words from the list
// Basically, we'll remove duplicates from the data/words file

// Define builder ROOT path
define('ROOT', realpath(dirname(__FILE__)));

// Let's define the paths
$pathToWords = ROOT . '/data/words';

// Get and parse the word list
$wordListString = file_get_contents($pathToWords);
$words = explode(PHP_EOL, $wordListString);

// Remove duplicates
$uniqueWords = array_unique($words);

// Parse to string and resave the file into the path
$uniqueWordListString = implode(PHP_EOL, $uniqueWords);
file_put_contents($pathToWords, $uniqueWordListString);

// Log
echo "Cleaned" . PHP_EOL . '=============' . PHP_EOL;
echo implode(PHP_EOL, array_diff_assoc($words, $uniqueWords)) . PHP_EOL;