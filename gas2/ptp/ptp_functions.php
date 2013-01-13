<?php
function ptp_header() { 
    return '<!DOCTYPE html> 
<html> 
<head> 
  <title>'._SITE_NAME.'</title> 
    
</head>
<body>'; }
function ptp_footer() { 
    global $page_start;
    return '
    <p>Pagina generata in '.(array_sum(explode(' ', microtime())) - $page_start).' secondi </p>
    </body>
        </thml>'; }
function ptp_head() {
 return "<h1>ReteDES.IT</h1>"; }
/**
 * Ritorna il footer della pagina
 * @param string $param1 name to declare
 * @param string $param2 value of the name
 * @return string 
 */
function ptp_menu() {
 return
    "<ul>
                <li><a href=\"ptp_aperti.php\">Ordini aperti</a></li>
                <li><a href=\"ptp_chiusi.php\">Ordini chiusi</a></li>
                <li><a href=\"index.php?do=logout\">Esci</a></li>
            </ul>";
    }
    
    
function ptp_lista_ordini_aperti($gas){
global $RG_addr;

//echo $site->posizione;
      
$my_query="SELECT retegas_ordini.id_ordini, 
            retegas_ordini.descrizione_ordini, 
            retegas_listini.descrizione_listini, 
            retegas_ditte.descrizione_ditte, 
            retegas_ordini.data_chiusura, 
            retegas_gas.descrizione_gas, 
            retegas_referenze.id_gas_referenze, 
            maaking_users.userid, 
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        

         $h = '<ul>'; 
         while ($row = mysql_fetch_array($result)){
         $riga++;
            
         if(id_referente_ordine_proprio_gas($row["id_ordini"],$gas)>0){
             $pal = '<strong>PARTECIPABILE</strong>';
         }else{
             $pal = '<strong>MANCA REFERENTE</strong>'; 
         }
         
        $h.=  '<li>'.$pal.' <a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></b>, di '.fullname_from_id($row["id_utente"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.</li>';
         }//end while
         
        $h.="</ul>";
         
return $h;          
 }
function ptp_ordini_io_coinvolto($id_user,$gas){

    
      
$my_query = "SELECT retegas_ordini.id_ordini, 
            retegas_ordini.descrizione_ordini, 
            retegas_listini.descrizione_listini, 
            retegas_ditte.descrizione_ditte, 
            retegas_ordini.data_chiusura, 
            retegas_gas.descrizione_gas, 
            retegas_referenze.id_gas_referenze, 
            maaking_users.userid, 
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE ((retegas_referenze.id_gas_referenze)=$gas)
            ORDER BY retegas_ordini.data_apertura DESC;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      
      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        
   
          
         while ($row = mysql_fetch_array($result)){
         
         
         $io_cosa_sono = ordine_io_cosa_sono($row["id_ordini"],$id_user);
         //echo "IO = $io_cosa_sono<br>";
         switch($io_cosa_sono){
             case 1:$io_sono = "";break;
             case 2:$io_sono = "Partecipo";$riga++;break;
             case 3:$io_sono = "Gestisco per il mio GAS";$riga++;break;
             case 4:$io_sono = "Gestisco l'ordine";$riga++;break;  
         }
         
         if(gas_mktime(conv_datetime_from_db($row["data_apertura"]))>gas_mktime(date("d/m/Y H:i"))){
                $pal = '<strong>FUTURO</strong>';   
         }else{
                if(gas_mktime(conv_datetime_from_db($row["data_chiusura"]))>gas_mktime(date("d/m/Y H:i"))){
                      if(id_referente_ordine_proprio_gas($row["id_ordini"],$gas)>0){ 
                            $pal = '<strong>PARTECIPABILE</strong>';
                      }else{
                            $pal = '<strong>MANCA REFERENTE</strong>'; 
                      }    
                }else{
                      if(is_printable_from_id_ord($row["id_ordini"])){
                             $pal = '<strong>CHIUSO E CONFERMATO</strong>';
                      }else{
                             $pal = '<strong>CHIUSO</strong>'; 
                      }
                    
                
                }
         } 

        if(($io_cosa_sono>1)){
            $h_table.=  '<li>
            '.$pal.'
            <a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'"><b>'.$row["descrizione_ordini"].'</b></a>, io '.$io_sono.' 
                         </li>
                        ';
            }           
        }//end while
         
         
       $h_table .='</ul>';
         
return $h_table;          
    

    
}
?>