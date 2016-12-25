<?php
class Smooth_Slider_Simple_Widget extends WP_Widget {
	function Smooth_Slider_Simple_Widget() {
		$widget_options = array('classname' => 'sslider_wclass', 'description' => 'Insert Smooth Slider' );
		parent::__construct('sslider_wid', 'Smooth Slider - Simple', $widget_options);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
	    global $smooth_slider;
		
		echo $before_widget;
		if($smooth_slider['multiple_sliders'] == '1') {
		$slider_id = empty($instance['slider_id']) ? '1' : apply_filters('widget_slider_id', $instance['slider_id']);
		}
		else{
		 $slider_id = '1';
		}

		echo $before_title . $after_title; 
		 get_smooth_slider($slider_id);
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	    global $smooth_slider;
		$instance = $old_instance;
		if($smooth_slider['multiple_sliders'] == '1') {
		   $instance['slider_id'] = strip_tags($new_instance['slider_id']);
		}

		return $instance;
	}

	function form($instance) {
	    global $smooth_slider;
		if($smooth_slider['multiple_sliders'] == '1') {
			$instance = wp_parse_args( (array) $instance, array( 'slider_id' => '' ) );
			$slider_id = strip_tags($instance['slider_id']);
			$sliders = ss_get_sliders();
			$sname_html='<option value="0" selected >Select the Slider</option>';
	 
		  foreach ($sliders as $slider) { 
			 if($slider['slider_id']==$slider_id){$selected = 'selected';} else{$selected='';}
			 $sname_html =$sname_html.'<option value="'.$slider['slider_id'].'" '.$selected.'>'.$slider['slider_name'].'</option>';
		  } 
	?>
				<p><label for="<?php echo $this->get_field_id('slider_id'); ?>">Select Slider Name: <select class="widefat" id="<?php echo $this->get_field_id('slider_id'); ?>" name="<?php echo $this->get_field_name('slider_id'); ?>"><?php echo $sname_html;?></select></label></p>
<?php  }
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Smooth_Slider_Simple_Widget");') );
?>
