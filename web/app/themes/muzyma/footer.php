        <!-- Footer -->
            <div id="footer-wrapper">
                <div id="footer" class="container">
                    <header class="major">
                        <h2>Worauf wartest Du noch?</h2>
                        <p>Hast Du Interesse an einer Mütze oder einem Yogakissen? Vielleicht in Deinen Lieblingsfarben oder einem bestimmten Strickmuster? Oder möchtest Du Lachyoga näher kennenlernen? Dann lass es mich einfach wissen und nimm Kontakt auf.</p>
                    </header>
                    <section class="container small">
                        <?php echo do_shortcode('[wpforms id="617" title="false" description="false"]'); ?>
                    </section>
                </div>
                <div id="copyright" class="container">
                    <p>2013 - <?php echo date('Y'); ?> &copy; Copyright <?php bloginfo( 'name' ); ?> • Zertifizierte Lachyoga Leiterin • Professionelle Strickerin • Künstlerin Rikto</p>

                    <?php
                        $args =  array(
                            'theme_location'  => 'nav_footer',
                            'container'       => '',
                            'container_class' => 'menu',
                            'fallback_cb'     => false,
                            'items_wrap'      => '<ul class = "%2$s">%3$s</ul>',
                            'depth'           => 1,
                        );
                        wp_nav_menu($args);
                    ?>
                </div>
            </div>

            <!-- Add Lightbox -->
            <?php
                if (is_page( 18 )) { ?>
                    <script>
                        $( function()
                        {
                            var

                                // ACTIVITY INDICATOR
                                activityIndicatorOn = function()
                                {
                                    $( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
                                },
                                activityIndicatorOff = function()
                                {
                                    $( '#imagelightbox-loading' ).remove();
                                },


                                // OVERLAY
                                overlayOn = function()
                                {
                                    $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
                                },
                                overlayOff = function()
                                {
                                    $( '#imagelightbox-overlay' ).remove();
                                },

                                // CAPTION
                                captionOn = function()
                                {
                                    var description = $( 'a[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"] img' ).attr( 'alt' );
                                    if( description.length > 0 )
                                        $( '<div id="imagelightbox-caption">' + description + '</div>' ).appendTo( 'body' );
                                },
                                captionOff = function()
                                {
                                    $( '#imagelightbox-caption' ).remove();
                                };


                            //  WITH OVERLAY & CAPTION & ACTIVITY INDICATION
                            $( '#galerie a' ).imageLightbox(
                            {
                                onStart:     function() { overlayOn(); },
                                onEnd:       function() { overlayOff(); captionOff(); activityIndicatorOff(); },
                                onLoadStart: function() { captionOff(); activityIndicatorOn(); },
                                onLoadEnd:   function() { captionOn(); activityIndicatorOff(); }
                            });


                        });
                    </script>
            <?php } elseif ( is_singular( array( 'p-strickmuetzen', 'p-genaehte-muetzen', 'p-haekelmuetzen', 'p-yogakissen' ) ) ) { ?>
                    <script>
                        $( function()
                        {
                            var

                                // ACTIVITY INDICATOR
                                activityIndicatorOn = function()
                                {
                                    $( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
                                },
                                activityIndicatorOff = function()
                                {
                                    $( '#imagelightbox-loading' ).remove();
                                },


                                // OVERLAY
                                overlayOn = function()
                                {
                                    $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
                                },
                                overlayOff = function()
                                {
                                    $( '#imagelightbox-overlay' ).remove();
                                };

                            //  WITH OVERLAY & CAPTION & ACTIVITY INDICATION

                            $( 'a.lightbox' ).imageLightbox(
                            {
                                onStart:     function() { overlayOn(); },
                                onEnd:       function() { overlayOff(); },
                                onLoadStart: function() { activityIndicatorOn(); },
                                onLoadEnd:   function() { activityIndicatorOff(); }
                            });


                        });
                    </script>
            <?php } else { ?>
                    <script>
                        $( function() {
                            $( 'a.lightbox, figure.wp-caption a' ).imageLightbox();
                            $( '.gallery-item a' ).imageLightbox().attr('data-imagelightbox', 'gallery');
                        });
                    </script>
            <?php } ?>

<?php wp_footer(); ?>
</body>
</html>