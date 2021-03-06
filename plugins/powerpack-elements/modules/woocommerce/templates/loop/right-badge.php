<?php
/**
 * PowerPack WooCommerce Products - Sale Badge.
 *
 * @package PowerPack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $product;

$sale_badge_position = $settings['sale_badge_position'];
$featured_badge_position = $settings['featured_badge_position'];
$top_rating_badge_position = $settings['top_rating_badge_position'];
$best_selling_badge_position = $settings['best_selling_badge_position'];
$badge_text = __( 'Sale!', 'power-pack' );

//echo '<div class="pp-right-badge-wrap">';
if( 'right' === $sale_badge_position ) {

	if ( '' !== $settings['sale_badge_custom_text'] ) {

		$sale_price = $product->get_sale_price();

		if ( $sale_price ) {
			$badge_text = $settings['sale_badge_custom_text'];

			$regular_price = $product->get_regular_price();
			$percent_sale  = round( ( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ), 0 );
			$badge_text     = $badge_text ? $badge_text : '-[value]%';
			$badge_text     = str_replace( '[value]', $percent_sale, $badge_text );
		}
	};


	?>
	<?php if ( $product->is_on_sale() ) : ?>

		<?php echo apply_filters( 'pp_woo_products_sale_flash', '<div class="pp-right-badge-wrap"><span class="pp-right-badge pp-sale-badge">' . esc_html( $badge_text ) . '</span></div>', $post, $product ); ?>

	<?php
	endif;
 
}
if( 'right' === $featured_badge_position ) {

	$badge_text = __( 'New', 'power-pack' );

	if ( '' !== $settings['featured_badge_custom_text'] ) {
		$badge_text = $settings['featured_badge_custom_text'];
	}

	if ( $product->is_featured() ) : ?>

		<?php echo apply_filters( 'pp_woo_products_featured_flash', '<div class="pp-right-badge-wrap"><span class="pp-right-badge pp-featured-badge">' . esc_html( $badge_text ) . '</span></div>', $post, $product ); ?>

	<?php
	endif;

}
if( 'right' === $top_rating_badge_position ) {

	$badge_text = __( 'Top Rated', 'power-pack' );
	
	if ( '' !== $settings['top_rating_badge_custom_text'] ) {
		$badge_text = $settings['top_rating_badge_custom_text'];
	}

	if ( $this->is_top_rated_product($product->get_id()) ) {
		echo '<div class="pp-right-badge-wrap"><span class="pp-right-badge pp-top-rated-badge">' . esc_html( $badge_text ) . '</span></div>';
	}
 
}
if( 'right' === $best_selling_badge_position ) {
	$badge_text = __( 'Best Selling', 'power-pack' );

	if ( '' !== $settings['best_selling_badge_custom_text'] ) {
		$badge_text = $settings['best_selling_badge_custom_text'];
	}

	if( $this->is_best_selling_product($product->get_id()) ) { 
		echo '<div class="pp-right-badge-wrap"><span class="pp-right-badge pp-best-selling-badge">' . esc_html( $badge_text ) . '</span></div>';
	}

}
//echo '</div>';