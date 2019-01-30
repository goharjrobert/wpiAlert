
<?php
    include('session/sessions.php');
    include('styles/header.html');
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/favicon-32x32.png">
    <title>Alert System</title>
</head>
<body>

<!--    Session Error Messages-->
    <?php if(isset($_SESSION['error'])): ?>
        <p class="alert alert-danger errorMessage"><?php echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?></p>
    <?php endif; ?>

<!--    Title and Image-->
    <div class="header">
        <div class="row">
            <img class="img" src="img/logo-large.png" width="300">
        </div>
        <div class="row">
            <p class="display-1">Alert System</p>
        </div>
    </div>

<!--    Drop down and the message text box-->
    <div class="container">
        <div class="row">
            <form action="logic/logic.php" method="POST" class="col-md">
                <button type="submit" class="form-control btn btn-outline-primary <?php if($_SESSION['category'] == 'Foremen'){echo 'active';} ?>" name="categoryForemen">Foremen</button>
            </form>

            <form action="logic/logic.php" method="POST" class="col-md">
                <button type="submit" class="form-control btn btn-outline-primary <?php if($_SESSION['category'] == "Project Managers and Estimators"){echo 'active';} ?>" name="categoryPMEst">Project Manager/Estimator</button>
            </form>

            <form action="logic/logic.php" method="POST" class="col-md">
                <button type="submit" class="form-control btn btn-outline-primary <?php if($_SESSION['category'] == 'All'){echo 'active';} ?>" name="categoryAll">Everyone</button>
            </form>
        </div>

        <?php if(isset($_SESSION['category'])): ?>
            <form name="autoGroupSelection" action="logic/checkContacts.php" onsubmit="return messageValidation(this);" method="POST">
                <select class="form-control form-control-lg" name="groupSelect" id="selectList" <?php if(isset($_SESSION['message']) && isset($_SESSION['selectedGroup'])): echo 'disabled'; endif; ?>>
                    <option value="-1" id="default"><b><?php echo $_SESSION['category'] ?>:</b> Select Group</option>
                    <?php if($_SESSION['category'] == 'Foremen'): ?>
                        <option value="0">Eugene Foremen</option>
                        <option value="1">Spokane Foremen</option>
                        <option value="2">Reno Foremen</option>
                        <option value="3">Seattle Foremen</option>
                        <option value="4">Portland Foremen</option>
                        <option value="5">New Mexico Foremen</option>
                        <option value="6">Las Vegas Foremen</option>
                        <option value="7">All Foremen</option>
                    <?php elseif($_SESSION['category'] == "Project Managers and Estimators"): ?>
                        <option value="8">Eugene PM/Estimators</option>
                        <option value="9">Spokane PM/Estimators</option>
                        <option value="10">Reno PM/Estimators</option>
                        <option value="11">Seattle PM/Estimators</option>
                        <option value="12">Portland PM/Estimators</option>
                        <option value="13">New Mexico PM/Estimators</option>
                        <option value="14">Las Vegas PM/Estimators</option>
                        <option value="15">All PM/Estimators</option>
                    <?php elseif($_SESSION['category'] == 'All'): ?>
                        <option value="16">Everyone</option>
                    <?php endif; ?>
                    <option value="17">WPI IT (For testing only)</option>
                </select>
                <br>
                <div class="form-group">
                    <textarea class="form-control form-control-lg messageField" id="inputMessage" name="message" placeholder="Message"
                        <?php if(isset($_SESSION['message']) && isset($_SESSION['selectedGroup'])): echo 'disabled'; endif; ?> rows="3"></textarea>
                </div>
                <div class="row justify-content-center" id="personalize">
                    <p data-toggle="tooltip" title="Message will appears as: 'Hey Rich! .....' "><input type="checkbox" name="personalize" value="personalize"> Personalize your message</p>
                </div>
                <!-- Raised button with ripple -->
                <!-- Disable button when user is in selection mode-->
                <div class="row container justify-content-center">
                    <i class="fa fa-spinner fa-spin" style="font-size:48px; display: none; color=green;" id="loader"></i>
                </div>
                <div class="row justify-content-center">
                    <button id="confirmButton" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect messageConfirm" name="tempMessage"
                        <?php if(isset($_SESSION['message']) || isset($_SESSION['selectedGroup']) || isset($_SESSION['numOfMessages'])): echo 'disabled'; endif; ?>>
                        Confirm
                    </button>
                </div>
            </form>

<!--    Ask user to confirm selection of group to text-->
            <?php if(isset($_SESSION['message']) && isset($_SESSION['selectedGroup'])): ?>
                <div class="mdl-card mdl-shadow--2dp card text-center through mdl-shadow--16dp sendMessageCard">
                    <div class="card-body">
                        <h5 class="card-title">Message Confirmation</h5>
                        <p class="card-text"><b>To:</b> <?php echo $_SESSION['selectedGroupName']; ?></p>
                        <p><b>Group Members: </b> <?php echo $_SESSION['numOfMessages'];?></p>
                        <p><b>Message: </b> <?php echo $_SESSION['message']; ?></p>
                        <hr>
                        <div class="row container justify-content-center">
                            <i class="fa fa-spinner fa-spin" style="font-size:48px; display: none; color=green;" id="loader2"></i>
                        </div>
                        <div class="row">
                            <form action="logic/send-message.php" method="POST" class="col-md" onsubmit="loader();">
                                <button type="submit" class="btn btn-outline-success col-12" name="send" id="sendButton">Send It</button>
                            </form>
            <!--                Clears all the sessions-->
                            <form action="logic/logic.php" method="POST" class="col-md">
                                <button type="submit" class="btn btn-outline-danger col-12" id="cancelButton" name="cancel">Cancel</button>
                            </form>
                        </div>
                        <?php if(isset($_SESSION['personalize'])): ?>
                            <div class="container row justify-content-center">
                                <p><i>Message will be personalized.</i></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

<!--    Display success message for user and all session variables are unset-->
            <?php elseif(isset($_SESSION['selectedGroupName']) && isset($_SESSION['numOfMessages'])): ?>
                <div class="mdl-card mdl-shadow--2dp card text-center through mdl-shadow--16dp sendMessageCard">
                    <div class="card-body">
                        <h5 class="card-title">Message Confirmation</h5>
                        <p><i class="fas fa-fw fa-check-circle checked"></i></p>
                        <p class="card-text">Your message to <b><?php echo $_SESSION['selectedGroupName']; ?> </b>was sent successfully.</p>
                        <p><b>Number of messages sent: </b> <?php echo $_SESSION['numOfMessages']; ?></p>
                        <p>Wait for a few minutes for all messages to go through</p>
                        <hr>
                        <div class="row">
                            <button type="button" class="btn btn-outline-primary col-12" name="send" onclick="window.location.reload();">Okay!</button>
                        </div>

                        <?php
                            unset($_SESSION['selectedGroupName']);
                            unset($_SESSION['numOfMessages']);
                            session_unset();
                            session_destroy();
                            ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="container">
            <div class="row justify-content-center">
                <button id="historyButton" class="btn btn-default btn-lg" type="button" data-toggle="collapse" data-target="#historyCollapse" aria-expanded="false" aria-controls="historyCollapse">
                    History
                </button>
            </div>
        </div>
        <div class="collapse" id="historyCollapse">
            <div class="card card-body" style="background-color: transparent">
                <table class="table table-hover" style="text-align: center">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Group</th>
                        <th scope="col">Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
//                  Opening up history.csv
//                  Looping through file and putting it in a table
                        $f = fopen("logic/history/history.csv", "r");
                        while (($line = fgetcsv($f)) !== false) {
                            echo "<tr>";
                            foreach ($line as $cell) {
                                echo "<td>" . htmlspecialchars($cell) . "</td>";
                            }
                            echo "</tr>\n";
                        }
                        fclose($f);

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>






