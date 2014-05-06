<?php

include_once("../rend.php");

if (is_logged_in($user)){
		$cookie_read = explode("|", base64_decode($user));
		$id_user = $cookie_read[0];
		$username =$cookie_read[1];
		$gas = id_gas_user($id_user);
		$gas_name = gas_nome($gas);
 
global $db;

// QUESTE RIGHE RENDONO LO SCRIPT COMPATIBILE CON LE VERSIONI
// DI PHP PRECEDENTI ALLA 4.1.0
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
if(!isset($_SERVER)) $_SERVER = $HTTP_SERVER_VARS;

/********************* VARIABILI DA SETTARE ********************/
// Directory dove salvare i files Uploadati ( chmod 777, percorso assoluto)
//$upload_dir = $_SERVER["DOCUMENT_ROOT"] . "/uploads";
 $upload_dir = "./uploads";
// Eventuale nuovo nome da dare al file uploadato

$new_name = time()+rand(0,100000).".csv";
$old_name_csv = $_FILES["upfile"]["name"];

// Se $new_name ? vuota, il nome sar? lo stesso del file uploadato
$file_name = ($new_name) ? $new_name : $_FILES["upfile"]["name"];
$filone = $upload_dir."/".$file_name; 
//include("../header.php");
			//header_menu();
			//header_menu_listini();



if(trim($_FILES["upfile"]["name"]) == "") {

die("Non hai indicato il file da uploadare !");

}

if(@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

@move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") 
or die("Impossibile spostare il file, controlla l'esistenza o i permessi della directory dove fare l'upload.");

} else {

die("Problemi nell'upload del file " . $_FILES["upfile"]["name"]);

}

//$h_table .= "L'upload del file " . $_FILES["upfile"]["name"] . " ? avvenuto correttamente";
$row = 0;
$fd = fopen ($upload_dir."/".$file_name, "r");
// ---Controllo errori-----------------------------------------------------------------------------------------------------
$erro_vuoti = 0;
$erro_num = 0;
$erro_doppi = 0;
$erro_zero=0;
$warn_string = 0;
$erro_multi =0;
$unici=0;
$array_articoli[0]="";  



//SALTA LA PRIMA RIGA
$data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR);

// initialize a loop to go through each line of the file
 while (($data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR)) !== FALSE) {
	$num = count($data);
	$row++;
		
		 
		for ($i=0; $i<=4; $i++){if(is_null($data[$i]) or ($data[$i]=="")){$erro_vuoti++;}} // Se i campi sono vuoti
		for ($i=6; $i<=7; $i++){if(is_null($data[$i]) or ($data[$i]=="")){$erro_vuoti++;}} // Se i campi sono vuoti saltano l'ingombro 
		
		
		
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[0])>0){$warn_string++;}  //codice
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[1])>0){$warn_string++;}   //descr
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[3])>0){$warn_string++;}  //u mis
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[5])>0){$warn_string++;}   // ingombro
		//if ($data[7]<1){$erro_zero++;}
		//if ($data[6]<1){$erro_zero++;}
		
		//if (!is_numeric($data[7])){$erro_num++;}   // Non sono numeri
		//if (!is_numeric($data[6])){$erro_num++;}
		if (!is_numeric(trim(str_replace(array(","),array("."),$data[7])))){$erro_num++;} 
		if (!is_numeric(trim(str_replace(array(","),array("."),$data[6])))){$erro_num++;}
		
		
		if (!is_multiplo((trim(str_replace(array(","),array("."),$data[7]))),(trim(str_replace(array(","),array("."),$data[6]))))){$erro_multi++;}  // Multiplo errato 
		if (!is_numeric(trim(str_replace(array(",","?"),array(".",""),$data[4])))){$erro_num++;} 
		if (!is_numeric(trim(str_replace(array(",","?"),array(".",""),$data[2])))){$erro_num++;}
		
		//$data[8]=sanitize($data[8]);
		
		if (trim($data[9])=="UNICO"){$unici++;}
		
		if (!$data[0]==""){
		$result = $db->sql_query("SELECT retegas_articoli.id_articoli
								FROM retegas_articoli
								WHERE (((retegas_articoli.codice)='$data[0]') AND ((retegas_articoli.id_listini)=$listino));");    
		
		if (empty($result)){
			$erro_vuoti++; 
		}else{
		
			if (mysql_numrows($result)>0){
			$doppi[]=$data[0];    
			$erro_doppi++;   
			}
		}//else empty result	
		}
		
		
		// COntrollo all'interno della stessa lista
		if (in_array($data[0],$array_articoli)){
			$doppi[]=$data[0];    
			$erro_doppi++;    
		}else{
			$array_articoli[$row]=$data[0];
		}
		
		 
 }
 fclose ($fd);
// ------------------------------------------------------------------------------------------------------------------------
		$fd = fopen ($upload_dir."/".$file_name, "r");
	   
		$h_table .= "<div class=\"ui-widget-content ui-corner-all padding_6px\">
		<div class=\"ui-widget-header ui-corner-all padding_6px\">
		Importazione da file CSV articoli listino
		</div><br>";
		if(($erro_vuoti+$erro_doppi+$error_msg+$erro_num)>0){
			$h_table .="Upload File ($old_name_csv) avvenuta correttamente, <b>MA...</b>";
		}else{
			$h_table .="Upload File ($old_name_csv) avvenuta correttamente !....  ";
		}
		
		
		//$h_table .="<td colspan=\"9\" align=\"center\">Importazione File ($old_name_csv) avvenuta correttamente</td></tr>";
		if ($erro_vuoti>0){
		$h_table .="<div class=\"campo_vuoto ui-corner-all\">Sono stati trovati n.$erro_vuoti campi vuoti</div>";
		}
		if ($erro_num>0){ 
		$h_table .="<div class=\"non_riconosciuto ui-corner-all\">n.$erro_num valori  non sono stati riconosciuti.</div>"; 
		}
		if ($erro_doppi>0){ 
		$h_table .="<div class=\"articoli_doppi ui-corner-all\">n.$erro_doppi articoli gia' presenti nel listino.</div>"; 
		}
		if ($erro_zero>0){ 
		$h_table .="<div class=\"qta_inf_1 ui-corner-all\">n.$erro_zero Quantita' inferiori a 1 (il minimo ? 1))</div>"; 
		}
		if ($erro_multi>0){ 
		$h_table .="<div class=\"multiplo_errato ui-corner-all\">n.$erro_multi articoli con Q. minima NON multipla di Q scatola.</div>"; 
		}
		if ($warn_string>0){ 
		$h_table .="<div class=\"punteggiatura ui-corner-all\">n.$warn_string campi con punteggiatura che verra' omessa. (Es: ' o \")</div>"; 
		}
		if ($unici>0){ 
		$h_table .="<div class=\"punteggiatura ui-corner-all\">n.$unici articoli UNIVOCI</div>"; 
		}
		
		if ($erro_num + $erro_vuoti + $erro_doppi + $erro_zero ==0){
		$h_table .="Sembra tutto OK;				
							 <a class =\"large awesome green\" href=\"listini_form.php?do=upload&fname=$filone&listino=$listino&tipo_file=CSV\">Carica questi $row Articoli nel listino $listino</a>
		 <br />"; 
		}else{
			
		$h_table .="<div class=\"ui-state-error ui-corner-all padding_6px\"><b>Correggere il vostro file CSV e rifare l'upload.
		</div>
		</br> ";     
		}
		$row = 0;
		$h_table .="</div>";
		$h_table .= "
		<table>
		<tr>
		<td width=\"2%\"> <b>Riga</td>
		<td width=\"7%\"> <b>Cod. Art. Fornitore</td>
		<td> <b>Descrizione</b> </td>
		<td width=\"7%\"> <b>Prezzo  </td>
		<td width=\"5%\"> <b>U.Mis.</td>
		<td width=\"5%\"> <b>Misura</td>
		<td width=\"5%\"> <b>Ingombro</td>
		<td width=\"5%\"> <b>Qta' scatola</td>
		<td width=\"5%\"> <b>Qta' minima</td>
		<td width=\"5%\"> <b>Note</td>
		<td width=\"5%\"> <b>Univoco</td>
		 </tr>";

$erro = 0;
// initialize a loop to go through each line of the file

//SALTA LA PRIMA RIGA
$data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR);
$htable .="<tr>";
$h_table .="<th>&nbsp</th>";		
foreach($data as $value) {
		$h_table .="<th>($value)</th>";
		}
$htable .="</tr>";

 while (($data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR)) !== FALSE) {
	$num = count($data);
	$row++;
			if(is_integer($row/2)){
			$h_table .= "<tr class=\"odd\">";    // Colore Riga
			}else{
			$h_table .= "<tr>";    
			}
			
			
	$h_table .="<td width=\"2%\">$row</td>";       // numero riga
			
		for ($i=0; $i<=7; $i++)
		{ 
			 if(is_null($data[$i]) or ($data[$i]=="")){                  // E' nullo il valore              
			 $bg[$i] = " class=\"campo_vuoto\" ";
			}else{
			 $bg[$i] = "";     
			}
		}
		
		
		
		if ($erro_doppi>0){if (in_array($data[0],$doppi)){$bg[0] = "class=\"articoli_doppi\"";}}// e' uno dei doppi
		
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[0])>0){$bg[0] = "class=\"punteggiatura\"";}// Cod art. COntiene caratteri insulsi
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[1])>0){$bg[1] = "class=\"punteggiatura\"";}// Descriz COntiene caratteri insulsi  
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[3])>0){$bg[3] = "class=\"punteggiatura\"";}// U mis caratteri insulsi  
		//if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[5])>0){$bg[5] = "class=\"punteggiatura\"";}// Ingombro caratteri insulsi  
		
		if (!is_numeric(trim(str_replace(array(","),array("."),$data[7])))){$bg[7] = "class=\"non_riconosciuto\"";} 
		if (!is_numeric(trim(str_replace(array(","),array("."),$data[6])))){$bg[6] = "class=\"non_riconosciuto\"";}
		
		$data[8]=sanitize($data[8]);
		
		if (trim($data[9])=="UNICO"){$bg[9] = "class=\"non_riconosciuto\"";}else{$bg[9] = "";}
		
		// controllo inferiore a 1 !!!
		
		if (!is_multiplo((trim(str_replace(array(","),array("."),$data[7]))),(trim(str_replace(array(","),array("."),$data[6]))))){$bg[7] = "class=\"multiplo_errato\"";} // Non ? un multiplo 
		
		if (!is_numeric(trim(str_replace(array(",","?","'"),array(".","",""),$data[4])))){$bg[4] = "bgcolor=\"#FF4477\"";} 
		if (!is_numeric(trim(str_replace(array(",","?","'"),array(".","",""),$data[2])))){$bg[2] = "bgcolor=\"#FF4477\"";}   
		
			$h_table .="<td width=\"7%\"><div $bg[0]>$data[0]</div></td>";// Codice Articolo
			$h_table .="<td width=\"15%\"><div $bg[1]>$data[1]<div></td>";    
			$h_table .="<td><div $bg[2]>$data[2]<div></td>
				 <td width=\"2%\"><div $bg[3]>$data[3]<div></td>
				 <td width=\"5%\"><div $bg[4]>$data[4]<div></td>
				 <td width=\"5%\">$data[5]</td>
				 <td width=\"5%\"><div $bg[6]>$data[6]<div></td>
				 <td width=\"5%\"><div $bg[7]>$data[7]<div></td>
				 <td width=\"5%\"><div $bg[8]>$data[8]<div></td>
				 <td width=\"5%\"><div $bg[9]>$data[9]<div></td>";
			$h_table .="</tr>";

}
$h_table .="</table>";

fclose ($fd);
if ($erro_num + $erro_vuoti + $erro_doppi+ $erro_zero  > 0){          // se c'era qualche errore cancello il file'
 if(is_file("$filone")) {
unlink("$filone");
}   
}

//echo $h_table;

// MENU APERTO
$menu_aperto=1;

include ("listini_main.php");


}else{
	c1_go_away("?q=no_permission");
}   






?>