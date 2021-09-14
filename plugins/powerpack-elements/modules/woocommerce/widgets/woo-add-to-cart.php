<?php
/**
 * PowerPack WooCommerce Add To Cart Button.
 *
 * @package PowerPack
 */

namespace PowerpackElements\Modules\Woocommerce\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Woo_Add_To_Cart.
 */
class Woo_Add_To_Cart extends Powerpack_Widget {

	/**
	 * Retrieve Widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pp-woo-add-to-cart';
	}

	/**
	 * Retrieve Widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Woo - Add To Cart', 'power-pack');
	}

	/**
	 * Retrieve Widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ppicon-woo-add-to-cart power-pack-admin-icon';
	}

    /**
	 * Retrieve the list of categories the Woo Add to Cart widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

	/**
	 * Get Script Depends.
	 *
	 * @access public
	 *
	 * @return array scripts.
	 */
	public function get_script_depends() {
		return [ 'pp-woocommerce' ];
	}

    /**
	 * Retrieve the list of styles the Add to Cart widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_style_depends() {
        return [
            'pp-woocommerce'
        ];
    }

	/**
	 * Register controls.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/* Product Control */
		$this->register_content_product_controls();
		/* Button Control */
		$this->register_content_button_controls();
		/* Button Style */
		$this->register_style_button_controls();
	}

	public function unescape_html( $safe_text, $text ) {
		return $text;
	}

	/**
	 * Register Content Product Controls.
	 *
	 * @access protected
	 */
	protected function register_content_product_controls() {

		$this->start_controls_section(
			'section_product_field',
			[
				'label' => __( 'Product', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_control(
				'product_id',
				[
					'label'     => __( 'Select Product', 'power-pack' ),
					'type'      => 'pp-query-posts',
					'post_type' => 'product',
				]
			);

		$this->add_control(
			'show_quantity',
			[
				'label' => __( 'Show Quantity', 'power-pack' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'power-pack' ),
				'label_on' => __( 'Show', 'power-pack' ),
			]
		);

        $this->add_control(
            'quantity',
            [
                'label'   => __( 'Quantity', 'power-pack' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );

        $this->add_control(
            'enable_redirect',
            [
                'label'        => __( 'Auto Redirect', 'power-pack' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'description'  => __( 'Enable this option to redirect cart page after the product gets added to cart', 'power-pack' ),
                'condition' => [
                    'show_quantity' => '',
                ],
            ]
        );

		$this->end_controls_section();
	}

	/**
	 * Register Content Button Controls.
	 *
	 * @access protected
	 */
	protected function register_content_button_controls() {
		$this->start_controls_section(
			'section_button_field',
			[
				'label' => __( 'Button', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
			$this->add_control(
				'btn_text',
				[
					'label'   => __( 'Text', 'power-pack' ),
					'type'    => Controls_Manager::TEXT,
					'default' => __( 'Add to cart', 'power-pack' ),
					'dynamic' => [
						'active' => true,
					],
				]
			);
			$this->add_responsive_control(
				'align',
				[
					'label'        => __( 'Alignment', 'power-pack' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => __( 'Left', 'power-pack' ),
							'icon'  => 'fa fa-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'power-pack' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'power-pack' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'power-pack' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'prefix_class' => 'pp-add-to-cart%s-align-',
					'default'      => 'left',
				]
			);
			$this->add_control(
				'btn_size',
				[
					'label'   => __( 'Size', 'power-pack' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'sm',
					'options' => [
						'xs' => __( 'Extra Small', 'power-pack' ),
						'sm' => __( 'Small', 'power-pack' ),
						'md' => __( 'Medium', 'power-pack' ),
						'lg' => __( 'Large', 'power-pack' ),
						'xl' => __( 'Extra Large', 'power-pack' ),
					],
				]
			);
			$this->add_responsive_control(
				'btn_padding',
				[
					'label'      => __( 'Padding', 'power-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_icon',
				[
					'label'       => __( 'Icon', 'power-pack' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => true,
					'default'     => 'fa fa-shopping-cart',
				]
			);
			$this->add_control(
				'btn_icon_align',
				[
					'label'     => __( 'Icon Position', 'power-pack' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'left',
					'options'   => [
						'left'  => __( 'Before', 'power-pack' ),
						'right' => __( 'After', 'power-pack' ),
					],
					'condition' => [
						'btn_icon!' => '',
					],
				]
			);
			$this->add_control(
				'btn_icon_indent',
				[
					'label'     => __( 'Icon Spacing', 'power-pack' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'condition' => [
						'btn_icon!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
		$this->end_controls_section();
	}

	/**
	 * Register Style Button Controls.
	 *
	 * @access protected
	 */
	protected function register_style_button_controls() {

		$this->start_controls_section(
			'section_design_button',
			[
				'label' => __( 'Button', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .pp-button',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->start_controls_tabs( 'button_tabs_style' );

			$this->start_controls_tab(
				'button_normal',
				[
					'label' => __( 'Normal', 'power-pack' ),
				]
			);

				$this->add_control(
					'button_color',
					[
						'label'     => __( 'Text Color', 'power-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name'           => 'button_background_color',
						'label'          => __( 'Background Color', 'power-pack' ),
						'types'          => [ 'classic', 'gradient' ],
						'selector'       => '{{WRAPPER}} .pp-button',
						'fields_options' => [
							'color' => [
								'scheme' => [
									'type'  => Scheme_Color::get_type(),
									'value' => Scheme_Color::COLOR_4,
								],
							],
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name'        => 'button_border',
						'placeholder' => '',
						'default'     => '',
						'selector'    => '{{WRAPPER}} .pp-button',
					]
				);

				$this->add_control(
					'border_radius',
					[
						'label'      => __( 'Border Radius', 'power-pack' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .pp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'button_box_shadow',
						'selector' => '{{WRAPPER}} .pp-button',
					]
				);

				$this->add_control(
					'view_cart_color',
					[
						'label'     => __( 'View Cart Text Color', 'power-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .added_to_cart' => 'color: {{VALUE}};',
						],
                        'condition' => [
                            'show_quantity' => '',
                        ],
					]
				);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'button_hover',
				[
					'label' => __( 'Hover', 'power-pack' ),
				]
			);

				$this->add_control(
					'button_hover_color',
					[
						'label'     => __( 'Text Color', 'power-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-button:focus, {{WRAPPER}} .pp-button:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name'           => 'button_background_hover_color',
						'label'          => __( 'Background Color', 'power-pack' ),
						'types'          => [ 'classic', 'gradient' ],
						'selector'       => '{{WRAPPER}} .pp-button:focus, {{WRAPPER}} .pp-button:hover',
						'fields_options' => [
							'color' => [
								'scheme' => [
									'type'  => Scheme_Color::get_type(),
									'value' => Scheme_Color::COLOR_4,
								],
							],
						],
					]
				);

				$this->add_control(
					'button_border_hover_color',
					[
						'label'     => __( 'Border Hover Color', 'power-pack' ),
						'type'      => Controls_Manager::COLOR,
						'scheme'    => [
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						],
						'condition' => [
							'button_border_border!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .pp-button:focus, {{WRAPPER}} .pp-button:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'hover_animation',
					[
						'label' => __( 'Hover Animation', 'power-pack' ),
						'type'  => Controls_Manager::HOVER_ANIMATION,
					]
				);

				$this->add_control(
					'view_cart_hover_color',
					[
						'label'     => __( 'View Cart Text Color', 'power-pack' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .added_to_cart:hover' => 'color: {{VALUE}};',
						],
                        'condition' => [
                            'show_quantity' => '',
                        ],
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render Woo Product Grid output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$node_id  = $this->get_id();
		$product  = false;

		if ( ! empty( $settings['product_id'] ) ) {
			$product_data = get_post( $settings['product_id'] );
		}

		$product = ! empty( $product_data ) && in_array( $product_data->post_type, [ 'product', 'product_variation' ] ) ? wc_setup_product_data( $product_data ) : false;

        if ( $product ) {
            if ( 'yes' === $settings['show_quantity'] ) {
                $this->render_form_button( $product );
            } else {
                $this->render_ajax_button( $product );
            }
		} elseif ( current_user_can( 'manage_options' ) ) {
            $class = implode(
				' ', array_filter(
					[
						'button',
						'pp-button',
				        'elementor-button',
				        'elementor-size-' . $settings['btn_size'],
						'elementor-animation-' . $settings['hover_animation'],
					]
				)
			);
			$this->add_render_attribute(
				'button', [ 'class' => $class ]
			);
            
            echo '<div class="pp-woo-add-to-cart">';
            echo '<a ' . $this->get_render_attribute_string( 'button' ) . '>';
			echo __( 'Please select the product', 'power-pack' );
            echo '</a>';
            echo '</div>';
        }
	}

	/**
	 * @param \WC_Product $product
	 */
	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();
		$atc_html = '';
        
        if ( $product ) {

			$product_id   = $product->get_id();
			$product_type = $product->get_type();

			$class = [
				'pp-button',
				'elementor-button',
				'elementor-animation-' . $settings['hover_animation'],
				'elementor-size-' . $settings['btn_size'],
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			];

			if ( 'yes' === $settings['enable_redirect'] ) {
				$class[] = 'pp-redirect';
			}

			$this->add_render_attribute(
				'button', [
					'rel'             => 'nofollow',
					'href'            => $product->add_to_cart_url(),
					'data-quantity'   => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product_id,
					'class'           => $class,
				]
			);

			$this->add_render_attribute(
				'icon-align',
				'class',
				[
					'pp-atc-icon-align',
					'elementor-align-icon-' . $settings['btn_icon_align'],
				]
			);

			$atc_html     .= '<div class="pp-woo-add-to-cart">';
				$atc_html .= '<a ' . $this->get_render_attribute_string( 'button' ) . '>';
				$atc_html .= '<span class="pp-atc-content-wrapper">';

                if ( ! empty( $settings['btn_icon'] ) ) :
				$atc_html     .= '<span ' . $this->get_render_attribute_string( 'icon-align' ) . '">';
					$atc_html .= '<i class="' . $settings['btn_icon'] . '" aria-hidden="true"></i>';
				$atc_html     .= '</span>';
				endif;

				$atc_html .= '<span class="pp-atc-btn-text">' . $settings['btn_text'] . '</span>';
				$atc_html .= '</span>';
				$atc_html .= '</a>';
			$atc_html     .= '</div>';

			echo $atc_html;
		}
	}

	private function render_form_button( $product ) {
		$settings = $this->get_settings_for_display();
        
        echo '<div class="pp-woo-add-to-cart">';
		if ( ! $product && current_user_can( 'manage_options' ) ) {

			return;
		}

		$text_callback = function() {
			ob_start();
			$this->render_text();

			return ob_get_clean();
		};

		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', [ $this, 'unescape_html' ], 10, 2 );

		ob_start();
		woocommerce_template_single_add_to_cart();
		$form = ob_get_clean();
		$form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button elementor-size-' . $settings["btn_size"] . ' pp-button', $form );
		echo $form;

		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', [ $this, 'unescape_html' ] );
        echo '</div>';
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['btn_icon_align'],
				],
			],
			'btn_text' => [
				'class' => 'elementor-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'btn_text', 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['btn_icon'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<i class="<?php echo esc_attr( $settings['btn_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'btn_text' ); ?>>
                <?php echo $settings['btn_text']; ?>
            </span>
		</span>
		<?php
	}
}
