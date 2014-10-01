				</section> <!--end of #main-->
			</div>	<!--end of #inside-content-->
			<footer>
				<nav role="menu">
					<!--Science Atlantic WordPress footer menu-->
					<?php wp_nav_menu( array('menu' => 'Footer Menu', 'menu_id' => 'footer-menu', 'container' => FALSE, 'fallback_cb' => FALSE)); ?>
				</nav>
				<!--copyright-->
				<p>Copyright © 2004 - <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>.  All Rights Reserved.</p>
			</footer>
		</div> <!-- end of #container -->
	</body>
</html>