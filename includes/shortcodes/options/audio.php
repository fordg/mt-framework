<?php
/**
 * TinyMCE editor fields
 * audio shortcode.
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
		'placeholder' => '.mp3, .m4a, .ogg, .wav, .wma',
    'label'   => esc_attr__( 'Audio File', 'mtcore' ),
    'tooltip' => esc_attr__( 'leave empty to get first audio file attached to the post', 'mtcore' ),
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
  )

);
