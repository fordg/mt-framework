<?php
/**
 * TinyMCE editor fields
 * heading H1 shortcode.
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
    'name'    => 'class',
    'value'   => '',
    'label'   => esc_attr__( 'Extra Class', 'jobpress' )
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'align',
    'label'   => esc_html__( 'Heading Align', 'jobpress' ),
    'values'  => array(
      array( 'value' => 'aligncenter', 'text' => esc_attr__( 'Align Center', 'mtcore' ), 'selected' => true ),
      array( 'value' => 'alignleft', 'text' => esc_attr__( 'Align Left', 'mtcore' ) ),
      array( 'value' => 'alignright', 'text' => esc_attr__( 'Align Right', 'mtcore' ) )
    )
  ),

);
