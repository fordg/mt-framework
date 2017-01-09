<?php
/**
 * TinyMCE editor fields
 * video shortcode.
 *
 * @return     mixed
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes/shortcodes
 */

return array(

	array(
    'type'    => 'textbox',
    'name'    => 'src',
    'value'   => '',
		'placeholder' => '.mp4, .m4v, .webm, .ogv, .wmv, .flv',
    'label'   => esc_attr__( 'Video File', 'mtcore' ),
    'tooltip' => esc_attr__( 'leave empty to get first video file attached to the post', 'mtcore' ),
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'poster',
    'value'   => '',
		'placeholder' => '.jpg, .png, .gif',
    'label'   => esc_attr__( 'Placehoder Image', 'mtcore' ),
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'loop',
    'value'   => 'off',
    'label'   => esc_attr__( 'Looping Media', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'off', 'text' => esc_attr__( 'Do not loop the media', 'mtcore' ) ),
			array( 'value' => 'on', 'text' => esc_attr__( 'Loop to beginning when finished', 'mtcore' ) ),
		)
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'autoplay',
    'value'   => 'off',
    'label'   => esc_attr__( 'Automatically Play', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'off', 'text' => esc_attr__( 'Do not automatically play the media', 'mtcore' ) ),
			array( 'value' => 'on', 'text' => esc_attr__( 'Media will play as soon as it is ready', 'mtcore' ) ),
		)
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'preload',
    'value'   => 'none',
    'label'   => esc_attr__( 'Auto Load', 'mtcore' ),
    'values'  => array(
			array( 'value' => 'none', 'text' => esc_attr__( 'Do not load media when page loads', 'mtcore' ) ),
			array( 'value' => 'auto', 'text' => esc_attr__( 'Load entirely when the page loads', 'mtcore' ) ),
			array( 'value' => 'metadata', 'text' => esc_attr__( 'Only metadata should be loaded', 'mtcore' ) ),
		)
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'height',
    'value'   => '',
		'placeholder' => '480px',
    'label'   => esc_attr__( 'Video Height', 'mtcore' ),
		'tooltip' => esc_attr__( 'Number only, in pixel', 'mtcore' ),
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'width',
    'value'   => '',
		'placeholder' => '900px',
    'label'   => esc_attr__( 'Video Width', 'mtcore' ),
		'tooltip' => esc_attr__( 'Number only, in pixel', 'mtcore' ),
  )

);
