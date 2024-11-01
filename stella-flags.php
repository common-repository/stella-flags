<?php
/*
Plugin Name: Stella Flags Widget
Plugin URI: http://bitbucket.org/rafallach/stella-flags-plugin
Description: Creates language selector with country flags. Based on Stella Widget tutorial by  Artem Popov. 
Version: 1.0
Author: Rafal Lach
Author URI: http://rafallach.pl
License: GPL2
*/

class Stella_Flags extends WP_Widget{
	function __construct() {
		parent::__construct(
			'stella_flags', 
			'Stella Flags',
			array( 'description' => 'Language sellector with image country flags') 
		);
		$this->lang_list = apply_filters( 'stella_get_lang_list', array() );
	}
	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Language' ) : $instance['title'], $instance, $this->id_base);
		$before_widget .= '<div  class="flags">';
		$after_widget = '</div>' . $after_widget;

		echo $before_widget;

		if ( ! empty( $title ) ) {
			if ( '<h3 class="widget-title">' == $before_title )
				$before_title = '<span>';

			if ( '</h3>' == $after_title )
				$after_title = '</span>';

			echo $before_title . $title . $after_title;
		}
		echo $this->__get_switcher_html( $args['widget_id'] );
		echo $after_widget;
	}
	private function __get_switcher_html( $id ) {
		$html = '';
		if( isset( $this->lang_list ) ){
			foreach ( $this->lang_list as $prefix=>$item ){
				$href = is_ssl() ? 'https://'.$item['href'] : 'http://'.$item['href'];
				$class = ( STELLA_CURRENT_LANG == $prefix ) ? "_on": '';
				$path = trailingslashit( plugins_url( basename( dirname( __FILE__ ) ) ) );
				$html.= '<li><a href="'.$href.'"><img src="'.$path.'images/'.$item['title'].''.$class.'.gif" title="'.$item['title'].'" alt="'.$item['title'].'"/></a></li>';
			}
		}
		return  '<ul>' . $html . '</ul>';
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "Stella_Flags" );' ));

class Stella_Flags_Styles {
	function __construct() {
		add_action('wp_enqueue_scripts', array( $this, 'add_scripts' ) );

	}
	function add_scripts() {
		$path = trailingslashit( plugins_url( basename( dirname( __FILE__ ) ) ) );
		wp_enqueue_style( 'stella_flags', $path . "/css/styles.css", null, '1.0'  );
	}
}
new Stella_Flags_Styles();