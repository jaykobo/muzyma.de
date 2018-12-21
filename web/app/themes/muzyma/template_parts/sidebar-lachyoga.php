<section id="sidebar">

    <?php

        $args = array(
            'category_name'  => 'lachyoga',
            'post_type'      => 'any',
            'order'          => 'ASC',
            'orderby'        => 'date',
            'posts_per_page' => 4,
        );        

        $loop_sidebar = new WP_Query($args);

    if ( $loop_sidebar->have_posts() ) : while ( $loop_sidebar->have_posts() ) : $loop_sidebar->the_post(); ?>
        <?php get_template_part( 'template_parts/content', 'sidebar-lachyoga' ); ?>
    <?php endwhile; else : ?>
        <?php get_template_part( 'template_parts/content','error' ); ?>
    <?php endif; wp_reset_postdata(); ?>


</section>