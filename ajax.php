<?

include( "model/class.database.php" );
include( "model/class.utente.php" );
include( "model/class.utility.php" );
include( "model/class.datatables.php" );
include( "model/class.parametri.php" );
include( "model/class.log.php" );
include( "model/class.checklist.php" );

$ajaxOP = "0";
if (isset($_REQUEST["ajaxOP"]))
    $ajaxOP = $_REQUEST["ajaxOP"];


switch ($ajaxOP)
{

    /*     * *************************************************** */
    case "get_cform_domanda_list":
        $dt = new DataTables();
        $dt->Table = "v_cform_domanda";
        $dt->IndexColumn = "id_progressivo";
        $dt->Columns = array(
            "id_arrivo",
            "id_istanza",
            "denominazione_ente",
            "identificativo_fiscale_ente",
            "stato_istanza",
            "data_invio",
            "ora_invio",
            "id_utente",
            "data_caricamento",
            "percorso_checklist",
            "id_stato_checklist",
            "id_esito",
            "id_progressivo"
        );

        if (isset($_GET['id_ruolo']) && ($_GET['id_ruolo']) != 'AB_000')
        {
            $dt->initWhere = "id_utente=" . $_GET['id_utente'];
        }
        $output = $dt->getData($_GET);
        echo json_encode($output);
        break;
    case "genera_checklist":
        if (isset($_REQUEST['id']) && ($_REQUEST['id']) != '')
        {
            $CheckList = new CheckList();
            $id_progressivo = $_REQUEST['id'];
            $template_checklist = "template/CheckList_CF_1_0_0.xlsx";

            if (!file_exists($template_checklist))
            {
                echo "Il format della checklist non e' presente sul server.";
            }
            else
            {
                $dati_domanda = $CheckList->getRowCheckList($id_progressivo, false);
                $filename = $dati_domanda["id_progressivo"] . "_" . $dati_domanda["id_istanza"] . "_" . preg_replace('/[^a-z0-9\.]/', '_', strtolower($dati_domanda["denominazione_ente"]));
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
                header('Cache-Control: max-age=0');
                readfile($template_checklist);
                exit;
            }
        }
        break;
    case "get_cform_domanda_row":
        $CheckList = new CheckList();

        if (isset($_REQUEST['id']) && ($_REQUEST['id']) != '')
            $output = $CheckList->getRowCheckList($_REQUEST['id'], false);
        echo json_encode($output);

        break;
    case "saveFormCheckList":
        $CheckList = new CheckList();

        //BARCODE
        if (isset($_REQUEST['id_progressivo']) && ($_REQUEST['id_progressivo']) != '')
            $id_progressivo = $_REQUEST['id_progressivo'];

        if (isset($_REQUEST['id_esito']) && ($_REQUEST['id_esito']) != '')
            $id_esito = $_REQUEST['id_esito'];
        else
            $id_esito = 0;
        //ANNOTAZIONI
        if (isset($_REQUEST['note']) && ($_REQUEST['note']) != '')
            $note = $_REQUEST['note'];
        else
            $note = "";
        if (isset($_REQUEST['ragione_sociale']) && ($_REQUEST['ragione_sociale']) != '')
            $ragione_sociale = $_REQUEST['ragione_sociale'];

        $esito = "0";

        if (isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"] == UPLOAD_ERR_OK)
        {
            ############ Edit settings ##############
            $UploadDirectory = 'files/'; //specify upload directory ends with / (slash)
            ##########################################
            /*
              Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini".
              Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit
              and set them adequately, also check "post_max_size".
             */

            if (!file_exists($UploadDirectory . $id_progressivo))
            {
                mkdir($UploadDirectory . $id_progressivo, 0777, true);
            }
            $UploadDirectory = $UploadDirectory . $id_progressivo . "/";

            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
            {
                $esito = "-99";
            }
            //Is file size is less than allowed size.
            if ($_FILES["FileInput"]["size"] > 5242880)
            {
                $esito = "-1"; //file size error
            }

            //allowed file type Server side check
            switch (strtolower($_FILES['FileInput']['type']))
            {
                //allowed file types
                case 'application/vnd.ms-excel':
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    break;
                default:
                    $esito = "-2"; //filetype error
                    break;
            }
            $File_Name = strtolower($_FILES['FileInput']['name']);
            $File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            /* VERIFICA NOME DEL FILE E CORRISPONDENZ CON IL BARCODE */

            $Random_Number = rand(0, 9999999999); //Random number to be added to name.    
            $File_with_no_ext = str_replace($File_Ext, '', $File_Name);
            $NewFileName = $File_with_no_ext . "_" . $Random_Number . $File_Ext;
            if (move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory . $NewFileName))
            {
                // do other stuff 				
                $dati_checklist["id_progressivo"] = $id_progressivo;
                $dati_checklist["id_esito"] = $id_esito;
                $dati_checklist["note"] = $note;
                $dati_checklist["id_stato_checklist"] = 1;
                $dati_checklist["percorso_checklist"] = $UploadDirectory . $NewFileName;
                $esito = $CheckList->saveFormCheckList($dati_checklist);
            }
            else
            {
                $esito = "-3"; //"error uploading File!";
            }
        }
        else
        {
            $dati_checklist["id_progressivo"] = $id_progressivo;
            $dati_checklist["id_esito"] = $id_esito;
            $dati_checklist["note"] = $note;
            $dati_checklist["percorso_checklist"] = "ND";
            $esito = $CheckList->saveFormCheckList($dati_checklist);
        }


        echo json_encode($esito);

        break;
        
    case "getRootAllegati":    
        $DownloadDirectory = 'download/'; //specify upload directory ends with / (slash)
        $files = array();
        if (isset($_REQUEST['id_istanza']) && ($_REQUEST['id_istanza']) != '')
        {
            $id_istanza = $_REQUEST['id_istanza'];
             $dirs = glob($DownloadDirectory . $id_istanza . "*", GLOB_ONLYDIR);
             $path_to_file = $dirs[0];
        }
        $ret =  array( "path" => $path_to_file);
        echo json_encode($ret);
        break;
    case "getFileAllegati":
        $DownloadDirectory = 'download/'; //specify upload directory ends with / (slash)
        $files = array();

        if (isset($_REQUEST['id_istanza']) && ($_REQUEST['id_istanza']) != '')
        {
            $id_istanza = $_REQUEST['id_istanza'];
            $dirs = glob($DownloadDirectory . $id_istanza . "*", GLOB_ONLYDIR);
            if (count($dirs) > 0)
            {
                $path_to_file = $dirs[0];
                if (is_dir($path_to_file))
                {
                    //$files = scandir($DownloadDirectory . $id_istanza, 1);
                    //*$files = array_diff($files, array('..', '.'));

                    $files = get_filelist_as_array($path_to_file);
                }
            }
        }
        echo json_encode($files);
        break;
    case "get_cform_domanda_corsi_list":
        if (isset($_GET['id_istanza']) && ($_GET['id_istanza']) != '')
        {
            $dt = new DataTables();
            $dt->Table = "v_cform_domanda_corsi";
            $dt->IndexColumn = "id_istanza";
            $dt->Columns = array(
                "id_istanza",
                "codice_percorso",
                "titolo_corso",
                "aula_svolgimento",
                "sede_svolgimento",
                "data_inizio_prevista",
                "data_fine_prevista"
            );
            $dt->initWhere = "id_istanza=" . $_GET['id_istanza'];
        }
        $output = $dt->getData($_GET);
        echo json_encode($output);
        break;
    /*
      Utente
     */
    case "getUtenteList":
        $utente = new Utente();

        $rResult = $utente->getList(true);

        $output = array(
            "aaData" => $rResult
        );
        echo json_encode($output);
        break;
    case "getListFunzioni":
        $utente = new Utente();

        $rResult = $utente->getListFunzioni(true);
        echo json_encode($rResult);
        break;
    case "getUtenteRow":
        $utente = new Utente();

        if (isset($_REQUEST['id']) && ($_REQUEST['id']) != '')
            echo $utente->getRow($_REQUEST['id'], true);

        break;
    case "getUtenteFunzioni":
        $utente = new Utente();

        if (isset($_REQUEST['id']) && ($_REQUEST['id']) != '')
            $rResult = $utente->getUtenteFunzioni($_REQUEST['id'], true);

        echo json_encode($rResult);
        break;
    case "saveUtente":
        $utente = new Utente();

        $ret = $utente->save(
                $_REQUEST["id"], $_REQUEST["nome"], $_REQUEST["cognome"], $_REQUEST["email"], $_REQUEST["username"], $_REQUEST["cod_funzione"]
        );
        echo $ret;

        break;
    case "deleteUtente":
        $utente = new Utente();

        if (isset($_REQUEST['id']) && ($_REQUEST['id']) != '')
            echo $utente->delete($_REQUEST['id']);
        break;
    case "setPassword":
        $utente = new Utente();

        $ret = $utente->setPassword(
                $_REQUEST["id_utente"], $_REQUEST["password"]
        );
        echo $ret;

        break;
    case "stats_stato_checklist":
        $CheckList = new CheckList();
        $ret = $CheckList->stats_stato_checklist();

        echo json_encode($ret);
        break;
}

function get_filelist_as_array($dir, $recursive = true, $basedir = '')
{
    if ($dir == '')
    {
        return array();
    }
    else
    {
        $results = array();
        $subresults = array();
    }
    if (!is_dir($dir))
    {
        $dir = dirname($dir);
    } // so a files path can be sent
    if ($basedir == '')
    {
        $basedir = realpath($dir) . DIRECTORY_SEPARATOR;
    }

    $files = scandir($dir);
    foreach ($files as $key => $value)
    {
        if (($value != '.') && ($value != '..'))
        {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (is_dir($path))
            { // do not combine with the next line or..
                if ($recursive)
                { // ..non-recursive list will include subdirs
                    $subdirresults = get_filelist_as_array($path, $recursive, $basedir);
                    $results = array_merge($results, $subdirresults);
                }
            }
            else
            { // strip basedir and add to subarray to separate file list
                $subresults[] = str_replace($basedir, '', $path);
            }
        }
    }
    // merge the subarray to give the list of files then subdirectory files
    if (count($subresults) > 0)
    {
        $results = array_merge($subresults, $results);
    }
    return $results;
}
