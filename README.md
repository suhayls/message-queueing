# Message Queueing

## Brief
System to queue messages.

## Objectives:
1. HTTP POST endpoint OR CLI command to input an SMS message into the queue
2. HTTP GET endpoint OR CLI command to consume an SMS message from the queue in JSON format. With consuming I mean: read SMS from queue and delete it, so that it won't be consumed by the next reader.
3. HTTP GET endpoint OR CLI command to view all messages in the queue in JSON format
4. HTML page to view all messages in the queue

## Requirements
- Composer
- pdosqlite extension

## Setup
1. Clone files in web server directory and run `composer install`
2. Load setup.php to initialize db file (Optionally add parameter `dummy_data=1` to load dummy data into database)
3. Load index.php for HTML view of all messages with HTML form to queue new messages
4. Load consume-message.php to load next message in queue and display in JSON, deleting the message
5. Load read-messages.php to display all messages in JSON
6. Submit POST data to queue-message.php to queue up message in DB.

