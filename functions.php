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

function edin_improved_post_thumbnail() {
  if ( post_password_required() || is_attachment() || ! has_post_thumbnail() || has_post_format() )
    {
      return;
    }
  ?>
  
  <a class="post-thumbnail" href="<?php the_permalink(); ?>">
     <?php
     if ( is_page_template( 'page-templates/front-page.php' ) || is_page_template( 'page-templates/grid-page.php' ) ) {
       $ratio = get_theme_mod( 'edin_thumbnail_style' );
       switch ( $ratio ) {
       case 'square':
	 the_post_thumbnail( 'edin-thumbnail-square' );
	 break;
       default :
	 the_post_thumbnail( 'edin-thumbnail-landscape' );
       }
     } else {
       the_post_thumbnail( 'edin-featured-image' );
     }
  ?>
  </a>

<?php
}
