<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       meccaproduction.com
 * @since      1.0.0
 *
 * @package    Meccaproduction
 * @subpackage Meccaproduction/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php
		if( isset( $_GET[ 'tab' ] ) ) {
		    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'custom_style';
		}else{
			$active_tab = 'custom_style';
		}
	?>

    <!-- <h2 class="nav-tab-wrapper">
            <a href="?page=meccaproduction&tab=custom_style" class="nav-tab <?php //echo $active_tab == 'custom_style' ? 'nav-tab-active' : ''; ?>">Custom Style</a>
            <a href="?page=meccaproduction&tab=smart_delivery" class="nav-tab <?php //echo $active_tab == 'smart_delivery' ? 'nav-tab-active' : ''; ?>">Smart Delivery</a>
    </h2> -->

    <form method="post" name="cleanup_options" action="options.php">

    	<table class="form-table">
    		<tbody>

    	<?php
    		$options = get_option($this->plugin_name);

    		$mp_custom_css = $options['mp_custom_css'];

    		$use_google_maps_api = $options['use_google_maps_api'];

    		$delivery_distance = $options['delivery_distance'];

    		$google_geocoding_api_key = $options['google_geocoding_api_key'];
    		$google_distance_matrix_api_key = $options['google_distance_matrix_api_key'];

    		$pickup_address1 = $options['pickup_address1'];
    		$pickup_city = $options['pickup_city'];
    		$pickup_state = $options['pickup_state'];

    		$number_cooks = $options['number_cooks'];
    		$number_drivers = $options['number_drivers'];
    		$max_pizza_fullfillment = $options['max_pizza_fullfillment'];

	    	if ($use_google_maps_api && $active_tab == 'smart_delivery') {

	    		if(!empty($options['pickup_lat'] ) && !empty($options['pickup_long'])) {
	    			$pickup_lat = $options['pickup_lat'];
	    			$pickup_long = $options['pickup_long'];
	    		}

	    		if(!empty($google_geocoding_api_key ) && !empty($pickup_address1) && !empty($pickup_city) && !empty($pickup_state) && empty($options['pickup_lat'] ) && empty($options['pickup_long'])){
	    			$pickup_location = getLatLong($google_geocoding_api_key, $pickup_address1, $pickup_city, $pickup_state);

	    			$pickup_lat = array_values($pickup_location)[0][0][geometry][location][lat];
	    			$pickup_long = array_values($pickup_location)[0][0][geometry][location][lng];

	    			$options['pickup_lat'] = $pickup_lat;
	    			$options['pickup_long'] = $pickup_long;
	    		}

	    		if($_GET["debug"] == 1) {

	    			$destination_location = getLatLong($google_geocoding_api_key, '555 N Pleasantburg Drive', 'Greenville', 'SC');

					$destination_lat = array_values($destination_location)[0][0][geometry][location][lat];
					$destination_long = array_values($destination_location)[0][0][geometry][location][lng];

		    		$tripArray = array_values(getDistanceBetweenAddresses($google_distance_matrix_api_key, $pickup_lat. ",". $pickup_long, $destination_lat . "," . $destination_long));

		    		$travel_time = $tripArray[2][0][elements][0][duration][text];
		    		$travel_distance = $tripArray[2][0][elements][0][distance][text];
		    		$source = $tripArray[1][0];
		    		$destination = $tripArray[0][0];


		    		echo "Source: " . $source . "<br>";
		    		echo "Source (Lat,Long): " . $pickup_lat . ", " . $pickup_long . "<br>";
		    		echo "Destination: " . $destination . "<br>";
		    		echo "Destination (Lat,Long): " . $destination_lat . ", " . $destination_long . "<br>";
		    		echo "<br>";
		    		echo "Travel Time: " . $travel_time . "<br>";
		    		echo "Distance (miles): " . $travel_distance . "<br>";
	    		}
	    	}
    		

    	?>

    	<?php
	        settings_fields($this->plugin_name);
	        do_settings_sections($this->plugin_name);
	    ?>


	    <!-- remove some meta and generators from the <head> -->
	    <?php //if($active_tab == 'custom_style') {?>
		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-mp_custom_css">Use custom Mecca Production CSS</label>
		    	</th>
		    	<td>
		    		<input type="checkbox" id="<?php echo $this->plugin_name;?>-mp_custom_css" name="<?php echo $this->plugin_name;?>[mp_custom_css]" value="1" <?php checked( $mp_custom_css, 1 ); ?> />
		    	</td>
		    </tr>
	    <?php //} ?>

	    <?php //if($active_tab == 'smart_delivery') {?>

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-use_google_maps_api">Turn on Smart Delivery?</label>
		    	</th>
		    	<td>
		    		<input type="checkbox" id="<?php echo $this->plugin_name;?>-use_google_maps_api" name="<?php echo $this->plugin_name;?>[use_google_maps_api]" value="1" <?php checked( $use_google_maps_api, 1 ); ?> />
		    	</td>
		    </tr>

		    <div class="meccaproduction-google-maps-section">

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-google_geocoding_api_key">Google Maps Geocoding API Key</label>
		    	</th>
		    	<td>
		    		<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-google_geocoding_api_key" name="<?php echo $this->plugin_name; ?>[google_geocoding_api_key]" value="<?php if(!empty($google_geocoding_api_key)) echo $google_geocoding_api_key; ?>"/>
		    	</td>
		    </tr>

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-google_distance_matrix_api_key">Google Maps Distance Matrix API Key</label>
		    	</th>
		    	<td>
		    		<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-google_distance_matrix_api_key" name="<?php echo $this->plugin_name; ?>[google_distance_matrix_api_key]" value="<?php if(!empty($google_distance_matrix_api_key)) echo $google_distance_matrix_api_key; ?>"/>
		    	</td>
		    </tr>

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-delivery_distance">Maximum Delivery Distance (in miles)</label>
		    	</th>
		    	<td>
		    		<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-delivery_distance" name="<?php echo $this->plugin_name; ?>[delivery_distance]" value="<?php if(!empty($delivery_distance)) echo $delivery_distance; ?>"/>
		    	</td>
		    </tr>

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-pickup_address1">Pickup Address Line 1</label>
		    	</th>
		    	<td>
		    		<input type="text" class="medium-text" id="<?php echo $this->plugin_name; ?>-pickup_address1" name="<?php echo $this->plugin_name; ?>[pickup_address1]" value="<?php if(!empty($pickup_address1)) echo $pickup_address1; ?>"/>
		    	</td>
		    </tr>
		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-">Pickup City</label>
		    	</th>
		    	<td>
		    		<input type="text" class="medium-text" id="<?php echo $this->plugin_name; ?>-pickup_city" name="<?php echo $this->plugin_name; ?>[pickup_city]" value="<?php if(!empty($pickup_city)) echo $pickup_city; ?>"/>
		    	</td>
		    </tr>
		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-">Pickup State (ST)</label>
		    	</th>
		    	<td>
		    		<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-pickup_state" name="<?php echo $this->plugin_name; ?>[pickup_state]" value="<?php if(!empty($pickup_state)) echo $pickup_state; ?>"/>
		    	</td>
		    </tr>

		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-">Number of Cooks</label>
		    	</th>
		    	<td>
		    		<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-number_cooks" name="<?php echo $this->plugin_name; ?>[number_cooks]" value="<?php if(!empty($number_cooks)) echo $number_cooks; ?>"/>
		    	</td>
		    </tr>
		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-">Number of Drivers</label>
		    	</th>
		    	<td>
		    		<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-number_drivers" name="<?php echo $this->plugin_name; ?>[number_drivers]" value="<?php if(!empty($number_drivers)) echo $number_drivers; ?>"/>
		    	</td>
		    </tr>
		    <tr>
		    	<th scope ="row">
		    		<label for="<?php echo $this->plugin_name;?>-">Max Number of Pizzas at Once</label>
		    	</th>
		    	<td>
		    		<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-max_pizza_fullfillment" name="<?php echo $this->plugin_name; ?>[max_pizza_fullfillment]" value="<?php if(!empty($max_pizza_fullfillment)) echo $max_pizza_fullfillment; ?>"/>
		    	</td>
		    </tr>
		    <?php if(!empty($pickup_lat)){ ?>
		    	<tr>
		    		<th>Pickup Latitude, Longitude</th>
		    		<td><?php echo $pickup_lat; ?>, <?php echo $pickup_long; ?></td>
		    	</tr>
		    <?php } ?>

	    <?php //} ?>


	    </tbody>
	    </table>


	    <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>


    </form>
</div>



<?php


function getLatLong($APIKey, $address1, $city, $state) {

	if(!empty($APIKey)){
		if(!empty($address1) && !empty($city) && !empty($state)){
			$googleURL = "https://maps.googleapis.com/maps/api/geocode/";
			$format = "json";
			$address = str_replace(" " , "+", $address1) . ",+" . str_replace(" " , "+", $city) . ",+" . $state;

			$fullGoogleURL = $googleURL . $format . "?address=". $address . "&key=" . $google_api_key;

			$du = file_get_contents($fullGoogleURL);
		    $djd = json_decode(utf8_encode($du),true);

		    return $djd;
		}
	} 

}

function getDistanceBetweenAddresses($APIKey, $from, $to){

	if(!empty($APIKey)){
		if(!empty($from) && !empty($to)){

			$googleURL = "https://maps.googleapis.com/maps/api/distancematrix/";
			$format = "json";
			$origins = $from;
			$destinations = $to;

			$fullGoogleURL = $googleURL . $format . "?origins=". $origins . "&destinations=" . $destinations . "&key=" . $APIKey . "&units=imperial";

			if($_GET["debug"] == 1) {
				echo "<br>URL Request: " . $fullGoogleURL . "<br><br>";
			}

			$du = file_get_contents($fullGoogleURL);
		    $djd = json_decode(utf8_encode($du),true);

		    return $djd;
		}
	}

}



?>