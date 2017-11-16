<?php
//Template Name: Search
get_header();
while(have_posts()) {
	the_post();
	?>
    <div class="page">
        <?php
        get_template_part('partials/slider');
        get_template_part('partials/breadcrumb');
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
			<?php
			the_content();
			?>
            <div id="search-app">
                <form method="post" action="/" @submit="search($event)">
                    <input type="search" name="search" v-model="searchString">
                    <input type="submit" value="Søg" @click="search($event)">
                </form>
                <h2 v-if="results.length === 0 && searchString !== ''">Din søgning gav ingen resultater</h2>
                <div v-else-if="results.length > 0">
                    <h2>Søgeresultater</h2>
                    <ul>
                        <li v-for="result in results">
                            <a :href="result.link">{{ result.title }}</a>
                            <p>{{ result.excerpt }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
	<?php
}
get_footer();