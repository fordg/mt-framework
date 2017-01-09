<?php
/**
 * Part Name: Page Builder Saved Layout
 * Description: Display parts from saved layouts page builder.
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes/builders
 */

$layout_name = wds_page_builder_get_this_part_data( 'template_saved_layout' );

if ( !empty( $layout_name ) ) get_saved_page_builder_layout_by_slug( $layout_name );
