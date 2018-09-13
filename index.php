<?
include("loader.php");
$utente = new Utente();
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="en"><!--<![endif]-->
    <head>
        <meta charset="utf-8">


<? include("view/common/link.php"); ?>	

        <title>CATALOGO FORMATIVO - Istruttoria</title>

    </head>

    <body>

        <!-- Header -->
<? include("view/common/header.php"); ?>	

        <!-- Start Main Wrapper -->
        <div id="mws-wrapper">

            <!-- SideBar Left -->
            <? include("view/common/sidebar.php"); ?>	

            <!-- Main Container Start -->
            <div id="mws-container" class="clearfix">

                <!-- Inner Container Start -->
                <div class="container">

                    <!-- contenitore messaggi -->
                    <div id="mws-jui-dialog">
                        <div class="mws-dialog-inner"></div>
                    </div>
                    <!-- fine contenitore messaggi-->   

                    <?
                    $oper = "";
                    $sub = "";
                    //MENU
                    if (isset($_REQUEST["op"]))
                        $oper = $_REQUEST["op"];
                    if (($oper == "fn_lista_checklist") || ($oper == "") )
                        include( "view/checklist/domanda_checklist.php" );
                    if ($oper == "fn_domanda_dettaglio")
                        include( "view/checklist/domanda_dettaglio.php" );                    
                    if ($oper == "fn_elenchi_checklist")
                        include( "view/checklist/elenchi_checklist.php" );    
                    if ($oper == "fn_utenti")
                        include( "view/sicurezza/utente.php" );                   
                    if ($oper == "fn_edit_profilo")
                        include( "view/common/edit_profilo.php" );                    
                    ?>         

                </div>
                <!-- Inner Container End -->

                <!-- Footer -->
                <div id="mws-footer">
                    Realizzato dal FORMEZ&COPY;
                </div>

            </div>
            <!-- Main Container End -->

        </div>

    </body>
</html>