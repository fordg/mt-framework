<?php
/**
 * TinyMCE editor fields
 * logged_in shortcode.
 *
 * @return     mixed
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes/shortcodes
 */

global $wp_roles;

$role_lists = array();
$roles = $wp_roles->get_names();

foreach ( $roles as $key => $value ) {
	$role_lists[] = array( 'value' => $key, 'text' => $value );
}

return array(

	array(
    'type'    => 'checkbox',
    'name'    => 'hide',
    'checked' => false,
    'label'   => esc_attr__( 'Hide from Logged In', 'mtcore' ),
    'text'    => esc_attr__( 'Display to Guest Only', 'mtcore' )
  ),

	array(
    'type'    => 'listbox',
    'name'    => 'role',
    'label'   => esc_html__( 'Minimum User Role', 'jobpress' ),
    'values'  => array_reverse($role_lists)
  ),

	array(
    'type'    => 'textbox',
    'name'    => 'message',
    'value'   => '',
		'multiline'=> true,
    'label'   => esc_attr__( 'Custom Message', 'jobpress' ),
		'tooltip'   => esc_attr__( 'Message displayed if content is hidden', 'jobpress' ),
  ),

);
