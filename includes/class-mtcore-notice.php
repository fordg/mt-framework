<?php
/**
 * The file that defines the admin notice
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes
 */

 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

class MT_Core_Notice {

	/**
	 * Instance of message style status.
	 *
	 * @since    1.0.0
	 */
	protected $status;

	/**
	 * Instance of message cause status.
	 *
	 * @since    1.0.0
	 */
	protected $cause;

	/**
	 * Instance of extra args.
	 *
	 * @since    1.0.0
	 */
	protected $args = array();

	/**
	 * Instance of the message.
	 *
	 * @since    1.0.0
	 */
	protected $message = '';

	/**
	 * Run or add new notice with :
	 * new MT_Core_Notice( $status, $cause, $args );
	 *
	 * @since    1.0.0
	 *
	 * @param 	 $status
	 *					 $cause
	 *					 $args
	 *
	 * @return	 void
	 */
	public function __construct( $status, $cause, $args ) {

		$this->status = $status;
		$this->cause  = $cause;
		$this->args   = $args;

		$this->set_message();

		switch ($status) {
			case 'success':
				add_action( 'admin_notices', array( $this, 'notice_success' ) );
				break;
			case 'info':
				add_action( 'admin_notices', array( $this, 'notice_info' ) );
				break;
			case 'warning':
				add_action( 'admin_notices', array( $this, 'notice_warning' ) );
				break;
			case 'error':
				add_action( 'admin_notices', array( $this, 'notice_error' ) );
				break;
		}
	}

	/**
	 * Set the notice message.
	 *
	 * @since 	1.0.0
	 * @return 	string
	 */
	private function set_message() {

		switch ( $this->cause ) {
			case 'version':
				$obj = isset( $this->args[0] ) ? $this->args[0] : _x( 'theme', 'notice theme version', 'mtcore' );
				$sub = isset( $this->args[1] ) ? $this->args[1] : _x( 'theme', 'notice theme version', 'mtcore' );
				$message = sprintf(
					__( '%1$s! %2$s version are not suitable. Please update your %3$s version.', 'mtcore' ),
					'<strong>'.strtoupper( $this->status ).'</strong>',
					ucfirst( $obj ),
					$sub
				);
				break;
			case 'features':
				$obj = isset( $this->args[0] ) ? $this->args[0] : _x( 'some of', 'notice theme features', 'mtcore' );
				$sub = isset( $this->args[1] ) ? $this->args[1] : _x( 'other', 'notice related features activation', 'mtcore' );
				$message = sprintf(
					__( '%1$s! %2$s feature require the %3$s feature. Please make sure %3$s feature are registered.', 'mtcore' ),
					'<strong>'.strtoupper( $this->status ).'</strong>',
					ucfirst( $obj ),
					$sub
				);
				break;
			case 'plugin':
				$obj = isset( $this->args[0] ) ? $this->args[0] : _x( 'some of', 'notice feature object conflict with plugin', 'mtcore' );
				$sub = isset( $this->args[1] ) ? $this->args[1] : _x( 'nameless', 'notice default plugin name', 'mtcore' );
				$message = sprintf(
					__( '%1$s! %2$s feature conflict with %3$s plugin. Please remove or deactivate %3$s plugin first.', 'mtcore' ),
					'<strong>'.strtoupper( $this->status ).'</strong>',
					ucfirst( $obj ),
					'<strong>'.$sub.'</strong>'
				);
				break;
      case 'saved':
        $message = __( 'Settings Saved.', 'mtcore' );
        break;
			default:
				if ( $this->status == 'success' ) {
					$message = __( '<strong>SUCCESS!</strong> You just succeeded in doing something.', 'mtcore' );
				}
				elseif ( $this->status == 'info' ) {
					$message = __( '<strong>INFO!</strong> check other cool theme by Minimal Themes <a href="//minimalthemes.net/" target="_blank">here!</a>.', 'mtcore' );
				}
				elseif ( $this->status == 'warning' ) {
					$message = __( '<strong>WARNING!</strong> Something is wrong, please try again.', 'mtcore' );
				}
				else {
					$message = __( '<strong>ERROR!</strong> Something is wrong, please try again later.', 'mtcore' );
				}
				break;
		}

		$this->message = $message;
	}

	/**
	 * Add admin success notice bar
	 *
	 * @since 	1.0.0
	 * @return 	html
	 */
	public function notice_success() {
		?>
    <div class="notice notice-success">
      <p><?php echo $this->message; ?></p>
    </div>
    <?php
	}

	/**
	 * Add admin info notice bar
	 *
	 * @since 	1.0.0
	 * @return 	html
	 */
	public function notice_info() {
		?>
    <div class="notice notice-info">
      <p><?php echo $this->message; ?></p>
    </div>
    <?php
	}

	/**
	 * Add admin warning notice bar
	 *
	 * @since 	1.0.0
	 * @return 	html
	 */
	public function notice_warning() {
		?>
    <div class="notice notice-warning">
      <p><?php echo $this->message; ?></p>
    </div>
    <?php
	}

	/**
	 * Add admin error notice bar
	 *
	 * @since 	1.0.0
	 * @return 	html
	 */
	public function notice_error() {
		?>
    <div class="notice notice-error">
      <p><?php echo $this->message; ?></p>
    </div>
    <?php
	}
}
