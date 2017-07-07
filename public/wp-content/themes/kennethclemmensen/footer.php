<footer class="footer">
	<?php
	$email = 'kenneth.clemmensen@gmail.com';
	?>
    <p class="footer__text">
		<?php bloginfo('description'); ?> | Email - <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> |
        <a href="https://www.linkedin.com/in/kennethclemmensen" target="_blank">LinkedIn</a>
    </p>
</footer>
<?php
wp_footer();
?>
</body>
</html>