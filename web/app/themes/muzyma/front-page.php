<?php get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


    <!-- Hero -->
    <section id="hero" class="container">
        <header>
            <?php if( get_field('hero_headline') ): ?>
                <?php the_field('hero_headline'); ?>
            <?php else : ?>
                <h2>Wir lachen nicht, weil wir glücklich sind - <br>sondern wir sind glücklich, weil wir lachen.</h2>
            <?php endif; ?>
        </header>

        <?php if( get_field('hero_introtext') ): ?>
            <?php the_field('hero_introtext'); ?>
        <?php else: ?>
            <p>Willkommen bei <strong>Muzyma.</strong></p>
        <?php endif; ?>

        <ul class="actions">
            <?php if( get_field('hero_button') ): ?>
                <li><a href="#first" class="button scrolly"><?php the_field('hero_button'); ?></a></li>
            <?php else: ?>
                <li><a href="#first" class="button scrolly">Mehr erfahren...</a></li>
            <?php endif; ?>
        </ul>
    </section>

    <?php endwhile; else : ?>
        <?php get_template_part( 'template_parts/content','error' ); ?>
    <?php endif; ?>

</div> <!-- END #header-wrapper -->

<?php // <mark>Inhalt: front-page.php</mark> ?>

<div class="wrapper" id="first">
    <section class="container">
        <header class="major">
            <?php if( get_field('content_first') ): ?>
                <?php the_field('content_first'); ?>
            <?php endif; ?>
        </header>
    </section>
</div>


<?php
    // Content Column 1
    $column01              = get_field('subjects_column_01');
    $column01_headline     = $column01['headline'];
    $column01_headline_url = $column01['headline_url'];
    $column01_content      = $column01['content'];

    // Content Column 2
    $column02              = get_field('subjects_column_02');
    $column02_headline     = $column02['headline'];
    $column02_headline_url = $column02['headline_url'];
    $column02_content      = $column02['content'];

    // Content Column 3
    $column03              = get_field('subjects_column_03');
    $column03_headline     = $column03['headline'];
    $column03_headline_url = $column03['headline_url'];
    $column03_content      = $column03['content'];
?>


<div class="wrapper dark style1">
    <section class="container">
            <div class="row features">
                <section class="4u feature">
                    <span class="feature-icon"><span class="fa fa-smile-o"></span></span>
                    <?php if( $column01_headline ): ?>
                        <header>
                            <h3>
                            <?php if( $column01_headline_url ): ?>
                                <a href="<?php echo $column01_headline_url; ?>">
                                    <strong><?php echo $column01_headline; ?></strong>
                                </a>
                            <?php else : ?>
                                <strong><?php echo $column01_headline; ?></strong>
                            <?php endif; ?>
                            </h3>
                        </header>
                    <?php endif; ?>
                    <?php echo $column01_content; ?>
                </section>
                <section class="4u feature">
                    <span class="feature-icon"><span class="fa fa-heart"></span></span>
                    <?php if( $column02_headline ): ?>
                        <header>
                            <h3>
                            <?php if( $column02_headline_url ): ?>
                                <a href="<?php echo $column02_headline_url; ?>">
                                    <strong><?php echo $column02_headline; ?></strong>
                                </a>
                            <?php else : ?>
                                <strong><?php echo $column02_headline; ?></strong>
                            <?php endif; ?>
                            </h3>
                        </header>
                    <?php endif; ?>
                    <?php echo $column02_content; ?>
                </section>
                <section class="4u feature">
                    <span class="feature-icon"><span class="fa fa-scissors"></span></span>
                    <?php if( $column03_headline ): ?>
                        <header>
                            <h3>
                            <?php if( $column03_headline_url ): ?>
                                <a href="<?php echo $column03_headline_url; ?>">
                                    <strong><?php echo $column03_headline; ?></strong>
                                </a>
                            <?php else : ?>
                                <strong><?php echo $column03_headline; ?></strong>
                            <?php endif; ?>
                            </h3>
                        </header>
                    <?php endif; ?>
                    <?php echo $column03_content; ?>
                </section>
            </div>
            <ul class="actions major">
                <?php if( get_field('subjects_button') ): ?>
                    <li><a href="#second" class="button scrolly"><?php the_field('subjects_button'); ?></a></li>
                <?php else: ?>
                    <li><a href="#second" class="button scrolly">Weiter...</a></li>
                <?php endif; ?>

            </ul>
        </section>

</div>



<div class="wrapper" id="second">
    <section class="container">
        <header class="major">
            <?php if( get_field('content_second') ): ?>
                <?php the_field('content_second'); ?>
            <?php endif; ?>
        </header>
    </section>
</div>


<div class="wrapper dark style2">
    <section class="container">
            <div class="row features">
                <section class="4u">
                    <?php if( get_field('latest_post_headline') ): ?>
                        <header>
                            <h3><?php the_field('latest_post_headline'); ?></h3>
                        </header>
                    <?php endif; ?>
                    <?php the_field('latest_post_content'); ?>
                </section>

                <?php
                    $args = array(
                        'post_type'      => array( 'p-strickmuetzen','p-genaehte-muetzen','p-haekelmuetzen','p-yogakissen' ),
                        'order'          => 'DESC',
                        'orderby'        => 'date',
                        'posts_per_page' => 6,
                    );
                    $strickmuetzen = new WP_Query($args);
                    $i = 0;
                ?>

                <section class="8u">
                    <?php

                    if ( $strickmuetzen->have_posts() ) : while ( $strickmuetzen->have_posts() ) : $strickmuetzen->the_post();

                        // Get Post Image
                        $image           = get_field('product_main_img');
                        $image_thumbnail = $image['sizes']['post-thumbnail'];

                        // Get Post Type and the title
                        $post_type = get_post_type_object(get_post_type());

                        // Get Post Type Name
                        if ($post_type) {
                            $post_type_name = esc_html($post_type->labels->singular_name);
                        }

                    ?>
                    <?php if($i == 0) { ?>
                        <div class="row no-collapse">
                    <?php } ?>
                        <section class="6u">
                            <a href="<?php the_permalink(); ?>" class="image fit">
                                <img src="<?php echo $image_thumbnail; ?>" alt="<?php echo $post_type_name . ': ' . get_the_title(); ?>" />
                            </a>

                        </section>

                    <?php
                        $i++;
                        if($i == 2) {
                            $i = 0; ?>
                        </div>
                    <?php } ?>

                    <?php endwhile; else : ?>
                        <?php get_template_part( 'template_parts/content','error' ); ?>
                    <?php endif; wp_reset_postdata(); ?>

                    <?php if($i > 0) { ?>
                        </div>
                    <?php } ?>

                </section>

            </div>
        </section>

</div>



<div class="wrapper">
    <section class="container">
        <header class="major">
            <?php if( get_field('content_third') ): ?>
                <?php the_field('content_third'); ?>
            <?php endif; ?>
        </header>
    </section>
</div>



<div id="promo-wrapper" class="bg2">
    <section id="promo">
        <?php if( get_field('promo_headline') ): ?>
            <h2><?php the_field('promo_headline'); ?></h2>
        <?php endif; ?>
        <a href="<?php the_field('promo_button_url'); ?>" class="button"><?php the_field('promo_button_text'); ?></a>
    </section>
</div>


<?php
    $category_name = 'lachyoga';
    $category_id   = get_cat_ID( $category_name );
    $category_link = get_category_link( $category_id );

    $args = array(
        'post_type'      => 'post',
        'cat'  => $category_id,
        'order'          => 'DESC',
        'orderby'        => 'date',
        'posts_per_page' => 3,
    );
    $news = new WP_Query($args);
?>

<div id="latest-news" class="wrapper">
    <section class="container">
        <header class="align-center">
            <h2><strong><?php echo get_cat_name( $category_id ); ?></strong></h2>
            <?php
                if( category_description( $category_id ) ):
                    echo category_description( $category_id );
                endif;
            ?>
        </header>

        <div class="row features">
            <?php if ( $news->have_posts() ) : while ( $news->have_posts() ) : $news->the_post(); ?>

            <section class="4u col-12-narrower">
                <?php get_template_part( 'template_parts/content','posts-latest' ); ?>
            </section>

            <?php endwhile; else : ?>
                <?php get_template_part( 'template_parts/content','error' ); ?>
            <?php endif; wp_reset_postdata(); ?>
        </div>

        <ul class="actions major">
            <li><a href="<?php echo esc_url( $category_link ); ?>" class="button dark">Alle Beiträge ansehen »</a></li>
        </ul>
    </section>
</div>


<?php get_footer(); ?>