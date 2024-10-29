<?php

/*

Plugin Name: Auto RSS Scroller

Plugin URI: http://themecrest.com/?page_id=123

Description: Auto RSS Feed Scroller Widget by <a href="http://themecrest.com">Themecrest</a>. 

Author: Themecrest

Version: 1.0

Author URI: http://themecrest.com

*/

//add_action('rss_scroller_style',  wp_enqueue_style( 'rss_scroller_style',  get_bloginfo('wpurl')."/wp-content/plugins/rss-feed-scroller-widget/rss_scroller_style.css", array(), '1.0', 'all' ), 0 );

//add_action('rss_scroller_script',  wp_enqueue_script( 'rss_scroller_script',  get_bloginfo('wpurl')."/wp-content/plugins/rss-feed-scroller-widget/script.js", false, false, false ), 0 );  



add_action('rss_scroller_script_auto',  wp_enqueue_script( 'rss_scroller_script_auto',  get_bloginfo('wpurl')."/wp-content/plugins/auto-rss-feed-scroller-widget/autoscroll.js", false, false, false ), 0 );

add_action('rss_scroller_style_auto',  wp_enqueue_style( 'rss_scroller_style_auto',  get_bloginfo('wpurl')."/wp-content/plugins/auto-rss-feed-scroller-widget/autoscroll.css", array(), '1.0', 'all' ), 0 );



//add_action('rss_scroller_script_auto_google',  wp_enqueue_script( 'rss_scroller_script_auto_google',  "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js", false, false, false ), 0 );

add_action('rss_scroller_script_auto_google',  wp_enqueue_script( 'rss_scroller_script_auto_google',  get_bloginfo('wpurl')."/wp-content/plugins/auto-rss-feed-scroller-widget/jquery.google.js", false, false, false ), 0 );







class RssScroller extends WP_Widget {



function RssScroller() {

  parent::WP_Widget(false, $name = 'RSS Scroller');	

}



function widget($args, $instance) {		



  extract( $args );  

  $title = apply_filters('widget_title', $instance['title']);

  $url = apply_filters('widget_url', $instance['url']);

  $count = apply_filters('widget_count', $instance['count']);

  $width = apply_filters('widget_width', $instance['width']);

  $height = apply_filters('widget_height', $instance['height']);

 $pause = apply_filters('widget_pause', $instance['pause']);

  echo $before_widget;

  

  if ( $title ) echo $before_title . $title . $after_title;

               

    $source = @file($url); 

    $ticker = implode ("", $source);

    preg_match_all("|<item>(.*)</item>|Uism",$ticker, $items, PREG_PATTERN_ORDER);

      if (count($items[1])==0) {

      preg_match_all("|<item .*>(.*)</item>|Uism",$ticker, $items, PREG_PATTERN_ORDER);

    }    

	echo "<script>

	var sagscroller_constants={

	navpanel: {height:'16px', downarrow:'".get_bloginfo('wpurl')."/wp-content/plugins/auto-rss-feed-scroller-widget/down.gif', opacity:0.6, title:'Go to Next Content', background:'black'},

	loadingimg: {src:'".get_bloginfo('wpurl')."/wp-content/plugins/auto-rss-feed-scroller-widget/ajaxloading.gif', dimensions:[100,15]}

}

			var sagscroller2=new sagscroller({

	id:'mysagscroller2',

	mode: 'auto',

	pause: ".$pause.",

	animatespeed: 400 

	})

	</script>";

    echo "<div id=\"mysagscroller2\" class=\"sagscroller\" style=\"width:".$width."px; height:".$height."px;\"><ul>";    

    

    if($count==0 || $count==""){

    for ($i=0; $i<count($items[1]); $i++) {

      preg_match_all("|<title>(.*)</title>(.*)<link>(.*)</link>|Uism",$items[1][$i], $regs, PREG_PATTERN_ORDER);

      echo "<li><a href=\"".$regs[3][0]."\" title=\"".$regs[1][0]."\">".$regs[1][0]."</a></li><dt>";

    }

    }



    else{

    for ($i=0; $i<$count; $i++) {

      preg_match_all("|<title>(.*)</title>(.*)<link>(.*)</link>|Uism",$items[1][$i], $regs, PREG_PATTERN_ORDER);

      echo "<li><a href=\"".$regs[3][0]."\" target=\"_blank\">".$regs[1][0]."</a></li>";

    }        

    }     

    

    echo "<ul></div>";

    echo $after_widget;

    

  }



    function update($new_instance, $old_instance) {				

    	 $instance = $old_instance;

	     $instance['title'] = strip_tags($new_instance['title']);

	     $instance['url'] = strip_tags($new_instance['url']);

	     $instance['count'] = strip_tags($new_instance['count']);

       $instance['width'] = strip_tags($new_instance['width']);	

       $instance['height'] = strip_tags($new_instance['height']);		
	   
	    $instance['pause'] = strip_tags($new_instance['pause']);		     

       return $instance;

    }



    function form($instance) {				

        $title = esc_attr($instance['title']);

        $url= esc_attr($instance['url']);

        $count = esc_attr($instance['count']);

        $width = esc_attr($instance['width']); 

        $height = esc_attr($instance['height']);         
		
		$pause = esc_attr($instance['pause']);         

?>



<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Feed URL:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Feed Count:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label>

<span style="color:#ff0000;">0 = View all</span></p>

<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height'); ?> <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" /></label></p>

<p><label for="<?php echo $this->get_field_id('pause'); ?>"><?php _e('Pause'); ?> <input class="widefat" id="<?php echo $this->get_field_id('pause'); ?>" name="<?php echo $this->get_field_name('pause'); ?>" type="text" value="<?php echo $pause; ?>" /></label>
<span style="color:#ff0000;">1000 = 1 second</span></p>

<?php 

}

} 

add_action('widgets_init', create_function('', 'return register_widget("RssScroller");'));

?>