<?php

// ================================================================================================
// CUSTOM FUNCTIONS
// ================================================================================================


// Grab ACF image alt or use fallback:
if ( ! function_exists( 'acf_image_fallback_alt' ) ) {

    function acf_image_fallback_alt($image) {
        $image_alt = $image['alt'];

        if (!empty($image_alt)) {
            return $image_alt;
        } else {

            // Get Post Type and the title
            $post_type = get_post_type_object(get_post_type());
            $title     = get_the_title();

            // Get Post Type Name
            if ($post_type) {
                $post_type_name = esc_html($post_type->labels->singular_name);
            }

            // Generate fallback text
            $fallback_alt = $post_type_name . ' Modell: ' . $title;

            return $fallback_alt;
        }
    }
}

// Add custom class to prev/next links
add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="button dark"';
}




// ================================================================================================
// CUSTOM POST TYPES PAGE AND CATEGORY OPTIONS
// ================================================================================================

// All Products CPT should use ONE single-post_type-slug template:
add_filter( 'template_include', function( $template ) {
    if ( is_singular( array( 'p-strickmuetzen', 'p-genaehte-muetzen', 'p-haekelmuetzen', 'p-yogakissen' ) ) ) {
        $locate = locate_template( 'single-product.php', false, false );
        if ( ! empty( $locate ) ) {
            $template = $locate;
        }
    }
    return $template;
});


// Change single template for posts with category 'poesie':
add_filter( 'template_include', function( $template ) {
    if ( in_category( 'poesie' ) ) {
        $locate = locate_template( 'single-poesie.php', false, false );
        if ( ! empty( $locate ) ) {
            $template = $locate;
        }
    }
    return $template;
});






// ================================================================================================
// CUSTOMIZE THE_EXCERPT FUNCTION
// ================================================================================================


function wpse_allowedtags() {
// Add custom tags to this string
    return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
}

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) :

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
    global $post;
    $raw_excerpt = $wpse_excerpt;
        if ( '' == $wpse_excerpt ) {

            $wpse_excerpt = get_the_content('');
            $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
            $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
            $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
            $wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

            //Set the excerpt word count and only break after sentence is complete.
                $excerpt_word_count = 15;
                $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
                $tokens = array();
                $excerptOutput = '';
                $count = 0;

                // Divide the string into tokens; HTML tags, or words, followed by any whitespace
                preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

                foreach ($tokens[0] as $token) { 

                    if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
                    // Limit reached, continue until , ; ? . or ! occur at the end
                        $excerptOutput .= trim($token);
                        break;
                    }

                    // Add words to complete sentence
                    $count++;

                    // Append what's left of the token
                    $excerptOutput .= $token;
                }

            $wpse_excerpt = trim(force_balance_tags($excerptOutput));

                // $excerpt_end = ' <a href="'. esc_url( get_permalink() ) . '">' . '&nbsp;&raquo;&nbsp;' . sprintf(__( 'Read more about: %s &nbsp;&raquo;', 'wpse' ), get_the_title()) . '</a>'; 
                // $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 

                // $pos = strrpos($wpse_excerpt, '</');
                // if ($pos !== false)
                // // Inside last HTML tag
                // $wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
                // else
                // // After the content
                // $wpse_excerpt .= $excerpt_end; /*Add read more in new paragraph */

            return $wpse_excerpt;

        }
        return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

endif; 

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');





// ================================================================================================
// NAVIGATION
// ================================================================================================

add_action( 'after_setup_theme', 'mp_register_nav' );

function mp_register_nav() {
    register_nav_menu( 'nav_main', 'Navigation oben im Header');
    register_nav_menu( 'nav_footer', 'Navigation unten im Footer (Copyright)');
}





// ================================================================================================
// TinyMCE-EDITOR - ADD CUSTOM STYLES
// ================================================================================================

// Activate Formats Dropdown:
function mp_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'mp_mce_buttons_2' );


// Add Custom Styles to Formats Dropdown:
function mp_mce_before_init_insert_formats( $init_array ) {
    $style_formats = array(
    // Button
        array(
            'title' => 'Button',
            'selector' => 'a',
            'classes' => 'button',
        ),
    // Button Dark
        array(
            'title' => 'Button Dunkel',
            'selector' => 'a',
            'classes' => 'button dark',
        ),
    // Smiley List
        array(
            'title' => 'Smiley Listenpunkte',
            'selector' => 'ul',
            'classes' => 'list-type-smiley',
        ),
    // Default Listitem within Smiley List
        array(
            'title' => 'Normaler Listenpunkt',
            'selector' => 'ul.list-type-smiley li',
            'classes' => 'default',
        ),
    // 2-Column Text
        array(
            'title' => 'Text 2-Spaltig',
            'block' => 'div',
            'classes' => 'multicolumn columns-2',
            'wrapper' => 'true',
        ),
    // 3-Column Text
        array(
            'title' => 'Text 3-Spaltig',
            'block' => 'div',
            'classes' => 'multicolumn columns-3',
            'wrapper' => 'true',
        ),
    );
    $init_array['style_formats'] = json_encode( $style_formats );
    return $init_array; 
}
add_filter( 'tiny_mce_before_init', 'mp_mce_before_init_insert_formats' );



// Add custom stylesheet for TinyMCE:
function mp_custom_editor_stylesheet() {
   add_editor_style('css/editor-style.css');
}
add_action( 'admin_head', 'mp_custom_editor_stylesheet' ); 






// ================================================================================================
// SHORTCODES
// ================================================================================================

// Add Shortcode
function my_custom_test_shortcode( $atts , $content = null ) {

    return '<strong>' . $content . '</strong>';

}
add_shortcode( 'xxtest', 'my_custom_test_shortcode' );




// ================================================================================================
// THEME SETUP
// ================================================================================================

// Register Theme Features
function mp_theme_features()  {

// THEME SUPPORT:

    // Add theme support for Post Formats
    add_theme_support( 'post-formats', array( 'video' ) );

    // Add theme support for Featured Images
    add_theme_support( 'post-thumbnails' );

    // Add theme support for HTML5 Semantic Markup
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Add theme support for document Title tag
    add_theme_support( 'title-tag' );


// CUSTOM IMAGE OPTIONS / SIZES:

    // Reduce jpg quality
    add_filter('jpeg_quality', function( $arg ){ return 85; });

    // Set Post Thumbnail Size
    set_post_thumbnail_size( 400, 200, array( 'center', 'center') ); // Hard Crop Mode

    // Product Thumbnails
    add_image_size( 'single-post', 800, 400, array( 'center', 'center') ); // Hard Crop Mode

}
add_action( 'after_setup_theme', 'mp_theme_features' );






// ================================================================================================
// REMOVE DEFAULT WP IMAGE SIZES
// ================================================================================================

function mp_remove_default_image_sizes( $sizes) {
    unset( $sizes['medium_large']); // 768

    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'mp_remove_default_image_sizes');






// ================================================================================================
// ADD CUSTOM IMAGE SIZE INTO MEDIA UPLOADER
// ================================================================================================

 
function mp_new_image_sizes($sizes) {
    $addsizes = array(
        "post-thumbnail" => 'Post Vorschaubild',
        "single-post" => 'Featured Image'
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}
add_filter('image_size_names_choose', 'mp_new_image_sizes');





// ================================================================================================
// BREADCRUMB NAVIGATION
// ================================================================================================

function nav_breadcrumb() {

 $delimiter = '&raquo;';
 $home = 'Home'; 
 $before = '<span class="current-page">'; 
 $after = '</span>'; 

 if ( !is_home() && !is_front_page() || is_paged() ) {

     echo '<nav class="breadcrumb container">';

     global $post;
     $homeLink = get_bloginfo('url');
     echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

     if ( is_category()) {
         global $wp_query;
         $cat_obj = $wp_query->get_queried_object();
         $thisCat = $cat_obj->term_id;
         $thisCat = get_category($thisCat);
         $parentCat = get_category($thisCat->parent);
         if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
         echo $before . single_cat_title('', false) . $after;

     } elseif ( is_day() ) {
         echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
         echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
         echo $before . get_the_time('d') . $after;

     } elseif ( is_month() ) {
         echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
         echo $before . get_the_time('F') . $after;

     } elseif ( is_year() ) {
         echo $before . get_the_time('Y') . $after;

     } elseif ( is_single() && !is_attachment() ) {
         if ( get_post_type() != 'post' ) {
             $post_type = get_post_type_object(get_post_type());
             $slug = $post_type->rewrite;
             echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
             echo $before . get_the_title() . $after;
         } else {
             $cat = get_the_category(); $cat = $cat[0];
             echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
             echo $before . get_the_title() . $after;
         }

     } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
         $post_type = get_post_type_object(get_post_type());
         echo $before . $post_type->labels->singular_name . $after;


     } elseif ( is_attachment() ) {
         $parent = get_post($post->post_parent);
         $cat = get_the_category($parent->ID); $cat = $cat[0];
         echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
         echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
         echo $before . get_the_title() . $after;

     } elseif ( is_page() && !$post->post_parent ) {
         echo $before . get_the_title() . $after;

     } elseif ( is_page() && $post->post_parent ) {
         $parent_id = $post->post_parent;
         $breadcrumbs = array();
         while ($parent_id) {
             $page = get_page($parent_id);
             $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
             $parent_id = $page->post_parent;
         }
         $breadcrumbs = array_reverse($breadcrumbs);
         foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
         echo $before . get_the_title() . $after;

     } elseif ( is_search() ) {
         echo $before . 'Ergebnisse für Ihre Suche nach "' . get_search_query() . '"' . $after;

     } elseif ( is_tag() ) {
         echo $before . 'Beiträge mit dem Schlagwort "' . single_tag_title('', false) . '"' . $after;

     } elseif ( is_404() ) {
         echo $before . 'Fehler 404' . $after;
     }

     if ( get_query_var('paged') ) {
         if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
         echo ': ' . 'Seite' . ' ' . get_query_var('paged');
         if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
     }

     echo '</nav>';

 } 
}






// ================================================================================================
// CUSTOM POST TYPES
// ================================================================================================

// Register Custom Post Type - Strickmützen
function mp_cpt_strickmuetzen() {

    $labels = array(
        'name'                  => 'Strickmützen',
        'singular_name'         => 'Strickmütze',
        'menu_name'             => 'Strickmützen',
        'name_admin_bar'        => 'Strickmützen',
        'archives'              => 'Strickmützen',
        'attributes'            => '',
        'parent_item_colon'     => '',
        'all_items'             => 'Alle Strickmützen',
        'add_new_item'          => 'Neue Strickmütze hinzufügen',
        'add_new'               => 'Hinzufügen',
        'new_item'              => 'Neue Strickmütze',
        'edit_item'             => 'Strickmütze bearbeiten',
        'update_item'           => '',
        'view_item'             => 'Strickmütze ansehen',
        'view_items'            => 'Strickmützen ansehen',
        'search_items'          => 'Strickmützen durchsuchen',
        'not_found'             => 'Keine Strickmützen gefunden',
        'not_found_in_trash'    => 'Keine Strickmützen im Papierkorb gefunden',
    );
    $rewrite = array(
        'slug'                  => 'handgemacht/strickmuetzen',
        'with_front'            => false,
    );
    $args = array(
        'label'                 => 'Strickmütze',
        'description'           => 'Strickmützen',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 30,
        'menu_icon'             => 'http://muzyma.app/app/themes/muzyma/img/knit_cap-icon.png',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => $rewrite,
    );
    register_post_type( 'p-strickmuetzen', $args );
}
add_action( 'init', 'mp_cpt_strickmuetzen', 0 );



// Register Custom Post Type - Genähte Mützen
function mp_cpt_genaehte_muetzen() {

    $labels = array(
        'name'                  => 'Genähte Mützen',
        'singular_name'         => 'Genähte Mütze',
        'menu_name'             => 'Genähte Mützen',
        'name_admin_bar'        => 'Genähte Mützen',
        'archives'              => 'Genähte Mützen',
        'attributes'            => '',
        'parent_item_colon'     => '',
        'all_items'             => 'Alle genähte Mützen',
        'add_new_item'          => 'Neue genähte Mütze hinzufügen',
        'add_new'               => 'Hinzufügen',
        'new_item'              => 'Neue genähte Mütze',
        'edit_item'             => 'Genähte Mütze bearbeiten',
        'update_item'           => '',
        'view_item'             => 'Genähte Mütze ansehen',
        'view_items'            => 'Genähte Mützen ansehen',
        'search_items'          => 'Genähte Mützen durchsuchen',
        'not_found'             => 'Keine genähte Mützen gefunden',
        'not_found_in_trash'    => 'Keine genähte Mützen im Papierkorb gefunden',
    );
    $rewrite = array(
        'slug'                  => 'handgemacht/genaehte-muetzen',
        'with_front'            => false,
    );
    $args = array(
        'label'                 => 'Genähte Mützen',
        'description'           => 'Genähte Mützen',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 31,
        'menu_icon'             => 'http://muzyma.app/app/themes/muzyma/img/sew_machine-icon.png',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => $rewrite,
    );
    register_post_type( 'p-genaehte-muetzen', $args );
}
add_action( 'init', 'mp_cpt_genaehte_muetzen', 0 );



// Register Custom Post Type - Häkelmützen
function mp_cpt_haekelmuetzen() {

    $labels = array(
        'name'                  => 'Häkelmützen',
        'singular_name'         => 'Häkelmütze',
        'menu_name'             => 'Häkelmützen',
        'name_admin_bar'        => 'Häkelmützen',
        'archives'              => 'Häkelmützen',
        'attributes'            => '',
        'parent_item_colon'     => '',
        'all_items'             => 'Alle Häkelmützen',
        'add_new_item'          => 'Neue Häkelmütze hinzufügen',
        'add_new'               => 'Hinzufügen',
        'new_item'              => 'Neue Häkelmütze',
        'edit_item'             => 'Häkelmütze bearbeiten',
        'update_item'           => '',
        'view_item'             => 'Häkelmütze ansehen',
        'view_items'            => 'Häkelmützen ansehen',
        'search_items'          => 'Häkelmützen durchsuchen',
        'not_found'             => 'Keine Häkelmützen gefunden',
        'not_found_in_trash'    => 'Keine Häkelmützen im Papierkorb gefunden',
    );
    $rewrite = array(
        'slug'                  => 'handgemacht/haekelmuetzen',
        'with_front'            => false,
    );
    $args = array(
        'label'                 => 'Häkelmützen',
        'description'           => 'Häkelmützen',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 32,
        // 'menu_icon'             => 'http://muzyma.app/app/themes/muzyma/img/knit_cap-icon.png',
        'menu_icon'             => 'dashicons-warning',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => $rewrite,
    );
    register_post_type( 'p-haekelmuetzen', $args );
}
add_action( 'init', 'mp_cpt_haekelmuetzen', 0 );



// Register Custom Post Type - Yogakissen
function mp_cpt_yogakissen() {

    $labels = array(
        'name'                  => 'Yogakissen',
        'singular_name'         => 'Yogakissen',
        'menu_name'             => 'Yogakissen',
        'name_admin_bar'        => 'Yogakissen',
        'archives'              => 'Yogakissen',
        'attributes'            => '',
        'parent_item_colon'     => '',
        'all_items'             => 'Alle Yogakissen',
        'add_new_item'          => 'Neue Yogakissen hinzufügen',
        'add_new'               => 'Hinzufügen',
        'new_item'              => 'Neue Yogakissen',
        'edit_item'             => 'Yogakissen bearbeiten',
        'update_item'           => '',
        'view_item'             => 'Yogakissen ansehen',
        'view_items'            => 'Yogakissen ansehen',
        'search_items'          => 'Yogakissen durchsuchen',
        'not_found'             => 'Keine Yogakissen gefunden',
        'not_found_in_trash'    => 'Keine Yogakissen im Papierkorb gefunden',
    );
    $rewrite = array(
        'slug'                  => 'handgemacht/yogakissen',
        'with_front'            => false,
    );
    $args = array(
        'label'                 => 'Yogakissen',
        'description'           => 'Yogakissen',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 33,
        'menu_icon'             => 'http://muzyma.app/app/themes/muzyma/img/pillow-icon.png',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => $rewrite,
    );
    register_post_type( 'p-yogakissen', $args );
}
add_action( 'init', 'mp_cpt_yogakissen', 0 );



// Register Custom Post Type - Kunstgalerie
function mp_cpt_kunstgalerie() {

    $labels = array(
        'name'                  => 'Kunstgalerie',
        'singular_name'         => 'Kunstgalerie',
        'menu_name'             => 'Kunstgalerie',
        'name_admin_bar'        => 'Kunstgalerie',
        'archives'              => 'Kunstgalerie',
        'attributes'            => '',
        'parent_item_colon'     => '',
        'all_items'             => 'Alle Bilder',
        'add_new_item'          => 'Neues Bild hinzufügen',
        'add_new'               => 'Hinzufügen',
        'new_item'              => 'Neues Bild',
        'edit_item'             => 'Bild bearbeiten',
        'update_item'           => '',
        'view_item'             => 'Bild ansehen',
        'view_items'            => 'Bilder ansehen',
        'search_items'          => 'Bilder durchsuchen',
        'not_found'             => 'Keine Bilder gefunden',
        'not_found_in_trash'    => 'Keine Bilder im Papierkorb gefunden',
    );
    $rewrite = array(
        'slug'                  => 'kunstgalerie/bild',
        'with_front'            => false,
    );
    $args = array(
        'label'                 => 'Kunstgalerie',
        'description'           => 'Kunstgalerie',
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 34,
        'menu_icon'             => 'dashicons-format-gallery',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,        
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => $rewrite,
    );
    register_post_type( 'gallery', $args );
}
add_action( 'init', 'mp_cpt_kunstgalerie', 0 );





// ================================================================================================
// CUSTOM TAXONOMY
// ================================================================================================

// Register Custom Taxonomy - Material
function mp_ct_material() {

    $labels = array(
        'name'                       => 'Materialien',
        'singular_name'              => 'Material',
        'menu_name'                  => 'Material',
        'all_items'                  => 'Alle Materialien',
        'parent_item'                => 'Übergeordnetes Material',
        'parent_item_colon'          => 'Übergeordnetes Material:',
        'new_item_name'              => 'Erstellen',
        'add_new_item'               => 'Neue Materialien erstellen',
        'edit_item'                  => 'Material bearbeiten',
        'update_item'                => 'Material aktualisieren',
        'view_item'                  => 'Anzeigen',
        'separate_items_with_commas' => 'Materialien mit Kommas trennen',
        'add_or_remove_items'        => 'Hinzufügen oder entfernen',
        'choose_from_most_used'      => 'Aus den meist genutzten auswählen',
        'popular_items'              => 'Populäre Materialien',
        'search_items'               => 'Materialien durchsuchen',
        'not_found'                  => 'Nichts gefunden',
        'no_terms'                   => 'Keine Materialien',
        'items_list'                 => 'Auflistung',
        'items_list_navigation'      => 'Auflistung Navigation',
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => true,
    );
    $registerTo = array(
        'p-strickmuetzen',
        'p-genaehte-muetzen',
        'p-haekelmuetzen',
        'p-yogakissen'
    );

    register_taxonomy( 'material', $registerTo, $args );
}
add_action( 'init', 'mp_ct_material', 0 );




// Register Custom Taxonomy - Attribute
// add_action( 'init', 'mp_ct_attribute', 0 );

// function mp_ct_attribute() {

//     $labels = array(
//         'name'                       => 'Attribute',
//         'singular_name'              => 'Attribute',
//         'menu_name'                  => 'Attribute',
//         'all_items'                  => 'Alle Attribute',
//         'parent_item'                => 'Übergeordnetes Attribute',
//         'parent_item_colon'          => 'Übergeordnetes Attribute:',
//         'new_item_name'              => 'Erstellen',
//         'add_new_item'               => 'Neue Attribute erstellen',
//         'edit_item'                  => 'Attribute bearbeiten',
//         'update_item'                => 'Attribute aktualisieren',
//         'view_item'                  => 'Anzeigen',
//         'separate_items_with_commas' => 'Attribute mit Kommas trennen',
//         'add_or_remove_items'        => 'Hinzufügen oder entfernen',
//         'choose_from_most_used'      => 'Aus den meist genutzten auswählen',
//         'popular_items'              => 'Populäre Attribute',
//         'search_items'               => 'Attribute durchsuchen',
//         'not_found'                  => 'Nichts gefunden',
//         'no_terms'                   => 'Keine Attribute',
//         'items_list'                 => 'Auflistung',
//         'items_list_navigation'      => 'Auflistung Navigation',
//     );
//     $args = array(
//         'labels'                     => $labels,
//         'hierarchical'               => false,
//         'public'                     => true,
//         'show_ui'                    => true,
//         'show_admin_column'          => true,
//         'show_in_nav_menus'          => true,
//         'show_tagcloud'              => true,
//     );
    // register_taxonomy( 'attribute', array( 'strickmuetzen', 'genaehte-muetzen', 'haekelmuetzen', 'yogakissen' ), $args );
// }


?>