<section <?php post_class(); ?>>
    <header>
        <h3><?php the_title(); ?></h3>
    </header>
    <?php
        // Show Post Thumbnail
        if ( has_post_thumbnail() ) { ?>
            <span class="image featured">
                <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title() ?>" />
            </span>
         <?php }
         
        // If post has excerpt then show and add button with link to post - else show content, no link
        if ( has_excerpt() ) { ?>
            <?php the_excerpt(); ?>
            <p><a href="<?php the_permalink(); ?>" class="button">Weiterlesen »</a></p>
        <?php } else {
            the_content();
        }
    ?>
</section>