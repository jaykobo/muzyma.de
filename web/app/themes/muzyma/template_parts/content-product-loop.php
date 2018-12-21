<?php

    // Get Post Image
    $image           = get_field('product_main_img');
    
    // vars
    $image_alt       = acf_image_fallback_alt($image); // grab image alt
    $image_thumbnail = $image['sizes']['post-thumbnail'];

?>

<section class="4u feature">
    <mark>template parts: content-product-loop.php</mark>

    <a href="<?php the_permalink(); ?>" class="image featured">
        <img src="<?php echo $image_thumbnail; ?>" alt="<?php echo($image_alt); ?>" />
    </a>
    <header>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </header>
</section>