

<?
$oper = "";
if (isset($_REQUEST["op"]))
    $oper = $_REQUEST["op"];
?>          

<!-- Necessary markup, do not remove -->
<div id="mws-sidebar-stitch"></div>
<div id="mws-sidebar-bg"></div>

<!-- Sidebar Wrapper -->
<div id="mws-sidebar" class="clearfix">

    <!-- Hidden Nav Collapse Button -->
    <div id="mws-nav-collapse">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Main Navigation -->
    <div id="mws-navigation">
        <ul>            
            <? if ($utente->abilitato("FN_001_GD")){ ?>
                <li <? if ( ($oper == "fn_lista_checklist") || ($oper == "") ) echo ("class='active'"); ?>><a href="index.php?op=fn_lista_checklist"><i class="icon-folder-closed"></i> Domande</a></li>
            <? } ?>               
            <? if ($utente->abilitato("FN_002_GC")) { ?>
                <li <? if ($oper == "fn_elenchi_checklist") echo ("class='active'"); ?>><a href="index.php?op=fn_elenchi_checklist"><i class="icon-business-card"></i> Monitor</a></li>
            <? } ?>     
            <? if ($utente->abilitato("FN_005_GU")) { ?>
                <li <? if ($oper == "fn_utenti") echo ("class='active'"); ?>><a href="index.php?op=fn_utenti"><i class="icon-lock"></i> Utenti</a></li> 
            <? } ?>	
        </ul>
    </div>         
</div>