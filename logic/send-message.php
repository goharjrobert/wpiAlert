<?php
///**
// * Created by PhpStorm.
// * User: GRobert
// * Date: 6/26/2018
// * Time: 8:28 AM
// */
include('../session/sessions.php');

//Get twilio SDK initialized
require __DIR__ . '/vendor/autoload.php';
use Twilio\REST\Client;

//If user confirms to send message
if(isset($_POST['send'])){
    $phoneNumbers = $_SESSION['phoneNumbers'];
    $names = $_SESSION['names'];
    $message = $_SESSION['message'];

    //  Unset all variables except of 'numOfMessages'
    unset($_SESSION['names']);
    unset($_SESSION['phoneNumbers']);
    unset($_SESSION['message']);
    unset($_SESSION['selectedGroup']);

    //Add Michelle in the phone numbers array
    //array_push($phoneNumbers, '+15037993850');
    array_push($phoneNumbers, '+19712916200');
    //array_push($phoneNumbers, '+15036199162');

    //Add Michelle in the names array
    //array_push($names, 'Michelle');
    array_push($names, 'Gohar');
    //array_push($names, 'Rich');

//  Call function to send the message and the parameters are set through session variables
    twilioMessage($names, $phoneNumbers, $message);

//    Insert record into file called history.csv
    historyMessage($_SESSION['selectedGroupName'], $message);
    header('Location: ../index.php');
}

//if user did not press the send button redirect back to index
else{
    $_SESSION['error'] = "Something went wrong";
    header('Location: index.php');
}

//twilioMessage is a function which takes the array of names and phone numbers
//It iterates through those arrays and sends text messages out to each
//group member
function twilioMessage($names, $phoneNumbers, $message){

    $greeting = "";
    // For testing with just my phone numbers
    //$names = ['Gohar', 'Gohar'];
    //$phoneNumbers = ['+15038105042', '+19712916200'];

    $sid = 'ACa568fdcd8afaf1f277db973ce378a5ad';
    $token = '808e34fcc6e4687a07023e8e0ffaa218';

//    for ($x=0; $x <= sizeof($names)-1;$x++) {
    for ($x=0; $x < sizeof($names);$x++) {
        if(isset($_SESSION['personalize'])){
            $greeting = "Hey $names[$x], ";
        }
//        echo "<b>User: </b>" . $names[$x] . '<br>';
//        echo "<b>Phone Number: </b>" . $phoneNumbers[$x] . '<br>';
//        echo $greeting . $message . "<br>";
//        echo "________________________________________________<br>";


//        Creates a new instance of a client and sends the text messages
        $client = new Client($sid,$token);
        $client->messages->create($phoneNumbers[$x],
            array(
                'from' => '+19718036499',
                'body' => $greeting . $message
            )
        );
    }

}

//After all messages have been send
//the historymessage functions makes an entry into
//the history.csv file for the the table in index.php to
//reference
function historyMessage($groupName, $message){
    $date = date("F j, Y, g:i a");

    $list = array(array($date, $groupName, $message));

    $fp = fopen('history/history.csv', 'a');
    foreach ($list as $fields) {
        fputcsv($fp, $fields);
    }
}

//?>