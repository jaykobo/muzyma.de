<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <mark>Inhalt: page-<?php echo get_query_var('pagename');?>.php</mark>

        <div class="row oneandhalf">
            <div class="8u">

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template_parts/content','page' ); ?>
                <?php endwhile; else : ?>
                    <?php get_template_part( 'template_parts/content','error' ); ?>
                <?php endif; ?>
            </div>

            <section class="4u">
                <?php get_template_part( 'template_parts/sidebar','lachyoga' ); ?>
            </section>
        </div>
    </div>
</div>

<?php get_template_part( 'template_parts/content','page-promo' ); ?>


<?php get_footer(); ?>