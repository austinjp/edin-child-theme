<?php

function theme_enqueue_styles() {

  $parent_style = 'parent-style';

  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style',
                    get_stylesheet_directory_uri() . '/style.css',
                    array( $parent_style )
                    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

/**
   Improve post thumbnails e.g. for use on grid page, by hiding them with CSS
   to reveal an identical background image. This allows realigning of the background
   image easily with CSS property background-position. Requires user to set the
   custom field "image_focus" in each page, as required.
*/

function edin_improved_post_thumbnail() {

  $postid = get_the_ID();

  if ( post_password_required() || is_attachment() || ! has_post_thumbnail() || has_post_format() )
    {
      return;
    }
  $ratio = get_theme_mod( 'edin_thumbnail_style' );
  switch ( $ratio ) {
  case 'square':
    ?><a class="post-thumbnail edin-improved-post-thumbnail edin-improved-post-thumbnail-square" <?php
    ?>href="<?php the_permalink(); ?>" <?php
    break;
  default:
    ?><a class="post-thumbnail edin-improved-post-thumbnail edin-improved-post-thumbnail-landscape" <?php
    ?>href="<?php the_permalink(); ?>" <?php
  }
  ?>style="background-image: url('<?php
$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ), 'edin-thumbnail-landscape' );
echo esc_url( $thumbnail[0] );
?>');
  background-repeat: no-repeat;
  background-size: cover;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-position: <?php echo get_post_meta($postid, 'image_focus', 1); ?> ;">
  <img class="edin-improved-post-thumbnail" src="<?php echo esc_url( $thumbnail[0] ); ?>">
  <?php
  ?></a><?php
     
     /*
       FIXME Should detect if image_focus is not sent.
       However, CSS errors fail gracefully.
     */

     /*
       Instead of using the thumbnail image on top of the background image,
       could use a transparent PNG instead:
       <img class="edin-improved-post-thumbnail" src="<?php echo '' . get_stylesheet_directory_uri() . '/transparent.png' ?>">
     */
}
