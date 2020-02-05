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
use App\Models\SMS as SMS;

$sqlite = new SQLiteSMSDB((new SQLiteConnection())->connect());

// Retrieve input from POST through filter_input
$sender = filter_input(INPUT_POST, 'sender', FILTER_SANITIZE_STRING);
$receiver = filter_input(INPUT_POST, 'receiver', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

if (!preg_match("/^[+]*[0-9]{7,11}$/", $sender) || !preg_match("/^[+]*[0-9]{7,11}$/", $receiver)) {
    // sender or receiver number not valid
    echo json_encode(['error' => 1, 'message' => 'Message queuing failed. Invalid phone number.']);
} else if ($message == "") {
    // message is empty
    echo json_encode(['error' => 1, 'message' => 'Message queuing failed. Message is empty.']);
} else {
// create SMS object with submitted data
    $sms = new SMS($sender, $receiver, $message, time());
    $success = $sqlite->insertMessage($sms);
    if ($success) {
        // notify in JSON if successfully added 
        echo json_encode(['error' => 0, 'message' => 'Message queued successfully']);
    } else {
        // notify in JSON if not successful
        echo json_encode(['error' => 1, 'message' => 'Message queuing failed.']);
    }
}