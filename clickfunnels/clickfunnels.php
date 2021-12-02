<?php
/**
    * Plugin Name: ClickFunnels
    * Plugin URI: https://www.clickfunnels.com
    * Description: Connect to your ClickFunnels account with simple authorization key and show any ClickFunnels page as your homepage or as 404 error pages or simply choose any of your pages and make clean URLs to your ClickFunnels pages. Don't have an account? <a target="_blank" href="https://www.clickfunnels.com">Sign up for your 2 week <em>free</em> trial now.</a>
    * Version: 3.1.1
    * Author: Etison, LLC
    * Author URI: https://www.clickfunnels.com
*/

define( "CF_API_URL", "https://api.clickfunnels.com/" );
class ClickFunnels {
    public function __construct() {
        add_action( "init", array( $this, "create_custom_post_type" ) );
        add_action( 'plugins_loaded', 'upgrade_existing_posts' );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_filter( 'manage_edit-clickfunnels_columns', array( $this, 'add_columns' ) );
        add_action( 'save_post', array( $this, 'save_meta' ), 10, 1 );
        add_action( 'manage_posts_custom_column', array( $this, 'fill_columns' ) );
        add_action( "template_redirect", array( $this, "process_page_request" ), 1, 2 );
        add_action( 'trashed_post', array( $this, 'post_trash' ), 10 );
        add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
        // check permalinks
        if ( get_option( 'permalink_structure' ) == '' ) {
            $message = '<div id="message" class="badAPI error notice" style="width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i> Error in ClickFunnels plugn, please check <a href="edit.php?post_type=clickfunnels&page=cf_api&error=compatibility">Settings > Compatibility Check</a> for details.</div>';
            add_action( "admin_notices", array( $this, $message ) );
        }
    }

    // Let's see if we should do anything about this page request
    public function process_page_request() {
        if (is_front_page()) {
            if ($this->get_home()) {
                status_header(200);
                $this->show_post( $this->get_home() );
                exit();
            } else {
                return; // not our home page
            }
        }

        $full_request_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $request_url_parts = explode( "?", $full_request_url );
        $request_url = $request_url_parts[0];
        $base_url = get_home_url()."/";
        $slug = str_replace( $base_url, "", $request_url );
        $slug = rtrim( $slug, '/' );

        // Home page doesn't necessarily live at root!
        if ($slug != '') {
            $query_args = array(
                'meta_key' => 'cf_slug',
                'meta_value' => $slug,
                'post_type' => 'clickfunnels',
                'compare' => '='
            );

            $the_posts = get_posts($query_args);
            $cf_page = current($the_posts);

            if ($cf_page) {
                status_header(200);
                $this->show_post( $cf_page->ID );
                exit();
            }
        }

        if (is_404()) {
            if ($this->get_404()) {
                $this->show_post( $this->get_404() );
                exit();
            } else {
                return; // not our 404 page
            }
        }
        return; // not our page
    }

    public function show_post( $post_id ) {
        $url = get_post_meta( $post_id, "cf_step_url", true );
        $method = get_option('clickfunnels_display_method');

        if ($method == 'download') {
            echo $this->get_page_content($url);
        } else if ($method == 'iframe') {
            echo $this->get_page_iframe($url);
        } else if ($method == 'redirect') {
            wp_redirect($url, 301);
        }

        exit();
    }

    public function get_page_content( $url ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        // Forward current cookies to curl
        $cookies = array();
        foreach ($_COOKIE as $key => $value) {
            if ($key != 'Array') {
                $cookies[] = $key . '=' . $value;
            }
        }
        curl_setopt( $ch, CURLOPT_COOKIE, implode(';', $cookies) );

        $destination = $url;

        while ($destination) {
            session_write_close();
            curl_setopt($ch, CURLOPT_URL, $destination);
            $response = curl_exec($ch);
            $curl_info = curl_getinfo($ch);
            $destination = $curl_info["redirect_url"];
            session_start();
        }
        curl_close($ch);

        $headers = substr($response, 0, $curl_info["header_size"]);
        $body = substr($response, $curl_info["header_size"]);

        // Extract cookies from curl and forward them to browser
        preg_match_all('/^(Set-Cookie:\s*[^\n]*)$/mi', $headers, $cookies);
        foreach($cookies[0] AS $cookie) {
            header($cookie, false);
        }

        return $body;
    }

    public function get_page_iframe( $cf_step_url ) {
        if (has_site_icon() && (get_option('clickfunnels_favicon_method') == 'wordpress')) {
            $favicon = '<link class="wp_favicon" href="'.get_site_icon_url().'" rel="shortcut icon"/>';
        } else {
            $favicon = '';
        }

        $additional_snippet = html_entity_decode(stripslashes(get_option('clickfunnels_additional_snippet')));

        return '<!DOCTYPE html>
            <head>
                '.$favicon.'
                <style>
                    body {
                        margin: 0;            /* Reset default margin */
                    }
                    iframe {
                        display: block;       /* iframes are inline by default */
                        border: none;         /* Reset default border */
                        height: 100vh;        /* Viewport-relative units */
                        width: 100vw;
                    }
                </style>
                <meta name="viewport" content="width=device-width, initial-scale=1">
            </head>
            <body>
                '.$additional_snippet.'
                <script type="text/javascript" src="'.plugin_dir_url( __FILE__ ).'js/update_meta_tags.js"></script>
                <iframe width="100%" height="100%" src="'.$cf_step_url.'" frameborder="0" allowfullscreen></iframe>
            </body>
        </html>';
    }

    public function updated_message( $messages ) {
        $post_id = get_the_ID();
        // make sure this is one of our pages
        if ( get_post_meta( $post_id, "cf_step_id", true ) == "" )
            return $messages;

        $our_message = '<strong><i class="fa fa-check" style="margin-right: 5px;"></i> Successfully saved and updated your ClickFunnels page.</strong>';

        $messages['post'][1] = $our_message;
        $messages['post'][4] = $our_message;
        $messages['post'][6] = $our_message;
        $messages['post'][10] = $our_message;

        return $messages;
    }

    public function post_trash( $post_id ) {
        if ( $this->is_404( $post_id ) ) {
            $this->set_404(NULL);
        }
        if ( $this->is_home( $post_id ) ) {
            $this->set_home(NULL);
        }
    }

    public function save_meta( $post_id ) {
        global $_POST;

        if (@$_POST['post_type'] != 'clickfunnels') {
            return;
        }

        $cf_slug = @$_POST['cf_slug'];
        $cf_page_type = @$_POST['cf_page_type'];
        $cf_step_id = @$_POST['cf_step_id'];
        $cf_step_name = @$_POST['cf_step_name'];
        $cf_funnel_id = @$_POST['cf_funnel_id'];
        $cf_funnel_name = @$_POST['cf_funnel_name'];
        $cf_step_url = @$_POST['cf_step_url'];

        if (isset($cf_slug)) {
            update_post_meta( $post_id, "cf_slug", $cf_slug );
        }
        if (isset($cf_page_type)) {
            update_post_meta( $post_id, "cf_page_type", $cf_page_type );
        }
        if (isset($cf_step_id)) {
            update_post_meta( $post_id, "cf_step_id", $cf_step_id );
        }
        if (isset($cf_step_name)) {
            update_post_meta( $post_id, "cf_step_name", $cf_step_name );
        }
        if (isset($cf_funnel_id)) {
            update_post_meta( $post_id, "cf_funnel_id", $cf_funnel_id );
        }
        if (isset($cf_funnel_name)) {
            update_post_meta( $post_id, "cf_funnel_name", $cf_funnel_name );
        }
        if (isset($cf_step_url)) {
            update_post_meta( $post_id, "cf_step_url", $cf_step_url );
        }

        if ($this->is_404($post_id)) {
            $this->set_404(NULL);
        } else if ($this->is_home($post_id)) {
            $this->set_home(NULL);
        }

        if ($cf_page_type == "homepage") {
            $this->set_home( $post_id );
        } else if ($cf_page_type == "404") {
            $this->set_404( $post_id );
        }
    }

    public function set_home( $post_id ) {
        update_option( 'clickfunnels_homepage_post_id', $post_id);
    }

    public function get_home() {
        return get_option( "clickfunnels_homepage_post_id" );
    }

    public function is_home( $post_id ) {
        return $post_id == get_option( "clickfunnels_homepage_post_id" );
    }

    public function set_404( $post_id ) {
        update_option( 'clickfunnels_404_post_id', $post_id);
    }

    public function get_404() {
        return get_option( "clickfunnels_404_post_id" );
    }

    public function is_404( $post_id ) {
        return $post_id == get_option( "clickfunnels_404_post_id" );
    }

    public function add_columns( $columns ) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['cf_post_name'] = "Page";
        $new_columns['cf_post_funnel'] = "Funnel";
        $new_columns['cf_path'] = 'View';
        $new_columns['cf_open_in_editor'] = 'Editor';
        $new_columns['cf_page_type'] = 'Type';
        return $new_columns;
    }

    public function fill_columns( $column ) {
        $id = get_the_ID();
        $cf_page_type = get_post_meta( $id, 'cf_page_type', true );
        $cf_slug = get_post_meta( $id, 'cf_slug', true );
        $cf_step_id = get_post_meta( $id, 'cf_step_id', true );
        $cf_step_name = get_post_meta( $id, 'cf_step_name', true );
        $cf_funnel_id = get_post_meta( $id, 'cf_funnel_id', true );
        $cf_funnel_name = get_post_meta( $id, 'cf_funnel_name', true );

        if ( 'cf_post_name' == $column ) {
            $url = get_edit_post_link( get_the_ID() );
            echo '<strong><a href="' . $url .'">'. $cf_step_name .'</a></strong>';
        }
        if ( 'cf_post_funnel' == $column ) {
            echo '<strong>'.$cf_funnel_name.'</strong>';
        }
        if ( 'cf_open_in_editor' == $column ) {
            echo "<strong><a href='" . CF_API_URL . "funnels/" . $cf_funnel_id . "/steps/". $cf_step_id ."' target='_blank'>Open in ClickFunnels</a></strong>";
        }

        switch ( $cf_page_type ) {
        case "page":
            $post_type = "Page";
            $url = get_home_url()."/".$cf_slug;
            break;
        case "homepage":
            $post_type = "<img src='https://images.clickfunnels.com/59/8ae200796511e581f93f593a07eabb/1445609147_house3.png' style='margin-right: 2px;margin-top: 3px;opacity: .7;width: 16px;height: 16px;' />Home Page";
            $url = get_home_url().'/';
            break;
        case "404":
            $post_type = "<img src='https://images.clickfunnels.com/c0/193250796611e599df696af00696f8/1445609787_attention_1.png' style='margin-right: 2px;margin-top: 3px;opacity: .7;width: 16px;height: 16px;' />404 Page";
            $url = get_home_url().'/test-url-404-page';
            break;
        default:
            $post_type = $cf_page_type;
            $url = get_edit_post_link( get_the_ID() );
        }

        if ( 'cf_page_type' == $column ) {
           echo "<strong>$post_type</strong>";
        }
        if ( 'cf_path' == $column ) {
            echo "<strong><a href='$url' target='_blank'>View Page</a></strong>";
        }
    }

    public function add_meta_box() {
        add_meta_box(
            'clickfunnels_meta_box', // $id
            'Setup Your ClickFunnels Page', // $title
            array( $this, "show_meta_box" ),
            'clickfunnels', // $page
            'normal', // $context
            'high' // $priority
        );
    }

    public function show_meta_box( $post ) {
        include 'pages/edit.php';
    }

    public function remove_save_box() {
        global $wp_meta_boxes;
        foreach ( $wp_meta_boxes['clickfunnels'] as $k=>$v )
            foreach ( $v as $l=>$m )
                foreach ( $m as $o=>$p )
                    if ( $o !="clickfunnels_meta_box" )
                        unset( $wp_meta_boxes['clickfunnels'][$k][$l][$o] );
    }

    public function create_custom_post_type() {
        $labels = array(
            'name' => _x( 'ClickFunnels', 'post type general name' ),
            'singular_name' => _x( 'Pages', 'post type singular name' ),
            'add_new' => _x( 'Add New', 'Click Funnels' ),
            'add_new_item' => __( 'Add New ClickFunnels Page' ),
            'edit_item' => __( 'Edit ClickFunnels Page' ),
            'new_item' => __( 'Add New' ),
            'all_items' => __( 'Pages' ),
            'view_item' => __( 'View Click Funnels Pages' ),
            'search_items' => __( 'Search Click Funnels' ),
            'not_found' => __( 'No Funnels Yet <br>
                              <a href="'.get_admin_url().'post-new.php?post_type=clickfunnels">add a new page</a> or <a href="'.get_admin_url().'edit.php?post_type=clickfunnels&page=cf_api/">finish plugin set-up</a>' ),
            'parent_item_colon' => '',
            'hide_post_row_actions' => array('trash', 'edit' ,'quick-edit')
        );

        register_post_type( 'clickfunnels',
            array(
                'labels' =>  $labels,
                'public' => true,
                'menu_icon' => plugins_url( 'images/icon.png', __FILE__ ),
                'has_archive' => true,
                'supports' => array( '' ),
                'rewrite' => array( 'slug' => 'clickfunnels' ),
                'register_meta_box_cb' => array( $this, "remove_save_box" ),
                'hide_post_row_actions' => array( 'trash' )
            )
        );
    }
}

function clickfunnels_plugin_activated() {
    if (!get_option('clickfunnels_display_method')) {
        update_option('clickfunnels_display_method', 'download');
    }
    upgrade_existing_posts();
}

function upgrading_clickfunnels_posts() {
    ?>
    <div class="error notice">
        <p>Your Clickfunnels posts have been upgraded to a new version.</p>
        <p>In order to conform to the new format, you may need to recreate your homepage and 404 page manually.</p>
    </div>
    <?php
}

function upgrade_existing_posts() {
    if (get_option('clickfunnels_posts_schema_version') == 3) {
        return;
    }
    add_action( 'admin_notices', 'upgrading_clickfunnels_posts' );

    $cf_options = get_option( "cf_options" );
    $args = array(
        'posts_per_page' => -1,
        'post_type' =>'clickfunnels',
        'post_status' => 'any',
        'fields' => 'id'
    );
    $the_posts = get_posts( $args );
    if (is_array($the_posts)) {
        foreach ($the_posts as $the_post) {
            $id = $the_post->ID;
            $sep = '{#}';

            $url = get_post_meta($id, 'cf_iframe_url', true);
            $funnel = get_post_meta($id, 'cf_thefunnel', true);
            $thepage = get_post_meta($id, 'cf_thepage', true);
            $slug = get_post_meta($id, 'cf_slug', true);

            if ($url && $funnel && $thepage && $slug) {
                // If metadata is working, use that, it's cleaner
                $funnel_parts = explode($sep, $funnel);
                $funnel_id = $funnel_parts[0];
                $funnel_name = $funnel_parts[11];
                $page_parts = explode($sep, $thepage);
                $page_name = $page_parts[5];
            } else {
                // Otherwise scour cf_options for the data
                if (isset($cf_options)) {
                    foreach ($cf_options['pages'] as $key => $value) {
                        $parts = explode($sep, $value);
                        if ($parts[5] == $id) {
                            $url = $parts[7];
                            $funnel_id = $parts[0];
                            $funnel_name = $parts[11];
                            $page_name = $parts[6];
                            $slug = $key;
                            break;
                        }
                    }
                }
            }

            // We have all the data we need to create a page
            // Homepage/404 set in cf_options will not be upgraded
            if ($url && $funnel_id && $slug) {
                if (!get_post_meta($id, 'cf_slug', true)){
                   update_post_meta($id, 'cf_slug', $slug);
                }
                if (!get_post_meta($id, 'cf_step_url', true)){
                   update_post_meta($id, 'cf_step_url', $url);
                }
                if (!get_post_meta($id, 'cf_funnel_id', true)){
                   update_post_meta($id, 'cf_funnel_id', $funnel_id);
                }
                if (!get_post_meta($id, 'cf_funnel_name', true)){
                   update_post_meta($id, 'cf_funnel_name', $funnel_name);
                }
                if (!get_post_meta($id, 'cf_step_name', true)){
                    update_post_meta($id, 'cf_step_name', $page_name);
                }

                $page_type = get_post_meta($id, 'cf_type', true);

                if ($page_type == 'hp' || $page_type == 'homepage') {
                    if (!get_option('clickfunnels_homepage_post_id')) {
                        update_option( 'clickfunnels_homepage_post_id', $id);
                    }
                    update_post_meta($id, 'cf_page_type', 'homepage');
                } else if ($page_type == 'np' || $page_type == '404') {
                    if (!get_option('clickfunnels_404_post_id')) {
                        update_option( 'clickfunnels_404_post_id', $id);
                    }
                    update_post_meta($id, 'cf_page_type', '404');
                } else {
                    update_post_meta($id, 'cf_page_type', 'page');
                }
            }
        }
    }
    update_option('clickfunnels_posts_schema_version', 3);
}

register_activation_hook( __FILE__, 'clickfunnels_plugin_activated' );

function cf_plugin_submenu() {
    add_submenu_page(
        'edit.php?post_type=clickfunnels',
        __( 'ClickFunnels Shortcodes', 'clickfunnels-menu' ),
        __( 'Shortcodes', 'clickfunnels-menu' ),
        'manage_options',
        'clickfunnels_shortcodes',
        'clickfunnels_shortcodes'
    );
    add_submenu_page(
        'edit.php?post_type=clickfunnels',
        __( 'Settings', 'clickfunnels-menu' ),
        __( 'Settings', 'clickfunnels-menu' ),
        'manage_options',
        'cf_api',
        'cf_api_settings_page'
    );
    add_submenu_page(
        null,
        __( 'Reset Data', 'clickfunnels-menu' ),
        __( 'Reset Data', 'clickfunnels-menu' ),
        'manage_options',
        'reset_data',
        'clickfunnels_reset_data_show_page'
    );
}
add_action( 'admin_menu', 'cf_plugin_submenu' );

function clickfunnels_reset_data_show_page(){
    include 'pages/reset_data.php';
}

function cf_api_settings_page() {
    include 'pages/settings.php';
}

function clickfunnels_shortcodes() {
    include 'pages/shortcodes.php';
}

function clickfunnels_loadjquery($hook) {
    if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
        return;
    }
    wp_enqueue_script( 'jquery' );
}
add_action('admin_enqueue_scripts', 'clickfunnels_loadjquery');

// ****************************************************************************************************************************
// Blog Post Embed Shortcode
function clickfunnels_embed( $atts ) {
    $a = shortcode_atts( array(
        'height' => '650',
        'scroll' => 'on',
        'url' => '<?php echo CF_API_URL ?>',
    ), $atts );

    return "<iframe src='{$a['url']}' width='100%' height='{$a['height']}' frameborder='0' scrolling='{$a['scroll']}'></iframe>";
}
add_shortcode( 'clickfunnels_embed', 'clickfunnels_embed' );

// ****************************************************************************************************************************
// ClickPop Shortcode
function clickfunnels_clickpop_script() {
    wp_register_script( 'cf_clickpop', 'https://app.clickfunnels.com/assets/cfpop.js', array(), '1.0.0', true );
    wp_enqueue_script( 'cf_clickpop' );
}
add_action( 'wp_enqueue_scripts', 'clickfunnels_clickpop_script' );
function clickfunnels_clickpop( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'exit' => 'false',
        'delay' => '',
        'id' => '',
        'subdomain' => '',
    ), $atts );
    if ($a['delay'] != '') {
        $delayTime = "{$a['delay']}000";
        $delay_js = "<script>window.onload=function(){setTimeout(clickpop_timed_click, $delayTime);}; function clickpop_timed_click(){for (links=document.getElementsByTagName('a'), i=0; i < links.length; ++i) link=links[i], null !=link.getAttribute('href') && link.getAttribute('href').match(/\/optin_box\/(([a-zA-Z]|\d){16})/i) && (cf_showpopup(link.getAttribute('href'))); function openPopup(e){if (ID=e.hashCode(), currentPopup=ID, cf_iframe=document.getElementById(ID), null==document.getElementById(ID)){var t=document.getElementsByTagName(\"body\"), n=e; document.body.innerHTML +='<iframe src=\"' + n + '?iframe=true\" id=\"' + ID + '\" style=\"position: fixed !important; left: 0px; top: 0px !important; width: 100%; border: none; z-index: 999999999999999 !important; visibility: hidden; \"></iframe>'}document.getElementById(ID).style.width=viewWidth + \"px\", document.getElementById(ID).style.height=viewHeight + \"px\", document.getElementById(ID).style.visibility=\"visible\", makeWindowModal(); var i=document.documentElement, t=document.body, o=i && i.scrollLeft || t && t.scrollLeft || 0, d=i && i.scrollTop || t && t.scrollTop || 0; document.getElementById(ID).style.top=0 + \"px\", document.getElementById(ID).style.left=o + \"px\"; var l=0; return reanimateMessageIntervalID=setInterval(function(){iframe=document.getElementById(ID), void 0 !=iframe && iframe.contentWindow.postMessage(\"reanimate\", \"*\"), ++l >=15 && clearInterval(reanimateMessageIntervalID)}, 1e3), !1}function cf_showpopup(url){openPopup(url);}}</script>";
    } else {
        $delayTime = '';
        $delay_js = "";
    }
    if (strpos($a['subdomain'], '.') !== false) {
        return "<a href='https://{$a['subdomain']}/optin_box/{$a['id']}' data-exit='{$a['exit']}'>$content</a>$delay_js";
    }
    else {
      return "<a href='https://{$a['subdomain']}.clickfunnels.com/optin_box/{$a['id']}' data-exit='{$a['exit']}'>$content</a>$delay_js";
    }

}
add_shortcode( 'clickfunnels_clickpop', 'clickfunnels_clickpop' );


// ****************************************************************************************************************************
// ClickOptin Shortcode
function clickfunnels_clickoptin( $atts ) {
    $a = shortcode_atts( array(
        'button_text' => 'Subscribe To Our Mailing List',
        'button_color' => 'blue',
        'placeholder' => 'Enter Your Email Address Here',
        'id' => '#',
        'subdomain' => '#',
        'input_icon' => 'show',
        'redirect' => '',
    ), $atts );
    if ($a['button_text'] == '') {
        $button_text = 'Subscribe To Our Mailing List';
    } else {
        $button_text = $a['button_text'];
    }

    if ($a['placeholder'] == '') {
        $placeholder = 'Enter Your Email Address Here';
    } else {
        $placeholder = $a['placeholder'];
    }

    if (strpos($a['subdomain'], '.') !== false) {
        $subdomain = $a['subdomain'];
    } else {
      $subdomain = $a['subdomain'] . '.clickfunnels.com';
    }

    $js_file_url = plugins_url( 'js/jquery.js', __FILE__ );

    return "<div id='clickoptin_cf_wrapper_".$a['id']."' class='clickoptin_".$a['theme_style']."'>
    <input type='text' id='clickoptin_cf_email_".$a['id']."' placeholder='".$placeholder."' class='clickoptin_".$a['input_icon']."' />
    <span class='clickoptin_".$a['button_color']."' id='clickoptin_cf_button_".$a['id']."'>".$button_text."</span>
</div>
<script>
    if (!window.jQuery) {
      var jq = document.createElement('script'); jq.type = 'text/javascript';
      jq.src = '" . $js_file_url . "';
      document.getElementsByTagName('head')[0].appendChild(jq);
      var jQueries = jQuery.noConflict();
        jQueries(document).ready(function($) {
            jQueries( '#clickoptin_cf_button_".$a['id']."' ).click(function() {
                var check_email = jQueries( '#clickoptin_cf_email_".$a['id']."' ).val();
                if (check_email != '' && /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(check_email)) {
                    jQueries( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_green');
                    if('".$a['redirect']."' == 'newtab') {
                        window.open('https://".$subdomain."/instant_optin/".$a['id']."/'+jQueries( '#clickoptin_cf_email_".$a['id']."' ).val(), '_blank');
                    }
                    else {
                        window.location.href = 'https://".$subdomain."/instant_optin/".$a['id']."/'+jQueries( '#clickoptin_cf_email_".$a['id']."' ).val();
                    }
                }
                else {
                   jQueries( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_red');
                }
            });
        });
    }
    else {
      var jq = document.createElement('script'); jq.type = 'text/javascript';
      jq.src = '" . $js_file_url . "';
      document.getElementsByTagName('head')[0].appendChild(jq);
      var $ = jQuery.noConflict();
        $(document).ready(function($) {
            $( '#clickoptin_cf_button_".$a['id']."' ).click(function() {
                var check_email = $( '#clickoptin_cf_email_".$a['id']."' ).val();
                if (check_email != '' && /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(check_email)) {
                    $( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_green');
                    if('".$a['redirect']."' == 'newtab') {
                        window.open('https://".$subdomain."/instant_optin/".$a['id']."/'+$( '#clickoptin_cf_email_".$a['id']."' ).val(), '_blank');
                    }
                    else {
                        window.location.href = 'https://".$subdomain."/instant_optin/".$a['id']."/'+$( '#clickoptin_cf_email_".$a['id']."' ).val();
                    }
                }
                else {
                   $( '#clickoptin_cf_email_".$a['id']."' ).addClass('clickoptin_cf_email_red');
                }
            });
        });
    }

</script>
<style>
    #clickoptin_cf_wrapper_".$a['id']." * {
        margin: 0;
        padding: 0;
        position: relative;
        font-family: Helvetica, sans-serif;
    }
    #clickoptin_cf_wrapper_".$a['id']." {
        padding: 5px 15px;
        border-radius: 4px;
        width: 100%;
        margin: 20px 0;
    }
    #clickoptin_cf_wrapper_".$a['id'].".clickoptin_dropshadow_off {
        box-shadow: none;
    }
    #clickoptin_cf_email_".$a['id']." {
        display: block;
        background: #fff;
        color: #444;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
        font-size: 15px;
        border: 2px solid #eee;
        text-align: left;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_show {
        background: #fff url(https://cdn2.iconfinder.com/data/icons/ledicons/email.png) no-repeat right;
        background-position: 97% 50%;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_cf_email_red {
        border: 2px solid #E54E3F;
    }
    #clickoptin_cf_email_".$a['id'].".clickoptin_cf_email_green {
        border: 2px solid #339933;
    }
    #clickoptin_cf_button_".$a['id']." {
        display: block;
        font-weight: bold;
        background: #0166AE;
        border: 1px solid #01528B;
        border-bottom: 3px solid #01528B;
        color: #fff;
        border-radius: 5px;
        padding: 8px;
        width: 100%;
        font-size: 16px;
        margin-top: 8px;
        cursor: pointer;
        text-align: center;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_red {
        background: #F05A38;
        border: 1px solid #D85132;
        border-bottom: 3px solid #D85132;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_green {
        background: #339933;
        border: 1px solid #2E8A2E;
        border-bottom: 3px solid #2E8A2E;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_black {
        background: #23282D;
        border: 1px solid #111;
        border-bottom: 3px solid #111;
    }
    #clickoptin_cf_button_".$a['id'].".clickoptin_grey {
        background: #fff;
        color: #0166AE;
        border: 1px solid #eee;
        border-bottom: 3px solid #eee;
    }
</style>";
}
add_shortcode( 'clickfunnels_clickoptin', 'clickfunnels_clickoptin' );

// ****************************************************************************************************************************
// ClickFunnels Shortcode Widget
add_filter('widget_text', 'do_shortcode');
class clickfunnels_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'clickfunnels_widget',
            __('ClickFunnels Shortcode', 'clickfunnels_widget_domain'),
            array( 'description' => __( 'Paste your ClickFunnels shortcodes here to embed an iframe, a ClickPop link or show a ClickForm box in your sidebar or footer.', 'clickfunnels_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $shortcode = apply_filters( 'widget_title', $instance['shortcode'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) ) echo '<h3 style="text-align: center;">'.$title.'</h3>';
        if ( ! empty( $shortcode ) ) echo do_shortcode(htmlspecialchars_decode(($shortcode)));
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( '', 'clickfunnels_widget_domain' );
        }
        if ( isset( $instance[ 'shortcode' ] ) ) {
            $shortcode = $instance[ 'shortcode' ];
        }
        else {
            $shortcode = __( '', 'clickfunnels_widget_domain' );
        }
        // Widget admin form
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Headline:' ); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'shortcode' ); ?>"><?php _e( 'Shortcode:' ); ?></label>
                <textarea style="height: 130px;font-size: 12px;color: #555;" class="widefat" id="<?php echo $this->get_field_id( 'shortcode' ); ?>" name="<?php echo $this->get_field_name( 'shortcode' ); ?>" ><?php echo esc_attr( $shortcode ); ?></textarea>
            </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ?  $new_instance['title']  : '';
        $instance['shortcode'] = ( ! empty( $new_instance['shortcode'] ) ) ? $new_instance['shortcode']  : '';
        return $instance;
    }
}

function clickfunnels_widget_load() {
    register_widget( 'clickfunnels_widget' );
}
add_action( 'widgets_init', 'clickfunnels_widget_load' );

// Pretty up the manage CF pages area
add_action('all_admin_notices', 'clickfunnels_edit_page_settings');
function clickfunnels_edit_page_settings() {
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if (isset($_GET['post_type']) and $_GET['post_type'] == 'clickfunnels' && strpos($url,'edit.php') !== false && !isset($_GET['page'])) {
    ?>
        <script>
            jQuery(function() {
                jQuery('.wrap h1').attr('style', 'font-weight: bold;');
                jQuery('.wrap h1').first().prepend('<img src="https://appassets3.clickfunnels.com/assets/favicon-8c74cad77e4e123f7dbb46b33e6de10c.png" style="margin-right: 5px;margin-bottom: -7px" />');
                jQuery('.wrap h1').first().append('<a href="https://support.clickfunnels.com/support/solutions/5000164139" target="_blank" class="page-title-action">Support Desk</a>');
            });
        </script>
    <?php
    }
}

// Do the thing
$cf = new ClickFunnels();
