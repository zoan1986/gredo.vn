<?php
namespace PowerpackElements\Modules\Posts\Skins;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Card Skin for Posts widget
 */
class Skin_Card extends Skin_Base {
    
    /**
	 * Retrieve Skin ID.
	 *
	 * @access public
	 *
	 * @return string Skin ID.
	 */
    public function get_id() {
        return 'card';
    }

    /**
	 * Retrieve Skin title.
	 *
	 * @access public
	 *
	 * @return string Skin title.
	 */
    public function get_title() {
        return __( 'Card', 'powerpack' );
    }

	/**
	 * Register Control Actions.
	 *
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		
		add_action( 'elementor/element/pp-posts/card_section_post_meta/before_section_end', [ $this, 'add_card_meta_controls' ] );
		add_action( 'elementor/element/pp-posts/card_section_meta_style/before_section_end', [ $this, 'add_card_meta_style_controls' ] );
	}

	public function add_card_meta_controls() {

		$this->add_control(
			'heading_author_avatar',
			[
				'label'                 => __( 'Author Avtar', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					$this->get_control_id( 'post_meta' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
            'author_avatar',
            [
                'label'                 => __( 'Show Author Avatar', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'author_avatar_size',
            [
                'label'                 => __( 'Avatar Size', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'xs'		=> __( 'Extra Small', 'powerpack' ),
                   'sm'		=> __( 'Small', 'powerpack' ),
                   'md'		=> __( 'Medium', 'powerpack' ),
                   'lg'		=> __( 'Large', 'powerpack' ),
                   'xl'		=> __( 'Extra Large', 'powerpack' ),
                ],
                'default'               => 'sm',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'author_avatar' ) => 'yes',
                ],
            ]
        );
		
	}

	public function add_card_meta_style_controls() {

        $this->add_control(
            'meta_border_color',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'				=> '#e6e6e6',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta-wrap' => 'border-top-color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'meta_border_width',
            [
                'label'                 => __( 'Border Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 1,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta-wrap' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );

		$this->add_control(
			'heading_post_author_avatar',
			[
				'label'                 => __( 'Author Avatar', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'author_avatar' ) => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
            'author_avatar_image_width',
            [
                'label'                 => __( 'Image Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 10,
                        'max'   => 240,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 40,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-avtar img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'author_avatar' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'author_avatar_image_spacing',
            [
                'label'                 => __( 'Image Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 10,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 10,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-avtar' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'author_avatar' ) => 'yes',
                ]
            ]
        );
		
	}
	
	protected function register_excerpt_controls() {
		parent::register_excerpt_controls();
        
        $this->update_control(
            'show_excerpt',
            [
                'label'                 => __( 'Show Excerpt', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
	}
	
	protected function register_style_content_controls() {
		parent::register_style_content_controls();
		
		$this->update_control(
			'post_content_padding',
			[
				'label'					=> __( 'Padding', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', 'em', '%' ],
				'default'				=> [
					'top'		=> '20',
					'right'		=> '20',
					'bottom'	=> '20',
					'left'		=> '20',
					'unit'		=> 'px',
				],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
	
	protected function register_style_image_controls() {
		parent::register_style_image_controls();
        
        $this->remove_control('img_border_radius');
        $this->remove_control('image_spacing');
	}
	
	protected function register_style_excerpt_controls() {
		parent::register_style_excerpt_controls();
        
        $this->update_responsive_control(
            'excerpt_margin_bottom',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> '',
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'show_excerpt' ) => 'yes',
                ]
            ]
        );
	}
	
	protected function register_style_meta_controls() {
		parent::register_style_meta_controls();
        
        $this->remove_control('meta_margin_bottom');
	}
    
    /**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_post_body() {
        $settings = $this->parent->get_settings_for_display();
        
		$post_terms = $this->get_instance_value( 'post_terms' );
		$post_meta = $this->get_instance_value( 'post_meta' );
		$author_avatar = $this->get_instance_value( 'author_avatar' );
		$author_avatar_size = $this->get_instance_value( 'author_avatar_size' );
		?>
		<div class="<?php echo $this->get_item_wrap_classes(); ?>">
			<div class="<?php echo $this->get_item_classes(); ?>">
				<?php
					$this->render_post_thumbnail();
				?>

				<div class="pp-post-content">
					<?php		
						if ( $post_terms == 'yes' ) {
							$this->render_terms();
						}

						$this->render_post_title();
					?>
					
					<?php $this->render_excerpt(); ?>
					
					<?php $this->render_button(); ?>
				</div>

				<?php if ( $post_meta == 'yes' ) { ?>
					<div class="pp-post-meta-wrap">
						<?php if ( $author_avatar == 'yes' ) { ?>
							<div class="pp-post-avtar">
								<?php
									$avatar_size = $this->get_avatar_size( $author_avatar_size );

									echo get_avatar( get_the_author_meta( 'ID' ), $avatar_size );
								?>
							</div>
						<?php } ?>
						<div class="pp-post-meta">
							<?php
								// Post Author
								$this->render_meta_item('author');

								// Post Date
								$this->render_meta_item('date');

								// Post Date
								$this->render_meta_item('comments');
							?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
        <?php
    }
}