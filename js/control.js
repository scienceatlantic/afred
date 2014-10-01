/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */

$(function() {
    //deactivate listing
    $("#deactivateButton").click(function() {
        if(!isFieldEmpty("#deactivate") && isValidListingID("#deactivate")) {
            var confirmed = confirm("Are you sure you want to deactivate listing #" + $("#deactivate").val());
            if(confirmed) {
                $("#request").val("deactivate listing");
                $("#controlForm").submit();                
            }
        }
    });
    
    //reactivate listing
    $("#reactivateButton").click(function() {
        if(!isFieldEmpty("#reactivate") && isValidListingID("#reactivate")) {
            var confirmed = confirm("Are you sure you want to reactivate listing #" + $("#reactivate").val());
            if(confirmed) {
                $("#request").val("reactivate listing");
                $("#controlForm").submit();                
            }
        }
    });
    
    //permanently delete a listing
    $("#deleteButton").click(function() {
        if(!isFieldEmpty("#delete") && isValidListingID("#delete")) {
            var confirmed = confirm("Are you sure you want to delete listing #" + $("#delete").val());
            if(confirmed) {
                $("#request").val("delete listing");
                $("#controlForm").submit();                
            }
        }
    });
});

/**
 * Checks if a particular input field is emptry
 * @param {String} id - jQuery element selector
 * @return true if empty, false if not
 */
function isFieldEmpty(id) {
    if($(id).val().trim() == "") {
        alert("Field cannot be empty");
        return true;
    }
    return false;
}

/**
 * Checks if the input field contains a valid number
 * @param {String} id - jQuery element selector
 * @return true if valid number, false if not
 */
function isValidListingID(id) {
    if(!isNaN($(id).val())) {
        return true;
    }
    alert("That's not a valid listing");
    return false;    
}