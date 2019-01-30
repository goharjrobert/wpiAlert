<?php
/**
 * Created by PhpStorm.
 * User: GRobert
 * Date: 7/31/2018
 * Time: 9:33 AM
 */
include('../session/sessions.php');

//When user decides to cancel the selection, unset all session variables and destroy the session
if (isset($_POST['cancel'])){
    unset($_SESSION['message']);
    unset($_SESSION['names']);
    unset($_SESSION['phoneNumbers']);
    unset($_SESSION['selectedGroupName']);
    unset($_SESSION['selectedGroup']);
    session_unset();
    session_destroy();

}

if(isset($_POST['categoryForemen'])){
    $_SESSION['category'] = "Foremen";
}


elseif(isset($_POST['categoryPMEst'])){
    $_SESSION['category'] = "Project Managers and Estimators";
}


elseif(isset($_POST['categoryAll'])){
    $_SESSION['category'] = "All";
}

//Always navigate back to index
header('Location: index.php');