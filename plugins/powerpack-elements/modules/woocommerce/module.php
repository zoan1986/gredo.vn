<?php
/**
 * PowerPack WooCommerce Module.
 *
 * @package PowerPack
 */

namespace PowerpackElements\Modules\Woocommerce;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class Module extends Module_Base {

	/**
	 * Module is active or not.
	 *
	 * @since 1.3.3
     *
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_active() {
		return function_exists( 'WC' );
	}

	/**
	 * Get Module Name.
	 *
	 * @since 1.3.3
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'woocommerce';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 1.3.3
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Woo_Add_To_Cart',
			'Woo_Categories',
			'Woo_Cart',
			'Woo_Checkout',
			'Woo_Products',
		];
	}

	/**
	 * WooCommerce hook.
	 *
	 * @since 1.3.3
	 * @access public
	 */
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * Query Offset Fix.
	 *
	 * @since 1.3.3
	 * @access public
	 * @param object $query query object.
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * Query Found Posts Fix.
	 *
	 * @since 1.3.3
	 * @access public
	 * @param int    $found_posts found posts.
	 * @param object $query query object.
	 * @return int string
	 */
	public function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}

	/**
	 * Load Quick View Product.
	 *
	 * @since 1.3.3
	 * @param array $localize localize.
	 * @access public
	 */
	public function js_localize( $localize ) {

		$localize['is_cart']           = is_cart();
		$localize['is_single_product'] = is_product();
		$localize['view_cart']         = esc_attr__( 'View cart', 'power-pack' );
		$localize['cart_url']          = apply_filters( 'pp_woocommerce_add_to_cart_redirect', wc_get_cart_url() );

		return $localize;
	}

	/**
	 * Load Quick View Product.
	 *
	 * @since 1.3.3
	 * @access public
	 */
	public function load_quick_view_product() {

		if ( ! isset( $_REQUEST['product_id'] ) ) {
			die();
		}

		$this->quick_view_content_actions();

		$product_id = intval( $_REQUEST['product_id'] );

		// echo $product_id;
		// die();
		// set the main wp query for the product.
		wp( 'p=' . $product_id . '&post_type=product' );

		ob_start();

		// load content template.
		include POWERPACK_ELEMENTS_PATH . 'modules/woocommerce/templates/quick-view-product.php';

		echo ob_get_clean();

		die();
	}

	/**
	 * Quick view actions
	 */
	public function quick_view_content_actions() {

		add_action( 'pp_woo_quick_view_product_image', 'woocommerce_show_product_sale_flash', 10 );
		// Image.
		add_action( 'pp_woo_quick_view_product_image', array( $this, 'quick_view_product_images_markup' ), 20 );

		// Summary.
		add_action( 'pp_woo_quick_view_product_summary', array( $this, 'quick_view_product_content_structure' ), 10 );
	}

	/**
	 * Quick view product images markup.
	 */
	function quick_view_product_images_markup() {

		include POWERPACK_ELEMENTS_PATH . 'modules/woocommerce/templates/quick-view-product-image.php';
	}

	/**
	 * Quick view product content structure.
	 */
	function quick_view_product_content_structure() {

		global $product;

		$post_id = $product->get_id();

		$single_structure = apply_filters(
			'pp_quick_view_product_structure',
			array(
				'title',
				'ratings',
				'price',
				'short_desc',
				'meta',
				'add_cart',
			)
		);

		if ( is_array( $single_structure ) && ! empty( $single_structure ) ) {

			foreach ( $single_structure as $value ) {

				switch ( $value ) {
					case 'title':
						/**
						 * Add Product Title on single product page for all products.
						 */
						do_action( 'pp_quick_view_title_before', $post_id );
						woocommerce_template_single_title();
						do_action( 'pp_quick_view_title_after', $post_id );
						break;
					case 'price':
						/**
						 * Add Product Price on single product page for all products.
						 */
						do_action( 'pp_quick_view_price_before', $post_id );
						woocommerce_template_single_price();
						do_action( 'pp_quick_view_price_after', $post_id );
						break;
					case 'ratings':
						/**
						 * Add rating on single product page for all products.
						 */
						do_action( 'pp_quick_view_rating_before', $post_id );
						woocommerce_template_single_rating();
						do_action( 'pp_quick_view_rating_after', $post_id );
						break;
					case 'short_desc':
						do_action( 'pp_quick_view_short_description_before', $post_id );
						woocommerce_template_single_excerpt();
						do_action( 'pp_quick_view_short_description_after', $post_id );
						break;
					case 'add_cart':
						do_action( 'pp_quick_view_add_to_cart_before', $post_id );
						woocommerce_template_single_add_to_cart();
						do_action( 'pp_quick_view_add_to_cart_after', $post_id );
						break;
					case 'meta':
						do_action( 'pp_quick_view_category_before', $post_id );
						woocommerce_template_single_meta();
						do_action( 'pp_quick_view_category_after', $post_id );
						break;
					default:
						break;
				}
			}
		}

	}

	/**
	 * Single Product add to cart ajax request
	 *
	 * @since 1.1.0
	 *
	 * @return void.
	 */
	function add_cart_single_product_ajax() {
		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? sanitize_text_field( $_POST['quantity'] ) : 0;

		if ( $variation_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}
		die();
	}
	/**
	 * Constructer.
	 *
	 * @since 1.3.3
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		// In Editor Woocommerce frontend hooks before the Editor init.
		add_action( 'admin_action_elementor', [ $this, 'register_wc_hooks' ], 9 );

		/**
		 * Pagination Break.
		 *
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );

		add_filter( 'pp_js_localize', array( $this, 'js_localize' ) );

		// quick view ajax.
		add_action( 'wp_ajax_pp_woo_quick_view', array( $this, 'load_quick_view_product' ) );
		add_action( 'wp_ajax_nopriv_pp_woo_quick_view', array( $this, 'load_quick_view_product' ) );

		add_action( 'wp_ajax_pp_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );
		add_action( 'wp_ajax_nopriv_pp_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );
	}
}
