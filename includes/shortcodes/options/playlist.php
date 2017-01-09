<?php
/**
 * TinyMCE editor fields
 * playlist shortcode.
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
    'type'    => 'combobox',
    'name'    => 'ids',
    'label'   => esc_html__( 'Image IDs', 'mtcore' ),
    'values'  => array(),
		'tooltip' => esc_attr__( 'attachment IDs separated by comma', 'mtcore' )
  ),

	array(
		'type'		=> 'container',
		'html'		=> __( 'leave empty to collect all audio or video from current post', 'mtcore' ),
	),

	array(
    'type'    => 'listbox',
    'name'    => 'type',
    'label'   => esc_html__( 'Content Type', 'mtcore' ),
    'values'  => array(
      array( 'value' => 'audio', 'text' => esc_attr__( 'Audio Playlist', 'mtcore' ) ),
      array( 'value' => 'video', 'text' => esc_attr__( 'Video Playlist', 'mtcore' ) ),
    )
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'style',
    'label'   => esc_html__( 'Color Style', 'mtcore' ),
    'values'  => array(
      array( 'value' => 'dark', 'text' => esc_attr__( 'Light Color', 'mtcore' ) ),
      array( 'value' => 'light', 'text' => esc_attr__( 'Dark Color', 'mtcore' ) ),
    )
  )

);
