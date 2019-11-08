<section id="sidebar">
    <?php

        // Make specific Post sticky in Sidebar
        global $post;
        $post = get_post(90);
        setup_postdata($post);
        get_template_part( 'template_parts/content', 'sidebar-lachyoga' );  ?>

    <?php wp_reset_postdata(); ?>

    <?php
        $category_name = 'Lachyoga';
        // Get Category ID by Name
        $category_id = get_cat_ID( $category_name );

        // Get Category Object by ID
        $category = get_category( $category_id );

        // Gather other information based on Category Object
        $category_slug = $category->slug;
        $category_url  = get_category_link( $category_id );


        $args = array(
            'category_name'  => $category_slug,
            'post_type'      => 'any',
            'order'          => 'DESC',
            'orderby'        => 'date',
            'posts_per_page' => 3,
        );

        $loop_sidebar = new WP_Query($args);

    if ( $loop_sidebar->have_posts() ) : while ( $loop_sidebar->have_posts() ) : $loop_sidebar->the_post(); ?>
        <?php get_template_part( 'template_parts/content', 'sidebar-lachyoga' ); ?>
    <?php endwhile; else : ?>
        <?php get_template_part( 'template_parts/content','error' ); ?>
    <?php endif; wp_reset_postdata(); ?>

    <section class="sidebar-category-link">
        <a href="<?php echo $category_url; ?>" class="button dark">Alle Beiträge zu <?php echo $category_name; ?>&nbsp;»</a>
    </section>


</section>