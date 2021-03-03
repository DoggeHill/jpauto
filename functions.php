<?php
global $allowed_actions;

$allowed_actions = array(
    'testajax'
);

if (!function_exists('seduco_setup')) :

    function seduco_setup()
    {

        load_theme_textdomain('seduco', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        add_image_size('blog-loop', 555, 401, true);
        add_image_size('klima-loop', 370, 300, true);
        add_image_size('service-loop', 265, 260, true);
        add_image_size('tepelne-loop', 263, 295, true);


        // MENU
        register_nav_menus(array(
            'main-menu' => esc_html__('Hlavné menu', 'seduco'),
            'extra-menu' => esc_html__('Extra menu', 'seduco'),
            'footer-menu' => esc_html__('Footer menu', 'seduco'),

        ));

        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif;

add_action('after_setup_theme', 'seduco_setup');

// SPECIAL NAV CLASS
add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

function special_nav_class($classes, $item)
{
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'current';
    }
    return $classes;
}

// ENQUEUE SCRIPTS AND STYLES
function seduco_scripts()
{
    // STYLES
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/seduco-core/vendor/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style('timepickerCSS', get_template_directory_uri() . '/seduco-core/vendor/timepicker/jquery.timepicker.min.css');
//    wp_enqueue_style( 'sweetalert', get_template_directory_uri() . '/seduco-core/vendor/swall/sweetalert.css' );
    wp_enqueue_style('swiperCSS', get_template_directory_uri() . '/seduco-core/vendor/swiper/swiper-bundle.min.css');
//	wp_enqueue_style( 'lightcase', get_template_directory_uri() . '/seduco-core/vendor/lightcase/src/css/lightcase.css' );
//	wp_enqueue_style( 'owlcarousel', get_template_directory_uri() . '/seduco-core/vendor/owlcarousel/css/owl.carousel.min.css' );
//	wp_enqueue_style( 'slick', get_template_directory_uri() . '/seduco-core/vendor/slick/slick.css' );
//	wp_enqueue_style( 'iconfont', get_template_directory_uri() . '/seduco-core/css/iconstyle.css' );
    wp_enqueue_style('seduco', get_template_directory_uri() . '/seduco-core/css/seduco.css');
//	wp_enqueue_style( 'SEDUCO_TEMP_CSS', get_template_directory_uri() . '/seduco-core/css/seduco-temp.css' );

    // FONTS
//	wp_enqueue_style( 'MAINFONT', '//fonts.googleapis.com/css?family=Muli:400,800&display=swap&subset=latin-ext' );
    wp_enqueue_style('Roboto', '//fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900&display=swap');

    // SCRIPTS
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/seduco-core/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '2019', true);
    wp_enqueue_script('timepickerJS', get_template_directory_uri() . '/seduco-core/vendor/timepicker/jquery.timepicker.min.js', array('jquery'), '2019', true);
//    wp_enqueue_script( 'popper', get_template_directory_uri() . '/seduco-core/vendor/bootstrap/js/popper.min.js', array('jquery'), '2019', true );
//    wp_enqueue_script( 'owlcarousel', get_template_directory_uri() . '/seduco-core/vendor/slick/slick.min.js', array('jquery'), '2019', true );
    wp_enqueue_script('swiperJS', get_template_directory_uri() . '/seduco-core/vendor/swiper/swiper-bundle.min.js', array('jquery'), '2019', true);
//    wp_enqueue_script( 'waypoint', get_template_directory_uri() . '/seduco-core/vendor/waypoints/lib/jquery.waypoints.min.js', array('jquery'), '2019', true );
//    wp_enqueue_script( 'owlcarousel', get_template_directory_uri() . '/seduco-core/vendor/owlcarousel/js/owl.carousel.min.js', array('jquery'), '2019', true );
//    wp_enqueue_script( 'SWALL_JS', get_template_directory_uri() . '/seduco-core/vendor/swall/sweetalert.js', array(), '201904', true );
//	  wp_enqueue_script( 'LC_JS', get_template_directory_uri() . '/seduco-core/vendor/lightcase/src/js/lightcase.js', array(), '201904', true );
//    wp_enqueue_script( 'LM_JS', get_template_directory_uri() . '/seduco-core/vendor/readmore/readmore.min.js', array(), '201904', true );
    wp_enqueue_script('SEDUCO_JS', get_template_directory_uri() . '/seduco-core/js/seduco.js', array(), '2019', true);

    // Chatbot nettle
//    wp_enqueue_style( 'nettle-chatbot', get_stylesheet_directory_uri() . '/inc/nettle/bundle.css', array(), '1.0' );
//    wp_enqueue_script( 'nettle-chatbot', get_stylesheet_directory_uri() . '/inc/nettle/bundle.js', array(), '1.0', false );
}

/**
 * Custom modification of script tag
 */
add_filter( 'script_loader_tag', 'add_script_attributes', 10, 3 );
function add_script_attributes($tag, $handle) {
    if( $handle === 'nettle-chatbot' ) {
        $tag = str_replace( 'text/javascript', 'module', $tag );
    }
    return $tag;
}

add_action('wp_enqueue_scripts', 'seduco_scripts');

// DISABLE ADMIN NOTICES
function pr_disable_admin_notices()
{
    global $wp_filter;
    if (is_user_admin()) {
        if (isset($wp_filter['user_admin_notices'])) {
            unset($wp_filter['user_admin_notices']);
        }
    } elseif (isset($wp_filter['admin_notices'])) {
        unset($wp_filter['admin_notices']);
    }
    if (isset($wp_filter['all_admin_notices'])) {
        unset($wp_filter['all_admin_notices']);
    }
}

add_action('admin_print_scripts', 'pr_disable_admin_notices');

// REQUIRE FILES

//require get_template_directory() . '/inc/customizer.php';
//require get_template_directory() . '/inc/float-placeholders.php';
require get_template_directory() . '/inc/seduco-ajax/seduco-ajax.php';
require get_template_directory() . '/inc/googlemap.php';

function testajax()
{
    check_ajax_referer('seduco', 'security');
    ?>


    <h2>afdasas</h2>
    <?php
    print_r("GGG");

    echo "hah";

    die;
}

add_action('SEDUCO_nopriv_testajax', 'testajax');
add_action('SEDUCO_testajax', 'testajax');

add_filter('wp_nav_menu_objects', 'add_has_children_to_nav_items');

function add_has_children_to_nav_items($items)
{
    $parents = wp_list_pluck($items, 'menu_item_parent');

    foreach ($items as $item)
        in_array($item->ID, $parents) && $item->classes[] = 'dropdown';

    return $items;
}

// Add specific CSS class by filter
add_filter('body_class', 'my_class_names');
function my_class_names($classes)
{
    global $post;

    // add 'post_name' to the $classes array
    $classes[] = $post->post_name;
    // return the $classes array
    return $classes;
}

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Nastavenia témy',
        'menu_title' => 'Nastavenia témy',
        'menu_slug' => 'nastavenia-temy',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

add_filter('wpcf7_support_html5_fallback', '__return_true');

add_filter('facetwp_facet_dropdown_show_counts', '__return_false');

// Enqueue the Dashicons script
add_action('wp_enqueue_scripts', 'load_dashicons_front_end');
function load_dashicons_front_end()
{
    wp_enqueue_style('dashicons');
}

//redirect
/*add_action('wp_print_styles', 'redirect_old_url_to_new_url');
function redirect_old_url_to_new_url() {
    global $post;
    if ('skladove-vozidla' == $post->post_name) {
        wp_redirect( site_url(), 301 );
        die();
    }
}*/

add_action('before_delete_post', function ($id) {
    $attachments = get_attached_media('', $id);
    foreach ($attachments as $attachment) {
        wp_delete_attachment($attachment->ID, 'true');
    }
    //echo 'why am I here';
});

// Update the post data after new VOZIDLO is found via json api script
add_action('save_post_vozidla', 'save_post_callback');
function save_post_callback($post_id)
{

    $post = get_post($post_id);
    $is_new = $post->post_date === $post->post_modified;

    //if the post is just created we will call functions to initialize taxonomies and ACF
    //since this action hooked is called also on the post deletion by this we will ensure
    //that the post initialize functions will no be run on the post deletion event
    if ($is_new) {
        // New post
        $post = get_post($post_id);
        
        //ensure the post is created by json robot
        if ($post->post_type != 'vozidla' || $post->post_author != 3) {
            return;
        }

        //update post meta and custom taxonomies
        update_vozidla_post_meta($post_id);

        //update post acf
        update_post_acf($post_id);



        //upload images and save them in acf gallery
        //upload_and_asign_images($post_id);
    } 
}

/**
 * Output 3rd party tracking scripts
 */
function jpauto_tracking_scripts()
{
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-164688780-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-164688780-1');
    </script>

    <!-- Hotjar Tracking Code for https://www.jpauto.sk -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1785712,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1284905151695225');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1284905151695225&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->
    <?php
}

if (!defined('WP_DEBUG') || !WP_DEBUG) {
    add_action('wp_head', 'jpauto_tracking_scripts');
}


/**
 * WP AJAX Call Frontend
 */
//Define AJAX URL
function myplugin_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'myplugin_ajaxurl');

//The Javascript
function ajax_cars_updater()
{ ?>
    <script>
        jQuery(document).ready(function($) {
            // This is the variable we are passing via AJAX
            var update = 'update';
            // This does the ajax request (The Call).
            var updateButton = $("#update");

            updateButton.click(function() {
                $.ajax({
                    url: ajaxurl, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
                    data: {
                        'action': 'jpauto_update_cars_json', // This is our PHP function below
                        'data': update // This is the variable we are sending via AJAX
                    },
                    beforeSend: function(data) {
                        $(".lds-ellipsis").removeClass("invisible");
                        updateButton.addClass("not-allowed")
                        updateButton.on('click', function(e) {
                            e.preventDefault();
                        });
                        console.log(data);
                    },
                    success: function(data) {
                        $(".lds-ellipsis").addClass("invisible");
                        updateButton.removeClass("not-allowed")
                        updateButton.text("aktualizované");
                        // This outputs the result of the ajax request (The Callback)
                        $("#status_json").text(data + " done");
                        console.log(data);
                    },
                    error: function(errorThrown) {
                        window.alert('Chyba, skúste znovu');
                        console.log(errorThrown);
                    },
                });
            });
        });
    </script>
<?php }
add_action('wp_footer', 'ajax_cars_updater');

//The PHP
function jpauto_update_cars_json()
{
    // The $_REQUEST contains all the data sent via AJAX from the Javascript call
    if (isset($_REQUEST)) {
        $data = $_REQUEST['data'];
        if ($data == 'update') {
            require_once 'cars_json_functions.php';
            create_all_cars_caller();
        }
        // Now let's return the result to the Javascript function (The Callback)
        echo $data;
    }
    // Always die in functions echoing AJAX content
    die();
}
// This bit is a special action hook that works with the WordPress AJAX functionality.
add_action('wp_ajax_jpauto_update_cars_json', 'jpauto_update_cars_json');
add_action('wp_ajax_nopriv_jpauto_update_cars_json', 'jpauto_update_cars_json');
