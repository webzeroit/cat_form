<script type="text/javascript" src="js/core/edit_profilo.js"></script>  

   <div id="panel_ins_mod" class="mws-panel grid_4">
        <div class="mws-panel-header">
			<span id="titolo_form"><i class="icon-unlock"></i> Modifica password</span>        
        </div>
        <div class="mws-panel-body no-padding">
        	<form id="form-password" class="mws-form">
	            <div id="mws-form-message" class="mws-form-message error" style="display:none;"></div>
   	            <div class="mws-form-message info">ATTENZIONE! L'operazione di modifica comporta la disconnessione dell'utente attualmente collegato.<br/>Per applicare le modifiche dovrai riconnetterti al sistema.</div>
               	<div class="mws-form-block">	
                    <div class="mws-form-row">
	                    <input type="hidden" id="id_utente" name="id_utente" value="<?= $_SESSION["id_utente"] ?>"/>
                        <label class="mws-form-label">Username</label>
                        <div class="mws-form-item small">
                            <input type="text" id="username" name="username" readonly="readonly" value="<?= $_SESSION["username"] ?>"/>
                        </div>
                    </div>                 				
                    <div class="mws-form-row">
                        <label class="mws-form-label">Nuova password</label>
                        <div class="mws-form-item small">
                            <input type="password" id="password" name="password" class="required password1" />
                        </div>
                    </div> 
                    <div class="mws-form-row">
                        <label class="mws-form-label">Conferma nuova password</label>
                        <div class="mws-form-item small">
                            <input type="password" id="conferma_password" name="conferma_password" class="required password2"/>
                        </div>
                    </div>                                        
                </div>
                <div class="mws-button-row">
                    <input id="btn_salva_password" type="button" value="Salva" class="btn btn-success">
                </div>
            </form>
        </div>    	
    </div> 