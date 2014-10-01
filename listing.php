<?php 
require "includes/variables.php";
session_start();
require "includes/db-equip-connect.php"; //database connection settings
define("HAS_LAB_LIST", 	1); //defined value to represent when a form entry contains a list of specialized lab equipment
define("VALID_ENTRY", 	1); //defined value to represent valid entries into the inventory database
define("INVALID_ENTRY", 0); //defined value to represent invalid entries into the inventory database

//gets the inventory_id via the GET method. if the inventory_id produces no matches, kill the page
$id = $db->real_escape_string($_GET['inventory_id']);
$query = $db->query("SELECT * FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id='".$id."'");
if($query->num_rows == 0) {
	die("Invalid URL");
}
else {
	$inventoryData = $query->fetch_assoc();
}

//if viewing, approving or deleting a new record
if(isset($_GET['validation_type'])) {
	//check if the URL provided is valid
	$entryExists = $db->query("SELECT * FROM ".DB_EQUIP_VALIDATION." WHERE inventory_id=".$id." AND validation_key='".$_GET['validation_key']."'");
	
	//if the URL is valid
	if($entryExists->num_rows != 0) {
		//for viewing an entry
		if($_GET['validation_type'] == "view") {
			echo "<script>alert('Note: You are currently viewing an entry that has not been approved yet')</script>";
		}
		//for approving an entry
		else if($_GET['validation_type'] == "approve") {
			$db->query("UPDATE ".DB_EQUIP_INVENTORY." SET valid=1 WHERE inventory_id=".$id); //set valid=1
			$validated = $db->affected_rows; //check if the entry was successfully validated
			$db->query("DELETE FROM ".DB_EQUIP_VALIDATION." WHERE inventory_id=".$id); //delete the validation_key from the DB as it's no longer valid or needed
			
			//If the entry was successfully validated
			if($validated == 1) {
				//send out an email to the submitter informing them that their entry has been approved
				require "includes/phpmailer/class.phpmailer.php";
				$mail             = new PHPMailer();
				$mail->SetFrom(FROM_EMAIL, FROM_EMAIL_NAME);
				$mail->AddReplyTo(REPLY_TO_EMAIL, REPLY_TO_EMAIL_NAME);
				$mail->AddAddress($inventoryData['email'], $inventoryData['contact_name']);
				$mail->AddBCC(CC_EMAIL, CC_EMAIL_NAME);
				$mail->Subject    = EMAIL_TITLE_FOR_APPROVED_SUBMISSION." (#".$id.")";
				$mail->Body 	  = "Your entry has been approved. For your reference, the link to your entry is ".EQUIPMENT_LISTING_PAGE.$id.".\n\n".
						   		    "If you have any comments or need to make any corrections, please contact ".CONTACT_EMAIL_NAME." via ".CONTACT_EMAIL.".\n\n".
						   		    "Thank you.\n\n".
									ORG_NAME."\n".
									DOMAIN."\n".
									ORG_MAILING_ADDRESS."\n".
									ORG_OFFICE_PHONE;
				$mail->send();

				echo "<script>alert('Note: You have successfully approved this listing. The URL you\'re using to view this page will no longer be valid after this session')</script>";
			}
			//if not kill the page
			else {
				die("Error, approving this listing failed.");
			}
			
		}
		//for deactivating an entry (technically speaking the entry is already deactivated, this just removes the validation_key from the database so that the URL is no longer valid)
		else if($_GET['validation_type'] == "deactivate") {
			$db->query("DELETE FROM ".DB_EQUIP_VALIDATION." WHERE inventory_id=".$id); //delete the validation_key from the DB as it's no longer valid or needed
			echo "<script>alert('Note: You have successfully deactivated this listing. The URL you\'re using to view this page will no longer be valid after this session')</script>";		
		}
		//for deleting an entry
		else if($_GET['validation_type'] == "delete") {
			$db->query("DELETE FROM ".DB_EQUIP_INVENTORY." WHERE inventory_id=".$id);
			$deleted = $db->affected_rows;
			$db->query("DELETE FROM ".DB_EQUIP_VALIDATION." WHERE inventory_id=".$id);
			$db->query("DELETE FROM ".DB_EQUIP_LAB_LIST." WHERE inventory_id=".$id);
			
			if($deleted == 1) {
				die("Note: You have successfully deleted this listing. This URL will no longer be active.");					
			}
			else {
				die("Error, failed to delete this listing");
			}
		
		}
		else {
			die("Invalid request");
		}		
	}
	//if the URL is invalid, kill the page
	else {
		die("Invalid validation key");
	}
}
//else, for normal viewing, check if the entry is active
else {
	if($inventoryData['valid'] == INVALID_ENTRY && !isset($_SESSION['valid_user'])) {
		die("This page listing has been removed");
	}	
}

//checks if the inventory query contains a list of specialized equipment. if it does, query the lab_list database for the entries
if($inventoryData['list'] == HAS_LAB_LIST) {
	$query = $db->query("SELECT * FROM ".DB_EQUIP_LAB_LIST." WHERE inventory_id='".$id."'");
	$numRows = $query->num_rows;
}
$db->close();
require "includes/header.php";
?>

<!--article-->
<article>
	<!--listing title is the name of the research lab/facility-->
	<?php echo "<h1>".$inventoryData['research']. " (#".$inventoryData['inventory_id'].")</h1>"; ?>
	
	<!--==============================================================[START] table-->
	<table class="listingTable">
		<!--table body-->
		<tbody>
			<?php
			if($inventoryData['institution'] != "") 	{echo "<tr><td>Institution:</td>					<td>".$inventoryData['institution']."</td></tr>";}
			if($inventoryData['url'] != "") 			{echo "<tr><td>Lab/facility website (URL):</td>		<td><a href='".$inventoryData['url']."' target='_blank'>".$inventoryData['url']."</a></td></tr>";}
			if($inventoryData['city'] != "") 			{echo "<tr><td>City:</td>							<td>".$inventoryData['city']."</td></tr>";}
			if($inventoryData['province'] != "") 		{echo "<tr><td>Province:</td>						<td>".substr($inventoryData['province'], 4)."</td></tr>";}
			if($inventoryData['does'] != "") 			{echo "<tr><td>What the lab/facility does:</td>		<td>".$inventoryData['does']."</td></tr>";}
			if($inventoryData['add_info'] != "") 		{echo "<tr><td>Additional information:</td>			<td>".$inventoryData['add_info']."</td></tr>";}	
			//==============================================================[BREAK] lab/equipment list
			if($inventoryData['list'] == HAS_LAB_LIST) {
				echo "<tr><td colspan='2'><br /><br /><br /><h2>Specialized labs and equipment</h2></td></tr>"; //main heading
				echo "<tr><td><h3>Name of specialized lab/equipment</h3></td><td><h3>Description of Function(s)</h3></td></tr>"; //subheadings (2)
				
				for($count = 1; $count <= $numRows; $count++) {
					$labData = $query->fetch_assoc();	
					echo "<tr><td>".$count.". ".$labData['lab']."<br />"; //lab name
					
					//display the values only if the checkbox was ticked during form submission
					if($labData['fee'] == 1) 		{echo "<br />- Charges a fee for use";}
					else if($labData['fee'] == 0) 	{echo "<br />- Does not charge a fee for use";}
					else if($labData['fee'] == 2)  {echo "<br />- Charge varies";}
					
					if($labData['guest'] == 1) 	{echo "<br />- Access by guest researcher allowed";}
					else if($labData['guest'] == 0){echo "<br />- Access by guest researcher not allowed";}
					
					if($labData['host'] == 1) 		{echo "<br />- Host technician(s) available";}
					else if($labData['host'] == 0) {echo "<br />- Host technician(s) not available";}
					
					echo "</td><td>".$labData['descr']."</td></tr>"; //equipment description
					
					//if this is not the last entry, append spacing
					if($count < $numRows) {
						echo "<tr><td colspan='2'><br /></td></tr>";
					}
				}
			}	
			//==============================================================[BREAK] contact information
			if($inventoryData['contact_name'] != "") 	{echo "<tr><td colspan='2'><br /><br /><br /><h2>Contact Information</h2></td></tr>";}
			if($inventoryData['contact_name'] != "") 	{echo "<tr><td>Contact Name:</td>					<td>".$inventoryData['contact_name']."</td></tr>";}
			if($inventoryData['position'] != "") 		{echo "<tr><td>Position:</td>						<td>".$inventoryData['position']."</td></tr>";}
			if($inventoryData['department'] != "") 		{echo "<tr><td>Department or office:</td>			<td>".$inventoryData['department']."</td></tr>";}
			if($inventoryData['email'] != "") 			{echo "<tr><td>Email:</td>							<td><a href='mailto:".$inventoryData['email']."'>".$inventoryData['email']."</a></td></tr>";}
			if($inventoryData['telephone'] != "") 		{echo "<tr><td>Telephone:</td>						<td>(".substr($inventoryData['telephone'], 0, 3).") ".substr($inventoryData['telephone'], 3, 3)."-".substr($inventoryData['telephone'], 6, 4)."</td></tr>";}
			?>
		</tbody>
	</table><br /><br /><br />
	<!--==============================================================[END] table-->
	
	<!--date added-->
	<p><span class="note italic">Date Added: <?php echo date("F j, Y", strtotime($inventoryData['date_posted'])); ?></span></p>
</article>
<?php require "includes/footer.php"; ?>