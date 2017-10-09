<?php
//Template Name: Search
get_header();
while(have_posts()) {
	the_post();
	?>
    <div class="page">
	    <?php get_template_part('partials/slider'); ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
			<?php
			the_content();
			?>
            <form method="post" action="/" id="search-form" @submit="search($event)">
                <input type="search" name="search" v-model="searchString">
                <input type="submit" value="Search" @click="search($event)">
            </form>
        </section>
    </div>
	<?php
}
get_footer();