$(document).ready(function() {
                  
});

function deleteNow() {
    var answer = confirm("Are you sure you want to CANCEL this order and delete all files related?");
    if (answer) {       
    }
    else {
        event.preventDefault();
    }
    return false;
}