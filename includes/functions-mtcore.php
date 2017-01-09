<?php

/**
 * Custom template tags for this plugin used by theme.
 * List of static function Helper.
 *
 * functions used across both the public-facing side of the site and the admin area.
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes
 */

 /**
  * The code that runs during plugin activation.
  */
 function activate_mtcore() {
	 require MT_INC.'class-mtcore-install.php';
	 new MT_Core_Install( 'activate' );
 }

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_mtcore() {
	require MT_INC.'class-mtcore-install.php';
	new MT_Core_Install( 'deactivate' );
}

/**
 * Cleanup shortcode javascript generated when switching theme.
 *
 * @since 	1.0.0
 */
function mtcore_remove_theme_cache() {
	deactivate_mtcore();
}
add_action( 'switch_theme', 'mtcore_remove_theme_cache' );

 /**
  * Convert string file name to class name.
  *
  * @since 	1.0.0
  * @return 	string
  */
 function file_to_classname( $file, $prefix = '', $suffix = '' ) {

 	$class_name	= basename( $file, ".php" );
 	$class_name = explode('-', $class_name);
 	$class_name = array_map('ucfirst', $class_name);

	foreach ($class_name as $key => $name) {
		if ( $name == 'Class' ) {
			unset( $class_name[ $key ] );
		} elseif ( $name == 'Mtcore' ) {
			$class_name[ $key ] = 'MT_Core';
		}
	}
 	$class_name = implode('_', $class_name);

 	if ( ! empty( $prefix ) ) {
 		$class_name = sanitize_title($prefix).'_'.$class_name;
 	}

 	if ( ! empty( $suffix ) ) {
 		$class_name = $class_name.'_'.sanitize_title($suffix);
 	}

 	return $class_name;
}

/**
 * Add admin notice
 *
 * @since 	1.0.0
 * @return 	string
 */
function add_admin_notice( $status, $cause, $args ) {
	include_once MT_INC . 'class-mtcore-notice.php';
	new MT_Core_Notice( $status, $cause, $args );
}

/**
 * Escaped HTML Color HEX Value
 *
 * @since		1.0.0
 *
 * @return string color or empty
 */
function esc_color_hex( $color ) {
	// If user accidentally passed along the # sign, strip it off
	$color = strtolower( ltrim($color, '#') );

	if ( ctype_xdigit($color) && (strlen($color) == 6 || strlen($color) == 3) ) {
		return '#'.$color;
	} else {
		return '';
	}
}

/**
 * Getting all values for a custom field key
 *
 * @since		1.0.0
 *
 * @return mixed    list of meta key value from all posts
 */
function get_meta_values( $key = '', $type = 'post', $status = 'any', $unique = true ) {

  if( empty( $key ) )
      return;

  $cache_key = 'MT_'.md5( serialize( array( $key, $type, $status ) ) );

  if ( false === ( $r = get_transient( $cache_key ) ) ) {

  	global $wpdb,$wp_post_statuses;

		$std_status = array_keys( $wp_post_statuses );

		$stat = is_array( $status ) ? array_intersect( $status, $std_status ) : ( in_array( $status, $std_status ) ? $status : $std_status );
		$stat = is_array( $stat ) ? implode( "', '", $stat ) : $stat;
		$type = is_array( $type ) ? implode( "', '", $type ) : $type;
		$unique = $unique ? ' DISTINCT' : '';

  	$r = $wpdb->get_col( $wpdb->prepare("
  		SELECT{$unique} pm.meta_value FROM {$wpdb->postmeta} pm
  		LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
  		WHERE pm.meta_key = '%s'
  		AND p.post_type IN ( '{$type}' )
			AND p.post_status IN ( '{$stat}' )
  	", $key ) );

    set_transient( $cache_key, $r, HOUR_IN_SECONDS );
  }
	return $r;
}

/**
 * Check if a page has a specific page builder part.
 *
 * @since  1.0.0
 *
 * @param  mixed $post_id   The post_id we're looking for.
 * @param  mixed $template  The template we're looking for.
 * @return boolean          Returns true if loaded on page.
 */
function post_has_builder_item( $post_id = null, $template = '', $area = 'all' ) {

  $return = false;

  if ( empty( $template ) )
    return $return;

  if ( empty( $post_id ) ) {
    global $wp_query;
	  $post_id = $wp_query->get_queried_object_id();
  }

  $metadata = array();

  if ( $area == 'all' || $area == 'default' ) {
    $metadata_default = (array) get_post_meta( $post_id, '_wds_builder_template', true );
    if ( !empty( $metadata_default ) ) {
      $metadata = array_merge( $metadata, $metadata_default );
    }
  }

  if ( $area == 'all' || $area == 'header' ) {
    $metadata_header = (array) get_post_meta( $post_id, '_wds_builder_page_builder_header_template', true );
    if ( !empty( $metadata_header ) ) {
      $metadata = array_merge( $metadata, $metadata_header );
    }
  }

  if ( empty( $metadata ) )
    return $return;

  foreach ($metadata as $key => $meta) {
    if ( isset( $meta['template_group'] ) && $meta['template_group'] == $template ) {
      $return = true;
      break;
    }
  }
  return $return;
}

/**
 * Get html select options saved layout page builder.
 *
 * @since  1.0.0
 *
 * @return mixed 		array layout post_name => post_title
 */
function mtcore_get_saved_layouts( $args = array() ) {
	$query = array(
		'post_type'      => 'wds_pb_layouts',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC'
	);
	$query = wp_parse_args( $args, $query );

	$layouts = new WP_Query( apply_filters( 'mtcore_get_saved_layouts', $query ) );

	$return = array();
	if ( $layouts->have_posts() ) {
		$layouts = $layouts->get_posts();
		foreach ( $layouts as $layout ) {
			$return[ $layout->post_name ] = $layout->post_title;
		}
	}
	wp_reset_postdata();
	return $return;
}

/**
 * Instance class loremipsum.
 *
 * @since  1.0.0
 *
 * @return object
 */
function mtcore_lorem() {
	/**
	 * Lorem ipsum generator in PHP without dependencies.
	 * Compatible with PHP 5.3+
	 */
	require_once MT_INC . 'class-mtcore-loremipsum.php';
	return new MT_Core_Lorem();
}

/**
 * Generate Ramdom Lorem Ipsum
 *
 * @since  1.0.0
 *
 * @param  string $type  	  	Gnerated type word/sentence/paragraph
 * @param  integer $num  			The template we're looking for.
 * @param  boolean $force			Force regenerate, ignoring transient
 * @return string           	Random generated lorem ipsum
 */
function get_generated_lorem_ipsum( $type = 'sentence', $num = 1, $force = false ) {
	$text = '';
	$lorem_key = 'MT_'.md5( serialize( array( $type, $num ) ) );

	if ( $force || false === ( $text = get_transient( $lorem_key ) ) ) {
		$lipsum = mtcore_lorem();
		switch ($type) {
			case 'word':
			case 'words':
				$text = $lipsum->words($num);
				break;
			case 'sentence':
			case 'sentences':
				$text = $lipsum->sentences($num);
				break;
			default:
			case 'paragraph':
			case 'paragraphs':
				$text = $lipsum->paragraphs($num, 'p');
				break;
		}
		if ( !$force )
			set_transient( $lorem_key, $text, 365 * DAY_IN_SECONDS );
	}
	return $text;
}
