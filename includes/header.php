<?php
//Science Atlantic WordPress settings
define('WP_USE_THEMES', false);
require $_SERVER['DOCUMENT_ROOT'].'/cms/wp/wp-blog-header.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--
			@author Prasad Rajandran
			@date June 24, 2013
		-->
		
		<!--meta details-->
		<meta charset="UTF-8" />
		<meta name="author" content="Science Atlantic" />
		<meta name="description" content="Searchable database of research equipment in Atlantic Canada" />
		<meta name="keywords" content="AFRED, Science, Atlantic, Facility, Research, Equipment, Database, Maritimes, University, Researcher" />
		
		<!--base link-->
		<base href="<?php echo EQUIPMENT_INDEX_PAGE; ?>" />
		
		<!--jquery-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		
		<!--jquery tablesorter plugin-->
		<script src="js/tablesorter/jquery.tablesorter.js"></script>
		
		<!--javascript variables-->
		<script>var DELIMITER = "<?php echo DELIMITER; ?>"</script>
		<script>var EQUIPMENT_LISTING_PAGE = "<?php echo EQUIPMENT_LISTING_PAGE; ?>"</script>
		<script>var MAX_LAB_ENTRIES = <?php echo MAX_LAB_ENTRIES; ?></script>
		<script>var HTTP_REQUEST = "<?php echo HTTP_REQUEST; ?>"</script>
		
		<!--css-->
		<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/fonts.css"> <!--Science Atlantic WordPress fonts-->
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?v=1"> <!--Science Atlantic WordPress theme-->
		<link rel="stylesheet" href="css/equipment.css"/> <!--equipment css-->
		
		<!--website title-->
		<title><?php echo SYSTEM_NAME; ?> | Science Atlantic</title>
		
		<!--Science Atlantic WordPress google analytics plugin-->
		<?php
		if(function_exists('yoast_analytics')) {
			yoast_analytics();
		}
		?>
	</head>
	<body>
		<header>
			<!--Science Atlantic WordPress top navigation menu-->
			<nav role="menu">
				<?php wp_nav_menu( array('menu' => 'Main Menu', 'menu_id' => 'main-menu', 'container' => FALSE, 'fallback_cb' => FALSE)); ?>
			</nav>
			<!--banner-->
			<h1 id="logo"><a href="<?php echo EQUIPMENT_INDEX_PAGE; ?>"></a></h1>
		</header>
		
		<div id="container"> <!--Science Atlantic WordPress container-->
			<div id="inside-content"> <!--Science Atlantic WordPress container-->
				<section id="main" role="main"> <!--Science Atlantic WordPress container-->
					<!--equipment navigation bar-->
					<nav id="equipmentNav">
						<a href="<?php echo EQUIPMENT_SEARCH_PAGE; ?>">Search</a>
						<a href="<?php echo EQUIPMENT_SUBMISSION_PAGE; ?>">Add A Record</a>
						<a href="<?php echo EQUIPMENT_ABOUT_PAGE; ?>">About</a>						
						<!--login/logout buttons-->
						<?php 
							if(isset($_SESSION['valid_user'])) {
								if($_SESSION['valid_user'] == "admin") {
									echo '<a href="'.EQUIPMENT_CONTROL_PAGE.'">Control Panel</a>';									
								}
								echo '<span id="authenticationButton"><a href="'.EQUIPMENT_AUTHENTICATION_PAGE.'">Logout</a></span>';
							}
							else {
								echo '<span id="authenticationButton"><a href="'.EQUIPMENT_AUTHENTICATION_PAGE.'">Administrator Login</a></span>';					
							}						
						?>
					</nav>
					<!--[if lt IE 9]>
						<script>alert("You are using an outdated version of Internet Explorer. For the best results, please upgrade your browser to the latest version or try using a different browser.");</script>
					<![endif]-->