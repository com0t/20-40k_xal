<style>.hndle {display: none !important}</style>
<?php
  $post_id = get_the_ID();
  $cf_page_type = get_post_meta( @$_GET['post'], "cf_page_type", true );
  $cf_funnel_id = get_post_meta( @$_GET['post'], "cf_funnel_id", true );
  $cf_funnel_name = get_post_meta( @$_GET['post'], "cf_funnel_name", true );
  $cf_step_id = get_post_meta( @$_GET['post'], "cf_step_id", true );
  $cf_step_name = get_post_meta( @$_GET['post'], "cf_step_name", true );
  $cf_step_url = get_post_meta( @$_GET['post'], "cf_step_url", true );
  $cf_slug = get_post_meta( @$_GET['post'], 'cf_slug', true );
  $cf_authorization_email = get_option( 'clickfunnels_api_email' );
  $cf_authorization_token = get_option( 'clickfunnels_api_auth' );
  $cf_homepage = get_option( "clickfunnels_homepage_post_id" );
  $cf_404 = get_option( "clickfunnels_404_post_id" );
?>

<script type="text/javascript">
  function string_to_slug(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaeeeeiiiioooouuuunc------";
    for (var i=0, l=from.length ; i<l ; i++) {
      str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
      .replace(/\s+/g, '-') // collapse whitespace and replace by -
      .replace(/-+/g, '-'); // collapse dashes

    return str;
  }

  function get_funnel_url(id) {
    var js_api_url = '<?php echo CF_API_URL ?>';
    var js_api_email = '<?php echo $cf_authorization_email ?>';
    var js_api_token = '<?php echo $cf_authorization_token ?>';
    var the_resource;

    if (id) {
      the_resource = 'funnels/' + id;
    } else {
      the_resource = 'funnels/list';
    }

    return js_api_url + the_resource + '.json?email=' + js_api_email + '&auth_token=' + js_api_token;
  }

  jQuery(document).ready(function(){
    var $ = jQuery;
    $('.draft').hide();
    console.log("%cClickFunnels WordPress Plugin", "background: #0166AE; color: white;");
    console.log("%cEditing anything inside the console is for developers only. Do not paste in any code given to you by anyone. Use with caution. Visit for support: https://support.clickfunnels.com/", "color: #888;");

    var selected_funnel = '<?php echo $cf_funnel_id ?>';
    var selected_step = '<?php echo $cf_step_id ?>';

    $('#cf_page_type').change(function() {
      if ($(this).val() == 'homepage') {
        $('.cf_url').hide();
        $('#publish').removeClass('disabledLink');
      } else if ($(this).val() == '404') {
        $('.cf_url').hide();
        $('#publish').removeClass('disabledLink');
      } else {
        $('.cf_url').show();
        $('#cf_slug').change();
      }
    }).change();

    $('#loading-funnels').fadeIn();
    $.getJSON(get_funnel_url(), function(data) {
      $.each(data, function() {
        // TODO: something with optgroup to group by tag
        $('#cf_funnel_id').append('<option value="' + this.id + '">' + this.name + '</option>');
      });
      if (selected_funnel) {
        $("#cf_funnel_id option[value='"+ selected_funnel +"']").prop('selected', true);
      }
      $('#cf_funnel_id').change();
    }).fail(function() {
      $('.badAPI').show();
    }).always(function() {
      $('#loading-funnels').fadeOut();
    });

    $('#cf_funnel_id').change(function() {
      $('#loading-steps').fadeIn();
      var funnel_name = $(this.selectedOptions[0]).text();
      $('.apiSubHeader h2').text(funnel_name)
      $('#cf_funnel_name').val(funnel_name);

      selected_funnel = this.value;
      $.getJSON(get_funnel_url(selected_funnel), function(data) {
        $('#cf_step_id').html(''); // Clean out other options

        $.each(data.funnel_steps, function() {
          $('#cf_step_id').append('<option data-url=' + this.published_url + ' value="' + this.id + '">' + this.name + '</option>');
        });

        if (data.funnel_steps.length == 0) {
          $('#cf_step_id').fadeOut();
          $('#noPageWarning').fadeIn();
        } else {
          $('#cf_step_id').fadeIn();
          $('#noPageWarning').fadeOut();
        }

        if (selected_step) {
          $("#cf_step_id option[value='"+ selected_step +"']").prop('selected', true);
        }

        $('#cf_step_id').trigger('change');
      }).fail(function() {
        $('.badAPI').show();
      }).always(function() {
        $('#loading-steps').fadeOut();
      });
    });

    $('#cf_step_id').change(function() {
      var published_url = $(this.selectedOptions[0]).data('url');
      $('#cf_step_url').val(published_url);

      var page_name = $(this.selectedOptions[0]).text();
      $('#cf_step_name').val(page_name);
    });

    $('#cf_slug').bind('keyup keypress blur change', function() {
      var myStr = $(this).val().toLowerCase().replace(/\s/g , "-");
      $('#cf_slug').val(myStr);
      slug = $(this).val();
      customSlug = slug;
      customSlug = string_to_slug(customSlug);
      $(this).val(customSlug);

      $('.customSlugText').text(customSlug);
      newurl = $('#cfslugurl').text();
      $('#cfslugurl').attr('href', newurl);
      $('#customurlError').hide();
      $('#customurlError_duplicate').hide();
      $('#publish').removeClass('disabledLink');

      $('.used_slug').each(function () {
        if ($(this).html() == customSlug) {
         $('#customurlError_duplicate').fadeIn();
         $('#publish').addClass('disabledLink');
        }
      });

      if ('' == customSlug) {
       $('#customurlError').fadeIn();
       $('#publish').addClass('disabledLink');
      }
    });

    $('.cftablink').click(function() {
      if ($(this).hasClass('disabledLink') === false) {
        $('.cftabs').hide();
        $('.cftablink').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).attr('data-tab');
        $('#'+tab).show();
      }
    });
  });
</script>

<link href="<?php echo plugins_url( '../css/font-awesome.css', __FILE__ ); ?>" rel="stylesheet">
<link href="<?php echo plugins_url( '../css/admin.css', __FILE__ ); ?>" rel="stylesheet">

<div id="no-funnels-error" class="badAPI error notice" style="display: none; width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i>There are no Funnels in your ClickFunnels account! Head over to <a href="https://app.clickfunnels.com/" target="_blank">ClickFunnels</a> to get started!</div>

<div id="failed-connection-error" class="badAPI error notice" style="display: none; width: 733px;padding: 10px 12px;font-weight: bold"><i class="fa fa-times" style="margin-right: 5px;"></i> Failed API Connection with ClickFunnels. Check <a href="edit.php?post_type=clickfunnels&page=cf_api&error=compatibility">Settings > Compatibility Check</a> for details.</div>

<!-- Header -->
<?php include('_header.php'); ?>

<div class="apiSubHeader">
  <h2>Clickfunnels</h2>
  <?php if ($cf_step_id) {  ?>
    <a style="margin-right: 0;margin-top: -27px;" href="<?php echo CF_API_URL ?>funnels/<?php echo $cf_funnel_id; ?>/steps/<?php echo $cf_step_id; ?>" target="_blank" class="editThisPage"><i class="fa fa-edit"></i>EDIT IN CLICKFUNNELS</a>
    <a style="margin-right: 10px;margin-top: -27px;" href="<?php echo get_home_url() ; ?>/<?php echo $cf_slug; ?>" title="View Page" target="_blank" class="editThisPage"><i class="fa fa-search"></i> PREVIEW</a>
    <?php if ( $cf_page_type=='page' ) { ?><?php }?>
    <?php if ( $cf_page_type=='homepage' ) {?>
       <span style="margin-right: 10px;margin-top: -27px;" class="editThisPage2"><i class="fa fa-home"></i> Home Page</span>
    <?php }?>
    <?php if ( $cf_page_type=='404' ) {?>
        <span style="margin-right: 10px;margin-top: -27px;" class="editThisPage2"><i class="fa fa-exclamation-triangle"></i> 404 Page</span>
    <?php }?>
  <?php }?>
</div>

<?php if ( $cf_authorization_email == "" || $cf_authorization_token == "" ) { ?>
  <div class="noAPI">
      <h4>You haven't setup your API settings. <a href="<?php echo get_admin_url() ?>edit.php?post_type=clickfunnels&page=cf_api">Click here to setup now.</a></h4>
  </div>
<?php } else { ?>

<form method="post">
    <div class="bootstrap-wp"><?php wp_nonce_field( "save_clickfunnel", "clickfunnel_nonce" ); ?>
        <div id="app_sidebar">
            <a href="#" data-tab="tab1" class="cftablink selectapagelink active">Page Settings</a>
        </div>
        <div id="app_main" class="col-sm-7 row-fluid form-horizontal">
            <div id="tab1" class="cftabs">
                <!-- Select a Page / Funnel -->
                <h2>Page Settings</h2>
                <div class="innerTab">
                    <div class="control-group ">
                        <label class="control-label" for="cf_page_type"> Choose Page Type</label>
                        <select name="cf_page_type"  id="cf_page_type" class="cf_header" style="width: 100% !important">
                            <option value="page" <?php if($cf_page_type == 'page'){ echo 'selected'; } ?>>Regular Page</option>
                            <option value="homepage"
                              <?php  if($cf_homepage == $post_id) {
                                echo 'selected';
                              } ?>
                            >Home Page</option>
                            <option value="404"
                              <?php  if($cf_404 == $post_id) {
                                echo 'selected';
                              } ?>
                            >404 Page</option>
                        </select>
                    </div>
                </div>

                <div class="control-group cf_uses_api clearfix" style="">
                    <label class="control-label" for="cf_funnel_id">
                      Choose Funnel  <span id="loading-funnels"><i class="fa fa-spinner"></i> <em style="margin-left: 5px;font-size: 11px;">Loading Funnels...</em></span>
                    </label>
                    <div class="controls">
                        <select class="input-xlarge" id="cf_funnel_id" name="cf_funnel_id">
                        </select>
                    </div>
                </div>

                <div class="control-group choosePageBox clearfix">
                    <label class="control-label" for="cf_step_id">
                        Choose Step  <span id="loading-steps"><i class="fa fa-spinner"></i> <em style="margin-left: 5px;font-size: 11px;">Loading Pages...</em></span>
                    </label>
                    <div class="controls">
                        <select class="input-xlarge" id="cf_step_id" name="cf_step_id" style="float: left;">
                        </select>
                    </div>
                    <div id="noPageWarning" style="font-size: 11px; margin-left: 28px; margin-top: -13px;float: left;padding-top: 14px;display: none;width: 100%; clear: both">
                        <strong style="font-size: 13px;display: block;">No compatible pages found. </strong>
                        <em style="display: block">Membership pages and order pages are not available through plugin.</em>
                    </div>
                    <br clear="all">
                </div>

                <div class="control-group" style="display: block">
                    <label class="control-label" for="cf_step_url"> ClickFunnels URL <small>(reference only)</small></label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="cf_step_url" id="cf_step_url" readonly="readonly" style="height: 30px;" value="<?php echo $cf_step_url; ?>" />
                    </div>
                </div>

                <div class="cf_url control-group clearfix">
                    <label class="control-label" for="cf_slug">Custom Slug</label>
                    <div id="cf-wp-path" class="controls ">
                       <input  style="padding:10px;"type="text" value="<?php if ( isset( $cf_slug ) ) echo $cf_slug;?>" placeholder="your-path-here" name="cf_slug" id="cf_slug" class="input-xlarge">
                       <div id="customurlError" style="display: none;> color: #E54F3F; font-weight: bold;margin-top: 4px;">
                           Add a path before saving.
                       </div>
                       <div id="customurlError_duplicate" style="display: none;> color: #E54F3F; font-weight: bold;margin-top: 4px;">
                           Slug already taken
                       </div>
                    </div>
                    <p class="infoHelp">
                      <span style="font-weight: bold;text-decoration: none; padding-bottom: 3px;"> <?php echo get_home_url() ; ?>/<span class="customSlugText"><?php echo $cf_slug; ?></span></span>
                    </p>
                </div>
            </div>

            <div style="display: none">
              <input type="hidden" name="cf_funnel_name" id="cf_funnel_name" value="<?php echo $cf_funnel_name; ?>"  />
              <input type="hidden" name="cf_step_name" id="cf_step_name" value="<?php echo $cf_step_name; ?>"  />
            </div>

            <div id="savePage">
                <div style="width: 100%">
                    <input type="submit" name="publish" id="publish" value="Save Page" class="action-button shadow animate green" style="float: right; ">
                    <div id="saving" style="float: right;display: none; padding-right: 10px;opacity: .6;padding-top: 9px;margin-right: 4px;font-size: 15px;">
                         <i class="fa fa-spinner fa-spin"></i>
                         <span>Saving...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } ?>
<?php include('_footer.php'); ?>
