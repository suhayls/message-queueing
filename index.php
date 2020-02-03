<?php
/*
 * Show table with list of messages in queue. Added form to simulate queuing of
 * messages. 
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

// get all messages in the queue
$messages = $sqlite->getMessages();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Message Queueing</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--===============================================================================================-->	
        <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <!--===============================================================================================-->
    </head>
    <body>
        <div class="limiter">
        <h1>Messages in Queue</h1>
        <?php if (count($messages)) { ?>
            <div class="table100 ver3 m-b-110">
                <div class="table100-head">
                    <table class="main-table">
                        <thead>
                            <tr class="row100 head">
                                <th class="cell100 column1">ID</th>
                                <th class="cell100 column2">Sender</th>
                                <th class="cell100 column3">Receiver</th>
                                <th class="cell100 column4">Message</th>
                                <th class="cell100 column5">Sent Date &amp; Time</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table100-body js-pscroll ps ps--active-y">
                    <table class="main-table">
                        <tbody>
                            <?php foreach ($messages as $msg) { ?>
                                <tr class="row100 body">
                                    <td class="cell100 column1"><?php echo $msg->getMessageId() ?></td>
                                    <td class="cell100 column2"><?php echo $msg->getSender() ?></td>
                                    <td class="cell100 column3"><?php echo $msg->getReceiver() ?></td>
                                    <td class="cell100 column4"><?php echo $msg->getMessage() ?></td>
                                    <td class="cell100 column5"><?php echo date('Y-m-d H:i:s', $msg->getSentDateTime()) ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                    <!--<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 585px; right: 5px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 293px;"></div></div>-->
                </div>
            </div>
        <?php } else { ?>
            <div >No data to show.</div>
        <?php } ?>
        </div>
        <div>
            <form action="queue-message.php" method="POST">
                <table class="form">
                    <tr>
                        <td>Sender</td>
                        <td><input type="text" name="sender" /></td>
                    </tr>
                    <tr>
                        <td>Receiver</td>
                        <td><input type="text" name="receiver" /></td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td><input type="text" name="message" maxlength="160" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Submit" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <!--===============================================================================================-->	
        <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
        <!--===============================================================================================-->
        <script src="vendor/bootstrap/js/popper.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <!--===============================================================================================-->
        <script src="vendor/select2/select2.min.js"></script>
        <!--===============================================================================================-->
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script>
            $('.js-pscroll').each(function () {
                var ps = new PerfectScrollbar(this);

                $(window).on('resize', function () {
                    ps.update();
                })
            });


        </script>
        <!--===============================================================================================-->
        <script src="js/main.js"></script>
    </body>
</html>

