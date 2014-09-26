<?php
/*
Plugin Name: Restrict Content Pro - Braintree Gateway
Plugin URL: http://dev7studios.com/restrict-content-pro-braintree
Description: Enables the Braintree gateway for Restict Content Pro
Version: 1.0.4
Author: Pippin Williamson
Author URI: https://pippinsplugins.com
*/

/**************************
* constants
**************************/

if ( !defined( 'RCP_BRAINTREE_FILE' ) ) {
	define( 'RCP_BRAINTREE_FILE', __FILE__ );
}
if ( !defined( 'RCP_BRAINTREE_DIR' ) ) {
	define( 'RCP_BRAINTREE_DIR', dirname( __FILE__ ) );
}
if ( !defined( 'RCP_BRAINTREE_URL' ) ) {
	define( 'RCP_BRAINTREE_URL', plugin_dir_url( __FILE__ ) );
}

/*******************************************
* plugin text domain for translations
*******************************************/

function rcp_braintree_load_textdomain() {
	load_plugin_textdomain( 'rcp_braintree', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'init', 'rcp_braintree_load_textdomain' );


function rcp_braintree_updater() {

	global $rcp_options;

	if( ! class_exists( 'RCP_Plugin_Updater' ) )
		return;

	// retrieve our license key from the DB
	$license_key = ! empty( $rcp_options['braintree_license_key'] ) ? trim( $rcp_options['braintree_license_key'] ) : false;

	if( $license_key ) {
		// setup the updater
		$rcp_updater = new RCP_Plugin_Updater( 'https://pippinsplugins.com', __FILE__, array(
				'version' 	=> '1.0.4',
				'license' 	=> $license_key,
				'item_name' => 'Restrict Content Pro Braintree Payment Gateway',
				'author' 	=> 'Pippin Williamson'
			)
		);
	}
}
add_action( 'admin_init', 'rcp_braintree_updater' );

/**************************
* includes
**************************/

// displays a message if RCP is below the required version
function rcp_braintree_plugin_version_notice() {
	// not sure the is_admin check is necessary, but just to be safe because get_plugin_data is only avail in admin
	if ( is_admin() ) {
		if( defined( 'RCP_PLUGIN_FILE' ) ) $rcp = get_plugin_data( RCP_PLUGIN_FILE );
		if ( !isset( $rcp['Version'] ) || $rcp['Version'] < '1.8' ) {

			$message = __( 'Your version of Restrict Content Pro is below the required version number. Please upgrade to at least 1.8', 'rcp_braintree' );

			echo '<div id="message" class="error"><p>' . $message . '</p></div>';
		}
	}
}
add_action( 'admin_notices', 'rcp_braintree_plugin_version_notice' );

// enables the Braintree gateway
function rcp_register_braintree_gateway( $gateways ) {

	$gateways['braintree'] = __( 'Credit Card', 'rcp_braintree' );

	return $gateways;
}
add_filter( 'rcp_payment_gateways', 'rcp_register_braintree_gateway' );

/**************************
* settings
**************************/

function rcp_braintree_gateway_settings( $rcp_options ) {
	ob_start(); ?>
	<table class="form-table">
		<tr valign="top">
			<th colspan=2>
				<h3><?php _e('Braintree Settings', 'rcp_braintree'); ?></h3>
			</th>
		</tr>
		<tr>
			<td colspan=2>
				<p><?php _e('Configure your Braintree settings.', 'rcp_braintree'); ?></p>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_live_merchantId]"><?php _e( 'Live Merchant ID', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_live_merchantId]" style="width: 300px;" name="rcp_settings[braintree_live_merchantId]" value="<?php if(isset($rcp_options['braintree_live_merchantId'])) { echo $rcp_options['braintree_live_merchantId']; } ?>"/>
				<div class="description"><?php _e('Enter your live Merchant ID', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_live_publicKey]"><?php _e( 'Live Public key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_live_publicKey]" style="width: 300px;" name="rcp_settings[braintree_live_publicKey]" value="<?php if(isset($rcp_options['braintree_live_publicKey'])) { echo $rcp_options['braintree_live_publicKey']; } ?>"/>
				<div class="description"><?php _e('Enter your live public key', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_live_privateKey]"><?php _e( 'Live Private key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_live_privateKey]" style="width: 300px;" name="rcp_settings[braintree_live_privateKey]" value="<?php if(isset($rcp_options['braintree_live_privateKey'])) { echo $rcp_options['braintree_live_privateKey']; } ?>"/>
				<div class="description"><?php _e('Enter your live private key', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_live_encryptionKey]"><?php _e( 'Live Client Side Encryption Key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<textarea class="regular-text" id="rcp_settings[braintree_live_encryptionKey]" style="width: 300px;height: 100px;" name="rcp_settings[braintree_live_encryptionKey]"><?php if(isset($rcp_options['braintree_live_encryptionKey'])) { echo $rcp_options['braintree_live_encryptionKey']; } ?></textarea>
				<div class="description"><?php _e('Enter your live client side encryption key (for use with Braintree.js)', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_sandbox_merchantId]"><?php _e( 'Sandbox Merchant ID', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_sandbox_merchantId]" style="width: 300px;" name="rcp_settings[braintree_sandbox_merchantId]" value="<?php if(isset($rcp_options['braintree_sandbox_merchantId'])) { echo $rcp_options['braintree_sandbox_merchantId']; } ?>"/>
				<div class="description"><?php _e('Enter your sandbox Merchant ID', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_sandbox_publicKey]"><?php _e( 'Sandbox Public key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_sandbox_publicKey]" style="width: 300px;" name="rcp_settings[braintree_sandbox_publicKey]" value="<?php if(isset($rcp_options['braintree_sandbox_publicKey'])) { echo $rcp_options['braintree_sandbox_publicKey']; } ?>"/>
				<div class="description"><?php _e('Enter your sandbox public key', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_sandbox_privateKey]"><?php _e( 'Sandbox Private key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input class="regular-text" id="rcp_settings[braintree_sandbox_privateKey]" style="width: 300px;" name="rcp_settings[braintree_sandbox_privateKey]" value="<?php if(isset($rcp_options['braintree_sandbox_privateKey'])) { echo $rcp_options['braintree_sandbox_privateKey']; } ?>"/>
				<div class="description"><?php _e('Enter your sandbox private key', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_sandbox_encryptionKey]"><?php _e( 'Sandbox Client Side Encryption Key', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<textarea class="regular-text" id="rcp_settings[braintree_sandbox_encryptionKey]" style="width: 300px;height: 100px;" name="rcp_settings[braintree_sandbox_encryptionKey]"><?php if(isset($rcp_options['braintree_sandbox_encryptionKey'])) { echo $rcp_options['braintree_sandbox_encryptionKey']; } ?></textarea>
				<div class="description"><?php _e('Enter your sandbox client side encryption key (for use with Braintree.js)', 'rcp_braintree'); ?></div>
			</td>
		</tr>
		<tr>
			<th>
				<label for="rcp_settings[braintree_environment]"><?php _e( 'Braintree Sandbox', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<input type="hidden" value="production" name="rcp_settings[braintree_environment]" />
				<input type="checkbox" value="sandbox" name="rcp_settings[braintree_environment]" id="rcp_settings[braintree_environment]" <?php checked( isset( $rcp_options['braintree_environment'] ) ? $rcp_options['braintree_environment'] : false, 'sandbox' ); ?>/>
				<span class="description"><label for="rcp_settings[braintree_environment]"><?php _e('Use Braintree sandbox to test your transactions.', 'rcp_braintree'); ?></label></span>
			</td>
		</tr>
		<tr>
			<th>
				<label><?php _e( 'Other Info', 'rcp_braintree' ); ?></label>
			</th>
			<td>
				<p><strong><?php _e( 'Subscriptions', 'rcp_braintree' ); ?></strong><br />
				For recurring subscriptions to work you need to create plans in the Braintree dashboard that correspond
				to the <a href="<?php echo admin_url( 'admin.php?page=rcp-member-levels' ); ?>">subscriptions levels</a> you set
				up in Restrict Content Pro. The <strong>plan ID</strong> should match the <strong>subscriptions level ID</strong>.</p>
				<p><strong>Webhooks</strong><br />
				To set up a webhook via the Braintree dashboard enter the following data:</p>
				<ul>
					<li><strong>Destination:</strong> <code><?php echo home_url(); ?></code></li>
					<li><strong>Notifications to send:</strong> <code>Subscription Charged Successfully</code>, <code>Subscription Charged Unsuccessfully</code>, <code>Subscription Canceled</code> and <code>Subscription Expired</code></li>
				</ul>
			</td>
		</tr>
	</table>
	<?php
	echo apply_filters( 'rcp_braintree_gateway_settings', ob_get_clean() );
}
add_action('rcp_payments_settings', 'rcp_braintree_gateway_settings');

function rcp_braintree_license_settings( $rcp_options ) {
	ob_start(); ?>
	<tr valign="top">
		<th>
			<label for="rcp_settings[braintree_license_key]"><?php _e( 'Braintree License Key', 'rcp_braintree' ); ?></label>
		</th>
		<td>
			<input class="regular-text" id="rcp_settings[braintree_license_key]" style="width: 300px;" name="rcp_settings[braintree_license_key]" value="<?php if(isset($rcp_options['braintree_license_key'])) { echo $rcp_options['braintree_license_key']; } ?>"/>
			<?php $status = get_option( 'rcp_braintree_license_status' ); ?>
			<?php if( $status !== false && $status == 'valid' ) { ?>
				<?php wp_nonce_field( 'rcp_braintree_deactivate_license', 'rcp_braintree_deactivate_license' ); ?>
				<input type="submit" class="button-secondary" name="rcp_braintree_license_deactivate" value="<?php _e('Deactivate License', 'rcp_braintree'); ?>"/>
				<span style="color:green;"><?php _e('active'); ?></span>
			<?php } ?>
			<div class="description"><?php printf( __( 'Enter your license key for the Braintree Payment Gateway. This is required for automatic updates and <a href="%s">support</a>.', 'rcp_braintree' ), 'https://pippinsplugins.com/plugin-support' ); ?></div>
		</td>
	</tr>
<?php
	echo ob_get_clean();
}
add_action('rcp_license_settings', 'rcp_braintree_license_settings');


function rcp_braintree_save_settings( $data ) {

	if( empty( $data['braintree_license_key'] ) )
		delete_option( 'rcp_braintree_license_status' );

	if( ! empty( $_POST['rcp_braintree_license_deactivate'] ) )
		rcp_braintree_deactivate_license();
	elseif( ! empty( $data['braintree_license_key'] ) )
		rcp_braintree_activate_license();


	return $data;
}
add_action( 'rcp_save_settings', 'rcp_braintree_save_settings' );

function rcp_braintree_activate_license() {
	if( ! isset( $_POST['rcp_settings'] ) )
		return;

	if( ! isset( $_POST['rcp_settings']['braintree_license_key'] ) )
		return;

	// retrieve the license from the database
	$status  = get_option( 'rcp_braintree_license_status' );
	$license = trim( $_POST['rcp_settings']['braintree_license_key'] );

	if( 'valid' == $status )
		return; // license already activated

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode( 'Restrict Content Pro Braintree Payment Gateway' ), // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, 'https://pippinsplugins.com' ), array( 'timeout' => 15, 'sslverify' => false ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return false;

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	update_option( 'rcp_braintree_license_status', $license_data->license );

}

function rcp_braintree_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['rcp_braintree_license_deactivate'] ) ) {

		global $rcp_options;

		// run a quick security check
	 	if( ! check_admin_referer( 'rcp_braintree_deactivate_license', 'rcp_braintree_deactivate_license' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( $rcp_options['braintree_license_key'] );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( 'Restrict Content Pro Braintree Payment Gateway' ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, 'https://pippinsplugins.com' ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'rcp_braintree_license_status' );

	}
}

/**************************
* form fields
**************************/

function rcp_braintree_form_fields() {
	global $rcp_options;

	if(isset($rcp_options['gateways']['braintree'])) {
	    ob_start(); ?>
	    <script type="text/javascript">
	      jQuery(document).ready(function($) {
	        // show the credit card fields
	        $('select#rcp_gateway').change(function() {
	          var $this = $(this);
	          if($('option:selected', $this).val() == 'braintree') {
	            $('#rcp-braintree-fields').slideDown();
	          } else {
	            $('#rcp-braintree-fields').slideUp();
	          }
	        });

	      });
	    </script>
	    <fieldset id="rcp-braintree-fields" <?php if(count(rcp_get_enabled_payment_gateways()) > 1) { ?>style="display:none;"<?php } ?>>
	    <legend><?php _e( 'Billing Details', 'rcp_braintree' ); ?></legend>
	      <div class="payment-errors"></div>
	      <p>
	        <label><?php _e('First Name', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="first_name" class="first-name" />
	      </p>
	      <p>
	        <label><?php _e('Last Name', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="last_name" class="last-name" />
	      </p>
	      <p>
	        <label><?php _e('Card Number', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" autocomplete="off" data-encrypted-name="number" class="card-number" />
	      </p>
	      <p>
	        <label><?php _e('CVC', 'rcp_braintree'); ?></label>
	        <input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" class="card-cvc" />
	      </p>
	      <p>
	        <label><?php _e('Expiration (MM/YYYY)', 'rcp_braintree'); ?></label>
	        <select name="exp_month" class="card-expiry-month">
	          <option value="01"><?php _e('01', 'rcp_braintree'); ?></option>
	          <option value="02"><?php _e('02', 'rcp_braintree'); ?></option>
	          <option value="03"><?php _e('03', 'rcp_braintree'); ?></option>
	          <option value="04"><?php _e('04', 'rcp_braintree'); ?></option>
	          <option value="05"><?php _e('05', 'rcp_braintree'); ?></option>
	          <option value="06"><?php _e('06', 'rcp_braintree'); ?></option>
	          <option value="07"><?php _e('07', 'rcp_braintree'); ?></option>
	          <option value="08"><?php _e('08', 'rcp_braintree'); ?></option>
	          <option value="09"><?php _e('09', 'rcp_braintree'); ?></option>
	          <option value="10"><?php _e('10', 'rcp_braintree'); ?></option>
	          <option value="11"><?php _e('11', 'rcp_braintree'); ?></option>
	          <option value="12"><?php _e('12', 'rcp_braintree'); ?></option>
	        </select>
	        <select name="exp_year" class="card-expiry-year">
	          <?php
	          $i = date('Y');
	          $max = $i + 10;
	          while( $i < $max ) : ?>
	            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
	          <?php $i++;
	          endwhile; ?>
	        </select>
	      </p>
	      <p>
	        <label><?php _e('Address', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="billing_address" class="billing-address" />
	      </p>
	      <p>
	        <label><?php _e('Address 2', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="billing_address2" class="billing-address2" />
	      </p>
	      <p>
	        <label><?php _e('City', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="billing_city" class="billing-city" />
	      </p>
	      <p>
	        <label><?php _e('State / Province', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="billing_state" class="billing-state" />
	      </p>
	      <p>
	        <label><?php _e('Zip', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="billing_zip" class="billing-zip" />
	      </p>
	      <p>
	        <label><?php _e('Country', 'rcp_braintree'); ?></label>
	        <select name="billing_country" class="billing-country" style="max-width: 180px">
	        	<?php
	        		$countries = rcp_braintree_get_country_list();
	        		foreach($countries as $code => $country) {
	        			echo '<option value="' . $code . '">' . $country . '</option>';
	        		}
	        	?>
	        </select>
	      </p>
	      <p>
	        <label><?php _e('Phone', 'rcp_braintree'); ?></label>
	        <input type="text" size="20" name="phone" class="phone" />
	      </p>
	    </fieldset>
	    <script src="https://js.braintreegateway.com/v1/braintree.js"></script>
	    <script>
	    var braintree = Braintree.create("<?php
		if ( isset( $rcp_options['braintree_environment'] ) && $rcp_options['braintree_environment'] == 'sandbox' ) {
	    	echo isset( $rcp_options['braintree_sandbox_encryptionKey'] ) ? $rcp_options['braintree_sandbox_encryptionKey'] : '';
	    } else {
			echo isset( $rcp_options['braintree_live_encryptionKey'] ) ? $rcp_options['braintree_live_encryptionKey'] : '';
	    }
	    ?>");
	    braintree.onSubmitEncryptForm('rcp_registration_form');
	    </script>
		<?php
		echo apply_filters( 'rcp_braintree_form_fields', ob_get_clean(), $rcp_options );
	}
}
add_action('rcp_before_registration_submit_field', 'rcp_braintree_form_fields');

/**************************
* cancel subscription
**************************/

function rcp_braintree_add_cancel_button() {

	$user_id    = get_current_user_id();
	$profile_id = get_user_meta( $user_id, 'rcp_recurring_payment_id', true );

	if ( rcp_get_status( $user_id ) == 'active' && $profile_id ) {
		echo '<a id="rcp_cancel_sub" href="' . esc_url( add_query_arg( array( 'cancel_braintree_subscription' => 'yes', 'profile_id' => $profile_id ) ) ) . '">';
		 	echo __('Cancel your subscription', 'rcp_braintree');
		echo '</a>';
	}
}
add_action( 'rcp_subscription_details_action_links', 'rcp_braintree_add_cancel_button', 999 );

function rcp_braintree_cancel_link_js() {

	global $rcp_load_css;

	$rcp_load_css = true;

	$user_id    = get_current_user_id();
	$profile_id = get_user_meta( $user_id, 'rcp_recurring_payment_id', true );

	if ( rcp_get_status( $user_id ) == 'active' && $profile_id ) {
?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#rcp_cancel_sub').on('click', function() {
				if(confirm('<?php _e("Do you really want to cancel your subscription? You will retain access for the length of time you have paid for.", "rcp_braintree"); ?>')) {
					return true;
				}
				return false;
			});
		});
	</script>
<?php
	}

	if( ! empty( $_GET['cancelled'] ) && 'yes' == $_GET['cancelled'] ) {
		echo '<div class="rcp_message updated">';
			echo '<p class="rcp_success"><span>' . __( 'Your subscription has been successfully cancelled', 'rcp_braintree' ) . '</span></p>';
		echo '</div>';
	}

}
add_action('rcp_subscription_details_top', 'rcp_braintree_cancel_link_js');

function rcp_braintree_init() {
	if ( isset( $_GET['cancel_braintree_subscription'] ) && $_GET['cancel_braintree_subscription'] == 'yes' ) {
		if ( isset( $_GET['profile_id'] ) ) {
	  		do_action( 'rcp_braintree_cancel_subscription', $_GET['profile_id'] );
	  		exit;
		}
  	}
  	if ( isset( $_GET['bt_challenge'] ) || ( isset( $_POST['bt_signature'] ) && isset( $_POST['bt_payload'] ) ) ) {
  		do_action( 'rcp_braintree_webhook' );
	  	exit;
  	}
}
add_action( 'init', 'rcp_braintree_init' );

function rcp_cancel_braintree_subscription( $profile_id ) {

	if( ! is_user_logged_in() ) {
		wp_die( __( 'Authorization error', 'rcp_braintree' ), __( 'Authorization Error', 'rcp_braintree' ) );
	}

	rcp_load_braintree_lib();

	$user_id = get_current_user_id();
	$user_profile_id = get_user_meta( $user_id, 'rcp_recurring_payment_id', true );

	if( $profile_id != $user_profile_id ) {
		wp_die( __( 'Authorization error', 'rcp_braintree' ), __( 'Authorization Error', 'rcp_braintree' ) );
	}

	$result = Braintree_Subscription::cancel( $profile_id );

	if ( $result->success ) {
		update_user_meta( $user_id, 'rcp_recurring', 'no' );
		update_user_meta( $user_id, 'rcp_recurring_cancelled', 'yes' );
		delete_user_meta( $user_id, 'rcp_recurring_payment_id' );

		rcp_set_status( $user_id, 'cancelled' );

		// send sub cancelled email
    	rcp_email_subscription_status( $user_id, 'cancelled' );

    	do_action( 'rcp_braintree_subscription_cancelled', $user_id );

	} else {
		$output = '<p><strong>'. __( 'Subscription Errors', 'rcp_braintree' ) .':</strong></p>';
		foreach($result->errors->deepAll() AS $error) {
			$output .= $error->code . ': ' . $error->message . '<br />';
		}
		wp_die( $output, __( 'Subscription Errors', 'rcp_braintree' ) );
	}

	wp_redirect( add_query_arg( 'cancelled', 'yes', $_SERVER['HTTP_REFERER'] ) ); exit;

}
add_action( 'rcp_braintree_cancel_subscription', 'rcp_cancel_braintree_subscription' );

/**************************
* webhooks
**************************/

function rcp_braintree_webhook() {
	rcp_load_braintree_lib();

	if( isset( $_GET['bt_challenge'] ) ){
		$verify = Braintree_WebhookNotification::verify($_GET['bt_challenge']);
		die($verify);
	}

	if( isset( $_POST['bt_signature'] ) && isset( $_POST['bt_payload'] ) ){
		$webhookNotification = Braintree_WebhookNotification::parse($_POST['bt_signature'], $_POST['bt_payload']);
		if( isset($webhookNotification->kind) && $webhookNotification->kind ){

			$user = false;
			if( isset($webhookNotification->subscription->id) ){
				// Query for user based on meta
				$user_query = new WP_User_Query( array(
					'meta_key' => 'rcp_recurring_payment_id',
					'meta_value' => $webhookNotification->subscription->id
				) );
				$users = $user_query->get_results();
				$user = empty ( $users[0] ) ? null : $users[0];
			}

			if( isset( $user->ID ) ){

				switch ( $webhookNotification->kind ) :

					case 'subscription_charged_successfully':
						if( isset($webhookNotification->subscription->transactions) && !empty($webhookNotification->subscription->transactions) ){
							$transaction = end( $webhookNotification->subscription->transactions );
							$payment_data = array(
								'date'             => date( 'Y-m-d g:i:s', strtotime( $transaction->createdAt ) ),
								'subscription'     => $subscription->planId,
								'payment_type'     => __( 'Credit Card Subscription', 'rcp_braintree' ),
								'subscription_key' => $webhookNotification->subscription->id,
								'amount'           => $transaction->amount,
								'user_id'          => $user->ID,
								'transaction_id'   => isset( $transaction->id ) ? $transaction->id : '',
							);
							$rcp_payments = new RCP_Payments();
							$rcp_payments->insert( $payment_data );

							$subscription = rcp_get_subscription_details( rcp_get_subscription_id( $user->ID ) );

							$member_new_expiration = rcp_calc_member_expiration( $subscription );

							if( function_exists( 'rcp_set_expiration_date' ) ) {
								rcp_set_expiration_date( $user->ID, $member_new_expiration );
							} else {
								update_user_meta( $user->ID, 'rcp_expiration', $member_new_expiration );
							}

							rcp_set_status( $user->ID, 'active' );

							update_user_meta( $user->ID, 'rcp_recurring_payment_id', $webhookNotification->subscription->id );
							update_user_meta( $user->ID, 'rcp_recurring', 'yes' );
							delete_user_meta( $user->ID, '_rcp_expired_email_sent' );

							do_action( 'rcp_braintree_subscription_charged_successfully', $user->ID );
						}
						break;

					case 'subscription_charged_unsuccessfully':
						do_action( 'rcp_braintree_subscription_charged_unsuccessfully', $user->ID );
						break;

					case 'subscription_canceled':
						update_user_meta( $user->ID, 'rcp_recurring', 'no' );
						update_user_meta( $user->ID, 'rcp_recurring_cancelled', 'yes' );
						delete_user_meta( $user->ID, 'rcp_recurring_payment_id' );
						rcp_email_subscription_status( $user->ID, 'cancelled' );

						do_action( 'rcp_braintree_subscription_canceled', $user->ID );
						break;

					case 'subscription_expired':
						delete_user_meta( $user->ID, 'rcp_recurring' );
						rcp_set_status( $user->ID, 'expired' );
						rcp_email_subscription_status( $user->ID, 'expired' );

						do_action( 'rcp_braintree_subscription_expired', $user->ID );
						break;

					default :
						break;

				endswitch;

			}
		}
	}

	exit;
}
add_action( 'rcp_braintree_webhook', 'rcp_braintree_webhook' );

/**************************
* process payment
**************************/

function rcp_process_braintree( $subscription_data ) {
	global $rcp_options;

	// just shorter and easier
	$data = $subscription_data;
	$paid = false;
	$type = 'single';
	if ( !empty( $data['auto_renew'] ) ) {
		$type = 'subscription';
	}

	rcp_load_braintree_lib();

	if ( $type == 'subscription' ) {
		try {
			$result = Braintree_Customer::create(array(
			    'firstName' => $_POST['first_name'],
				'lastName' => $_POST['last_name'],
				'phone' => $_POST['phone'],
				'email' => $data['user_email'],
			    'creditCard' => array(
			        'number' => $_POST['number'],
			        'cvv' => $_POST['cvv'],
			        'expirationMonth' => $_POST['exp_month'],
			        'expirationYear' => $_POST['exp_year'],
			        'billingAddress' => array(
			            'firstName' => $_POST['first_name'],
						'lastName' => $_POST['last_name'],
						'streetAddress' => $_POST['billing_address'],
						'extendedAddress' => $_POST['billing_address2'],
						'locality' => $_POST['billing_city'],
						'region' => $_POST['billing_state'],
						'postalCode' => $_POST['billing_zip'],
						'countryCodeAlpha2' => $_POST['billing_country']
			        )
			    )
			));
		}
		catch(Exception $e){
			$log_data = array(
				'post_title'    => __( 'Failed to create subscription', 'rcp_braintree' ),
				'post_content'  =>  $e->getMessage(),
				'post_parent'   => 0,
				'log_type'      => 'gateway_error'
			);
			$log_meta = array(
				'user_id' => $data['user_id']
			);
			$log_entry = WP_Logging::insert_log( $log_data, $log_meta );
		}
	} else {
		try {
			$result = Braintree_Transaction::sale(array(
			    'amount' => $data['price'],
			    'creditCard' => array(
			        'number' => $_POST['number'],
			        'cvv' => $_POST['cvv'],
			        'expirationMonth' => $_POST['exp_month'],
			        'expirationYear' => $_POST['exp_year']
			    ),
			    'customer' => array(
					'firstName' => $_POST['first_name'],
					'lastName' => $_POST['last_name'],
					'phone' => $_POST['phone'],
					'email' => $data['user_email']
				),
				'billing' => array(
					'firstName' => $_POST['first_name'],
					'lastName' => $_POST['last_name'],
					'streetAddress' => $_POST['billing_address'],
					'extendedAddress' => $_POST['billing_address2'],
					'locality' => $_POST['billing_city'],
					'region' => $_POST['billing_state'],
					'postalCode' => $_POST['billing_zip'],
					'countryCodeAlpha2' => $_POST['billing_country']
				),
			    'options' => array(
			        'submitForSettlement' => true
			    )
			));
		}
		catch(Exception $e){
			$log_data = array(
				'post_title'    => __( 'Failed to create transaction', 'rcp_braintree' ),
				'post_content'  =>  $e->getMessage(),
				'post_parent'   => 0,
				'log_type'      => 'gateway_error'
			);
			$log_meta = array(
				'user_id' => $data['user_id']
			);
			$log_entry = WP_Logging::insert_log( $log_data, $log_meta );
		}
	}


	if( isset( $result ) ){
		if ( $result->success ) {
			$payment_data = array(
				'transaction_id'   => isset( $result->transaction->id ) ? $result->transaction->id : '',
				'date'             => date( 'Y-m-d g:i:s', time() ),
				'subscription'     => $data['subscription_name'],
				'payment_type'     => __( 'Credit Card Subscription', 'rcp_braintree' ),
				'payer_email'      => $data['user_email'],
				'subscription_key' => $data['key'],
				'amount'           => $data['price'],
				'amount2'          => 0,
				'user_id'          => $data['user_id']
			);

		    $paid = true;
		    if ( $type == 'subscription' ) {
		    	$credit_card_token = $result->customer->creditCards[0]->token;
			}
		} else if ( $result->transaction ) {
		    wp_die( $result->message, $result->transaction->processorResponseCode );
		} else {
			$output = '<p><strong>'. __( 'Validation Errors', 'rcp_braintree' ) .':</strong></p>';
			foreach($result->errors->deepAll() AS $error) {
				$output .= $error->message . '<br />';
			}
			wp_die( $output, __( 'Validation Errors', 'rcp_braintree' ), array( 'response' => 200, 'back_link' => true ) );
		}
	}

	if ( $paid ) {
		if ( $data['new_user'] ) {
			// send an email to the admin alerting them of the registration
			wp_new_user_notification( $data['user_id'] );
			// log the new user in
			rcp_login_user_in( $data['user_id'], $data['user_name'], $data['post_data']['rcp_user_pass'] );
		}

		if ( $type == 'subscription' && isset( $credit_card_token ) ) {
			$subscription_id = rcp_get_subscription_id( $data['user_id'] );

			$subscription_result = Braintree_Subscription::create(array(
				'paymentMethodToken' => $credit_card_token,
				'planId' => $subscription_id
			));

			if ( $subscription_result->success ) {
				update_user_meta( $data['user_id'], 'rcp_recurring_payment_id', $subscription_result->subscription->id );
				update_user_meta( $data['user_id'], 'rcp_recurring', 'yes' );
			} else {
				$output = '<p><strong>'. __( 'Subscription Errors', 'rcp_braintree' ) .':</strong></p>';
				foreach($subscription_result->errors->deepAll() AS $error) {
					$output .= $error->code . ': ' . $error->message . '<br />';
				}
				wp_die( $output, __( 'Subscription Errors', 'rcp_braintree' ) );
			}
		}

		// set this user to active
		rcp_set_status( $data['user_id'], 'active' );

		// send out the notification email
		rcp_email_subscription_status( $data['user_id'], 'active' );

		rcp_insert_payment( $payment_data );
	} else {
		wp_die( __( 'An error occurred, please contact the site administrator: ', 'rcp_braintree' ) . get_bloginfo( 'admin_email' ) );
	}

	// redirect to the success page, or error page if something went wrong
	$redirect = get_permalink( $rcp_options['redirect'] );
	wp_redirect( $redirect ); exit;
}
add_action( 'rcp_gateway_braintree', 'rcp_process_braintree' );

/**************************
* misc
**************************/

function rcp_load_braintree_lib() {
	global $rcp_options;

	require_once RCP_BRAINTREE_DIR . '/braintree/lib/Braintree.php';

	if ( isset( $rcp_options['braintree_environment'] ) && $rcp_options['braintree_environment'] == 'sandbox' ) {
		Braintree_Configuration::environment('sandbox');
		Braintree_Configuration::merchantId( isset( $rcp_options['braintree_sandbox_merchantId'] ) ? trim( $rcp_options['braintree_sandbox_merchantId'] ) : null );
		Braintree_Configuration::publicKey( isset( $rcp_options['braintree_sandbox_publicKey'] ) ? trim( $rcp_options['braintree_sandbox_publicKey'] ) : null );
		Braintree_Configuration::privateKey( isset( $rcp_options['braintree_sandbox_privateKey'] ) ? trim( $rcp_options['braintree_sandbox_privateKey'] ) : null );
	} else {
		Braintree_Configuration::environment('production');
		Braintree_Configuration::merchantId( isset( $rcp_options['braintree_live_merchantId'] ) ? trim( $rcp_options['braintree_live_merchantId'] ) : null );
		Braintree_Configuration::publicKey( isset( $rcp_options['braintree_live_publicKey'] ) ? trim( $rcp_options['braintree_live_publicKey'] ) : null );
		Braintree_Configuration::privateKey( isset( $rcp_options['braintree_live_privateKey'] ) ? trim( $rcp_options['braintree_live_privateKey'] ) : null );
	}
}

function rcp_braintree_get_country_list() {
	$countries =array(
		'US' => 'United States',
		'GB' => 'United Kingdom (GB)',
		'AD' => 'Andorra',
		'AE' => 'United Arab Emirates',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AN' => 'Netherlands Antilles',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BN' => 'Brunei Darrussalam',
		'BO' => 'Bolivia',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos (keeling) Islands',
		'CD' => 'Congo, Democratic People\'s Republic',
		'CF' => 'Central African Republic',
		'CG' => 'Congo, Republic of',
		'CH' => 'Switzerland',
		'CI' => 'Cote d\'Ivoire',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CS' => 'Serbia and Montenegro',
		'CU' => 'Cuba',
		'CV' => 'Cap Verde',
		'CS' => 'Christmas Island',
		'CY' => 'Cyprus Island',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands (Malvina)',
		'FM' => 'Micronesia, Federal State of',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		'GA' => 'Gabon',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'South Georgia',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HK' => 'Hong Kong',
		'HM' => 'Heard and McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia/Hrvatska',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran (Islamic Republic of)',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JE' => 'Jersey',
		'JM' => 'Jamaica',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'Korea, Democratic People\'s Republic',
		'KR' => 'Korea, Republic of',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourgh',
		'LV' => 'Latvia',
		'LY' => 'Libyan Arab Jamahiriya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova, Republic of',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia',
		'ML' => 'Mali',
		'MM' => 'Myanmar',
		'MN' => 'Mongolia',
		'MO' => 'Macau',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'Mv' => 'Maldives',
		'MW' => 'malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'papua New Guinea',
		'PH' => 'Phillipines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'St. Pierre and Miquelon',
		'PN' => 'Pitcairn Island',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territories',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Reunion Island',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'St. Helena',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen Islands',
		'SK' => 'Slovak Republic',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'ST' => 'Sao Tome and Principe',
		'SV' => 'El Salvador',
		'SY' => 'Syrian Arab Republic',
		'SZ' => 'Swaziland',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TP' => 'East Timor',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UM' => 'US Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Holy See (City Vatican State)',
		'VC' => 'Saint Vincent and the Grenadines',
		'VE' => 'Venezuela',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (USA)',
		'VN' => 'Vietnam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna Islands',
		'WS' => 'Western Samoa',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'YU' => 'Yugoslavia',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
	return $countries;
}
