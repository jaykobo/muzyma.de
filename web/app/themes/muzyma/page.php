<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <mark>Inhalt: page.php</mark>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template_parts/content','page' ); ?>
        <?php endwhile; else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; ?>

    </div>
</div>

<?php get_template_part( 'template_parts/content','page-promo' ); ?>


<?php get_footer(); ?>