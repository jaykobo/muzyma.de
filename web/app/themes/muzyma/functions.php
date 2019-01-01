<?php

// ================================================================================================
// CUSTOM FUNCTIONS
// ================================================================================================


// Grab ACF image alt-text otherwise use fallback:
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




/*
 * ADD CUSTOM WP GALLERY OUTPUT
 * Customize the last half, the output half to suit your projects needs
 *
 * From https://stackoverflow.com/a/22660335
*/

add_filter('post_gallery', 'my_post_gallery', 10, 2);
function my_post_gallery($output, $attr) {
    global $post;

    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
        unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => ''
    ), $attr));

    $id = intval($id);
    if ('RAND' == $order) $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }

    if (empty($attachments)) return '';

    // Here's your actual output, you may customize it to your need
    $output = "<div class=\"gallery gallery-{$id}\">\n";
    $output .= "<div class=\"row\">\n";

    // Now you loop through each attachment
    foreach ($attachments as $id => $attachment) {
        // Fetch all data related to attachment
        $img = wp_prepare_attachment_for_js($id);

        // If you want a different size change 'large' to eg. 'medium'
        $url = $img['sizes']['full']['url']; // Target Image for opening in Lightbox
        $src = $img['sizes']['single-post']['url'];
        // $height = $img['sizes']['single-post']['height'];
        // $width = $img['sizes']['single-post']['width'];
        $alt = $img['alt'];

        // Store the caption
        $caption = $img['caption'];

        $output .= "<section class=\"6u\">\n";
        $output .= "<div class=\"image-wrapper\">\n";
        $output .= "<a href=\"{$url}\" class=\"image lightbox\">\n";
        $output .= "<img src=\"{$src}\" alt=\"{$alt}\" />\n";
        $output .= "</a>\n";
        $output .= "</div>\n";

        // Output the caption if it exists
        if ($caption) {
            $output .= "<figcaption class=\"wp-caption-text\">{$caption}</figcaption>\n";
        }
        $output .= "</section>\n";
    }

    $output .= "</div>\n";
    $output .= "</div>\n";

    return $output;
}




// ================================================================================================
// CUSTOMIZE THE_EXCERPT FUNCTION
//
// From https://wordpress.stackexchange.com/a/141136
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
    register_nav_menu( 'nav_main', 'Header');
    register_nav_menu( 'nav_footer', 'Footer');
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
            'title' => 'Smiley Listenpunkt',
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
// IMAGE SETTINGS
// ================================================================================================

// remove default wp image sizes:
function mp_remove_default_image_sizes( $sizes) {
    unset( $sizes['medium_large']); // 768

    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'mp_remove_default_image_sizes');



// Add custom image size into Media Uploader:
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
// CUSTOM POST TYPES -- PAGE AND CATEGORY OPTIONS
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
// REGISTER CUSTOM POST TYPES
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
        'slug'                  => 'strickmuetzen',
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
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="-31 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M406.105 382.684c0-99.305.942-104.094-3.066-125.305-14.156-75.676-60.898-108.586-120.36-142.211 8.75-11.977 13.93-26.738 13.93-42.688C296.61 32.52 264.09 0 224.13 0c-39.965 0-72.48 32.52-72.48 72.48 0 15.938 5.167 30.692 13.917 42.668-58.832 33.2-106.195 66.247-120.37 142.344-3.954 21.016-3.044 25.328-3.044 125.43C18.055 388.34 0 409.91 0 435.622v22.37C0 487.777 24.234 512 54.016 512h341.336c29.785 0 54.015-24.223 54.015-54.008v-22.37c0-26.102-18.61-47.942-43.262-52.938zm12.418 75.308c0 12.77-10.394 23.164-23.171 23.164h-32.43V412.45h32.43c12.648 0 23.171 10.274 23.171 23.172zm-387.68 0v-22.37c0-12.829 10.45-23.173 23.173-23.173h31.316v68.707H54.016c-12.778 0-23.172-10.394-23.172-23.164zm343.821-180.957c.777 8.457.598 13.977.598 33.403l-53.63-27.633a15.367 15.367 0 0 0-14.081-.024l-79.774 40.797-79.132-40.773a15.386 15.386 0 0 0-14.086-.024l-61.563 31.48c0-23.23-.094-27.738.297-33.3l68.258-34.922 79.133 40.785a15.375 15.375 0 0 0 14.086.02l79.77-40.805zM72.996 348.898l68.555-35.058 79.133 40.777a15.386 15.386 0 0 0 14.086.02l79.77-40.797 60.722 31.289v36.476H72.996zm43.18 63.551h51.922v68.707h-51.922zm82.765 0h50.375v68.707h-50.375zm81.22 0h51.921v68.707H280.16zM224.128 30.844c22.957 0 41.637 18.68 41.637 41.636 0 22.899-18.618 41.641-41.637 41.641-23.004 0-41.64-18.738-41.64-41.64 0-22.958 18.683-41.637 41.64-41.637zm-33.762 105.761c21.02 11.122 46.356 11.176 67.504.012 53.215 29.66 90.824 52.781 108.137 101.258L321.633 215a15.367 15.367 0 0 0-14.082-.023l-79.774 40.808L148.645 215a15.375 15.375 0 0 0-14.086-.023l-53.84 27.543c16.847-51.907 56.554-76.356 109.648-105.915zm0 0" fill="black" /></svg>'),
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
        'slug'                  => 'genaehte-muetzen',
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
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 -28 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M72 456c-39.703 0-72-32.297-72-72 0-4.426 3.574-8 8-8s8 3.574 8 8c0 30.871 25.129 56 56 56 4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M440 456c-4.426 0-8-3.574-8-8s3.574-8 8-8c13.23 0 24-10.77 24-24 0-4.426 3.574-8 8-8s8 3.574 8 8c0 22.055-17.945 40-40 40zm0 0M472.008 80c-4.422 0-8-3.574-8-8 0-13.23-10.77-24-24-24-4.422 0-8-3.574-8-8s3.578-8 8-8c22.055 0 40 17.945 40 40 0 4.426-3.574 8-8 8zm0 0M47.984 80a7.99 7.99 0 0 1-8-8c0-22.055 17.946-40 40-40 4.422 0 8 3.574 8 8s-3.578 8-8 8c-13.23 0-24 10.77-24 24 0 4.426-3.578 8-8 8zm0 0M79.984 256c-22.054 0-40-17.945-40-40 0-4.426 3.575-8 8-8 4.422 0 8 3.574 8 8 0 13.23 10.77 24 24 24 4.422 0 8 3.574 8 8s-3.578 8-8 8zm0 0M144 256c-4.426 0-8-3.574-8-8s3.574-8 8-8c13.23 0 24-10.77 24-24 0-4.426 3.574-8 8-8s8 3.574 8 8c0 22.055-17.945 40-40 40zm0 0M176 192a7.99 7.99 0 0 1-8-8c0-13.23 10.77-24 24-24 4.426 0 8 3.574 8 8s-3.574 8-8 8c-4.414 0-8 3.586-8 8 0 4.426-3.574 8-8 8zm0 0M311.992 192a7.99 7.99 0 0 1-8-8c0-4.414-3.586-8-8-8-4.426 0-8-3.574-8-8s3.574-8 8-8c13.23 0 24 10.77 24 24 0 4.426-3.578 8-8 8zm0 0" fill="black"/><path d="M8 392a7.99 7.99 0 0 1-8-8v-32c0-4.426 3.574-8 8-8s8 3.574 8 8v32c0 4.426-3.574 8-8 8zm0 0M472 424a7.99 7.99 0 0 1-8-8V72c0-4.426 3.574-8 8-8s8 3.574 8 8v344c0 4.426-3.574 8-8 8zm0 0M440 456H72c-4.426 0-8-3.574-8-8s3.574-8 8-8h368c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/><path d="M8 360a7.99 7.99 0 0 1-8-8c0-13.23 10.77-24 24-24 4.426 0 8 3.574 8 8s-3.574 8-8 8c-4.414 0-8 3.586-8 8 0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M88 344H24c-4.426 0-8-3.574-8-8s3.574-8 8-8h64c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M440.008 48H80c-4.426 0-8-3.574-8-8s3.574-8 8-8h360.008c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M47.984 224a7.99 7.99 0 0 1-8-8V72c0-4.426 3.575-8 8-8 4.422 0 8 3.574 8 8v144c0 4.426-3.578 8-8 8zm0 0M144 256H79.984c-4.425 0-8-3.574-8-8s3.575-8 8-8H144c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M311.992 344a7.99 7.99 0 0 1-8-8V184c0-4.426 3.574-8 8-8 4.422 0 8 3.574 8 8v152c0 4.426-3.578 8-8 8zm0 0M295.992 176H192c-4.426 0-8-3.574-8-8s3.574-8 8-8h103.992c4.422 0 8 3.574 8 8s-3.578 8-8 8zm0 0M176 224a7.99 7.99 0 0 1-8-8v-32c0-4.426 3.574-8 8-8s8 3.574 8 8v32c0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M88 344a7.99 7.99 0 0 1-8-8c0-8.824-7.176-16-16-16s-16 7.176-16 16c0 4.426-3.574 8-8 8s-8-3.574-8-8c0-17.648 14.352-32 32-32s32 14.352 32 32c0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M88 424a7.99 7.99 0 0 1-8-8v-80c0-4.426 3.574-8 8-8s8 3.574 8 8v80c0 4.426-3.574 8-8 8zm0 0M280 344a7.99 7.99 0 0 1-8-8c0-8.824-7.176-16-16-16-4.426 0-8-3.574-8-8s3.574-8 8-8c17.648 0 32 14.352 32 32 0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M280 424a7.99 7.99 0 0 1-8-8v-80c0-4.426 3.574-8 8-8s8 3.574 8 8v80c0 4.426-3.574 8-8 8zm0 0M256 320H64c-4.426 0-8-3.574-8-8s3.574-8 8-8h192c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/><path d="M280 424H88c-4.426 0-8-3.574-8-8s3.574-8 8-8h192c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M136 360a7.99 7.99 0 0 1-8-8v-16c0-4.426 3.574-8 8-8s8 3.574 8 8v16c0 4.426-3.574 8-8 8zm0 0M136 400a7.99 7.99 0 0 1-8-8v-16c0-4.426 3.574-8 8-8s8 3.574 8 8v16c0 4.426-3.574 8-8 8zm0 0M472 344H280c-4.426 0-8-3.574-8-8s3.574-8 8-8h192c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M112 320a7.99 7.99 0 0 1-8-8v-63.992c0-4.422 3.574-8 8-8s8 3.578 8 8V312c0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M128 288h-16.008c-4.426 0-8-3.574-8-8s3.574-8 8-8H128c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M496 88h-23.992c-4.422 0-8-3.574-8-8s3.578-8 8-8H496c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/><path d="M504 96a7.99 7.99 0 0 1-8-8v-.016c-4.426 0-8-3.57-8-7.992A7.989 7.989 0 0 1 496 72c8.824 0 16 7.176 16 16 0 4.426-3.574 8-8 8zm0 0M496 184h-23.992c-4.422 0-8-3.574-8-8s3.578-8 8-8H496c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/><path d="M496 184c-4.426 0-8-3.574-8-8s3.574-8 8-8c0-4.426 3.574-8 8-8s8 3.574 8 8c0 8.824-7.176 16-16 16zm0 0" fill="black"/><path d="M504 176a7.99 7.99 0 0 1-8-8V88c0-4.426 3.574-8 8-8s8 3.574 8 8v80c0 4.426-3.574 8-8 8zm0 0M480 120h-8c-4.426 0-8-3.574-8-8s3.574-8 8-8h8c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M480 152h-7.992c-4.422 0-8-3.574-8-8s3.578-8 8-8H480c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M392 304c-26.473 0-48-21.527-48-48s21.527-48 48-48 48 21.527 48 48-21.527 48-48 48zm0-80c-17.648 0-32 14.352-32 32s14.352 32 32 32 32-14.352 32-32-14.352-32-32-32zm0 0M392.238 200c-4.422 0-8.039-3.574-8.039-8s3.535-8 7.953-8h.086c4.418 0 8 3.574 8 8s-3.582 8-8 8zm0 0" fill="black"/><path d="M392.238 248c-4.422 0-8.039-3.574-8.039-8s3.535-8 7.953-8h.086c4.418 0 8 3.574 8 8s-3.582 8-8 8zm0 0M360.238 208.574c-4.422 0-8.039-3.574-8.039-8 0-4.422 3.535-8 7.953-8h.086c4.418 0 8 3.578 8 8 0 4.426-3.582 8-8 8zm0 0M336.816 232c-4.425 0-8.039-3.574-8.039-8s3.535-8 7.95-8h.09c4.413 0 8 3.574 8 8s-3.594 8-8 8zm0 0M447.656 232c-4.426 0-8.039-3.574-8.039-8s3.535-8 7.95-8h.09c4.413 0 8 3.574 8 8s-3.587 8-8 8zm0 0M424.223 208.574c-4.422 0-8.04-3.574-8.04-8 0-4.422 3.536-8 7.954-8h.086c4.418 0 8 3.578 8 8 0 4.426-3.582 8-8 8zm0 0M112 232a7.99 7.99 0 0 1-8-8V40c0-4.426 3.574-8 8-8s8 3.574 8 8v184c0 4.426-3.574 8-8 8zm0 0M432 128h-80a7.99 7.99 0 0 1-8-8V80c0-4.426 3.574-8 8-8h80c4.426 0 8 3.574 8 8v40c0 4.426-3.574 8-8 8zm-72-16h64V88h-64zm0 0M224 128c-13.23 0-24-10.77-24-24s10.77-24 24-24 24 10.77 24 24-10.77 24-24 24zm0-32c-4.414 0-8 3.586-8 8s3.586 8 8 8 8-3.586 8-8-3.586-8-8-8zm0 0M272 128c-4.426 0-8-3.574-8-8s3.574-8 8-8c4.414 0 8-3.586 8-8s-3.586-8-8-8c-4.426 0-8-3.574-8-8s3.574-8 8-8c13.23 0 24 10.77 24 24s-10.77 24-24 24zm0 0" fill="black"/><path d="M272 128h-48c-4.426 0-8-3.574-8-8s3.574-8 8-8h48c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M272 96h-48c-4.426 0-8-3.574-8-8s3.574-8 8-8h48c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0M272 48a7.99 7.99 0 0 1-8-8V8c0-4.426 3.574-8 8-8s8 3.574 8 8v32c0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M368 16h-96c-4.426 0-8-3.574-8-8s3.574-8 8-8h96c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/><path d="M368 48a7.99 7.99 0 0 1-8-8V8c0-4.426 3.574-8 8-8s8 3.574 8 8v32c0 4.426-3.574 8-8 8zm0 0M295.992 48c-1.199 0-2.426-.273-3.566-.84a8 8 0 0 1-3.578-10.734l16-32A8.013 8.013 0 0 1 315.586.848c3.95 1.976 5.55 6.785 3.574 10.738l-16 32A8.013 8.013 0 0 1 295.992 48zm0 0M327.992 48c-1.199 0-2.426-.273-3.566-.84a8 8 0 0 1-3.578-10.734l16-32A8.01 8.01 0 0 1 347.586.848c3.95 1.976 5.55 6.785 3.574 10.738l-16 32A8.013 8.013 0 0 1 327.992 48zm0 0M432 48a7.99 7.99 0 0 1-8-8V16c0-4.426 3.574-8 8-8s8 3.574 8 8v24c0 4.426-3.574 8-8 8zm0 0" fill="black"/><path d="M440.008 24H424c-4.426 0-8-3.574-8-8s3.574-8 8-8h16.008c4.426 0 8 3.574 8 8s-3.574 8-8 8zm0 0" fill="black"/></svg>'),
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
        'slug'                  => 'haekelmuetzen',
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
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M386.576 365.674c24.978-29.688 40.091-67.932 40.091-109.674 0-38.887-13.22-74.661-35.202-103.392l71.098-67.879C489.083 89.469 512 68.427 512 42.667 512 19.135 492.854 0 469.333 0s-42.667 19.135-42.667 42.667c0 2.229.208 4.469.604 6.771l-67.863 71.109C330.674 98.557 294.893 85.333 256 85.333s-74.674 13.224-103.408 35.214L84.729 49.438c.396-2.302.604-4.542.604-6.771C85.333 19.135 66.188 0 42.667 0 19.146 0 0 19.135 0 42.667 0 68.427 23.083 89.5 49.438 84.729l71.098 67.879C98.553 181.34 85.333 217.113 85.333 256c0 41.742 15.115 79.986 40.092 109.674L2.958 493.969c-4 4.188-3.938 10.813.167 14.906C5.208 510.958 7.938 512 10.667 512c2.646 0 5.292-.979 7.375-2.948l128.294-122.467c29.686 24.973 67.927 40.082 109.664 40.082s79.978-15.109 109.664-40.082l128.294 122.467c2.083 1.969 4.729 2.948 7.375 2.948 2.729 0 5.458-1.042 7.542-3.125 4.104-4.094 4.167-10.719.167-14.906L386.576 365.674zM446.75 59.917c2.708-2.844 3.646-6.938 2.438-10.667-.792-2.448-1.188-4.604-1.188-6.583 0-11.76 9.563-21.333 21.333-21.333 11.771 0 21.333 9.573 21.333 21.333 0 11.76-9.563 21.333-21.333 21.333-2.021 0-4.083-.375-6.542-1.177-3.729-1.26-7.854-.323-10.708 2.406l-74.544 71.156c-.639-.65-1.281-1.29-1.931-1.93l71.142-74.538zM59.917 65.229c-2.854-2.719-7.021-3.656-10.708-2.406C46.75 63.625 44.688 64 42.667 64c-11.771 0-21.333-9.573-21.333-21.333 0-11.76 9.563-21.333 21.333-21.333C54.438 21.333 64 30.906 64 42.667c0 1.979-.396 4.135-1.188 6.583-1.208 3.729-.271 7.823 2.438 10.667l71.142 74.538c-.65.639-1.29 1.283-1.93 1.931L59.917 65.229zm73.949 276.422c-17.078-24.276-27.199-53.781-27.199-85.651 0-82.344 67-149.333 149.333-149.333 31.858 0 61.354 10.113 85.628 27.181-13.948-3.624-28.296-5.848-42.961-5.848C204.563 128 128 204.563 128 298.667c0 14.679 2.237 29.032 5.866 42.984zm40.157 39.03c-16.064-24.385-24.69-52.658-24.69-82.014 0-82.344 67-149.333 149.333-149.333 29.337 0 57.62 8.581 81.987 24.646.362.547.668 1.129 1.022 1.68-13.129-3.188-26.589-4.992-40.342-4.992-94.104 0-170.667 76.563-170.667 170.667 0 13.758 1.809 27.219 5.003 40.348-.54-.348-1.11-.649-1.646-1.002zm81.898-161.66c36.206 25.26 59.066 64.523 63.188 108.1-39.797 18.576-86.561 18.556-126.384-.077 4.261-44.656 28.293-83.573 63.196-108.023zm53.928 176.105C293.117 401.628 275 405.333 256 405.333c-19.001 0-37.118-3.707-53.852-10.208-5.564-14.391-8.806-29.438-9.711-44.921 20.117 8.065 41.753 12.462 63.563 12.462 21.796 0 43.43-4.395 63.564-12.456-.905 15.47-4.145 30.52-9.715 44.916zm28.124-14.442c-.533.352-1.1.651-1.639.996 3.191-13.135 5-26.596 5-40.346 0-52.913-24.285-101.794-65.408-134.039 13.499-6.616 28.108-11.223 43.503-13.503 27.829 28.134 43.238 65.15 43.238 104.875 0 29.366-8.621 57.636-24.694 82.017zm8.6-188.427c16.762.574 33.039 3.881 48.552 9.888 6.503 16.733 10.208 34.852 10.208 53.855 0 31.868-10.12 61.372-27.195 85.647 3.628-13.949 5.862-28.301 5.862-42.98 0-39.321-13.256-76.297-37.427-106.41z"/></svg>'),
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
        'slug'                  => 'yogakissen',
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
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="-21 -101 682.667 682" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd" fill="black"><path d="M508.297 195.375c16.36 3.758 31.16 9.035 44.277 15.828 5.125 2.656 12.914 4.184 20.91-3.601 10.305-10.02 24.461-12.395 31.559-5.297 3.45 3.441 4.156 7.968 4.144 11.152-.015 6.957-3.484 14.523-9.265 20.23-4.899 4.844-5.817 12.168-2.336 18.668C609.14 273.906 615 296.391 615 319.175c0 26.075-5.82 48.493-17.305 66.618-3.984 6.277-3.004 13.945 2.438 19.07 5.722 5.395 9.062 12.492 9.164 19.465.074 4.746-1.442 8.906-4.254 11.719-7.078 7.082-21.246 4.7-31.57-5.32-8.547-8.274-17.18-5.575-21.567-3.223-27.656 14.805-64.597 22.316-109.789 22.316-45.195 0-82.133-7.511-109.789-22.316-4.39-2.352-13.023-5.05-21.562 3.23-10.328 10.012-24.493 12.391-31.578 5.313-2.813-2.813-4.32-6.973-4.25-11.719.101-6.973 3.445-14.07 9.171-19.469 5.438-5.129 6.407-12.793 2.434-19.066-11.484-18.125-17.305-40.543-17.305-66.617 0-22.785 5.856-45.27 17.407-66.817 3.433-6.402 2.496-13.902-2.332-18.671-5.782-5.708-9.243-13.274-9.27-20.231-.008-3.187.703-7.71 4.145-11.152 7.101-7.098 21.261-4.723 31.562 5.297 7.996 7.785 15.781 6.257 20.91 3.601 8.93-4.625 18.766-8.601 29.238-11.816 6.598-2.032 10.305-9.024 8.278-15.621-2.028-6.602-9.02-10.309-15.621-8.278-10.114 3.106-19.746 6.856-28.707 11.172-20.141-16.992-47.239-18.125-63.332-2.035-7.43 7.43-11.508 17.7-11.473 28.91.039 11.383 4.262 22.875 11.777 32.426-11.668 23.586-17.582 48.184-17.582 73.215 0 17.695 2.348 34.086 6.992 49.039-58.203-.328-105.96-10.25-141.968-29.527-5.871-3.145-14.746-5.079-23.68 3.582-14.941 14.492-35.8 17.585-46.488 6.89-4.403-4.398-6.766-10.8-6.656-18.023.144-10.11 4.937-20.34 13.14-28.074 5.727-5.395 6.762-13.461 2.578-20.075C32.79 258.742 25 228.84 25 194.113c0-30.34 7.79-60.25 23.156-88.914 3.719-6.945 2.79-14.734-2.375-19.832-8.363-8.262-13.172-18.851-13.207-29.058-.015-4.88 1.102-11.825 6.52-17.243 10.71-10.714 31.562-7.628 46.472 6.872 7.067 6.878 14.762 8.238 22.868 4.039 14.972-7.75 31.785-14.094 49.98-18.848 6.68-1.75 10.68-8.578 8.934-15.258-1.746-6.676-8.575-10.676-15.258-8.933-18.473 4.832-35.727 11.19-51.387 18.933C75.777 3.473 41.461 1.34 21.41 21.387 12.445 30.355 7.535 42.785 7.574 56.383c.047 14.734 5.828 29.648 16.051 41.793C7.945 129.059 0 161.305 0 194.113c0 36.719 7.844 69.008 23.324 96.102-9.96 11.613-15.672 26.047-15.886 40.558-.211 14.036 4.753 26.844 13.972 36.063 20.004 20.004 54.29 17.887 79.238-4.438 39.864 20.457 91.493 30.829 153.532 30.829 2.203 0 4.574-.02 6.89-.051-6.984 9.015-10.968 19.863-11.129 30.781-.171 11.563 3.942 22.133 11.575 29.766 16.047 16.047 43.109 14.93 63.265-1.985 30.746 15.317 70.184 23.082 117.336 23.082 47.156 0 86.598-7.765 117.34-23.082 20.156 16.914 47.219 18.028 63.27 1.985 7.628-7.633 11.742-18.203 11.57-29.766-.164-11.16-4.317-22.246-11.586-31.383C634.188 371.714 640 347.066 640 319.176c0-25.031-5.914-49.63-17.582-73.215 7.512-9.55 11.738-21.043 11.77-32.426.039-11.21-4.036-21.48-11.461-28.906-16.09-16.094-43.196-14.961-63.336 2.031-15.75-7.582-33.368-13.32-52.711-17.183-3.414-24.48-11.176-49.77-21.953-71.29 10.23-12.148 16.015-27.062 16.058-41.804.043-13.598-4.87-26.028-13.836-34.992C466.9 1.34 432.582 3.477 407.66 25.875 366.324 5.41 314.785-4.977 254.395-5h-.004c-6.899 0-12.496 5.598-12.5 12.496 0 6.902 5.59 12.5 12.492 12.504 58.203.023 107.172 10.113 145.543 29.98 8.113 4.196 15.8 2.836 22.87-4.042 14.915-14.5 35.759-17.586 46.477-6.872 5.41 5.41 6.532 12.364 6.512 17.243-.027 10.207-4.844 20.796-13.207 29.058-5.164 5.102-6.094 12.89-2.367 19.84 9.598 17.902 16.8 39.227 20.547 60.336a343.625 343.625 0 0 0-24.024-1.738c-6.855-.235-12.703 5.125-12.953 12.027-.261 6.898 5.121 12.7 12.02 12.953 9.547.356 18.734 1.094 27.558 2.211" /><path d="M418.793 177.79c0-6.888-5.613-12.5-12.5-12.5s-12.5 5.612-12.5 12.5c0 6.882 5.613 12.5 12.5 12.5s12.5-5.618 12.5-12.5zm0 0M204.457 22.613c6.887 0 12.5-5.617 12.5-12.5 0-6.886-5.613-12.5-12.5-12.5-6.883 0-12.5 5.61-12.5 12.5 0 6.883 5.617 12.5 12.5 12.5zm0 0" /></g></svg>'),
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
        'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="-10 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M479.426 165.215a11.545 11.545 0 0 0-.48-.387 32.55 32.55 0 0 0-18.782-6.531h-.078c-.106-.004-.211-.004-.313-.008h-.101c-.129-.004-.258-.004-.387-.004h-69.98L279.075 54.63a37.103 37.103 0 0 0 4.273-17.328C283.348 16.73 266.613 0 246.047 0s-37.3 16.73-37.3 37.3c0 6.25 1.546 12.15 4.276 17.329l-110.23 103.656H32.812c-.125 0-.253 0-.378.004h-.118c-.093 0-.187.004-.285.008h-.101l-.38.012a32.558 32.558 0 0 0-17.917 6.175 7.728 7.728 0 0 0-.356.27C5.227 170.738 0 180.32 0 191.105v288.079C-.004 497.277 14.719 512 32.816 512h426.801a32.58 32.58 0 0 0 19.184-6.2c.699-.5 1.32-1.085 1.86-1.738 7-6.023 11.44-14.94 11.44-24.878V191.105c-.003-10.507-4.968-19.878-12.675-25.89zm-36.168 278.039a24.066 24.066 0 0 0 1.941-9.5V236.53c0-3.308-.668-6.465-1.875-9.343l28.746-36.942c.02.281.028.57.028.856V479.18c0 .363-.012.722-.043 1.078zm-423.2 37.176c-.038-.41-.062-.828-.062-1.246V191.105c0-.496.027-.984.082-1.46l28.883 37.113a24.01 24.01 0 0 0-2.066 9.773v197.223c0 3.418.714 6.672 1.996 9.621zm195.665-103.117c4.332 5.183 8.515 10.617 12.558 15.867.164.215.328.425.492.636.02.028.036.051.055.075.031.039.059.078.086.113.356.46.711.922 1.066 1.383.028.035.051.066.079.097.261.344.527.688.793 1.028l.398.515c.555.715 1.11 1.434 1.664 2.149a.274.274 0 0 1 .031.039c.082.105.168.21.25.316.012.02.024.035.04.055.48.617.964 1.234 1.449 1.851.011.016.027.032.039.047l.257.329c.012.011.02.023.028.035l.265.336c.012.015.024.027.032.043.484.613.972 1.226 1.465 1.84.02.027.042.05.062.078a75110.007 75110.007 0 0 0 .578.718l.242.305c.02.02.035.043.051.062l.246.305c.024.028.043.05.063.078.074.086.144.176.215.266a.95.95 0 0 1 .078.098l.28.34v.003c.302.368.602.739.907 1.106.012.015.024.027.035.043.184.222.368.449.551.672.031.039.063.074.094.113.066.078.133.16.2.238.03.04.065.078.097.117.066.082.133.164.199.243.035.039.066.082.102.12.07.087.14.169.21.25.028.036.051.067.079.098l.234.282c.023.023.043.05.066.078l.25.297c.016.02.036.039.051.058l.613.727c.004.004.004.008.008.008.094.109.184.218.278.328.03.039.066.074.097.113.078.09.153.18.23.27a.68.68 0 0 1 .048.054c.082.094.16.188.242.281.031.04.066.075.097.114l.204.238.101.113c.067.082.137.16.207.242a1.4 1.4 0 0 0 .102.114c.082.101.168.199.254.297.011.011.02.023.03.03l.282.325a.389.389 0 0 0 .04.043l.937 1.066c.023.028.047.055.074.082.074.083.148.168.226.254.04.043.079.09.118.133l.191.215c.043.047.086.094.125.14l.191.208c.04.043.079.086.114.129.07.078.136.152.207.226.035.043.074.082.11.125.073.078.144.156.214.235.031.035.062.07.098.101.074.082.144.164.218.242l.106.117c.09.094.176.192.265.29.016.015.028.03.043.042a106.177 106.177 0 0 0 1.06 1.141c.073.082.151.164.226.242.035.04.07.075.105.114.07.074.14.144.207.218.043.043.082.086.121.13l.223.234c.031.035.062.066.094.097.07.078.144.153.218.23.04.044.078.087.121.126l.2.21.129.13c.078.082.152.164.23.242l.09.09c.215.226.433.449.652.671.035.036.067.067.102.098.078.082.16.168.242.25.027.027.05.055.078.078.086.086.168.168.25.254.04.035.074.074.113.113a43518.026 43518.026 0 0 1 .329.329l.238.238c.035.031.07.066.105.101.067.07.137.137.203.204l.149.148c.066.062.133.129.2.195.042.043.085.082.128.125.074.075.152.149.227.22l.113.112.687.664c.016.012.032.032.047.043l.293.282c.031.03.063.058.094.09l.258.246c.035.03.066.062.101.093l.23.22c.048.042.094.085.141.132.067.062.133.125.203.187.051.047.102.094.153.145.062.058.129.117.191.176.055.047.106.097.16.144.07.067.145.133.215.2.04.038.078.074.121.109.094.09.196.18.293.27.016.015.035.03.051.05l.352.317c.004.003.007.007.011.007.118.11.235.215.356.325l.098.085c.09.083.175.16.265.239l.102.09c.082.074.168.148.254.226.039.035.082.07.12.106.083.074.169.148.25.222.04.032.075.063.11.094.086.074.168.148.25.219.04.035.082.07.125.11.082.07.164.14.246.214.04.031.074.063.113.098.09.078.18.156.274.23.027.027.062.055.094.082.101.09.207.18.308.266.02.015.035.031.055.047l.316.27.067.054c.12.101.242.207.367.308a3634.875 3634.875 0 0 1 .383.32l.07.06.3.245c.036.032.071.06.106.09.098.078.188.153.282.23.03.024.062.052.093.075.094.078.192.156.285.234l.106.082c.094.079.184.153.277.227.04.027.074.059.114.09.09.07.183.144.273.219l.11.086c.105.082.206.164.308.246.023.015.047.035.07.054.117.094.239.184.356.278a43.331 43.331 0 0 0 .852.66l.363.281c.035.024.07.05.101.074.11.082.215.164.32.242.016.016.036.028.055.04a10.804 10.804 0 0 0 .43.324c.097.07.195.144.297.219.039.03.074.058.113.085.102.075.203.153.309.227.027.02.054.043.086.063.12.09.242.175.363.265.012.008.02.016.031.024.688.496 1.379.988 2.078 1.476.031.024.067.043.098.067l.32.222.102.067.328.222c.027.024.058.043.09.063.113.078.23.156.347.234.024.02.047.035.075.05.125.087.25.169.375.255.015.008.03.02.043.027.14.098.285.192.43.285H72.374c19.734-41.476 49.484-86.312 78.086-96.043 9.152-3.113 17.672-2.441 26.047 2.051 18.097 9.711 29.98 22.317 39.215 33.36zm20.035-7.079c7.836-10.765 28.199-32.773 59.644-25.363 15.032 3.547 22.938 13.734 32.946 26.633 16.582 21.367 37 47.691 96.851 48.668v13.578a4.197 4.197 0 0 1-4.191 4.191h-87.04c-.011-.003-.027-.003-.038-.003-.121-.012-.243-.028-.364-.04l-.214-.023c-.137-.012-.274-.027-.41-.043-.055-.008-.11-.012-.165-.02-.132-.011-.261-.027-.39-.042-.067-.004-.13-.012-.196-.02-.125-.016-.246-.027-.37-.043a110.86 110.86 0 0 1-.598-.07c-.059-.008-.114-.012-.168-.02-.149-.02-.297-.035-.446-.054-.039-.004-.082-.008-.12-.016l-.466-.059-.097-.011c-.145-.016-.29-.036-.438-.055-.043-.008-.09-.012-.133-.02l-.457-.058-.093-.012a18.328 18.328 0 0 0-.457-.062c-.036-.004-.07-.012-.106-.016-.14-.02-.281-.04-.426-.059-.043-.007-.09-.011-.136-.02-.145-.019-.285-.038-.426-.062-.043-.004-.082-.011-.121-.015-.156-.024-.309-.043-.457-.067l-.09-.011c-.137-.024-.277-.043-.414-.067-.05-.004-.098-.012-.149-.02-.14-.023-.28-.042-.418-.066-.039-.004-.074-.012-.113-.015l-.433-.07c-.04-.005-.075-.012-.114-.016-.148-.028-.3-.051-.449-.075l-.078-.011-.473-.082a.342.342 0 0 1-.062-.008l-.43-.074c-.039-.008-.078-.012-.117-.02-.145-.023-.285-.05-.426-.074a.912.912 0 0 0-.101-.016 17.119 17.119 0 0 0-.438-.078c-.027-.008-.059-.012-.086-.015-.164-.032-.324-.06-.488-.09-.008 0-.016-.004-.024-.004l-.5-.094c-.007-.004-.015-.004-.027-.004-.16-.031-.32-.062-.476-.094a111.852 111.852 0 0 0-.535-.102l-.024-.003-.453-.09c-.028-.004-.055-.012-.078-.016-.145-.03-.29-.058-.434-.086-.023-.007-.05-.011-.078-.015l-.48-.102c-.004 0-.008 0-.012-.004-.68-.14-1.348-.285-2.012-.433h-.004c-.828-.188-1.64-.38-2.45-.578-.007 0-.015-.004-.023-.004-.476-.121-.953-.242-1.425-.364-.008-.003-.016-.003-.024-.007-31.492-8.188-47.574-26.2-63.87-47.114l-.009-.007c-.613-.786-1.222-1.579-1.84-2.372-.113-.148-.23-.3-.347-.449l-1.578-2.047-.32-.418c-.215-.277-.43-.554-.645-.836-.317-.41-.633-.82-.95-1.234-.152-.2-.308-.398-.46-.598l-.27-.347a90.413 90.413 0 0 0-.558-.727l-.188-.242-.68-.879c-.05-.07-.105-.137-.16-.207a417.769 417.769 0 0 0-.738-.953c-.508-.656-1.02-1.313-1.535-1.973-.008-.007-.016-.02-.024-.03-.265-.337-.53-.677-.796-1.013l-.024-.03c-.25-.321-.504-.638-.754-.958l-.07-.086c-.246-.316-.496-.629-.746-.941l-.14-.176c-.095-.117-.188-.234-.278-.352zM246.047 20c9.539 0 17.3 7.762 17.3 17.3 0 9.54-7.761 17.302-17.3 17.302s-17.3-7.762-17.3-17.301c0-9.54 7.76-17.301 17.3-17.301zm-19.32 49.195a37.06 37.06 0 0 0 19.32 5.407 37.05 37.05 0 0 0 19.32-5.407l94.742 89.09H131.99zm229.308 109.09l-27.445 35.27a23.993 23.993 0 0 0-4.492-1.016c-.012-.004-.024-.004-.036-.004-.082-.012-.16-.02-.242-.031-.023 0-.047-.004-.07-.008l-.227-.023c-.02-.004-.039-.004-.058-.008-.098-.008-.192-.02-.29-.027h-.015l-.262-.024c-.027 0-.054-.004-.082-.004-.07-.004-.136-.012-.203-.015-.027 0-.058-.004-.09-.004a89.453 89.453 0 0 1-.23-.012c-.016-.004-.035-.004-.05-.004a3.292 3.292 0 0 0-.278-.012c-.028-.004-.059-.004-.086-.004-.067-.004-.137-.004-.203-.007h-.098c-.062 0-.125-.004-.187-.004h-.102c-.094-.004-.187-.004-.277-.004H209.047c-5.52 0-10 4.476-10 10s4.48 10 10 10h211.965c.039 0 .078 0 .113.004h.074c.012 0 .02.004.031.004.489.023.95.132 1.38.308.554.235.98.563 1.288.856l.168.156a4.174 4.174 0 0 1 1.133 2.863v163.64c-49.972-.859-65.754-21.218-81.05-40.933-10.938-14.09-22.243-28.664-44.157-33.832-22.96-5.41-45.082-.008-63.976 15.63-5.59 4.632-10.106 9.519-13.524 13.737-9.05-9.613-20.816-20.011-36.527-28.445-13.344-7.16-27.457-8.293-41.945-3.367-31.973 10.875-59.297 49.816-77.125 81.914V236.53a4.16 4.16 0 0 1 1.136-2.863c.055-.05.11-.102.164-.156a4.355 4.355 0 0 1 1.223-.824l.016-.004a4.108 4.108 0 0 1 1.43-.336h.034c.024-.004.047-.004.07-.004.04 0 .079-.004.118-.004h57.96c5.524 0 10-4.477 10-10 0-5.524-4.476-10-10-10h-57.96c-.09 0-.184.004-.274.004h-.097c-.067 0-.133.004-.2.004-.027 0-.05 0-.078.004-.09 0-.183.003-.273.007h-.059l-.226.012c-.027 0-.055.004-.082.004l-.207.012c-.028.004-.055.004-.082.004-.09.007-.18.011-.27.02l-.05.003c-.079.008-.157.012-.231.02-.027.004-.05.004-.074.007-.074.008-.149.012-.223.02-.02.004-.043.004-.062.008-.09.008-.184.02-.278.027-.011.004-.023.004-.039.008-.082.008-.164.02-.25.027-.015.004-.031.004-.047.008l-.27.035h-.01c-1.286.18-2.552.465-3.778.848l-27.344-35.137zM36.398 492l27.38-35.184a24.16 24.16 0 0 0 7.308 1.13h350.258c2.46 0 4.87-.372 7.164-1.083L455.848 492zm0 0" fill="black"/><path d="M370.633 318.145c19.05 0 34.547-15.5 34.547-34.547 0-19.051-15.496-34.547-34.547-34.547-19.047 0-34.547 15.496-34.547 34.547 0 19.047 15.5 34.547 34.547 34.547zm0-49.094c8.02 0 14.547 6.523 14.547 14.547 0 8.02-6.524 14.547-14.547 14.547-8.02 0-14.547-6.528-14.547-14.547 0-8.024 6.527-14.547 14.547-14.547zm0 0M169.047 232.344h.027c5.52 0 9.985-4.48 9.985-10 0-5.524-4.489-10-10.012-10s-10 4.476-10 10c0 5.52 4.48 10 10 10zm0 0" fill="black"/></svg>'),
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


// REDIRECT CUSTOM SINGLE-PAGE FROM CPT (Kunstgallerie)
// From https://blog.kulturbanause.de/2016/02/wordpress-single-seiten-von-custom-post-types-umleiten/

function mp_template_redirect() {
    if(is_singular('gallery')) {
        wp_redirect( home_url('/kunstgalerie/') );
        exit();
    }
}
add_action('template_redirect', 'mp_template_redirect');




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





// ================================================================================================
// USEFULL SNIPPETS / PERFORMANCE BOOST
//
// From https://www.drweb.de/26-nuetzlichsten-funktionellsten-wordpress-snippets/
// ================================================================================================

/**
* Disable the emoji's
*/
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
* Filter function used to remove the tinymce emoji plugin.
*
* @param array $plugins
* @return array Difference betwen the two arrays
*/
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/**
* Remove emoji CDN hostname from DNS prefetching hints.
*
* @param array $urls URLs to print for resource hints.
* @param string $relation_type The relation type the URLs are printed for.
* @return array Difference betwen the two arrays.
*/
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

        $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }

    return $urls;
}



/**
* Dequeue jQuery Migrate Script in WordPress.
*/
if ( ! function_exists( 'evolution_remove_jquery_migrate' ) ) :

function evolution_remove_jquery_migrate( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }
}
add_filter( 'wp_default_scripts', 'evolution_remove_jquery_migrate' );
endif;




/**
 * Disable embeds on init.
 *
 * - Removes the needed query vars.
 * - Disables oEmbed discovery.
 * - Completely removes the related JavaScript.
 *
 * @since 1.0.0
 */
function evolution_disable_embeds_init() {
    /* @var WP $wp */
    global $wp;

    // Remove the embed query var.
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );

    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'evolution_disable_embeds_tiny_mce_plugin' );

    // Remove all embeds rewrite rules.
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'evolution_disable_embeds_init', 9999 );

/**
 * Removes the 'wpembed' TinyMCE plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array The modified list.
 */
function evolution_disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}

/**
 * Remove all rewrite rules related to embeds.
 *
 * @since 1.0.0
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function evolution_disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    }

    return $rules;
}

/**
 * Remove embeds rewrite rules on plugin activation.
 *
 * @since 1.0.0
 */
function evolution_disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules( false );
}

register_activation_hook( __FILE__, 'evolution_disable_embeds_remove_rewrite_rules' );

/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @since 1.0.0
 */
function evolution_disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules( false );
}

register_deactivation_hook( __FILE__, 'evolution_disable_embeds_flush_rewrite_rules' );




/**
 * Befreit den Header von unnötigen Einträgen
 */
add_action('init', 'evolution_remheadlink');
function evolution_remheadlink()
{
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_header', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

?>