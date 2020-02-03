<?php

namespace App;

/*
 * Abstract class for Message objects. Message objects should not be modifiable
 * once created, except for the message id which is retrieved from the database
 * after the object is stored.
 */
abstract class Message {

    /* 
     * The ID of the message retrieved from the database after it is stored.
     * 
     * @param int message_id
     */
    private $message_id;
    
    /*
     * The sender of the message. For an SMS it will be a phone number or an
     * email address for an email message.
     * 
     * @param string $sender
     */
    private $sender;
    
    /*
     * The receiver of the message. For an SMS it will be a phone number or an
     * email address for an email message.
     * 
     * @param string $receiver
     */
    private $receiver;
    
    /*
     * The message itself. 
     * 
     * $param string $message
     */
    private $message;
    
    /*
     * The unix timestamp of the time the message was sent. 
     * 
     * @param int $sentDateTime
     */
    private $sentDateTime;

    /*
     * Public constructor of message class. $message_id is optional 
     * 
     * @param int $message_id
     * @param string $sender
     * @param string $receiver
     * @param string $message
     * @param int $sentDateTime
     */
    public function __construct($sender, $receiver, $message, $sentDateTime, $message_id = null) {
        $this->message_id = $message_id;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
        $this->sentDateTime = $sentDateTime;
    }
    
    /*
     * Function to set the Message ID
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->message_id = $id;
    }
    
    /*
     * Function to return the message in an array
     * @return array $message
     */
    public function toArray() {
        $message = [
            'message_id' => $this->message_id,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'message' => $this->message,
            'sentDateTime' => $this->sentDateTime,
        ];
        
        return $message;
    }
    
    /*
     * Getter to retrieve message_id
     * 
     * @return int $message_id
     */
    public function getMessageId() {
        return $this->message_id;
    }
    
    /*
     * Getter to retrieve sender
     * 
     * @return string $sender
     */
    public function getSender() {
        return $this->sender;
    }
    
    /*
     * Getter to retrieve receiver
     * 
     * @return string $receiver
     */
    public function getReceiver() {
        return $this->receiver;
    }
    
    /*
     * Getter to retrieve message
     * 
     * @return string $message
     */
    public function getMessage() {
        return $this->message;
    }
    
    /*
     * Getter to retrieve sentDateTime
     * 
     * @return int $sentDateTime
     */
    public function getSentDateTime() {
        return $this->sentDateTime;
    }
}
