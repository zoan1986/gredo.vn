<?php
namespace PowerpackElements;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function register_modules() {
		$modules = [
			'query-post',
			'advanced-accordion',
			'advanced-menu',
			'advanced-tabs',
			'album',
			'breadcrumbs',
			'business-hours',
			'buttons',
			'caldera-forms',
			'contact-form-seven',
			'countdown',
			'counter',
			'divider',
			'flipbox',
			'headings',
			'gallery',
			'google-maps',
			'gravity-forms',
			'headings',
			'hotspots',
			'icon-list',
			'image-accordion',
			'image-comparison',
			'info-box',
			'info-list',
			'info-table',
			'instafeed',
			'link-effects',
			'logos',
			'modal-popup',
			'ninja-forms',
			'offcanvas-content',
			'onepage-nav',
			'posts',
			'pricing',
			'promo-box',
			'recipe',
			'scroll-image',
			'showcase',
			'tabbed-gallery',
			'team-member',
			'testimonials',
			'toggle',
			'table',
			'twitter',
			'video',
			'query-post',
			'woocommerce',
			'wpforms',
		];
        
        ksort($modules);
        
        //$modules = pp_get_enabled_modules();

        //$modules = pp_get_modules();

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	private function require_files() {
		require( POWERPACK_ELEMENTS_PATH . 'base/module-base.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}
}