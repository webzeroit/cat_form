<script type="text/javascript" src="js/core/checklist/domanda_checklist.js"></script> 


<!-- TABELLA DATI -->    
<div class="mws-panel grid_8">
    <div class="mws-panel-header">
        <span><i class="icon-table"></i> Elenco domande</span>
    </div>
    <div class="mws-panel-toolbar">
        <div class="btn-toolbar">
            <div class="btn-group">            
                <a id="btn_modifica" href="#" class="btn" title="Gestisci"><i class="icol-application-edit"></i> Gestione</a>   	
                <a id="btn_export" href="#" class="btn" title="Esporta tabella"><i class="icol-page-white-excel"></i> Esporta tabella</a>                                
            </div>
        </div>
    </div>        
    <div class="mws-panel-body no-padding">
        <table id="tbl_elenco_domande" class="mws-datatable mws-table" >
            <thead>
                <tr>     	
                    <th width="5%">ID Progr.</th>
                    <th width="5%">ID Istanza</th>
                    <th width="30%">Ente</th>
                    <th width="10%">C.F.</th>
                    <th width="0">stato_istanza</th>	
                    <th width="10%">Data invio</th>				
                    <th width="10%">Ora invio</th>					
                    <th width="0">id_utente</th>
                    <th width="0">data_caricamento</th>									
                    <th width="10%">Scarica CheckList</th>    					
                    <th width="10%">Stato CheckList</th>  
                    <th width="10%">Esito</th>  
                </tr>
            </thead>
            <tbody>              

            </tbody>
        </table>            
    </div>    	
</div>    
 <input id="id_utente" name="id_utente" type="hidden" value="<?=$_SESSION["id_utente"]; ?>"/>
<? if ($utente->abilitato("AB_001"))
{
?>
    <input id="id_ruolo" name="id_ruolo" type="hidden" value="AB_001"/>
<?
}
else
{
    ?>
    <input id="id_ruolo" name="id_ruolo" type="hidden" value="AB_000"/>
<? } ?>            