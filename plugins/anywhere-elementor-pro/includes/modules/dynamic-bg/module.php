<?php
namespace Aepro\Modules\DynamicBg;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Aepro\Aepro_Control_Manager;
use AePro\AePro;
class Module{
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action('elementor/element/section/section_advanced/after_section_end',[ $this, 'add_fields'],10,2);
        add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'add_fields'], 10, 2);

        add_action('elementor/frontend/element/before_render',[ $this, 'before_section_render'],10,1);

        add_action('elementor/frontend/section/before_render',[ $this, 'before_section_render'],10,1);
        add_action('elementor/frontend/column/before_render',[ $this, 'before_section_render'],10,1);

    }

    public function add_fields($element, $args){
        
        //if ( ('section' === $element->get_name() && 'section_background' === $section_id) || ('column' === $element->get_name() && 'section_style' === $section_id)) {

            $element->start_controls_section(
                'post_featured_bg',
                [
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO,
                    'label' => __( 'Dynamic Background', 'ae-pro' ),
                ]
            );

            $element->add_control(
                'show_featured_bg',
                [
                    'label' => __( 'Background Image', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Show', 'ae-pro' ),
                    'label_off' => __( 'Hide', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-featured-bg-'
                ]
            );
            $ae_featured_bg_source[''] = __('Select','ae-pro');
            $ae_featured_bg_source['post'] = __('Post','ae-pro');
            if(class_exists('ACF') || class_exists('acf')){
                $ae_featured_bg_source['custom_field'] = __('Post Custom Field','ae-pro');
                $ae_featured_bg_source['term_custom_field'] = __('Term Custom Field', 'ae-pro');
                $ae_featured_bg_source['option_page_custom_field'] = __( 'Option Page Custom Field', 'ae-pro' );
            }

            $element->add_control(
                'ae_featured_bg_source',
                [
                    'label'         => __('Source','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $ae_featured_bg_source,
                    'default'       => 'post',
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                    ],
                    'prefix_class'  => 'ae-featured-bg-source-',
                    'condition'     => [
                        'show_featured_bg' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'is_featured_bg_group_field',
                [
                    'label' => __( 'Is Group Field', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-featured-bg-',
                    'condition'     => [
                        'ae_featured_bg_source!' => 'post',
                        'show_featured_bg' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_cf_parent_key',
                [
                    'label' => __( 'Group Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Group Field Key', 'ae-pro' ),
                    'default' => '',
                    'condition' => [
                        'ae_featured_bg_source!' => 'post',
                        'show_featured_bg' => 'yes',
                        'is_featured_bg_group_field' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_cf_field_key',
                [
                    'label' => __( 'Custom Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Custom Field Key', 'ae-pro' ),
                    'default' => '',
                    'prefix_class' => 'ae-feature-bg-custom-field-',
                    'condition' => [
                        'ae_featured_bg_source!' => ['post',''],
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );


            $element->add_control(
                'ae_featured_image_size',
                [
                    'label'         => __('Image Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => AePro::$_helper->ae_get_intermediate_image_sizes(),
                    'default'       => 'large',
                    'prefix_class'  => 'ae-featured-img-size-',
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_size',
                [
                    'label'         => __('Background Size','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => array(
                        'auto'   => __('Auto','ae-pro'),
                        'cover'   => __('Cover','ae-pro'),
                        'contain'   => __('Contain','ae-pro')
                    ),
                    'default'       => 'cover',
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_position',
                [
                    'label'         => __('Background Position','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'top left' => __( 'Top Left', 'ae-pro' ),
                        'top center' => __( 'Top Center', 'ae-pro' ),
                        'top right' => __( 'Top Right', 'ae-pro' ),
                        'center left' => __( 'Center Left', 'ae-pro' ),
                        'center center' => __( 'Center Center', 'ae-pro' ),
                        'center right' => __( 'Center Right', 'ae-pro' ),
                        'bottom left' => __( 'Bottom Left', 'ae-pro' ),
                        'bottom center' => __( 'Bottom Center', 'ae-pro' ),
                        'bottom right' => __( 'Bottom Right', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-position: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_attachment',
                [
                    'label'         => __('Background Attachment','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'scroll' => __( 'Scroll', 'ae-pro' ),
                        'fixed' => __( 'Fixed', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-attachment: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_featured_bg_repeatae_featured_bg_repeat',
                [
                    'label'         => __('Background Repeat','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options' => [
                        '' => __( 'Default', 'ae-pro' ),
                        'no-repeat' => __( 'No-repeat', 'ae-pro' ),
                        'repeat' => __( 'Repeat', 'ae-pro' ),
                        'repeat-x' => __( 'Repeat-x', 'ae-pro' ),
                        'repeat-y' => __( 'Repeat-y', 'ae-pro' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'background-repeat: {{VALUE}};',
                    ],
                    'condition'     => [
                        'show_featured_bg' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_section_column_background_alert',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'ae_pro_alert',
                    'raw' => __( Aepro::$_helper->get_widget_admin_note_html( "Know more about Section/Column Backgrounds", "https://wpvibes.link/go/dynamic-section-column-backgrounds" ) , 'ae-pro' ),
                    'separator' => 'none',
                ]
            );

            if(class_exists('ACF') || class_exists('acf')){

                $element->add_control(
                    'bg_image_divider',
                    [
                        'type' => Controls_Manager::DIVIDER,
                        'style' => 'thick',
                    ]
                );

                $element->add_control(
                    'enable_bg_color',
                    [
                        'label' => __( 'Background Color', 'ae-pro' ),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => '',
                        'label_on' => __( 'Show', 'ae-pro' ),
                        'label_off' => __( 'Hide', 'ae-pro' ),
                        'return_value' => 'yes',
                        'prefix_class'  => 'ae-bg-color-'
                    ]
                );

            
                $ae_bg_color_source['custom_field'] = __('Post Custom Field','ae-pro');
                $ae_bg_color_source['term_custom_field'] = __('Term Custom Field', 'ae-pro');
                $ae_bg_color_source['option_page_custom_field'] = __( 'Option Page Custom Field', 'ae-pro' );

                $element->add_control(
                    'ae_bg_color_source',
                    [
                        'label'         => __('Source','ae-pro'),
                        'type'          => Controls_Manager::SELECT,
                        'options'       => $ae_bg_color_source,
                        'default'       => 'custom_field',
                        'prefix_class'  => 'ae-bg-color-source-',
                        'condition'     => [
                            'enable_bg_color' => 'yes',
                        ]
                    ]
                );

                $element->add_control(
                'is_bg_color_group_field',
                [
                    'label' => __( 'Is Group Field', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-featured-bg-',
                    'condition'     => [
                        'ae_bg_color_source!' => 'post',
                        'enable_bg_color' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'ae_bg_color_cf_parent_key',
                [
                    'label' => __( 'Group Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Group Field Key', 'ae-pro' ),
                    'default' => '',
                    'condition' => [
                        'ae_bg_color_source!' => 'post',
                        'enable_bg_color' => 'yes',
                        'is_bg_color_group_field' => 'yes'
                    ]
                ]
            );

                $element->add_control(
                    'ae_bg_color_cf_field_key',
                    [
                        'label' => __( 'Custom Field key', 'ae-pro' ),
                        'type' => Controls_Manager::TEXT,
                        'placeholder' => __( 'Custom Field Key', 'ae-pro' ),
                        'default' => '',
                        'prefix_class' => 'ae-bg-color-custom-field-',
                        'condition' => [
                            'enable_bg_color' => 'yes'
                        ]
                    ]
                );
            }

            $element->end_controls_section();

            $element->start_controls_section(
                'dynamic_link_section',
                [
                    'tab' => Aepro_Control_Manager::TAB_AE_PRO,
                    'label' => __( 'Dynamic Link', 'ae-pro' ),
                ]
            );


            $element->add_control(
                'enable_link',
                [
                    'label' => __( 'Enable Link', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-link-',
                ]
            );


            $element->add_control(
                'dynamic_link_source',
                [
                    'label'         => __('Links To','ae-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'post'              => __('Post', 'ae-pro'),
                        'custom_field_url'  => __('Custom Field (URL)', 'ae-pro'),
                        'static_url'        => __('Static Url', 'ae-pro')
                    ],
                    'default'       => 'post',
                    'condition'     => [
                        'enable_link' => 'yes',
                    ]
                ]
            );

            $element->add_control(
                'dynamic_link_custom_field',
                [
                    'label' => __( 'Custom Field key', 'ae-pro' ),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __( 'Custom Field Key', 'ae-pro' ),
                    'default' => '',
                    'condition' => [
                        'dynamic_link_source' => 'custom_field_url',
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'static_url',
                [
                    'label'         => __('Url', 'ae-pro'),
                    'type'          => Controls_Manager::TEXT,
                    'placeholder'   => __('https://example.com', 'ae-pro'),
                    'default'       => '',
                    'condition'     => [
                        'dynamic_link_source' => 'static_url',
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'enable_open_in_new_window',
                [
                    'label' => __( 'Enable Open In New Window', 'ae-pro' ),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __( 'Yes', 'ae-pro' ),
                    'label_off' => __( 'No', 'ae-pro' ),
                    'return_value' => 'yes',
                    'prefix_class'  => 'ae-new-window-',
                    'condition'     => [
                        'enable_link' => 'yes'
                    ]
                ]
            );

            $element->add_control(
                'ae_section_column_clickable_alert',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'content_classes' => 'ae_pro_alert',
                    'raw' => __( Aepro::$_helper->get_widget_admin_note_html( "Know more about Section/Column Clickable", "https://wpvibes.link/go/section-column-clickable" ) , 'ae-pro' ),
                    'separator' => 'none',
                ]
            );

            $element->end_controls_section();
        //}
    }

    function before_section_render($element){



        if($element->get_settings( 'show_featured_bg' ) == 'yes') {

            $img_size = $element->get_settings('ae_featured_image_size');
            $img_source = $element->get_settings('ae_featured_bg_source');
            $image = '';
            switch ($img_source) {
                case 'post'         :
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size);
                    if(is_array($img)) {
                        $image = $img[0];
                    }

                    break;

                case 'custom_field' :
                    if (!class_exists('ACF') && !class_exists('acf')) {
                        $image = '';
                        break;
                    }
                    
                    $img = wp_get_attachment_image_src(get_field($element->get_settings('ae_featured_bg_cf_field_key'), get_the_ID()), $img_size);
                    $repeater = Aepro::$_helper->is_repeater_block_layout();
                    if($repeater['is_repeater']){
                        $img_id = $this->is_bg_field_in_repeater($element, $img, 'ae_featured_bg_cf_field_key');
                        $img = wp_get_attachment_image_src($img_id, $img_size);
                    }
                    
                    if($element->get_settings('is_featured_bg_group_field') == 'yes'){
                        $group_field = get_field($element->get_settings('ae_featured_bg_cf_parent_key'), get_the_ID());
                        $img = wp_get_attachment_image_src($group_field[$element->get_settings('ae_featured_bg_cf_field_key')], $img_size);
                    }

                    if(is_array($img)){
                        $image = $img[0];
                    }
	                
                    break;

                case 'term_custom_field' :
                    if (!class_exists('ACF') && !class_exists('acf')) {
                        $image = '';
                        break;
                    }

                    if (Plugin::instance()->editor->is_edit_mode()) {
                        $term = Aepro::$_helper->get_preview_term_data();
                    } else {
                        $term = get_queried_object();
                    }

                    $img = wp_get_attachment_image_src(get_field($element->get_settings('ae_featured_bg_cf_field_key'), $term), $img_size);
                    if(is_array($img)){
                        $image = $img[0];
                    }
                    break;

                case 'option_page_custom_field' :
                    if (!class_exists('ACF') && !class_exists('acf')) {
                        $image = '';
                        break;
                    }
                    
                    $img = wp_get_attachment_image_src(get_field($element->get_settings('ae_featured_bg_cf_field_key'), 'option', true), $img_size);
                    $repeater = Aepro::$_helper->is_repeater_block_layout();
                    if($repeater['is_repeater']){
                        $img_id = $this->is_bg_field_in_repeater($element, $img, 'ae_featured_bg_cf_field_key');
                        $img = wp_get_attachment_image_src($img_id, $img_size);
                    }
                    
                    if($element->get_settings('is_featured_bg_group_field') == 'yes'){
                        $group_field = get_field($element->get_settings('ae_featured_bg_cf_parent_key'), 'option', true);
                        $img = wp_get_attachment_image_src($group_field[$element->get_settings('ae_featured_bg_cf_field_key')], $img_size);
                    }

                    if(is_array($img)){
                        $image = $img[0];
                    }
                    break;

                default                 :
                    $image = '';
            }

            if(!empty($image)) {
                $element->add_render_attribute('_wrapper', [
                    'data-ae-bg' => $image,
                ]);
            }
        }

        if($element->get_settings('enable_bg_color') == 'yes'){
            $color_source = $element->get_settings('ae_bg_color_source');
            $color = '';
            switch ($color_source) {
                case 'custom_field' :
                    $color = get_field($element->get_settings('ae_bg_color_cf_field_key'), get_the_ID());
                    $color = $this->is_bg_field_in_repeater($element, $color, 'ae_bg_color_cf_field_key');
                    $color = $this->is_bg_field_in_group($element, $color);
                    break;
                case 'term_custom_field' :
                    if (Plugin::instance()->editor->is_edit_mode()) {
                        $term = Aepro::$_helper->get_preview_term_data();
                    } else {
                        $term = get_queried_object();
                    }

                    $color = get_field($element->get_settings('ae_bg_color_cf_field_key'), $term);
                    break;
                case 'option_page_custom_field':
                    $color = get_field($element->get_settings('ae_bg_color_cf_field_key'), 'option', true);
                    $color = $this->is_bg_field_in_repeater($element, $color, 'ae_bg_color_cf_field_key');
                    $color = $this->is_bg_field_in_group($element, $color);
                    break;
                }
            $element->add_render_attribute( '_wrapper', [
                    'data-ae-bg-color' => $color
            ]);
        }

        if( $element->get_settings('enable_link') == 'yes'){
            $link_source = $element->get_settings('dynamic_link_source');

            switch ($link_source){
                case 'post'                 :       $bg_link = get_permalink();
                                                    break;
                case 'custom_field_url'     :       $bg_link = get_post_meta(get_the_id(), $element->get_settings('dynamic_link_custom_field'), true);
									                $repeater = Aepro::$_helper->is_repeater_block_layout();
									                if($repeater['is_repeater']){
										                if(isset($repeater['field'])){
											                $repeater_field = get_field($repeater['field'], get_the_ID());
											                $bg_link = $repeater_field[0][$element->get_settings('dynamic_link_custom_field')];

										                }else {
											                $bg_link = get_sub_field($element->get_settings('dynamic_link_custom_field'));
										                }
									                }
                                                    break;
                case 'static_url'           :       $bg_link = $element->get_settings('static_url');
                                                    break;
                default                     :       $bg_link = '';
            }

            $element->add_render_attribute( '_wrapper', [
                'data-ae-url' => $bg_link,
            ] );

        }

    }

    function is_bg_field_in_repeater($element, $bg, $field_key){
        $repeater = Aepro::$_helper->is_repeater_block_layout();
        if($repeater['is_repeater']){
            if(isset($repeater['field'])){
                $repeater_field = get_field($repeater['field'], get_the_ID());
                $bg = $repeater_field[0][$element->get_settings($field_key)];

            }else {
                $bg = get_sub_field($element->get_settings($field_key));
            }
        }
        return $bg;
    }

    function is_bg_field_in_group($element, $bg){
        if($element->get_settings('is_bg_color_group_field') == 'yes'){
            $parent_field = get_field($element->get_settings('ae_bg_color_cf_parent_key'), get_the_ID());
            $bg = $parent_field[$element->get_settings('ae_bg_color_cf_field_key')];
        }
        return $bg;
    }

}