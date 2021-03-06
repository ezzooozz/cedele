<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

/**
 * @since 3.11
 */
class Omise_Payment_Paynow extends Omise_Payment_Offline {
	public function __construct() {
		parent::__construct();

		$this->id                 = 'omise_paynow';
		$this->has_fields         = false;
		$this->method_title       = __( 'Omise PayNow', 'omise' );
		$this->method_description = wp_kses(
			__( 'Accept payments through <strong>PayNow</strong> via Omise payment gateway.', 'omise' ),
			array( 'strong' => array() )
		);

		$this->init_form_fields();
		$this->init_settings();

		$this->title                = $this->get_option( 'title' );
		$this->description          = $this->get_option( 'description' );
		$this->restricted_countries = array( 'SG' );
		$this->source_type          = 'paynow';

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_order_action_' . $this->id . '_sync_payment', array( $this, 'sync_payment' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'display_qrcode' ) );
	}

	/**
	 * @see WC_Settings_API::init_form_fields()
	 * @see woocommerce/includes/abstracts/abstract-wc-settings-api.php
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'omise' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Omise PayNow Payment', 'omise' ),
				'default' => 'no'
			),

			'title' => array(
				'title'       => __( 'Title', 'omise' ),
				'type'        => 'text',
				'description' => __( 'This controls the title the user sees during checkout.', 'omise' ),
				'default'     => __( 'PayNow', 'omise' ),
			),

			'description' => array(
				'title'       => __( 'Description', 'omise' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description the user sees during checkout.', 'omise' ),
				'default'     => __( 'You will not be charged yet. The PayNow QR code will be displayed at the next page.', 'omise' )
			),
		);
	}

	/**
	 * @param int|WC_Order $order
	 * @param string       $context  pass 'email' value through this argument only for 'sending out an email' case.
	 */
	public function display_qrcode( $order, $context = 'view' ) {
		if ( ! $this->load_order( $order ) ) {
			return;
		}

		$charge_id = $this->get_charge_id_from_order();
		$charge    = OmiseCharge::retrieve( $charge_id );
		$qrcode    = $charge['source']['scannable_code']['image']['download_uri'];
		?>
		<div class="omise omise-paynow-details" <?php echo 'email' === $context ? 'style="margin-bottom: 4em; text-align:center;"' : ''; ?>>
			<div class="omise omise-paynow-logo"></div>
			<p>
				<?php echo __( 'Scan the QR code to pay', 'omise' ); ?>
			</p>
			<div class="omise omise-paynow-qrcode">
				<img src="<?php echo $qrcode; ?>" alt="Omise QR code ID: <?php echo $charge['source']['scannable_code']['image']['id']; ?>">
			</div>
		</div>
		<?php
	}
}
