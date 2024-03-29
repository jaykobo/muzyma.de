<?php
    // Get category name and url
    $category = get_the_category();

    if ( !empty ( $category ) ) {
        $category_id   = $category[0]->cat_ID;
        $category_name = $category[0]->cat_name;
        $category_url  = get_category_link( $category_id );
    }
?>

<article <?php post_class(); ?>>
    <?php // <mark>template parts: content-page.php</mark> ?>
    <header>
        <h2><?php the_title(); ?></h2>

        <?php if( get_field('subtitel') ): ?>
            <p><?php the_field('subtitel'); ?></p>
        <?php endif; ?>

        <?php if( is_single() ): ?>
            <div class="post-meta">
                <?php
                    if ( get_the_date( 'U' ) !== get_the_modified_date( 'U' ) ) { ?>
                        Zuletzt aktualisiert am <time datetime="<?php the_modified_time('c')?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time>
                    <?php } else { ?>
                        Veröffentlicht am <time datetime="<?php the_time('c')?>" itemprop="datePublished"><?php echo get_the_date('j. F Y'); ?></time>
                <?php } ?>
                <?php if ( !empty ( $category ) ) { ?>
                    &nbsp;•&nbsp; <a href="<?php echo $category_url; ?>"><?php echo $category_name; ?></a>
                <?php } ?>
            </div>
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