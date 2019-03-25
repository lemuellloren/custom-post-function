<?php
/**
 * "Role Discounts" page related functionality
 */
class MLM_Options {
	/**
	 * Holder for internal roles (created by this plugin)
	 * @var array
	 */
	public $local_roles	= array();

	/**
	 * Simple class constructor
	 */
	function __construct() {
		// Add menu page, register settings and alter the query (if needed)
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'pre_get_posts', array( $this, 'alter_query' ), 10, 1 );

		// Set internal roles
		$this->local_roles	= get_option( 'mlmrd_roles', array() );
	}

	/**
	 * Add "Role Discounts" menu page under WooCommerce
	 */
	function add_menu_page() {
		// Add page
		add_submenu_page( 'dashboard-page', __( 'Role Discounts', MLM_PLUGIN_NAME ), __( 'Role Discounts', MLM_PLUGIN_NAME ), 'manage_options', 'role-discounts', array( $this, 'content' ) );
	}

	/**
	 * Outputs content for the menu page
	 * @return void
	 */
	function content() { 
		// Check if we need to do something
		if( isset( $_REQUEST['mlmrd_action'] ) ) {
			// Define the action
			$action	= $_REQUEST['mlmrd_action'];

			// Do we need to update settings
			if( $action == 'settings' ) {
				// Update settings
				$this->submit_settings( $_POST['mlmrd_settings'] );

				// Show message
				$this->show_message( __( 'Configuration has been saved', MLM_PLUGIN_NAME ) );
			}
			// Do we need to update or create roles
			elseif( $action == 'new_role' OR $action == 'manage' ) {
				// Get role name and slug
				$role_name	= $_POST['role_name'];
			   // $role_slug	= sanitize_key( $role_name );
	           $role_slug	= $_POST['role_slug'];
			   
			   
				// Set role settings
				$new_role 	= array( 
					$role_slug => array(
						'discount'			=> $_POST['role_discount'],
						'discount_amount'	=> $_POST['role_discount_amount']
					)
				);

				// Do action specific things
				if( $action == 'new_role' ) {
					// Add new role
					$this->add_role( $role_name, $role_slug, $new_role );

					// Show message
					$this->show_message( __( 'Role has been successfully added.', MLM_PLUGIN_NAME ) );
				}
				else {
					// Check if we need to delete the discount
					$delete_discount	= ( isset( $_POST['remove_discount'] ) && $_POST['remove_discount'] == 'Y' ) ? TRUE : FALSE;

					// Update role
					$this->update_role( $role_name, $role_slug, $new_role, $delete_discount );

					// Show message
					$this->show_message( __( 'Role has been successfully edited.', MLM_PLUGIN_NAME ) );
				}
			}
			// Do we need to delete role
			elseif( $action == 'delete' ) {
				// Set role
				$role	= $_GET['role'];

				// Delete role
				$this->delete_role( $role );

				// Show message
				$this->show_message( __( 'Role has been deleted', MLM_PLUGIN_NAME ) );
			}
		}

		// Get WP roles
		global $wp_roles;
		?>

<div class="wrap">
	<?php screen_icon( 'users' ); ?>
	<h2><?php _e( 'Roles Discount', MLM_PLUGIN_NAME ) ?></h2>
	<h3><?php _e( 'All Roles', MLM_PLUGIN_NAME ) ?></h3>

	<form action="admin.php?page=role-discounts" method="post">
		<input type="hidden" name="mlmrd_action" value="manage">
		<table class="widefat wp-list-table fixed woord-table">
			<thead>
				<tr>
					<th class="counter"><?php _e( '#', MLM_PLUGIN_NAME ) ?></th>
					<th><?php _e( 'Role name', MLM_PLUGIN_NAME ) ?></th>
					<th><?php _e( 'Role slug', MLM_PLUGIN_NAME ) ?></th>
					<th><?php _e( 'Discount', MLM_PLUGIN_NAME ) ?></th>
					<th class="actions"><?php _e( 'Actions', MLM_PLUGIN_NAME ) ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $counter=0; foreach( $wp_roles->get_names() as $value => $name ) : $counter++; ?>
				<?php
				// Get current role discount
				$role_discount	= $this->get_discount( $value, 'array' );

				// Set default settings
				if( ! $role_discount )
					$role_discount = array( 'discount_amount' => '', 'discount' => '' );

				?>
				<tr class="<?php echo ( $counter % 2 ) ? 'alt' : 'even' ?>">
					<td><?php echo $counter ?>.</td>
					<td><?php echo apply_filters( 'title', $name ) ?></td>
					<td><?php echo $value; ?></td>
					<td>
						<?php if( isset( $_GET['manage'] ) && $_GET['manage'] == $value ) : ?>
						<input type="hidden" name="role_slug" value="<?php echo $value ?>">
						<input type="hidden" name="role_name" value="<?php echo $name ?>">
						<input type="text" size="5" value="<?php echo $role_discount['discount'] ?>" name="role_discount">
						<select name="role_discount_amount" id="discount_amount">
							<option value="amount" <?php selected( 'amount', $role_discount['discount'] ) ?>><?php echo get_woocommerce_currency_symbol() ?></option>
							<option value="percent" <?php selected( 'percent', $role_discount['discount_amount'] ) ?>>%</option>
						</select> 
						<?php if( $role_discount['discount'] != '' ) : ?>
						<label><input type="checkbox" name="remove_discount" value="Y"> <?php _e( 'Remove discount', MLM_PLUGIN_NAME ) ?></label>
						<?php endif; ?>
						<?php else : ?>
							<?php if( $this->get_discount( $value ) ) : ?>
								<?php echo $this->get_discount( $value ) ?>
							<?php else : ?>
								<em><?php _ex( '-', 'role discount not set', MLM_PLUGIN_NAME ) ?></em>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if( isset( $_GET['manage'] ) && $_GET['manage'] == $value ) : ?>
						<?php submit_button( __( 'Save', MLM_PLUGIN_NAME ), 'primary', 'submit', false ) ?>
						<a href="<?php echo admin_url( 'admin.php?page=role-discounts' ) ?>" class="button"><?php _e( 'Cancel', MLM_PLUGIN_NAME ) ?></a>
						<?php else : ?>
						<a href="<?php echo admin_url( 'admin.php?page=role-discounts&manage=' . $value )  ?>"><?php _e( 'Manage', MLM_PLUGIN_NAME ) ?></a>
						<?php if( in_array( $value, $this->local_roles ) ) : ?>
						&mdash; <a href="<?php echo admin_url( 'admin.php?page=role-discounts&mlmrd_action=delete&role=' . $value )  ?>"><?php _e( 'Delete Role', MLM_PLUGIN_NAME ) ?></a>
						<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</form>

	<form action="admin.php?page=role-discounts" method="post">
		<input type="hidden" name="mlmrd_action" value="new_role">
		<h3><?php _e( 'Add new roles', MLM_PLUGIN_NAME ) ?></h3>
		<table class="widefat wp-list-table fixed woord-table">
			<thead>
				<tr>
					<th><?php _e( 'Role name', MLM_PLUGIN_NAME ) ?></th>
					<th><?php _e( 'Discount', MLM_PLUGIN_NAME ) ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" name="role_name"></td>
					<td>
						<input type="text" name="role_discount" size="5">
						<select name="role_discount_amount" id="role_discount_amount">
							<option value="amount"><?php echo get_woocommerce_currency_symbol() ?></option>
							<option value="percent">%</option>
						</select>
					</td>
					<td><?php submit_button( __( 'Add new', MLM_PLUGIN_NAME ), 'primary', 'submit', false ) ?></td>
				</tr>
			</tbody>
		</table>
	</form>

	<form action="admin.php?page=role-discounts" method="post">
		<h3><?php _e( 'Configuration', MLM_PLUGIN_NAME ) ?></h3>
		<p>
			<label><input type="checkbox" name="mlmrd_settings[exclude_role_coupons]" value="Y" <?php checked( 'Y', $this->get_option( 'exclude_role_coupons' ) ) ?>> <?php _e( 'Exclude automatically generated coupons from the list <em>(WooCommerce -> Coupons)</em>', MLM_PLUGIN_NAME ) ?></label>
		</p>
		<?php $mlmrd_options = get_option( 'mlmrd', array() ); ?>
		<?php if( empty( $mlmrd_options ) ) : ?>
		<p>
			<label><?php _e( 'Secret word', MLM_PLUGIN_NAME ) ?>: <input type="text" name="mlmrd_settings[hashword]" value="<?php echo $this->get_option( 'hashword', 'secret' ) ?>"> <em><?php _e( 'This will be used for coupon code generation.', MLM_PLUGIN_NAME ) ?></em></label>
		</p>
		<?php else : ?>
		<p><em><?php _e( 'To change the secret word, you have to delete remove all discounts.', MLM_PLUGIN_NAME ) ?></em></p>
		<?php endif; ?>
		<input type="hidden" name="mlmrd_action" value="settings">
		<?php submit_button( __( 'Save', MLM_PLUGIN_NAME ) ) ?>
	</form>
</div>
		<?php
	}

	/**
	 * Exclude the automatically generated coupons if necessary
	 * @param  WP_Query $query WP Query
	 * @return void
	 */
	function alter_query( $query ) {
		// If we are on admin dashboard
		if( is_admin() ) {
			// If we need to exclude 
			if( $this->get_option( 'exclude_role_coupons' ) == 'Y' ) {
				// Set excludes
				$query->set( 'post__not_in', array_keys( get_option( 'mlm_ecom_coupons', array() ) ) );
			}
		}
	}

	/**
	 * Add new role
	 * @param string $name Role name
	 * @param string $slug Role slug
	 * @param array  $args Role options
	 */
	function add_role( $name, $slug, $args ) {
		// Add role
		add_role( $slug, $name );

		// Update local roles
		if( !isset( $this->local_roles[$slug] ) )
			$this->local_roles[]	= $slug;

		// Update roles
		update_option( 'mlmrd_roles', $this->local_roles );

		// Save new role to options
		update_option( 'mlmrd', wp_parse_args( $args, get_option( 'mlmrd', array() ) ) );

		// Add new coupon through action
		do_action( 'mlm_ecom_new_role', $name, $slug, $args[ $slug ] );
	}

	/**
	 * Update role
	 * @param  string  $name   Role name
	 * @param  string  $slug   Role slug
	 * @param  array   $args   Role options
	 * @param  boolean $delete To delete the discount or not
	 * @return void
	 */
	function update_role( $name, $slug, $args, $delete = FALSE ) {
		// Get current discounts and merge with new ones
		$all_discounts		= get_option( 'mlmrd', array() );
		$updated_discounts	= wp_parse_args( $args, $all_discounts );

		if( $delete === FALSE ) {
			// Update (or create if does not exist) coupon through action
			do_action( 'mlm_ecom_updated_role', $name, $slug, $args[$slug] );
		}
		else {
			// Remove discount
			unset( $updated_discounts[ $slug ] );

			// Remove coupon
			do_action( 'mlm_ecom_removed_role', $slug );
		}

		// Save new discounts
		update_option( 'mlmrd', $updated_discounts );
	}

	/**
	 * Delete role
	 * @param  string $slug Role slug
	 * @return void
	 */
	function delete_role( $slug ) {
		// Check if this is a valid role
		if( ! in_array( $slug, $this->local_roles ) ) return FALSE;

		// Remove role
		remove_role( $slug );

		// Update local roles
		$this->local_roles 	= array_diff( $this->local_roles, array( $slug ) );

		// Update option
		update_option( 'mlmrd_roles', $this->local_roles );

		// Delete coupon
		do_action( 'mlm_ecom_removed_role', $slug );
	}

	/**
	 * Save settings
	 * @param  array $settings New settings
	 * @return void
	 */
	function submit_settings( $settings ) {
		// Set default
		if( !isset( $settings['exclude_role_coupons'] ) )
			$settings['exclude_role_coupons']	= 'N';

		// Update
		update_option( 'mlmrd_settings', wp_parse_args( $settings, $this->get_option() ) );
	}

	/**
	 * Get discount
	 * @param  string $role    Role slug
	 * @param  string $display Format
	 * @return string          Role discount
	 */
	function get_discount( $role, $display = 'display' ) {
		// Get all discounts
		$all_discounts	= get_option( 'mlmrd', array() );

		// Do not proceed if discount is not set
		if( !isset( $all_discounts[ $role ] ) ) return FALSE;

		// Check format
		if( $display == 'display' ) {
			// Set char
			$discount_type	= ( $all_discounts[$role]['discount_amount'] == 'percent' ) ? '%' : get_woocommerce_currency_symbol();

			// Return 
			return sprintf( '%s %s', $all_discounts[$role]['discount'], $discount_type );
		}
		else {
			return $all_discounts[$role];
		}
	}

	/**
	 * Fetches all of the settings or single one of them
	 * @param  string $option  Option name
	 * @param  string $default Default value
	 * @return string          Option value
	 */
	function get_option( $option = NULL, $default = NULL ) {
		// Get all options
		$options	= get_option( 'mlmrd_settings', array( 'exclude_role_coupons' => 'N', 'hashword' => 'secret' ) );

		// If we need all
		if( $option === NULL ) {
			// Return all
			return $options;
		}
		else {
			// Check if we need to return the default
			if( !isset( $options[$option] ) && $default !== NULL ) {
				return $default;
			}
			// Or the one that is set
			else return $options[$option];
		}
	}

	/**
	 * Show message
	 * @param  string $message Message
	 * @return void
	 */
	function show_message( $message ) {
		echo '<div class="updated fade">';
		echo apply_filters( 'the_content', $message );
		echo '</div>';
	}
}