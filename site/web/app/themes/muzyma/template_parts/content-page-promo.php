<?php
    $promo_content     = get_field('promo_content');
    $promo_button_text = get_field('promo_button_text');
    $promo_button_url  = get_field('promo_button_url');
?>

<?php if( $promo_content || $promo_button_text && $promo_button_url ) : ?>

<div id="promo-wrapper" class="bg2">
    <section id="promo">
        <?php if( $promo_content ) : ?>
            <?php echo $promo_content; ?>
        <?php endif; ?>

        <?php if( $promo_button_text && $promo_button_url ) : ?>
            <a href="<?php echo $promo_button_url; ?>" class="button"><?php echo $promo_button_text; ?></a>
        <?php endif; ?>
    </section>
</div>

<?php endif; ?>