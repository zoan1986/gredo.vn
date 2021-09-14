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
 * News Skin for Posts widget
 */
class Skin_News extends Skin_Base {
    
    /**
	 * Retrieve Skin ID.
	 *
	 * @access public
	 *
	 * @return string Skin ID.
	 */
    public function get_id() {
        return 'news';
    }

    /**
	 * Retrieve Skin title.
	 *
	 * @access public
	 *
	 * @return string Skin title.
	 */
    public function get_title() {
        return __( 'News', 'powerpack' );
    }

	/**
	 * Register Control Actions.
	 *
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();
		
		add_action( 'elementor/element/pp-posts/news_section_image/before_section_end', [ $this, 'add_news_image_controls' ] );
	}
	
	protected function register_layout_content_controls() {
		parent::register_layout_content_controls();
		
		$this->update_control(
            'layout',
            [
                'label'                 => __( 'Layout', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'grid'		=> __( 'Grid', 'powerpack' ),
                   'masonry'	=> __( 'Masonry', 'powerpack' ),
                ],
                'default'               => 'grid',
            ]
        );

        $this->update_responsive_control(
            'columns',
            [
                'label'                 => __( 'Columns', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '2',
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
                'frontend_available'    => true,
            ]
        );
	}
	
	protected function register_style_image_controls() {
		parent::register_style_image_controls();
		
		$this->update_control(
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
					'{{WRAPPER}} .pp-post-thumbnail' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'default'				=> [
					'size' => 20,
				],
				'condition'				=> [
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				],
			]
		);
	}

	public function add_news_image_controls() {
		
		$this->add_control(
			'image_position',
			[
				'label'                 => __( 'Image Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'			=> false,
				'options'               => [
					'left'		=> [
						'title' => __( 'Left', 'powerpack' ),
						'icon'	=> 'eicon-h-align-left',
					],
					'right'		=> [
						'title' => __( 'Right', 'powerpack' ),
						'icon'	=> 'eicon-h-align-right',
					],
				],
				'default'               => 'left',
				'selectors'				=> [
					'{{WRAPPER}} .pp-post-thumbnail'   => 'float: {{VALUE}};',
				],
			]
		);
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
        
		$post_meta = $this->get_instance_value( 'post_meta' );
		?>
		<div class="<?php echo $this->get_item_wrap_classes(); ?>">
			<div class="<?php echo $this->get_item_classes(); ?>">
				<?php 
					$this->render_post_thumbnail();
				?>
				<div class="pp-post-content">
					<?php 
						if ( $post_meta == 'yes' ) {
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
}