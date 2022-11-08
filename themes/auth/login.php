<?php
/*
Template Name: signin

*/
if (is_user_logged_in())
{
    header( 'Location:' . home_url('wp-admin') );
    exit;
}
$errors = array();
if($_POST)
{

    global $wpdb;

    //We shall SQL escape all inputs
    $username = $wpdb->escape($_REQUEST['username']);
    $password = $wpdb->escape($_REQUEST['password']);
    // $remember = $wpdb->escape($_REQUEST['rememberme']);

    // if($remember) $remember = "true";
    // else $remember = "false";

    $login_data = array();
    $login_data['user_login'] = $username;
    $login_data['user_password'] = $password;
    $login_data['remember'] = true;
    // $login_data['remember'] = $remember;

    $user_verify = wp_signon( $login_data, true );

//     $userID = $user_verify->ID;
//
// wp_set_current_user( $userID, $username );
// wp_set_auth_cookie( $userID, true, false );
// do_action( 'wp_login', $username );

    if(empty($username) || empty($username)) {
      $errors['username'] = "فیلدها نمیتوانند خالی باشند";
    }

    elseif ( is_wp_error($user_verify) )
    {
        // echo "Invalid login details";
        $errors['username'] = "اطلاعات ورود اشتباه است";
       // Note, I have created a page called "Error" that is a child of the login page to handle errors. This can be anything, but it seemed a good way to me to handle errors.
     } else
    {
       echo "<script type='text/javascript'>window.location.href='". home_url('/wp-admin') ."'</script>";
       exit();
     }

} else
{

    // No login details entered - you should probably add some more user feedback here, but this does the bare minimum

    //echo "Invalid login details";

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

    <title>ورود</title>



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
	<form id="wp_signup_form" action="<?php the_permalink(); ?>" method="post">
		<img style="margin-bottom: 30px;" src="https://hacoupian.net/wp-content/uploads/2021/08/logo2.png" alt="">
		<h1 class="h3 mb-3 font-weight-normal">ورود</h1>
	<?php if ($errors) { ?>
		<div class="error">
		<?php foreach ($errors as $error) {
			echo '<p>' . $error . '</p>';
		} ?>
		</div>
	<?php } ?>

	        <label class="sr-only" for="username">تلفن همراه</label>
        	<input placeholder="نام کاربری یا تلفن همراه" type="text" name="username" id="username">

	        <label class="sr-only" for="password">رمز عبور</label>
        	<input placeholder="رمز عبور" type="password" name="password" id="password">

<!--         	<input name="rememberme" id="rememberme" type="checkbox" value="Yes">
        	<label for="rememberme">به یاد داشتن</label>   -->


	        <input type="submit" id="submitbtn" name="submit" value="ورود" />

	        <div style="text-align: right;padding: 11px 0;">
	        	<p>هنوز ثبت نام نکرده اید؟  <a href="<?php echo home_url('/register') ?>">  ثبت نام </a></p>
	        </div>
	</form>
</div>



 <?php wp_footer(); ?>

	</body>
</html>
