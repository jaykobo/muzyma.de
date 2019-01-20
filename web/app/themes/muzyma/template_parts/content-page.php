<article <?php post_class(); ?>>
    <mark>template parts: content-page.php</mark>
    <header>
        <h2><?php the_title(); ?></h2>
        <?php if( get_field('subtitel') ): ?>
            <p><?php the_field('subtitel'); ?></p>
        <?php endif; ?>
    </header>
    <?php
        // IMAGE ALT TEXT: Grab Image Caption OR use Post Title
        if ( $alt = get_the_post_thumbnail_caption() ) {
            // Nothing to do here
        } else {
            $alt = get_the_title();
        }
    ?>
    <?php if( has_post_thumbnail() ): ?>
        <a href="<?php the_post_thumbnail_url('large'); ?>" class="image featured lightbox" alt="<?php echo $alt?>">
            <?php the_post_thumbnail('single-post'); ?>
        </a>
    <?php endif; ?>

    <?php the_content(); ?>
</article>