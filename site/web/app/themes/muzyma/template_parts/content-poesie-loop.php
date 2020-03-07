<section class="4u feature">

    <header>
        <h3><a href="<?php the_permalink(); ?>"><strong><?php the_title(); ?></strong></a></h3>
    </header>

    <?php the_excerpt();?>

    <ul class="actions">
        <li><a href="<?php the_permalink(); ?>" class="button">Weiterlesen &raquo;</a></li>
    </ul> 
</section>