<?php
/**
 * TinyMCE editor fields
 * gallery shortcode.
 *
 * @return     mixed
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes/shortcodes
 */

$img_sizes = array();
$image_sizes = get_intermediate_image_sizes();

foreach ( $image_sizes as $size_name ) {
	$img_sizes[] = array( 'value' => sanitize_title( $size_name ), 'text' => $size_name );
}

return array(

	array(
    'type'    => 'textbox',
    'name'    => 'columns',
    'value'   => '3',
    'label'   => esc_attr__( 'Gallery Column', 'mtcore' ),
    'tooltip' => esc_attr__( 'The default value is 3. If columns is set to 0, no row breaks will be included', 'mtcore' ),
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'size',
    'value'   => 'thumbnail',
    'label'   => esc_attr__( 'Image Size', 'mtcore' ),
    'values'  => $img_sizes
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'link',
    'value'   => 'file',
    'label'   => esc_attr__( 'Image to Link', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'none', 'text' => esc_attr__( 'None', 'mtcore' ) ),
			array( 'value' => 'file', 'text' => esc_attr__( 'Image File', 'mtcore' ) ),
		)
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'orderby',
    'value'   => 'post_date',
    'label'   => esc_attr__( 'Sort Display', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'menu_order', 'text' => esc_attr__( 'Menu Order', 'mtcore' ) ),
			array( 'value' => 'title', 'text' => esc_attr__( 'Title of the image', 'mtcore' ) ),
			array( 'value' => 'post_date', 'text' => esc_attr__( 'Image upload date', 'mtcore' ) ),
			array( 'value' => 'rand', 'text' => esc_attr__( 'Random order', 'mtcore' ) ),
		)
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'order',
    'value'   => 'ASC',
    'label'   => esc_attr__( 'Sort Order', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'ASC', 'text' => 'ASC' ),
			array( 'value' => 'DESC', 'text' => 'DESC' ),
		)
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'include',
    'value'   => '',
    'label'   => esc_attr__( 'Include Items', 'mtcore' ),
    'tooltip' => esc_attr__( 'attachment IDs separated by comma', 'mtcore' ),
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'exclude',
    'value'   => '',
    'label'   => esc_attr__( 'Exclude Items', 'mtcore' ),
    'tooltip' => esc_attr__( 'attachment IDs separated by comma', 'mtcore' ),
  ),

);
