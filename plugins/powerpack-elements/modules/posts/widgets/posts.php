<?php
namespace PowerpackElements\Modules\Posts\Widgets;

//use PowerpackElements\Base\Powerpack_Widget;

use PowerpackElements\Modules\Posts\Skins;

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
 * Posts Grid Widget
 */
class Posts extends Posts_Base {
    
    /**
	 * Retrieve posts grid widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-posts';
    }

    /**
	 * Retrieve posts grid widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Posts', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the posts grid widget belongs to.
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
	 * Retrieve posts grid widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-posts-grid power-pack-admin-icon';
    }

	/**
	 * Register Skins.
	 *
	 * @access protected
	 */
	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
		$this->add_skin( new Skins\Skin_Card( $this ) );
		$this->add_skin( new Skins\Skin_Creative( $this ) );
		$this->add_skin( new Skins\Skin_Event( $this ) );
		$this->add_skin( new Skins\Skin_News( $this ) );
		$this->add_skin( new Skins\Skin_Overlap( $this ) );
		$this->add_skin( new Skins\Skin_Portfolio( $this ) );
	}

	/*public function query_posts() {

	}*/

	/*protected function _register_controls() {
		parent::_register_controls();

		$this->register_query_section_controls();
		//$this->register_pagination_section_controls();
	}

	public function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();
	}*/
}