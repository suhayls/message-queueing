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

// get oldest message from DB
$message = $sqlite->getOldestSMS();

if ($message !== null) {
    // if message was successfully retrieved, convert SMS object to an array and
    // return in JSON format and delete the message from the DB. 
    $sqlite->deleteMessage($message->getMessageId());
    echo json_encode($message->toArray());
} else {
    // else show an error in JSON format.
    echo json_encode(['error' => 1, 'error_message' => 'No More Messages in Queue']);
}