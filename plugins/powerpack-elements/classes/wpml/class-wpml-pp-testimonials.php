<?php

class WPML_PP_Testimonials extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	public function get_fields() {
		return array(
			'content',
			'name',
			'position',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'content':
				return esc_html__( 'Testimonials - Content', 'powerpack' );
			case 'name':
				return esc_html__( 'Testimonials - User Name', 'powerpack' );
			case 'position':
				return esc_html__( 'Testimonials - User Position', 'powerpack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'content':
				return 'AREA';
			case 'name':
				return 'LINE';
			case 'position':
				return 'LINE';
			default:
				return '';
		}
	}

}
