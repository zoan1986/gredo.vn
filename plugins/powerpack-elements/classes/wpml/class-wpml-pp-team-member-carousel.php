<?php

class WPML_PP_Team_Member_Carousel extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'team_member_details';
	}

	public function get_fields() {
		return array(
			'team_member_name',
			'team_member_position',
			'team_member_description',
			'facebook_url',
			'twitter_url',
        	'google_plus_url',
        	'linkedin_url',
        	'instagram_url',
        	'youtube_url',
        	'pinterest_url',
        	'dribbble_url',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'team_member_name':
				return esc_html__( 'Team Member Carousel - Name', 'powerpack' );
			case 'team_member_position':
				return esc_html__( 'Team Member Carousel - Team Member Position', 'powerpack' );
			case 'team_member_description':
				return esc_html__( 'Team Member Carousel - Team Member Description', 'powerpack' );
			case 'facebook_url':
				return esc_html__( 'Team Member Carousel - Facebook URL', 'powerpack' );
			case 'twitter_url':
				return esc_html__( 'Team Member Carousel - Twitter URL', 'powerpack' );
			case 'google_plus_url':
				return esc_html__( 'Team Member Carousel - Google_plus URL', 'powerpack' );
			case 'linkedin_url':
				return esc_html__( 'Team Member Carousel - Linkedin URL', 'powerpack' );
			case 'instagram_url':
				return esc_html__( 'Team Member Carousel - Instagram URL', 'powerpack' );
			case 'youtube_url':
				return esc_html__( 'Team Member Carousel - Youtube URL', 'powerpack' );
			case 'pinterest_url':
				return esc_html__( 'Team Member Carousel - Pinterest URL', 'powerpack' );
			case 'dribbble_url':
				return esc_html__( 'Team Member Carousel - Dribbble URL', 'powerpack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'team_member_name':
				return 'LINE';
			case 'team_member_position':
				return 'LINE';
			case 'team_member_description':
				return 'AREA';
			case 'facebook_url':
				return 'LINK';
			case 'twitter_url':
				return 'LINK';
			case 'google_plus_url':
				return 'LINK';
			case 'linkedin_url':
				return 'LINK';
			case 'instagram_url':
				return 'LINK';
			case 'youtube_url':
				return 'LINK';
			case 'pinterest_url':
				return 'LINK';
			case 'dribbble_url':
				return 'LINK';
			default:
				return '';
		}
	}
}
