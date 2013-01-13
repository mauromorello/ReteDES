<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     //ssa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     //pussa_via();
}

//do = 423409893hf93ojdhsdfg4325
if($do=="423409893hf93ojdhsdfg4325"){
    
    $e = 0;  //ERRORS
    $w = 0;  //WARNINGS
    $d = 0;  //DB Issues
    $c = 0;  //COnsigli
    
    $h .= "Situazione sito al ".date("j/F/Y, G:i:s")."<hr>";
   
    //---------------------------------------MAIL
    $sql = "SELECT * FROM maaking_users WHERE (user_permission & 65536);";
    $res = $db->sql_query($sql);
    
    $h.="<h3>Amministratori RETEGAS :</h3>";
    $h.="<p>";
    
    $verso_chi = array();
    $mail_verso_chi = array();
    while($row = mysql_fetch_array($res)){
        $h.=  $row["fullname"]." (".gas_nome($row["id_gas"]).") PERM : ".$row["user_permission"]."<br>";
        $verso_chi[] = $row["fullname"];
        $mail_verso_chi[] = $row["email"];
    }
    $h .="</p>"; 
    $h .="<hr>";
    //--------------------------------------MAIL
    
    
    //--------------------------------------USERS & GAS
    $h.="<h4>Situazione Gas e Users</H4>";
    
    $ut_not_act = user_non_attivi();
    $h.="Utenti ReteGas Totali NON attivi : $ut_not_act<br>";
    $ut_geo_ok  =   utenti_con_geocode_ok($row["id_gas"]);
    $h.="Utenti ReteGas Geocode OK : $ut_geo_ok<hr>";
    
    
    $h.="<div style=\"font-size:10px;\">";
    
    $sql= "SELECT * FROM retegas_gas";
    $res = $db->sql_query($sql);
    while($row = mysql_fetch_array($res)){
        $h.="<strong>".$row["descrizione_gas"]."</strong> - ";
        
        $ut_tot = gas_n_user($row["id_gas"]);
        $ut_att = gas_n_user_Act($row["id_gas"]);
        $ut_att_att = utenti_attesa_attivazione($row["id_gas"]);
        
        $h.="Attesa Attivazione : $ut_att_att; Totali : $ut_tot; Attivi : $ut_att <br>";
        if($ut_att_att>5){
            $w++;
            $h_w .= $row["descrizione_gas"]." ha $ut_att_att utenti in attesa attivazione";
        }
        
        
        
 
    }
    $h.="</div>";
    //--------------------------------------USERS & GAS
    
    //----------------------DATABASE
    
  
      
      // Dettagli ordini senza ordine
      
      $d_o_s_o = db_dettagli_ordine_senza_ordine();
      if($d_o_s_o==0){          
        $dettagli_ordini_senza_ordine='ORDINE -> ORDINE: <b>OK</b><br>';    
      }else{
        $dettagli_ordini_senza_ordine='DETTAGLI ORDINE -> ORDINE : Ci sono <b>'.$d_o_s_o.'</b> dettagli ordine senza ordine papà.';      
        $e++;
        $h_e .= $dettagli_ordini_senza_ordine;
      }
      
      // Distribuzione senza dettagli
      $d_s_s_d_o = db_distribuzione_spesa_senza_dettagli_ordine();
      if($d_s_s_d_o==0){          
        $distribuzione_spesa_senza_dettagli_ordine='DISTRIBUZIONE SPESA -> DETTAGLI ORDINE : <b>OK</b><br>';    
      }else{
        $distribuzione_spesa_senza_dettagli_ordine='DISTRIBUZIONE SPESA -> DETTAGLI ORDINE : Ci sono <b>'.$d_s_s_d_o.'</b> distribuzioni spesa SENZA dettagli ordine.<br>';      
        $e++;
        $h_e .= $distribuzione_spesa_senza_dettagli_ordine;
      } 
      
      // Amici senza referente
      $a_s_r = db_amici_senza_referente();
      if($a_s_r==0){          
        $amici_senza_referente='AMICI -> USER : <b>OK</b><br>';    
      }else{
        $amici_senza_referente='AMICI -> USER : Ci sono <b>'.$a_s_r.'</b> amici SENZA referente.<br>';      
        $e++;
        $h_e .= $amici_senza_referente;
      }
      
      // Articoli senza listino
      $a_s_l = db_articoli_senza_listino();
      if($a_s_l==0){          
        $articoli_senza_listino='ARTICOLI -> LISTINI : <b>OK</b><br>';    
      }else{
        $articoli_senza_listino='ARTICOLI -> LISTINI : Ci sono <b>'.$a_s_l.'</b> Articoli SENZA Listino.<br>';      
        $e++;
        $h_e .= $articoli_senza_listino;
      }
      
      // Listini senza ditte
      $l_s_d = db_listini_senza_ditte();
      if($l_s_d==0){          
        $listini_senza_ditte='LISTINI -> DITTE : <b>OK</b><br>';    
      }else{
        $listini_senza_ditte='LISTINI -> DITTE : Ci sono <b>'.$l_s_d.'</b> Listini SENZA Ditte.<br>';      
        $e++;
        $h_e .= $listini_senza_ditte;
      }  
      
      // Referenze senza ordine
      $r_s_o = db_referenze_senza_ordine();
      if($r_s_o==0){          
        $referenze_senza_ordine='REFERENZE -> ORDINI: <b>OK</b><br>';    
      }else{
        $referenze_senza_ordine='REFERENZE -> ORDINI: : Ci sono <b>'.$r_s_o.'</b> Referenze SENZA Ordini.<br';      
        $e++;
        $h_e .= $referenze_senza_ordine;
      }
      
      
      // Ccda postino totale
      $coda_totale = quante_mail_coda_totale();
      if($coda_totale==0){          
        $coda_totale='Coda Postino : <b>Vuota</b><br>';    
      }else{
        $coda_totale='Coda Postino : Ci sono in totale <b>'.$coda_totale.'</b> Messaggi non consegnati<br>';      
        $w++;
        $h_w .= $coda_totale;
      }
      
      // Ccda postino effettiva
      $coda_effettiva = quante_mail_coda_effettiva();
      if($coda_effettiva==0){          
        $coda_effettiva='Coda Postino : <b>Vuota</b><br>';    
      }else{
        $coda_effettiva='Coda Postino : Ci sono <b>'.$coda_effettiva.'</b> Messaggi non consegnati<br>';      
        $w++;
        $h_w .= $coda_effettiva;
      }
      
      // Status Mailer
      
      $mailer_status = read_option_text(0,"MAILER");
      //echo read_option_text(0,"MAILER");
      if($mailer_status=="ON"){          
        $mailer_status='Mailer  : <b>ON</b><br>';    
      }else{
        $mailer_status='Mailer : <b>OFF</b><br>';      
        $w++;
        $h_w .= $mailer_status;
      }
      
      // Status Mailer
      $debug_status = read_option_text(0,"DEBUG");
      if($debug_status=="OFF"){          
        $debug_status='Debug : <b>OFF</b><br>';    
      }else{
        $debug_status='Debug : <b>ON</b><br>';      
        $w++;
        $h_w .= $debug_status;
      }
      
    
      //-----------------------------------LOG
      $sql = "SELECT * FROM retegas_messaggi ORDER BY id_messaggio DESC LIMIT 1";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      
      $last_log_id = $row[0];
      $previous_log_id = read_option_integer(0,"_LAST_LOG_ID");
      $logs_generated = $last_log_id-$previous_log_id;
      
      write_option_integer(0,"_LAST_LOG_ID",$last_log_id);
      
      $h .="<h4>LOG</h4>";
      $h .="LAST LOG ID = $last_log_id<br>";
      $h .="PREVIOUS LOG ID = $previous_log_id<br>";
      $h .="GENERATED LOGS = $logs_generated<br>";
      
      $h .="<div style=\"font-size:8px;\">";
      $sql = "SELECT * FROM retegas_messaggi ORDER BY id_messaggio DESC LIMIT $logs_generated;";
      $res = $db->sql_query($sql);
      while($row = mysql_fetch_array($res)){
      if($row["tipo"]=="ERR"){
              $e++;
              $h_e = "LOG con errore : <br>";
              $pre = "<p style=\"color:red;\">";
              $post="</p>";
      }else{
          $pre="";
          $post ="";
      }
      
          $h .= $pre.$row["timbro"]." - ".$row["tipo"]."/".$row["tipo2"]." - ".fullname_from_id($row["id_user"])."-".descrizione_ordine_from_id_ordine($row["id_ordine"])." - ".myTruncate(strip_tags($row["messaggio"]),30," ")." - ".myTruncate(strip_tags($row["query"]),40," ")."<br>".$post;

      }
      $h .="</div>";
      
      
      //-----------------------------------LOG  
    
    
    
      //DELETE OLD LOGS
      $sql = "SELECT * FROM retegas_messaggi WHERE timbro < DATE_SUB(NOW(), INTERVAL 6 MONTH) ORDER BY id_messaggio DESC;";
      $res = $db->sql_query($sql);
      $n_del = $db->sql_numrows($res);
      $h .="<h3>LOG TO DELETE : $n_del</h3>";
      $h .="<div style=\"font-size:8px;\">";
      while($row = mysql_fetch_array($res)){
            $h .= $row["timbro"]." - ".$row["tipo"]."/".$row["tipo2"]." - ".fullname_from_id($row["id_user"])."-".descrizione_ordine_from_id_ordine($row["id_ordine"])." - ".myTruncate(strip_tags($row["messaggio"]),30," ")." - ".myTruncate(strip_tags($row["query"]),40," ")."<br>"; 
      }
      $h .="</div>";
      $sql = "DELETE FROM retegas_messaggi WHERE timbro < DATE_SUB(NOW(), INTERVAL 6 MONTH) ORDER BY id_messaggio DESC;";
      $res = $db->sql_query($sql);
      
      //DELETE OLD LOGS  
    
    
    $h .= ' <div>                    
                   
                   
                   <h4>Incongruenze relazioni database :</h4>
                   '.$dettagli_ordini_senza_ordine.'
                   '.$distribuzione_spesa_senza_dettagli_ordine.'
                   '.$amici_senza_referente.'
                   '.$articoli_senza_listino.'
                   '.$listini_senza_ditte.'
                   '.$referenze_senza_ordine.'
                   <h4>Status :</h4>
                   '.$debug_status.'
                   '.$mailer_status.'
                   '.$coda_totale.'
                   '.$coda_effettiva.'
                   </div> 
                   ';
    
    
    
    //----------------------DATABASE
    
}

$soggetto = "RETEGAS al ".date("j/F/Y, G:i:s");
if($e>0){
    
    $h_e = "<h3 style=\"color:#FF0000\">ERRORI GRAVI</h3><p>".$h_e."</p><hr>";
    $h = $h_e
         .$h;
}

if($w>0){
    
    $h_w = "<h3 style=\"color:#FF8040\">WARNINGS</h3><p>".$h_w."</p><hr>";
    $h = $h_w
         .$h;
}


if(($e+$w)==0){
    $msg = "Tutto ok. Vai tranquillo.";
    log_me(0,0,"HAL","REP",$msg,0,$h);
}else{
    $res_mail = manda_mail_multipla_istantanea(_SITE_NAME,_SITE_MAIL_REAL,$verso_chi,$mail_verso_chi,$soggetto,NULL,"EML",0,0,$h);
}
$h.="<h4>Warnings : $w</h4>";
$h.="<h4>Errors : $e</h4>";

echo sistema_accenti($h);
   
?>