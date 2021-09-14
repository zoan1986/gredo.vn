<?php
namespace PowerpackElements\Modules\Posts\Widgets;

use PowerpackElements\Base\Powerpack_Widget;
use PowerpackElements\Classes\PP_Posts_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts Grid Widget
 */
abstract class Posts_Base extends Powerpack_Widget {

	/**
	 * @var \WP_Query
	 */
	protected $query = null;

	protected $_has_template_content = false;

    /**
	 * Retrieve posts grid widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eicon-posts-group power-pack-admin-icon';
    }

	public function get_script_depends() {
		return [
			'isotope',
			'imagesloaded',
			'jquery-slick',
			'powerpack-frontend-posts',
			'powerpack-pp-posts',
			'powerpack-frontend',
		];
	}

    /**
	 * Register posts grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    public function register_query_section_controls() {

        /**
         * Content Tab: Query
         */
        $this->start_controls_section(
            'section_query',
            [
                'label'             	=> __( 'Query', 'powerpack' ),
            ]
        );

		$this->add_control(
            'post_type',
            [
                'label'					=> __( 'Post Type', 'powerpack' ),
                'type'					=> Controls_Manager::SELECT,
                'options'				=> PP_Posts_Helper::get_post_types(),
                'default'				=> 'post',

            ]
        );
        
        $this->add_control(
            'posts_per_page',
            [
                'label'                 => __( 'Posts Per Page', 'powerpack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 6,
            ]
        );
		
		$post_types = PP_Posts_Helper::get_post_types();
		
		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );
			
			if ( ! empty( $taxonomy ) ) {

				foreach ( $taxonomy as $index => $tax ) {
					
					$terms = get_terms( $index );

					$tax_terms = array();

					if ( ! empty( $terms ) ) {

						foreach ( $terms as $term_index => $term_obj ) {

							$tax_terms[ $term_obj->term_id ] = $term_obj->name;
						}
						
						$tax_control_key = $index . '_' . $post_type_slug;
						
						// Taxonomy filter type
						$this->add_control(
							$index . '_' . $post_type_slug . '_filter_type',
							[
								/* translators: %s Label */
								'label'       => sprintf( __( '%s Filter Type', 'powerpack' ), $tax->label ),
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								'label_block' => true,
								'options'     => [
									/* translators: %s label */
									'IN'     => sprintf( __( 'Include %s', 'powerpack' ), $tax->label ),
									/* translators: %s label */
									'NOT IN' => sprintf( __( 'Exclude %s', 'powerpack' ), $tax->label ),
								],
                				'separator'         => 'before',
								'condition'   => [
									'post_type' => $post_type_slug,
								],
							]
						);

						// Add control for all taxonomies.
						$this->add_control(
							$tax_control_key,
							[
								'label'       => $tax->label,
								'type'        => Controls_Manager::SELECT2,
								'multiple'    => true,
								'default'     => '',
								'label_block' => true,
								'options'     => $tax_terms,
								'condition'   => [
									'post_type' => $post_type_slug,
								],
							]
						);
						
					}
				}
			}
		}
		
		$this->add_control(
			'author_filter_type',
			[
				'label'					=> __( 'Authors Filter Type', 'powerpack' ),
				'type'					=> Controls_Manager::SELECT,
				'default'				=> 'author__in',
				'label_block'			=> true,
                'separator'         	=> 'before',
				'options'				=> [
					'author__in'     => __( 'Include Authors', 'powerpack' ),
					'author__not_in' => __( 'Exclude Authors', 'powerpack' ),
				],
			]
		);

        $this->add_control(
            'authors',
            [
                'label'					=> __( 'Authors', 'powerpack' ),
                'type'					=> Controls_Manager::SELECT2,
				'label_block'			=> true,
				'multiple'				=> true,
				'options'				=> PP_Posts_Helper::get_users(),
            ]
        );
		
		$post_types = PP_Posts_Helper::get_post_types();
		
		foreach ( $post_types as $post_type_slug => $post_type_label ) {
		
			$posts_all = PP_Posts_Helper::get_all_posts_by_type( $post_type_slug );
						
			if ( $post_type_slug == 'post' ) {
				$posts_control_key = 'exclude_posts';
			} else {
				$posts_control_key = $post_type_slug . '_filter';
			}
		
			$this->add_control(
				$post_type_slug . '_filter_type',
				[
					'label'				=> sprintf( __( '%s Filter Type', 'powerpack' ), $post_type_label ),
					'type'				=> Controls_Manager::SELECT,
					'default'			=> 'post__not_in',
					'label_block'		=> true,
					'separator'         => 'before',
					'options'			=> [
						'post__in'     => sprintf( __( 'Include %s', 'powerpack' ), $post_type_label ),
						'post__not_in' => sprintf( __( 'Exclude %s', 'powerpack' ), $post_type_label ),
					],
					'condition'			=> [
						'post_type' => $post_type_slug,
					],
				]
			);
			
			$this->add_control(
				$posts_control_key,
				[
					/* translators: %s Label */
					'label'				=> $post_type_label,
					'type'				=> Controls_Manager::SELECT2,
					'default'			=> '',
					'multiple'			=> true,
					'label_block'		=> true,
					'options'			=> $posts_all,
					'condition'			=> [
						'post_type' => $post_type_slug,
					],
				]
			);
		}

		$this->add_control(
            'select_date',
            [
				'label'					=> __( 'Date', 'powerpack' ),
				'type'					=> Controls_Manager::SELECT,
				'options'				=> [
					'anytime'	=> __( 'All', 'powerpack' ),
					'today'		=> __( 'Past Day', 'powerpack' ),
					'week'		=> __( 'Past Week', 'powerpack' ),
					'month'		=> __( 'Past Month', 'powerpack' ),
					'quarter'	=> __( 'Past Quarter', 'powerpack' ),
					'year'		=> __( 'Past Year', 'powerpack' ),
					'exact'		=> __( 'Custom', 'powerpack' ),
				],
				'default'				=> 'anytime',
				'label_block'			=> false,
				'multiple'				=> false,
				'separator'				=> 'before',
			]
        );

		$this->add_control(
            'date_before',
            [
				'label'					=> __( 'Before', 'powerpack' ),
				'description'			=> __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'powerpack' ),
				'type'					=> Controls_Manager::DATE_TIME,
				'label_block'			=> false,
				'multiple'				=> false,
				'placeholder'			=> __( 'Choose', 'powerpack' ),
				'condition'				=> [
					'select_date' => 'exact',
				],
			]
        );


		$this->add_control(
            'date_after',
            [
				'label'					=> __( 'After', 'powerpack' ),
				'description'			=> __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'powerpack' ),
				'type'					=> Controls_Manager::DATE_TIME,
				'label_block'			=> false,
				'multiple'				=> false,
				'placeholder'			=> __( 'Choose', 'powerpack' ),
				'condition'				=> [
					'select_date' => 'exact',
				],
			]
        );

        $this->add_control(
            'order',
            [
                'label'					=> __( 'Order', 'powerpack' ),
                'type'					=> Controls_Manager::SELECT,
                'options'				=> [
                   'DESC'		=> __( 'Descending', 'powerpack' ),
                   'ASC'		=> __( 'Ascending', 'powerpack' ),
                ],
                'default'				=> 'DESC',
                'separator'				=> 'before',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'					=> __( 'Order By', 'powerpack' ),
                'type'					=> Controls_Manager::SELECT,
                'options'				=> [
                   'date'           => __( 'Date', 'powerpack' ),
                   'modified'       => __( 'Last Modified Date', 'powerpack' ),
                   'rand'           => __( 'Random', 'powerpack' ),
                   'comment_count'  => __( 'Comment Count', 'powerpack' ),
                   'title'          => __( 'Title', 'powerpack' ),
                   'ID'             => __( 'Post ID', 'powerpack' ),
                   'author'         => __( 'Post Author', 'powerpack' ),
                ],
                'default'				=> 'date',
            ]
        );
        
        $this->add_control(
            'sticky_posts',
            [
                'label'					=> __( 'Sticky Posts', 'powerpack' ),
                'type'					=> Controls_Manager::SWITCHER,
                'default'				=> '',
                'label_on'				=> __( 'Yes', 'powerpack' ),
                'label_off'				=> __( 'No', 'powerpack' ),
                'return_value'			=> 'yes',
                'separator'				=> 'before',
            ]
        );
        
        $this->add_control(
            'all_sticky_posts',
            [
                'label'					=> __( 'Show Only Sticky Posts', 'powerpack' ),
                'type'					=> Controls_Manager::SWITCHER,
                'default'				=> '',
                'label_on'				=> __( 'Yes', 'powerpack' ),
                'label_off'				=> __( 'No', 'powerpack' ),
                'return_value'			=> 'yes',
				'condition'				=> [
					'sticky_posts' => 'yes',
				],
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'					=> __( 'Offset', 'powerpack' ),
                'description'			=> __( 'Use this setting to skip this number of initial posts', 'powerpack' ),
                'type'					=> Controls_Manager::NUMBER,
                'default'				=> '',
                'separator'				=> 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
	 * Get post query arguments.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    public function query_posts_args( $filter = '' ) {
        $settings = $this->get_settings();
        $paged = $this->get_paged();
        $tax_count = 0;

		// Query Arguments
		$args = array(
			'post_status'           => array( 'publish' ),
			'post_type'             => $settings['post_type'],
			'orderby'               => $settings['orderby'],
			'order'                 => $settings['order'],
			'offset'                => $settings['offset'],
			'ignore_sticky_posts'   => ( 'yes' == $settings[ 'sticky_posts' ] ) ? 0 : 1,
			'showposts'             => $settings['posts_per_page'],
			'paged'					=> $paged,
		);
		
		// Author Filter
		if ( !empty( $settings['authors'] ) ) {
			$args[ $settings['author_filter_type'] ] = $settings['authors'];
		}
		
		// Posts Filter
		$post_type = $settings['post_type'];
						
		if ( $post_type == 'post' ) {
			$posts_control_key = 'exclude_posts';
		} else {
			$posts_control_key = $post_type . '_filter';
		}

		if ( !empty( $settings[$posts_control_key] ) ) {
			$args[ $settings[$post_type . '_filter_type'] ] = $settings[$posts_control_key];
		}
		
		// Taxonomy Filter
		$taxonomy = pp_get_post_taxonomies( $post_type );

		if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

			foreach ( $taxonomy as $index => $tax ) {
				
				$tax_control_key = $index . '_' . $post_type;

				if ( ! empty( $settings[ $tax_control_key ] ) ) {

					$operator = $settings[ $index . '_' . $post_type . '_filter_type' ];

					$args['tax_query'][] = [
						'taxonomy' => $index,
						'field'    => 'term_id',
						'terms'    => $settings[ $tax_control_key ],
						'operator' => $operator,
					];
				}
			}
		}
		
		$skin_id  = $settings['_skin'];
		
		if ( '' != $filter && '*' != $filter ) {
			$args['tax_query'][$tax_count]['taxonomy'] = $settings[$skin_id . '_tax_' . $post_type . '_filter'];
			$args['tax_query'][$tax_count]['field'] = 'slug';
			$args['tax_query'][$tax_count]['terms'] = $filter;
			$args['tax_query'][$tax_count]['operator'] = 'IN';
		}
		
		if ( $settings['select_date'] != 'anytime' ) {
			$select_date = $settings['select_date'];
			if ( ! empty( $select_date ) ) {
				$date_query = [];
				if ( $select_date == 'today' ) {
						$date_query['after'] = '-1 day';
				}
				elseif ( $select_date == 'week' ) {
						$date_query['after'] = '-1 week';
				}
				elseif ( $select_date == 'month' ) {
						$date_query['after'] = '-1 month';
				}
				elseif ( $select_date == 'quarter' ) {
						$date_query['after'] = '-3 month';
				}
				elseif ( $select_date == 'year' ) {
						$date_query['after'] = '-1 year';
				}
				elseif ( $select_date == 'exact' ) {
					$after_date = $settings['date_after'];
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}
					$before_date = $settings['date_before'];
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}
					$date_query['inclusive'] = true;
				}

				$args['date_query'] = $date_query;
			}
		}
		
		// Sticky Posts Filter
		if ( $settings['sticky_posts'] == 'yes' && $settings['all_sticky_posts'] == 'yes' ) {
			$post__in = get_option( 'sticky_posts' );
			
			$args['post__in'] = $post__in;
		}
		
		return $args;
	}

	public function query_posts( $filter = '' ) {
		//global $wp_query;
		$query_args  = $this->query_posts_args( $filter );
		//print_r($query_args);
		$this->query = new \WP_Query( $query_args );
	}

	/**
	 * Render current query.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	public function get_query() {

		return $this->query;
	}

	/**
	 * Returns the paged number for the query.
	 *
	 * @since 1.7.0
	 * @return int
	 */
	static public function get_paged() {

		global $wp_the_query, $paged;

		if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
			return $_POST['page_number'];
		}

		// Check the 'paged' query var.
		$paged_qv = $wp_the_query->get( 'paged' );

		if ( is_numeric( $paged_qv ) ) {
			if ( ! $paged_qv ) {
				$paged_qv = 1;
			}
			return $paged_qv;
		}

		// Check the 'page' query var.
		$page_qv = $wp_the_query->get( 'page' );

		if ( is_numeric( $page_qv ) ) {
			return $page_qv;
		}

		// Check the $paged global?
		if ( is_numeric( $paged ) ) {
			return $paged;
		}

		return 0;
	}

	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = [];

		$paged = $this->get_paged();

		$link_template = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}

	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post = get_post();
		$query_args = [];
		$url = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id'] = wp_unslash( $_GET['preview_id'] );
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_skin_field',
			[
				'label'					=> __( 'Skin', 'powerpack' ),
			]
		);

		$this->end_controls_section();

		$this->register_query_section_controls();
	}
}