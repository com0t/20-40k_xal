<?php
function acx_csma_service_banners()
{
?>
<div id="acx_csma_sidebar">
<?php $acx_csma_service_banners = get_option('acx_csma_service_banners');
if ($acx_csma_service_banners != "no") { ?>
<div id="acx_ad_banners_csma">
<a href="http://www.acurax.com/?utm_source=csma&utm_campaign=sidebar_banner_1" target="_blank" class="acx_ad_csma_1">
<div class="acx_ad_csma_title"><?php _e('Need Help on Wordpress?','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_title -->
<div class="acx_ad_csma_desc"><?php _e('Instant Solutions for your wordpress Issues','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_desc -->
</a> <!--  acx_ad_csma_1 -->

<a href="http://www.acurax.com/branding/?utm_source=csma&utm_campaign=sidebar_banner_2" target="_blank" class="acx_ad_csma_1">
<div class="acx_ad_csma_title"><?php _e('Unique Design For Better Branding','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_title -->
<div class="acx_ad_csma_desc acx_ad_csma_desc2" style="padding-top: 0px; padding-left: 50px; height: 41px; font-size: 13px; text-align: center;"><?php _e('Get Responsive Custom Designed Website For High Conversion','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_desc -->
</a> <!--  acx_ad_csma_1 -->

<a href="http://www.acurax.com/social-profile-design/?utm_source=csma&utm_campaign=sidebar_banner_3" target="_blank" class="acx_ad_csma_1">
<div class="acx_ad_csma_title"><?php _e('Brand Your Social Media Profiles','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_title -->
<div class="acx_ad_csma_desc acx_ad_csma_desc3" style="padding-top: 0px; height: 110px; text-align: left; font-size: 12px; line-height: 20px;"><?php _e('Social Profile Design means customizing and designing your online presence across many social networks in a professional way to maximize your customer engagement.<br><br>Order and Get Social Media Elements in 24 Hours','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_desc -->
</a> <!--  acx_ad_csma_1 -->

</div> <!--  acx_ad_banners_csma -->
<?php } else { ?>
<div class="acx_csma_sidebar_widget">
<div class="acx_csma_sidebar_w_title"><?php _e('Affordable Website Services','floating-social-media-icon');?></div> <!-- acx_ad_csma_title -->
<div class="acx_csma_sidebar_w_content">
<?php _e('We know you are in the process of improving your website, and we the team at Acurax is always available for hire. ','floating-social-media-icon'); ?><a href="http://www.acurax.com/webdesigning/?utm_source=csma&utm_campaign=sidebar_text_1" target="_blank"><?php _e('Get in touch','floating-social-media-icon') ;?></a>
</div>
</div> <!-- acx_csma_sidebar_widget -->
<div class="acx_csma_sidebar_widget">
<div class="acx_csma_sidebar_w_title"><?php _e('Brand Your Social Media Profiles','floating-social-media-icon');?></div>
<div class="acx_csma_sidebar_w_content"><?php _e('Social Profile Design means customizing and designing your online presence across many social networks in a professional way to maximize your customer engagement.','floating-social-media-icon');?> <br><br><a href="http://www.acurax.com/social-profile-design/?utm_source=csma&utm_campaign=sidebar_text_2" target="_blank"><?php _e('Order and Get Social Media Elements in 24 Hours','floating-social-media-icon'); ?></a></div>
</div> <!-- acx_csma_sidebar_widget -->
<div class="acx_csma_sidebar_widget">
<div class="acx_csma_sidebar_w_title"><?php _e('Partner With Us','floating-social-media-icon'); ?></div> <!-- acx_ad_csma_title -->
<div class="acx_csma_sidebar_w_content acx_csma_sidebar_w_content_p_slide">
</div>
</div> <!-- acx_csma_sidebar_widget -->
<script type="text/javascript">
var acx_csma = new Array("<?php _e('<b>Are you an Agency?</b>, You can Outsource your projects to the team at Acurax...<br><br><a href=\'http://www.acurax.com/partner-with-us/?utm_source=csma&utm_campaign=sidebar_text_partner\' target=\'_blank\'>Know More...</a>','floating-social-media-icon'); ?>","<?php _e('<ul><li>- Expert team with timely delivery</li><li>- Reducing the project cost</li><li>- Single Point of contact</li><li>- 100% White-label + Non disclosed agreement</li></ul><a href=\'http://www.acurax.com/partner-with-us/?utm_source=csma&utm_campaign=sidebar_text_partner\' target=\'_blank\'>Know More...</a>','floating-social-media-icon'); ?>","<?php _e('<ul><li>- Ability to handle multiple projects at a time</li><li>- Well documented project management on project management system</li><li>- No Communication Barriers. Email/support ticket/IM via Skype, Yahoo, Hangouts and Phone etc...</li></ul><a href=\'http://www.acurax.com/partner-with-us/?utm_source=csma&utm_campaign=sidebar_text_partner\' target=\'_blank\'>Know More...</a>','floating-social-media-icon'); ?>");
var current_loaded = 0;
function acx_csma_t_rotate()
{
	acx_csma_count = (acx_csma.length-1);
	acx_csma_text = acx_csma[current_loaded];
	jQuery(".acx_csma_sidebar_w_content_p_slide").fadeOut('fast');
	jQuery(".acx_csma_sidebar_w_content_p_slide").html(acx_csma_text);
	jQuery(".acx_csma_sidebar_w_content_p_slide").fadeIn('slow');
	current_loaded = current_loaded+1;
	if(current_loaded > acx_csma_count)
	{
		current_loaded = 0;
	}
}
jQuery(document).ready(function() {
	acx_csma_t_rotate();
	setInterval(function(){ acx_csma_t_rotate(); }, 4000);
});
</script>
<?php } ?>
<div class="acx_csma_sidebar_widget">
<div class="acx_csma_sidebar_w_title"><?php _e('Rate us on wordpress.org','floating-social-media-icon'); ?></div>
<div class="acx_csma_sidebar_w_content" style="text-align:center;font-size:13px;"><b><?php _e('Thank you for being with us... If you like our plugin then please show us some love','floating-social-media-icon');?> </b></br>
<a href="https://wordpress.org/support/view/plugin-reviews/<?php echo ACX_CSMA_WP_SLUG; ?>/" target="_blank" style="text-decoration:none;">
<span id="acx_csma_stars">
<span class="dashicons dashicons-star-filled"></span>
<span class="dashicons dashicons-star-filled"></span>
<span class="dashicons dashicons-star-filled"></span>
<span class="dashicons dashicons-star-filled"></span>
<span class="dashicons dashicons-star-filled"></span>
</span>
<span class="acx_csma_star_button button button-primary"><?php _e('Click Here','floating-social-media-icon'); ?></span>
</a>
<p><?php _e('If you are facing any issues, kindly post them at plugins support forum','floating-social-media-icon');?> <a href="http://wordpress.org/support/plugin/<?php echo ACX_CSMA_WP_SLUG; ?>/" target="_blank"><?php _e('here','floating-social-media-icon'); ?></a>
</div>
</div> <!-- acx_csma_sidebar_widget -->
</div> <!--  acx_csma_sidebar -->
<?php	
}
add_action('acx_csma_hook_sidebar_widget','acx_csma_service_banners',100);
add_action('acx_csma_misc_hook_option_sidebar','acx_csma_service_banners',100);
/********************************************************** MISC PAGE ************************************************************/
function acx_csma_misc_nonce_check()
{
	if (!isset($_POST['acx_csma_misc_nonce'])) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href=''>Click Here</a>");
	if (!wp_verify_nonce($_POST['acx_csma_misc_nonce'],'acx_csma_misc_nonce')) die("<br><br>".__('Unknown Error Occurred, Try Again... ',ACX_CSMA_WP_SLUG)."<a href=''>Click Here</a>");
	if(!current_user_can('manage_options')) die("<br><br>".__('Sorry, You have no permission to do this action...',ACX_CSMA_WP_SLUG)."</a>");
} add_action('acx_csma_misc_hook_option_onpost','acx_csma_misc_nonce_check',1);
function acx_csma_misc_nonce_field()
{
	echo "<input name='acx_csma_misc_nonce' type='hidden' value='".wp_create_nonce('acx_csma_misc_nonce')."' />";
	echo "<input name='acx_csma_misc_hidden' type='hidden' value='Y' />";
} add_action('acx_csma_misc_hook_option_fields','acx_csma_misc_nonce_field',10);
function acx_csma_misc_option_form_start()
{
	echo "<form name='acurax_csma_misc_form' id='acurax_csma_misc_form'  method='post' action='".esc_url(str_replace( '%7E', '~',$_SERVER['REQUEST_URI']))."'>";
} add_action('acx_csma_misc_hook_option_form_head','acx_csma_misc_option_form_start',100);
function acx_csma_misc_option_form_end()
{
	echo "</form>";
}  add_action('acx_csma_misc_hook_option_form_footer','acx_csma_misc_option_form_end',100);
function acx_csma_misc_option_div_start()
{
	echo "<div id=\"acx_csma_option_page_holder\"> \n";
	acx_csma_hook_function('acx_csma_misc_hook_option_above_page_left');
	echo "<div class=\"acx_csma_option_page_left\"> \n";
} add_action('acx_csma_misc_hook_option_form_head','acx_csma_misc_option_div_start',30);
function acx_csma_misc_option_sidebar_start()
{
	echo "</div> <!-- acx_csma_option_page_left --> \n";
	echo "<div class=\"acx_csma_option_page_right\"> \n";
}  add_action('acx_csma_misc_hook_option_sidebar','acx_csma_misc_option_sidebar_start',10);
function acx_csma_misc_option_sidebar_end()
{
	echo "</div> <!-- acx_csma_option_page_right --> \n";
	acx_csma_hook_function('acx_csma_misc_hook_option_footer');
	echo "</div> <!-- acx_csma_option_page_holder --> \n";
} add_action('acx_csma_misc_hook_option_sidebar','acx_csma_misc_option_sidebar_end',500);
function acx_csma_misc_print_option_page_title()
{
		$acx_string = __("Misc Settings",ACX_CSMA_WP_SLUG);
 echo print_acx_csma_option_heading($acx_string);
} add_action('acx_csma_misc_hook_option_form_head','acx_csma_misc_print_option_page_title',50);
function display_acx_csma_misc_saved_success()
{ ?>
<div class="updated"><p><strong><?php _e('Misc Settings Saved!.',ACX_CSMA_WP_SLUG ); ?></strong></p></div>
<script type="text/javascript">
		 setTimeout(function(){
				jQuery('.updated').fadeOut('slow');
				
				}, 4000);

		</script>
<?php
}   add_action('acx_csma_misc_hook_option_onpost','display_acx_csma_misc_saved_success',5000);
/********************************************************** MISC PAGE ************************************************************/
?>