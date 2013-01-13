<?php
function des_render_main(){
$h .= '<div class="rg_widget rg_widget_helper">
       <h3>DES '._USER_DES_NAME.'</h3>
        <p>Questa parte del sito include alcune pagine di statistiche al livello di DES (Distretto Economia Solidale). 
        Alcune sono visibili a tutti, altre sono riservate agli utenti che gestiscono tutta la rete.
        </p>
       </div>';    

return $h;    
}
function des_render_donate(){
$h .= '<div class="rg_widget rg_widget_helper" style="font-size:1.2em">
       <center>
       <h3>Supporta il futuro di ReteDes.it !!</h3>
       <p>
       Il Software Gestionale '._SITE_NAME.' è nato nel 2009 come mio impegno volontario, per il quale mi sono preso carico oltre alle ore impegnate per progettarlo e scriverlo, anche
       dei costi relativi alla gestione del server che lo sta attualmente ospitando, degli strumenti informatici (con licenze ufficiali) necessari al suo sviluppo e della registrazione e mantenimento dei domìni internet (www.retegas.info/www.retedes.it); Questo per gli anni 2009 , 2010 , 2011 e 2012.
       <strong>Il sito rimarrà gratuito senza alcuna limitazione</strong>, e se lo ritenete giusto ed equo, potete contribuire al suo mantenimento futuro offrendo la vostra collaborazione concreta (Cerco programmatori PHP - javascript, piuttosto che manutentori DB MySql, Editors per gli help ecc ecc - il lavoro non manca !)
       <strong>oppure</strong> semplicemente dimostrando la vostra soddisfazione di utilizzatori attraverso una donazione, offrendomi "virtualmente" una birra.
       </p>
       <p>
       <b>Se lo ritenete più comodo (o giusto, o cosa volete voi) potete organizzarvi a livello di DES o di GAS, parlatene con i vostri amici gasisti.</b>
       </p>
       
       <p>
       RICARICA POSTE PAY :<br><b>4023 6006 3231 6289 Mauro Morello</b><br>
       BONIFICO :<br> <b>IT36Y0306944900100000006874 conto intestato a Mauro Morello</b><br>
       PAYPAL : <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="XHTYVN8N3W3NU"><input type="image" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - Il sistema di pagamento online più facile e sicuro!"><img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1"></form><br> 
       </p>
       
       <p> 
       Personalmente vi ringrazio in ogni caso, perchè senza di voi, amici di lunga data e nuovi utenti, questo sito non sarebbe mai esistito.
       </p>
       <p>Mauro Morello.</p>
       
       </center>
              
       </div>';    

return $h;    
}
