        <!-- Footer -->
            <div id="footer-wrapper">
                <div id="footer" class="container">
                    <header class="major">
                    <?php
                        // Use Input from Customizer OR Fallback

                        // Footer Headline:
                        if ( get_theme_mod( 'footer_headline_field_id') != "" ) {
                            echo '<h2>'.get_theme_mod( 'footer_headline_field_id').'</h2>';
                        } else {
                            echo '<h2>Worauf wartest Du noch?</h2>';
                        }

                        // Footer Text:
                        if ( get_theme_mod( 'footer_textarea_field_id') != "" ) {
                            echo '<p>'.get_theme_mod( 'footer_textarea_field_id').'</p>';
                        } else {
                            echo '<p>Hast Du Interesse an einer Mütze oder einem Yogakissen? Vielleicht in Deinen Lieblingsfarben oder einem bestimmten Strickmuster? Oder möchtest Du Lachyoga näher kennenlernen? Dann lass es mich einfach wissen und nimm Kontakt auf.</p>';
                        }
                    ?>
                    </header>
                    <section class="container small">
                        <h4>Wir überarbeiten gerade unser Kontaktformular. Für direkt Anfragen wende dich bitte an <a href="mailto:info@muzyma.de">info@muzyma.de</a>. Danke für dein Verständnis.</h4>
                        <?php // echo do_shortcode('[wpforms id="617" title="false" description="false"]'); ?>
                    </section>
                </div>
                <div id="copyright" class="container">
                    <p>
                    <?php
                        echo date('Y') . ' &copy; Copyright ' . get_bloginfo( 'name' ) . ' ';

                        // Use Input from Customizer OR Fallback
                        // Footer Copyright:
                        if ( get_theme_mod( 'footer_copyright_field_id') != "" ) {
                            echo get_theme_mod( 'footer_copyright_field_id');
                        } else {
                            echo '• Zertifizierte Lachyoga Leiterin • Professionelle Strickerin • Künstlerin Rikto';
                        }
                    ?>
                    </p>


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

<?php wp_footer(); ?>
</body>
</html>