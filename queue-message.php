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

// Retrieve input from POST through filter_input
$sender = filter_input(INPUT_POST, 'sender');
$receiver = filter_input(INPUT_POST, 'receiver');
$message = filter_input(INPUT_POST, 'message');

// create SMS object with submitted data
$sms = new SMS($sender, $receiver, $message, time());
$success = $sqlite->insertMessage($sms);
if ($success) {
    // notify in JSON if successfully added 
    echo json_encode(['error'=>0,'message' => 'Message queued successfully']);
} else {
    // notify in JSON if not successful
    echo json_encode(['error'=>1,'message' => 'Message queuing failed.']);
}