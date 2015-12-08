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
   Improve excerpt displayed on grid page. Permit certain HTML to allow basic formatting.
   Taken from http://www.jeffmould.com/2014/01/17/enabling-formatting-wordpress-excerpt/
*/

function custom_wp_trim_excerpt($text) {
  $raw_excerpt = $text;
  if ( '' == $text ) {
    //Retrieve the post content. 
    $text = get_the_content('');
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]&gt;', ']]&gt;', $text);
    
    // the code below sets the excerpt length to 55 words. You can adjust this number for your own blog.
    $excerpt_length = apply_filters('excerpt_length', 55);
    
    // the code below sets what appears at the end of the excerpt, in this case ...
    $excerpt_more = apply_filters('excerpt_more', ' ' . '...');
    
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
      array_pop($words);
      $text = implode(' ', $words);
      $text = force_balance_tags( $text );
      $text = $text . $excerpt_more;
    } else {
      $text = implode(' ', $words);
    }
    
  }
  return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');


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
