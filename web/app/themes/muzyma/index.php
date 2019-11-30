<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <?php // <mark>Inhalt: index.php</mark> ?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template_parts/content' ); ?>
        <?php endwhile; else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>