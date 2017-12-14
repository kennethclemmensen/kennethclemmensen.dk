<footer class="footer">
    <?php
    $themeSettings = new ThemeSettings();
    ?>
    <p class="footer__text">
        <?php bloginfo('description'); ?> |
        <a href="mailto:<?php echo $themeSettings->getEmail(); ?>" class="footer__link footer__link--email"></a> |
        <a href="<?php echo $themeSettings->getLinkedInUrl(); ?>" class="footer__link footer__link--linkedin"
           target="_blank"></a> |
        <a href="<?php echo $themeSettings->getGitHubUrl(); ?>" class="footer__link footer__link--github"
           target="_blank"></a>
    </p>
</footer>
<?php
wp_footer();
?>
</body>
</html>