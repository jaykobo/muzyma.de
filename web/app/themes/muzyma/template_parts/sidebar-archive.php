<mark>template parts: sidebar-archive.php</mark>
<section id="sidebar" class="sidebar-archive">
    <section>
        <header>
            <h3>Letzte Beiträge</h3>
        </header>
        <?php

            $args = array(
                'category_name'  => 'news,lachyoga-news',
                'post_type'      => 'any',
                'order'          => 'DESC',
                'orderby'        => 'date',
                'posts_per_page' => 10,
            );

            $loop_sidebar = new WP_Query($args);
        ?>
        <ul class="sidebar-list-posts">
        <?php if ( $loop_sidebar->have_posts() ) : while ( $loop_sidebar->have_posts() ) : $loop_sidebar->the_post(); ?>
            <?php get_template_part( 'template_parts/content', 'sidebar-archive' ); ?>
        <?php endwhile; ?>
        </ul>
        <?php else : ?>
            <?php get_template_part( 'template_parts/content','error' ); ?>
        <?php endif; wp_reset_postdata(); ?>
    </section>


</section>