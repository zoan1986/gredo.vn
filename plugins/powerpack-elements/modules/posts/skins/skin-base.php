<?php
//namespace PowerpackElements\Modules\Posts\Widgets;
namespace PowerpackElements\Modules\Posts\Skins;

use PowerpackElements\Base\Powerpack_Widget;
use PowerpackElements\Classes\PP_Posts_Helper;
use PowerpackElements\Group_Control_Transition;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Base
 */
abstract class Skin_Base extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/pp-posts/section_skin_field/after_section_end', [ $this, 'register_layout_controls' ] );
		add_action( 'elementor/element/pp-posts/section_query/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/pp-posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_style_controls();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_slider_controls();
		$this->register_filter_section_controls();
		$this->register_terms_controls();
		$this->register_image_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_controls();
		$this->register_button_controls();
		$this->register_pagination_controls();
	}

	public function register_style_controls() {
		$this->register_style_layout_controls();
		$this->register_style_box_controls();
		$this->register_style_content_controls();
		$this->register_style_filter_controls();
		$this->register_style_image_controls();
		$this->register_style_terms_controls();
		$this->register_style_title_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_meta_controls();
		$this->register_style_button_controls();
		$this->register_style_pagination_controls();
		$this->register_style_arrows_controls();
		$this->register_style_dots_controls();
	}
	
    public function register_layout_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		
		$this->register_layout_content_controls();
	}
	
	protected function register_layout_content_controls() {
        /**
         * Content Tab: Layout
         */
        $this->start_controls_section(
            'section_layout',
            [
                'label'                 => __( 'Layout', 'powerpack' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'                 => __( 'Layout', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'grid'		=> __( 'Grid', 'powerpack' ),
                   'masonry'	=> __( 'Masonry', 'powerpack' ),
                   'carousel'	=> __( 'Carousel', 'powerpack' ),
                ],
                'default'               => 'grid',
            ]
        );
		
        $this->add_responsive_control(
            'columns',
            [
                'label'                 => __( 'Columns', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '3',
                'tablet_default'        => '2',
                'mobile_default'        => '1',
                'options'               => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                ],
                'prefix_class'          => 'elementor-grid%s-',
				'render_type'           => 'template',
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'equal_height',
            [
                'label'                 => __( 'Equal Height', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'prefix_class'          => 'pp-equal-height-',
				'render_type'           => 'template',
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'masonry',
				],
            ]
        );
		
		$this->end_controls_section();
	}

	public function register_slider_controls() {

		$this->start_controls_section(
			'section_slider_options',
			[
				'label'					=> __( 'Carousel Options', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_CONTENT,
				'condition'				=> [
					$this->get_control_id( 'layout' ) => 'carousel',
				],
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Slides to Scroll', 'power-pack' ),
				'description'           => __( 'Set how many slides are scrolled per swipe.', 'power-pack' ),
				'options'               => $slides_per_view,
				'default'               => '1',
				'tablet_default'        => '1',
				'mobile_default'        => '1',
				'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
				],
				'frontend_available'    => true,
			]
		);

        $this->add_control(
            'animation_speed',
            [
                'label'                 => __( 'Animation Speed', 'powerpack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 600,
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
				'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'                 => __( 'Dots', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
				'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'powerpack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 3000,
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                    $this->get_control_id( 'autoplay' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label'                 => __( 'Pause on Hover', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                    $this->get_control_id( 'autoplay' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'                 => __( 'Infinite Loop', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'adaptive_height',
            [
                'label'                 => __( 'Adaptive Height', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'powerpack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'options'               => [
                    'left' 	=> [
                        'title' 	=> __( 'Left', 'powerpack' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'powerpack' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                ],
                'default'               => 'left',
                'frontend_available'    => true,
                'condition'             => [
					$this->get_control_id( 'layout' ) => 'carousel',
                ],
            ]
        );
		
		$this->end_controls_section();
	}

	public function register_filter_section_controls() {

		$this->start_controls_section(
			'section_filters',
			[
				'label'					=> __( 'Filters', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_CONTENT,
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
				],
			]
		);

		$this->add_control(
			'show_filters',
			[
				'label'					=> __( 'Show Filters', 'powerpack' ),
				'type'					=> Controls_Manager::SWITCHER,
				'label_on'				=> __( 'Yes', 'powerpack' ),
				'label_off'				=> __( 'No', 'powerpack' ),
				'return_value'			=> 'yes',
				'default'				=> 'no',
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
				],
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				$related_tax = [];

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax[ $index ] = $tax->label;
				}

				// Add control for all taxonomies.
				$this->add_control(
					'tax_' . $post_type_slug . '_filter',
					[
						'label'     => __( 'Filter By', 'powerpack' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => $related_tax,
						'default'   => array_keys( $related_tax )[0],
						'condition' => [
							'post_type' => $post_type_slug,
							$this->get_control_id( 'show_filters' ) => 'yes',
							$this->get_control_id( 'layout' ) => [ 'grid', 'masonry' ],
						],
					]
				);
			}
		}

        $this->add_control(
            'filter_all_label',
            [
                'label'                 => __( '"All" Filter Label', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'All', 'powerpack' ),
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
			'filter_alignment',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
                'label_block'           => true,
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'left',
				'options'               => [
					'left'          => [
						'title'     => __( 'Left', 'powerpack' ),
						'icon'      => 'eicon-h-align-left',
					],
					'center'        => [
						'title'     => __( 'Center', 'powerpack' ),
						'icon'      => 'eicon-h-align-center',
					],
					'right'         => [
						'title'     => __( 'Right', 'powerpack' ),
						'icon'      => 'eicon-h-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-filters'   => 'text-align: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
				],
			]
		);
		
		$this->end_controls_section();
	}
	
	protected function register_terms_controls() {
        /**
         * Content Tab: Post Terms
         */
        $this->start_controls_section(
            'section_terms',
            [
                'label'                 => __( 'Post Terms', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'post_terms',
            [
                'label'					=> __( 'Show Post Terms', 'powerpack' ),
                'type'					=> Controls_Manager::SWITCHER,
                'default'				=> 'yes',
                'label_on'				=> __( 'Yes', 'powerpack' ),
                'label_off'				=> __( 'No', 'powerpack' ),
                'return_value'			=> 'yes',
            ]
        );

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				$related_tax = [];

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax[ $index ] = $tax->label;
				}

				// Add control for all taxonomies.
				$this->add_control(
					'tax_badge_' . $post_type_slug,
					[
						'label'     => __( 'Select Taxonomy', 'powerpack' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => $related_tax,
						'default'   => array_keys( $related_tax )[0],
						'condition' => [
							'post_type' => $post_type_slug,
							$this->get_control_id( 'post_terms' ) => 'yes',
						],
					]
				);
			}
		}

		$this->add_control(
			'max_terms',
			[
				'label'					=> __( 'Max Terms to Show', 'powerpack' ),
				'type'					=> Controls_Manager::NUMBER,
				'default'				=> 1,
				'condition'				=> [
					$this->get_control_id( 'post_terms' ) => 'yes',
				],
				'label_block'			=> false,
			]
		);
        
        $this->add_control(
            'post_taxonomy_link',
            [
                'label'					=> __( 'Link to Taxonomy', 'powerpack' ),
                'type'					=> Controls_Manager::SWITCHER,
                'default'				=> 'yes',
                'label_on'				=> __( 'Yes', 'powerpack' ),
                'label_off'				=> __( 'No', 'powerpack' ),
                'return_value'			=> 'yes',
                'condition'				=> [
					$this->get_control_id( 'post_terms' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'post_terms_separator',
            [
                'label'                 => __( 'Terms Separator', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => ',',
				'selectors'             => [
					'{{WRAPPER}} .pp-post-terms > .pp-post-term:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ],
            ]
        );

        /*$this->add_control(
            'taxonomy',
            [
                'label'                 => __( 'Select Taxonomy', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT2,
				'label_block'       => true,
				'default'               => 'category',
				'multiple'          => false,
				'options'               => pp_get_post_taxonomies(),
                'condition'             => [
                    'post_terms'    => 'yes'
                ]
            ]
        );*/
        
        $this->end_controls_section();
	}
	
	/**
	 * Content Tab: Image
	 */
	protected function register_image_controls() {
		
        $this->start_controls_section(
            'section_image',
            [
                'label'                 => __( 'Image', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'show_thumbnail',
            [
                'label'                 => __( 'Show Image', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'thumbnail_link',
            [
                'label'                 => __( 'Link to Post', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
                ],
            ]
        );
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail',
				'label'                 => __( 'Image Size', 'powerpack' ),
				'default'               => 'large',
				'exclude'           => [ 'custom' ],
                'condition'             => [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
                ],
			]
		);

        $this->add_control(
            'fallback_image',
            [
                'label'					=> __( 'Fallback Image', 'powerpack' ),
                'description'			=> __( 'If a featured image is not available in post, it will display the first image from the post or default image placeholder or a custom image. You can choose None to do not display the fallback image.', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'none'			=> __( 'None', 'powerpack' ),
                   'default'		=> __( 'Default', 'powerpack' ),
                   'custom'			=> __( 'Custom', 'powerpack' ),
                ],
                'default'               => 'default',
                'condition'             => [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
                ],
            ]
        );

		$this->add_control(
			'fallback_image_custom',
			[
				'label'             => __( 'Fallback Image Custom', 'powerpack' ),
				'type'              => Controls_Manager::MEDIA,
                'condition'         => [
                    $this->get_control_id( 'show_thumbnail' ) => 'yes',
                    $this->get_control_id( 'fallback_image' ) => 'custom',
                ]
			]
		);
        
        $this->end_controls_section();
	}
	
	/**
	 * Content Tab: Title
	 */
	protected function register_title_controls() {
        $this->start_controls_section(
            'section_post_title',
            [
                'label'                 => __( 'Title', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'post_title',
            [
                'label'                 => __( 'Post Title', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'post_title_link',
            [
                'label'                 => __( 'Link to Post', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'					=> __( 'HTML Tag', 'powerpack' ),
                'type'					=> Controls_Manager::SELECT,
                'default'				=> 'h2',
                'options'				=> [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
                'condition'				=> [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_title_separator',
            [
                'label'                 => __( 'Title Separator', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
	}
	
	/**
	 * Content Tab: Excerpt
	 */
	protected function register_excerpt_controls() {
        $this->start_controls_section(
            'section_post_excerpt',
            [
                'label'                 => __( 'Excerpt', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'show_excerpt',
            [
                'label'                 => __( 'Show Excerpt', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'excerpt_length',
            [
                'label'					=> __( 'Excerpt Length', 'powerpack' ),
                'type'					=> Controls_Manager::NUMBER,
                'default'				=> 20,
                'min'					=> 0,
                'max'					=> 58,
                'step'					=> 1,
                'condition'				=> [
                    $this->get_control_id( 'show_excerpt' ) => 'yes',
                ]
            ]
        );
        
        $this->end_controls_section();
	}
	
	/**
	 * Content Tab: Meta
	 */
	protected function register_meta_controls() {
        $this->start_controls_section(
            'section_post_meta',
            [
                'label'                 => __( 'Meta', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'post_meta',
            [
                'label'                 => __( 'Post Meta', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'post_meta_separator',
            [
                'label'                 => __( 'Post Meta Separator', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '-',
				'selectors'             => [
					'{{WRAPPER}} .pp-post-meta > span:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ],
            ]
        );

		$this->add_control(
			'heading_post_author',
			[
				'label'                 => __( 'Post Author', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					$this->get_control_id( 'post_meta' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
            'show_author',
            [
                'label'                 => __( 'Show Post Author', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'author_link',
            [
                'label'                 => __( 'Link to Author', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_author' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'author_icon',
            [
                'label'                 => __( 'Author Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_author' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'author_prefix',
            [
                'label'                 => __( 'Prefix', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_author' ) => 'yes',
                ]
            ]
        );

		$this->add_control(
			'heading_post_date',
			[
				'label'                 => __( 'Post Date', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
            'show_date',
            [
                'label'                 => __( 'Show Post Date', 'powerpack' ),
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
            'date_link',
            [
                'label'                 => __( 'Link to Post', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_date' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_format',
            [
                'label'                 => __( 'Date Format', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   ''			=> __( 'Default', 'powerpack' ),
                   'ago'		=> __( 'Time Ago', 'powerpack' ),
                ],
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_date' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_icon',
            [
                'label'                 => __( 'Date Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_date' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'date_prefix',
            [
                'label'                 => __( 'Prefix', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_date' ) => 'yes',
                ]
            ]
        );

		$this->add_control(
			'heading_post_comments',
			[
				'label'                 => __( 'Post Comments', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
            'show_comments',
            [
                'label'                 => __( 'Show Post Comments', 'powerpack' ),
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
            'comments_icon',
            [
                'label'                 => __( 'Comments Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                    $this->get_control_id( 'show_comments' ) => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
	}
	
	protected function register_button_controls() {
		
		$this->start_controls_section(
            'section_button',
            [
                'label'                 => __( 'Read More Button', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'show_button',
            [
                'label'                 => __( 'Show Button', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'                 => __( 'Button Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Read More', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'show_button' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label'                 => __( 'Button Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    $this->get_control_id( 'show_button' ) => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label'                 => __( 'Icon Position', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'after',
                'options'               => [
                    'after'     => __( 'After', 'powerpack' ),
                    'before'    => __( 'Before', 'powerpack' ),
                ],
                'condition'             => [
                    $this->get_control_id( 'show_button' ) => 'yes',
                    $this->get_control_id( 'button_icon!' ) => '',
                ],
            ]
        );
		
		$this->end_controls_section();
	}

	public function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label'					=> __( 'Pagination', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'					=> __( 'Pagination', 'powerpack' ),
				'type'					=> Controls_Manager::SELECT,
				'default'				=> 'none',
				'options'				=> [
					'none'					=> __( 'None', 'powerpack' ),
					'numbers'				=> __( 'Numbers', 'powerpack' ),
					'numbers_and_prev_next'	=> __( 'Numbers', 'powerpack' ) . ' + ' . __( 'Previous/Next', 'powerpack' ),
					'load_more'				=> __( 'Load More Button', 'powerpack' ),
					'infinite'				=> __( 'Infinite', 'powerpack' ),
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label'					=> __( 'Page Limit', 'powerpack' ),
				'type'					=> Controls_Manager::NUMBER,
				'default'				=> 5,
				'condition'				=> [
					$this->get_control_id( 'layout!' )			=> 'carousel',
					$this->get_control_id( 'pagination_type' )	=> [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label'					=> __( 'Shorten', 'powerpack' ),
				'type'					=> Controls_Manager::SWITCHER,
				'default'				=> '',
				'condition'				=> [
					$this->get_control_id( 'layout!' )			=> 'carousel',
					$this->get_control_id( 'pagination_type' )	=> [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_load_more_label',
			[
				'label'					=> __( 'Button Label', 'powerpack' ),
				'default'				=> __( 'Load More', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' )	=> 'load_more',
				],
			]
		);

		$this->add_control(
			'pagination_prev_label',
			[
				'label'					=> __( 'Previous Label', 'powerpack' ),
				'default'				=> __( '&laquo; Previous', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' )	=> 'numbers_and_prev_next',
				],
			]
		);

		$this->add_control(
			'pagination_next_label',
			[
				'label'					=> __( 'Next Label', 'powerpack' ),
				'default'				=> __( 'Next &raquo;', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' )	=> 'numbers_and_prev_next',
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label'					=> __( 'Alignment', 'powerpack' ),
				'type'					=> Controls_Manager::CHOOSE,
				'options'			=> [
					'left'		=> [
						'title'	=> __( 'Left', 'powerpack' ),
						'icon'	=> 'fa fa-align-left',
					],
					'center'	=> [
						'title' => __( 'Center', 'powerpack' ),
						'icon'	=> 'fa fa-align-center',
					],
					'right'		=> [
						'title' => __( 'Right', 'powerpack' ),
						'icon'	=> 'fa fa-align-right',
					],
				],
				'default'				=> 'center',
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type!' )	=> 'none',
				],
			]
		);

		$this->end_controls_section();
	}
	
	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {
		
        $this->start_controls_section(
            'section_layout_style',
            [
                'label'                 => __( 'Layout', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'posts_horizontal_spacing',
            [
                'label'                 => __( 'Column Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'               => [
                    'size' 	=> 25,
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-wrap' => 'padding-left: calc( {{SIZE}}{{UNIT}}/2 ); padding-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .pp-posts'  => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );

        $this->add_responsive_control(
            'posts_vertical_spacing',
            [
                'label'                 => __( 'Row Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'               => [
                    'size' 	=> 25,
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-elementor-grid .pp-grid-item-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
				],
            ]
        );
        
        $this->end_controls_section();
	}
	
	/**
	 * Style Tab: Box
	 */
	protected function register_style_box_controls() {
        $this->start_controls_section(
            'section_post_box_style',
            [
                'label'                 => __( 'Box', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'post_box_bg',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'post_box_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-post',
			]
		);

		$this->add_control(
			'post_box_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_box_padding',
			[
				'label'					=> __( 'Padding', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', 'em', '%' ],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'post_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-post',
			]
		);
        
        $this->end_controls_section();
	}
	
	/**
	 * Style Tab: Content
	 */
	protected function register_style_content_controls() {
        $this->start_controls_section(
            'section_post_content_style',
            [
                'label'                 => __( 'Content', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'post_content_align',
			[
				'label'					=> __( 'Alignment', 'powerpack' ),
				'type'					=> Controls_Manager::CHOOSE,
				'label_block'			=> false,
				'options'			=> [
					'left'		=> [
						'title'	=> __( 'Left', 'powerpack' ),
						'icon'	=> 'fa fa-align-left',
					],
					'center'	=> [
						'title' => __( 'Center', 'powerpack' ),
						'icon'	=> 'fa fa-align-center',
					],
					'right'		=> [
						'title' => __( 'Right', 'powerpack' ),
						'icon'	=> 'fa fa-align-right',
					],
				],
				'default'				=> '',
				'selectors'			=> [
					'{{WRAPPER}} .pp-post-content' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'post_content_bg',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'post_content_border_radius',
			[
				'label'					=> __( 'Border Radius', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', '%' ],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_content_padding',
			[
				'label'					=> __( 'Padding', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', 'em', '%' ],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
	}
	
    public function register_style_filter_controls() {    
        /**
         * Style Tab: Filters
         */
        $this->start_controls_section(
            'section_filter_style',
            [
                'label'                 => __( 'Filters', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'filter_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'selector'              => '{{WRAPPER}} .pp-post-filters',
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'filters_margin_bottom',
            [
                'label'                 => __( 'Filters Bottom Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_filter_style' );

        $this->start_controls_tab(
            'tab_filter_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_color_normal',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter' => 'color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_background_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter' => 'background-color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'filter_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-post-filter',
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);

		$this->add_control(
			'filter_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);

		$this->add_responsive_control(
			'filter_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'filter_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-post-filter',
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_active',
            [
                'label'                 => __( 'Active', 'powerpack' ),
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_color_active',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter.pp-filter-current' => 'color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_background_color_active',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter.pp-filter-current' => 'background-color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_border_color_active',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter.pp-filter-current' => 'border-color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'filter_box_shadow_active',
				'selector'          => '{{WRAPPER}} .pp-post-filter.pp-filter-current',
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_color_hover',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter:hover' => 'color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_background_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter:hover' => 'background-color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

        $this->add_control(
            'filter_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-filter:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'filter_box_shadow_hover',
				'selector'          => '{{WRAPPER}} .pp-post-filter:hover',
                'condition'				=> [
					$this->get_control_id( 'layout!' )		=> 'carousel',
					$this->get_control_id( 'show_filters' )	=> 'yes',
                ]
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }
	
	/**
	 * Style Tab: Image
	 */
	protected function register_style_image_controls() {
		$this->start_controls_section(
			'section_image_style',
			[
				'label'					=> __( 'Image', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_STYLE,
				'condition'				=> [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label'					=> __( 'Border Radius', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', '%' ],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'				=> [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label'					=> __( 'Spacing', 'powerpack' ),
				'type'					=> Controls_Manager::SLIDER,
				'range'					=> [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default'				=> [
					'size' => 20,
				],
				'condition'				=> [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label'					=> __( 'Normal', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'					=> 'thumbnail_filters',
				'selector'				=> '{{WRAPPER}} .pp-post-thumbnail img',
			]
		);

		$this->add_group_control(
			Group_Control_Transition::get_type(),
			[
				'name' 		=> 'image_transition',
				'selector' 	=> '{{WRAPPER}} .pp-post-thumbnail img',
				'separator'	=> '',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label'					=> __( 'Hover', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'					=> 'thumbnail_hover_filters',
				'selector'				=> '{{WRAPPER}} .pp-post:hover .pp-post-thumbnail img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	
	/**
	 * Style Tab: Title
	 */
	protected function register_style_title_controls() {
        $this->start_controls_section(
            'section_title_style',
            [
                'label'					=> __( 'Title', 'powerpack' ),
                'tab'					=> Controls_Manager::TAB_STYLE,
                'condition'				=> [
					$this->get_control_id( 'post_title' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-title, {{WRAPPER}} .pp-post-title a' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label'                 => __( 'Hover Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-title a:hover' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                    $this->get_control_id( 'post_title_link' ) => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'selector'              => '{{WRAPPER}} .pp-post-title',
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label'                 => __( 'Bottom Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 10,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_title' ) => 'yes',
                ]
            ]
        );
        
        $this->add_control(
            'title_separator_heading',
            [
                'label'                 => __( 'Separator', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'title_separator_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-post-separator',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
                ]
			]
		);
        
        $this->add_responsive_control(
            'title_separator_height',
            [
                'label'                 => __( 'Separator Height', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'unit' 	=> 'px',
                    'size' 	=> 1,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 20,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-separator' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'title_separator_width',
            [
                'label'                 => __( 'Separator Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'unit' 	=> '%',
                    'size' 	=> 100,
                ],
                'range'                 => [
                    '%' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    'px' => [
                        'min'   => 10,
                        'max'   => 200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ '%', 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-separator' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'title_separator_margin_bottom',
            [
                'label'                 => __( 'Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	=> 15,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-separator-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
                ]
            ]
        );
        
        $this->end_controls_section();
	}

	/**
	 * Style Tab: Post Terms
	 */
	protected function register_style_terms_controls() {
        $this->start_controls_section(
            'section_terms_style',
            [
                'label'                 => __( 'Post Terms', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'terms_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'selector'              => '{{WRAPPER}} .pp-post-terms',
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'terms_margin_bottom',
            [
                'label'                 => __( 'Bottom Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	=> 10,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'terms_gap',
            [
                'label'                 => __( 'Terms Gap', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	=> 5,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms .pp-post-term:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );

		$this->start_controls_tabs( 'terms_style_tabs' );

		$this->start_controls_tab( 'terms_style_normal',
			[
				'label'					=> __( 'Normal', 'powerpack' ),
			]
		);

        $this->add_control(
            'terms_bg_color',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'terms_text_color',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );

		$this->add_control(
			'terms_border_radius',
			[
				'label'					=> __( 'Border Radius', 'powerpack' ),
				'type'					=> Controls_Manager::DIMENSIONS,
				'size_units'			=> [ 'px', '%' ],
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-terms' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'				=> [
					$this->get_control_id( 'post_terms' ) => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'terms_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-terms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab( 'terms_style_hover',
			[
				'label'					=> __( 'Hover', 'powerpack' ),
			]
		);

        $this->add_control(
            'terms_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms:hover' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'terms_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-terms a:hover' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_terms' ) => 'yes',
                ]
            ]
        );
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
        
        $this->end_controls_section();
	}
	
	/**
	 * Style Tab: Excerpt
	 */
	protected function register_style_excerpt_controls() {
        $this->start_controls_section(
            'section_excerpt_style',
            [
                'label'					=> __( 'Excerpt', 'powerpack' ),
                'tab'					=> Controls_Manager::TAB_STYLE,
                'condition'				=> [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-excerpt' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'show_excerpt' ) => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'excerpt_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'selector'              => '{{WRAPPER}} .pp-post-excerpt',
                'condition'             => [
                    $this->get_control_id( 'show_excerpt' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'excerpt_margin_bottom',
            [
                'label'                 => __( 'Bottom Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 20,
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
        
        $this->end_controls_section();
	}

	/**
	 * Style Tab: Meta
	 */
	protected function register_style_meta_controls() {
		
        $this->start_controls_section(
            'section_meta_style',
            [
                'label'                 => __( 'Meta', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'meta_links_color',
            [
                'label'                 => __( 'Links Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta a' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );

        $this->add_control(
            'meta_links_color_hover',
            [
                'label'                 => __( 'Links Hover Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta a:hover' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'meta_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'selector'              => '{{WRAPPER}} .pp-post-meta',
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'meta_margin_bottom',
            [
                'label'                 => __( 'Bottom Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 20,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'post_meta' ) => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

    }

	/**
	 * Style Tab: Button
	 */
	protected function register_style_button_controls() {
		
        $this->start_controls_section(
            'section_button_style',
            [
                'label'                 => __( 'Read More Button', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    $this->get_control_id( 'show_button' ) => 'yes',
                ]
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'sm',
				'options'               => [
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-posts-button',
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-button' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-posts-button',
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-posts-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-posts-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-posts-button',
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
                    'button_icon!' => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
                    'button_icon!' => '',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-button:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-posts-button:hover',
				'condition'             => [
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();
	}

	public function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label'					=> __( 'Arrows', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_STYLE,
				'condition'				=> [
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				],
			]
		);
        
        $this->add_control(
			'arrow',
			[
				'label'                 => __( 'Choose Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'include'               => [
					'fa fa-angle-right',
                    'fa fa-angle-double-right',
                    'fa fa-chevron-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-long-arrow-right',
                    'fa fa-caret-right',
                    'fa fa-caret-square-o-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-arrow-circle-o-right',
                    'fa fa-toggle-right',
                    'fa fa-hand-o-right',
				],
				'default'               => 'fa fa-angle-right',
				'frontend_available'    => true,
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
			]
		);
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '22' ],
                'range'                 => [
                    'px' => [
                        'min'   => 15,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_position',
            [
                'label'                 => __( 'Align Arrows', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slider-arrow' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slider-arrow' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-slider-arrow',
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slider-arrow:hover' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slider-arrow:hover' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slider-arrow:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
                'condition'             => [
                    $this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
                ],
			]
		);
        
        $this->end_controls_section();
	}

	public function register_style_dots_controls() {
		$this->start_controls_section(
			'section_dots_style',
            [
                'label'                 => __( 'Dots', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_STYLE,
				'condition'				=> [
					$this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
            'dots_size',
            [
                'label'                 => __( 'Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 2,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'                 => __( 'Spacing', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-slick-slider .slick-dots li',
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slick-slider .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
			]
		);

		$this->add_responsive_control(
			'dots_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
                'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-slick-slider .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [
                'label'                 => __( 'Active', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_color_active',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li.slick-active' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_active',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li.slick-active' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li:hover' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-slick-slider .slick-dots li:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    $this->get_control_id( 'layout' )	=> 'carousel',
					$this->get_control_id( 'dots' )		=> 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
	}

	public function register_style_pagination_controls() {
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'					=> __( 'Pagination', 'powerpack' ),
				'tab'					=> Controls_Manager::TAB_STYLE,
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type!' ) => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_margin_top',
			[
				'label'					=> __( 'Space Above', 'powerpack' ),
				'type'					=> Controls_Manager::SLIDER,
				'default'				=> [
					'size' => '',
				],
				'range'				=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_control(
			'load_more_button_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'sm',
				'options'               => [
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'load_more',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'				=> 'pagination_typography',
				'selector'			=> '{{WRAPPER}} .pp-posts-pagination',
				'scheme'			=> Scheme_Typography::TYPOGRAPHY_2,
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_pagination' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			[
				'label'					=> __( 'Normal', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

        $this->add_control(
            'pagination_link_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
            ]
        );

		$this->add_control(
			'pagination_color',
			[
				'label'					=> __( 'Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'pagination_link_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_control(
			'pagination_link_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_responsive_control(
			'pagination_link_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pagination_link_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			[
				'label'					=> __( 'Hover', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

        $this->add_control(
            'pagination_link_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-pagination a:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
            ]
        );

		$this->add_control(
			'pagination_color_hover',
			[
				'label'					=> __( 'Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'					=> __( 'Border Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'border-color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pagination_link_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-posts-pagination a:hover',
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next', 'load_more'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			[
				'label'					=> __( 'Active', 'powerpack' ),
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
			]
		);

        $this->add_control(
            'pagination_link_bg_color_active',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
            ]
        );

		$this->add_control(
			'pagination_color_active',
			[
				'label'					=> __( 'Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
			]
		);

		$this->add_control(
			'pagination_border_color_active',
			[
				'label'					=> __( 'Border Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pagination_link_box_shadow_active',
				'selector'              => '{{WRAPPER}} .pp-posts-pagination .page-numbers.current',
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'					=> __( 'Space Between', 'powerpack' ),
				'type'					=> Controls_Manager::SLIDER,
				'separator'			=> 'before',
				'default'				=> [
					'size' => 10,
				],
				'range'				=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'			=> [
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['numbers', 'numbers_and_prev_next'],
				],
			]
		);

		$this->add_control(
			'heading_loader',
			[
				'label'                 => __( 'Loader', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['load_more', 'infinite'],
				],
			]
		);

		$this->add_control(
			'loader_color',
			[
				'label'					=> __( 'Color', 'powerpack' ),
				'type'					=> Controls_Manager::COLOR,
				'selectors'			=> [
					'{{WRAPPER}} .pp-loader:after, {{WRAPPER}} .pp-posts-loader:after' => 'border-bottom-color: {{VALUE}}; border-top-color: {{VALUE}};',
				],
				'condition'				=> [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['load_more', 'infinite'],
				],
			]
		);
        
        $this->add_responsive_control(
            'loader_size',
            [
                'label'                 => __( 'Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 10,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'default'               => [
                    'size' 	=> 46,
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-posts-loader' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => ['load_more', 'infinite'],
                ]
            ]
        );

		$this->end_controls_section();
	}
	
	public function get_avatar_size( $size = 'sm' ) {
			
		if ( $size == 'xs' ) {
			$value = 30;
		} elseif ( $size == 'sm' ) {
			$value = 60;
		} elseif ( $size == 'md' ) {
			$value = 120;
		} elseif ( $size == 'lg' ) {
			$value = 180;
		} elseif ( $size == 'xl' ) {
			$value = 240;
		} else {
			$value = 60;
		}
		
		return $value;
	}

	/**
	 * Get Filter taxonomy array.
	 *
	 * Returns the Filter array of objects.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_filter_values() {

		$settings = $this->parent->get_settings();

		$post_type = $settings['post_type'];

		$filter_by = $this->get_instance_value( 'tax_' . $post_type . '_filter' );
			
		$filter_type = $settings[ $filter_by . '_' . $post_type . '_filter_type' ];
		
		$filters = $settings[ $filter_by . '_' . $post_type ];

		// Get the categories for post types.
		$taxs = get_terms( $filter_by );

		$filter_array = array();

		if ( is_wp_error( $taxs ) ) {
			return array();
		}

		if ( empty( $filters ) || '' == $filters ) {

			$filter_array = $taxs;
		} else {

			foreach ( $taxs as $key => $value ) {

				if ( 'IN' == $filter_type ) {

					if ( in_array( $value->term_id, $filters ) ) {

						$filter_array[] = $value;
					}
				} else {

					if ( ! in_array( $value->term_id, $filters ) ) {

						$filter_array[] = $value;
					}
				}
			}
		}

		return $filter_array;
	}

	/**
	 * Render Filters.
	 *
	 * Returns the Filter HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_filters() {
		
		$layout = $this->get_instance_value( 'layout' );
		$show_filters = $this->get_instance_value( 'show_filters' );
		
		if ( 'carousel' == $layout ) {
			return;
		}

		if ( 'yes' != $show_filters ) {
			return;
		}

		$filters = $this->get_filter_values();
		$all_label = $this->get_instance_value( 'filter_all_label' );

		?>
		<div class="pp-post-filters-container">
			<div class="pp-post-filters-wrap">
				<ul class="pp-post-filters">
					<li class="pp-post-filter pp-filter-current" data-filter="*"><?php echo ( 'All' == $all_label || '' == $all_label ) ? __( 'All', 'powerpack' ) : $all_label; ?></li>
					<?php foreach ( $filters as $key => $value ) { ?>
					<li class="pp-post-filter" data-filter="<?php echo '.' . $value->slug; ?>"><?php echo $value->name; ?></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Masonry classes array.
	 *
	 * Returns the Masonry classes array.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_masonry_classes() {

		$settings = $this->parent->get_settings();

		$post_type = $settings['post_type'];

		$filter_by = $this->get_instance_value( 'tax_' . $post_type . '_filter' );

		$taxonomies = wp_get_post_terms( get_the_ID(), $filter_by );
		$class      = array();

		if ( count( $taxonomies ) > 0 ) {

			foreach ( $taxonomies as $taxonomy ) {

				if ( is_object( $taxonomy ) ) {

					$class[] = $taxonomy->slug;
				}
			}
		}

		return implode( ' ', $class );
	}
    
    /**
	 * Render post terms output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_terms() {
		$settings = $this->parent->get_settings();
		$post_terms = $this->get_instance_value( 'post_terms' );
        
        if ( $post_terms != 'yes' )
            return;
		
		$post_type = $settings['post_type'];

		$taxonomy = $this->get_instance_value( 'tax_badge_' . $post_type );
		
		$terms = wp_get_post_terms( get_the_ID(), $taxonomy );
		
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}
		
		$max_terms = $this->get_instance_value( 'max_terms' );
		
		if ( $max_terms != '' ) {
			$terms = array_slice( $terms, 0, $max_terms );
		}
		
		$link_terms = $this->get_instance_value( 'post_taxonomy_link' );
		
		if ( $link_terms == 'yes' ) {
			$format = '<span class="pp-post-term"><a href="%2$s">%1$s</a></span>';
		} else {
			$format = '<span class="pp-post-term">%1$s</span>';
		}
        ?>
		<div class="pp-post-terms-wrap">
			<span class="pp-post-terms">
				<?php
					foreach ( $terms as $term ) {
						printf( $format, $term->name, get_term_link( (int) $term->term_id ) );
					}
				?>
			</span>
		</div>
		<?php
    }
    
    /**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_meta_item( $item_type = '' ) {
		if ( $item_type == '' )
            return;
		
		$show_item = $this->get_instance_value( 'show_' . $item_type );
		$item_link = $this->get_instance_value( $item_type . '_link' );
		$item_icon = $this->get_instance_value( $item_type . '_icon' );
		$item_prefix = $this->get_instance_value( $item_type . '_prefix' );
        
        if ( $show_item != 'yes' )
            return;
        ?>
		<span class="pp-post-<?php echo $item_type; ?>">
			<?php
				if ( $item_icon != '' ) {
					?>
					<span class="pp-meta-icon <?php echo $item_icon; ?>">
					</span>
					<?php
				}
		
				if ( $item_prefix != '' ) {
					?>
					<span class="pp-meta-prefix">
						<?php
							echo $item_prefix;
						?>
					</span>
					<?php
				}
			?>
			<span class="pp-meta-text">
				<?php
					if ( $item_type == 'author' ) {
						echo $this->get_post_author( $item_link );
					}
					else if ( $item_type == 'date' ) {
						echo $this->get_post_date( $item_link );
					}
					else if ( $item_type == 'comments' ) {
						echo $this->get_post_comments();
					}
				?>
			</span>
		</span>
		<?php
    }
    
    /**
	 * Get post author
	 *
	 * @access protected
	 */
    protected function get_post_author( $author_link = '' ) {
		if ( $author_link == 'yes' ) {
			return get_the_author_posts_link();
		} else {
			return get_the_author();
		}
    }
    
    /**
	 * Get post author
	 *
	 * @access protected
	 */
    protected function get_post_comments() {
		return comments_number();
    }
    
    /**
	 * Get post date
	 *
	 * @access protected
	 */
    protected function get_post_date( $date_link = '' ) {
		$date_format = $this->get_instance_value( 'date_format' );
		$output = '';
		
		if ( $date_format == 'ago' ) {
			$date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'powerpack' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		} else {
			$date = get_the_date();
		}
		
		if ( $date_link == 'yes' ) {
			$output .= '<a href="' . get_permalink(). '">' . $date . '</a>';
		} else {
			$output .= $date;
		}
		
		return $output;
    }
    
    /**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function get_post_thumbnail() {

		$settings = $this->parent->get_settings();
        $image = $this->get_instance_value( 'show_thumbnail' );
        $fallback_image = $this->get_instance_value( 'fallback_image' );
        $fallback_image_custom = $this->get_instance_value( 'fallback_image_custom' );

		if ( $image !== 'yes' ) {
			return;
		}

		if ( has_post_thumbnail() ) {
            
            $image_id = get_post_thumbnail_id( get_the_ID() );
			
			$setting_key = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = [
				'id' => $image_id,
			];
			$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		} elseif ( $fallback_image == 'default' ) {

			$thumbnail_url = Utils::get_placeholder_image_src();
			$thumbnail_html = '<img src="'. $thumbnail_url .'"/>';

		} elseif ( $fallback_image == 'custom' ) {
			
			$custom_image_id = $fallback_image_custom['id'];
			$setting_key = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = [
				'id' => $custom_image_id,
			];
			$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );
			
		}

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		
		return $thumbnail_html;
	}
    
    /**
	 * Render post title output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_post_title() {
        $post_title = $this->get_instance_value( 'post_title' );
        $title_tag = $this->get_instance_value( 'title_html_tag' );
        $title_link = $this->get_instance_value( 'post_title_link' );
        $post_title_separator = $this->get_instance_value( 'post_title_separator' );
        
        if ( $post_title != 'yes' )
            return;
        ?>
		<<?php echo $title_tag; ?> class="pp-post-title">
			<?php if ( $title_link == 'yes' ) { ?>
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			<?php } else { the_title(); } ?>
		</<?php echo $title_tag; ?>>
        <?php
		if ( $post_title_separator == 'yes' ) {
			?>
			<div class="pp-post-separator-wrap">
				<div class="pp-post-separator"></div>
			</div>
			<?php
		}
    }
    
    /**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_post_thumbnail() {

        $image_link = $this->get_instance_value( 'thumbnail_link' );
		
		$thumbnail_html = $this->get_post_thumbnail();

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		
		if ( $image_link == 'yes' ) {
			
			$thumbnail_html = '<a href="' . get_the_permalink() . '">' . $thumbnail_html . '</a>';
			
		}
		?>
		<div class="pp-post-thumbnail">
			<?php echo $thumbnail_html; ?>
		</div>
		<?php
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function pp_excerpt_length_filter() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	/**
	 * Get post excerpt end text.
	 *
	 * Returns the string to append to post excerpt.
	 *
	 * @param string $more returns string.
	 * @since 1.7.0
	 * @access public
	 */
	public function pp_excerpt_more_filter( $more ) {
		return ' ...';
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_excerpt() {

		$show_excerpt = $this->get_instance_value( 'show_excerpt' );
		$excerpt_length = $this->get_instance_value( 'excerpt_length' );

		if ( $show_excerpt != 'yes' ) {
			return;
		}

		if ( $excerpt_length == 0 ) {
			return;
		}
		
		$excerpt = PP_Posts_Helper::custom_excerpt( $excerpt_length );
		?>

		<div class="pp-post-excerpt">
			<?php echo $excerpt; ?>
		</div>

		<?php
	}
    
    /**
	 * Render button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_button() {
		$show_button = $this->get_instance_value( 'show_button' );
		$button_animation = $this->get_instance_value( 'button_animation' );
		
		if ( $show_button != 'yes' ) {
			return;
		}
		
		$button_text = $this->get_instance_value( 'button_text' );
		$button_icon = $this->get_instance_value( 'button_icon' );
		$button_icon_position = $this->get_instance_value( 'button_icon_position' );
		$button_size = $this->get_instance_value( 'button_size' );
		
		$classes = array(
			'pp-posts-button',
			'elementor-button',
			'elementor-size-' . $button_size
		);
		
		if ( $button_animation ) {
			$classes[] = 'elementor-animation-' . $button_animation;
		}
		?>
		<a class="<?php echo implode(" ", $classes); ?>" href="<?php echo get_the_permalink(); ?>">
			<?php if ( $button_icon != '' && $button_icon_position == 'before' ) { ?>
				<span class="pp-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></span>
			<?php } ?>
			<?php if ( $button_text != '' ) { ?>
				<span class="pp-button-text">
					<?php echo esc_html( $button_text ); ?>
				</span>
			<?php } ?>
			<?php if ( $button_icon != '' && $button_icon_position == 'after' ) { ?>
				<span class="pp-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></span>
			<?php } ?>
		</a>
		<?php
	}
    
    /**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    public function render_ajax_post_body( $filter = '' ) {
		ob_start();
		$this->parent->query_posts( $filter );
		
		$query = $this->parent->get_query();
		$total_pages = $query->max_num_pages;

		while ($query->have_posts()) {
			$query->the_post();

			$this->render_post_body();

		$i++;
		}

		wp_reset_postdata();
		
		return ob_get_clean();
	}
    
    /**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    public function render_ajax_pagination() {
		ob_start();
		$this->render_pagination();
		return ob_get_clean();
	}

	/**
	 * Get Pagination.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_pagination() {

		$pagination_type = $this->get_instance_value( 'pagination_type' );
		$page_limit = $this->get_instance_value( 'pagination_page_limit' );
		$pagination_shorten = $this->get_instance_value( 'pagination_numbers_shorten' );
		$load_more_label = $this->get_instance_value( 'pagination_load_more_label' );

		if ( 'none' == $pagination_type ) {
			return;
		}

		// Get current page number.
		$paged = $this->parent->get_paged();
		
		$query = $this->parent->get_query();
		$total_pages = $query->max_num_pages;

		if ( 'load_more' != $pagination_type || 'infinite' != $pagination_type ) {

			if ( '' !== $page_limit && null != $page_limit ) {
				$total_pages = min( $page_limit, $total_pages );
			}
		}
		
		if ( 2 > $total_pages ) {
			return;
		}
		
		$has_numbers = in_array( $pagination_type, [ 'numbers', 'numbers_and_prev_next' ] );
		$has_prev_next = ( $pagination_type == 'numbers_and_prev_next' );
		$is_load_more = ( $pagination_type == 'load_more' );
		$is_infinite = ( $pagination_type == 'infinite' );
		
		$links = [];

		if ( $has_numbers || $is_infinite ) {
			
			$current_page = $paged;
			if ( ! $current_page ) {
				$current_page = 1;
			}
			
			$paginate_args = [
				'type'			=> 'array',
				'current'		=> $current_page,
				'total'			=> $total_pages,
				'prev_next'		=> false,
				'show_all'		=> 'yes' !== $pagination_shorten,
			];
		}

		if ( $has_prev_next ) {
			$prev_label = $this->get_instance_value( 'pagination_prev_label' );
			$next_label = $this->get_instance_value( 'pagination_next_label' );
			
			$paginate_args['prev_next'] = true;
			
			if ( $prev_label ) {
				$paginate_args['prev_text'] = $prev_label;
			}
			if ( $next_label ) {
				$paginate_args['next_text'] = $next_label;
			}

		}
		
		if ( $has_numbers || $has_prev_next || $is_infinite ) {

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base'] = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );

		}
		
		if ( !$is_load_more ) {
			?>
			<nav class="pp-posts-pagination elementor-pagination" role="navigation" aria-label="<?php _e( 'Pagination', 'powerpack' ); ?>">
				<?php echo implode( PHP_EOL, $links ); ?>
			</nav>
			<?php
		}
		
		if ( $is_load_more ) {
			?>
			<div class="pp-post-load-more-wrap pp-posts-pagination">
				<a class="pp-post-load-more elementor-button elementor-size-sm" href="javascript:void(0);">
					<span><?php echo $load_more_label; ?></span>
				</a>
			</div>
			<?php
		}
	}
	
	public function get_item_wrap_classes() {
		$layout = $this->get_instance_value( 'layout' );
		
		$classes = array('pp-post-wrap');
			
		if ( $layout == 'carousel' ) {
			$classes[] = 'pp-carousel-item-wrap';
		} else {
			$classes[] = 'pp-grid-item-wrap';
		}
		
		return implode( ' ', $classes );
	}
	
	public function get_item_classes() {
		$layout = $this->get_instance_value( 'layout' );
		
		$classes = array();
		
		$classes[] = 'pp-post';
			
		if ( $layout == 'carousel' ) {
			$classes[] = 'pp-carousel-item';
		} else {
			$classes[] = 'pp-grid-item';
		}
		
		return implode( ' ', $classes );
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
		
		if ( has_post_thumbnail() ) {
			$image_id = get_post_thumbnail_id( get_the_ID() );
			//$pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail_size', $settings );
		} else {
			$pp_thumb_url = '';
		}
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

					<?php if ( $post_meta == 'yes' ) { ?>
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
					<?php } ?>
					
					<?php $this->render_excerpt(); ?>
					
					<?php $this->render_button(); ?>
				</div>
			</div>
		</div>
        <?php
    }

	/**
	 * Carousel Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$autoplay = $this->get_instance_value( 'autoplay' );
		$autoplay_speed = $this->get_instance_value( 'autoplay_speed' );
		$arrows = $this->get_instance_value( 'arrows' );
		$arrow = $this->get_instance_value( 'arrow' );
		$dots = $this->get_instance_value( 'dots' );
		$animation_speed = $this->get_instance_value( 'animation_speed' );
		$infinite_loop = $this->get_instance_value( 'infinite_loop' );
		$pause_on_hover = $this->get_instance_value( 'pause_on_hover' );
		$adaptive_height = $this->get_instance_value( 'adaptive_height' );
		$direction = $this->get_instance_value( 'direction' );

		$slides_to_show = ( $this->get_instance_value( 'columns' ) !== '' ) ? absint( $this->get_instance_value( 'columns' ) ) : 3;
		$slides_to_show_tablet = ( $this->get_instance_value( 'columns_tablet' ) !== '' ) ? absint( $this->get_instance_value( 'columns_tablet' ) ) : 2;
		$slides_to_show_mobile = ( $this->get_instance_value( 'columns_mobile' ) !== '' ) ? absint( $this->get_instance_value( 'columns_mobile' ) ) : 2;
		$slides_to_scroll = ( $this->get_instance_value( 'slides_to_scroll' ) !== '' ) ? absint( $this->get_instance_value( 'slides_to_scroll' ) ) : 1;
		$slides_to_scroll_tablet = ( $this->get_instance_value( 'slides_to_scroll_tablet' ) !== '' ) ? absint( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) : 1;
		$slides_to_scroll_mobile = ( $this->get_instance_value( 'slides_to_scroll_mobile' ) !== '' ) ? absint( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) : 1;
        
        $slider_options = [
            'slidesToShow'           => $slides_to_show,
            'slidesToScroll'         => $slides_to_scroll,
            'autoplay'               => ( $autoplay === 'yes' ),
            'autoplaySpeed'          => ( $autoplay_speed !== '' ) ? $autoplay_speed : 3000,
            'arrows'                 => ( $arrows === 'yes' ),
            'dots'                   => ( $dots === 'yes' ),
            'speed'                  => ( $animation_speed !== '' ) ? $animation_speed : 600,
            'infinite'               => ( $infinite_loop === 'yes' ),
            'pauseOnHover'           => ( $pause_on_hover === 'yes' ),
            'adaptiveHeight'         => ( $adaptive_height === 'yes' ),
        ];

        if ( $direction === 'right' ) {
			$slider_options['rtl'] = true;
		}

        if ( $arrows == 'yes' ) {
            if ( $arrow ) {
                $pa_next_arrow = $arrow;
                $pa_prev_arrow = str_replace("right","left",$arrow);
            }
            else {
                $pa_next_arrow = 'fa fa-angle-right';
                $pa_prev_arrow = 'fa fa-angle-left';
            }

            $slider_options['prevArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' . $pa_prev_arrow . '"></i></div>';
            $slider_options['nextArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' . $pa_next_arrow . '"></i></div>';
        }

        $slider_options['responsive'] = [
            [
                'breakpoint' => 1024,
                'settings' => [
                    'slidesToShow'      => $slides_to_show_tablet,
                    'slidesToScroll'    => $slides_to_scroll_tablet,
                ],
            ],
            [
                'breakpoint' => 768,
                'settings' => [
                    'slidesToShow'      => $slides_to_show_mobile,
                    'slidesToScroll'    => $slides_to_scroll_mobile,
                ]
            ]
        ];

        $this->parent->add_render_attribute(
            'posts-wrap',
            [
                'data-slider-settings' => wp_json_encode( $slider_options ),
            ]
        );
    }

    /**
	 * Render posts grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    public function render() {
        $settings = $this->parent->get_settings();
		
		$layout				= $this->get_instance_value( 'layout' );
		$pagination_type	= $this->get_instance_value( 'pagination_type' );
		$equal_height		= $this->get_instance_value( 'equal_height' );
		$direction			= $this->get_instance_value( 'direction' );
		$skin				= $this->get_id();
		$page_id			= '';
		
		if ( null != \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}
        
        $this->parent->add_render_attribute( 'posts-container', 'class', 'pp-posts-container' );
		
		if ( $pagination_type == 'infinite' ) {
			$this->parent->add_render_attribute( 'posts-container', 'class', 'pp-posts-infinite-scroll' );
		}
        
        $this->parent->add_render_attribute( 'posts-wrap', 'class', [
			'pp-posts',
			'pp-posts-skin-' . $this->get_id(),
			]
		);
		
		if ( $layout == 'carousel' ) {
			$this->parent->add_render_attribute( 'posts-wrap', 'class', ['pp-posts-carousel', 'pp-slick-slider'] );
			if ( $equal_height == 'yes' ) {
				$this->parent->add_render_attribute( 'posts-wrap', 'data-equal-height', 'yes' );
			}
			if ( $direction == 'right' ) {
				$this->parent->add_render_attribute( 'posts-wrap', 'dir', 'rtl' );
			}
		} else {
			$this->parent->add_render_attribute( 'posts-wrap', 'class', [
				'pp-elementor-grid',
				'pp-posts-grid',
				]
			);
		}
		
		$this->parent->add_render_attribute( 'posts-wrap', 'data-layout', $layout );
		$this->parent->add_render_attribute( 'posts-wrap', 'data-page', $page_id );
		$this->parent->add_render_attribute( 'posts-wrap', 'data-skin', $skin );
        
        $this->parent->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );
		
		// Filters
		$this->render_filters();
		
		if ( $layout == 'carousel' ) {
			$this->slider_settings();
		}
		?>
        <div <?php echo $this->parent->get_render_attribute_string( 'posts-container' ); ?>>
			<div <?php echo $this->parent->get_render_attribute_string( 'posts-wrap' ); ?>>
				<?php
					$i = 1;

					$this->parent->query_posts();
					$query = $this->parent->get_query();
					$total_pages = $query->max_num_pages;

					if ( $query->have_posts() ) : while ($query->have_posts()) : $query->the_post();

						$this->render_post_body();

					$i++;

					endwhile; endif; wp_reset_postdata();
				?>
			</div>
			<?php if ( 'load_more' == $pagination_type || 'infinite' == $pagination_type ) { ?>
			<div class="pp-posts-loader"></div>
			<?php } ?>
			
			<?php if ( 'carousel' != $layout ) { ?>
				<div class="pp-posts-pagination-wrap" data-total="<?php echo $total_pages; ?>">
					<?php
						$this->render_pagination();
					?>
				</div>
			<?php } ?>
        </div>
        <?php

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

			if ( 'masonry' === $layout ) {
				$this->render_editor_script();
			}
		}
    }

	/**
	 * Get masonry script.
	 *
	 * Returns the post masonry script.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_editor_script() {

        $settings = $this->parent->get_settings();
		
		$layout = $this->get_instance_value( 'layout' );

		if ( 'masonry' != $layout ) {
			return;
		}

		$layout = 'masonry';

		?>
		<script type="text/javascript">

			jQuery( document ).ready( function( $ ) {
				$( '.pp-posts-grid' ).each( function() {

					var $node_id 	= '<?php echo $this->parent->get_id(); ?>',
                        $scope 		= $( '[data-id="' + $node_id + '"]' ),
                        $selector 	= $(this);

					if ( $selector.closest( $scope ).length < 1 ) {
						return;
					}

					$selector.imagesLoaded( function() {

						$isotopeObj = $selector.isotope({
							layoutMode: '<?php echo $layout; ?>',
							itemSelector: '.pp-grid-item-wrap',
						});

						$selector.find( '.pp-grid-item-wrap' ).resize( function() {
							$isotopeObj.isotope( 'layout' );
						});
					});
				});
			});

		</script>
		<?php
	}
}