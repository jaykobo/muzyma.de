<?php get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        

    <!-- Hero -->
    <section id="hero" class="container">
        <header>
            <h2><?php the_title(); ?></h2>
        </header>
        <?php the_content(); ?>
        <ul class="actions">
            <li><a href="#first" class="button scrolly">Mehr erfahren...</a></li>
        </ul>
    </section>

    <?php endwhile; else : ?>
        <?php get_template_part( 'template_parts/content','error' ); ?>
    <?php endif; ?>

</div> <!-- END #header-wrapper -->

<!-- <div class="wrapper site-content"> -->
    <!-- <div class="container" id="main"> -->
        <mark>Inhalt: front-page.php</mark>


        <?php

            global $post;
            $args = array(
                'post_type'     =>  'page',
                'post_parent'   =>  $post->ID,
                'post_status'   =>  'publish',
                'orderby'       =>  'menu_order',
                'order'         =>  'ASC',
                'posts_per_page'=>   -1,
                'nopaging'      =>  true
            );

            $child_pages = new WP_Query($args);

        if ( $child_pages->have_posts() ) : while ( $child_pages->have_posts() ) : $child_pages->the_post(); ?>
            <?php get_template_part( 'template_parts/content','child' ); ?>
        <?php endwhile; else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; wp_reset_postdata(); ?>

    <!-- </div> -->
<!-- </div> -->

<?php get_footer(); ?>