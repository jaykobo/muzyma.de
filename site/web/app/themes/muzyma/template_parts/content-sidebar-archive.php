<li>

    <div class="post-info">
        <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
        <div class="post-meta"><span class="date"><?php echo get_the_date( 'd. M Y' ); ?></span> | <span class="category"><?php echo the_category(' '); ?></span></div>
    </div>
    <?php
        // IMAGE ALT TEXT: Grab Image Caption OR use Post Title
        if ( $alt = get_the_post_thumbnail_caption() ) {
            // Nothing to do here
        } else {
            $alt = get_the_title();
        }
    ?>
    <?php if( has_post_thumbnail() ): ?>
        <a href="<?php the_permalink(); ?>" class="image list-thumbnail" alt="<?php echo $alt?>">
            <?php the_post_thumbnail('thumbnail'); ?>
        </a>
    <?php endif; ?>
</li>