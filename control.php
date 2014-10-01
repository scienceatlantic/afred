<?php
require "includes/variables.php";
session_start();
if(!isset($_SESSION['valid_user'])) {
	header("Location: ".EQUIPMENT_AUTHENTICATION_PAGE);
	exit();
}
else if($_SESSION['valid_user'] != "admin") {
	echo "You are not authorized to view this page";
	exit();
}
require "includes/header.php";
if(isset($_SESSION['confirmation'])) {
	echo "<script>alert('".$_SESSION['confirmation']."');</script>";
	unset($_SESSION['confirmation']);
}
?>
<!--javascript-->
<script src="js/control.js"></script>

<!--article-->
<article>
	<h1>Control Panel</h1>
	
	<!--==============================================================[START] FORM-->
	<form id="controlForm" action="<?php echo HTTP_REQUEST; ?>" method="post">
		
		<!--hidden request container, this is used to identify the type of query-->
		<input type="hidden" name="request" id="request" value="" />
		
		<!--==============================================================[START] TABLE-->
		<table class="controlTable">
			<!--table body-->
			<tbody>
				<!--row-->
				<tr><td colspan="2"><h2>Deactivate Listing</h2></td></tr>			
				<!--row-->
				<tr><td>Listing #:</td><td><input type="text" name="deactivate" id="deactivate" maxlength="4"></td></tr>
				<!--row-->
				<tr><td colspan="2"><button type="button" id="deactivateButton">Deactivate listing</button><br /><br /><br /><br /></td></tr>
				
				<!--row-->
				<tr><td colspan="2"><h2>Reactivate Listing</h2></td></tr>			
				<!--row-->
				<tr><td>Listing #:</td><td><input type="text" name="reactivate" id="reactivate" maxlength="4"> (To view a list of deactivated entries, go to the search page)</td></tr>
				<!--row-->
				<tr><td colspan="2"><button type="button" id="reactivateButton">Reactivate listing</button><br /><br /><br /><br /></td></tr>
				
				<!--row-->
				<tr><td colspan="2"><h2>Permanently Delete Listing</h2></td></tr>			
				<!--row-->
				<tr><td>Listing #:</td><td><input type="text" name="delete" id="delete" maxlength="4"></td></tr>
				<!--row-->
				<tr><td colspan="2"><button type="button" id="deleteButton">Delete listing</button></td></tr>
			</tbody>
		</table>
		<!--==============================================================[END] TABLE-->
	</form>
	<!--==============================================================[END] FORM -->
</article>
<?php require "includes/footer.php"; ?>