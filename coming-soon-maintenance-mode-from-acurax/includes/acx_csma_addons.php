<?php
$csma_addons_intro = array();
$csma_addons_intro[] = array(
							'name' => __('URL Access Addon',ACX_CSMA_WP_SLUG),
							'img' => plugins_url('/images/addon_private_url.jpg',dirname(__FILE__)),
							'desc' => __('Allows you to generate private urls which you can provide to your clients or contacts, So they can view your website even if under maintenance is active. You can define the URL expiry while generating the URL',ACX_CSMA_WP_SLUG),
							'url' => 'http://www.acurax.com/products/under-construction-maintenance-mode-wordpress-plugin/?feature=url-access&utm_source=csma&utm_campaign=addon-page',
							'button' => __('View Details',ACX_CSMA_WP_SLUG)
							);
$csma_addons_intro[] = array(
							'name' => __('Service Theme Pack 1',ACX_CSMA_WP_SLUG),
							'img' => plugins_url('/images/addon_stp_1.gif',dirname(__FILE__)),
							'desc' => __('Its a theme pack, consisting of 4 entirely differant premium under maintenance themes which you can use when your website is under construction mode. It acts like your website, You can list your services, add contact form etc...',ACX_CSMA_WP_SLUG),
							'url' => 'http://www.acurax.com/products/under-construction-maintenance-mode-wordpress-plugin/?feature=service-theme-pack-1&utm_source=csma&utm_campaign=addon-page',
							'button' => __('View Demo and More',ACX_CSMA_WP_SLUG)
							);
$csma_addons_intro[] = array(
							'name' => __('Page Policy Manager',ACX_CSMA_WP_SLUG),
							'img' => plugins_url('/images/addon_ppm.png',dirname(__FILE__)),
							'desc' => __('Page Policy Manager is a simple and easy addon which helps you to Include/Exclude Pages/Posts from showing Under Construction/Maintenance Pages. Choose a list of Pages/Posts & set the condition as Show/Not to Show Maintenance Mode for the Selected Pages',ACX_CSMA_WP_SLUG),
							'url' => 'http://www.acurax.com/products/under-construction-maintenance-mode-wordpress-plugin/?feature=page-policy-manager&utm_source=csma&utm_campaign=addon-page',
							'button' => __('Screenshots and More',ACX_CSMA_WP_SLUG)
							);
?>
<h2><?php _e("Coming Soon/Maintenance - Available Addons",ACX_CSMA_WP_SLUG); ?></h2>
<div id="csma_addons_intro_holder">
<?php
foreach($csma_addons_intro as $key => $value)
{
?>
<div class="csma_addons_intro" onclick="window.open('<?php echo $value['url']; ?>'); return false;">
<img src="<?php echo $value['img']; ?>">
<h3><?php echo $value['name']; ?></h3>
<p>
<?php echo $value['desc']; ?>
</p>
<a class="csma_addon_button" href="<?php echo $value['url']; ?>" target="_blank"><?php echo $value['button']; ?></a>
</div> <!-- csma_addons_intro -->
<?php } ?>
</div> <!-- csma_addons_intro_holder -->
<?php 
do_action("acx_csma_purachase_licence_form_hook");
?>