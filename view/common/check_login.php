<?
	session_start();
	
	# inclusione del file di funzione
	include_once("model/class.database.php" );
	include_once("model/class.utente.php" );
	# istanza della classe
	$user = new Utente();
	# identificativo univoco dell'utente
	if ( isset( $_SESSION["id_utente"] ) ) $id_utente = $_SESSION["id_utente"];
	
	# chiamata al metodo per la verifica della sessione
	if (!$user->isLogged())
	{
	  #redirect in caso di sessione non verificata
	  @header("location:login.php");
	}
	# controllo sul valore di input per il logout
	if (isset($_GET['logout']) && ($_GET['logout'] == 'true')) 
	{
	  # chiamata al metodo per il logout
	  $user->logout();
	  # redirezione alla pagina di login
	  @header("location:login.php");
	}
	# Area riservata
?>