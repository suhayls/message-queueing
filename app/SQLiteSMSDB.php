<?php

namespace App;

use App\Models\SMS as SMS;

/**
 * SQLite SMS DB Management class. This class manages the interaction
 * between the frontend and the SMS DB Storage.
 */
class SQLiteSMSDB {

    /**
     * PDO object to hold the DB connection. Takes an instance of PDO sqlite 
     * connection. 
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructor take a pdo connection.
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Creates the database table necessary to store the data. This method
     * should only be called as part of the setup process. 
     */
    public function createTable() {
        $command = 'CREATE TABLE IF NOT EXISTS messages (
                        message_id   INTEGER PRIMARY KEY,
                        sender TEXT NOT NULL,
                        receiver TEXT NOT NULL,
                        message TEXT NOT NULL,
                        sentDateTime DATETIME NOT NULL)';
        // execute the sql command to create new table
        $this->pdo->exec($command);
    }

    /*
     * Takes an SMS Message and inserts it into the table. If successful, it 
     * will return the ID of the SMS inserted or 0 if a failure has occured.
     * 
     * @param SMS $sms An object of type message
     * @return bool
     */
    public function insertMessage($sms) {
        $sql = 'INSERT INTO messages(sender, receiver, message, sentDateTime) '
                . 'VALUES(:sender,:receiver,:message,:sentDateTime)';
        //Prepares the SQL statement against existing tables
        $stmt = $this->pdo->prepare($sql);
        
        //Execute the statement and insert the SMS in the table. 
        $stmt->execute([
            ':sender' => $sms->getSender(),
            ':receiver' => $sms->getReceiver(),
            ':message' => $sms->getMessage(),
            ':sentDateTime' => time(),
        ]);
        
        // check for error code and return false if an error has occured
        if ($this->pdo->errorCode() == "00000") {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Function to insert dummy data into the table. This function should only
     * be called as part of the setup process to test the database connection 
     * and table.
     */
    public function insertDummyData() {
        $messages = [new SMS('+6012958193', 
                '+6013918250', 
                'This is an SMS Message.', 
                time() - 2000),
            new SMS('+6011531958', 
                    '+6014829135', 
                    'Another SMS message queued. ', 
                    time() - 1000),
            new SMS('+6019511955', 
                    '+6014512849', 
                    'Also an SMS message that has been queued for later delivery.', 
                    time())];

        // loop thru $messages array and insert into DB one by one
        foreach ($messages as $msg) {
            $sql = 'INSERT INTO messages(sender, receiver, message, sentDateTime) '
                    . 'VALUES(:sender,:receiver,:message,:sentDateTime)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':sender' => $msg->getSender(),
                ':receiver' => $msg->getReceiver(),
                ':message' => $msg->getMessage(),
                ':sentDateTime' => $msg->getSentDateTime(),
            ]);
        }
    }

    /*
     * Function to get all SMS in the database. Will return an array of 
     * SMS objects. 
     * 
     * @return array 
     */
    public function GetAllSMS() {
        $stmt = $this->pdo->query('SELECT * '
                . 'FROM messages');
        $messages = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $messages[] = new SMS($row['sender'],
                    $row['receiver'],
                    $row['message'],
                    $row['sentDateTime'],
                    $row['message_id']);
        }
        return $messages;
    }

    /*
     * Function to retrieve the oldest SMS from the database and returns
     * an SMS object. 
     * 
     * @return SMS
     */
    public function getOldestSMS() {
        //create an SQL query and order by the sentDateTime in ascending order
        //to get the next SMS to be sent out (FIFO)
        $stmt = $this->pdo->query('SELECT * '
                . 'FROM messages ORDER BY sentDateTime ASC LIMIT 1');
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            $SMS = new SMS($result['sender'],
                    $result['receiver'],
                    $result['message'],
                    $result['sentDateTime'],
                    $result['message_id']);
            return $SMS;
        } else {
            return null;
        }
    }

    /*
     * Function to delete an SMS from the database. Takes an id and 
     * attempts to perform a deletion
     * 
     * @param int $id
     */
    public function deleteMessage($id) {
        $sql = 'DELETE FROM messages WHERE message_id = :message_id';
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([':message_id' => $id]);
    }
}
