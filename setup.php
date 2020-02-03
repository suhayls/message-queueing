<?php
/*
 * This file retrieves the next message from the queue and displays it in JSON
 * format or shows an error message in JSON format if the queue is empty. 
 */
namespace App;

// use composer to automatically load necessary classes
require 'vendor/autoload.php';

// set the timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Connect to the DB and create a connection object
use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteSMSDB as SQLiteSMSDB;

$sqlite = new SQLiteSMSDB((new SQLiteConnection())->connect());

// create DB table and file
$sqlite->createTable();

// check URL for GET to load dummy data
$loadDummyData = filter_input(INPUT_GET, 'dummy_data');

if ($loadDummyData == 1) {
    // load dummy data
    $sqlite->insertDummyData();
    echo "Loaded Dummy Data<br />";
}

echo "Setup Complete";