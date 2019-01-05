<!DOCTYPE HTML>
<html lang="<?php bloginfo( 'language' ); ?>">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->

        <!--[if lte IE 8]><script src="<?php bloginfo( 'template_directory' ) ?>/css/ie/html5shiv.js"></script><![endif]-->

        <script src="<?php bloginfo( 'template_directory' ) ?>/js/jquery.min.js"></script>
        <script src="<?php bloginfo( 'template_directory' ) ?>/js/jquery.dropotron.min.js"></script>
        <script src="<?php bloginfo( 'template_directory' ) ?>/js/skel.min.js"></script>
        <script src="<?php bloginfo( 'template_directory' ) ?>/js/skel-layers.min.js"></script>
        <script src="<?php bloginfo( 'template_directory' ) ?>/js/init.js"></script>

        <script src="<?php bloginfo( 'template_directory' ) ?>/js/imagelightbox.js"></script>

        <noscript>
            <link rel="stylesheet" href="<?php bloginfo( 'template_directory' ) ?>/css/skel.css" />
            <link rel="stylesheet" href="<?php bloginfo( 'template_directory' ) ?>/css/style.css" />
        </noscript>

        <link rel="stylesheet" href="<?php bloginfo( 'template_directory' ) ?>/css/custom.css" />
        <!--[if lte IE 8]><link rel="stylesheet" href="<?php bloginfo( 'template_directory' ) ?>/css/ie/v8.css" /><![endif]-->

        <?php wp_head(); ?>
    </head>
    <?php
        $bodyclass = '';

        if (is_front_page()) {
            $bodyclass = 'homepage';
        }
    ?>
    <body <?php body_class($bodyclass); ?>>

        <!-- Header -->
            <div id="header-wrapper" class="site-header">
                <div id="header" class="container">

                    <!-- Logo -->
                  <h1 id="logo"><a href="<?php echo home_url('/'); ?>"><?php bloginfo( 'name' ); ?></a></h1>

                    <!-- Nav -->
                    <?php
                        $args =  array(
                            'theme_location'  => 'nav_main',
                            'container'       => 'nav',
                            'container_id'    => 'nav',
                            'fallback_cb'     => false,
                            'items_wrap'      => '<ul>%3$s</ul>',
                            'depth'           => 2,
                        );
                        wp_nav_menu($args);
                    ?>


                </div>

            <?php if ( !is_front_page()) { ?>
                </div>
            <?php } ?>
