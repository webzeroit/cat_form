<script type="text/javascript" src="js/core/checklist/elenchi_checklist.js"></script> 


<input id="id_utente" name="id_utente" type="hidden" value="<?= $_SESSION["id_utente"]; ?>"/>
<? if ($utente->abilitato("AB_001"))
{ ?>
    <input id="id_ruolo" name="id_ruolo" type="hidden" value="AB_001"/>
<? }
else
{ ?>
    <input id="id_ruolo" name="id_ruolo" type="hidden" value="AB_000"/>
<? } ?>    
<!-- Statistiche -->
<div class="mws-stat-container clearfix">

    <!-- Statistic Item -->
    <a class="mws-stat" title="Totale domande presentate">
        <!-- Statistic Icon (edit to change icon) -->
        <span class="mws-stat-icon icol32-package"></span>

        <!-- Statistic Content -->
        <span class="mws-stat-content">
            <span id="stat_tot_presentate_label" class="mws-stat-title">Domande presentate</span>
            <span id="stat_tot_presentate" class="mws-stat-value">0</span>
        </span>
    </a>

    <!-- Statistic Item -->
    <a class="mws-stat" title="Checklist caricate">
        <!-- Statistic Icon (edit to change icon) -->
        <span class="mws-stat-icon icol32-save-as"></span>

        <!-- Statistic Content -->
        <span class="mws-stat-content">
            <span id="stat_tot_checklist_label" class="mws-stat-title">Checklist caricate</span>
            <span id="stat_tot_checklist" class="mws-stat-value">0</span>
        </span>
    </a>

    <!-- Statistic Item -->
    <a class="mws-stat" title="Checklist da caricare">
        <!-- Statistic Icon (edit to change icon) -->
        <span class="mws-stat-icon icol32-document-prepare"></span>

        <!-- Statistic Content -->
        <span class="mws-stat-content">
            <span id="stat_tot_checklist_ko_label" class="mws-stat-title">Checklist da caricare</span>
            <span id="stat_tot_checklist_ko" class="mws-stat-value">0</span>
        </span>
    </a>                
</div>	

<!-- Sommario Istruttoria -->
<div class="mws-panel grid_2">
    
</div>

<div class="mws-panel grid_4">
    <div class="mws-panel-header">
        <span><i class="icon-book"></i> Esito delle istruttorie</span>
    </div>
    <div class="mws-panel-body no-padding">
        <ul class="mws-summary clearfix">
            <li>
                <span class="key"><i class="icon-eye-closed"></i> Sospese</span>
                <span class="val">
                    <span id="num_sospese" class="text-nowrap">0</span>
                </span>
            </li>                                
            <li>
                <span class="key"><i class="icon-edit"></i> Da Integrare</span>
                <span class="val">
                    <span id="num_integrare" class="text-nowrap">0</span>
                </span>
            </li> 
            <li>
                <span class="key"><i class="icon-thumbs-down"></i> Non Ammissibili</span>
                <span class="val">
                    <span id="num_non_ammissibili" class="text-nowrap">0</span>
                </span>
            </li>   
            <li>
                <span class="key"><i class="icon-thumbs-up"></i> Ammissibili</span>
                <span class="val">
                    <span id="num_ammissibili" class="text-nowrap">0</span>
                </span>
            </li>  				                                                                                                                 
        </ul>
    </div>
</div>
<!-- Sommario Istruttoria -->
<div class="mws-panel grid_2">
    
</div>