<?php


//VALORI SU QUANTITA' ORDINATE


/**
 * valore_totale_ordine() ATTENZIONE : Il valore di questa funzione è sulla quantità ORDINATA !!!
 * 
 * @param mixed $idu   id dell'ordine;
 * @return             valore della merce ordinata
 */
function valore_totale_ordine($idu){
$sql="SELECT
Sum(retegas_dettaglio_ordini.qta_ord*retegas_articoli.prezzo) AS somma_valore_ordine
FROM
retegas_ordini
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_ordini.id_ordini =  '$idu'
GROUP BY
retegas_ordini.id_ordini,
retegas_ordini.id_listini,
retegas_ordini.descrizione_ordini,
retegas_ordini.costo_trasporto,
retegas_ordini.costo_gestione
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return (float)round($row[0],4);
   
}


//VALORI SU QUANTITA' ARRIVATE
 
function valore_totale_mio_ordine_lordo($idu,$usr){
$mio_o =  valore_totale_mio_ordine($idu,$usr);
//echo "mio=".$mio_o."<br>";

$vto = valore_totale_ordine_qarr($idu);

//echo "vto=".$vto."<br>"; 
if ($vto>0){    
$perc = (float)($mio_o/$vto)*100;

$tras = valore_trasporto($idu,$perc);
//echo "perc=".$perc."<br>";
//echo "tras=".$tras."<br>"; 
$gest  =valore_gestione($idu,$perc);
//echo "perc=".$perc."<br>"; 
//echo $perc;
return (float)round($mio_o + $tras + $gest,4);
}else{
return "";    
	
}
}

function valore_trasporto($ord,$perc){
	
   
$sql="SELECT retegas_ordini.costo_trasporto FROM retegas_ordini WHERE retegas_ordini.id_ordini = '$ord'";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);

  $ct = (float)($row[0]/100)*$perc;
  
  return $ct;
}
function valore_gestione($ord,$perc){
$sql="SELECT retegas_ordini.costo_gestione FROM retegas_ordini WHERE retegas_ordini.id_ordini = '$ord'";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  
  $cg = ($row[0]/100)*$perc;
  return $cg;
}
function valore_totale_lordo_ordine($idu){    
return valore_totale_ordine_qarr($idu)+valore_trasporto($idu,100)+valore_gestione($idu,100);    
}

//Valore di tutto il giro Retegas
function totale_retegas_netto(){
global $db;
$qry = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo)
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli";
 
$res = $db->sql_query($qry);
$row = $db->sql_fetchrow($res);

return (int) $row[0]; 
    
}


// Maggiorazioni riferite ai singoli GAS
function valore_percentuale_maggiorazione_mio_gas($ordine,$gas){
	global $db;
	$query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
	$result = $db->sql_query($query);
	$row = $db->sql_fetchrow($result);

	return $row["maggiorazione_percentuale_referenza"];         
}


function valore_maggiorazione_mio_gas($ordine,$gas,$cifra){
	global $db;
	$query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
	$result = $db->sql_query($query);
	$row = $db->sql_fetchrow($result);

	
	
	
	(float)$risult =  ((float)$row["maggiorazione_percentuale_referenza"]/100) * $cifra;         

	return $risult;
}
function valore_percentuale_costo_mio_gas($ordine,$gas,$cifra){
	global $db;
	$query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$ordine' AND id_gas_referenze='$gas';";
	$result = $db->sql_query($query);
	$row = $db->sql_fetchrow($result);

	
	$totale = valore_totale_ordine_qarr($ordine);
	
	(float)$risult =  ((float)$row["maggiorazione_referenza"]/$totale) * $cifra;         

	return $risult;
}

//----------------------------------------------------VALORI ORDINI

function valore_netto_arr_articolo_ordine_user($id_articolo,$id_ordine,$id_user){

(int)$id_articolo;
(int)$id_ordine;
(int)$id_user;
	
global $db;

$query = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo)
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$id_ordine' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_articoli = '$id_articolo';";
$res = $db->sql_query($query);
$row = $db->sql_fetchrow($res);

if(is_null($row[0])){
	return 0;
}else{
	return (float)round($row[0],4);    
}
	
}
function valore_costo_trasporto_arr_articolo_ordine_user($id_articolo,$id_ordine,$id_user){

 global $valore_totale_ordine_per_costo_trasporto;   
(int)$id_articolo;
(int)$id_ordine;
(int)$id_user;

if(!isset($valore_totale_ordine_per_costo_trasporto)){
	//echo "NON SONO SETTATO" ;
	$valore_totale_ordine_per_costo_trasporto = valore_totale_ordine_qarr($id_ordine); 
}    
global $db;

$query = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo)
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$id_ordine' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_articoli = '$id_articolo';";
$res = $db->sql_query($query);
$row = $db->sql_fetchrow($res);

if(is_null($row[0])){
	return 0;
}else{
	//echo $row[0]."<br>";
	
	
	if($valore_totale_ordine_per_costo_trasporto > 0){
	$percentuale = (float)(($row[0] / $valore_totale_ordine_per_costo_trasporto)*100);
	//echo "PERCENTUALE". $percentuale."<br>";   
		return (float)round(valore_trasporto($id_ordine,$percentuale),4);
	}else{
		return 0;
	}
			
}
	
}
function valore_costo_gestione_arr_articolo_ordine_user($id_articolo,$id_ordine,$id_user){

 global $valore_totale_ordine_per_costo_gestione;   
(int)$id_articolo;
(int)$id_ordine;
(int)$id_user;

if(!isset($valore_totale_ordine_per_costo_gestione)){
	//echo "NON SONO SETTATO" ;
	$valore_totale_ordine_per_costo_gestione = valore_totale_ordine_qarr($id_ordine); 
}    
global $db;

$query = "SELECT
Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo)
FROM
retegas_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_dettaglio_ordini.id_ordine =  '$id_ordine' AND
retegas_dettaglio_ordini.id_utenti =  '$id_user' AND
retegas_dettaglio_ordini.id_articoli = '$id_articolo';";
$res = $db->sql_query($query);
$row = $db->sql_fetchrow($res);

if(is_null($row[0])){
	return 0;
}else{
	//echo $row[0]."<br>";
	
	
	if($valore_totale_ordine_per_costo_gestione > 0){
	$percentuale = (float)(($row[0] / $valore_totale_ordine_per_costo_gestione)*100);
	//echo "PERCENTUALE". $percentuale."<br>";   
		return (float)round(valore_gestione($id_ordine,$percentuale),4);
	}else{
		return 0;
	}
			
}
	
}    


//Valore ARR AMICO :                    Parametri   : Ordine, idUser, Amico
function valore_netto_singolo_amico($ordine,$id_user,$amico){
//echo "--id_ord: ". $ordine;
//echo "--id_amico: ". $amico;
//echo "--id user: ". $id_user."<br>"; 
  
// la nuova query si appoggia sull'id articolo reperito dalla tabella dettaglio ordini
$sql ="SELECT Sum(retegas_articoli.prezzo * retegas_distribuzione_spesa.qta_arr) as tot_amico
FROM
retegas_distribuzione_spesa
Inner Join retegas_dettaglio_ordini ON retegas_distribuzione_spesa.id_riga_dettaglio_ordine = retegas_dettaglio_ordini.id_dettaglio_ordini
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE retegas_distribuzione_spesa.id_user = '$id_user' 
AND retegas_distribuzione_spesa.id_ordine = '$ordine' 
AND retegas_distribuzione_spesa.id_amico = '$amico';";



//echo $sql;
$ret = mysql_query($sql);
$row = mysql_fetch_row($ret);
//echo "RESULT".$row[0]."<br>";
return round($row[0],4);
  
}
//Valore ARR AMICO COSTO TRASPORTO :    Parametri   : Ordine,id_user, Amico
function valore_costo_trasporto_ordine_amico($id_ordine,$id_user,$id_amico){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_personale_attuale_netto_qarr = valore_netto_singolo_amico($id_ordine,$id_user,$id_amico);
    $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
    return (float)$costo_trasporto;
}
//Valore ARR AMICO COSTO GESTIONE :     Parametri   : Ordine, id_user,  Amico
function valore_costo_gestione_ordine_amico($id_ordine,$id_user,$id_amico){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_personale_attuale_netto_qarr = valore_netto_singolo_amico($id_ordine,$id_user,$id_amico);
    $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;
    return (float)$costo_gestione;
}
//Valore ARR AMICO COSTO GAS :          Parametri   : Ordine, User, Amico
function valore_costo_mio_gas_amico($id_ordine,$id_user,$id_amico){
    
    $id_gas = id_gas_user($id_user);
    $valore_personale_attuale_netto_qarr =valore_netto_singolo_amico($id_ordine,$id_user,$id_amico);
    $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
    $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;
    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    $costo_personale_mio_gas = ($costo_globale_mio_gas /100) * $percentuale_mio_ordine_gas;
    return (float)$costo_personale_mio_gas;
}
//Valore ARR AMICO MAGGIORAZIONE GAS :  Parametri   : Ordine, User, Amico
function valore_costo_maggiorazione_mio_gas_amico($id_ordine,$id_user,$id_amico){
    
    $id_gas=id_gas_user($id_user);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    $valore_personale_attuale_netto_qarr = valore_netto_singolo_amico($id_ordine,$id_user,$id_amico);
    $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;
    return (float)$valore_maggiorazione_mio_gas;
}
//Valore ARR AMICO COSTI TOTALI ORDINE :Parametri   : Ordine, User, Amico
function valore_costi_totali_amico($id_ordine,$id_user,$id_amico){
    $costo_trasporto = valore_costo_trasporto_ordine_amico($id_ordine,$id_user,$id_amico);
    $costo_gestione = valore_costo_gestione_ordine_amico($id_ordine,$id_user,$id_amico);
    $costo_mio_gas = valore_costo_mio_gas_amico($id_ordine,$id_user,$id_amico);
    $costo_maggiorazione = valore_costo_maggiorazione_mio_gas_amico($id_ordine,$id_user,$id_amico);
    $costi_totali = $costo_trasporto +
                    $costo_gestione +
                    $costo_mio_gas +
                    $costo_maggiorazione;
   return (float)$costi_totali;                           
}
//Valore ARR AMICO TOTALI ORDINE :      Parametri   : Ordine, User, Amico



//---------------------------------------USER

//Valore ARR USER :                     Parametri   : Ordine, User
function valore_totale_mio_ordine($idu,$usr){
$sql="SELECT
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS somma_valore_ordine
FROM
retegas_ordini
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_ordini.id_ordini =  '$idu' AND
retegas_dettaglio_ordini.id_utenti='$usr'
GROUP BY
retegas_ordini.id_ordini,
retegas_ordini.id_listini,
retegas_ordini.descrizione_ordini,
retegas_ordini.costo_trasporto,
retegas_ordini.costo_gestione
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return $row[0];
   
}
function valore_arrivato_netto_ordine_user($id_ordine,$id_user){
$sql="SELECT
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS somma_valore_ordine
FROM
retegas_ordini
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_ordini.id_ordini =  '$id_ordine' AND
retegas_dettaglio_ordini.id_utenti='$id_user'
GROUP BY
retegas_ordini.id_ordini,
retegas_ordini.id_listini,
retegas_ordini.descrizione_ordini,
retegas_ordini.costo_trasporto,
retegas_ordini.costo_gestione
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  return (float)$row[0];
   
}
//Valore ARR USER COSTO TRASPORTO :     Parametri   : Ordine, User
function valore_costo_trasporto_ordine_user($id_ordine,$id_user){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_personale_attuale_netto_qarr = valore_arrivato_netto_ordine_user($id_ordine,$id_user);
    $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
    return (float)$costo_trasporto;
}
//Valore ARR USER COSTO GESTIONE :      Parametri   : Ordine, User
function valore_costo_gestione_ordine_user($id_ordine,$id_user){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_personale_attuale_netto_qarr = valore_arrivato_netto_ordine_user($id_ordine,$id_user);
    $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;
    return (float)$costo_gestione;
}
//Valore ARR USER COSTO GAS :           Parametri   : Ordine, User
function valore_costo_mio_gas($id_ordine,$id_user){
    
    $id_gas = id_gas_user($id_user);
    $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
    $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
    $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;
    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    $costo_personale_mio_gas = ($costo_globale_mio_gas /100) * $percentuale_mio_ordine_gas;
    return (float)$costo_personale_mio_gas;
}
//Valore ARR USER MAGGIORAZIONE GAS :   Parametri   : Ordine, User
function valore_costo_maggiorazione_mio_gas($id_ordine,$id_user){
    
    $id_gas=id_gas_user($id_user);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
    $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;
    return (float)$valore_maggiorazione_mio_gas;
}
//Valore ARR USER COSTI TOTALI ORDINE : Parametri   : Ordine, User
function valore_costi_totali($id_ordine,$id_user){
    $costo_trasporto = valore_costo_trasporto_ordine_user($id_ordine,$id_user);
    $costo_gestione = valore_costo_gestione_ordine_user($id_ordine,$id_user);
    $costo_mio_gas = valore_costo_mio_gas($id_ordine,$id_user);
    $costo_maggiorazione = valore_costo_maggiorazione_mio_gas($id_ordine,$id_user);
    $costi_totali = $costo_trasporto +
                    $costo_gestione +
                    $costo_mio_gas +
                    $costo_maggiorazione;
   return (float)$costi_totali;                           
}
//Valore ARR USER TOTALI ORDINE :       Parametri   : Ordine, User
function valore_totale_lordo_mio_ordine($id_ordine,$id_user){
    return (float)valore_costi_totali($id_ordine,$id_user)+
                        valore_arrivato_netto_ordine_user($id_ordine,$id_user);
}



//Valore ARR GAS :                      Parametri   : Ordine, Gas
function valore_totale_mio_gas($id_ordine,$id_gas){

(int)$id_ordine;
(int)$id_gas;

global $db,$class_debug;
    
$sql="SELECT
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS somma_valore_ordine
FROM
retegas_ordini
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
WHERE
retegas_ordini.id_ordini =  '$id_ordine' AND
maaking_users.id_gas =  '$id_gas'
GROUP BY
retegas_ordini.id_ordini,
retegas_ordini.id_listini,
retegas_ordini.descrizione_ordini,
retegas_ordini.costo_trasporto,
retegas_ordini.costo_gestione
LIMIT 1";
$ret = $db->sql_query($sql);

$row = mysql_fetch_row($ret);
$class_debug->debug_msg[]="FUN : valore_totale_mio_gas id_ordine = $id_ordine, id_gas = $id_gas, Result : ".$row[0];
return $row[0];
   
}
//Valore ARR GAS COSTO TRASPORTO :      Parametri   : Ordine, Gas
function valore_costo_trasporto_ordine_gas($id_ordine,$id_gas){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
    $percentuale_mio_gas = ($valore_gas_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_gas;
    return (float)$costo_trasporto;
}
//Valore ARR GAS COSTO GESTIONE :       Parametri   : Ordine, Gas
function valore_costo_gestione_ordine_gas($id_ordine,$id_gas){
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
    $percentuale_mio_gas = ($valore_gas_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_gas;
    return (float)$costo_gestione;
}
//Valore ARR GAS COSTO GAS :            Parametri   : Ordine, Gas
function valore_assoluto_costo_mio_gas($id_ordine,$id_gas){
    //echo $ordine." - ".$gas;
    global $db;
    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze='$id_ordine' AND id_gas_referenze='$id_gas';";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    return (float)$row["maggiorazione_referenza"];         
}
//Valore ARR GAS MAGGIORAZIONE GAS :    Parametri   : Ordine, Gas
function valore_reale_maggiorazione_percentuale_gas($id_ordine,$id_gas){
    
    $magg =  valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    return (float)(valore_totale_mio_gas($id_ordine,$id_gas)/100)*$magg;
    
}
//Valore ARR GAS COSTI TOTALI ORDINE :  Parametri   : Ordine, Gas
function valore_costi_esterni_gas($id_ordine,$id_gas){

       $costo_trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$id_gas); 
       $costo_gestione = valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
       $costi = $costo_trasporto+
                $costo_gestione;
                
       return (float)$costi;         
}
function valore_costi_interni_gas($id_ordine,$id_gas){

       $costo_trasporto = valore_costo_trasporto_ordine_gas($id_ordine,$id_gas); 
       $costo_gestione = valore_costo_gestione_ordine_gas($id_ordine,$id_gas);
       $costo_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
       $magg_gas = valore_reale_maggiorazione_percentuale_gas($id_ordine,$id_gas);
       $costi = $costo_trasporto+
                $costo_gestione+
                $costo_gas+
                $magg_gas;
                
       return (float)$costi;         
}
//Valore ARR GAS TOTALI ORDINE :        Parametri   : Ordine, Gas
function valore_totale_lordo_gas_esterno($id_ordine,$id_gas){
         $ordine =  valore_totale_mio_gas($id_ordine,$id_gas);
         $costi =  valore_costi_esterni_gas($id_ordine,$id_gas);
         return (float)$ordine+$costi;
}
function valore_totale_lordo_gas_inetrno($id_ordine,$id_gas){
         $ordine =  valore_totale_mio_gas($id_ordine,$id_gas);
         $costi =  valore_costi_interni_gas($id_ordine,$id_gas);
         return (float)$ordine+$costi;
}

//Valore ARR ORDINE :                   Parametri   : Ordine
function valore_totale_ordine_qarr($idu){
global $db;
Global $class_debug;    


   
$sql="SELECT
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS somma_valore_ordine
FROM
retegas_ordini
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
retegas_ordini.id_ordini =  '$idu'
GROUP BY
retegas_ordini.id_ordini,
retegas_ordini.id_listini,
retegas_ordini.descrizione_ordini,
retegas_ordini.costo_trasporto,
retegas_ordini.costo_gestione
LIMIT 1";
$ret = mysql_query($sql);
  $row = mysql_fetch_row($ret);
  
  $class_debug->debug_msg[]= "FUN : valore_totale_ordine_qarr id_ordine=$idu result = ".$row[0]; 
  return $row[0];
   
}
//Valore ARR ORDINE COSTO TRASPORTO :   Parametri   : Ordine
//Valore ARR ORDINE COSTO GESTIONE :    Parametri   : Ordine
//Valore ARR ORDINE COSTO GAS :         Parametri   : Ordine
//Valore ARR ORDINE MAGGIORAZIONE GAS   Parametri   : Ordine
//Valore ARR ORDINE COSTI TOTALI ORDINE Parametri   : Ordine
//Valore ARR ORDINE TOTALI ORDINE :     Parametri   : Ordine
