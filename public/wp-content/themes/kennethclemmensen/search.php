<?php
get_header();
?>
    <div class="page">
	    <?php get_template_part('partials/slider'); ?>
        <section class="page__content">
            <h1>Søg</h1>
			<?php
			get_search_form();
			$args = ['s' => get_search_query()];
			$wp_query = new WP_Query($args);
			if($wp_query->have_posts()) {
				?>
                <h2>Søgeresultater <?php //echo get_query_var('s'); ?></h2>
                <ul>
					<?php
					while($wp_query->have_posts()) {
						$wp_query->the_post();
						?>
                        <li>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <p><?php the_excerpt(); ?></p>
                        </li>
						<?php
					}
					?>
                </ul>
				<?php
			} else {
				?>
                <h2>Din søgning gav ingen resultater</h2>
			<?php } ?>
        </section>
    </div>
<?php
get_footer();