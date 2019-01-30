<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 7/5/2018
 * Time: 9:03 AM
 */

include('../session/sessions.php');

//$ONE = 'Eugene Foremen';
//$TWO = 'Spokane Foremen';
//$THREE = 'Reno Foremen';
//$FOUR = 'Seattle Foremen';
//$FIVE = 'Portland Foremen';
//$SIX = 'New Mexico Foremen';
//$SEVEN = 'Las Vegas Foremen';
//$EIGHT = 'All Foremen';
//$NINE = 'Eugene PM/Estimators';
//$TEN = 'Spokane PM/Estimators';
//$ELEVEN = 'Reno PM/Estimators';
//$TWELVE = 'Seattle PM/Estimators';
//$THIRTEEN = 'Portland PM/Estimators';
//$FOURTEEN = 'New Mexico PM/Estimators';
//$FIFTEEN = 'Las Vegas PM/Estimators';
//$SIXTEEN = 'All PM/Estimators';
//$SEVENTEEN = 'Everyone';
//$EIGHTEEN = 'WPI IT';

$GROUPS = ['Eugene Foremen', 'Spokane Foremen', 'Reno Foremen', 'Seattle Foremen', 'Portland Foremen', 'New Mexico Foremen', 'Las Vegas Foremen', 'All Foremen', 'Eugene PM/Est', 'Spokane PM/Est', 'Reno PM/Est', 'Seattle PM/Est', 'Portland PM/Est', 'New Mexico PM/Est', 'Las Vegas PM/Est', 'All', 'Everyone', 'WPI IT'];


if(isset($_POST['tempMessage'])){


    $selectedGroup = (int)$_POST['groupSelect'];
    echo "<script>alert($selectedGroup)</script>";
//    Confirm the selected group is not the empty category
    if ($selectedGroup !== -1) {
        $message = $_POST['message'];
//      Clean up the message of all special characters
        $message =preg_replace("/[^a-zA-z0-9!,?.: ]/", "", $message);
//      Confirm that message is of a good length
        $messageLength = strlen((string)$message);

        if ($messageLength > 5){
            if(isset($_POST['personalize'])){
                $_SESSION['personalize'] = "yes";
            }
            $contacts = array();

            //These strings are concat. when calling the powershell script
            $cmd = "powershell.exe -command ";
            $script = "C:\Bitnami\wampstack-7.1.21-0\apache2\htdocs\logic\contacts.ps1";
            //Function to execute the powershell script
            $contacts = execContacts($cmd, $script, $selectedGroup);

            $contactsLength = sizeof($contacts);
            //Ensure there are records in the array
            if ($contactsLength >= 1){
                $names = array();
                $phoneNumbers = array();

                //Get half way point where phoneNumbers end and names begin
                $contactsLengthHalf = $contactsLength/2;

                //Determine which group was selected from drop down

                //Foremen Groups
                if ($selectedGroup >=1 && $selectedGroup <= 17) {
                    $selectedGroupName = $GROUPS[$selectedGroup];
                }

                else{
                    $_SESSION['error'] = "Something went wrong. Please try again. Wrong group selection";
                    header('Location: ../index.php');
                }

                //Split up the contacts array into phoneNumbers and Names
                for($i=0;$i<=$contactsLengthHalf-1;$i++){
                    $phoneNumber = $contacts[$i];
                    $phoneNumber = '+1' . preg_replace("/[^0-9]/", "", $phoneNumber);
                    array_push($phoneNumbers,$phoneNumber);
                }

                for($i=$contactsLengthHalf;$i<=$contactsLength-1;$i++){
                    $name = $contacts[$i];
                    array_push($names,$name);
                }



                //Check to see if there are an equal number of phone numbers and names so were are not one off
                if (count($phoneNumbers) !== count($names)){
                    $_SESSION['error'] = "Something went wrong. Please try again. Phone numbers and names do not match up";
                }

                else {
                    $_SESSION['names'] = $names;
                    $_SESSION['phoneNumbers'] = $phoneNumbers;
                    $_SESSION['selectedGroup'] = $selectedGroup;
                    $_SESSION['selectedGroupName'] = $selectedGroupName;
                    $_SESSION['message'] = $message;
                    $_SESSION['numOfMessages'] = sizeof($phoneNumbers);
                }

                header('Location: ../index.php');
            }

            else{
                $_SESSION['error'] = "Something went wrong. It seems like there is no one in that group";
                header('Location: ../index.php');
            }
        }
        else{
            $_SESSION['error']='Please ensure that the message is valid';
            header('Location: ../index.php');
        }
    }
    else{
        $_SESSION['error'] = "Please make an appropriate group selection";
        header('Location: ../index.php');
    }
}

elseif (isset($_POST['removeContacts'])){
    unlink('uploads/Contacts-Employees.csv');
    unset($_SESSION['fileUpload']);
    header('Location: index.php');
}

else{
    header('Location: index.php');
}

header('Location: index.php');


//Function to execute script which grabs the appropriate members
function execContacts($cmd, $script, $selectedGroup){
    $execScript = shell_exec($cmd.' '.$script.' '.escapeshellarg($selectedGroup));
    $output = preg_split('/\s+/', trim($execScript));
    //
    //exec("powershell.exe C:\Bitnami\wampstack-7.1.20-1\apache2\htdocs\logic\contacts.ps1");
    return $output;

}
