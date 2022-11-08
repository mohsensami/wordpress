<?php
/*
Template Name: verify

*/ 
global $wpdb;
$user_phone = $_GET['phone'];
$user_email = $_GET['email'];
$user = get_user_by('login', $user_phone);
$sms_code = get_user_meta( $user->ID, 'activation_code', $single);
if ( ! $user->ID ) {
	header( 'Location:' . home_url() ); 
	exit; 
} 





$errors = array();  

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) { 
$code = $wpdb->escape($_REQUEST['code']); 
    	
	if(empty($code)) {   
	    $errors['code'] = "فیلد کد نمی تواند خالی باشد";  
	} 
	elseif (strval($code) != strval($sms_code)) {
		$errors['code'] = "کد تایید اشتباه است";  
	}



	if(0 === count($errors)) {
		delete_user_meta( $user->ID, 'activation_code' );
		update_user_meta( $user->ID, 'is_actived', true );
		header( 'Location:' . home_url('/signin') ); 
		exit;
	}

}



?>

<!doctype html>
<html lang="fa">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>تایید کاربر</title>



    <!-- Custom styles for this template -->
    <style type="text/css">
    	html,
		body {
		  height: 100%;
		  text-align: center ;
		}

		body {
		  display: -ms-flexbox;
		  display: -webkit-box;
		  display: flex;
		  -ms-flex-align: center;
		  -ms-flex-pack: center;
		  -webkit-box-align: center;
		  align-items: center;
		  -webkit-box-pack: center;
		  justify-content: center;
		  padding-top: 40px;
		  padding-bottom: 40px;
		  background-color: #f5f5f5;
		}
		h1 {
			margin: 20px 0;
		}
		.form-signin {
		  width: 100%;
		  max-width: 330px;
		  padding: 15px;
		  margin: 0 auto;
		}
		.form-signin .checkbox {
		  font-weight: 400;
		}
		.form-signin input {
		  position: relative;
		  box-sizing: border-box;
		  height: auto;
		  padding: 10px !important;
		  font-size: 16px !important;
		  text-align: center;
		  width: 100%;
		  font-family: inherit !important;
		}
		.form-signin .form-control:focus {
		  z-index: 2;
		}
		.form-signin input[type="email"] {
		  margin-bottom: -1px;
		  border-bottom-right-radius: 0;
		  border-bottom-left-radius: 0;
		}
		.form-signin input[type="password"] {
		  margin-bottom: 10px;
		  border-top-left-radius: 0;
		  border-top-right-radius: 0;
		}
		.form-signin #submitbtn {
			width: 100%;
		}
		.error {
			background: #ffb2b2;
		    padding: 10px;
		    border-radius: 5px;
		    line-height: 30px;
		    margin-bottom: 14px;
		}
    </style>

    <?php wp_head(); ?>
  </head>

  <body class="text-center">



<div class="form-signin">
	<form id="wp_signup_form" action="" method="post">  
		<img style="margin-bottom: 30px;" src="https://hacoupian.net/wp-content/uploads/2021/08/logo2.png" alt="">
	<?php if ($errors) { ?>
		<div class="error">
		<?php foreach ($errors as $error) {
			echo '<p>' . $error . '</p>';
		} ?>
		</div>
	<?php } ?>

	        <label class="sr-only" for="code">کد</label>  
	        <input placeholder="کد ارسال شده را وارد کنید" type="text" name="code" id="code">  
	  
	  
	        <input type="submit" id="submitbtn" name="submit" value="ارسال" />  
	  
	</form>  
</div>



 <?php wp_footer(); ?>
	
	</body>
</html>