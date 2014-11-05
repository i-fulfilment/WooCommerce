<?php if ( !current_user_can( 'manage_woocommerce' ) ): ?>

<div style="    width: 630px;    margin: 0 auto;    margin-top: 140px;    background: #fff;    padding: 30px;    box-shadow: 0px 0px 5px #C7C7C7;">
	 You do not have permission to access this plugin. This functionaility is restricted to users that can manage woocommerce stores.
</div>

<?php else: ?>

<div class="wrap">
	<?php if($_GET['hide']) {
		update_option('ifl_hide_welcome', true ); // API not setup
	}

	 if(!get_option('ifl_hide_welcome') && !$_GET['hide']) :?>
	<div style=" width: 100%;    background: #D6D6D6;    height: 300px;    margin-bottom: 20px;    margin-top: 30px;    border-radius: 5px;    padding: 20px;    box-sizing: border-box;">
		<h1>Welcome to the I-Fulfilment WooCommerce Integration!</h1>
<h2>
	We are leaders in our field and we offer a number of services related to product storage and fulfilment. Principally we pick, pack, and ship on behalf of our customers who range from small start-ups to large multinational businesses.
</h2>

<h2>Our aim is to utilise our advanced fulfilment platform to provide a competitive advantage to our customers.
</h2><a class="button button-primary button-hero load-customize hide-if-no-customize" href="http://www.i-fulfilment.co.uk/" style=" float: left; margin-top: 30px; background: rgb(255, 92, 0); border-color: rgb(168, 61, 0); box-shadow: inset 0 1px 0 rgb(255, 182, 140),0 1px 0 rgba(0,0,0,.15); font-weight: bold;">Find out more about us</a>
<a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>&hide=1" style=" float: left; margin-top: 30px; background: rgb(160, 160, 160); border-color: rgb(99, 99, 99); box-shadow: inset 0 1px 0 rgb(209, 209, 209),0 1px 0 rgba(0,0,0,.15); font-weight: bold; margin-left: 20px;">Hide this box</a>
</div>
<?php endif; ?>
	<div id="welcome-panel" class="welcome-panel">
		<div style=" height: 50px; ">
			<img src="http://www.i-fulfilment.co.uk/img/i-fulfilment-logo.jpg" alt="I-Fulfilment" style=" height: 50px; margin-top: -15px;">
			<a class="button button-primary button-hero load-customize hide-if-no-customize" href="https://blade.i-fulfilment.co.uk/" style=" float: right; margin-top: -10px; background: rgb(255, 92, 0); border-color: rgb(168, 61, 0); box-shadow: inset 0 1px 0 rgb(255, 182, 140),0 1px 0 rgba(0,0,0,.15); font-weight: bold;">
			Open Blade IMS </a>
		</div>
	</div>
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div class="postbox-container" style="width:50%;">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox " style=" background: rgb(255, 92, 0); border-radius: 7px; ">
						<div class="inside" style=" /* background: red; */ ">
							<div class="main" style=" margin-top: 47px; color: #fff; ">
								<div style="width:50%; border-right: 1px solid #FFAE77;height: 171px;box-sizing: border-box;float: left;/* padding-left: 10px; */text-align: center;">
									<h2 style=" color: #fff; font-weight: bold; font-size: 30px; ">Techincal Support</h2>
									<div style=" margin-top: 40px; ">
										<b>Call us:</b> +44 (01425) 200 210
									</div>
									<div style=" margin-top: 20px; ">
										<b>Email us:</b> support.team@i-fulfilment.co.uk
									</div>
								</div>
								<div style="width: 50%; height: 205px;box-sizing: border-box;float: left;padding-left: 20px;text-align: center;">
									<h2 style=" color: #fff; font-weight: bold; font-size: 30px; ">Fulfilment Support</h2>
									<div style=" margin-top: 40px; ">
										<b>Call us:</b> +44 (01425) 200 200
									</div>
									<div style=" margin-top: 20px; ">
										<b>Email us:</b> sales@i-fulfilment.co.uk
									</div>
								</div>
								<div class="clear">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<h3 style='border-bottom:1px solid #eee;'><span> Integration Overview </span></h3>
						<div class="inside">
							<div class="main" style="height: 204px;">
								<input type="hidden" name="option_page" value="super-settings-group"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="455d545277"><input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=ifulfilment/admin.php">
								<table class="form-table">
								<tbody>
								<tr valign="top">
									<th scope="row">
										 Integration Status
									</th>
									<td>
										<div style="border-radius: 5px;padding: 5px;background:<?php if( get_option( 'ifl_integration_status' ) == 'Good') { echo "rgba(0, 0, 0, 0.08);"; } else { echo "rgb(255, 139, 139);";  } ?> text-align: center;color: #2E2E2E;font-weight: bold;border: 1px solid #FFFFFF;">
											<?php echo get_option( 'ifl_integration_status' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										 Wordpress Status
									</th>
									<td>
										<div style="border-radius: 5px;padding: 5px; background:<?php if( get_option( 'ifl_health_check' ) == 'Good') { echo "rgba(0, 0, 0, 0.08);"; } else { echo "rgb(255, 139, 139);";  } ?> text-align: center;color: #3C3C3C;font-weight: bold;/* border: 1px solid #647C00; */">
											<?php echo get_option( 'ifl_health_check' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">
										WooCommerce Status
									</th>
									<td>
										<div style="border-radius: 5px;padding: 5px; background:<?php if( get_option( 'ifl_woocommerce_status' ) == 'Good') { echo "rgba(0, 0, 0, 0.08);"; } else { echo "rgb(255, 139, 139);";  } ?> text-align: center;color: #474747;font-weight: bold;/* border: 1px solid #647C00; */">
											<?php echo get_option( 'ifl_woocommerce_status' ); ?>
										</div>
									</td>
								</tr>
								</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="postbox-container">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox ">
						<h3 style='border-bottom:1px solid #eee;'><span> Technical Breakdown </span></h3>
						<div class="inside">
							<div class="main">
								<textarea style=" width: 100%; height: 200px;"><?php

								$order_statuses = wc_get_order_statuses();

								// Defaults
								$key = 'NOT_SET';
								$secret = 'NOT_SET';

								// Loop over all the users to find the API key account
								foreach(get_users() as $user){

									// Check this user has WooCommerce details
									if($user->allcaps['manage_woocommerce'] == true) {

										$key = get_user_meta($user->data->ID, 'woocommerce_api_consumer_key');
										$secret = get_user_meta($user->data->ID, 'woocommerce_api_consumer_secret');

										// See if we found the details
										if($key && $secret) {

												// We did! Lets break out of the loop now.
												break;
										}
									}
								}

								 echo json_encode(
									array(
										'api_key' => $key,
										'api_token' => $secret,
										'checks' => array(
											'ifl_woocommerce_status' => get_option('ifl_woocommerce_status'),
											'ifl_integration_status' => get_option('ifl_integration_status'),
											'ifl_health_check' => get_option('ifl_health_check'),
										),
										'shipping_methods' => $shipping,
										'order_statuses' => $order_statuses,
										'store' => array(
											'plugins' => get_option('active_plugins'),
											'woocommerce_version' => get_option('woocommerce_version'),
											'wordpress_version' => get_bloginfo('version')
										),
									)) ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- wpbody -->
			<div class="clear">
			</div>
		</div>
		<!-- wpcontent -->
		<div class="clear">
		</div>
	</div>
</div>
<?php endif; ?>
