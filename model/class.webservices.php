<?

class AuthHeader
{
	var $username;//string
	var $password;//string
	var $nomeServizio;//string
  
	const SERVIZIO_SCARICA_LISTA_DOMANDE     = 1;	  
	const SERVIZIO_SCARICA_FORNITURA      = 2;
	const SERVIZIO_TROVA_FASCICOLO      = 3;
	const SERVIZIO_DETTAGLIO_SOGGETTO      = 4;
	const SERVIZIO_SCARICA_LISTA_DOMANDE_DATA     = 5;	

  
	function __construct()
	{
		$this->username  = "rgn001w"; // Produzione
		$this->password = "1villedeparis18";
	}
	
	public function setNomeServizio( $id_servizio ){

		switch ($id_servizio ){
		case 1:
			$this->nomeServizio ="DettaglioSoggettoFS1.0";
			break;			
		case 2:
			$this->nomeServizio ="TrovaFascicoloFS2.0";
			break;					
		}				
	}
	public function getNomeServizio(){
		return $this->nomeServizio;
	}
}


class WebServices
{
	private $args = array(
		/* non-WSDL mode è necessario specificare location e uri */
		"location" => "http://cooperazione.sian.it/wspdd/services/WSScaricaDomandaASR", //, // URL to request
		//"location" => "",
		"uri" => "http://testuri.org", // target namespace of the SOAP service
		"style" => NULL, // Specificato nel WSDL file. Ad esempio "style" => SOAP_DOCUMENT
		"use" => NULL, // Specificato nel WSDL file. Ad esempio "user" => SOAP_LITERAL
		"soap_version" => NULL, // Se usare SOAP 1.1 o SOAP 1.2. Ad esempio "soap_version" => SOAP_1_1
		
		/* HTTP authentication*/
		"login" => NULL,
		"password" => NULL,
		
		/* HTTP connection attraverso un proxy server */
		"proxy_host" => NULL, 
		"proxy_port" => NULL, 
		"proxy_login" => NULL,
		"proxy_password" => NULL,
		
		/* HTTPS client certificate authentication */
		"local_cert" => NULL,
		"passphrase" => NULL,
		"authentication" => NULL, // SOAP_AUTHENTICATION_BASIC (Default) oppure SOAP_AUTHENTICATION_DIGEST
		
		"compression" => NULL, // Ad esempio "compression" => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | 5
		"encoding" => NULL, // Ad esempio "encoding" => "ISO-8859-1"
		"trace" => 1, // Se impostato a 1 permetti di tracciare le richieste per captare eventuali Fault. Settato a uno permette di chiamare i metodi SoapClient->__getLastRequest, SoapClient->__getLastRequestHeaders, SoapClient->__getLastResponse e SoapClient->__getLastResponseHeaders
		"classmap" => NULL, // can be used to map some WSDL types to PHP classes. Ad esempio "classmap" => array('result' => 'MyNamespace\\Result') 
		"exceptions" => true, // Per gestire eccessioni di tipo SoapFault. Valori true o false
		"connection_timeout" => 900, // timeout in seconds for the connection to the SOAP service
		"typemap" => NULL, // The typemap option is an array of type mappings. Type mapping is an array with keys type_name, type_ns (namespace URI), from_xml (callback accepting one string parameter) and to_xml (callback accepting one object parameter)
		"cache_wsdl" => NULL, // Valori possibili WSDL_CACHE_NONE, WSDL_CACHE_DISK, WSDL_CACHE_MEMORY o WSDL_CACHE_BOTH
		"user_agent" => NULL, // The user_agent option specifies string to use in User-Agent header
		"stream_context" => NULL,
		"features" => NULL // SOAP_SINGLE_ELEMENT_ARRAYS, SOAP_USE_XSI_ARRAY_TYPE, SOAP_WAIT_ONE_WAY_CALLS
	);
	
	public $risposta_servizio;
	public $codRet;
	public $segnalazione;
	public $fornitura;
	
	public $pagamenti;
	public $anomalie;
	
	public $wsdl;
	
	
	 
	/***************************************************
		METODI DEL FASCICOLO AZIENDALE
	***************************************************/	
	public function setDettaglioSoggetto( $cuaa ){
		set_time_limit(0); 
		if ($this->wsdl == "") $this->wsdl = "http://cooperazione.sian.it/wspdd/services/OprFascicolo?WSDL";
		try{
			ini_set('default_socket_timeout', 900);
						
			//INIZIALIZZO IL CLIENT
			$client = new SoapClient( $this->wsdl, $this->args );
			//SETTO L'HEADER
			$AuthHeader = new AuthHeader();
			$AuthHeader->setNomeServizio(1);
			$headers = new SoapHeader( "http://cooperazione.sian.it/schema/SoapAutenticazione","SOAPAutenticazione",  $AuthHeader, false );
			$client->__setSoapHeaders( $headers );		
				
			//CHIAMO IL METODO
			$funcname = "DettaglioSoggettoFS1.0";
			$result = $client->$funcname( array("Cuaa"=>$cuaa, "Data"=>"99990131" ) );
			
			if ( $result->ISWSResponse->codRet == "012" ){
				$this->codRet = $result->ISWSResponse->codRet;
				$this->segnalazione = $result->ISWSResponse->Segnalazione;
								
				$result = $result->risposta12;
				$this->risposta_servizio = $result;						

			} else {
				$this->codRet = $result->ISWSResponse->codRet;
				$this->segnalazione = $result->ISWSResponse->Segnalazione;	
				$this->risposta_servizio = array();
			}
				
			
		} catch ( SoapFault $E ){
			$this->codRet = "000"; 
			$this->segnalazione = $E->faultstring;	
			$this->risposta_servizio = array();
		}
	}	
	
	public function setDatiFascicolo( $cuaa ){
		set_time_limit(0); 
		
		if ($this->wsdl == "")
			$this->wsdl = "http://cooperazione.sian.it/wspdd/services/OprFascicolo?WSDL";
		try{
			ini_set('default_socket_timeout', 900);
			//INIZIALIZZO IL CLIENT
			 
			$this->args["location"] = "http://cooperazione.sian.it/wspdd/services/OprFascicolo";
			$this->args["encoding"] = "ISO-8859-1";

			$client = new SoapClient( $this->wsdl, $this->args );
			//SETTO L'HEADER
			$AuthHeader = new AuthHeader();
			$AuthHeader->setNomeServizio(2);
			$headers =  new SoapHeader("http://cooperazione.sian.it/schema/SoapAutenticazione","SOAPAutenticazione",  $AuthHeader, false);		
			$client->__setSoapHeaders( $headers );		
			
			
			//CHIAMO IL METODO
			$funcname = "TrovaFascicoloFS2.0";
			$result = $client->$funcname( $cuaa );				

			if ( $result->ISWSResponse->codRet == "012" ){
				$this->codRet = $result->ISWSResponse->codRet;
				$this->segnalazione = $result->ISWSResponse->Segnalazione;
				
				
				$result = $result->risposta10;
				$this->risposta_servizio = $result;	
								

			} else {
				$this->codRet = $result->ISWSResponse->codRet;
				$this->segnalazione = $result->ISWSResponse->Segnalazione;	
				$this->risposta_servizio = array();
			}
				
			
		} catch ( SoapFault $E ){
			$this->codRet = "000"; 
			$this->segnalazione = $E->faultstring;	
			$this->risposta_servizio = array();
		}
	}


	/***************************************************
		METODI UTILITY
	***************************************************/	
	public function getFunctions( $wsdl ){
		//INIZIALIZZO IL CLIENT
		$client = new SoapClient( $wsdl, $this->args );		
		echo "<br><b>Funzioni disponibili:</b><br>";
		foreach ( $client->__getFunctions() as $function ){
			echo $function . "<br>";
		}
	}
	
}


?>