<!-- Header -->
<div id="mws-header" class="clearfix">

    <!-- Logo Container -->
    <div id="mws-logo-container">
    
        <!-- Logo Wrapper, images put within this wrapper will always be vertically centered -->
        <div id="mws-logo-wrap">
            <img src="images/mws-logo.png" alt="Gestione Checklist" />
        </div>
    </div>
    
    <!-- User Tools (notifications, logout, profile, change password) -->
    <div id="mws-user-tools" class="clearfix">
    
     
        
        <!-- User Information and functions section -->
        <div id="mws-user-info" class="mws-inset">
        
            <!-- User Photo -->
            <div id="mws-user-photo">
                <img src="images/profile.png" alt="User Photo" />
            </div>
            
            <!-- Username and Functions -->
            <div id="mws-user-functions">
                <div id="mws-username">
                    Benvenuto, <? echo $_SESSION["nome"] . ' ' . $_SESSION["cognome"];?>
                </div>
                <ul>
                    <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?op=fn_edit_profilo">Cambia password</a></li>
                    <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?logout=true">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>