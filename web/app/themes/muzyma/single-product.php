<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <mark>Inhalt: single-product.php</mark>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template_parts/content','product' ); ?>
        <?php endwhile; else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; ?>

    </div>
</div>


<?php
    // Getting the post type of the current post
    $current_post_type      = get_post_type_object(get_post_type($post->ID));
    $current_post_type_slug = $current_post_type->rewrite['slug'];
    $parent_page_url        = home_url().'/'.$current_post_type_slug;
?>

<div class="wrapper dark style1 link-back">
    <div class="container">
        <a href="<?php echo $parent_page_url; ?>" class="button">&laquo; Zurück zur Übersicht</a>
    </div>
</div>

<?php get_footer(); ?>