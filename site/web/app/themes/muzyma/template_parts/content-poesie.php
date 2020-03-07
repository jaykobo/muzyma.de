<article <?php post_class('feature'); ?>>

    <header>
        <h2><?php the_title(); ?></h2>
        <?php if( get_field('subtitel') ): ?>
            <p><?php the_field('subtitel'); ?></p>
        <?php endif; ?>
    </header>

    <?php the_content(); ?>
</article>