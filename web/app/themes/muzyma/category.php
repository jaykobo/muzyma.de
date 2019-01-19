<?php get_header(); ?>

<div class="wrapper site-content">
    <div class="container" id="category">
        <mark>Inhalt: category.php</mark>

        <div class="row oneandhalf">
            <div class="8u">
                <header>
                    <h2><?php single_cat_title(); ?></h2>
                    <?php if( category_description() ): ?>
                        <?php echo category_description(); ?>
                    <?php endif; ?>
                </header>

                <?php
                    $args =  array(
                        'mid_size'  => 2,
                        'prev_next' => true,
                        'prev_text' => '‹&nbsp;Zurück',
                        'next_text' => 'Vorwärts&nbsp;›',
                        'type'      => 'list',
                    );
                ?>


                <div class="row">
                <?php  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php  get_template_part( 'template_parts/content','posts-loop' ); ?>
                <?php  endwhile; ?>
                </div>
                    <?php the_posts_pagination( $args ); ?>
                <?php else : ?>
                    <?php  get_template_part( 'template_parts/content','error' ); ?>
                <?php endif; ?>
            </div>

            <section class="4u">
            Sidebar
                <?php // get_template_part( 'template_parts/sidebar','archive' ); ?>
            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>