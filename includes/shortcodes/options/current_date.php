<?php
/**
 * TinyMCE editor fields
 * current_date shortcode.
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
		'type'	=> 'container',
		'label' => '',
		'html'	=> '<strong>Instruction</strong><ul><li> <code>l</code> = Full name for day of the week (lower-case L).</li><li> <code>F</code> = Full name for the month.</li><li> <code>j</code> = The day of the month.</li><li> <code>Y</code> = The year in 4 digits.</li></ul><br><a href=\'https://codex.wordpress.org/Formatting_Date_and_Time#Examples\' target=\'_blank\'>more information &#10140;</a>'
	),

	array(
    'type'    => 'textbox',
    'name'    => 'format',
    'value'   => get_option( 'date_format' ),
    'label'   => esc_attr__( 'Date Format', 'mtcore' ),
    'tooltip' => esc_attr__( 'Required Field', 'mtcore' ),
  )

);
