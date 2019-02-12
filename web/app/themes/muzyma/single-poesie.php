<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <mark>Inhalt: single-poesie.php</mark>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template_parts/content','poesie' ); ?>
        <?php endwhile; else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; ?>

    </div>
</div>


<div class="wrapper dark style1 link-back">
    <div class="container">
        <a href="<?php echo get_page_link(20); ?>" class="button">« Zurück zur Übersicht</a>
    </div>
</div>

<?php get_footer(); ?>