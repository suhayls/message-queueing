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

// retrieve all messages in queue and encode in JSON
$results = $sqlite->GetAllSMS();
$messages = [];

foreach ($results as $rs) {
    $messages[] = $rs->toArray();
}

echo json_encode($messages);