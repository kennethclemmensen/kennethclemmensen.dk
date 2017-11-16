<div class="breadcrumb">
    <span class="breadcrumb__title"><?php pll_e('You are here:'); ?></span>
    <ul class="breadcrumb__list">
        <?php
        $pages = get_breadcrumb();
        foreach($pages as $page) {
            ?>
            <li class="breadcrumb__list-item">
                <a href="<?php echo $page['link']; ?>"><?php echo $page['title']; ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>