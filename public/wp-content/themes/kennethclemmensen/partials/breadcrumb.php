<div class="breadcrumb">
    <span class="breadcrumb__title"><?php pll_e('You are here:'); ?></span>
    <ul class="breadcrumb__list">
        <?php
        $pages = get_breadcrumb();
        for($i = 0; $i < count($pages); $i++) {
            $pageID = $pages[$i];
            $title = ($i === 0) ? pll__('Front page') : get_the_title($pageID);
            ?>
            <li class="breadcrumb__list-item">
                <a href="<?php echo get_permalink($pageID); ?>"><?php echo $title; ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>