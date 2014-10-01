/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */

var incompleteFieldsArray = null; //stores a list of all the mandatory fields that are incomplete

//if the submit button was clicked
$(function() {
	$("#equipmentForm").hide();

	$("#confirmFacility").change(function() {
		if(this.value == 1) {
			$("#equipmentForm").show();
			$("#invalidFacility").empty();
		}
		else if(this.value == 0) {
			$("#equipmentForm").hide();
			$("#invalidFacility").append("The purpose of this database is to connect owners of specialist research equipment with surplus capacity in Atlantic Canada with scientists requiring access to these resources.");
		}
		else if(this.value == -1) {
			$("#equipmentForm").hide();
			$("#invalidFacility").empty();
		}
	});

    $("#submitButton").click(function() {
        checkInput();
    });
});

/**
 * check if all the mandatory fields (and the url field if it's not empty) are complete.
 * if it is, submit the form
 */
function checkInput() {
	var formComplete       = true; 
	incompleteFieldsArray  = new Array();
	var validURL;
	var validEmail;
	var validTelephone;
	
	//checks if all mandatory fields are complete
	$(".checkInput").each(function() {
        if($(this).val().trim() == "" && !$(this).hasClass("customErrorMsg")) {
        	incompleteFieldsArray.push($(this).parent().prev().text()); //if the field is not complete, add it to the incompleteFields array           
            formComplete = false;
        }
        else if($(this).hasClass("customErrorMsg")) {
        	var fieldName = $(this).parent().prev().text();
        	switch(fieldName) {
        		case "Lab/facility website (URL):": validURL = isValidURL(); break;
        		case "Email:*": validEmail = isValidEmail(); break;
        		case "Telephone:*": validTelephone = isValidTelephone(); break;
        	}
        }
	});
	
	//if the mandatory fields are not complete, display an error message and return
	if(!formComplete || !validURL || !validEmail || !validTelephone) {
	    var incompleteFieldsString = "";
	    
	    //this loop concatenates all the incomplete fields into a single string
	    for(var index = 0; index < incompleteFieldsArray.length; index++) {
            if(incompleteFieldsString[index] != "") {
                if(index == (incompleteFieldsArray.length - 1)) {
                    incompleteFieldsString += "- " + incompleteFieldsArray[index];
                }
                else {
                    incompleteFieldsString += "- " + incompleteFieldsArray[index] + "\n";
                }                
            }	        
	    }
	    //display an error message with the complete fields
        alert("You have not completed the following mandatory fields:\n" + incompleteFieldsString);
        return;	    
    }
    
    //submits the form data
    $("#equipmentForm").submit();
}

/**
 * checks if the input field contains a valid telephone number
 * @return true if it's a valid telephone number or false if it's not
 */
function isValidTelephone() {	
	var telNum = $("#telephone").val().trim();
	if(isNaN(telNum) || telNum.length != 10) {
        incompleteFieldsArray.push("Telephone:*");
		return false;
	}
	else if(telNum < 0) {
        incompleteFieldsArray.push("Telephone:*");
        return false;	    
	}
    return true;
}

/**
 * checks if the input field contains a valid email
 * @return true if it's a valid email or false if it's not
 */
function isValidEmail() {
	//credit: http://stackoverflow.com/questions/2507030/email-validation-using-jquery
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  	var email = $("#email").val().trim(); 	
  	if(!regex.test(email) && email != "") {
        incompleteFieldsArray.push("Email:* (invalid email)");
        return false;
  	}
  	else if(email == "") {
        incompleteFieldsArray.push("Email:*");
        return false;   
  	}
    return true;
}

/**
 * checks if the input field contains a valid URL
 * @return true if it's a valid URL or false if it's not
 */
function isValidURL() {
    //http://forums.asp.net/t/1576488.aspx/1
    var regex = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	var url = $("#url").val().trim();
    if(!regex.test(url) && url != "") {
        incompleteFieldsArray.push("Lab/facility website (URL): (invalid URL)");
        return false;
    }
    return true;
}