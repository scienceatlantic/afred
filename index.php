<?php
require "includes/variables.php";
session_start();
require "includes/header.php"; 
?>
<!--javascript-->
<script src="js/search.js"></script>

<!--article-->
<article class="search">
	<!--AFRED logo-->
	<div class="center"><img src="img/logo.png" width="400" height="163" alt="AFRED logo" /></div>
	
	<!--search box-->
	<div class="center" id="searchBar"><span>Search: </span><input type="text" maxlength="100" id="search" placeholder="for equipment, facility, researcher..." /></div><br />
	
	<!--list all button, list all deactivated button (login required), clear button-->
	<div class="center"><button type="button" id="listAllButton">List all</button> <?php if(isset($_SESSION['valid_user'])) {echo '<button type="button" id="listAllDeactivatedButton">List all deactivated</button>';} ?> <button type="button" id="clearButton">Clear</button><br /></div>
	
	<!--progress bar container-->
	<div id="progressBar"></div><br /><br />
	
	<!--search tips container-->
	<div id="searchTips" class="note center">
		<button type="button" class="center">Search Tips</button><br /><br />
		<ul id="searchTipsBullets"></ul>
	</div>
	
	<!--search results container-->
	<div id="searchResults"></div>
</article>
<?php require "includes/footer.php"; ?>