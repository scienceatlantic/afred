<?php
/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */
 
require "includes/variables.php";
session_start();
require "includes/db-equip-connect.php"; //database connection settings
define("LIST_ALL", 				0); //defined value to represent the "list all" feature
define("BASIC_SEARCH", 			1); //defined value to represent the "basic search" feature
define("LIST_ALL_DEACTIVATED", 	2); //define value to represent the "list all deactivated" feature

/* this portion of code makes all GET, POST, COOKIE, and REQUEST variables database safe.
 * if the get_magic_quotes_gpc extension is active (active by default in older PHP configs), strip all the slashes and use
 * MySQL's real_escape_string function instead. it also removes HTML tags and trims whitespace
 */
if(get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v))  {
                $process[$key][$db->real_escape_string(trim(stripslashes($k)))] = $v;
                $process[] = &$process[$key][trim($k)];
            } 
            else {
                $process[$key][$db->real_escape_string(trim(stripslashes($k)))] = $db->real_escape_string(trim(stripslashes($v)));
            }
        }
    }
    unset($process);
}
//does the same thing above except without stripping the slashes first since the extension is not active
else {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process))  {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][$db->real_escape_string(trim($k))] = $v;
                $process[] = &$process[$key][trim($k)];
            } 
            else {
                $process[$key][$db->real_escape_string(trim($k))] = $db->real_escape_string(trim($v));
            }
        }
    }
    unset($process);
}

//gets the request type. the GET method is used for search queries, everything else uses the POST method
if(isset($_GET['request'])) {
	$request = $_GET['request'];
}
else if(isset($_POST['request'])) {
	$request = $_POST['request'];
}
//if no request was found, kill the page
else {
	die("Invalid request");
}

//==============================================================[START] SEARCH FUNCTIONS
//basic search request
if($request == "basic search") {
	$query = $_GET['query'];	
	$query = explode(" ", $query); //Breaks query up into individual words
	
	/* affixes the sql wildcard character "%" to the start and
	 * end of each word. example "Telescope Halifax" becomes
	 * %Telescope%Halifax%
	 */
	$rows  = count($query);
	$tmpQuery   = "%";
	for($count = 0; $count < $rows; $count++) {
		$tmpQuery .= $query[$count]."%";
	}
	$query = $tmpQuery;
	
	//selects all inventory_ids that match the search query
	$results = $db->query("SELECT inventory_id FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id LIKE '".$query."' OR research LIKE '".$query."' OR institution LIKE '".$query.
						   "' OR url LIKE '".$query."' OR city LIKE '".$query."' OR province LIKE '".$query."' OR does LIKE '".$query.
						   "' OR add_info LIKE '".$query."' OR keywords LIKE '".$query."' OR contact_name LIKE '".$query.
						   "' OR position LIKE '".$query."' OR department LIKE '".$query."' OR email LIKE '".$query.
						   "' OR telephone LIKE '".$query."' OR date_posted LIKE '".$query."' OR date_updated LIKE '".$query."' ".
						   "UNION ".
						   "SELECT DISTINCT inventory_id FROM ".DB_EQUIP_LAB_LIST." WHERE lab LIKE '".$query."' OR descr LIKE '".$query."'");
	$rows = $results->num_rows;
	
	//collects all the inventory_ids and formats them into a string that looks like this: (23, 34, 54, 67) (without the brackets)
	$inventoryID = "";
	for($count = 0; $count < $rows; $count++) {
		$tmpAssocArray = $results->fetch_assoc();
		$tmpArray[$count] = $tmpAssocArray['inventory_id'];
		$inventoryID = formatInventoryID($tmpArray);
	}
	
	//uses the inventory_id string created and does a basic search
	returnSearchResults($inventoryID, BASIC_SEARCH);
}
//list all request
else if($request == "list all") {
	returnSearchResults(null, LIST_ALL);
}
//list all deactivated request
else if($request == "list all deactivated") {
	returnSearchResults(null, LIST_ALL_DEACTIVATED);
}
//==============================================================[END] SEARCH FUNCTIONS

//==============================================================[START] EQUIPMENT SUBMISSION 
//process submission request, handles the submission of new records
else if($request == "process submission") {
	$inventoryDB 	 = false; //stores all queries for the inventory database
	$labListDB 		 = false; //stores all queries for the lab_list database
	$validEntry 	 = 0; //valid entry -> set to zero, waiting for approval from the administrator
	
	//if the submission contains a list of specialized labs/equipment
	$hasLabEquipment = 0;
	for($count = 1; $count <= MAX_LAB_ENTRIES; $count++) {
		$lab = "lab".$count; //lab1, lab2, ...
		
		if(isset($_POST[$lab])) {
			if($_POST[$lab] != "") {
				$hasLabEquipment = 1;
				break;
			}
		}
		else {
			break;
		}
	}
	
	//input the form data into the inventory database
	$inventoryDB = $db->query("INSERT INTO ".DB_EQUIP_INVENTORY." (research, institution, url, city, province, does, add_info, keywords, contact_name, position, department, email, telephone, list, date_posted, date_updated, valid) VALUES ".
							  "('".$_POST['research']."', '".$_POST['institution']."', '".$_POST['url']."', '".$_POST['city']."', '".$_POST['province']."', '".$_POST['does']."', '".$_POST['addInfo']."', '".$_POST['keywords']."', '".$_POST['contactName']."', '".$_POST['position']."', '".$_POST['department']."', '".$_POST['email']."', '".$_POST['telephone']."', ".$hasLabEquipment.", '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', ".$validEntry.")");			
	
	//get the inventory_id to be used with the lab_list database
	$inventoryID = $db->insert_id;
	
	/*if the form contains a list of specialized labs/equipment AND the query into the inventory database was successful,
	 *input the list into the lab_list database. the inventory_id is used to identify which facility the equipment belongs to
	 */ 
	if($hasLabEquipment == 1 && $inventoryDB) {
		for($count = 1; $count <= MAX_LAB_ENTRIES; $count++) {
			$lab = "lab".$count; //lab1, lab2, ...
			
			//if the variable "lab#" is not set, break the loop because we've reached the end of the list
			if(!isset($_POST[$lab])) {
				break;
			}
			//if the equipment name is not blank add the entry
			else if($_POST[$lab] != "") {
				//these variables will be used to get the POST values
				$fee 	= "fee".  $count; //fee1, fee2, ...
				$guest 	= "guest".$count; //guest1, guest2, ...
				$host 	= "host". $count; //host1, host2, ...
				$descr 	= "descr".$count; //descr1, descr2, ... 
				
				//use the formatted variables to get the POST values
				$lab    = $_POST[$lab];
				$fee 	= $_POST[$fee];
				$guest 	= $_POST[$guest];
				$host 	= $_POST[$host];
				$descr 	= $_POST[$descr];
				
				//get the values of the checkboxes
				if($fee   == 1) {$fee   = 1;} else if($fee == 2) {$fee = 2;} else {$fee = 0;}
				if($guest == 1) {$guest = 1;} else {$guest = 0;}			
				if($host  == 1) {$host  = 1;} else {$host  = 0;}
				
				//input into the lab_list database
				$labListDB = $db->query("INSERT INTO ".DB_EQUIP_LAB_LIST." (inventory_id, lab, fee, guest, host, descr) VALUES ".
										"(".$inventoryID.", '".$lab."', ".$fee.", ".$guest.", ".$host.", '".$descr."')");
				
				//if the query failed, delete the data from BOTH databases and end the loop
				if(!$labListDB) {
					$db->query("DELETE FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id='".$inventoryID."'");
					$db->query("DELETE FROM ".DB_EQUIP_LAB_LIST." WHERE inventory_id='".$inventoryID."'");
					$inventoryDB = false;
					break;
				}
			}
		}
	}
	
	/*
	 * mails the form user and science atlantic to notify them of a successful new record.
	 * only emails them if the entry was successful
	 */
	if($inventoryDB) {
		//generates a random string to be used as a validation key for view, approval, and/or deletion
		//credit: http://www.lateralcode.com/creating-a-random-string-with-php/
		$chars = DB_EQUIP_VALIDATION_VALID_KEY_CHARS;	
		$size = strlen($chars);
		for($index = 0; $index < DB_EQUIP_VALIDATION_KEY_LENGTH; $index++) {
			$validationKey .= $chars[rand(0, $size - 1)];
		}
		$db->query("INSERT INTO ".DB_EQUIP_VALIDATION." (inventory_id, validation_key) VALUES (".$inventoryID.", '".$validationKey."')");
		
		//email to the submitter
		require "includes/phpmailer/class.phpmailer.php";
		$mail             = new PHPMailer();
		$mail->SetFrom(FROM_EMAIL, FROM_EMAIL_NAME);
		$mail->AddReplyTo(REPLY_TO_EMAIL, REPLY_TO_EMAIL_NAME);
		$mail->AddAddress($_POST['email'], $_POST['contactName']);
		$mail->Subject    = EMAIL_TITLE_FOR_NEW_SUBMISSION." (#".$inventoryID.")";
		$mail->Body 	  = "Thank you for submitting information about your equipment to the ".SYSTEM_NAME.". ". 
				   		    "This database is publicly available and searchable by keyword.\n\n".
				   		    "Your entry has been sent to the administrator for approval. If your entry is approved, you will receive an email with a link to your entry for your reference.\n\n".
				   		    "If you have any comments or need to make any corrections, please contact ".CONTACT_EMAIL.".\n\n".
				   		    "Thank you.\n\n".
							ORG_NAME."\n".
							DOMAIN."\n".
							ORG_MAILING_ADDRESS."\n".
							ORG_OFFICE_PHONE;
		$mail->send();
		
		//email to admin	
		$mail             = new PHPMailer(); //declare it again to reset the settings
		$mail->SetFrom(FROM_EMAIL, FROM_EMAIL_NAME);
		$mail->AddAddress(TO_EMAIL, TO_EMAIL_NAME);
		$mail->Subject    = EMAIL_TITLE_FOR_NEW_SUBMISSION." and Waiting for Approval (#".$inventoryID.")";
		$mail->Body 	  = "A new record has been submitted into the ".SYSTEM_NAME." database and is waiting for your approval/rejection.\n\n". 
				   		    "To view the entry:\n".
				   		    EQUIPMENT_LISTING_PAGE.$inventoryID."&validation_type=view&validation_key=".$validationKey."\n\n".
				   		    "To approve the entry:\n".
				   		    EQUIPMENT_LISTING_PAGE.$inventoryID."&validation_type=approve&validation_key=".$validationKey."\n\n".
				   		    "To deactivate the entry:\n".
				   		    EQUIPMENT_LISTING_PAGE.$inventoryID."&validation_type=deactivate&validation_key=".$validationKey."\n\n".
				   		    "To reject and delete the entry:\n".
				   		    EQUIPMENT_LISTING_PAGE.$inventoryID."&validation_type=delete&validation_key=".$validationKey."\n\n".
				   		    "Thank you,\n".
							SYSTEM_NAME_ACRONYM;
		$mail->send();
	}
	
	//message to the user upon a form submission
	require "includes/header.php";
	echo '<article>';
		//if the submission was successful
		if($inventoryDB) {
			echo '<h1>Thank You!</h1>';
			echo '<p>Your entry has been sent to the administrator for approval. If your entry is approved, you will receive an email with a link to your entry for your reference.</p>';
			echo '<p>If you have any comments or need to make any corrections, please contact <a href="mailto:'.CONTACT_EMAIL.'">'.CONTACT_EMAIL_NAME.'</a>.</p>';
		}
		//if the submission failed
		else {
			echo '<h1>Submission Failed!</h1>';
			echo '<p>Sorry, there was a problem with submitting your form. Please try <a href="'.EQUIPMENT_SUBMISSION_PAGE.'">again</a>.</p>';
			echo '<p>If this error persists, please contact <a href="mailto:'.CONTACT_EMAIL.'">'.CONTACT_EMAIL_NAME.'</a>.</p>';			
		}
	echo '</article>';
	require "includes/footer.php";
}
//==============================================================[END] EQUIPMENT SUBMISSION 

//==============================================================[START] CONTROL PANEL 
//delete listing request
else if($request == "delete listing") {
	//checks if the entry exists
	$inventoryQuery = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['delete']);
	$entryExists = $inventoryQuery->num_rows;

	//if the entry exists, delete the entry
	if($entryExists != 0) {
		$db->query("DELETE FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['delete']);
		$inventoryQuery = $db->affected_rows;
		$db->query("DELETE FROM ".DB_EQUIP_LAB_LIST." WHERE inventory_id=".$_POST['delete']);		
	}
	
	//set the confirmation messages
	//if the entry was not found
	if($entryExists == 0) {
		$_SESSION['confirmation'] = "Could not find listing #".$_POST['delete']." in the database";
	}
	//if the system failed to delete the entry
	else if($inventoryQuery == 0) {
		$_SESSION['confirmation'] = "Failed to delete listing #".$_POST['delete'];
	}
	//if the system successfully delete the entry
	else {
		$_SESSION['confirmation'] = "Listing #".$_POST['delete']." successfully deleted";	
	}
	
	//redirect the user back to the control panel page
	header("Location: ".EQUIPMENT_CONTROL_PAGE);
	$db->close();
	exit();
}
//reactivate listing request
else if($request == "deactivate listing") {
	//checks if the entry exists
	$inventoryQuery = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['deactivate']);
	$entryExists = $inventoryQuery->num_rows;

	//if the entry exists, check if the entry is already active, if not reactivate the entry	
	if($entryExists != 0) {
		$inventoryQuery = $db->query("SELECT valid FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['deactivate']);
		$validValue = $inventoryQuery->fetch_assoc();
		
		//if the entry is not already active, activate it
		if($validValue['valid'] != 0) {
			$db->query("UPDATE ".DB_EQUIP_INVENTORY." SET valid=0 WHERE inventory_id=".$_POST['deactivate']);
			$inventoryQuery = $db->affected_rows;				
		}
		else {
			$inventoryQuery = -1;
		}
	}

	//set the confirmation messages
	//if the entry was not found	
	if($entryExists == 0) {
		$_SESSION['confirmation'] = "Could not find listing #".$_POST['deactivate']." in the database";
	}
	//if the entry is already active
	else if($inventoryQuery == -1) {
		$_SESSION['confirmation'] = "Error, listing #".$_POST['deactivate']." has already been deactivated";
	}
	//if the system failed to reactivate the listing
	else if($inventoryQuery == 0) {
		$_SESSION['confirmation'] = "Failed to deactivate listing #".$_POST['deactivate'];
	}
	//if the system successfully reactivated the listing
	else {
		$_SESSION['confirmation'] = "Listing #".$_POST['deactivate']." successfully deactivated";	
	}
	
	//redirect the user back to the control panel page
	header("Location: ".EQUIPMENT_CONTROL_PAGE);
	$db->close();
	exit();
}
else if($request == "reactivate listing") {
	//checks if the entry exists
	$inventoryQuery = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['reactivate']);
	$entryExists = $inventoryQuery->num_rows;

	//if the entry exists, check if the entry is already inactive, if not deactivate the entry
	if($entryExists != 0) {
		$inventoryQuery = $db->query("SELECT valid FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$_POST['reactivate']);
		$validValue = $inventoryQuery->fetch_assoc();

		//if the entry is not already inactive, deactivate it
		if($validValue['valid'] != 1) {
			$db->query("UPDATE ".DB_EQUIP_INVENTORY." SET valid=1 WHERE inventory_id=".$_POST['reactivate']);
			$inventoryQuery = $db->affected_rows;				
		}
		else {
			$inventoryQuery = -1;
		}	
	}

	//set the confirmation messages
	//if the entry was not found		
	if($entryExists == 0) {
		$_SESSION['confirmation'] = "Could not find listing #".$_POST['reactivate']." in the database";
	}
	//if the entry was already deactivated
	else if($inventoryQuery == -1) {
		$_SESSION['confirmation'] = "Error, listing #".$_POST['reactivate']." is already active";
	}
	//if the system failed to deactivate the listing
	else if($inventoryQuery == 0) {
		$_SESSION['confirmation'] = "Failed to reactivate listing #".$_POST['reactivate'];
	}
	//if the system successfully deactivated the listing
	else {
		$_SESSION['confirmation'] = "Listing #".$_POST['reactivate']." successfully reactivated";	
	}

	//redirect the user back to the control panel page
	header("Location: ".EQUIPMENT_CONTROL_PAGE);
	$db->close();
	exit();
}
//==============================================================[END] CONTROL PANEL 

/**
 * formats the inventory_ids into a string that looks like this
 * without brackets (34, 45, 23, 36...). this string can be
 * used for "IN" MySQL queries
 * @param $tmpArray - the array containing the inventory_ids
 */
function formatInventoryID($tmpArray) {
	$inventoryID = "";
	$rows = count($tmpArray);
	for($count = 0; $count < $rows; $count++) {
		if($count == ($rows - 1)) {
			$inventoryID .= $tmpArray[$count];
		}
		else {
			$inventoryID .= $tmpArray[$count].", ";
		}
	}
	return $inventoryID;
}

/**
 * return the search results given the inventory_ids or search type or
 * both.
 * @param $inventoryID - inventory_ids used for basic searches
 * @param $searchType - the search type (basic search, list all, list all deactivated)
 */
function returnSearchResults($inventoryID, $searchType) {
	global $db;
	
	if($searchType == LIST_ALL) {
		$searchResults = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE valid=1");		
		$numRows = $searchResults->num_rows;	
	}
	else if($searchType == BASIC_SEARCH) {
		$searchResults = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id IN (".$inventoryID.") AND valid=1");			
		$numRows = $searchResults->num_rows;		
	}
	else if($searchType == LIST_ALL_DEACTIVATED) {
		$searchResults = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE valid=0");		
		$numRows = $searchResults->num_rows;	
	}
	
	for($count = 1; $count <= $numRows; $count++) {
		$tmpArray = $searchResults->fetch_assoc();
		echo $tmpArray['inventory_id'].	DELIMITER;
		echo $tmpArray['research'].		DELIMITER;
		echo $tmpArray['institution'].	DELIMITER;
		echo $tmpArray['city'].			DELIMITER;
		echo $tmpArray['province'].		DELIMITER;
		
		if($count == $numRows) {
			echo $tmpArray['date_updated'];	
		}
		else {
			echo $tmpArray['date_updated'].	DELIMITER;	
		}
	}	
}
$db->close();
exit();
?>