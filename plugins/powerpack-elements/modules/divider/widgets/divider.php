<?php
namespace PowerpackElements\Modules\Divider\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Divider Widget
 */
class Divider extends Powerpack_Widget {
    
    /**
	 * Retrieve divider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-divider';
    }

    /**
	 * Retrieve divider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Divider', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the divider widget belongs to.
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
	 * Retrieve divider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-divider power-pack-admin-icon';
    }

    /**
	 * Register divider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Divider
         */
        $this->start_controls_section(
            'section_buton',
            [
                'label'                 => __( 'Divider', 'powerpack' ),
            ]
        );
        
        $this->add_control(
			'divider_type',
			[
				'label'                 => esc_html__( 'Type', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'plain'        => [
						'title'    => esc_html__( 'Plain', 'powerpack' ),
						'icon'     => 'fa fa-ellipsis-h',
					],
					'text'         => [
						'title'    => esc_html__( 'Text', 'powerpack' ),
						'icon'     => 'fa fa-file-text-o',
					],
					'icon'         => [
						'title'    => esc_html__( 'Icon', 'powerpack' ),
						'icon'     => 'fa fa-certificate',
					],
					'image'        => [
						'title'    => esc_html__( 'Image', 'powerpack' ),
						'icon'     => 'fa fa-picture-o',
					],
				],
				'default'               => 'plain',
			]
		);

        $this->add_control(
            'divider_direction',
            [
                'label'                 => __( 'Direction', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'horizontal',
                'options'               => [
                   'horizontal'     => __( 'Horizontal', 'powerpack' ),
                   'vertical'       => __( 'Vertical', 'powerpack' ),
                ],
				'condition'             => [
					'divider_type'    => 'plain',
				],
            ]
        );

        $this->add_control(
            'divider_text',
            [
                'label'                 => __( 'Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Divider Text', 'powerpack' ),
				'condition'             => [
					'divider_type'    => 'text',
				],
            ]
        );

		$this->add_control(
			'divider_icon',
			[
				'label'                 => __( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'label_block'           => false,
				'default'               => 'fa fa-circle',
				'condition'             => [
					'divider_type'    => 'icon',
				],
			]
		);

        $this->add_control(
            'text_html_tag',
            [
                'label'                 => __( 'HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'span',
                'options'               => [
                    'h1'            => __( 'H1', 'powerpack' ),
                    'h2'            => __( 'H2', 'powerpack' ),
                    'h3'            => __( 'H3', 'powerpack' ),
                    'h4'            => __( 'H4', 'powerpack' ),
                    'h5'            => __( 'H5', 'powerpack' ),
                    'h6'            => __( 'H6', 'powerpack' ),
                    'div'           => __( 'div', 'powerpack' ),
                    'span'          => __( 'span', 'powerpack' ),
                    'p'             => __( 'p', 'powerpack' ),
                ],
				'condition'             => [
					'divider_type'    => 'text',
				],
            ]
        );
        
        $this->add_control(
            'divider_image',
            [
                'label'                 => __( 'Image', 'powerpack' ),
                'type'                  => Controls_Manager::MEDIA,
                'default'               => [
                    'url'           => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'divider_type'    => 'image',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'               => 'full',
				'separator'             => 'none',
				'condition'             => [
					'divider_type'    => 'image',
				],
			]
		);
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'center',
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
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: Divider
         */
        $this->start_controls_section(
            'section_divider_style',
            [
                'label'                 => __( 'Divider', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_control(
			'divider_vertical_align',
			[
				'label'                 => __( 'Vertical Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Center', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'powerpack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-text-wrap'   => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'condition'             => [
					'divider_type!'   => 'plain',
				],
			]
		);

        $this->add_control(
            'divider_style',
            [
                'label'                 => __( 'Style', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'dashed',
                'options'               => [
                   'solid'          => __( 'Solid', 'powerpack' ),
                   'dashed'         => __( 'Dashed', 'powerpack' ),
                   'dotted'         => __( 'Dotted', 'powerpack' ),
                   'double'         => __( 'Double', 'powerpack' ),
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-style: {{VALUE}};',
				],
            ]
        );
        
        $this->add_responsive_control(
			'horizontal_height',
			[
				'label'                 => __( 'Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px'       => [
						'min'  => 1,
						'max'  => 60,
					],
				],
				'default'               => [
					'size'     => 3,
					'unit'     => 'px',
				],
				'tablet_default'    => [
					'unit'     => 'px',
				],
				'mobile_default'    => [
					'unit'     => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'divider_type',
									'operator' => '==',
									'value' => 'plain',
								],
								[
									'name' => 'divider_direction',
									'operator' => '==',
									'value' => 'horizontal',
								],
							],
						],
						[
							'name' => 'divider_type',
							'operator' => '!=',
							'value' => 'plain',
						],
					],
				],
			]
		);
        
        $this->add_responsive_control(
			'vertical_height',
			[
				'label'                 => __( 'Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 500,
					],
					'%'           => [
						'min'      => 1,
						'max'      => 100,
					],
				],
				'default'               => [
					'size'         => 80,
					'unit'         => 'px',
				],
				'tablet_default'   => [
					'unit'         => 'px',
				],
				'mobile_default'   => [
					'unit'         => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'divider_type'		=> 'plain',
					'divider_direction'	=> 'vertical',
				],
			]
		);
        
        $this->add_responsive_control(
			'horizontal_width',
			[
				'label'                 => __( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 1200,
					],
				],
				'default'               => [
					'size'         => 300,
					'unit'         => 'px',
				],
				'tablet_default'   => [
					'unit'         => 'px',
				],
				'mobile_default'   => [
					'unit'         => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'divider_type',
									'operator' => '==',
									'value' => 'plain',
								],
								[
									'name' => 'divider_direction',
									'operator' => '==',
									'value' => 'horizontal',
								],
							],
						],
						[
							'name' => 'divider_type',
							'operator' => '!=',
							'value' => 'plain',
						],
					],
				],
			]
		);
        
        $this->add_responsive_control(
			'vertical_width',
			[
				'label'                 => __( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 100,
					],
				],
				'default'               => [
					'size'         => 3,
					'unit'         => 'px',
				],
				'tablet_default'   => [
					'unit'         => 'px',
				],
				'mobile_default'   => [
					'unit'         => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'divider_type'		=> 'plain',
					'divider_direction'	=> 'vertical',
				],
			]
		);

        $this->add_control(
            'divider_border_color',
            [
                'label'                 => __( 'Divider Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-color: {{VALUE}};',
                ],
				'condition'             => [
					'divider_type'    => 'plain',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_before_after_style' );

        $this->start_controls_tab(
            'tab_before_style',
            [
                'label'                 => __( 'Before', 'powerpack' ),
				'condition'             => [
					'divider_type!'   => 'plain',
				],
            ]
        );

        $this->add_control(
            'divider_before_color',
            [
                'label'                 => __( 'Divider Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
                'selectors'             => [
                    '{{WRAPPER}} .divider-border-left .divider-border' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_after_style',
            [
                'label'                 => __( 'After', 'powerpack' ),
				'condition'             => [
					'divider_type!'   => 'plain',
				],
            ]
        );

        $this->add_control(
            'divider_after_color',
            [
                'label'                 => __( 'Divider Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
                'selectors'             => [
                    '{{WRAPPER}} .divider-border-right .divider-border' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Text
         */
        $this->start_controls_section(
            'section_text_style',
            [
                'label'                 => __( 'Text', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type'    => 'text',
				],
            ]
        );
        
        $this->add_control(
			'text_position',
			[
				'label'                 => __( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => __( 'Left', 'powerpack' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => __( 'Center', 'powerpack' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => __( 'Right', 'powerpack' ),
						'icon'     => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
                'prefix_class'		    => 'pp-divider-'
			]
		);

        $this->add_control(
            'divider_text_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
				'condition'             => [
					'divider_type'    => 'text',
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-divider-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-divider-text',
				'condition'             => [
					'divider_type'    => 'text',
				],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'divider_text_shadow',
                'selector'              => '{{WRAPPER}} .pp-divider-text',
            ]
        );
        
        $this->add_responsive_control(
			'text_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type'    => 'text',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Icon
         */
        $this->start_controls_section(
            'section_icon_style',
            [
                'label'                 => __( 'Icon', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type'    => 'icon',
				],
            ]
        );
        
        $this->add_control(
			'icon_position',
			[
				'label'                 => __( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => __( 'Left', 'powerpack' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => __( 'Center', 'powerpack' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => __( 'Right', 'powerpack' ),
						'icon'     => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
                'prefix_class'		    => 'pp-divider-'
			]
		);

        $this->add_control(
            'divider_icon_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
				'condition'             => [
					'divider_type'    => 'icon',
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-divider-icon' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'icon_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'default'               => [
					'size' => 16,
					'unit' => 'px',
				],
				'condition'             => [
					'divider_type'    => 'icon',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'icon_rotation',
			[
				'label'                 => __( 'Icon Rotation', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 360,
					],
				],
				'default'               => [
					'unit' => 'px',
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-icon .fa' => 'transform: rotate( {{SIZE}}deg );',
				],
				'condition'             => [
					'divider_type'    => 'icon',
				],
			]
		);
        
        $this->add_responsive_control(
			'icon_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type'    => 'icon',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_image_style',
            [
                'label'                 => __( 'Image', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type'    => 'image',
				],
            ]
        );
        
        $this->add_control(
			'image_position',
			[
				'label'                 => __( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
                'prefix_class'		    => 'pp-divider-'
			]
		);
        
        $this->add_responsive_control(
			'image_width',
			[
				'label'                 => __( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 1200,
					],
				],
				'default'               => [
					'size' => 80,
					'unit' => 'px',
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'condition'             => [
					'divider_type'    => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'condition'             => [
					'divider_type'    => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'image_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type'    => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

    }

    /**
	 * Render divider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $classes = [ 'pp-divider' ];

        if ( $settings['divider_direction'] ) {
            $classes[] = 'pp-divider-' . $settings['divider_direction'];
            $classes[] = $settings['divider_direction'];
        }

        if ( $settings['divider_style'] ) {
            $classes[] = 'pp-divider-' . $settings['divider_style'];
            $classes[] = $settings['divider_style'];
        }

        $this->add_render_attribute( 'divider', 'class', $classes );
        
        $this->add_render_attribute( 'divider-content', 'class', 'pp-divider-' . $settings['divider_type'] );
        
        $this->add_inline_editing_attributes( 'divider_text', 'none' );
        $this->add_render_attribute( 'divider_text', 'class', 'pp-divider-' . $settings['divider_type'] );
        
        ?>
        <div class="pp-divider-wrap">
            <?php
            if ( $settings['divider_type'] == 'plain' ) { ?>
                <div <?php echo $this->get_render_attribute_string( 'divider' ); ?>></div>
                <?php
            }
            else { ?>
                <div class="divider-text-container">
                    <div class="divider-text-wrap">
                        <span class="pp-divider-border-wrap divider-border-left">
                            <span class="divider-border"></span>
                        </span>
                        <span class="pp-divider-content">
                            <?php if ( $settings['divider_type'] == 'text' && $settings['divider_text'] ) { ?>
                                <?php
                                    printf('<%1$s %2$s>%3$s</%1$s>', $settings['text_html_tag'], $this->get_render_attribute_string( 'divider_text' ), $settings['divider_text'] );
                                ?>
                            <?php } elseif ( $settings['divider_type'] == 'icon' && $settings['divider_icon'] ) { ?>
                                <span <?php echo $this->get_render_attribute_string( 'divider-content' ); ?>>
                                    <span class="<?php echo esc_attr( $settings['divider_icon'] ); ?>" aria-hidden="true"></span>
                                </span>
                            <?php } elseif ( $settings['divider_type'] == 'image' ) { ?>
                                <span <?php echo $this->get_render_attribute_string( 'divider-content' ); ?>>
                                    <?php
                                        $image = $settings['divider_image'];
                                        if ( $image['url'] ) {
                                            echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'divider_image' );
                                        }
                                    ?>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="pp-divider-border-wrap divider-border-right">
                            <span class="divider-border"></span>
                        </span>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>    
        <?php
    }

    /**
	 * Render divider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <div class="pp-divider-wrap">
            <# if ( settings.divider_type == 'plain' ) { #>
                <div class="pp-divider pp-divider-{{ settings.divider_direction }} {{ settings.divider_direction }} pp-divider-{{ settings.divider_style }} {{ settings.divider_style }} "></div>
            <# } else { #>
                <div class="divider-text-container">
                    <div class="divider-text-wrap">
                        <span class="pp-divider-border-wrap divider-border-left">
                            <span class="divider-border"></span>
                        </span>
                        <span class="pp-divider-content">
                            <# if ( settings.divider_type == 'text' && settings.divider_text != '' ) { #>
                                <{{ settings.text_html_tag }} class="pp-divider-{{ settings.divider_type }} elementor-inline-editing" data-elementor-setting-key="divider_text" data-elementor-inline-editing-toolbar="none">
                                    {{ settings.divider_text }}
                                </{{ settings.text_html_tag }}>
                            <# } else if ( settings.divider_type == 'icon' && settings.divider_icon != '' ) { #>
                                <span class="pp-divider-{{ settings.divider_type }}">
                                    <span class="{{ settings.divider_icon }}" aria-hidden="true"></span>
                                </span>
                            <# } else if ( settings.divider_type == 'image' ) { #>
                                <span class="pp-divider-{{ settings.divider_type }}">
                                    <#
                                    var image = {
                                        id: settings.divider_image.id,
                                        url: settings.divider_image.url,
                                        size: settings.image_size,
                                        dimension: settings.image_custom_dimension,
                                        model: view.getEditModel()
                                    };
                                    var image_url = elementor.imagesManager.getImageUrl( image );
                                    #>
                                    <img src="{{{ image_url }}}" />
                                </span>
                            <# } #>
                        </span>
                        <span class="pp-divider-border-wrap divider-border-right">
                            <span class="divider-border"></span>
                        </span>
                    </div>
                </div>
            <# } #>
        </div>
        <?php
    }
}