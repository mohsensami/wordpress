<?php
/*
	Module Name: Arqam Lite
	Plugin URI: https://codecanyon.net/user/TieLabs/portfolio
*/

/*-----------------------------------------------------------------------------------*/
# Get Plugin Options and Transient
/*-----------------------------------------------------------------------------------*/

define ('ARQAM_LITE_Plugin' , 'Arqam Lite' );


$arq_lite_transient	=	get_transient( 'arq_lite_counters' );
$arq_lite_options	=	get_option( 'arq_lite_options' );
if( empty($arq_lite_options)	)	$arq_lite_options = array();
if( empty($arq_lite_transient) || (false ===  $arq_lite_transient) )	$arq_lite_transient = array();

$arq_lite_data = array();



/*-----------------------------------------------------------------------------------*/
# Store Defaults settings
/*-----------------------------------------------------------------------------------*/
if ( is_admin() && isset($_GET['activate'] ) && $pagenow == 'plugins.php' ) {
	global $arq_lite_options;

	if( empty ( $arq_lite_options ) ){

		$default_data = array(
			'social' => array(
				'facebook' 		=> array( 'text' => __( 'Fans',			'tie' ) ),
				'twitter' 		=> array( 'text' => __( 'Followers',	'tie' ) ),
				'youtube'		=> array( 'text' => __( 'Subscribers',	'tie' ) ,'type' => 'User'),
				'vimeo' 		=> array( 'text' => __( 'Subscribers',	'tie' ) ),
				'dribbble' 		=> array( 'text' => __( 'Followers',	'tie' ) ),
				'soundcloud'  	=> array( 'text' => __( 'Followers',	'tie' ) ),
				'behance'  		=> array( 'text' => __( 'Followers',	'tie' ) ),
				'github'  		=> array( 'text' => __( 'Followers',	'tie' ) ),
				'instagram'  	=> array( 'text' => __( 'Followers',	'tie' ) ),
				'rss'  			=> array( 'text' => __( 'Subscribers',	'tie' ) ),
			),
		);

		update_option( 'arq_lite_options' , $default_data);
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Data From API's
/*-----------------------------------------------------------------------------------*/
function arq_lite_remote_get( $url , $json = true) {
	$get_request 	= wp_remote_get( $url , array( 'timeout' => 18 , 'sslverify' => false ) );
	$request 		= wp_remote_retrieve_body( $get_request );
	if( $json ) $request = @json_decode( $request , true );
	return $request;
}


/*-----------------------------------------------------------------------------------*/
# Update Options and Transient
/*-----------------------------------------------------------------------------------*/
function arq_lite_update_count( $data ){
	global $arq_lite_options, $arq_lite_transient ;
	$cache = 12;

	if( is_array($data) ){
		foreach( $data as $item => $value ){
			$arq_lite_transient[$item] = $value;

			if( empty( $arq_lite_options['data'] ) || ! is_array( $arq_lite_options['data'] ) ){
				$arq_lite_options['data'] = array();
			}

			$arq_lite_options['data'][$item] = $value;
		}
	}

	set_transient( 'arq_lite_counters', $arq_lite_transient , $cache*60*60 );
	update_option( 'arq_lite_options' , $arq_lite_options );
}


/*-----------------------------------------------------------------------------------*/
# Number Format Function
/*-----------------------------------------------------------------------------------*/
function arq_lite_format_num( $number ){
	if( !is_numeric( $number ) ) return $number ;

	if($number >= 1000000){
		return round( ($number/1000)/1000 , 1) . "M";
	}elseif($number >= 100000){
		return round( $number/1000, 0) . "k";
	}else{
		return @number_format( $number );
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Social Counters
/*-----------------------------------------------------------------------------------*/
function arq_lite_get_counters( $style = '' ){
	global $arq_lite_data, $arq_lite_options, $arq_lite_social_items ;


	$arq_lite_social_items = array( 'rss', 'facebook', 'twitter', 'youtube', 'vimeo', 'dribbble', 'soundcloud', 'behance', 'instagram', 'github' );

	$new_window = ' target="_blank" ';

	?>
	<div class="arqam-lite-widget-counter <?php echo $style ?>">
		<ul>
	<?php
foreach ( $arq_lite_social_items as $arq_lite_item ){

	switch ( $arq_lite_item ) {
		case 'facebook':
		if( !empty($arq_lite_options['social']['facebook']['id']) ){
			$text = __( 'Fans' , 'tie' );
			if( !empty($arq_lite_options['social']['facebook']['text']) ) $text = $arq_lite_options['social']['facebook']['text'];
		?>
			<li class="arq-lite-facebook">
				<a href="https://www.facebook.com/<?php echo $arq_lite_options['social']['facebook']['id']; ?>"<?php echo $new_window ?>>
					<i class="fa fa-facebook"></i>
					<span><?php echo arq_lite_format_num( arq_lite_facebook_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'twitter':
		if( !empty($arq_lite_options['social']['twitter']['id']) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['twitter']['text']) ) $text = $arq_lite_options['social']['twitter']['text'];
		?>
			<li class="arq-lite-twitter">
				<a href="https://twitter.com/<?php echo $arq_lite_options['social']['twitter']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-twitter"></i>
					<span><?php echo arq_lite_format_num( arq_lite_twitter_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'youtube':
		if( !empty($arq_lite_options['social']['youtube']['id']) ){
			$text = __( 'Subscribers' , 'tie' );
			if( !empty($arq_lite_options['social']['youtube']['text']) ) $text = $arq_lite_options['social']['youtube']['text'];

			$type = 'user';
			if( !empty($arq_lite_options['social']['youtube']['type']) && $arq_lite_options['social']['youtube']['type'] == 'Channel' ) $type = 'channel';
		?>
			<li class="arq-lite-youtube">
				<a href="https://youtube.com/<?php echo $type ?>/<?php echo $arq_lite_options['social']['youtube']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-youtube"></i>
					<span><?php echo arq_lite_format_num(  arq_lite_youtube_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'vimeo':
 		if( !empty($arq_lite_options['social']['vimeo']['id']) ){
			$text = __( 'Subscribers' , 'tie' );
			if( !empty($arq_lite_options['social']['vimeo']['text']) ) $text = $arq_lite_options['social']['vimeo']['text'];
		?>
			<li class="arq-lite-vimeo">
				<a href="https://vimeo.com/channels/<?php echo $arq_lite_options['social']['vimeo']['id'] ?>"<?php echo $new_window ?>>
					<i class="tieicon-vimeo"></i>
					<span><?php echo arq_lite_format_num( arq_lite_vimeo_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'dribbble':
 		if( !empty($arq_lite_options['social']['dribbble']['id']) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['dribbble']['text']) ) $text = $arq_lite_options['social']['dribbble']['text'];
		?>
			<li class="arq-lite-dribbble">
				<a href="https://dribbble.com/<?php echo $arq_lite_options['social']['dribbble']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-dribbble"></i>
					<span><?php echo arq_lite_format_num( arq_lite_dribbble_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'github':
		if( !empty($arq_lite_options['social']['github']['id']) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['github']['text']) ) $text = $arq_lite_options['social']['github']['text'];
		?>
			<li class="arq-lite-github">
				<a href="https://github.com/<?php echo $arq_lite_options['social']['github']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-github"></i>
					<span><?php echo arq_lite_format_num( arq_lite_github_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'soundcloud':
		if( !empty($arq_lite_options['social']['soundcloud']['id']) && !empty( $arq_lite_options['social']['soundcloud']['api'] ) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['soundcloud']['text']) ) $text = $arq_lite_options['social']['soundcloud']['text'];
		?>
			<li class="arq-lite-soundcloud">
				<a href="https://soundcloud.com/<?php echo $arq_lite_options['social']['soundcloud']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-soundcloud"></i>
					<span><?php echo arq_lite_format_num( arq_lite_soundcloud_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'behance':
		if( !empty($arq_lite_options['social']['behance']['id']) && !empty( $arq_lite_options['social']['behance']['api'] ) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['behance']['text']) ) $text = $arq_lite_options['social']['behance']['text'];
		?>
			<li class="arq-lite-behance">
				<a href="https://www.behance.net/<?php echo $arq_lite_options['social']['behance']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-behance"></i>
					<span><?php echo arq_lite_format_num( arq_lite_behance_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'instagram':
		if( !empty($arq_lite_options['social']['instagram']['id']) ){
			$text = __( 'Followers' , 'tie' );
			if( !empty($arq_lite_options['social']['instagram']['text']) ) $text = $arq_lite_options['social']['instagram']['text'];
		?>
			<li class="arq-lite-instagram">
				<a href="https://instagram.com/<?php echo $arq_lite_options['social']['instagram']['id'] ?>"<?php echo $new_window ?>>
					<i class="fa fa-instagram"></i>
					<span><?php echo arq_lite_format_num( arq_lite_instagram_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
		case 'rss':
		if( !empty($arq_lite_options['social']['rss']['url']) ){
			$text = __( 'Subscribers' , 'tie' );
			if( !empty($arq_lite_options['social']['rss']['text']) ) $text = $arq_lite_options['social']['rss']['text'];
		?>
			<li class="arq-lite-rss">
				<a href="<?php echo esc_url( $arq_lite_options['social']['rss']['url'] ) ?>"<?php echo $new_window ?>>
					<i class="fa fa-rss"></i>
					<span><?php echo arq_lite_format_num( arq_lite_rss_count() ) ?></span>
					<small><?php echo $text; ?></small>
				</a>
			</li>
		<?php
		}
		break;
	}

} //End Foreach ?>

			</ul>
		</div>
		<!-- arqam_lite Social Counter Plugin : https://tielabs.com/buy/portfolio-envato-1 -->
<?php
	if( !empty ($arq_lite_data) ){
		arq_lite_update_count( $arq_lite_data );
	}
}


/*-----------------------------------------------------------------------------------*/
# Functions to Get Counters
/*-----------------------------------------------------------------------------------*/
/* Twitter Followers */
function arq_lite_twitter_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['twitter']) ){
		$result = $arq_lite_transient['twitter'];
	}
	elseif( empty($arq_lite_transient['twitter']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['twitter'] )  ){
		$result = $arq_lite_options['data']['twitter'];
	}
	else{
		$id = $arq_lite_options['social']['twitter']['id'];

		$consumerKey 	= $arq_lite_options['social']['twitter']['key'];
		$consumerSecret = $arq_lite_options['social']['twitter']['secret'];
		$token 			= get_option('arqam_lite_TwitterToken');

		// getting new auth bearer only if we don't have one
		if(!$token) {
			// preparing credentials
			$credentials 	= $consumerKey . ':' . $consumerSecret;
			$toSend 		= base64_encode($credentials);

			// http post arguments
			$args = array(
				'method' 		=> 'POST',
				'httpversion' 	=> '1.1',
				'blocking' 		=> true,
				'headers'		=> array(
					'Authorization'	=> 'Basic ' . $toSend,
					'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
				),
				'body' 			=> array( 'grant_type' => 'client_credentials' )
			);

			add_filter('https_ssl_verify', '__return_false');
			$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

			$keys = json_decode(wp_remote_retrieve_body($response));

			if($keys) {
				// saving token to wp_options table
				update_option('arqam_lite_TwitterToken', $keys->access_token);
				$token = $keys->access_token;
			}
		}

		// we have bearer token wether we obtained it from API or from options
		$args = array(
			'httpversion' 	=> '1.1',
			'blocking' 		=> true,
			'headers' 		=> array(
				'Authorization' => "Bearer $token"
			)
		);

		add_filter('https_ssl_verify', '__return_false');
		$api_url 	= "https://api.twitter.com/1.1/users/show.json?screen_name=$id";
		$response 	= wp_remote_get($api_url, $args);

		if (!is_wp_error($response)) {
			$followers 	= json_decode(wp_remote_retrieve_body($response));
			$result 	= $followers->followers_count;
		} else {
			$result = $arq_lite_options['data']['twitter'];
			// uncomment below to debug
			//die($response->get_error_message());
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['twitter'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['twitter'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['twitter'];
	}
	return $result;
}

/* Facebook Fans */
function arq_lite_facebook_count(){
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	$result = 0;

	if( !empty($arq_lite_transient['facebook']) ){
		$result = $arq_lite_transient['facebook'];
	}
	elseif( empty($arq_lite_transient['facebook']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['facebook'] )  ){
		$result = $arq_lite_options['data']['facebook'];
	}
	else{

		$social_id = $arq_lite_options['social']['facebook']['id'];

		if( ! empty( $social_id ) ){

			// The request URL
			$url = "https://www.facebook.com/plugins/likebox.php?href=https://facebook.com/$social_id&show_faces=true&header=false&stream=false&show_border=false&locale=en_US";

			// Due a change on Facebook this request takes 11 second to complete
			/*
			$get_request = wp_remote_get( $url,
				array(
					'timeout'    => 20,
					'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0'
				));
			*/

			// Check if CURL is enabled
			if( ! function_exists( 'curl_version' ) ){
				return;
			}

			//---
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
			curl_setopt( $ch, CURLOPT_ENCODING, '' );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_AUTOREFERER,true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Accept-Language: en'] );
			curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0' );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			$the_request = curl_exec($ch);

			//error checking
			if ($the_request === false) {
				curl_close($ch);
			}
			else{

				$pattern = '/_1drq[^>]+>(.*?)<\/a/s';
				preg_match( $pattern, $the_request, $matches );

				if ( ! empty( $matches[1] ) ) {
					$number  = strip_tags( $matches[1] );

					foreach ( str_split( $number ) as $char ) {
						if ( is_numeric( $char ) ){
							$result .= $char;
						}
					}
				}
			}
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['facebook'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['facebook'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['facebook'];
	}

	return $result;
}

/* Youtube Subscribers */
function arq_lite_youtube_count(){
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['youtube']) ){
		$result = $arq_lite_transient['youtube'];
	}
	elseif( empty($arq_lite_transient['youtube']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['youtube'] )  ){
		$result = $arq_lite_options['data']['youtube'];
	}
	else{
		$id  = $arq_lite_options['social']['youtube']['id'];
		$api = $arq_lite_options['social']['youtube']['key'];
		try {
			if( !empty($arq_lite_options['social']['youtube']['type']) && $arq_lite_options['social']['youtube']['type'] == 'Channel' ){
				$data = @arq_lite_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$id&key=$api");
			}else{
				$data = @arq_lite_remote_get("https://www.googleapis.com/youtube/v3/channels?part=statistics&forUsername=$id&key=$api");
			}

			if( ! empty( $data['items'][0]['statistics']['subscriberCount'] ) ){
				$result = (int) $data['items'][0]['statistics']['subscriberCount'];
			}
			else{
				$result = 0;
			}

		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['youtube'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['youtube'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['youtube'];
	}
	return $result;
}

/* Vimeo Subscribers */
function arq_lite_vimeo_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['vimeo']) ){
		$result = $arq_lite_transient['vimeo'];
	}
	elseif( empty($arq_lite_transient['vimeo']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['vimeo'] )  ){
		$result = $arq_lite_options['data']['vimeo'];
	}
	else{
		$id = $arq_lite_options['social']['vimeo']['id'];
		try {
			@$data 	= arq_lite_remote_get( "http://vimeo.com/api/v2/channel/$id/info.json" );
			$result = (int) $data['total_subscribers'];
		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['vimeo'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['vimeo'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['vimeo'];
	}
	return $result;
}

/* Dribbble Followers */
function arq_lite_dribbble_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['dribbble']) ){
		$result = $arq_lite_transient['dribbble'];
	}
	elseif( empty($arq_lite_transient['dribbble']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['dribbble'] )  ){
		$result = $arq_lite_options['data']['dribbble'];
	}else{
		$id 	= $arq_lite_options['social']['dribbble']['id'];
		$api 	= get_option( 'dribbble_access_token' );
		try {
			$data 	= @arq_lite_remote_get("https://api.dribbble.com/v2/user/?access_token=$api");
			$result = (int) $data['followers_count'];
		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['dribbble'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['dribbble'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['dribbble'];
	}
	return $result;
}

/* Github Followers */
function arq_lite_github_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['github']) ){
		$result = $arq_lite_transient['github'];
	}
	elseif( empty($arq_lite_transient['github']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['github'] )  ){
		$result = $arq_lite_options['data']['github'];
	}
	else{
		$id = $arq_lite_options['social']['github']['id'];
		try {
			$data 	= @arq_lite_remote_get("https://api.github.com/users/$id");
			$result = (int) $data['followers'];
		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['github'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['github'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['github'];
	}
	return $result;
}


/* SoundCloud Followers */
function arq_lite_soundcloud_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['soundcloud']) ){
		$result = $arq_lite_transient['soundcloud'];
	}
	elseif( empty($arq_lite_transient['soundcloud']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['soundcloud'] )  ){
		$result = $arq_lite_options['data']['soundcloud'];
	}
	else{
		$id 	= $arq_lite_options['social']['soundcloud']['id'];
		$api 	= $arq_lite_options['social']['soundcloud']['api'];
		try {
			$data 	= @arq_lite_remote_get("http://api.soundcloud.com/users/$id.json?consumer_key=$api");
			$result = (int) $data['followers_count'];
		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['soundcloud'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['soundcloud'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['soundcloud'];
	}
	return $result;
}

/* Behance Followers */
function arq_lite_behance_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['behance']) ){
		$result = $arq_lite_transient['behance'];
	}
	elseif( empty($arq_lite_transient['behance']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['behance'] )  ){
		$result = $arq_lite_options['data']['behance'];
	}
	else{
		$id 	= $arq_lite_options['social']['behance']['id'];
		$api 	= $arq_lite_options['social']['behance']['api'];
		try {
			$data = @arq_lite_remote_get("http://www.behance.net/v2/users/$id?api_key=$api");
			$result = (int) $data['user']['stats']['followers'];
		} catch (Exception $e) {
			$result = 0;
		}

		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['behance'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['behance'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['behance'];
	}
	return $result;
}

/* Instagram Followers */
function arq_lite_instagram_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['instagram']) ){
		$result = $arq_lite_transient['instagram'];
	}
	elseif( empty($arq_lite_transient['instagram']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['instagram'] )  ){
		$result = $arq_lite_options['data']['instagram'];
	}
	else{

		// Make a new connection
		$api_key = $arq_lite_options['social']['instagram']['api'];
		$api_url = 'https://api.instagram.com/v1/users/self/?access_token='. $api_key;
		$request = arq_lite_remote_get( $api_url );

		// Error
		if( empty( $request ) || is_wp_error( $request ) ){
			return;
		}

		if( isset( $request['data']['counts']['followed_by'] ) ){
			$result = $request['data']['counts']['followed_by'];
			$arq_lite_data['instagram'] = $result;
		}

		if( empty( $result ) && !empty( $arq_lite_options['data']['instagram'] ) ){ //Get the stored data
			$result = $arq_lite_options['data']['instagram'];
		}
	}

	return $result;
}

/* Rss Subscribers */
function arq_lite_rss_count() {
	global $arq_lite_data, $arq_lite_options, $arq_lite_transient;

	if( !empty($arq_lite_transient['rss']) ){
		$result = $arq_lite_transient['rss'];
	}
	elseif( empty($arq_lite_transient['rss']) && !empty($arq_lite_data) && !empty( $arq_lite_options['data']['rss'] )  ){
		$result = $arq_lite_options['data']['rss'];
	}
	else{
		if( ( $arq_lite_options['social']['rss']['type'] == 'feedpress.it' ) && !empty($arq_lite_options['social']['rss']['feedpress']) ){
			try {
				$feedpress_url 	= esc_url($arq_lite_options['social']['rss']['feedpress']);
				$feedpress_url 	= str_replace( 'feedpress.it', 'feed.press', $feedpress_url);
				$data 			= @arq_lite_remote_get( $feedpress_url );
				$result 		= (int) $data[ 'subscribers' ];
			} catch (Exception $e) {
				$result = 0;
			}
		}
		elseif( ( $arq_lite_options['social']['rss']['type'] == 'Manual' ) && !empty($arq_lite_options['social']['rss']['manual']) ){
			$result = $arq_lite_options['social']['rss']['manual'] ;
		}
		else{
			$result = 0;
		}
		if( !empty( $result ) ) //To update the stored data
			$arq_lite_data['rss'] = $result;

		if( empty( $result ) && !empty( $arq_lite_options['data']['rss'] ) ) //Get the stored data
			$result = $arq_lite_options['data']['rss'];
	}
	return $result;
}


/*-----------------------------------------------------------------------------------*/
# Social Counter Widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'arqam_lite_counter_widget_box' );
function arqam_lite_counter_widget_box() {
	register_widget( 'arqam_lite_counter_widget' );
}

class arqam_lite_counter_widget extends WP_Widget {

	public function __construct(){
		$widget_ops 	= array( 'classname' => 'arqam_lite_counter-widget', 'description' => ''  );
		$control_ops 	= array( 'width' => 250, 'height' => 350, 'id_base' => 'arqam_lite_counter-widget' );
		parent::__construct( 'arqam_lite_counter-widget', ARQAM_LITE_Plugin. ' - Social Counter', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		arq_lite_get_counters( $instance['style'] );
	}

	public function update( $new_instance, $old_instance ) {
		$instance 			= $old_instance;
		$instance['style'] 	= $new_instance['style'] ;

		return $instance;
	}

	public function form( $instance ) {
		$defaults = array(  'style' => 'gray' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style :' , 'tie' ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
				<option value="gray" <?php if( $instance['style'] == 'gray' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Gray Icons' , 'tie' ) ?></option>
				<option value="colored" <?php if( $instance['style'] == 'colored' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Colored Icons' , 'tie' ) ?></option>
				<option value="border" <?php if( $instance['style'] == 'border' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Colored border Icons' , 'tie' ) ?></option>
			</select>
		</p>
	<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function arqam_lite_admin_register() {

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'arqam_lite' ) {
	 ?>
<style type="text/css" media="all">
	.arq-lite-content .links-table{ border-right:1px solid #CCC; padding-right: 15px; margin-right:15px; width: 65%; float:left;}
	.arq-lite-content .links-table th{vertical-align: top; padding-top:7px;}
	.js.toplevel_page_arqam_lite .postbox .hndle{cursor: auto;}
	.links-table td input[type=text], .links-table td input[type=password], .links-table td textarea {width: 100%;}
	.arq-lite-content .links-table th {min-width: 150px;}
	body.rtl .arq-lite-content .links-table{ border-left:1px solid #CCC;  border-right:0 none;  padding-right: 0; padding-left: 15px; margin-left:15px; margin-right:0;  float:right;}
	#tie_rss_feedpress, #tie_rss_manual{display:none;}
	.tie-get-api-key{
		margin: 5px 0 10px !important;
	}
</style>
	<?php
	}
}
add_action( 'admin_enqueue_scripts', 'arqam_lite_admin_register' );


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
add_action('admin_menu', 'arqam_lite_add_admin');
function arqam_lite_add_admin() {
	global $arq_lite_options;

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	add_menu_page(ARQAM_LITE_Plugin.' Settings', ARQAM_LITE_Plugin ,'install_plugins', 'arqam_lite' , 'arqam_lite_options', ''  );
	$theme_page = add_submenu_page('arqam_lite',ARQAM_LITE_Plugin.' Settings', ARQAM_LITE_Plugin.' Settings','install_plugins', 'arqam_lite' , 'arqam_lite_options');

	if( isset( $_REQUEST['action'] ) ){
		if( 'save' == $_REQUEST['action']  && $current_page == 'arqam_lite' ) {
			$arq_lite_options['social'] = $_REQUEST['social'];
			$arq_lite_options['data'] 	= '';

			update_option( 'arq_lite_options' , $arq_lite_options);
			delete_transient('arq_lite_counters');
			delete_option('arqam_lite_TwitterToken');

			header("Location: admin.php?page=arqam_lite&saved=true");
			die;

		}elseif( 'dribbble' == $_REQUEST['action']  && $current_page == 'arqam_lite' ){

			$dribbble_client_id     = $_REQUEST['client_id'];
			$dribbble_client_secret = $_REQUEST['client_secret'];
			$rand = substr(md5(microtime()),rand(0,26),10);

			$cur_page =  urlencode ( admin_url().'admin.php?page=arqam_lite&service=arq-dribbble' );

			set_transient( 'arq_dribbble_client_id',     $dribbble_client_id,     60*60 );
			set_transient( 'arq_dribbble_client_secret', $dribbble_client_secret, 60*60 );

			$url = "https://dribbble.com/oauth/authorize?client_id=$dribbble_client_id&redirect_uri=$cur_page&scope=public&state=$rand";

			header( "Location: $url" );

		}
	}
}

/*-----------------------------------------------------------------------------------*/
# arqam_lite Panel
/*-----------------------------------------------------------------------------------*/
function arqam_lite_options() {
	global $arq_lite_options, $arq_lite_social_items;
	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

if( isset( $_REQUEST['service'] ) && 'arq-dribbble' == $_REQUEST['service'] && $current_page == 'arqam_lite' ){

	if( !empty( $_REQUEST['code'] ) ){
		$code                   = $_REQUEST['code'];
		$cur_page               = admin_url().'admin.php?page=arqam_lite&service=arq-dribbble' ;
		$dribbble_client_id     = get_transient( 'arq_dribbble_client_id' );
		$dribbble_client_secret = get_transient( 'arq_dribbble_client_secret' );

		// http post arguments
		$args = array(
			'body'            => array(
				'client_id'     => $dribbble_client_id,
				'client_secret' => $dribbble_client_secret ,
				'redirect_uri'  => $cur_page,
				'code'          => $code,
			)
		);

		add_filter('https_ssl_verify', '__return_false');
		$response 		= wp_remote_post('https://dribbble.com/oauth/token', $args);
		$response 		= json_decode(wp_remote_retrieve_body($response) );

		if( ! empty( $response ) ){

			$access_token = $response->access_token;

			update_option( 'dribbble_access_token' , $access_token );

			delete_transient( 'arq_dribbble_client_id' );
			delete_transient( 'arq_dribbble_client_secret' );
		}

		echo "<script type='text/javascript'>window.location='".admin_url()."admin.php?page=arqam_lite';</script>";

		exit;
	}

?>

<div class="wrap">
	<h1><?php _e( 'Dribbble App info' , 'arq' ) ?></h1>
	<br />
	<form method="post">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="arq-lite-content">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Dribbble App info' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="client_id"><?php _e( 'Consumer key' , 'tie' ) ?></label></th>
										<td><input type="text" name="client_id" id="client_id" value=""></td>
									</tr>
									<tr>
										<th scope="row"><label for="client_secret"><?php _e( 'Consumer secret' , 'tie' ) ?></label></th>
										<td><input type="text" name="client_secret" id="client_secret" value=""></td>
									</tr>
								</tbody>
							</table>
							<div>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your App Client ID and App Client Secret, <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->
				</div> <!-- Post Body COntent -->

				<div id="publishing-action">
					<input type="hidden" name="action" value="dribbble" />
					<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php _e( 'Submit' , 'tielabs' ) ?>">
				</div>
				<div class="clear"></div>

			</div><!-- post-body /-->
		</div><!-- poststuff /-->
	</form>
</div>
<?php
}

else{

	if ( isset($_REQUEST['saved'])) echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>'. __( 'Settings saved.' , 'tie' ) .'</strong></p></div>'; ?>
<div class="wrap">
	<h1><?php _e( 'Arqam Lite Settings' , 'tie' ) ?> <a href="https://tielabs.com/product/arqam-plugin/" target="_blank" class="page-title-action"><?php _e( 'Need More?' , 'tie' ) ?></a> </h1>
	<br />
	<form method="post">
		<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="post-body-content" class="arq-lite-content">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Facebook' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[facebook][id]"><?php _e( 'Page ID/Name' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[facebook][id]" class="code" id="social[facebook][id]" value="<?php if( !empty($arq_lite_options['social']['facebook']['id']) ) echo $arq_lite_options['social']['facebook']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[facebook][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[facebook][text]" class="code" id="social[facebook][text]" value="<?php if( !empty($arq_lite_options['social']['facebook']['text']) ) echo $arq_lite_options['social']['facebook']['text'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php _e( 'Enter Your Facebook Page Name or ID' , 'tie' );  ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->


					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Twitter' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[twitter][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[twitter][id]" class="code" id="social[twitter][id]" value="<?php if( !empty($arq_lite_options['social']['twitter']['id']) ) echo $arq_lite_options['social']['twitter']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[twitter][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[twitter][text]" class="code" id="social[twitter][text]" value="<?php if( !empty($arq_lite_options['social']['twitter']['text']) ) echo $arq_lite_options['social']['twitter']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[twitter][key]"><?php _e( 'Consumer key' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[twitter][key]" class="code" id="social[twitter][key]" value="<?php if( !empty($arq_lite_options['social']['twitter']['key']) ) echo $arq_lite_options['social']['twitter']['key'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[twitter][secret]"><?php _e( 'Consumer secret' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[twitter][secret]" class="code" id="social[twitter][secret]" value="<?php if( !empty($arq_lite_options['social']['twitter']['secret']) ) echo $arq_lite_options['social']['twitter']['secret'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your Twitter Account Username , your APP Consumer key and Consumer secret, <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'YouTube' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[youtube][id]"><?php _e( 'Username or Channel ID' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[youtube][id]" class="code" id="social[youtube][id]" value="<?php if( !empty($arq_lite_options['social']['youtube']['id']) ) echo $arq_lite_options['social']['youtube']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[youtube][key]"><?php _e( 'Youtube API Key' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[youtube][key]" class="code" id="social[youtube][key]" value="<?php if( !empty($arq_lite_options['social']['youtube']['key']) ) echo $arq_lite_options['social']['youtube']['key'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[youtube][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[youtube][text]" class="code" id="social[youtube][text]" value="<?php if( !empty($arq_lite_options['social']['youtube']['text']) ) echo $arq_lite_options['social']['youtube']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[youtube][type]"><?php _e( 'Type' , 'tie' ) ?></label></th>
										<td>
											<select name="social[youtube][type]" id="social[youtube][type]">
											<?php
											$youtube_type = array('User', 'Channel');
											foreach ( $youtube_type as $type ){ ?>
												<option <?php if( !empty($arq_lite_options['social']['youtube']['type']) && $arq_lite_options['social']['youtube']['type'] == $type ) echo'selected="selected"' ?> value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your Youtube username or Channel ID, API Key and choose User or Channel from Type menu, <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->


					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Vimeo' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[vimeo][id]"><?php _e( 'Channel Name' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[vimeo][id]" class="code" id="social[vimeo][id]" value="<?php if( !empty($arq_lite_options['social']['vimeo']['id']) ) echo $arq_lite_options['social']['vimeo']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[vimeo][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[vimeo][text]" class="code" id="social[vimeo][text]" value="<?php if( !empty($arq_lite_options['social']['vimeo']['text']) ) echo $arq_lite_options['social']['vimeo']['text'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php _e( 'Enter Your Vimeo Channel Name.' , 'tie' ) ?> </em></p></div>

							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Dribbble' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[dribbble][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[dribbble][id]" class="code" id="social[dribbble][id]" value="<?php if( !empty($arq_lite_options['social']['dribbble']['id']) ) echo $arq_lite_options['social']['dribbble']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[dribbble][api]"><?php _e( 'Access Token Key' , 'tie' ) ?></label></th>
										<td>
											<input type="text" disabled="disabled" name="social[dribbble][api]" id="social[dribbble][api]" value="<?php echo get_option( 'dribbble_access_token' ) ?>">
											<a class="button-large button-primary tie-get-api-key" href="<?php echo admin_url().'admin.php?page=arqam_lite&service=arq-dribbble' ?>"><?php _e( 'Get Access Token' , 'tie' ) ?></a>
										</td>
									</tr>

									<tr>
										<th scope="row"><label for="social[dribbble][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[dribbble][text]" class="code" id="social[dribbble][text]" value="<?php if( !empty($arq_lite_options['social']['dribbble']['text']) ) echo $arq_lite_options['social']['dribbble']['text'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Click on Get Access Token to get your App Access Token <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'SoundCloud' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[soundcloud][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[soundcloud][id]" class="code" id="social[soundcloud][id]" value="<?php if( !empty($arq_lite_options['social']['soundcloud']['id']) ) echo $arq_lite_options['social']['soundcloud']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[soundcloud][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[soundcloud][text]" class="code" id="social[soundcloud][text]" value="<?php if( !empty($arq_lite_options['social']['soundcloud']['text']) ) echo $arq_lite_options['social']['soundcloud']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[soundcloud][api]"><?php _e( 'API Key' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[soundcloud][api]" class="code" id="social[soundcloud][api]" value="<?php if( !empty($arq_lite_options['social']['soundcloud']['api']) ) echo $arq_lite_options['social']['soundcloud']['api'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your SoundCloud Account Username and the API Key, <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Behance' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[behance][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[behance][id]" class="code" id="social[behance][id]" value="<?php if( !empty($arq_lite_options['social']['behance']['id']) ) echo $arq_lite_options['social']['behance']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[behance][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[behance][text]" class="code" id="social[behance][text]" value="<?php if( !empty($arq_lite_options['social']['behance']['text']) ) echo $arq_lite_options['social']['behance']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[behance][api]"><?php _e( 'API Key' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[behance][api]" class="code" id="social[behance][api]" value="<?php if( !empty($arq_lite_options['social']['behance']['api']) ) echo $arq_lite_options['social']['behance']['api'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your Behance Account Username and the API Key, <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Github' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[github][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[github][id]" class="code" id="social[github][id]" value="<?php if( !empty($arq_lite_options['social']['github']['id']) ) echo $arq_lite_options['social']['github']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[github][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[github][text]" class="code" id="social[github][text]" value="<?php if( !empty($arq_lite_options['social']['github']['text']) ) echo $arq_lite_options['social']['github']['text'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php _e( 'Enter Your Github Account Username.' , 'tie' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Instagram' , 'tie' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[instagram][id]"><?php _e( 'UserName' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[instagram][id]" class="code" id="social[instagram][id]" value="<?php if( !empty($arq_lite_options['social']['instagram']['id']) ) echo $arq_lite_options['social']['instagram']['id'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[instagram][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[instagram][text]" class="code" id="social[instagram][text]" value="<?php if( !empty($arq_lite_options['social']['instagram']['text']) ) echo $arq_lite_options['social']['instagram']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[instagram][api]"><?php _e( 'Access Token' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[instagram][api]" class="code" id="social[instagram][api]" value="<?php if( !empty($arq_lite_options['social']['instagram']['api']) ) echo $arq_lite_options['social']['instagram']['api'] ?>"></td>
									</tr>
								</tbody>
							</table>

							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your Instagram Username, From <a href="%s" target="_blank">this page</a> You can get your Access Token.' , 'tie' ), 'https://instagram.pixelunion.net/' ) ?></em></p></div>

							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

					<div class="postbox" id="rss">
						<h3 class="hndle"><span><?php _e( 'RSS' , 'tie' ) ?></span></h3>
						<div class="inside">
						<script>
						jQuery(document).ready(function() {
							var selected_item = jQuery("select[name='social[rss][type]'] option:selected").val();

							if (selected_item == 'Manual') {jQuery('#tie_rss_manual').show();}
							if (selected_item == 'feedpress.it') {jQuery('#tie_rss_feedpress').show();}

							jQuery("select[name='social[rss][type]']").change(function(){
								var selected_item = jQuery("select[name='social[rss][type]'] option:selected").val();
								if (selected_item == 'feedpress.it') {
									jQuery( '#tie_rss_manual' ).hide();
									jQuery( '#tie_rss_feedpress' ).fadeIn();
								}
								if (selected_item == 'Manual') {
									jQuery( '#tie_rss_feedpress' ).hide();
									jQuery( '#tie_rss_manual' ).fadeIn();
								}
							 });
						});</script>
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="social[rss][url]"><?php _e( 'Feed URL' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[rss][url]" class="code" id="social[rss][url]" value="<?php if( !empty($arq_lite_options['social']['rss']['url']) ) echo $arq_lite_options['social']['rss']['url'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[rss][text]"><?php _e( 'Text Below The Number' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[rss][text]" class="code" id="social[rss][text]" value="<?php if( !empty($arq_lite_options['social']['rss']['text']) ) echo $arq_lite_options['social']['rss']['text'] ?>"></td>
									</tr>
									<tr>
										<th scope="row"><label for="social[rss][type]"><?php _e( 'Type' , 'tie' ) ?></label></th>
										<td>
											<select name="social[rss][type]" id="social[rss][type]">
											<?php
											$rss_type = array('feedpress.it', 'Manual');
											foreach ( $rss_type as $type ){ ?>
												<option <?php if( !empty($arq_lite_options['social']['rss']['type']) && $arq_lite_options['social']['rss']['type'] == $type ) echo'selected="selected"' ?> value="<?php echo $type ?>"><?php echo $type ?></option>
											<?php } ?>
											</select>
										</td>
									</tr>
									<tr id="tie_rss_feedpress">
										<th scope="row"><label for="social[rss][feedpress]"><?php _e( 'Feedpress Json file URL' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[rss][feedpress]" class="code" id="social[rss][feedpress]" value="<?php if( !empty($arq_lite_options['social']['rss']['feedpress']) ) echo $arq_lite_options['social']['rss']['feedpress'] ?>"></td>
									</tr>
									<tr id="tie_rss_manual">
										<th scope="row"><label for="social[rss][manual]"><?php _e( 'Number of Subscribers' , 'tie' ) ?></label></th>
										<td><input type="text" name="social[rss][manual]" class="code" id="social[rss][manual]" value="<?php if( !empty($arq_lite_options['social']['rss']['manual']) ) echo $arq_lite_options['social']['rss']['manual'] ?>"></td>
									</tr>
								</tbody>
							</table>
							<div><strong><?php _e( 'Need Help?' , 'tie' ) ?></strong><p><em><?php printf( __( 'Enter Your Feed URl and the Feedpress Json file URL or Number of Subscribers manually  <a href="%s" target="_blank">Click Here</a> For More Details.' , 'tie' ), 'https://themes.tielabs.com/docs/sahifa/#counter' ) ?></em></p></div>
							<div class="clear"></div>
						</div>
					</div> <!-- Box end /-->

				</div> <!-- Post Body COntent -->


				<div id="postbox-container-1" class="postbox-container">
					<a href="https://tielabs.com/product/arqam-plugin/" target="_blank">
						<img style="max-width:100%;" src="https://themes.tielabs.com/images/get-arqam.png" alt="" />
					</a>
						<div class="inside" style="background-color: #E8FBFF; border:1px solid #43D1EC; padding:10px; margin-bottom:15px;">
							<strong><?php _e( 'Need More?' , 'tie' ) ?></strong>
							<p>
								<?php _e( 'Purchase the full version of Arqam plugin to get all following features :' , 'tie' ) ?>
								<ul style="list-style-type: disc;list-style-position: inside;">
									<li><?php _e( 'Drag an Drop feature to sort icons as you wish !' , 'tie' ) ?></li>
									<li><?php _e( 'Option To set the Cache time to reduce load time and API calls.' , 'tie' ) ?></li>
									<li><?php _e( 'More Layout options.' , 'tie' ) ?></li>
									<li><strong><?php _e( 'More Social Networks:' , 'tie' ) ?></strong>


										<ol>
											<li>Spotify</li>
											<li>Goodreads</li>
											<li>Mixcloud</li>
											<li>Twitch</li>
											<li>Pinterest</li>
											<li>LinkedIn</li>
											<li>Tumblr</li>
											<li>Flickr</li>
											<li>Foursquare</li>
											<li>500px</li>
											<li>Vk</li>
											<li>Envato</li>
											<li>MailChimp List</li>
											<li>Vine</li>
											<li>Steam</li>
											<li>myMail plugin list</li>
											<li>Mailpoet plugin List.</li>
											<li><?php _e( 'Members Number' , 'tie' ) ?></li>
											<li><?php _e( 'Posts Number' , 'tie' ) ?></li>
											<li><?php _e( 'Comments Number' , 'tie' ) ?></li>
											<li>bbPress topics, replies and forums counters.</li>
											<li>BuddyPress groups counter.</li>
										</ol>
									</li>

								</ul>
							</p>
							<div class="clear"></div>
						</div>


					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Save Settings' , 'tie' ) ?></span></h3>
						<div class="inside">

							<div id="publishing-action">
								<input type="hidden" name="action" value="save" />
								<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php _e( 'Save' , 'tie' ) ?>">
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div><!-- postbox-container /-->
			</div><!-- post-body /-->

		</div><!-- poststuff /-->
	</form>
</div>

<?php
	}
}
?>
