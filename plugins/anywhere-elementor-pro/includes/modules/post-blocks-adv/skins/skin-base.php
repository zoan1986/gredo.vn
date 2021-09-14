<?php
namespace Aepro\Modules\PostBlocksAdv\Skins;

use Aepro\Aepro;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Aepro\Modules\PostBlocksAdv\Classes;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base
{

    protected function _register_controls_actions()
    {

    }

    public function register_controls( Widget_Base  $widget){

        $this->parent = $widget;

    }

    public function if_layout_is_blank(){
        $settings = $this->parent->get_settings_for_display();
        if(!isset($settings['layout']) || empty($settings['layout'])){
            printf( '<div class"message"><p class="%1$s">%2$s</p></div>', esc_attr( "elementor-alert elementor-alert-warning" ), esc_html( __("Please select a Block Layout first from 'Content > Layout > Primary Block Layout'",'ae-pro') ) );
        }
    }

    public function render_item( $layout ){

        $with_css = false;
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            $with_css = true;
        }
        $settings = $this->parent->get_settings_for_display();
        $post_data = Aepro::$_helper->get_demo_post_data();

        
        // Add Dynamic Classes Hook
        $custom_classes = apply_filters('aepro/postblocksadv/post-class', [], get_the_ID() );
        
        $item_classes = array_merge(['ae-post-item', 'ae-post-item-' . $this->parent->get_id()], $custom_classes);

        if($settings['_skin'] == 'carousel'){
            $item_classes[] = 'ae-swiper-slide swiper-slide';   
        }

        // Post Item Attributess
        $this->parent->set_render_attribute('item', 'class', $item_classes);

        $item_inner_classes = ['ae-post-item-inner'];

        //WooCommerce Sales Badge
        if(isset($settings['sale_badge_switcher'])  && $settings['sale_badge_switcher'] == 'yes'){
            $item_inner_classes[] = 'badge-type-'.$settings['sale_badge_type'];
        }

        if($settings['_skin'] == 'carousel'){
            $item_inner_classes[] = 'ae-swiper-slide-wrapper';   
        }

        // Item Inner Attribute
        $this->parent->set_render_attribute('item-inner', 'class', $item_inner_classes);
        
        if(isset($settings['sale_badge_switcher'])  && $settings['sale_badge_switcher'] == 'yes'){
            $this->parent->set_render_attribute( 'on-sale', 'class', 'onsale' );
            $sale_badge_img = '';
            $sale_badge_text = '';
            if($settings['sale_badge_type'] == 'image') {
                $this->parent->add_render_attribute('on-sale', 'class', 'sale-badge-image');
                switch ($settings['sale_badge_source']) {
                    case 'default' :
                        $sale_badge_img = $settings['sale_badge_icon']['url'];
                        break;
                    case 'custom_field' :
                        $custom_field = get_post_meta($post_data->ID, $settings['sale_badge_custom_field_image'], true);
                        $sale_badge_img = wp_get_attachment_url($custom_field);
                        if ($sale_badge_img == '') {
                            $sale_badge_img = $settings['sale_badge_custom_field_image_fallback']['url'];
                        }
                        break;
                }
                $this->parent->set_render_attribute('on-sale', 'style', "background-image: url('" . $sale_badge_img . "');");
            }else{
                switch ($settings['sale_badge_source']) {
                    case 'default' :
                        $sale_badge_text = do_shortcode($settings['sale_badge_title']);
                        break;
                    case 'custom_field' :
                        $sale_badge_text = get_post_meta( $post_data->ID, $settings['sale_badge_custom_field_text'], true);
                        if($sale_badge_text == ''){
                            $sale_badge_text = $settings['sale_badge_custom_field_text_fallback'];
                        }
                        break;
                }

                if($sale_badge_text != '') {
                    $sale_badge_text = $settings['sale_badge_prefix'] . $sale_badge_text . $settings['sale_badge_suffix'];
                }
            }
        }

        ?>

        <article <?php echo $this->parent->get_render_attribute_string('item'); ?> >
            <div <?php echo $this->parent->get_render_attribute_string('item-inner'); ?>>
                <div class="ae_data elementor elementor-<?php echo $layout; ?>">
                    <?php
                    //WooCommerce Sales Badge
                    if ( class_exists( 'WooCommerce' ) ) {
                        if ($settings['sale_badge_switcher'] == 'yes') {
                            if(Aepro::$_helper->ae_is_product_on_sale(get_the_ID())){
                                if($sale_badge_text != '' || $sale_badge_img != ''){
                                ?>
                                <div class="ae-sale-badge-wrapper">
                                    <span <?php echo $this->parent->get_render_attribute_string('on-sale'); ?> >
                                        <?php echo $sale_badge_text; ?>
                                    </span>
                                </div>
                            <?php }
                            }
                        }
                    }

                    echo Plugin::instance()->frontend->get_builder_content( $layout, $with_css );

                    ?>
                </div>
            </div>
        </article>

        <?php

    }

    function get_layout($seq, $settings, $sg_layout = 0){

        $layout             = $settings['layout'];
        $alt_layout         = $this->get_instance_value('alt_layout');
        $infinite_scroll    = $this->get_instance_value('show_infinite_scroll');
        $grid_layout        = $this->get_instance_value('grid_layout');

        if ( is_paged() && $infinite_scroll == 'yes' && $grid_layout == 'smart-grid' ) {
            return $layout;
        }

        switch ($grid_layout){

            case 'smart-grid' : if(in_array($seq, $sg_layout) && $alt_layout != ''){
                                    $layout = $alt_layout;
                                }
                                break;

            case 'checker-board' : if($this->get_instance_value('columns') % 2 != 0){
                                        // col count is odd - just play even-odd
                                        if($seq % 2 == 0){
                                            $layout = $alt_layout;
                                        }
                                    }else{
                                        // more complex
                                        $row = ceil($seq / $this->get_instance_value('columns'));
                                        if($row%2 == 0){
                                            if($seq % 2 == 0){
                                                $layout = $alt_layout;
                                            }
                                        }else{
                                            if($seq % 2 == 1){
                                                $layout = $alt_layout;
                                            }
                                        }
                                    }

        }

        
        return $layout;

    }

    function get_filters_html($settings){
        if($settings['show_filters'] == 'yes' && $settings['source'] != 'current_loop'){
            if($settings['filter_taxonomy'] == ''){
                printf( '<div class"message"><p class="%1$s">%2$s</p></div>', esc_attr( "elementor-alert elementor-alert-warning" ), esc_html( __("Please select taxonomy from 'Content > Filters > Taxonomy'", 'ae-pro') ) );
                return false;
            }

            $filter_terms = Aepro::$_helper->get_filter_bar_filters($settings);
            if(is_array($filter_terms) && count($filter_terms)){

                $collapse_filter_after['desktop'] = $settings['collapse_filter_after'];
                $collapse_filter_after['tablet'] = $settings['collapse_filter_after_tablet'];
                $collapse_filter_after['mobile'] = $settings['collapse_filter_after_mobile'];
                $collapse_after = [];
                $collapse_after_class = [];

                foreach ($collapse_filter_after as $key => $value) {
                    if(!in_array($value, $collapse_after)) {
                        $collapse_after[] = $value;
                        $collapse_after_class[$value] = $key;
                    }else{
                        $collapse_after_class[$value] = $collapse_after_class[$value] . ' ' . $key;
                    }
                }

                foreach($collapse_after as $key => $value){ ?>
                <div class="aep-filter-bar <?php echo $settings['collapse_filter'] == 'yes' ? 'collapse' : ''; ?> <?php echo $collapse_after_class[$value]; ?>">
                    <?php
                    if($settings['filter_label'] != ''){
                        ?><div class="filter-label"><?php echo $settings['filter_label']; ?></div>
                        <?php
                    }
                    $first_term_element = 0;
                    $active_filter_class = '';

                    // Check if Default Term is selected
                    if($settings[$settings['filter_taxonomy'] . '_filter_default_term' ] != '') {
                        $first_term_element = $settings[$settings['filter_taxonomy'] . '_filter_default_term'];
                    }
                    
                    // Check if there is any Term ID in $_POST
                    if(isset($_POST['term_id'])){
                        $first_term_element = $_POST['term_id'];
                    }

                    // Check if 'All' Tab is Enabled
                    if($settings['show_all'] == 'yes'){ ?>
                        <div class="filter-items <?php echo (!$first_term_element)?'active':''; ?>"><a  href="#" data-term-id="0"><?php echo __($settings['tab_all_text'], 'ae-pro'); ?></a></div>
                    <?php
                    }else{
                        if(!$first_term_element){
                            $first_term_element = $filter_terms[0]->term_id;
                        }
                    }
                    $index = 1;

                    foreach($filter_terms as $term){
                        if($settings['collapse_filter'] == 'yes') {
                            if ($index > $collapse_after[$key]) {
                                continue;
                            }
                            $index++;
                        }?>
                        <div class="filter-items <?php echo ((isset($_POST['term_id']) && $_POST['term_id'] == $term->term_id) || ($term->term_id == $first_term_element && !isset($_POST['term_id'])) )?'active':''; ?>">
                            <a href="#" data-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a>
                        </div>
                    <?php
                    }
                    if($settings['collapse_filter'] == 'yes') {
                    ?>
                        <div class="filter-items ae-dropdown">
                            <a href="#" data-term-id="other">
                                <span class="dropdown-filter-text"><?php echo $settings['collapse_filter_text']; ?></span>
                                <?php Icons_Manager::render_icon($settings['collapse_filter_icon'], ['aria-hidden' => 'true']); ?>
                            </a>

                            <?php
                            $index = 1;
                             ?>
                            <ul class="ae-menu hide">
                            <?php
                                foreach($filter_terms as $term){
                                    if ($index <= $collapse_after[$key]) {
                                        $index++;
                                        continue;
                                    }
                                    ?>
                                    <li>
                                        <div class="filter-items <?php echo ((isset($_POST['term_id']) && $_POST['term_id'] == $term->term_id) || ($term->term_id == $first_term_element && !isset($_POST['term_id'])) )?'active':''; ?>">
                                            <a href="#" data-term-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a>
                                        </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
            }
        }
    }

    /* function get_filters($settings){

        $filter_taxonomy = $settings['filter_taxonomy'];
        // check if post have terms selected for this taxonomy

        if(isset($settings[$filter_taxonomy . '_' . $settings['source'] . '_include_term_ids']) && is_array($settings[$filter_taxonomy . '_' . $settings['source'] . '_include_term_ids']) && count($settings[$filter_taxonomy . '_' . $settings['source'] . '_include_term_ids'])){
            // just return the list of these terms
            $terms = get_terms( $filter_taxonomy, [
                'hide_empty' => true,
                'term_taxonomy_id' => $settings[$filter_taxonomy . '_' . $settings['source'] . '_include_term_ids'],
                'orderby' => $settings['filter_term_order_by'],
                'order' => $settings['filter_term_order']
            ]);
        }else{
            $terms = get_terms( [
                'taxonomy' => $filter_taxonomy,
                'exclude' => $settings[$filter_taxonomy . '_filter_exclude_term_ids'],
                'parent' => $settings['only_parent_term'],
                'orderby' => $settings['filter_term_order_by'],
                'order' => $settings['filter_term_order']
            ]);
        }
        return $terms;
    } */

    function ae_no_post_message($settings){
            if(trim($settings['no_posts_message']) == ''){
                return false;
            }
            return '<div class="ae-no-posts">' . do_shortcode($settings['no_posts_message']) . '</div>';
    }
}
