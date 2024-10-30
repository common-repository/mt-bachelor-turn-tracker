<?php
/*
Plugin Name: Mt. Bachelor Turn Tracker
Plugin URI: http://planetauz.com/
Description: A Wordpress plug-in for displaying turns on Mt. Bachelor.
Version: 2.2
Author: Auzzy
Author URI: http://www.auz1111.com
*/


/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : auz@auz1111.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



class turn_tracker_widget extends WP_Widget {


  // Set up the widget name and description.
  public function __construct() {
    $widget_options = array( 'classname' => 'turn_tracker_widget', 'description' => 'This widget is used to track turns on Mt. Bachelor.' );
    parent::__construct( 'turn_tracker_widget', 'Mt. Bachelor Turn Tracker', $widget_options );
  }


  // Create the widget output.
  public function widget( $args, $instance ) {

	$GLOBALS['passmediacode' . $this->number] = '';
	$GLOBALS['season' . $this->number] = '';
	  
	if($instance[ 'name' ] === null){
		$instance[ 'name' ] = "Auzzy";
	}else{
		$name = apply_filters( 'widget_title', $instance[ 'name' ] );
	}
	  
	if($instance['pass_id'] === null){
		$instance['pass_id'] = "MBA6484141";
	}else{
		$GLOBALS['passmediacode' . $this->number] = apply_filters( 'widget_title', $instance[ 'pass_id' ] );
	}
	  
	if($instance['season'] === null){
		$instance['season'] = "08-09";
	}else{
		$GLOBALS['season' . $this->number] = apply_filters( 'widget_title', $instance[ 'season' ] );
	}
	  

    echo $args['before_widget'] . $args['before_title'] . $name . " | Mt. Bachelor <span id='season_span-" . $this->number . "'>" . $GLOBALS['season' . $this->number] . "</span> Season" . $args['after_title']; 
	?>
   
    <!--<p><strong>Pass ID:</strong> <?php echo $GLOBALS['passmediacode' . $this->number]; ?></p>-->
    <!--<p><strong>Season Year:</strong> <?php echo $GLOBALS['season' . $this->number]; ?></p>-->
    
    <select id="season-<?php echo $this->number; ?>" name="season">
		<?php try { ?>
			<option <?php selected( $instance['season'], '16-17'); ?> value="16-17">2016-2017</option>
			<option <?php selected( $instance['season'], '15-16'); ?> value="15-16">2015-2016</option>
			<option <?php selected( $instance['season'], '14-15'); ?> value="14-15">2014-2015</option>
			<option <?php selected( $instance['season'], '13-14'); ?> value="13-14">2013-2014</option>
			<option <?php selected( $instance['season'], '12-13'); ?> value="12-13">2012-2013</option>
			<option <?php selected( $instance['season'], '11-12'); ?> value="11-12">2011-2012</option>
			<option <?php selected( $instance['season'], '10-11'); ?> value="10-11">2010-2011</option>
			<option <?php selected( $instance['season'], '09-10'); ?> value="09-10">2009-2010</option>
			<option <?php selected( $instance['season'], '08-09'); ?> value="08-09">2008-2009</option>
			<option <?php selected( $instance['season'], '07-08'); ?> value="07-08">2007-2008</option>
			<option <?php selected( $instance['season'], '06-07'); ?> value="06-07">2006-2007</option>
			<option <?php selected( $instance['season'], '05-06'); ?> value="05-06">2005-2006</option>
		<?php } catch(Exception $e){ ?>
			<option value="16-17">2016-2017</option>
			<option value="15-16">2015-2016</option>
			<option value="14-15">2014-2015</option>
			<option value="13-14">2013-2014</option>
			<option value="12-13">2012-2013</option>
			<option value="11-12">2011-2012</option>
			<option value="10-11">2010-2011</option>
			<option value="09-10">2009-2010</option>
			<option value="08-09">2008-2009</option>
			<option value="07-08">2007-2008</option>
			<option value="06-07">2006-2007</option>
			<option value="05-06">2005-2006</option>
		<?php } ?>
	</select>
	<br/><br/>
	<div id="mtbachelor-turn-tracker-<?php echo $this->number; ?>"></div>
    <div id="currentday-<?php echo $this->number; ?>" style="display:none;"><div id="inner-<?php echo $this->number; ?>"></div></div>
    
    <?php 
		echo $args['after_widget'];

		//get some external script that is needed for this script 
		wp_register_script('turn_tracker', plugin_dir_url( __FILE__ ) . '/js/turn-tracker.js', array ('jquery'), false, false);

		//always enqueue the script after registering or nothing will happen...duh.
		wp_enqueue_script('turn_tracker');


		wp_enqueue_style('turn_tracker_style', plugin_dir_url( __FILE__ ) . '/css/mtbachelor_turn_tracker.css');

		wp_enqueue_style('fancyStyle', plugin_dir_url( __FILE__ ) . 'css/jquery.fancybox.min.css');
		wp_enqueue_script('fancyScript', plugin_dir_url( __FILE__ ) . 'js/jquery.fancybox.min.js', array('jquery'), '', true );

	  	//var_dump($this);
	?>

	<script type="text/javascript">
		jQuery(function(){ 
			//console.log("Starting up " + "<?php echo $this->number; ?>" + "...");

			jQuery(document).ready(function() {
				//console.log("JQuery ready...");

				var loaderHTML = '<div style="margin-top:20%;width:100%;text-align:center;"><img src="wp-content/plugins/mtbachelor-turn-tracker/images/line-loader.gif"/></div>';
				
				
				setTimeout(
					function() {
						//do something special!
						getCurrentTurns('<?php echo $GLOBALS['passmediacode' . $this->number] ?>','<?php echo $GLOBALS['season' . $this->number] ?>','<?php echo $this->number ?>');
				 	}
				, (1000 * <?php echo $this->number ?> ) );
				
				

				jQuery('#season-' + '<?php echo $this->number ?>').on('change', function() {
					season = this.value;
					jQuery("#mtbachelor-turn-tracker-" + '<?php echo $this->number ?>').html(loaderHTML);
					jQuery("#season_span-" + '<?php echo $this->number ?>').html(season);
					getCurrentTurns('<?php echo $GLOBALS['passmediacode' . $this->number] ?>',season,'<?php echo $this->number ?>');
				});

				jQuery("#mtbachelor-turn-tracker-" + '<?php echo $this->number ?>' + ", #currentday-" + '<?php echo $this->number ?>' + " #inner-" + '<?php echo $this->number ?>').html(loaderHTML);




				var interval = setInterval(function () {
					
					if (jQuery("a.turnDateLink-" + "<?php echo $this->number ?>").length) {
						
						jQuery("a.turnDateLink-" + "<?php echo $this->number ?>").each(function() {
							
							jQuery("a.turnDateLink-" + "<?php echo $this->number ?>").fancybox({
								'type': 'modal',
								'beforeMove': resetLoader,
								'onComplete': function(instance, slide) {
									//console.log("Getting current day...");
									
									// Clicked element
									//console.info( slide.opts.$orig[0].innerText );
									
									jQuery("#currentday-" + '<?php echo $this->number ?>' + " #inner-" + '<?php echo $this->number ?>').html(loaderHTML);
					
									var currentday = slide.opts.$orig[0].innerText;
									var season = jQuery("#season_span-" + '<?php echo $this->number ?>').text();

									currentday = currentday.replace(/\d+/g, function(m){
										return "0".substr(m.length - 1) + m;
									});
									
									//console.log("About to run getCurrentTurns..." + '<?php echo $GLOBALS['passmediacode' . $this->number] ?>' + "|" + '<?php echo $GLOBALS['season' . $this->number] ?>' + "|" + currentday);

									getCurrentTurns('<?php echo $GLOBALS['passmediacode' . $this->number] ?>',season,'<?php echo $this->number ?>',currentday);
								},
								'fullScreen' : false
							});
			
						});
						
					}
					
				}, 10);


				function resetLoader(){
					//console.log("Resetting the loader animation...");
					jQuery("#currentday-" + '<?php echo $this->number ?>' + " #inner-" + '<?php echo $this->number ?>').html(loaderHTML);
				}


			});
		});
	</script>


	<?php
  }// End of Function: widget

  
  // Create the admin area widget settings form.
  public function form( $instance ) {
	$name = ! empty( $instance['name'] ) ? $instance['name'] : ''; ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'name' ); ?>">Rider Name:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo esc_attr( $name ); ?>" />
    </p>
    <?php
    $pass_id = ! empty( $instance['pass_id'] ) ? $instance['pass_id'] : ''; ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'pass_id' ); ?>">Mt. Bachelor Pass ID:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'pass_id' ); ?>" name="<?php echo $this->get_field_name( 'pass_id' ); ?>" value="<?php echo esc_attr( $pass_id ); ?>" />
    </p>
    
    <?php
	$season = ! empty( $instance['season'] ) ? $instance['season'] : 'select';
	$instance['season'] = $season
	?>
    <p>
    	<label for="<?php echo $this->get_field_id( 'season' ); ?> "><?php _e('Season:', 'season'); ?></label>
		<select id="<?php echo $this->get_field_id( 'season' ); ?>" name="<?php echo $this->get_field_name( 'season' ); ?>">
			<?php try { ?>
				<option <?php selected( $instance['season'], 'select'); ?> value="select">Select season</option> 
				<option <?php selected( $instance['season'], '16-17'); ?> value="16-17">2016-2017</option>
				<option <?php selected( $instance['season'], '15-16'); ?> value="15-16">2015-2016</option>
				<option <?php selected( $instance['season'], '14-15'); ?> value="14-15">2014-2015</option>
				<option <?php selected( $instance['season'], '13-14'); ?> value="13-14">2013-2014</option>
				<option <?php selected( $instance['season'], '12-13'); ?> value="12-13">2012-2013</option>
				<option <?php selected( $instance['season'], '11-12'); ?> value="11-12">2011-2012</option>
				<option <?php selected( $instance['season'], '10-11'); ?> value="10-11">2010-2011</option>
				<option <?php selected( $instance['season'], '09-10'); ?> value="09-10">2009-2010</option>
				<option <?php selected( $instance['season'], '08-09'); ?> value="08-09">2008-2009</option>
				<option <?php selected( $instance['season'], '07-08'); ?> value="07-08">2007-2008</option>
				<option <?php selected( $instance['season'], '06-07'); ?> value="06-07">2006-2007</option>
				<option <?php selected( $instance['season'], '05-06'); ?> value="05-06">2005-2006</option>
			<?php } catch(Exception $e){ ?>
				<option value="season" slected>Select season</option> 
				<option value="16-17">2016-2017</option>
				<option value="15-16">2015-2016</option>
				<option value="14-15">2014-2015</option>
				<option value="13-14">2013-2014</option>
				<option value="12-13">2012-2013</option>
				<option value="11-12">2011-2012</option>
				<option value="10-11">2010-2011</option>
				<option value="09-10">2009-2010</option>
				<option value="08-09">2008-2009</option>
				<option value="07-08">2007-2008</option>
				<option value="06-07">2006-2007</option>
				<option value="05-06">2005-2006</option>
			<?php } ?>
		</select>
    	</p>
    
    <?php
  }// End of Function: form


  // Apply settings to the widget instance.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
	$instance[ 'name' ] = strip_tags( $new_instance[ 'name' ] );
    $instance[ 'pass_id' ] = strip_tags( $new_instance[ 'pass_id' ] );
	$instance[ 'season' ] = strip_tags( $new_instance[ 'season' ] );
    return $instance;
  }

}

// Register the widget.
function register_Turn_Tracker_widget() { 
  register_widget( 'turn_tracker_widget' );
}
add_action( 'widgets_init', 'register_Turn_Tracker_widget' );

//Add widget link to plugin entry
add_filter('plugin_action_links', 'myplugin_plugin_action_links', 10, 2);

function myplugin_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/widgets.php">Widgets</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}


?>