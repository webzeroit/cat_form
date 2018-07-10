<script type="text/javascript" src="js/core/checklist/domanda_dettaglio.js"></script> 
<script type="text/javascript" src="custom-plugins/wizard/jquery.form.js"></script>  


<!-- INSERISCI MODIFICA -->  
<div id="panel_ins_mod" class="mws-panel grid_8">
    <div class="mws-panel-header">
        <span id="titolo_form"><i class="icon-list"></i> Gestione domanda</span>        
    </div>
    <div class="mws-panel-body no-padding">
        <form id="form-ins-mod" class="mws-form" action="ajax.php">
            <div id="mws-form-message" class="mws-form-message error" style="display:none;"></div>
            <div class="mws-form-block">
                <!-- PRECARICATE -->
                <div class="mws-form-row">
                    <div class="mws-form-cols clearfix" >								
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">ID prog.</label>
                            <div class="mws-form-item small">
                                <input type="text" id="id_progressivo" name="id_progressivo" readonly />
                            </div>
                        </div> 	
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">ID istanza</label>
                            <div class="mws-form-item small">
                                <input type="text" id="id_istanza" name="id_istanza" readonly />
                            </div>
                        </div> 	  
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">Data Invio</label>
                            <div class="mws-form-item small">
                                <input type="text" id="data_invio" name="data_invio" readonly />
                            </div>
                        </div> 	
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">Ora Invio</label>
                            <div class="mws-form-item small">
                                <input type="text" id="ora_invio" name="ora_invio" readonly />
                            </div>
                        </div> 	                                               										
                    </div>                        
                </div> 
                <div class="mws-form-row">
                    <div class="mws-form-cols clearfix" >	
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">Identificativo Fiscale</label>
                            <div class="mws-form-item large">
                                <input type="text" maxlength="16" id="identificativo_fiscale_ente" name="identificativo_fiscale_ente" readonly  />   
                            </div>
                        </div>							
                        <div class="mws-form-col-6-8">
                            <label class="mws-form-label">Denominazione Ente</label>
                            <div class="mws-form-item large">
                                <input type="text" maxlength="255" id="denominazione_ente" name="denominazione_ente" readonly />   
                            </div>
                        </div>  	
                    </div>
                </div>                 							
                <div class="mws-form-row">
                    <div class="mws-form-cols clearfix">					
                        <div class="mws-form-col-4-8">
                            <label class="mws-form-label">Caricamento File CheckList</label>
                            <div class="mws-form-item">
                                <input id="FileInput" name="FileInput" type="file">
                            </div>
                        </div>		
                        <div class="mws-form-col-2-8">
                            <label class="mws-form-label">Esito Istruttoria</label>
                            <div class="mws-form-item large">
                                <select id="id_esito" name="id_esito" class="mws-select2 large" required>
                                    <option value=""></option>                                    
                                    <option value="1">Ammissibile</option>
                                    <option value="2">Non Ammissibile</option>
                                    <option value="3">Valutazione Sospesa</option> 
                                    <option value="4">Da Integrare</option> 
                                </select>                                       
                            </div>
                        </div>      
                        <div class="mws-form-col-2-8">	
                            <label class="mws-form-label">Ultimo caricamento Check List</label>
                            <div class="mws-form-item">
                                <input type="text" id="data_caricamento" name="data_caricamento" readonly />
                            </div>
                        </div>																																																														
                    </div>
                </div>		
                <div class="mws-form-row">
                    <div class="mws-form-cols clearfix">						
                        <div class="mws-form-col-8-8">
                            <label class="mws-form-label">ANNOTAZIONI (Max. 4000 caratteri)</label>
                            <div class="mws-form-item">
                                <textarea maxlength="4000" id="note" name="note" rows="" cols="" class="large autosize"></textarea>
                            </div>
                        </div>																				
                    </div>							
                </div>								        				
                <input id="ajaxOP" name="ajaxOP" type="hidden" value="saveFormCheckList">																								
            </div>
            <div class="mws-button-row">
                <input id="btn_salva" type="button" value="Salva" class="btn btn-success">
            </div>
        </form>
    </div>    	
</div>            

<!-- ELENCO CORSI  -->   
<div id="div_elenco_corsi" class="mws-panel grid_8 mws-collapsible">
    <div class="mws-panel-header">
        <span><i class="icon-table"></i> ELENCO CORSI</span>
    </div>
    <div class="mws-panel-body no-padding">
        <table id="table_elenco_corsi" class="mws-table">
            <thead>
                <tr>  
                    <th width="0">id_istanza</th>  
                    <th width="5%">Codice</th>                       
                    <th width="35%">Descrizione</th>
                    <th width="10%">Aula</th>
                    <th width="30%">Sede</th>
                    <th width="10%">Data Inizio</th>
                    <th width="10%">Data Fine</th>
                </tr>
            </thead>  
            <tbody>                  

            </tbody>
        </table>                	
    </div>	
</div>

<!-- FILE CONTROLLI UFFICIO -->   
<div id="div_file_controlli" class="mws-panel grid_8 mws-collapsible">
    <div class="mws-panel-header">
        <span><i class="icon-download"></i> ALLEGATI ISTANZA</span>
    </div>
    <div class="mws-panel-body no-padding">
        <table id="table_controlli" class="mws-table">
            <thead>
                <tr>                    	
                    <th width="70%">Nome File</th>                       
                    <th width="30%">Scarica</th>
                </tr>
            </thead>  
            <tbody>                  

            </tbody>
        </table>                	
    </div>	
</div>