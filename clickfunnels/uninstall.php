<?php
  // Don't delete posts, this was causing issues where people wanted to be able to
  // Uninstall and reinstall. Reset data is available for a true uninstall

  // $args = array(
  //   'posts_per_page' => -1,
  //   'post_type' =>'clickfunnels'
  // );
  // $posts = get_posts( $args );
  // $total = count($posts);
  // $current = 0;
  // if (is_array($posts) && $total) {
  //    foreach ($posts as $post) {
  //       wp_delete_post( $post->ID, true);
  //    }
  // }
  delete_option('clickfunnels_api_email');
  delete_option('clickfunnels_api_auth');
  delete_option('clickfunnels_display_method');
  delete_option('clickfunnels_favicon_method');
  delete_option('clickfunnels_additional_snippet');
?>
