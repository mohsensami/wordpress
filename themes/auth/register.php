<?php
/*
Template Name: Register

*/ 
if (is_user_logged_in()) 
{  
   header( 'Location:' . home_url('wp-admin') );  
    exit;
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

    <title>ثبت نام</title>



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


<?php
global $wpdb, $user_ID;  
//Check whether the user is already logged in  
 
   
    $errors = array();  
   
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
      {  
   
        // Check username is present and not already in use  
        $username = $wpdb->escape($_REQUEST['username']);  

        if(!check_is_haco_member($username))
         {  
          $errors['username'] = "شما از اعضای مجموعه هاکوپیان نمی باشید";  
        }

        if ( strpos($username, ' ') !== false )
        {   
            $errors['username'] = "فاصله خالی مجاز نمی باشد";  
        }  
        if(empty($username)) 
        {   
            $errors['username'] = "فیلد تلفن همراه نمی تواند خالی باشد";  
        } elseif( username_exists( $username ) ) 
        {  
            $errors['username'] = "تلفن همراه از قبل موجود است";  
        }  
   
        // Check email address is present and valid  
        $email = $wpdb->escape($_REQUEST['email']);  
        if( !is_email( $email ) ) 
        {   
            $errors['email'] = "لطفا یک آدرس ایمیل معتبر وارد کنید";  
        } elseif( email_exists( $email ) ) 
        {  
            $errors['email'] = "این آدرس ایمیل قبلا استفاده شده است";  
        }  
   
        // Check password is valid  
        if(0 === preg_match("/.{6,}/", $_POST['password']))
        {  
          $errors['password'] = "رمز عبود حداقل باید 6 حرف داشته باشد";  
        }  
   
        // Check password confirmation_matches  
        if(0 !== strcmp($_POST['password'], $_POST['password_confirmation']))
         {  
          $errors['password_confirmation'] = "رمز عبود مطابقت ندارد";  
        }  

        
   
        // Check terms of service is agreed to  
        // if($_POST['terms'] != "Yes")
        // {  
        //     $errors['terms'] = "You must agree to Terms of Service";  
        // }  
   
        if(0 === count($errors)) 
         { ?>

			<?php 
			// header("Location: http://localhost/mag/login/");
			// die(); 

			$rand_code = rand(1000,9999);
   
            $password = $_POST['password'];  
   
            $new_user_id = wp_create_user( $username, $password, $email );  

            update_user_meta( $new_user_id, 'activation_code', $rand_code );


$url = 'http://messenger.haco.dc/send-sms';
$args = array(
	'timeout'     => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking'    => true,
	'headers'     => array(
		'auth-secret'  => 'aEvPFiZVr$&QLutJ9!CCnp&#Y(ecy%$V1))&!DhP!sxQ4cE3EWktNDQf0z8Ue8^W', 
		'Content-Type' => 'application/json'
	),
	'body'        => json_encode( array( 
			'phone' => $username, 
			'message' => 'هاکوپیان . کدتایید شما: ' . $rand_code, 
			'driver' => 'Vesal', 
		)
	),
	'cookies'     => array()
);
$response = wp_remote_post( $url, $args );


    // error check
if ( is_wp_error( $response ) ) {
   $error_message = $response->get_error_message();
   echo "Something went wrong: $error_message";
}
else {
   echo 'Response: <pre>';
   print_r( $response );
   echo '</pre>';
}

   
            // You could do all manner of other things here like send an email to the user, etc. I leave that to you.  
   
            $success = 1;  
   
            header( 'Location:' . home_url('verify') . '/?phone=' . $username . '&email=' . $email );  
   
        }  
   
    }  

  
?>  
  <div class="form-signin">
  	
  

  

<form id="wp_signup_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">  
  <img class="mb-4" src="https://hacoupian.net/wp-content/uploads/2021/08/logo2.png" alt="">
    <h1 class="h3 mb-3 font-weight-normal">ثبت نام</h1>
<?php if ($errors) { ?>
	<div class="error">
	<?php foreach ($errors as $error) {
		echo '<p>' . $error . '</p>';
	} ?>
	</div>
<?php } ?>

        <label class="sr-only" for="username">تلفن همراه</label>  
        <input placeholder="تلفن همراه  -------09" type="text" name="username" id="username">  
        <label class="sr-only" for="email">آدرس ایمیل</label>  
        <input placeholder="آدرس ایمیل" type="text" name="email" id="email">  
        <label class="sr-only" for="password">رمز عبور</label>  
        <input placeholder="رمز عبور" type="password" name="password" id="password">  
        <label class="sr-only" for="password_confirmation">Confirm Password</label>  
        <input placeholder="تکرار رمز عبور" type="password" name="password_confirmation" id="password_confirmation">  
  
<!--         <div>
        	<input name="terms" id="terms" type="checkbox" value="Yes">  
        	<label for="terms">I agree to the Terms of Service</label>  
        </div> -->
  
        <input type="submit" id="submitbtn" name="submit" value="ثبت نام" />  


        <div style="text-align: right;padding: 11px 0;">
	        	<p>قبلا ثبت نام کرده اید؟  <a href="<?php echo home_url('/signin') ?>"> ورود </a></p>
	        </div> 
  
</form>  
  </div>


<?php wp_footer(); ?>
	
	</body>
</html>