<div class="breadcrumb">
    <span class="breadcrumb__title"><?php echo TranslationStrings::getYouAreHereText(); ?></span>
    <ul class="breadcrumb__list">
        <?php
        $pages = ThemeHelper::getBreadcrumb();
        for($i = 0; $i < count($pages); $i++) {
            $pageID = $pages[$i];
            $title = ($i === 0) ? TranslationStrings::getFrontPageText() : get_the_title($pageID);
            ?>
            <li class="breadcrumb__list-item">
                <?php
                echo (($i + 1) === count($pages)) ? '<em>'.$title.'</em>' : '<a href="'.get_permalink($pageID).'">'.$title.'</a>';
                ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>