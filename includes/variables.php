<?php
/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */

/*other contact info*/
define("ORG_NAME", 						 		"Science Atlantic");
define("ORG_MAILING_ADDRESS", 			 		"Department of Psychology and Neuroscience | Dalhousie University\nLSC Room 1376 | PO Box 15000 | Halifax, NS | B3H 4R2");
define("ORG_OFFICE_PHONE", 				 		"(902) 494-3421");

/*url settings*/
define("DOMAIN", 						 		"http://scienceatlantic.ca/");
define("EQUIPMENT_INDEX_PAGE", 			 		DOMAIN."afred/");
define("EQUIPMENT_SEARCH_PAGE", 		 		EQUIPMENT_INDEX_PAGE."");
define("EQUIPMENT_ABOUT_PAGE", 					EQUIPMENT_INDEX_PAGE."about.php");
define("EQUIPMENT_SUBMISSION_PAGE", 	 		EQUIPMENT_INDEX_PAGE."submission.php");
define("EQUIPMENT_LISTING_PAGE", 		 		EQUIPMENT_INDEX_PAGE."listing.php?inventory_id=");
define("EQUIPMENT_CONTROL_PAGE", 		 		EQUIPMENT_INDEX_PAGE."control.php");
define("EQUIPMENT_AUTHENTICATION_PAGE",  		EQUIPMENT_INDEX_PAGE."authentication.php");
define("HTTP_REQUEST", 							EQUIPMENT_INDEX_PAGE."query.php");		

/*default delimiter*/
define("DELIMITER", 					 		"<br>");

/*database settings*/
define("ROOT", 							 		"sciencea");
define("DB_EQUIP", 						 		ROOT."_afred");
define("DB_EQUIP_AUTH", 				 		DB_EQUIP.".authentication");
define("DB_EQUIP_INVENTORY", 			 		DB_EQUIP.".inventory");
define("DB_EQUIP_LAB_LIST", 			 		DB_EQUIP.".lab_list");
define("DB_EQUIP_VALIDATION", 					DB_EQUIP.".validation");
define("DB_EQUIP_VALIDATION_VALID_KEY_CHARS",   "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"); //characters that can be used to generate a random string
define("DB_EQUIP_VALIDATION_KEY_LENGTH",        100); //length of the random string		

define("SYSTEM_NAME", 							"Atlantic Facilities and Research Equipment Database");
define("SYSTEM_NAME_ACRONYM", 					"AFRED");

/*form settings*/
define("MAX_LAB_ENTRIES", 				 		30); //max number of entries for the specialised lab list

/*email settings*/
define("FROM_EMAIL", 					 		"afred@scienceatlantic.ca"); //this is the email that will be sending out system generated emails
define("FROM_EMAIL_NAME", 				 		ORG_NAME);
define("CC_EMAIL", 						 		"afred@scienceatlantic.ca"); 
define("CC_EMAIL_NAME", 				 		ORG_NAME);
define("TO_EMAIL",                       		"afred@scienceatlantic.ca");
define("TO_EMAIL_NAME",  				 		ORG_NAME);
define("REPLY_TO_EMAIL", 				 		"afred@scienceatlantic.ca"); //this is the email that they'll reply to if any user receives an email from the system (placed in the "Reply To" field not in the message body)
define("REPLY_TO_EMAIL_NAME", 			 		"afred@scienceatlantic.ca");
define("CONTACT_EMAIL",  				 		"afred@scienceatlantic.ca"); //this is the same as the REPLY_TO email except that it is only meant for the message body
define("CONTACT_EMAIL_NAME", 			 		"afred@scienceatlantic.ca");						 
define("EMAIL_TITLE_FOR_NEW_SUBMISSION", 		SYSTEM_NAME_ACRONYM." - New Record Received");
define("EMAIL_TITLE_FOR_APPROVED_SUBMISSION",   SYSTEM_NAME_ACRONYM." - New Record Approved");
?>
