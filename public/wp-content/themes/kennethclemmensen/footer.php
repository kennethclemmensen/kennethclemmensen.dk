<footer class="footer">
	<?php
	$email = 'kenneth.clemmensen@gmail.com';
	?>
    <p class="footer__text">
		<?php bloginfo('description'); ?> |
        <a href="mailto:<?php echo $email; ?>" class="footer__link footer__link--email"></a> |
        <a href="https://www.linkedin.com/in/kennethclemmensen" class="footer__link footer__link--linkedin" target="_blank"></a> |
        <a href="https://github.com/kennethclemmensen?tab=repositories" class="footer__link footer__link--github" target="_blank"></a>
    </p>
</footer>
<?php
wp_footer();
?>
</body>
</html>