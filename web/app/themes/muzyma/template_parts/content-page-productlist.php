<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php
        $productpage_sidebar_headline = get_field('productpage_sidebar_headline');
        $productpage_sidebar_image    = get_field('productpage_sidebar_image');
        $productpage_sidebar_content  = get_field('productpage_sidebar_content');
    ?>

    <?php if( $productpage_sidebar_headline || $productpage_sidebar_image || $productpage_sidebar_content ) : ?>

    <div class="row grid features">
        <section class="8u">
            <?php get_template_part( 'template_parts/content','page' ); ?>
        </section>

        <section class="4u">
            <?php if( $productpage_sidebar_headline ) : ?>
            <header>
                <h3><?php echo $productpage_sidebar_headline; ?></h3>
            </header>
            <?php endif; ?>
            
            <?php if( $productpage_sidebar_image ) : ?>
            <a href="<?php echo $productpage_sidebar_image['sizes']['large']; ?>" class="image featured lightbox">
                <img src="<?php echo $productpage_sidebar_image['sizes']['post-thumbnail']; ?>" alt="<?php echo $productpage_sidebar_image['alt']; ?>" />
            </a>
            <?php endif; ?>

            <?php if( $productpage_sidebar_content ) : echo $productpage_sidebar_content; endif; ?>
        </section>
    </div>

    <?php else : ?>
        <?php get_template_part( 'template_parts/content','page' ); ?>
    <?php endif; ?>

    <?php endwhile; else : ?>
        <?php get_template_part( 'template_parts/content','error' ); ?>
<?php endif; ?>
