<style>

.anyClass {
  height:360px;
  overflow-y: auto;
}
</style>
<?php

$_language->readModule('info', false, true);

function getter($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$userID = (int)$_SESSION['userID']; // Angemeldet?

// Beispiel: Benutzername und letzte Anmeldung holen
$statement = $_database->prepare("SELECT username, lastlogin FROM users WHERE userID = ?");
$statement->bind_param('i', $userID);
$statement->execute();
$statement->bind_result($username, $lastlogin);
$statement->fetch();
$statement->close();
$datetime = new DateTime($lastlogin);
$lastlogin_formatted = $datetime->format('d.m.Y \u\m H:i \U\h\r');

echo'<div class="card">
      <div class="card-header">
            <i class="bi bi-speedometer" style="font-size: 1rem;"></i> '.$_language->module['title'].'
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <img src="/admin/images/info-logo.png" style="max-width: 100%;height: auto;">
              </div>            
              <div class="card-body" style="min-height: 270px">
                <h4>'.$_language->module['welcome'].'</h4>
                '.$_language->module['hello'].' <b>'.$username.'</b> '.$_language->module['last_login'].' '.$lastlogin_formatted.'.
                '. $_language->module['welcome_message'].'
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card" style="margin-left: 50px; margin-right: 50px">
              <div class="card-header">
                <i class="bi bi-ticket" style="font-size: 1rem;"></i> '.$_language->module['live_ticker'].'
              </div>
              <div class="card-body" style="height: 400px">
                <div class="anyClass">
                  <div class="alert alert-warning" role="alert">';
                    echo getter('https://www.webspell-rm.de/includes/modules/live_ticker.php');
                  echo'</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card" style="margin-left: 50px; margin-right: 50px">
          <div class="card-header">
           <i class="bi bi-info-circle" style="font-size: 1rem;"></i> '.$_language->module['update_support'].'
          </div>
          <div class="card-body">
            <div class="style_prevu_kit">
              <a href="admincenter.php?site=update" target="_self" style="text-decoration:none">
                <div class="cart">
                  <div class="cart-block">
                    <div class="logo1 image_caption text-center" style="height:220px">
                      <span style="margin-top: -35px">'.$_language->module['version_check'].'';
                        #if (!$getnew = @file_get_contents($updateserverurl.'/base/vupdate.php')) {
                        #  echo '<i><b>' . $_language->module[ 'error' ] . '</b></i>';
                        #} else {
                        #  echo ''.$updatetxt.'';
                        #}    
                      echo'</span>
                    </div>
                  </div>
                  <div class="cart-header" style="text-align: center;">
                    <p style="margin-top: 8px"><i class="bi bi-info-circle" style="font-size: 1rem;"></i> '.$_language->module['install_version'].' <b></b></p>
                  </div>
                </div>
              </a>
            </div>
            <div class="style_prevu_kit">
              <div class="cart">
                <div class="cart-block">
                  <div class="logo1 image_caption text-center" style="height:220px">
                    <span style="margin-top: -35px">'.$_language->module['server_check'].'                    
                      Basesystem <br>
                      Pluginsystem <br>
                      Themesystem  <br>
                      '.$_language->module['server_used'].': 
                    </span>
                  </div>
                </div>
                <div class="cart-header" style="text-align: center;">
                  <p style="margin-top: 8px"><i class="bi bi-database-fill-check" style="font-size: 1rem;"></i> '.$_language->module['serversystem_text'].'</p>
                  
                </div>
              </div>
            </div>
            <div class="style_prevu_kit">
              <a href="https://webspell-rm.de/index.php?site=forum" target="_blank" style="text-decoration:none">
                <div class="cart">
                  <div class="cart-block">
                    <div class="logo1 image_caption text-center" style="height:220px">
                      <span style="margin-top: -35px">'.$_language->module['forum'].'</span>
                    </div>
                  </div>
                  <div class="cart-header" style="text-align: center;">
                    <p style="margin-top: 8px"><i class="bi bi-chat-left-text" style="font-size: 1rem;"></i> '.$_language->module['forum_text'].'</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="style_prevu_kit">
              <a href="https://www.webspell-rm.de/wiki.html" target="_blank" style="text-decoration:none">
                <div class="cart">
                  <div class="cart-block">
                    <div class="logo1 image_caption text-center" style="height:220px">
                      <span style="margin-top: -35px">'.$_language->module['wiki'].'</span>
                    </div>
                  </div>
                  <div class="cart-header" style="text-align: center;">
                    <p style="margin-top: 8px"><i class="bi bi-wikipedia" style="font-size: 1rem;"></i> '.$_language->module['wiki_text'].'</p>   
                  </div>
                </div>
              </a>
            </div>
            <div class="style_prevu_kit">
              <a href="https://discordapp.com/invite/SgPrVk?utm_source=Discord%20Widget&utm_medium=Connect" target="_blank" style="text-decoration:none">
                <div class="cart">
                  <div class="cart-block">
                    <div class="logo1 image_caption text-center" style="height:220px">
                      <span style="margin-top: -35px">'.$_language->module['discord'].'</span>
                    </div>
                  </div>
                  <div class="cart-header" style="text-align: center;">
                    <p style="margin-top: 8px"><i class="bi bi-discord" style="font-size: 1rem;"></i> '.$_language->module['discord_text'].'</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>

  </div>
</div>

';
