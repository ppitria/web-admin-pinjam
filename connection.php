<?php
require_once __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

$mongoUrl = 'mongodb+srv://ppitria05:mongo05@cluster0.tceeqol.mongodb.net/';
$databaseName = 'pinjamDB';

$client = new Client($mongoUrl);
$database = $client->selectDatabase($databaseName);
?>