<?
class Utility {
	public static function str2num($str) {
		/*
		if (strpos ( $str, '.' ) < strpos ( $str, ',' )) {
			$str = str_replace ( '.', '', $str );
			$str = strtr ( $str, ',', '.' );
		} else {
			$str = str_replace ( ',', '', $str );
		}
		return ( float ) $str;
		*/
		if (strpos ( $str, '.' ) > 0){
			$str = str_replace ( '.', '', $str );
		} 
		if (strpos ( $str, ',' ) > 0){
			$str = strtr ( $str, ',', '.' );
		}
		return ( float ) $str;		
	}
	
	private static function verifica_regexp($reg_exp, $testo) {
		if (preg_match ( $reg_exp, $testo ))
			return true;
		else
			return false;
	}
	public static function rexp_barcode($barcode) {
		return Utility::verifica_regexp ( "(^\d{11}$)", $barcode );
	}
	public static function rexp_numerico($numero, $caratteri) {
		return Utility::verifica_regexp ( "(^\d{" . $caratteri . "}$)", $numero );
	}
	public static function luhn_barcode($number) {
		return $number . Utility::luhn_digit ( $number );
	}
	public static function luhn_digit($number) {
		// Strip any non-digits (useful for credit card numbers with spacesand hyphens)
		$number = preg_replace ( '/\D/', '', $number );
		
		// Set the string length and parity
		$number_length = strlen ( $number );
		$parity = $number_length % 2;
		
		// Loop through each digit and do the maths
		$total = 0;
		for($i = 0; $i < $number_length; $i ++) {
			$digit = $number [$i];
			if ($i % 2 != $parity) {
				
				$digit *= 2;
				// If the sum is two digits, add them together (in effect)
				if ($digit > 9) {
					$digit -= 9;
				}
			}
			// Total up the digits
			$total += $digit;
		}
		if (($total % 10) == 0)
			return 0;
		else
			return 10 - ($total % 10);
	}
	public static function YYYYMMDD_to_date($numberDate) {
		$retDate = "";
		if (! empty ( $numberDate )) {
			$retDate = date ( "d/m/Y", strtotime ( $numberDate ) );
		}
		return $retDate;
	}
	
	/**
	 * Write to log file
	 * $livello : INFO - WARN - ERROR - DEBUG
	 * $tipo: FILE - DB - BOTH
	 * 
	 * @return true on success
	 */
	public static function Logga($messaggio, $livello = "DEBUG", $contesto = "RUFA", $file) {
		$tipo_log = get_params_ini ( "Log", "tipo_log", "config.ini" );
		;
		
		$today = date ( "Ymd" );
		
		if ($fh = @fopen ( $_SERVER ['APPL_PHYSICAL_PATH'] . "log/" . $file . "_" . $today . ".txt", 'a+' )) {
			$msg = "[" . date ( 'd-m-Y H:i:s' ) . "] [" . $livello . "] [" . $contesto . "] - " . $messaggio . "\r\n";
			fputs ( $fh, $msg, strlen ( $msg ) );
			fclose ( $fh );
			return true;
		} else {
			return false;
		}
	}
	public static function toItalianDate($american_date, $separator = "/", $show_time = false) {
		if ($american_date != "") {
			$day = substr ( $american_date, 8, 2 );
			$mounth = substr ( $american_date, 5, 2 );
			$year = substr ( $american_date, 0, 4 );
			
			$hours = substr ( $american_date, 11, 2 );
			$minutes = substr ( $american_date, 14, 2 );
			$seconds = substr ( $american_date, 17, 2 );
			
			$time = "";
			if ($show_time) {
				if ($hours == "")
					$hours = "00";
				if ($minutes == "")
					$minutes = "00";
				if ($seconds == "")
					$seconds = "00";
				$time .= " " . $hours . ":" . $minutes . ":" . $seconds;
			}
			
			return ($day . $separator . $mounth . $separator . $year . $time);
		} else {
			return "";
		}
	}
	public static function EuroToDb($valuta) {
		$valuta = str_replace ( "€", "", $valuta );
		$valuta = str_replace ( ".", "", $valuta );
		$valuta = str_replace ( ",", ".", $valuta );
		$valuta = trim ( $valuta );
		return $valuta;
	}
	public static function formattaCifre($valuta) {
		return number_format ( $valuta, 2, ",", "." );
	}
	public static function encodeToIso($string) {
		return mb_convert_encoding ( $string, "ISO-8859-1", mb_detect_encoding ( $string, "UTF-8, ISO-8859-1, ISO-8859-15", true ) );
	}
	
	
	public static function StampaVuoto($string) {
		if (empty($string)) 
			return "-";
		else 
			return $string;
	}
	
	
	public static function numero_lettere($numero) 
	{ 
		if (($numero < 0) || ($numero > 999999999)) 
		{ 
			return "$numero"; 
		} 

		$milioni = floor($numero / 1000000);  // Milioni   
		$numero -= $milioni * 1000000; 
		$migliaia = floor($numero / 1000);    // Migliaia  
		$numero -= $migliaia * 1000; 
		$centinaia = floor($numero / 100);     // Centinaia  
		$numero -= $centinaia * 100; 
		$decine = floor($numero / 10);       // Decine  
		$unita = $numero % 10;               // Unità  

		$cifra_lettere = ""; 

		if ($milioni) 
		{ 
			$tmp = Utility::numero_lettere($milioni); 
			$cifra_lettere .= ($tmp=='uno') ? '' : $tmp; 
			$cifra_lettere .= ($milioni == '1') ? "un milione":"milioni"; 
		} 

		if ($migliaia) 
		{ 
			$tmp = Utility::numero_lettere($migliaia); 
			$cifra_lettere .= ($tmp=='uno') ? '' : $tmp; 
			$cifra_lettere .= ($migliaia == '1') ? "mille":"mila"; 
		} 

		if ($centinaia) 
		{ 
			$tmp = Utility::numero_lettere($centinaia); 
			$cifra_lettere .= ($tmp=='uno') ? '' : $tmp; 
			$cifra_lettere .= "cento"; 
		} 

		$array_primi = array("", "uno", "due", "tre", "quattro", "cinque", "sei", 
			"sette", "otto", "nove", "dieci", "undici", "dodici", "tredici", 
			"quattordici", "quindici", "sedici", "diciassette", "diciotto", 
			"diciannove"); 
		$array_decine = array("", "", "venti", "trenta", "quaranta", "cinquanta", "sessanta", 
			"settanta", "ottanta", "novanta"); 
		$array_decine_tronc = array("", "", "vent", "trent", "quarant", "cinquant", "sessant", 
			"settant", "ottant", "novant"); 


		if ($decine || $unita) 
		{ 

			if ($decine < 2) 
			{ 
				$cifra_lettere .= $array_primi[$decine * 10 + $unita]; 
			} 
			else 
			{ 
				if ($unita == 1 || $unita == 8) 
					$cifra_lettere .= $array_decine_tronc[$decine]; 
				else 
					$cifra_lettere .= $array_decine[$decine]; 

				if ($unita) { 
					$cifra_lettere .= $array_primi[$unita]; 
				} 
			} 
		} 


		if (empty($cifra_lettere)) { 
			$cifra_lettere = "zero"; 
		} 

		return $cifra_lettere; 
	} 
}

?>