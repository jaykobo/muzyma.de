<?php

    // Get Post Image
    $main_image       = get_field('product_main_img');

    // vars
    // $main_image_url   = $main_image['url'];
    $main_image_alt   = acf_image_fallback_alt($main_image); // grab image alt
    $main_image_large = $main_image['sizes'][ 'large' ];

?>

<article <?php post_class(); ?>>
    <mark>template parts: content-product.php</mark>

    <header>
        <h2><?php the_title(); ?></h2>
        <?php if( get_field('subtitel') ): ?>
            <p><?php the_field('subtitel'); ?></p>
        <?php endif; ?>
    </header>

    <div class="row oneandhalf">
        <section class="8u">
            <a href="<?php echo $main_image_large; ?>" class="image featured lightbox">
                <img src="<?php echo $main_image_large; ?>" alt="<?php echo $main_image_alt; ?>">
            </a>

            <?php the_content(); ?>
        </section>

        <section class="4u">

                <section id="sidebar">
                    <section>
                        <?php $fields = get_field('productimg_group'); ?>

                        <?php if ($fields['productimg_01']) { ?>
                            <header>
                                <h3>Weitere Bilder:</h3>
                            </header>
                        <?php
                        $i = 1;
                        foreach($fields as $field)
                            {
                                $product_image_thumbnail = $field['sizes']['post-thumbnail'];
                                $product_image           = $field['sizes']['large'];
                                $product_image_alt       = acf_image_fallback_alt($field);
                                $product_image_caption   = $field['caption'];

                                if ($field) { ?>
                                    <a href="<?php echo $product_image; ?>" class="image <?php if (!$product_image_caption) { echo 'featured'; }; ?> lightbox">
                                        <img src="<?php echo $product_image_thumbnail; ?>" <?php if ($product_image_alt) { echo 'alt="'.$product_image_alt.' 0'.$i.'"'; } ?> />
                                    </a>

                                <?php }
                                if ($product_image_caption) { ?>
                                    <p><?php echo $product_image_caption; ?></p>
                                <?php }
                                $i++;
                            }
                        } ?>

                        <ul class="actions">
                            <li><a href="#footer-wrapper" class="button scrolly">Die möchte ich gerne haben!</a></li>
                        </ul>
                    </section>
                </section>

        </section>
    </div>
</article>