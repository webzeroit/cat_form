<?
	session_start();
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="en"><!--<![endif]-->
<head>
<meta charset="utf-8">

<!-- Viewport Metatag -->
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<!-- Required Stylesheets -->
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/fonts/ptsans/stylesheet.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/fonts/icomoon/style.css" media="screen">

<link rel="stylesheet" type="text/css" href="css/login.css" media="screen">

<link rel="stylesheet" type="text/css" href="css/mws-theme.css" media="screen">

<title>Gestione Checklist</title>

</head>

<body>

    <div id="mws-login-wrapper">
        <div id="mws-head">
             <p><img src="images/mws-logo_start.png" alt="FitosanBas"/></p>
        </div>     
        <div id="mws-login">
            <h1>Login</h1>
            <div class="mws-login-lock"><i class="icon-lock"></i></div>
            <div id="mws-login-form">
                <form class="mws-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="mws-form-row">
                        <div class="mws-form-item">
                            <input type="text" name="username" class="mws-login-username required" placeholder="username">
                        </div>
                    </div>
                    <div class="mws-form-row">
                        <div class="mws-form-item">
                            <input type="password" name="password" class="mws-login-password required" placeholder="password">
                        </div>
                    </div>                   
                    <div class="mws-form-row">
                        <input type="submit" value="Login" class="btn btn-success mws-login-button">
                    </div>
                </form>
            </div>
        </div>
        <div id="mws-head">
        	<br/>
            <p><img src="images/mws-logo_end.png" alt="Regione Basilicata"/></p>
        </div>  
         
    </div>

    <!-- JavaScript Plugins -->
    <script src="js/libs/jquery-1.8.3.min.js"></script>
    <script src="js/libs/jquery.placeholder.min.js"></script>
    <script src="custom-plugins/fileinput.js"></script>
    
    <!-- jQuery-UI Dependent Scripts -->
    <script src="jui/js/jquery-ui-effects.min.js"></script>

    <!-- Plugin Scripts -->
    <script src="plugins/validate/jquery.validate-min.js"></script>

    <!-- Login Script -->
    <script src="js/core/login.js"></script>
	<?

		require_once("model/class.database.php" );
		require_once("model/class.utente.php" );
		
		$user = new Utente();
		
		if ($user->isLogged())
		{
		  # redirect in caso di esito positivo
		  @header("location:index.php");
		}
		
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") { 
		
			$login = $user->setUserLogin(htmlentities($_POST['username'], ENT_QUOTES), htmlentities($_POST['password'], ENT_QUOTES));
			# controllo sull'esito del metodo
			if ($login) {
				# redirect in caso di esito positivo
				@header("location:index.php");
			}else{
				# notifica in caso di esito negativo
				echo '<script>$(document).ready(function() {$("#mws-login").effect("shake", {distance: 6, times: 2}, 35);});</script>';
			}
		}
		# form per l'autenticazione

	?>
  
</body>
</html>
