<?php

if ( function_exists( 'wpv_ae' ) ) {
    wpv_ae()->set_basename( true, __FILE__ );
} else {
    
    if ( !function_exists( 'wpv_ae' ) ) {
        // Create a helper function for easy SDK access.
        function wpv_ae()
        {
            global  $wpv_ae ;
            
            if ( !isset( $wpv_ae ) ) {
                // Include Freemius SDK.
                require_once AE_PRO_PATH . '/freemius/start.php';
                $wpv_ae = fs_dynamic_init( array(
                    'id'             => '8276',
                    'slug'           => 'anywhere-elementor',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_43d5f405cc0b87b346911199cff66',
                    'is_premium'     => true,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'edit.php?post_type=ae_global_templates',
                    'first-path' => 'edit.php?post_type=ae_global_templates',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wpv_ae;
        }
        
        // Init Freemius.
        // wpv_ae();
        // // Signal that SDK was initiated.
        // do_action( 'wpv_ae_loaded' );
    }

}

// EDD License Migration Code
function aep_fs_license_key_migration()
{
    if ( !wpv_ae()->has_api_connectivity() || wpv_ae()->is_registered() ) {
        // No connectivity OR the user already opted-in to Freemius.
        //return;
    }
    if ( 'pending' != get_option( 'aep_fs_migrated2fs', 'pending' ) ) {
        return;
    }
    // Get the license key from the previous eCommerce platform's storage.
    $license_key = get_option( 'ae_pro_license_key', '' );
    if ( empty($license_key) ) {
        // No key to migrate.
        return;
    }
    // Get the first 32 characters.
    $license_key = substr( $license_key, 0, 32 );
    try {
        $next_page = wpv_ae()->activate_migrated_license( $license_key );
    } catch ( Exception $e ) {
        update_option( 'aep_fs_migrated2fs', 'unexpected_error' );
        return;
    }
    
    if ( wpv_ae()->can_use_premium_code() ) {
        update_option( 'aep_fs_migrated2fs', 'done' );
        if ( is_string( $next_page ) ) {
            fs_redirect( $next_page );
        }
    } else {
        update_option( 'aep_fs_migrated2fs', 'failed' );
    }

}

add_action( 'admin_init', 'aep_fs_license_key_migration' );
// Admin Notice for missing license
function aep_fs_missing_license()
{
    
    if ( wpv_ae()->is_not_paying() ) {
        $url = admin_url( 'plugins.php?activate-ae=1' );
        $upgrade_url = wpv_ae()->get_upgrade_url();
        ?>
	<div class="error aep-license-error">
		<p>
			<strong>AnyWhere Elementor Pro</strong><br />
			You license key is missing or invalid. Please <a href="<?php 
        echo  $url ;
        ?>">activate</a> your license.<br/>
			Don't have a license yet? <a href="<?php 
        echo  $upgrade_url ;
        ?>">Get it Now</a>
		</p>
	</div>
	<?php 
    }

}

// add_action( 'admin_notices', 'aep_fs_missing_license' );