<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="main">
        <mark>Inhalt: page-<?php echo get_query_var('pagename');?>.php</mark>

        <?php get_template_part( 'template_parts/content','page-productlist' ); ?>

        <?php

            $number_of_posts = 12;
            $paged           = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
            $offset          = ($paged - 1) * $number_of_posts;

            $args = array(
                'post_type'      => 'p-haekelmuetzen',
                'posts_per_page' => $number_of_posts,
                'offset'         => $offset,
                'paged'          => $paged
            );

            $products = new WP_Query($args);
            $i = 0;

        ?>
        <?php if ( $products->have_posts() ) : while ( $products->have_posts() ) : $products->the_post(); ?>

            <?php if($i == 0) { ?>
                <div class="row grid features">
            <?php } ?>

            <?php get_template_part( 'template_parts/content', 'product-loop' ); ?>


            <?php
                $i++;
                if($i == 3) {
                    $i = 0; ?>
                </div>
            <?php } ?>

        <?php endwhile; ?>


        <?php if($i > 0) { ?>
            </div>
        <?php } ?>

        <?php if ( $products->max_num_pages > 1 ) : ?>
            <div class="pagination align-center">
                <?php previous_posts_link('« Vorherige Seite', $products->max_num_pages);?>
                <?php next_posts_link('Nächste Seite »', $products->max_num_pages);?>
            </div>
        <?php endif; ?>

        <?php else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</div>

<?php get_footer(); ?>