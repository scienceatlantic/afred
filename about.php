<?php
require "includes/variables.php";
session_start();
require "includes/header.php"; 
?>
<!--javascript-->
<script src="js/slideshow.js"></script>

<!--article-->
<article id="homePage">
	<h1>About the <?php echo SYSTEM_NAME; ?></h1>
	
	<!--slideshow container-->
	<div id="homePageSlideshowContainer">
		<p id="homePageSlideshowLoadMessage">Loading slideshow...</p>
		<div id="homePageSlideshowImageContainer"></div>
		<figcaption id="homePageSlideshowCaption"></figcaption>	
	</div>

	<!--home page content container-->
	<div id="homePageContentContainer">
	    <p>The purpose of this inventory is to increase the use of sophisticated research equipment in our region by connecting potential users with the facilities that host the equipment.</p>
	    <p>The database includes brief descriptions of major pieces of research equipment available for use by external researchers, as well as contact information for you to connect directly with the host.</p>
	    <p>We hope this information will provide a starting point for the development of partnerships, regionally, nationally, and internationally.</p>
	    	    <p><span class="note">*Please note that the data in this inventory is voluntarily submitted by each research facility and is provided by Science Atlantic for information only.  Contact the facility directly to ensure the information provided is accurate.<br />If you have any difficulty with this website, or if you find an out-of-date listing, please contact <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>.</span></p>
    </div>
</article>
<?php require "includes/footer.php" ?>  