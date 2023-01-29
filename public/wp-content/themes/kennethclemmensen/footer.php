<footer class="footer">
	<?php
	$themeService = new ThemeService();
	dynamic_sidebar($themeService->getFooterSidebarID());
	?>
</footer>
<?php wp_footer(); ?>
</body>
</html>