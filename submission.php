<?php
require "includes/variables.php";
session_start();
require "includes/header.php";
?>
<!--javascript-->
<script src="js/input-validation.js"></script>
<script src="js/add-lab-entry.js"></script>

<!--article-->
<article>
	<h1>Equipment Submission</h1>
	<p>The purpose of this database is to identify the availability of specialized equipment and facilities in the Atlantic region, and to increase opportunities for use of these facilities. Please list only facilities and equipment that have the capacity to support additional research projects.</p>
	<p>Do you have research equipment that is available for use by a guest researcher and/or through local operational support? <select id="confirmFacility"><option value="-1"></option><option value="1">Yes</option><option value="0">No</option></select></p>
	<p id="invalidFacility"></p>
						
	<!--==============================================================[START] form-->
	<form name="equipmentForm" id="equipmentForm" action="<?php echo HTTP_REQUEST; ?>" method="post">
		<input type="hidden" name="request" value="process submission"/>
		<!--==============================================================[START] table-->
		<table class="formTable">
			<!--table body-->
			<tbody>
				<!--row-->
				<tr>
					<!--column 1-->
					<td colspan="2"><br /><h2>Facility Information</h2></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td colspan="2"></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Name of research lab/facility:*</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="research" name="research" class="checkInput"/>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Institution:*</td>
					<!--column 2-->
					<td><input type="text" maxlength="100" id="institution" name="institution" class="checkInput"/></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Lab/facility website (URL):</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="url" name="url" class="color_0000ff checkInput customErrorMsg"/>
						<br /><span class="hint">Your URL must start with "http://" or "https://"</span>
						<br /><span class="hint">Example: http://www.myamazinglaboratory.com</span>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>City:*</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="city" name="city" class="checkInput"/>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Province:*</td>
					<!--column 2-->
					<td>
						<select id="province" name="province" class="checkInput">
							<option selected="selected"></option>
							<option>NB - New Brunswick</option>
							<option>NL - Newfoundland and Labrador</option>
							<option>NS - Nova Scotia</option>
							<option>PE - Prince Edward Island</option>
						</select>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>What the lab/facility does:*</td>
					<!--column 2-->
					<td>
						<textarea rows="5" maxlength="750" id="does" name="does" class="checkInput"></textarea>
						<br /><span class="hint">(Maximum number of characters: 750)</span>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Additional information:</td>
					<!--column 2-->
					<td>
						<textarea rows="5" maxlength="750" id="addInfo" name="addInfo"></textarea>
						<br /><span class="hint">(Maximum number of characters: 750)</span>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Keywords:</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="keywords" name="keywords"/>
						<br /><span class="hint">A list of keywords separated by commas. 
						<br />Example: DNA, sequencer, particle, accelerator</span>
					</td>
				</tr>
				<!--==============================================================[BREAK] section-->
				<!--row-->
				<tr>
					<!--column 1-->
					<td colspan="2"><br /><br /><br /><h2>Contact Information</h2></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Contact Name:*</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="contactName" name="contactName" class="checkInput"/>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Position:</td>
					<!--column 2-->
					<td><input type="text" maxlength="100" id="position" name="position"/></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Department or office:</td>
					<!--column 2-->
					<td><input type="text" maxlength="100" id="department" name="department"/></td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Email:*</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="100" id="email" name="email" class="color_0000ff checkInput customErrorMsg"/>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td>Telephone:*</td>
					<!--column 2-->
					<td>
						<input type="text" maxlength="10" id="telephone" name="telephone" class="checkInput customErrorMsg" />
						<br /><span class="hint">(Maximum number of digits: 10)</span>
						<br /><span class="hint">Example: 9023008909</span>
					</td>
				</tr>
				<!--==============================================================[BREAK] section-->
				<!--row-->
				<tr>
					<!--column 1-->
					<td colspan="2">
						<br /><br /><br /><h2>Specialized Labs and Equipment</h2>
						<div class="hint">(Maximum of <?php echo MAX_LAB_ENTRIES; ?> entries)</div>
					</td>
				</tr>
				<!--row-->
				<tr>
					<!--column 1-->
					<td><h3>Name of specialized lab/equipment</h3></td>
					<!--column 2-->
					<td><h3>Short description of function</h3></td>
				</tr>
			</tbody>
		</table>
		<!--==============================================================[END] table-->
		<span class="hint">
			*An asterisk denotes a mandatory field.
			<br />**All fields accept a maximum of 100 characters unless otherwise stated.			
		</span>
		<br />
		<br />
		<div class="center"><button type="button" name="submitButton" id="submitButton" >Submit</button></div>
	</form>
	<!--==============================================================[END] form-->
</article>
<?php require "includes/footer.php"; ?>