<?php 

if(isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	switch($action) {
		case 'add_products' : om_add_products();break;
	}
}

function om_add_products(){
	$url = "https://api.omnivore.io/0.1/locations/cexe5e8i/menu/items";

	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=> "Api-Key: 28d62481841b4f398d444c967036a854" //. $omnivore_api_key
	  )
	);

	$context = stream_context_create($opts);

	$file = file_get_contents($url, false, $context);
	$menu_items = json_decode(utf8_encode($file),true);

	//echo '<br><br>';


	foreach(array_values($menu_items)[0][menu_items] AS $key => $value){
		//echo $value[id] . ' - ';
		//echo $value[name] . '<br>';

		if(get_post_status( $id ) === FALSE) {
			$post = array(
				'ID' => $value[id],
				'post_author' => '',
				'post_content' => '',
				'post_status' => "publish",
				'post_title' => $value[name],
				'post_parent' => '',
				'post_category' => "Demo",
				'post_type' => "product");

			$post_id = wp_insert_post($post, $wp_error);
		}

	}
}


?>