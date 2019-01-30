
function messageValidation() {
    var valid = false;
    var message = document.forms["autoGroupSelection"]["message"].value;
    var groupSelect = document.getElementById('selectList');
    groupSelect = groupSelect.options[groupSelect.selectedIndex].value;

    if (groupSelect === "-1"){
        alert("Please select a valid group");
    }
    else {
        if (message === "") {
            alert("Please Enter a Valid Message");
        } else {
           if (message.match(/^[^a-zA-Z0-9!.,?]+$/) === false)
           {
               alert("Invalid characters detected.");
           }
            else {
                valid = true;
               // Hide the confirm and history buttons
               var loader = document.getElementById('loader');
               loader.style.display='block';
               loader.style.color = 'green';
               var confirmButton = document.getElementById('confirmButton');
               confirmButton.style.display='none';
               var historyButton = document.getElementById('historyButton');
               historyButton.style.display='none';
               var personalizeCheck = document.getElementById('personalize')
               personalizeCheck.style.display='none';
            }
        }
    }

    //return true or false
    return valid;
}

function loader(){
    var loader = document.getElementById('loader2');
    loader.style.display='block';
    loader.style.color = 'green';
    var sendButton = document.getElementById('sendButton');
    sendButton.style.display='none';
    var cancelButton = document.getElementById('cancelButton');
    cancelButton.style.display='none';
}