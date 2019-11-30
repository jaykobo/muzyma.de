<?php get_header(); ?>

<?php
    // Get category name and url for breadcrumb
    $category = get_the_category();

    if ( !empty ( $category ) ) {
        $category_id   = $category[0]->cat_ID;
        $category_name = $category[0]->cat_name;
        $category_url  = get_category_link( $category_id );
    }
?>

<div class="wrapper breadcrumb">
    <div class="container">
        <nav class="breadcrumb">
        « <a href="<?php echo $category_url; ?>"><?php echo $category_name; ?></a>
        </nav>
    </div>
</div>

<div class="wrapper site-content">
    <div class="container" id="main">
        <?php // <mark>Inhalt: single.php</mark> ?>

        <div class="row oneandhalf">
            <div class="8u">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template_parts/content','page' ); ?>
                <?php endwhile; else : ?>
                    <?php get_template_part( 'template_parts/content','error' ); ?>
                <?php endif; ?>
            </div>

            <section class="4u">
                <?php get_template_part( 'template_parts/sidebar','archive' ); ?>
            </section>
        </div>

    </div>
</div>

<div class="wrapper dark style1 link-back">
    <div class="container">
        <a href="<?php echo $category_url; ?>" class="button">« Zurück zur Übersicht</a>
    </div>
</div>

<?php get_footer(); ?>