<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </header>

    <div class="post-meta">
        <?php
            if ( get_the_date( 'U' ) !== get_the_modified_date( 'U' ) ) { ?>
                Zuletzt aktualisiert am <time datetime="<?php the_modified_time('c')?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time>
            <?php } else { ?>
                Veröffentlicht am <time datetime="<?php the_time('c')?>" itemprop="datePublished"><?php echo get_the_date('j. F Y'); ?></time>
        <?php } ?>
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
    <div class="image-wrapper">
        <a href="<?php the_permalink(); ?>" class="image single-thumbnail">
            <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php echo $alt ?>" />
        </a>
    </div>
    <?php endif; ?>

    <p><?php if( get_field('subtitel') ): ?><strong><?php echo get_field('subtitel'); ?></strong> <?php endif; echo substr(get_the_excerpt(), 0,120).'...'; ?></p>
    <p><a href="<?php the_permalink(); ?>" class="button light">Beitrag weiterlesen &raquo;</a></p>

</article>