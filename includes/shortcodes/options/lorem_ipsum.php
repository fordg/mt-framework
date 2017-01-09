<?php
/**
 * TinyMCE editor fields
 * lorem_ipsum shortcode.
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
    'type'    => 'listbox',
    'name'    => 'type',
    'label'   => esc_html__( 'Text Type', 'jobpress' ),
    'values'  => array(
      array( 'value' => 'words', 'text' => esc_attr__( 'Words', 'mtcore' ) ),
      array( 'value' => 'sentences', 'text' => esc_attr__( 'Sentences', 'mtcore' ) ),
      array( 'value' => 'paragraphs', 'text' => esc_attr__( 'Paragraphs', 'mtcore' ) )
    )
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'number',
    'value'   => '3',
    'label'   => esc_attr__( 'Generated Count', 'mtcore' )
  ),

	array(
    'type'    => 'checkbox',
    'name'    => 'unique',
    'text'    => esc_attr__( 'Force Unique Random Generated', 'mtcore' ),
		'label'   => esc_attr__( 'Always Generate New', 'mtcore' ),
		'tooltip' => esc_attr__( 'Check to force generate new text every page loaded', 'mtcore' ),
  ),

);
