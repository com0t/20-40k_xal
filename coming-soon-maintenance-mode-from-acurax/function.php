<?php
function acx_csma_display_template_array_filter_hook()
{
	global $acx_csma_template_array;
	$acx_csma_template_array = array();
	$acx_csma_template_array = apply_filters('acx_csma_display_template_array_filter',$acx_csma_template_array);	
	
} add_action('init','acx_csma_display_template_array_filter_hook');
function acx_csma_filter_lw_pr($acx_csma_template_array)
{
	return $acx_csma_template_array;
} add_filter('acx_csma_display_template_array_filter','acx_csma_filter_lw_pr',5);
function acx_csma_appearence_array_default_filter_hook()
{
	global $acx_csma_appearence_array_default;
	$acx_csma_appearence_array_default = array();
	$acx_csma_appearence_array_default = apply_filters('acx_csma_appearence_array_default_filter',$acx_csma_appearence_array_default);	
	
} add_action('init','acx_csma_appearence_array_default_filter_hook');
function acx_csma_appearence_array_default_lw_pr($acx_csma_appearence_array_default)
{
	return $acx_csma_appearence_array_default;
} add_filter('acx_csma_appearence_array_default_filter','acx_csma_appearence_array_default_lw_pr',5);

$acx_csma_display_var_arr = get_option('acx_csma_display_var_arr');
if(is_serialized($acx_csma_display_var_arr))
{ 
	$acx_csma_display_var_arr = unserialize($acx_csma_display_var_arr); 
}
if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
{
	$acx_csma_display_var_arr = array();
}
if(empty($acx_csma_display_var_arr))
{
	$acx_csma_display_var_arr=array(
								'year'=>array(
											'singular'=>__('Year',ACX_CSMA_WP_SLUG),
											'plural'=>__('Years',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Year',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Years',ACX_CSMA_WP_SLUG)
											),
								'month'=>array(
											'singular'=>__('Month',ACX_CSMA_WP_SLUG),
											'plural'=>__('Months',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Month',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Months',ACX_CSMA_WP_SLUG)
											),
								'week'=>array(
											'singular'=>__('Week',ACX_CSMA_WP_SLUG),
											'plural'=>__('Weeks',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Week',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Weeks',ACX_CSMA_WP_SLUG)
											),
								'day'=>array(
											'singular'=>__('Day',ACX_CSMA_WP_SLUG),
											'plural'=>__('Days',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Day',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Days',ACX_CSMA_WP_SLUG)
											),
								'hour'=>array(
											'singular'=>__('Hour',ACX_CSMA_WP_SLUG),
											'plural'=>__('Hours',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Hour',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Hours',ACX_CSMA_WP_SLUG)
											),
								'minute'=>array(
											'singular'=>__('Minute',ACX_CSMA_WP_SLUG),
											'plural'=>__('Minutes',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Minute',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Minutes',ACX_CSMA_WP_SLUG)
											),
								'second'=>array(
											'singular'=>__('Second',ACX_CSMA_WP_SLUG),
											'plural'=>__('Seconds',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Second',ACX_CSMA_WP_SLUG),
											'default_plural'=>__('Seconds',ACX_CSMA_WP_SLUG)
											),
								'next'=>array(
											'singular'=>__('Next',ACX_CSMA_WP_SLUG),
											'default_singular'=>__('Next',ACX_CSMA_WP_SLUG)
											)
							);
	if(!is_serialized($acx_csma_display_var_arr))
	{ 
		$acx_csma_display_var_arr = serialize($acx_csma_display_var_arr); 
	}
	update_option('acx_csma_display_var_arr',$acx_csma_display_var_arr);
}
function acx_csma_styles() 
{	
	wp_register_style('acx_csmaadmin_style', plugins_url('css/admin.css?v='.ACX_CSMA_CURRENT_VERSION, __FILE__));
	wp_enqueue_style('acx_csmaadmin_style');
	wp_register_style('acx_csmaaddons_style', plugins_url('css/csma_addons.css?v='.ACX_CSMA_CURRENT_VERSION, __FILE__));
	wp_enqueue_style('acx_csmaaddons_style');
	wp_register_style('acx_csma_layout_style', plugins_url('css/layout.css?v='.ACX_CSMA_CURRENT_VERSION, __FILE__));
	wp_enqueue_style('acx_csma_layout_style');
	wp_register_style('acx_csmabox_style', plugins_url('css/acx_csma_box.css?v='.ACX_CSMA_CURRENT_VERSION, __FILE__));
	wp_enqueue_style('acx_csmabox_style');
	wp_register_style('acx_datepick_style', plugins_url('css/jquery.datetimepicker.css?v='.ACX_CSMA_CURRENT_VERSION, __FILE__));
	wp_enqueue_style('acx_datepick_style');
}
add_action('admin_enqueue_scripts', 'acx_csma_styles');
function acx_csma_date_picker_scripts()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
}
add_action('admin_enqueue_scripts','acx_csma_date_picker_scripts');
function acx_csma_colorpicker_scripts() 
{
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic','',array( 'jquery' ) );
}
// color picker
 if(ISSET($_GET['page']))
{
	$acx_csma_page = sanitize_text_field(trim($_GET['page']));
}
else
{
	$acx_csma_page="";
}	
if($acx_csma_page == "Acurax-Coming-Soon-Maintenance-Mode-Settings")
{
	add_action('admin_init','acx_csma_colorpicker_scripts');
}
//Date picker
function acx_csma_script()
{
	wp_register_script('acx_csma_datepick_script', plugins_url('js/jquery.datetimepicker.js', __FILE__)); 
	wp_enqueue_script('acx_csma_datepick_script','',array( 'jquery' ));
}
add_action('admin_enqueue_scripts', 'acx_csma_script');
function acx_csma_color_pick()
{
	echo '<script type="text/javascript" src="'.plugins_url('js/color.js', __FILE__). '"></script>';
}	
if($acx_csma_page == "Acurax-Coming-Soon-Maintenance-Mode-Settings")
{
	add_action('admin_head','acx_csma_color_pick');	
}
function filter_acx_csma_template_array($acx_csma_template_array)
{
	$acx_site_url = get_site_url();
	$acx_csma_parent_folder = basename(dirname(__FILE__));
	$acx_csma_template_array['0'] = array(
											'id' => 0,
											'name' =>__('Custom Html',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_custom_template',
											'description' => '',
											'parent' =>  $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);
	$acx_csma_template_array['1'] = array(
											'id' => 1,
											'name' =>__('Template 1',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_template1',
											'description' => '',
											'parent' => $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);
	$acx_csma_template_array['2'] = array(
											'id' => 2,
											'name' =>__('Template 2',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_template2',
											'description' => '',
											'parent' => $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);	
	$acx_csma_template_array['3'] = array(
											'id' => 3,
											'name' =>__('Template 3',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_template3',
											'description' => '',
											'parent' => $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);
	$acx_csma_template_array['4'] = array(
											'id' => 4,
											'name' =>__('Template 4',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_template4',
											'description' => '',
											'parent' => $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);
	$acx_csma_template_array['5'] = array(
											'id' => 5,
											'name' =>__('Template 5',ACX_CSMA_WP_SLUG),
											'index' =>'acx_csma_template5',
											'description' => '',
											'parent' => $acx_csma_parent_folder,
											'path' => $acx_site_url,
											'thumb' => ACX_CSMA_BASE_LOCATION
											);

	return $acx_csma_template_array;
}
add_filter('acx_csma_display_template_array_filter','filter_acx_csma_template_array');

$acx_csma_activation_status = get_option('acx_csma_activation_status');
if($acx_csma_activation_status == '')
{
	update_option('acx_csma_activation_status',0);
}
if($acx_csma_activation_status == 1)
{
	$acx_csma_display_template=true;
}
else
{
	$acx_csma_display_template = false;
}
if($acx_csma_display_template == true)
{
	$acx_csma_max_date = get_option('acx_csma_date_time');
	$acx_csma_timestamp=current_time('timestamp');
	$acx_csma_auto_launch=get_option('acx_csma_auto_launch'); 

	if($acx_csma_timestamp > $acx_csma_max_date)
	{
		if($acx_csma_auto_launch==0)
		{	
			$acx_csma_display_template = true;
		} else
		{
			$acx_csma_display_template = false;
		}
	}
}
if($acx_csma_display_template == true)
{
	add_action('template_redirect','acx_csma_plugin_activation');
}
function acx_csma_plugin_activation()
{
	global $wpdb,$acx_csma_display_template,$acx_csma_template_path,$acx_csma_template_array;
	$acx_csma_send_header_option = get_option('acx_csma_send_header_option');
	if ($acx_csma_send_header_option == "") {	$acx_csma_send_header_option = "yes"; }
	if($acx_csma_display_template==true)
	{
		if (is_user_logged_in()) 
		{
			$acx_csma_role_array=get_option('acx_csma_restrict_role');
			if(is_serialized($acx_csma_role_array))
			{
				$acx_csma_role_array = unserialize($acx_csma_role_array); 
			}
			if($acx_csma_role_array == "" || !is_array($acx_csma_role_array))
			{
				$acx_csma_role_array = array();
			}
			$current_user = wp_get_current_user();
			$roles = $current_user->roles;   //$roles -array
			$user_roles = "";
			foreach($roles as $key=>$value)
			{
				$user_roles = $value;	
			}
			if(is_array($acx_csma_role_array))
			{
				
				if(in_array($user_roles,$acx_csma_role_array)|| $user_roles=="administrator" || is_super_admin())
				{
					//do not display maintenance page.....
					$acx_csma_display_template=false;
				}
				
			}
		}
	}
	if($acx_csma_display_template==true)
	{
		$acx_csma_ip_array=get_option('acx_csma_ip_list');
		if($acx_csma_ip_array=="")
		{
		$acx_csma_ip_array = array();	
		}
		if(is_serialized($acx_csma_ip_array))
		{
			$acx_csma_ip_array = unserialize($acx_csma_ip_array); 
		}
		if($acx_csma_ip_array == "" || !is_array($acx_csma_ip_array))
		{
			$acx_csma_ip_array = array();
		}
		$current_ip = acx_csma_getrealip();
		
		if(is_array($acx_csma_ip_array) && in_array($current_ip,$acx_csma_ip_array))
		{
			// do not display maintenance page.....
			$acx_csma_display_template=false;
		}
	}
	 $acx_csma_display_template = apply_filters('acx_csma_display_template_filter',$acx_csma_display_template);

	if($acx_csma_display_template == true)
	{
		
		$protocol = "HTTP/1.0";
		if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] )
		$protocol = "HTTP/1.1";
		if($acx_csma_send_header_option == "yes")
		{
			header( "$protocol 503 Service Unavailable", true, 503 );
		}
		$end_time = get_option('acx_csma_date_time');
		if($end_time != "")
		{
			$end_time = date_i18n("D, j M Y H:i:s e", $end_time);
			header( "Retry-After: $end_time" );
		}
		$acx_csma_template=get_option('acx_csma_template');
		if($acx_csma_template == "" || !is_numeric($acx_csma_template))
		{
			$acx_csma_template = 1;
		}
		$acx_csma_base_template=get_option('acx_csma_base_template');
		if(is_array($acx_csma_template_array) && !array_key_exists($acx_csma_template,$acx_csma_template_array) && $acx_csma_base_template !== "")
		{
			$acx_csma_template= $acx_csma_base_template;
		}
		$acx_csma_template_path_loc = WP_CONTENT_DIR."/plugins/".$acx_csma_template_array[$acx_csma_template]['parent'];
		$acx_csma_template_path = $acx_csma_template_path_loc."/templates/".$acx_csma_template."/index.php";
		include_once($acx_csma_template_path);
		exit;
	}
}
function filter_acx_csma_template($acx_csma_display_template)
{
	if($acx_csma_display_template != '')
	{
		return $acx_csma_display_template;
	}
}
add_filter('acx_csma_display_template_filter','filter_acx_csma_template',5); 
function acx_csma_template_preview()
{
	global $acx_csma_template_array;
	$acx_csma_send_header_option = get_option('acx_csma_send_header_option');
	if ($acx_csma_send_header_option == "") {	$acx_csma_send_header_option = "yes"; }
	if(ISSET($_GET['acx_csma_preview']) && current_user_can( 'manage_options' )){
		$acx_csma_preview = sanitize_text_field(trim($_GET['acx_csma_preview']));
		if(is_array($acx_csma_template_array) && array_key_exists($acx_csma_preview,$acx_csma_template_array))
		{
			$protocol = "HTTP/1.0";
			if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] )
			$protocol = "HTTP/1.1";
			if($acx_csma_send_header_option == "yes")
			{
				header( "$protocol 503 Service Unavailable", true, 503 );
			}
			$end_time = get_option('acx_csma_date_time');
			if($end_time != "")
			{
				$end_time = date_i18n("D, j M Y H:i:s e", $end_time);
				header( "Retry-After: $end_time" );
			}
			$acx_csma_template_path_loc = WP_CONTENT_DIR."/plugins/".$acx_csma_template_array[$acx_csma_preview]['parent'];
			include_once($acx_csma_template_path_loc."/templates/".$acx_csma_preview."/index.php");
			exit;
		}
	}
}
add_action('template_redirect','acx_csma_template_preview');
// changing launch text
$acx_csma_appearence_array=acx_csma_get_db_array_value(); 
if(is_array($acx_csma_appearence_array))
{
	if(array_key_exists('3',$acx_csma_appearence_array) && array_key_exists('acx_csma_inside_title3',$acx_csma_appearence_array['3']))
	{
		$acx_csma_inside_title3 = $acx_csma_appearence_array['3']['acx_csma_inside_title3'];
		if(strcmp($acx_csma_inside_title3,"Estimate Time Before Lunching") === 0 )
		{
			$acx_csma_appearence_array['3']['acx_csma_inside_title3'] = __("Estimate Time Before Launching",ACX_CSMA_WP_SLUG);
		}
	}
	acx_csma_update_array_value($acx_csma_appearence_array);
}
// upload images and logos
function acx_csma_upload_images_template_1() 
{
	if(function_exists('wp_enqueue_media'))
	{
		wp_enqueue_media();	
	}
?>
<script type="text/javascript">
function acx_csma_upload_images_template_loader(button_id,uploader_title,uploader_button,hidden_field_id,preview_id)
{                                                       
	if(button_id)
	{
		button_id = "#"+button_id;
	}
	if(uploader_title == "")
	{
		uploader_title = "<?php _e('Choose Image',ACX_CSMA_WP_SLUG);?>";
	}
	if(uploader_button == "")
	{
		uploader_button = "<?php _e('Select',ACX_CSMA_WP_SLUG);?>";
	}
	if(hidden_field_id)
	{
		hidden_field_id = "#"+hidden_field_id;
	}
	if(preview_id)
	{
		preview_id = "#"+preview_id;
	}
	var custom_uploader_template_1_1;
	jQuery(button_id).click(function(e) 
	{
		e.preventDefault();
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader_template_1_1) 
		{
			custom_uploader_template_1_1.open();
			return;
		}
		//Extend the wp.media object
		custom_uploader_template_1_1 = wp.media.frames.file_frame = wp.media({
		title: uploader_title,
		button:
		{
			text: uploader_button
		},
		multiple: false	});
		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader_template_1_1.on('select', function() 
		{
			attachment = custom_uploader_template_1_1.state().get('selection').first().toJSON();
			// console.log(attachment);
			if(hidden_field_id)
			{
				jQuery(hidden_field_id).val(attachment.id);
			}
			if(preview_id != "")
			{
				jQuery(preview_id).attr('src',attachment.url);
			}
		});
		//Open the uploader dialog
		custom_uploader_template_1_1.open();
	});
}
</script>
<?php
} 
add_action('admin_head', 'acx_csma_upload_images_template_1'); 	
//Quick Request Form
function acx_csma_quick_request_submit_callback()
{
	$acx_name =  $acx_email = $acx_phone = $acx_csma_es = $acx_weburl = $acx_subject = $acx_question = "";
	if(ISSET($_POST['acx_name']))
	{
		$acx_name =  $_POST['acx_name'];
	}
	if(ISSET($_POST['acx_email']))
	{
		$acx_email =  $_POST['acx_email'];
	}
	if(ISSET($_POST['acx_phone']))
	{
		$acx_phone =  $_POST['acx_phone'];
	}
	if(ISSET($_POST['acx_csma_es']))
	{
		$acx_csma_es =  $_POST['acx_csma_es'];
	}
	if(ISSET($_POST['acx_weburl']))
	{
		$acx_weburl =  $_POST['acx_weburl'];
	}
	if(ISSET($_POST['acx_subject']))
	{
		$acx_subject =  stripslashes($_POST['acx_subject']);
	}
	if(ISSET($_POST['acx_question']))
	{
		$acx_question =  stripslashes($_POST['acx_question']);
	}
	if (!wp_verify_nonce($acx_csma_es,'acx_csma_es'))
	{
		$acx_csma_es == "";
	}
	if(!current_user_can('manage_options'))
	{
		$acx_csma_es == "";
	}
	if($acx_csma_es == "" || $acx_name == "" || $acx_phone == "" || $acx_email == "" || $acx_weburl == "" || $acx_subject == "" || $acx_question == "")
	{
		echo 2;
	} 
	else
	{
		$current_user = wp_get_current_user();
		$current_user_acx = $current_user->user_email;
		if($current_user_acx == "")
		{
			$current_user_acx = $acx_email;
		}
		$headers[] = __('From: ',ACX_CSMA_WP_SLUG) . $acx_name . ' <' . $current_user_acx . '>';
		$headers[] = __('Content-Type: text/html; charset=UTF-8',ACX_CSMA_WP_SLUG); 
		$message = __('Name: ',ACX_CSMA_WP_SLUG).$acx_name . "\r\n <br>";
		$message = $message . __('Email: ',ACX_CSMA_WP_SLUG).$acx_email . "\r\n <br>";
		if($acx_phone != "")
		{
			$message = $message . __('Phone:',ACX_CSMA_WP_SLUG).$acx_phone . "\r\n <br>";
		}
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$acx_question = wordwrap($acx_question, 70, "\r\n <br>");
		$message = $message . __('Request From: CSMA - Expert Help Request Form',ACX_CSMA_WP_SLUG).' \r\n <br>';
		$message = $message . __('Website: ',ACX_CSMA_WP_SLUG).$acx_weburl . "\r\n <br>";
		$message = $message . __('Question: ',ACX_CSMA_WP_SLUG).$acx_question . "\r\n <br>";
		$emailed = wp_mail( 'info@acurax.com', $acx_subject, $message, $headers );
		if($emailed)
		{
			echo 1;
		} else
		{
			echo 0;
		}
	}
	die(); // this is required to return a proper result
}add_action('wp_ajax_acx_csma_quick_request_submit','acx_csma_quick_request_submit_callback');
function acx_csma_add_items($admin_bar)
{
	$args = array(
    'id'    => 'acx_csma_activation_msg',
    'parent' => 'top-secondary',
    'title' => __('Maintenance Mode is Activated',ACX_CSMA_WP_SLUG),
    'href'  => esc_url(wp_nonce_url(admin_url('admin.php?page=Acurax-Coming-Soon-Maintenance-Mode-Settings')))
    );
	if (!current_user_can('manage_options') ) {
        return;
    }
    $admin_bar->add_menu($args);
}
$acx_csma_activation_status = get_option('acx_csma_activation_status');
if($acx_csma_activation_status == 1 && is_admin())
{
	add_action('admin_bar_menu', 'acx_csma_add_items'); 
}
function acx_csma_subscribe_email()
{
	if (!isset($_POST['acx_csma_subscribe_es'])) die("<br><br>".__('Unknown Error Occurred, Try Again...',ACX_CSMA_WP_SLUG)." <a href=''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if (!wp_verify_nonce($_POST['acx_csma_subscribe_es'],'acx_csma_subscribe_es')) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href=''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	$name = $email = $ip = $timestamp = "";
	if(ISSET($_POST['name']))
	{
		$name = $_POST['name'];
	}
	if(ISSET($_POST['email']))
	{
		$email = $_POST['email'];
	}
	if(ISSET($_POST['ip']))
	{
		$ip = $_POST['ip'];
	}
	if(ISSET($_POST['timestamp']))
	{
		$timestamp = $_POST['timestamp'];
	}
	$acx_csma_subscribe_details = get_option('acx_csma_subscribe_user_details');
	
	if(is_serialized($acx_csma_subscribe_details))
	{ 
		$acx_csma_subscribe_details = unserialize($acx_csma_subscribe_details); 
	}
	if($acx_csma_subscribe_details == "" || !is_array($acx_csma_subscribe_details))
	{
		$acx_csma_subscribe_details = array();
	}
		 
	$found = 0;
	foreach($acx_csma_subscribe_details as $key => $value)
	{
		if($value['email'] == $email)
		{
			$found = 1;
		}
	}
	if($found == 1)
	{
		echo "Exists";
	}
	else{
		$acx_csma_subscribe_details[]= array (
											'name' => $name,
											'email' => sanitize_email($email),
											'ip' => $ip,
											'timestamp' => $timestamp
												);
		if(!is_serialized($acx_csma_subscribe_details))
		{
			$acx_csma_subscribe_details = serialize($acx_csma_subscribe_details); 
		}
		update_option('acx_csma_subscribe_user_details',$acx_csma_subscribe_details);
		echo "success";
	} 
	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_nopriv_acx_csma_subscribe_email', 'acx_csma_subscribe_email' );

function acx_csma_subscribe_ajax()
{	
	$acx_csma_subscribe_details=get_option('acx_csma_subscribe_user_details');
	if(is_serialized($acx_csma_subscribe_details ))
	{
		$acx_csma_subscribe_details = unserialize($acx_csma_subscribe_details); 
	}	
	if($acx_csma_subscribe_details == "" || !is_array($acx_csma_subscribe_details))
	{
		$acx_csma_subscribe_details = array();
	}
	if(!empty($acx_csma_subscribe_details)) {
		$filename = 'subscribers-list-' . date('Y-m-d') . '.csv';
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename='.$filename);
		$fp = fopen('php://output', 'w');
		fputcsv($fp, array(__('Name',ACX_CSMA_WP_SLUG),__('Email',ACX_CSMA_WP_SLUG),__('Date',ACX_CSMA_WP_SLUG)));
		foreach ($acx_csma_subscribe_details as $item=> $value) {
			if(ISSET($value['ip']))
			{
				unset($acx_csma_subscribe_details[$item]['ip']);
			}
			if(ISSET($value['timestamp']))
			{	
				$format="Y-m-d H:i:s";
				$acx_csma_subscribe_details[$item]['timestamp']=date_i18n($format, $acx_csma_subscribe_details[$item]['timestamp']);
			}
		}
		foreach ($acx_csma_subscribe_details as $item=> $value) {
			fputcsv($fp, $value);
		}
		fclose($fp);
	}
	die();
}
add_action( 'wp_ajax_acx_csma_subscribe_ajax', 'acx_csma_subscribe_ajax' );
function acx_csma_addon_ua_demo()
{
echo "<div id='acx_csma_addon_demo_ua'><br><hr>
<img src='".plugins_url('/images/addon_ua_demo.png',__FILE__)."' style='border:0px;width:100%;height:auto;' class='acx_csma_info_lb' lb_title='".__('Private Access URL Feature - Premium Addon Plugin',ACX_CSMA_WP_SLUG)."' lb_content='<p style=\"font-size:13px;\">".__('You may needs to showcase the website to your friends, contacts or clients to get approval, or get suggestions.',ACX_CSMA_WP_SLUG)."<br><br>".__('When website is in under construction mode, they may needs to login or provide you their ip address to grand them access.',ACX_CSMA_WP_SLUG)."<br><br>".__('But using Private Access URL Addon, You will get the option to generate a private URL which you can provide to anyone, and they can access your website, They wont see the under construction page until the url expire.',ACX_CSMA_WP_SLUG)."<br><br>". __('While generating a URL, You can set expiry, It can be Never, So URL is valid until you delete it. Can set expiry as Hours, So URL will be active for specified hours from their first visit.',ACX_CSMA_WP_SLUG)."<br><br>".__('Can also set expiry as page views. Lets say, you generated a URL for 10 Page views, and when someone visit the URL and access 10 pages or visited 10 times.URL will automatically gets expired.',ACX_CSMA_WP_SLUG)."<br><br><a style=\"float: left; color: black; border: 1px solid black; border-radius: 3px; padding: 4px; font-size: 13px; opacity: 0.8;\"href=\"http://www.acurax.com/products/under-construction-maintenance-mode-wordpress-plugin/?feature=url-access&utm_source=link_1&utm_medium=csma_1&utm_campaign=csma\" style=\"float:right;\" target=\"_blank\">".__('View Screenshots/More Details',ACX_CSMA_WP_SLUG)."</a><a href=\"https://clients.acurax.com/order.php?pid=csmauapa&utm_source=link_1&utm_medium=csma_1&utm_campaign=csma\" style=\"float:right;\" target=\"_blank\">".__('Order Now',ACX_CSMA_WP_SLUG)."</a></p><p><br></p>'>
</div>";
}
add_action('acx_csma_hook_mainoptions_below_general_settings','acx_csma_addon_ua_demo',50);
function acx_csma_service_addon_demo()
{
	$acx_theme_addon_demo_array['STPA1'] = array(
			'STP1_A' => array(
				'image' => plugins_url('/images/csma_01.png',__FILE__),
				'preview' => 'https://clients.acurax.com/link.php?id=18',
				'name' => 'STP1-A'
			),
			'STP1_B' => array(
				'image' => plugins_url('/images/csma_02.png',__FILE__),
				'preview' => 'https://clients.acurax.com/link.php?id=19',
				'name' => 'STP1-B'
			),
			'STP1_C' => array(
				'image' => plugins_url('/images/csma_03.png',__FILE__),
				'preview' => 'https://clients.acurax.com/link.php?id=20',
				'name' => 'STP1-C'
			),
			'STP1_D' => array(
				'image' => plugins_url('/images/csma_04.png',__FILE__),
				'preview' => 'https://clients.acurax.com/link.php?id=21',
				'name' => 'STP1-D'
			),
	);
	$acx_theme_addon_demo_array = apply_filters('acx_csma_demo_theme_filter_hook',$acx_theme_addon_demo_array);
	if($acx_theme_addon_demo_array == '')
	{
		$acx_theme_addon_demo_array = array();
	}
	$acx_csma_lb_title = __('Showcase Products and Services While Your Website Is Under Construction',ACX_CSMA_WP_SLUG);
	$acx_csma_lb_content = "<p style=\"font-size:14px;\">".__('We have prepared 4 special themes ',ACX_CSMA_WP_SLUG)."( <a href=\"https://clients.acurax.com/link.php?id=18\" target=\"_blank\" title=\"Preview This Theme\">".__('STP1-A',ACX_CSMA_WP_SLUG)."</a>, <a href=\"https://clients.acurax.com/link.php?id=19\" target=\"_blank\" title=\"Preview This Theme\">".__('STP1-B',ACX_CSMA_WP_SLUG)."</a>, <a href=\"https://clients.acurax.com/link.php?id=20\" target=\"_blank\" title=\"Preview This Theme\">".__('STP1-C',ACX_CSMA_WP_SLUG)."</a>".__(' and',ACX_CSMA_WP_SLUG)." <a href=\"https://clients.acurax.com/link.php?id=21\" target=\"_blank\" title=\"Preview This Theme\">".__('STP1-D',ACX_CSMA_WP_SLUG)."</a> )". __('as an Addon plugin labeled "Service Theme Pack 1" which you can install just like a normal plugin after purchase. And the additional themes will be available here. These themes are designed and developed with high customizable options.',ACX_CSMA_WP_SLUG)."<br><br>".__('This theme pack have 4 highly customizable themes with Contact/Lead Capture Form,Service/Product Showcase, About us Section etc... Check preview for a live preview.',ACX_CSMA_WP_SLUG)."<br><br><a style=\"float: left; color: black; border: 1px solid black; border-radius: 3px; padding: 4px; font-size: 13px; opacity: 0.8;\"href=\"http://www.acurax.com/products/under-construction-maintenance-mode-wordpress-plugin/?feature=service-theme-pack-1&utm_source=preview_link&utm_medium=csma&utm_campaign=csma\" style=\"float:right;\" target=\"_blank\">".__('View Screenshots/More Details',ACX_CSMA_WP_SLUG)."</a><a href=\"https://clients.acurax.com/order.php?pid=csmastp1&utm_source=preview_link&utm_medium=csma&utm_campaign=csma\" style=\"float:right;\" target=\"_blank\" class=\"button\">".__('Click Here to Order Now',ACX_CSMA_WP_SLUG)."</a><br></p><p><br></p>";
	foreach($acx_theme_addon_demo_array as $key => $value)
	{
		foreach($value as $k => $v)
		{
		echo "<div id='img_holder' class='img_holder_demo'><label for='".$k."'><img src='".$v['image']."' class='acx_csma_info_lb' lb_title='".$acx_csma_lb_title."' lb_content='".$acx_csma_lb_content."'></label><br /><input type='radio' class='acx_csma_info_lb acx_csma_info_lb_demo' name='acx_csma_template_demo' id='".$k."' lb_title='".$acx_csma_lb_title."' lb_content='".$acx_csma_lb_content."' />".$v['name']."<br /><a href='".esc_url($v['preview'])."' target='_blank'>".__('Preview',ACX_CSMA_WP_SLUG)."</a></div>";
		}
	}
}
add_action('acx_csma_hook_mainoptions_below_add_template','acx_csma_service_addon_demo');
function acx_csm_info_lb()
{
?>
<script type="text/javascript">
jQuery( ".img_holder_demo .acx_csma_info_lb_demo" ).click(function() {
	jQuery(this).attr('checked', false);
});
jQuery( ".acx_csma_info_lb" ).click(function() {
	var lb_title = jQuery(this).attr('lb_title');
	var lb_content = jQuery(this).attr('lb_content');
	var html= '<div id="acx_csma_c_icon_p_info_lb_h" style="display:none;"><div class="acx_csma_c_icon_p_info_c"><span class="acx_csma_c_icon_p_info_close" onclick="acx_csma_remove_info()"></span><h4>'+lb_title+'</h4><div class="acx_csma_c_icon_p_info_content">'+lb_content+'</div></div></div> <!-- acx_csma_c_icon_p_info_lb_h -->';
	jQuery( "body" ).append(html)
	jQuery( "#acx_csma_c_icon_p_info_lb_h" ).fadeIn();
});

function acx_csma_remove_info()
{
	jQuery( "#acx_csma_c_icon_p_info_lb_h" ).fadeOut()
	jQuery( "#acx_csma_c_icon_p_info_lb_h" ).remove();
	var lb_title = "";
	var lb_content = "";
};
</script>
<?php
}
add_action('acx_csma_hook_mainoptions_below_general_settings','acx_csm_info_lb');
add_action('acx_csma_hook_mainoptions_below_javascript','acx_csm_info_lb');
function acx_csma_updated_fields_content()
{
	$acx_csma_appearence_array=acx_csma_get_db_array_value(); 
	if(is_array($acx_csma_appearence_array))
	{
		if(ISSET($acx_csma_appearence_array['1']))
		{
			if(!array_key_exists('acx_csma_show_subscription',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_show_subscription'] = 1;
			}
			if(!array_key_exists('acx_csma_custom_html_top_sub1',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_custom_html_top_sub1'] = "";
			}
			if(!array_key_exists('acx_csma_custom_html_bottom_sub1',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_custom_html_bottom_sub1'] = "";
			}
			if(!array_key_exists('acx_csma_custom_css_temp1',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_custom_css_temp1'] = "";
			}
			if(!array_key_exists('acx_csma_custom_html_top_temp1_title',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_custom_html_top_temp1_title'] = "";
			}
			if(!array_key_exists('acx_csma_footer_gdprcolor1',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_footer_gdprcolor1'] = "#ffffff";
			}
			if(!array_key_exists('acx_csma_footer_gdpr_hovercolor1',$acx_csma_appearence_array['1']))
			{
				$acx_csma_appearence_array['1']['acx_csma_footer_gdpr_hovercolor1'] = "#e3e3e3";
			}
		}
		if(ISSET($acx_csma_appearence_array['2']))
		{
			if(!array_key_exists('acx_csma_custom_html_top_timer',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_custom_html_top_timer'] = "";
			}
			if(!array_key_exists('acx_csma_show_subscription2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_show_subscription2'] = 1;
			}
			if(!array_key_exists('acx_csma_subscribe_btn_text2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_subscribe_btn_text2'] = __("Submit",ACX_CSMA_WP_SLUG);
			}
			if(!array_key_exists('acx_csma_custom_html_above_timer',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_custom_html_above_timer'] = "";
			}
			if(!array_key_exists('acx_csma_show_timer2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_show_timer2'] = 1;
			}
			if(!array_key_exists('acx_csma_custom_css_temp2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_custom_css_temp2'] = "";
			}
			if(!array_key_exists('acx_csma_footer_gdprcolor2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_footer_gdprcolor2'] = "#000000";
			}
			if(!array_key_exists('acx_csma_footer_gdpr_hovercolor2',$acx_csma_appearence_array['2']))
			{
				$acx_csma_appearence_array['2']['acx_csma_footer_gdpr_hovercolor2'] = "#8f8f8f";
			}
		}
		if(ISSET($acx_csma_appearence_array['3']))
		{
			if(!array_key_exists('acx_csma_primary_color3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_primary_color3'] = "#ffffff";
			}
			if(!array_key_exists('acx_csma_secondary_color3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_secondary_color3'] = "#fe7e01";
			}
			if(!array_key_exists('acx_csma_left_bar_color3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_left_bar_color3'] = "#000000";
			}
			if(!array_key_exists('acx_csma_timer_color3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_timer_color3'] = "#ffffff";
			}
			if(!array_key_exists('acx_csma_show_subscription3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_show_subscription3'] = 1;
			}
			if(!array_key_exists('acx_csma_show_timer3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_show_timer3'] = 1;
			}
			if(!array_key_exists('acx_csma_custom_html_top_timer_temp3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_custom_html_top_timer_temp3'] = "";
			}
			if(!array_key_exists('acx_csma_custom_html_bottom_temp3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_custom_html_bottom_temp3'] = "";
			}
			if(!array_key_exists('acx_csma_custom_css_temp3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_custom_css_temp3'] = "";
			}
			if(!array_key_exists('acx_csma_footer_gdprcolor3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_footer_gdprcolor3'] = "#ffffff";
			}
			if(!array_key_exists('acx_csma_footer_gdpr_hovercolor3',$acx_csma_appearence_array['3']))
			{
				$acx_csma_appearence_array['3']['acx_csma_footer_gdpr_hovercolor3'] = "#fe7e01";
			}
		}
		if(ISSET($acx_csma_appearence_array['4']))
		{
			if(!array_key_exists('acx_csma_custom_css_temp4',$acx_csma_appearence_array['4']))
			{
				$acx_csma_appearence_array['4']['acx_csma_custom_css_temp4'] = "";
			}
			if(!array_key_exists('acx_csma_footer_gdprcolor4',$acx_csma_appearence_array['4']))
			{
				$acx_csma_appearence_array['4']['acx_csma_footer_gdprcolor4'] = "#000000";
			}
			if(!array_key_exists('acx_csma_footer_gdpr_hovercolor4',$acx_csma_appearence_array['4']))
			{
				$acx_csma_appearence_array['4']['acx_csma_footer_gdpr_hovercolor4'] = "#adadad";
			}
		}
		if(ISSET($acx_csma_appearence_array['5']))
		{
			if(!array_key_exists('acx_csma_show_subscription5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_show_subscription5'] = 1;
			}
			if(!array_key_exists('acx_csma_subscribe_btn_text5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_subscribe_btn_text5'] = __("Submit",ACX_CSMA_WP_SLUG);
			}
			if(!array_key_exists('acx_csma_custom_html_top_sub',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_custom_html_top_sub'] = "";
			}
			if(!array_key_exists('acx_csma_custom_html_bottom_sub',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_custom_html_bottom_sub'] = "";
			}
			if(!array_key_exists('acx_csma_launch_title_color5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_launch_title_color5'] = "#4b4b4b";
			}
			if(!array_key_exists('acx_csma_custom_css_temp5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_custom_css_temp5'] = "";
			}
			if(!array_key_exists('acx_csma_footer_gdprcolor5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_footer_gdprcolor5'] = "#ffffff";
			}
			if(!array_key_exists('acx_csma_footer_gdpr_hovercolor5',$acx_csma_appearence_array['5']))
			{
				$acx_csma_appearence_array['5']['acx_csma_footer_gdpr_hovercolor5'] = "#a19e99";
			}
		}	
	}
	$acx_csma_appearence_array=acx_csma_get_db_array_value(); 
}
add_action('acx_csma_hook_mainoptions_inside_else_submit','acx_csma_updated_fields_content');
function acx_csma_getrealip()
{
	if ( isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) {
      return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if ( isset( $_SERVER["REMOTE_ADDR"] ) ) {
      return $_SERVER['REMOTE_ADDR'];
    }
}
// custom html before saving
function acx_csma_custom_html_before_save_hook_fn($name,$value)
{
	$value = apply_filters('acx_csma_custom_html_before_save_hook',$name,$value);
	return $value;
} 
function acx_csma_custom_html_after_save_hook_fn($value)
{
	$value = apply_filters('acx_csma_text_after_save_hook',$value);
	return $value;
}
function acx_csma_text_before_save_hook_fn($name,$value)
{
	$value = apply_filters('acx_csma_text_before_save_hook',$name,$value);
	return $value;
}
function acx_csma_text_after_save_hook_fn($value)
{
	$value = apply_filters('acx_csma_text_after_save_hook',$value);
	return $value;
}
function acx_csma_option_text_after_save_hook_fn($value)
{
	$value = apply_filters('acx_csma_option_text_after_save_hook',$value);
	$value = apply_filters('acx_csma_text_after_save_hook',$value);
	return $value;
}
function acx_csma_textarea_before_save_hook_function($name,$value)
{
	$value = apply_filters('acx_csma_text_area_before_save_hook',$name,$value);
	return $value;
}
function acx_csma_textarea_after_save_hook_function($value)
{
	$value = apply_filters('acx_csma_text_after_save_hook',$value);
	return $value;
}
function acx_customhtml_stripslashes($name,$value)
{
	if(ISSET($_POST[$name]))
	{
		if($_POST[$name] == $value)
		{
			$value = stripslashes($_POST[$name]);
		}
	}
	return $value;
}
add_filter('acx_csma_custom_html_before_save_hook','acx_customhtml_stripslashes',25,2);
function acx_custom_html_trim($name,$value)
{
	if(ISSET($_POST[$name]))
	{
		if($_POST[$name] == $value)
		{
			$value = trim($_POST[$name]);
		}
	}
	return $value;
}
add_filter('acx_csma_custom_html_before_save_hook','acx_custom_html_trim',20,2);

function acx_sanitize_text($name,$value)
{
	if(ISSET($_POST[$name]))
	{
		if($_POST[$name] == $value)
		{
			$value = sanitize_text_field($_POST[$name]);
		}
	}
	return $value;
}
add_filter('acx_csma_text_before_save_hook','acx_sanitize_text',10,2); 
function acx_stripslashes_text_after_save($value)
{
	$value = stripslashes($value);
	return $value;
}
add_filter('acx_csma_text_after_save_hook','acx_stripslashes_text_after_save',20,1);
function acx_esc_attr_text_after_save($value)
{
	$value = esc_attr($value);
	return $value;
}
add_filter('acx_csma_option_text_after_save_hook','acx_esc_attr_text_after_save',15,1);
function acx_text_area_stripslashes($name,$value)
{
	if(ISSET($_POST[$name]))
	{
		if($_POST[$name] == $value)
		{
			$value = stripslashes($_POST[$name]);
		}
	}
	return $value;
}
add_filter('acx_csma_text_area_before_save_hook','acx_text_area_stripslashes',20,2);
function acx_csma_display_var_content()
{ 
	$display_content = '';
	$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
	if(is_serialized($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
	}
	if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr = array();
	}
	$display_content.="<hr />";
	ksort($acx_csma_display_var_arr);
	$display_content.="<table class='wp-list-table widefat fixed striped'><th>".__('Text',ACX_CSMA_WP_SLUG)."</th><th>".__('Variable',ACX_CSMA_WP_SLUG)."</th><th>".__('Action',ACX_CSMA_WP_SLUG)."</th>";
	foreach($acx_csma_display_var_arr as $key => $values)
	{
		$singular = '';
		$plural = '';
		if(ISSET($values['singular']))
		{
			$singular=$values['singular'];
		}
		if(ISSET($values['plural']))
		{
			$plural=$values['plural'];
		}
		if($singular != '' && $plural != '' )
		{
			$value = $singular."/".$plural;
		}
		else if($singular == '')
		{
			$value = $plural;
		}
		else{
			$value = $singular;
		}
	$display_content.="<tr><td>".ucfirst($key)."</td><td>".acx_csma_option_text_after_save_hook_fn($value)."</td><td><span id='acx_disp_edit_link'><a onclick='acx_csma_disp_var_edit(\"$key\",\"$singular\",\"$plural\");' id='acx_csma_disp_var_edit'>&nbsp".__('Edit',ACX_CSMA_WP_SLUG)."</a></span><span id='acx_disp_reset_link' style='margin-left: 23px;'><a onclick='acx_csma_disp_var_reset(\"$key\");' id='acx_csma_disp_var_edit'>&nbsp".__('Reset to Default',ACX_CSMA_WP_SLUG)."</a></span></td></tr>";
	}
	$display_content.="</table>";
	return $display_content;
}
function acx_csma_display_variables()
{
	echo "<div id='acx_csma_display_variable_content'>";
	$acx_csma_dis_cont=acx_csma_display_var_content();
	echo $acx_csma_dis_cont;
	echo "</div>";
	?>
<div class="acx_csma_disp_edit_litbx" style="display:none;">
		<div class="acx_csma_disp_edit_inner">
			<div class='acx_csma_disp_edit_close_btn' onclick='acx_csma_disp_edit_cls();'></div>
				<div class="acx_csma_disp_edit_ltbx1" id="acx_csma_disp_edit_ltbx">
				</div>
		</div>
</div> 
	<script>
	function acx_csma_disp_var_edit(key,singular,plural)
	{
		
		var acx_load="<div id='acx_csmap_loading_1'><div class='load_1'></div></div>";
		jQuery('body').append(acx_load);
		var acx_csma_open_lb ="<?php echo admin_url('admin-ajax.php'); ?>";
		var order = 'key='+key+'&singular='+singular+'&plural='+plural+'&action=acx_csma_open_disp_var'+'&acx_csma_open_disp_var_e=<?php echo wp_create_nonce('acx_csma_open_disp_var_e'); ?>';
		jQuery.post(acx_csma_open_lb, order, function(theResponse)
		{
			jQuery("#acx_csmap_loading_1").remove();
			if(theResponse)
			{
				jQuery('#acx_csma_disp_edit_ltbx').html(theResponse);
				jQuery('.acx_csma_disp_edit_litbx').show();
			}
		});
			
	}
	function acx_csma_disp_edit_cls()
	{
		jQuery('.acx_csma_disp_edit_litbx').hide();
	}
	function acx_csma_edit_disp_var()
	{
		var key = jQuery('#acx_csma_edit_key').val();
		if(key == "")
		{
			alert('<?php _e('Something Went Wrong\nTry Again!!!',ACX_CSMA_WP_SLUG);?>');
			return false;
		}
		var singular=jQuery('#acx_csma_edit_singular').val();
		var plural=jQuery('#acx_csma_edit_plural').val();
		var acx_load="<div id='acx_csmap_loading_1'><div class='load_1'></div></div>";
		jQuery('#acx_csma_edit_box').append(acx_load);
		var acx_csma_edit_ajaxurl ="<?php echo admin_url('admin-ajax.php'); ?>";
		var order = 'key='+key+'&singular='+singular+'&plural='+plural+'&action=acx_csma_edit_disp_var'+'&acx_csma_edit_var=<?php echo wp_create_nonce('acx_csma_edit_var'); ?>';
		jQuery.post(acx_csma_edit_ajaxurl, order, function(theResponse)
		{
			jQuery("#acx_csmap_loading_1").remove();
			if(theResponse)
			{
				jQuery('#acx_csma_display_variable_content').html(theResponse);
				jQuery('#acx_csma_edit_key').val('');
				jQuery('#acx_csma_edit_singular').val('');
				jQuery('#acx_csma_edit_plural').val('');
				jQuery('.acx_csma_disp_edit_litbx').hide();
			}
			else{
				alert('<?php _e('Something Went Wrong\nTry Again!!!',ACX_CSMA_WP_SLUG);?>');
			}
		});
	}
	function acx_csma_disp_var_reset(key)
	{
		var acx_load1="<div id='acx_csmap_loading_1'><div class='load_1'></div></div>";
		jQuery('body').append(acx_load1);
		var acx_csma_edit_ajaxurl1 ="<?php echo admin_url('admin-ajax.php'); ?>";
		var order = 'key='+key+'&action=acx_csma_reset_disp_var'+'&acx_csma_reset_var=<?php echo wp_create_nonce('acx_csma_reset_var'); ?>';
		jQuery.post(acx_csma_edit_ajaxurl1, order, function(theResponse)
		{
			jQuery("#acx_csmap_loading_1").remove();
			if(theResponse)
			{
				jQuery('#acx_csma_display_variable_content').html(theResponse);
			}
			else
			{
				alert('<?php _e('Something Went Wrong\nTry Again!!!',ACX_CSMA_WP_SLUG);?>');
			}
		});	
	}
	</script>
	<?php
}

function acx_csma_reset_disp_var_callback()
{
	if (!isset($_POST['acx_csma_reset_var'])) die("<br><br>__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)<a href = ''>__('Click Here',ACX_CSMA_WP_SLUG)</a>");
	if (!wp_verify_nonce($_POST['acx_csma_reset_var'],'acx_csma_reset_var')) die("<br><br>__('Unknown Error Occurred, Try Again... ,ACX_CSMA_WP_SLUG)<a href = ''>__('Click Here,ACX_CSMA_WP_SLUG)</a>");
	if(!current_user_can('manage_options')) die("<br><br>__('Sorry, You have no permission to do this action...',ACX_CSMA_WP_SLUG)</a>");
	$acx_csma_reset_key = '';
	if (isset($_POST['key']))
	{
		$acx_csma_reset_key = $_POST['key'];
	}
	$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
	if(is_serialized($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
	}
	if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr = array();
	}
	if(ISSET($acx_csma_display_var_arr[$acx_csma_reset_key]['default_singular']))
	{
		$acx_csma_display_var_arr[$acx_csma_reset_key]['singular']=$acx_csma_display_var_arr[$acx_csma_reset_key]['default_singular'];
	}
	if(ISSET($acx_csma_display_var_arr[$acx_csma_reset_key]['default_plural']))
	{
		$acx_csma_display_var_arr[$acx_csma_reset_key]['plural']=$acx_csma_display_var_arr[$acx_csma_reset_key]['default_plural'];
	}
	if(!is_serialized($acx_csma_display_var_arr))
	{ 
		$acx_csma_display_var_arr = serialize($acx_csma_display_var_arr); 
	}
	update_option('acx_csma_display_var_arr',$acx_csma_display_var_arr);
	$acx_csma_dis_cont=acx_csma_display_var_content();
	echo $acx_csma_dis_cont;
	die();
}
add_action( 'wp_ajax_acx_csma_reset_disp_var', 'acx_csma_reset_disp_var_callback' );
function acx_csma_edit_disp_var_callback()
{
	if (!isset($_POST['acx_csma_edit_var'])) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href = ''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if (!wp_verify_nonce($_POST['acx_csma_edit_var'],'acx_csma_edit_var')) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href = ''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if(!current_user_can('manage_options')) die("<br><br>".__('Sorry, You have no permission to do this action...',ACX_CSMA_WP_SLUG)."</a>");
	$acx_csma_edit_key = $acx_csma_edit_singular = $acx_csma_edit_plural = '';
	if (isset($_POST['key']))
	{
		$acx_csma_edit_key = $_POST['key'];
	}
	if (isset($_POST['singular']))
	{
		$acx_csma_edit_singular = $_POST['singular'];
	}
	if (isset($_POST['plural']))
	{
		$acx_csma_edit_plural = $_POST['plural'];
	}
	$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
	if(is_serialized($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
	}
	if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr = array();
	}
	if(ISSET($acx_csma_display_var_arr[$acx_csma_edit_key]['default_singular']))
	{
		$acx_csma_display_var_arr[$acx_csma_edit_key]['singular']=$acx_csma_edit_singular;
	}
	if(ISSET($acx_csma_display_var_arr[$acx_csma_edit_key]['default_plural']))
	{
		$acx_csma_display_var_arr[$acx_csma_edit_key]['plural']=$acx_csma_edit_plural;
	}
	if(!is_serialized($acx_csma_display_var_arr))
	{ 
		$acx_csma_display_var_arr = serialize($acx_csma_display_var_arr); 
	}
	update_option('acx_csma_display_var_arr',$acx_csma_display_var_arr);
	$acx_csma_dis_cont=acx_csma_display_var_content();
	echo $acx_csma_dis_cont;
	die();
}
add_action( 'wp_ajax_acx_csma_edit_disp_var', 'acx_csma_edit_disp_var_callback' );
function acx_csma_open_disp_var_callback()
{
	if (!isset($_POST['acx_csma_open_disp_var_e'])) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href = ''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if (!wp_verify_nonce($_POST['acx_csma_open_disp_var_e'],'acx_csma_open_disp_var_e')) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href = ''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if(!current_user_can('manage_options')) die("<br><br>".__('Sorry, You have no permission to do this action...',ACX_CSMA_WP_SLUG)."</a>");
	$response = $acx_csma_key = $acx_csma_singular = $acx_csma_plural = '';
	if (isset($_POST['key']))
	{
		$acx_csma_key = $_POST['key'];
	}
	if (isset($_POST['singular']))
	{
		$acx_csma_singular = $_POST['singular'];
	}
	if (isset($_POST['plural']))
	{
		$acx_csma_plural = $_POST['plural'];
	}
	$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
	if(is_serialized($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
	}
	if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr = array();
	}
	$heading = ucfirst($acx_csma_key);
	$response .= "<div id='acx_csma_edit_box'><div id='acx_csma_edit_box_inner'><span id='acx_csma_heading'><h3>".__('Edit Text for ',ACX_CSMA_WP_SLUG) .$heading."</h3></span><hr><div id='acx_csma_disp_var_inside_cnt'>";
	if(ISSET($acx_csma_display_var_arr[$acx_csma_key]['default_singular']))
	{
		$response .= "<div class='acx_csma_input_cvr'><span class='acx_csma_disp_label'>".__('Text for Singular:',ACX_CSMA_WP_SLUG)."</span><span class='acx_csma_disp_input'><input type='text' name='acx_csma_edit_singular' id='acx_csma_edit_singular' value='".acx_csma_option_text_after_save_hook_fn($acx_csma_singular)."'></span></div>";
	}
	if(ISSET($acx_csma_display_var_arr[$acx_csma_key]['default_plural']))
	{
	$response .= "<div class='acx_csma_input_cvr'><span class='acx_csma_disp_label'>".__('Text for Plural: ',ACX_CSMA_WP_SLUG)."</span><span class='acx_csma_disp_input'><input type='text' name='acx_csma_edit_plural' id='acx_csma_edit_plural' value='".acx_csma_option_text_after_save_hook_fn($acx_csma_plural)."'></span></div>";
	}
	$response .= "<span class='acx_csma_disp_input'><input type='hidden' name='acx_csma_edit_key' id='acx_csma_edit_key' value='".$acx_csma_key."'></span><span class='acx_csma_disp_btn'><button type='button' class='button' onclick='acx_csma_edit_disp_var();'>".__('Save',ACX_CSMA_WP_SLUG)."</button></span></div></div></div>";
	echo $response;
	die();
}
add_action( 'wp_ajax_acx_csma_open_disp_var', 'acx_csma_open_disp_var_callback' );
if(!function_exists('acx_csma_disp_var_to_show'))
{
	function acx_csma_disp_var_to_show($acx_csma_disp_key)
	{
		$display_response = array();
		$acx_csma_edit_plural = "";
		$acx_csma_edit_singular = "";
		$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
		if(is_serialized($acx_csma_display_var_arr))
		{
			$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
		}
		if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
		{
			$acx_csma_display_var_arr = array();
		}
		if(isset($acx_csma_display_var_arr[$acx_csma_disp_key]['singular']))
		{
			$acx_csma_edit_singular=$acx_csma_display_var_arr[$acx_csma_disp_key]['singular'];
		}
		if(isset($acx_csma_display_var_arr[$acx_csma_disp_key]['plural']))
		{
			$acx_csma_edit_plural=$acx_csma_display_var_arr[$acx_csma_disp_key]['plural'];
		}
		$display_response['singular']=$acx_csma_edit_singular;
		$display_response['plural']=$acx_csma_edit_plural;
		return $display_response;
	}
}
function acx_csma_get_db_array_value()
{
	$acx_csma_appearence_array=get_option('acx_csma_appearence_array');
	$acx_csma_appearence_array = apply_filters('acx_csma_demo_get_array_filter',$acx_csma_appearence_array);
	if($acx_csma_appearence_array != "")
	{
		if(is_serialized($acx_csma_appearence_array))
		{ 
			$acx_csma_appearence_array = unserialize($acx_csma_appearence_array); 
		}
		if($acx_csma_appearence_array == "" || !is_array($acx_csma_appearence_array))
		{
			$acx_csma_appearence_array = array();
		}
	}
	return $acx_csma_appearence_array;
}
function acx_csma_demo_get_array_filter_low_priority($acx_csma_appearence_array)
{
	return $acx_csma_appearence_array;
}
add_filter('acx_csma_demo_get_array_filter','acx_csma_demo_get_array_filter_low_priority',5);
function acx_csma_update_array_value($acx_csma_appearence_array)
{
	$acx_csma_appearence_array = apply_filters('acx_csma_demo_update_array_filter',$acx_csma_appearence_array);
	if(!is_serialized($acx_csma_appearence_array))
	{
		$acx_csma_appearence_array = serialize($acx_csma_appearence_array); 
	}
	update_option('acx_csma_appearence_array',$acx_csma_appearence_array);
}
// --- Making Sure acx_csma_appearence_array has all indexes starts here -----
function acx_csma_appearence_array_refresh()
{
	global $acx_csma_appearence_array_default;
	
	$acx_csma_appearence_array = acx_csma_get_db_array_value();
	$changes_happened = false;
	if($acx_csma_appearence_array == "")
	{
		$acx_csma_appearence_array = array();
	}
	$acx_csma_appearence_array_cloned = $acx_csma_appearence_array;
	foreach($acx_csma_appearence_array_default as $key => $value)
	{
		if(is_array($acx_csma_appearence_array_cloned) && !array_key_exists($key,$acx_csma_appearence_array_cloned)) // If Template Not Available then add
		{
			$acx_csma_appearence_array_cloned[$key] = $acx_csma_appearence_array_default[$key];
			$changes_happened = true;
		} else // If Template is Available Then Checking Keys
		{
			if(is_array($value))
			{
				foreach($value as $key2 => $value2)
				{
					if(is_array($acx_csma_appearence_array_cloned[$key]) && !array_key_exists($key2,$acx_csma_appearence_array_cloned[$key])) // If Template Not Available then add
					{
						$acx_csma_appearence_array_cloned[$key][$key2] = $acx_csma_appearence_array_default[$key][$key2];
						$changes_happened = true;
					}
				}
			}
		}
	}
	if($changes_happened == true)
	{
		acx_csma_update_array_value($acx_csma_appearence_array_cloned);
	}
}
add_action( 'init', 'acx_csma_appearence_array_refresh');
function update_acx_csma_display_var_array()
{
	$acx_csma_display_var_arr=get_option('acx_csma_display_var_arr');
	$change_status = false;
	if(is_serialized($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr=unserialize($acx_csma_display_var_arr);
	}
	if($acx_csma_display_var_arr == "" || !is_array($acx_csma_display_var_arr))
	{
		$acx_csma_display_var_arr = array();
	}
	if(is_array($acx_csma_display_var_arr))
	{
		if(!ISSET($acx_csma_display_var_arr['Subscription Name Placeholder']))
		{
			$acx_csma_display_var_arr['Subscription Name Placeholder'] = array(
			'singular'=>__('Enter Your Name Here',ACX_CSMA_WP_SLUG),
			'default_singular'=>__('Enter Your Name Here',ACX_CSMA_WP_SLUG),
			);
			$change_status = true;
		}
		if(!ISSET($acx_csma_display_var_arr['Subscription Email Placeholder']))
		{
			$acx_csma_display_var_arr['Subscription Email Placeholder'] = array(
			'singular'=>__('Enter Your Email Here',ACX_CSMA_WP_SLUG),
			'default_singular'=>__('Enter Your Email Here',ACX_CSMA_WP_SLUG),
			);
			$change_status = true;
		}
		if(!ISSET($acx_csma_display_var_arr['Subscription Name Error Message']))
		{
			$acx_csma_display_var_arr['Subscription Name Error Message'] = array(
			'singular'=>__('Please Enter Your Name Here',ACX_CSMA_WP_SLUG),
			'default_singular'=>__('Please Enter Your Name Here',ACX_CSMA_WP_SLUG),
			);
			$change_status = true;
		}
		if(!ISSET($acx_csma_display_var_arr['Subscription Email Error Message']))
		{
			$acx_csma_display_var_arr['Subscription Email Error Message'] = array(
			'singular'=>__('Please Enter Your Email Here',ACX_CSMA_WP_SLUG),
			'default_singular'=>__('Please Enter Your Email Here',ACX_CSMA_WP_SLUG),
			);
			$change_status = true;
		}
		if(!ISSET($acx_csma_display_var_arr['Subscription Email Exists Message']))
		{
			$acx_csma_display_var_arr['Subscription Email Exists Message'] = array(
			'singular'=>__('Email Already Exists',ACX_CSMA_WP_SLUG),
			'default_singular'=>__('Email Already Exists',ACX_CSMA_WP_SLUG),
			);
			$change_status = true;
		}
		if($change_status == true)
		{
			if(!is_serialized($acx_csma_display_var_arr))
			{
				$acx_csma_display_var_arr = serialize($acx_csma_display_var_arr); 
			}
			update_option('acx_csma_display_var_arr',$acx_csma_display_var_arr);
		}
	}
}
add_action('init','update_acx_csma_display_var_array');
// --- Making Sure acx_csma_appearence_array has all indexes ends here -----
function print_acx_csma_option_heading($heading)
{
	$heading_format = "<h2 class='acx_csma_option_head'>";
	$heading_format .= $heading;
	$heading_format .= "</h2>";
	return $heading_format;
}
function print_acx_csma_option_block_start($title,$pre_fix="",$suf_fix="")
{
	global $acx_csma_options_uid;
	if(!$acx_csma_options_uid || $acx_csma_options_uid == "")
	{
		$acx_csma_options_uid = 0;
	}
	$acx_csma_options_uid = $acx_csma_options_uid+1;
	echo "<div class='acx_csma_q_holder acx_csma_q_holder_".$acx_csma_options_uid."'>";
	echo $pre_fix;
	echo "<h4>".$title."</h4>";
	echo $suf_fix;
	echo "<div class='acx_csma_q_holder_c acx_csma_q_holder_c_".$acx_csma_options_uid."'>";
}
function print_acx_csma_option_block_end()
{
	echo "</div> <!-- acx_csma_q_holder_c -->";
	echo "</div> <!-- acx_csma_q_holder -->";
}
function acx_csma_post_isset_check($field)
{
	$value = "";
	if(ISSET($_POST[$field]))
	{
		$value = $_POST[$field];
	}
	return $value;
}
/******************* MISC PAGE ****************************************/
/* 	Acurax Service/Info Settings HTML - Get - Set Default Logic Starts Here */
function acx_csma_misc_service_info_html()
{
	$acx_string = __('Acurax Service/Info Settings',ACX_CSMA_WP_SLUG);
	print_acx_csma_option_block_start($acx_string);	
	do_action('acx_csma_misc_service_info');
	echo "<span class='acx_csma_q_sep'></span>";
	print_acx_csma_option_block_end();
}  add_action('acx_csma_misc_hook_option_fields','acx_csma_misc_service_info_html',100);
function acx_csma_service_info_option()
{
	global $acx_csma_service_banners;
	echo "<span class='label' style='width:50%;'>". __('Acurax Service Info ',ACX_CSMA_WP_SLUG)."</span>";?>
	<select name="acx_csma_service_banners" style="width:49%;">
	<option value="yes"<?php if ($acx_csma_service_banners == "yes") { echo 'selected="selected"'; } ?>><?php _e('Show Acurax Service Banner',ACX_CSMA_WP_SLUG);?></option>
	<option value="no"<?php if ($acx_csma_service_banners == "no") { echo 'selected="selected"'; } ?>><?php _e('Hide Acurax Service Banner',ACX_CSMA_WP_SLUG); ?></option>
	</select>
	<?php
	echo "<span class='acx_csma_q_sep'></span>";
}
add_action('acx_csma_misc_service_info','acx_csma_service_info_option',100);
function acx_csma_service_info_if()
{
	global $acx_csma_service_banners;
	$acx_csma_service_banners = sanitize_text_field(acx_csma_post_isset_check('acx_csma_service_banners'));
	update_option('acx_csma_service_banners', $acx_csma_service_banners);
} add_action('acx_csma_misc_hook_option_onpost','acx_csma_service_info_if');

function acx_csma_service_info_else()
{
	global $acx_csma_service_banners;
	$acx_csma_service_banners = get_option('acx_csma_service_banners');
} 
add_action('acx_csma_misc_hook_option_postelse','acx_csma_service_info_else');
function acx_csma_service_info_after_else()
{
	global $acx_csma_service_banners;

if ($acx_csma_service_banners == "") {	$acx_csma_service_banners = "yes"; }
} add_action('acx_csma_misc_hook_option_after_else','acx_csma_service_info_after_else');

/* 	Acurax Service/Info Settings HTML - Get - Set Default Logic Ends Here */

/* 	Expert Support Menu Settings HTML - Get - Set Default Logic Starts Here */
function acx_csma_misc_expert_support_option()
{
	global $acx_csma_hide_expert_support_menu;
	echo "<span class='label' style='width:50%;'>". __('Expert Support Menu',ACX_CSMA_WP_SLUG)."</span>";?>
	<select name="acx_csma_hide_expert_support_menu" style="width:49%;">
<option value="yes"<?php if ($acx_csma_hide_expert_support_menu == "yes") { echo 'selected="selected"'; } ?>><?php _e('Hide Expert Support Menu From Acurax');?></option>
<option value="no"<?php if ($acx_csma_hide_expert_support_menu == "no") { echo 'selected="selected"'; } ?>><?php _e('Show Expert Support Menu From Acurax');?></option>
</select>
	<?php
	echo "<span class='acx_csma_q_sep'></span>";
	
}
add_action('acx_csma_misc_service_info','acx_csma_misc_expert_support_option',300);
function acx_csma_expert_support_if()
{
	global $acx_csma_hide_expert_support_menu;
	$acx_csma_hide_expert_support_menu = sanitize_text_field(acx_csma_post_isset_check('acx_csma_hide_expert_support_menu'));
	update_option('acx_csma_hide_expert_support_menu', $acx_csma_hide_expert_support_menu);
} add_action('acx_csma_misc_hook_option_onpost','acx_csma_expert_support_if');

function acx_csma_expert_support_else()
{
	global $acx_csma_hide_expert_support_menu;
	$acx_csma_hide_expert_support_menu = get_option('acx_csma_hide_expert_support_menu');
} 
add_action('acx_csma_misc_hook_option_postelse','acx_csma_expert_support_else');
function acx_csma_expert_support_after_else()
{
	global $acx_csma_hide_expert_support_menu;

if ($acx_csma_hide_expert_support_menu == "") {	$acx_csma_hide_expert_support_menu = "no"; }
} add_action('acx_csma_misc_hook_option_after_else','acx_csma_expert_support_after_else');

/*	Expert Support Menu Settings  Settings HTML - Get - Set Default Logic Ends Here */

/* 	Send Header Settings HTML - Get - Set Default Logic Starts Here */
function acx_csma_send_header_option()
{
	global $acx_csma_send_header_option;
	echo "<span class='label' style='width:50%;'>". __('Send ',ACX_CSMA_WP_SLUG)."<a href='https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html#sec10.5.4' target='_blank'>". __('503 Header [?]',ACX_CSMA_WP_SLUG)."</a>". __(' On Maintenance Mode?',ACX_CSMA_WP_SLUG)."</span>";?>
	<select name="acx_csma_send_header_option" style="width:49%;">
<option value="yes"<?php if ($acx_csma_send_header_option == "yes") { echo 'selected="selected"'; } ?>><?php _e('Yes, Send 503 Header');?></option>
<option value="no"<?php if ($acx_csma_send_header_option == "no") { echo 'selected="selected"'; } ?>><?php _e('No, Dont Send 503 Header');?></option>
</select>
	<?php
	echo "<span class='acx_csma_q_sep'></span>";
}
add_action('acx_csma_misc_service_info','acx_csma_send_header_option',300);
function acx_csma_send_header_if()
{
	global $acx_csma_send_header_option;
	$acx_csma_send_header_option = sanitize_text_field(acx_csma_post_isset_check('acx_csma_send_header_option'));
	update_option('acx_csma_send_header_option', $acx_csma_send_header_option);
} add_action('acx_csma_misc_hook_option_onpost','acx_csma_send_header_if');

function acx_csma_send_header_else()
{
	global $acx_csma_send_header_option;
	$acx_csma_send_header_option = get_option('acx_csma_send_header_option');
} 
add_action('acx_csma_misc_hook_option_postelse','acx_csma_send_header_else');
function acx_csma_send_header_after_else()
{
	global $acx_csma_send_header_option;

if ($acx_csma_send_header_option == "") {	$acx_csma_send_header_option = "yes"; }
} add_action('acx_csma_misc_hook_option_after_else','acx_csma_send_header_after_else');

/*	Send Header Settings  Settings HTML - Get - Set Default Logic Ends Here */
/* 	Acurax GDPR Settings HTML - Get - Set Default Logic Starts Here */
function acx_csma_misc_gdpr_html()
{
	$acx_string = __('GDPR Settings',ACX_CSMA_WP_SLUG);
	print_acx_csma_option_block_start($acx_string);	
	do_action('acx_csma_misc_gdpr_settings');
	echo "<span class='acx_csma_q_sep'></span>";
	print_acx_csma_option_block_end();
}  add_action('acx_csma_misc_hook_option_fields','acx_csma_misc_gdpr_html',700);
function acx_csma_gdpr_info_option()
{
	$acx_csma_gdpr_consent = '<b>Under Construction / Maintenance Mode From Acurax</b> can help users website to comply with GDPR, a regulation in European Union Law on Data Protection and Privacy. If you want to make <b>Under Construction / Maintenance Mode From Acurax</b> GDPR compliant, you need to include below information on your privacy policy statement.<br><br>We provide this Privacy Policy consent to inform you about our policies and procedures concerning the collection, use and disclosure of the personal information we receive and collect from users. By using Subscription form, we collects your submitted details like name email and your public IP Address, so that we can reach you back.<br><br>We are using/placing cookies at website back-end users for the proper functioning of plugin settings/options at the wp-admin of your website.';
	echo "<span class='label' style='width:100%;'>". __($acx_csma_gdpr_consent,ACX_CSMA_WP_SLUG)."</span>";
	echo "<span class='acx_csma_q_sep'></span>";
	do_action('acx_csma_misc_gdpr_consent_hook');
}
add_action('acx_csma_misc_gdpr_settings','acx_csma_gdpr_info_option',100);

function acx_csma_gdpr_info_check_option()
{
	global $acx_csma_form_text,$acx_csma_privacy_policy_title,$acx_csma_privacy_policy_desc,$acx_csma_gdpr_status;
	echo "<span class='label' style='width:100%;'><input type='checkbox' style='width:4%;' name='acx_csma_gdpr_status'  id='acx_csma_gdpr_status' value='yes' ";if($acx_csma_gdpr_status == "yes"){echo 'checked';}
	$acx_csma_consent_head = 'Yes, I would Like to Make this Plugin GDPR Compliant.';
	echo "><span class='check_label_txt'>". __($acx_csma_consent_head,ACX_CSMA_WP_SLUG)."</span></span>";?>
	<?php
	echo "<span class='acx_csma_q_sep'></span>";
	if($acx_csma_gdpr_status == "yes")
	{
		$acx_csma_display_style = "";
	}
	else{
		$acx_csma_display_style = "style='display:none;'";
	}
	echo "<div class='csma_gdpr_settings_cvr' ".$acx_csma_display_style.">";
	echo "<span class='label' style='width:50%;'>". __('What would you like to show as the cookie consent checkbox label on forms',ACX_CSMA_WP_SLUG)."</span>";
	echo "<input type='text' style='width:49%;' name='acx_csma_form_text' value='".acx_csma_option_text_after_save_hook_fn($acx_csma_form_text)."'>";
	echo "<span class='acx_csma_q_sep'></span>";
	echo "<span class='label' style='width:50%;'>". __('Privacy Policy Heading',ACX_CSMA_WP_SLUG)."</span>";
	echo "<input type='text' style='width:49%;' name='acx_csma_privacy_policy_title' value='".acx_csma_option_text_after_save_hook_fn($acx_csma_privacy_policy_title)."'>";
	echo "<span class='acx_csma_q_sep'></span>";
	echo "<span class='label' style='width:50%;'>". __('Privacy Policy Content',ACX_CSMA_WP_SLUG)."</span>";
	echo "<span class='acx_csma_q_sep'></span>";
	$acx_csma_privacy_policy_desc = acx_csma_textarea_after_save_hook_function($acx_csma_privacy_policy_desc);
	$acx_csma_settings = array( 'wpautop' => false );
	wp_editor($acx_csma_privacy_policy_desc, "acx_csma_privacy_policy_desc", $acx_csma_settings );
	echo "</div>";
	echo "<span class='acx_csma_q_sep'></span>";
	?>
	<script type="text/javascript">
	jQuery("#acx_csma_gdpr_status").change(function ()
	{
		if(this.checked) {
			if(jQuery(this).val() == "yes")
			{
				jQuery(".csma_gdpr_settings_cvr").fadeIn();
			}
		}
		else{
			jQuery(".csma_gdpr_settings_cvr").fadeOut();
		}
	});
	</script>
	<?php
}
add_action('acx_csma_misc_gdpr_settings','acx_csma_gdpr_info_check_option',500);
function acx_csma_gdpr_info_if()
{
	global $acx_csma_form_text,$acx_csma_privacy_policy_title,$acx_csma_privacy_policy_desc,$acx_csma_gdpr_status;
	$acx_csma_form_text = $_POST['acx_csma_form_text'];
	$acx_csma_form_text = acx_csma_text_before_save_hook_fn('acx_csma_form_text',$acx_csma_form_text);
	update_option('acx_csma_form_text', $acx_csma_form_text);
	
	if(ISSET($_POST['acx_csma_gdpr_status']))
	{
		$acx_csma_gdpr_status = $_POST['acx_csma_gdpr_status'];
	}
	else{
		$acx_csma_gdpr_status = "no"; 
	}
	update_option('acx_csma_gdpr_status', $acx_csma_gdpr_status);
	$acx_csma_privacy_policy_title = $_POST['acx_csma_privacy_policy_title'];
	$acx_csma_privacy_policy_title = acx_csma_text_before_save_hook_fn('acx_csma_privacy_policy_title',$acx_csma_privacy_policy_title);
	update_option('acx_csma_privacy_policy_title', $acx_csma_privacy_policy_title);
	
	$acx_csma_privacy_policy_desc = $_POST['acx_csma_privacy_policy_desc'];
	$acx_csma_privacy_policy_desc = acx_csma_textarea_before_save_hook_function('acx_csma_privacy_policy_desc',$acx_csma_privacy_policy_desc);
	update_option('acx_csma_privacy_policy_desc', $acx_csma_privacy_policy_desc);
} add_action('acx_csma_misc_hook_option_onpost','acx_csma_gdpr_info_if');

function acx_csma_gdpr_info_else()
{
	global $acx_csma_form_text,$acx_csma_privacy_policy_title,$acx_csma_privacy_policy_desc,$acx_csma_gdpr_status;

	$acx_csma_form_text = get_option('acx_csma_form_text');
	$acx_csma_gdpr_status = get_option('acx_csma_gdpr_status');
	$acx_csma_privacy_policy_title = get_option('acx_csma_privacy_policy_title');
	$acx_csma_privacy_policy_desc = get_option('acx_csma_privacy_policy_desc');
} 
add_action('acx_csma_misc_hook_option_postelse','acx_csma_gdpr_info_else');
function acx_csma_gdpr_info_after_else()
{
global $acx_csma_form_text,$acx_csma_privacy_policy_title,$acx_csma_privacy_policy_desc,$acx_csma_gdpr_status;

if ($acx_csma_gdpr_status == "") {	$acx_csma_gdpr_status = "no"; }
if ($acx_csma_privacy_policy_title == "") {	$acx_csma_privacy_policy_title = "Privacy Policy"; }
if ($acx_csma_form_text == "") {	$acx_csma_form_text = "I have read and agree with the sites privacy policy"; }
if ($acx_csma_privacy_policy_desc == "") {	$acx_csma_privacy_policy_desc = "This is to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service.<br><br>If you choose to use our Service, then you agree to the collection and use of information in relation with this policy. The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone.<br><br>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.<br><br>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us."; }


} add_action('acx_csma_misc_hook_option_after_else','acx_csma_gdpr_info_after_else');
 
 
/* 	Acurax GDPR Settings HTML - Get - Set Default Logic Ends Here */

/* Define Misc Submit Button Starts Here */
function acx_csma_misc_submit_button_html()
{
	echo "<span class='acx_csma_q_sep'></span>";?>
	<input type="submit" name="Submit" class="button button-primary" value="<?php _e("Save Settings",ACX_CSMA_WP_SLUG) ;?>" />
	<?php
echo "<span class='acx_csma_q_sep'></span>";
} 
add_action('acx_csma_misc_hook_option_fields','acx_csma_misc_submit_button_html',900);
/* Define Misc Submit Button Ends Here */
// refresh 
function acx_csma_install_licence_refresh_callback()
{
	if (!isset($_POST['acx_csma_install_licence_refresh_w_c_n'])) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href=''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	if (!wp_verify_nonce($_POST['acx_csma_install_licence_refresh_w_c_n'],'acx_csma_install_licence_refresh_w_c_n')) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href=''>".__('Click Here',ACX_CSMA_WP_SLUG)."</a>");
	
	$key = $licence = $id = "";
	$response_stat = "failed";
	if(ISSET($_POST['key']))
	{
		$key = $_POST['key'];
	}
	if(ISSET($_POST['licence']))
	{
		$licence = $_POST['licence'];
	}
	$result = acx_csma_p_check_license($licence,'',true,$id);
	if(ISSET($result["localkey"]))
	{
		$local_key = $result["localkey"];
	}
	else{
		$local_key = "";
	}
	$acx_csma_p_licence_array = get_option('acx_csma_p_licence_array');
	if(is_serialized($acx_csma_p_licence_array))
	{
		$acx_csma_p_licence_array = unserialize($acx_csma_p_licence_array);
	}
	if($acx_csma_p_licence_array == "" || !is_array($acx_csma_p_licence_array))
	{
		$acx_csma_p_licence_array = array();
	}
	$acx_csma_purchased_li_array = get_option('acx_csma_purchased_li_array');
	if(is_serialized($acx_csma_purchased_li_array))
	{
		$acx_csma_purchased_li_array = unserialize($acx_csma_purchased_li_array);
	}
	if($acx_csma_purchased_li_array == "" || !is_array($acx_csma_purchased_li_array))
	{
		$acx_csma_purchased_li_array = array();
	}
	if(ISSET($result["status"]))
	{
		if($result["status"] == 'Active')
		{
			if(ISSET($acx_csma_p_licence_array[$key]))
			{
				if($local_key != "")
				{
					$acx_csma_p_licence_array[$key]['local_key'] = $local_key;
					
					if(!is_serialized($acx_csma_p_licence_array))
					{
						$acx_csma_p_licence_array = serialize($acx_csma_p_licence_array);
					}
					update_option('acx_csma_p_licence_array',$acx_csma_p_licence_array);
					
				}
			}
			$response_stat = "success";
		} 
		else{
			$response_stat = $result["status"];
		}
		$acx_csma_purchased_li_array[$licence]['status'] = $result['status'];
		if(!is_serialized($acx_csma_purchased_li_array))
		{
			$acx_csma_purchased_li_array = serialize($acx_csma_purchased_li_array);
		}
		update_option('acx_csma_purchased_li_array',$acx_csma_purchased_li_array); 
	
	}
	echo $response_stat;
	die();
}
add_action("wp_ajax_acx_csma_install_licence_refresh","acx_csma_install_licence_refresh_callback");
function acx_csma_license_refresh_with_forcing($acx_license,$addon_key)
{
	$acx_csma_ip =  isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
	$acx_csma_domain = $_SERVER['SERVER_NAME'];
	$acx_csma_directory = dirname(__FILE__);
	$acx_csma_args = array(
		'action' 	=> 'acx-li-check-latest-version',
		'method'	=> 'addon_activation',
		'directory' => $acx_csma_directory,
		'unique_id' => $addon_key,
		'domain' 	=> $acx_csma_domain,
		'ip' 		=> $acx_csma_ip,
		'licence' 	=> $acx_license
	);
	$acx_csma_unique_id = "";
	$response_stat = "failed";
	$retry = true;
	$acx_csma_p_licence_array = get_option('acx_csma_p_licence_array');
	if(is_serialized($acx_csma_p_licence_array))
	{
		$acx_csma_p_licence_array = unserialize($acx_csma_p_licence_array);
	}
	if($acx_csma_p_licence_array == "" || !is_array($acx_csma_p_licence_array))
	{
		$acx_csma_p_licence_array = array();
	}
	$acx_csmap_retry_array = get_option('acx_csmap_retry_array');
	if(is_serialized($acx_csmap_retry_array))
	{
		$acx_csmap_retry_array = unserialize($acx_csmap_retry_array);
	}
	if($acx_csmap_retry_array == "")
	{
		$acx_csmap_retry_array = array();
	}
	if(!is_array($acx_csmap_retry_array))
	{
		$acx_csmap_retry_array = array();
	}
	if(ISSET($acx_csmap_retry_array[$acx_license]['activation_licence_check']))
	{
		if($acx_csmap_retry_array[$acx_license]['activation_licence_check'] >= 3)
		{
			$retry = false;	
		}
	}
	// Send request checking for an update
	if($retry == true)
	{
		$response = acx_csma_licence_activation_api_request( $acx_csma_args );
		$response = json_decode($response, true);
	}
	if(!ISSET($response['response_status']) && !ISSET($response['status']))
	{
		if(ISSET($acx_csmap_retry_array[$acx_license]['activation_licence_check']))
		{
			$acx_csmap_retry_array[$acx_license]['activation_licence_check'] = $acx_csmap_retry_array[$acx_license]['activation_licence_check'] + 1;
		}
		else{
			$acx_csmap_retry_array[$acx_license]['activation_licence_check'] =  1;
		}
	}
	else
	{
		if($response['response_status'] == "success" &&  $response['status'] == "Active")
		{
			$acx_csma_purchased_li_array = get_option('acx_csma_purchased_li_array');
			if(is_serialized($acx_csma_purchased_li_array))
			{
				$acx_csma_purchased_li_array = unserialize($acx_csma_purchased_li_array);
			}
			if($acx_csma_purchased_li_array == "" || !is_array($acx_csma_purchased_li_array))
			{
				$acx_csma_purchased_li_array = array();
			}
			$acx_csma_unique_id = trim($response['unique_id']);
			$acx_csma_purchased_li_array[$acx_license] = array(
			'slug' => $response['slug'],
			'status' => $response['status'],
			'download_dynamic_url' => $response['download_dynamic_url']
			); 
			// update licence array
			
			$acx_csma_p_licence_array[$acx_csma_unique_id]['addon_name'] = $response['name'];
			$acx_csma_p_licence_array[$acx_csma_unique_id]['licence_code'] = $acx_license;
			if($response['localkey'] != "")
			{
				$acx_csma_p_licence_array[$acx_csma_unique_id]['local_key'] = $response['localkey'];
			}
			if(!is_serialized($acx_csma_p_licence_array))
			{
				$acx_csma_p_licence_array = serialize($acx_csma_p_licence_array);
			}
			update_option('acx_csma_p_licence_array',$acx_csma_p_licence_array); 
			if(!is_serialized($acx_csma_purchased_li_array))
			{
				$acx_csma_purchased_li_array = serialize($acx_csma_purchased_li_array);
			}
			update_option('acx_csma_purchased_li_array',$acx_csma_purchased_li_array); 
			$acx_csmap_retry_array[$acx_license]['activation_licence_check'] =  0;
			if(!is_serialized($acx_csmap_retry_array))
			{
				$acx_csmap_retry_array = serialize($acx_csmap_retry_array);
			}
			update_option('acx_csmap_retry_array',$acx_csmap_retry_array);
			$response_stat = $response['response_status'];
		}
	}

	return $response_stat;
}
function acx_csma_load_plugin_textdomain() {
    load_plugin_textdomain( ACX_CSMA_WP_SLUG, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'acx_csma_load_plugin_textdomain' );
?>