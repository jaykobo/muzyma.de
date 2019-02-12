<?php get_header(); ?>

<?php
    // Getting the post type of the current post
    $current_post_type_object = get_post_type_object(get_post_type($post->ID));
    $current_post_type_name   = $current_post_type_object->label;
    $current_post_type        = get_post_type();
    $strip_slug_to_url        = str_replace('p-', '', $current_post_type);
    $parent_page_url          = home_url('/').'handgemacht-'.$strip_slug_to_url;
?>

<div class="wrapper breadcrumb">
    <div class="container">
        <nav class="breadcrumb">
        « <a href="<?php echo $parent_page_url; ?>"><?php echo $current_post_type_name; ?></a>
        </nav>
    </div>
</div>

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

<div class="wrapper dark style1 link-back">
    <div class="container">
        <a href="<?php echo $parent_page_url; ?>" class="button">« Zurück zur Übersicht</a>
    </div>
</div>

<?php get_footer(); ?>