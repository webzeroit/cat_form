<script type="text/javascript" src="js/core/sicurezza/utente.js"></script>

  <div id="panel_ins_mod" class="mws-panel grid_8">
        <div class="mws-panel-header">
			<span id="titolo_form"><i class="icon-list"></i> Utente</span>        
        </div>
        <div class="mws-panel-body no-padding">
			<form id="form-ins-mod" class="mws-form">
	        	<div id="mws-form-message" class="mws-form-message error" style="display:none;"></div>
				<div class="mws-form-block">
                    <input type="hidden" id="id_utente" name="id_utente" />
                    
                    <div class="mws-form-row">
                        <label class="mws-form-label">Nome</label>
                        <div class="mws-form-item">
                            <input type="text" id="nome" name="nome" class="small required" />
                        </div>
                    </div>        
					<div class="mws-form-row">
                        <label class="mws-form-label">Cognome</label>
                        <div class="mws-form-item">
                            <input type="text" id="cognome" name="cognome" class="small required" />
                        </div>
                    </div>  
					<div class="mws-form-row">
                        <label class="mws-form-label">Email</label>
                        <div class="mws-form-item">
                            <input type="text" id="email" name="email" class="small required email" />
                        </div>
                    </div> 
					<div class="mws-form-row">
                        <label class="mws-form-label">Username</label>
                        <div class="mws-form-item">
                            <input type="text" id="username" name="username" class="small required" />
                        </div>
                    </div>   
                    <div class="mws-form-row">  
                    </div>
					<fieldset class="mws-form-inline"> 
	                    <legend><center><b>Abilitazioni utente</b></center></legend>  
                    </fieldset>          					
                    <table id="table_utente_funzioni" class="mws-table">
                        <tbody>                                                                                              
                        </tbody>
                    </table>                                                      
                </div>
                <div class="mws-button-row">
                    <input id="btn_salva" type="button" value="Salva" class="btn btn-success">
                    <input id="btn_chiudi" type="reset" value="Chiudi" class="btn">  
                </div>
            </form>
        </div>    	
    </div>            
    
    
	<!-- TABELLA DATI -->    
    <div class="mws-panel grid_8">
        <div class="mws-panel-header">
            <span><i class="icon-table"></i> Utenti di sistema</span>
        </div>
        <div class="mws-panel-toolbar">
            <div class="btn-toolbar">
                <div class="btn-group">
                
                    <a id="btn_aggiungi" href="#" class="btn" title="Aggiungi"><i class="icol-add"></i> Aggiungi</a>
                    <a id="btn_modifica" href="#" class="btn" title="Modifica"><i class="icol-pencil"></i> Modifica</a>
                    <a id="btn_elimina" href="#" class="btn" title="Elimina" name="0"><i class="icol-cross"></i> Elimina</a>
           
                    
                </div>
            </div>
        </div>        
        <div class="mws-panel-body no-padding">
            <table id="mws-table" class="mws-datatable mws-table">
                <thead>
                    <tr>
                        <th>id_utente</th> 	
                        <th>nome</th> 						
                        <th>cognome</th>							
                        <th>email</th>																							                        
                    </tr>
                </thead>
                <tbody>              

                </tbody>
            </table>            
        </div>    	
    </div>        