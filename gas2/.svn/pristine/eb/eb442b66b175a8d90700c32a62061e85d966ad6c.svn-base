<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;     
}    

if(!(_USER_PERMISSIONS & perm::puo_creare_gas)){
	go("sommario",_USER_ID,"Non hai i permessi per modificare i dati dei GAS");

}	

if($do=="mod"){

// CONTROLLO I VUOTI
if(empty($descrizione_gas) | $descrizione_gas==""){
		$msg = "Descrizione GAS obbligatoria<br>";
		$empty++;
}
if(empty($mail_gas) | $mail_gas==""){
		$msg .= "Mail Gas obbligatoria<br>";
		$empty++;
}
if(empty($sede_gas) | $sede_gas==""){
		$msg .= "Sede Gas obbligatoria<br>";
		$empty++;
}

// SANITIZZO
$descrizione_gas = sanitize($descrizione_gas);
$sede_gas = sanitize($sede_gas);
$nome_gas = sanitize($nome_gas);
$website_gas = sanitize($website_gas);
$comunicazione_referenti = sanitize($comunicazione_referenti);

//CONTROLLO IL NUMERO DELLA PERCENTUALE
if(empty($maggiorazione_ordini) | $maggiorazione_ordini==""){
	$maggiorazione_ordini=0;
}

if(!is_numeric($maggiorazione_ordini)){
	$logical++;
	$msg .= "Devi inserire un numero, senza il simbolo '%' <br>";
}
			   
//CONTROLLO MAIL SE E' VALIDA

//TUTTO OK  ?

if(($logical+$empty==0)){

$query = "UPDATE retegas_gas 
			  SET 
			  retegas_gas.descrizione_gas = '$descrizione_gas',
			  retegas_gas.sede_gas = '$sede_gas',
              retegas_gas.nome_gas = '$nome_gas',
			  retegas_gas.website_gas = '$website_gas',
			  retegas_gas.mail_gas = '$mail_gas',
			  retegas_gas.comunicazione_referenti = '$comunicazione_referenti',
			  retegas_gas.maggiorazione_ordini = '$maggiorazione_ordini' 
			  WHERE 
			  retegas_gas.id_gas = '"._USER_ID_GAS."' LIMIT 1;";
$result = $db->sql_query($query);


        $res_geocode = geocode_gas_table("SELECT * FROM retegas_gas WHERE (id_gas='"._USER_ID_GAS."')");
        //$res_geocode = geocode_gas_table("SELECT * FROM retegas_gas");
        log_me(0,$id_user,"GAS","MOD","Modificati dati gas",null,$res_geocode."<br>".$sql);


$msg .= "Modifiche effettuate";
go("gas_form",_USER_ID,$msg);

//QUERO     
}else{

	
$msg .= "Verifica i dati immessi e riprova<br>";
	
}





	
	
}
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Modifica dati mio GAS";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = gas_menu_completo($user);
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","ckeditor");   
    
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
		  
	   
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
	//  $retegas->java_scripts_header[]=java_tablesorter("gas_table");
	  $retegas->java_scripts_header[]=java_superfish();
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

		  // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }else{
        $retegas->messaggio .= read_option_text($id_user,"MSG");
        delete_option_text($id_user,"MSG");
      }
	  
        if(!isset($do)){
	  // qui ci va la pagina vera e proria  
	    $query = "SELECT * FROM retegas_gas WHERE retegas_gas.id_gas='"._USER_ID_GAS."' LIMIT 1;";
        $res = $db->sql_query($query);
        $row = $db->sql_fetchrow($res);
        
        $descrizione_gas = $row["descrizione_gas"];
        $sede_gas= $row["sede_gas"];
        $nome_gas = $row["nome_gas"];
        $website_gas= $row["website_gas"];
        $mail_gas= $row["mail_gas"];
        
        $comunicazione_referenti= $row["comunicazione_referenti"];
        $maggiorazione_ordini= $row["maggiorazione_ordini"];
        }
        

        $help_descrizione_gas='Il nome del tuo GAS.';
        $help_indirizzo_gas='Indirizzo della ditta, mettere almeno la città'; 
        $help_website ='Se la sita ha un indirizzo internet inserirlo qua';
        $help_note_ditte ='Si possono mettere immagini facendo il copia e incolla dal sito della ditta in questione. Le immagini saranno collegate, non incorporate.';
        $help_mail_ditte='Mail della ditta, se si lascia vuoto allora sarà inserita la mail del proponente';
        $help_tag_ditte = 'I tag sono delle parole che si possono liberamente associare alla ditta stessa, separate da una virgola, 
        che permettono di filtrarla più agevolmente in mezzo alle altre e quindi di ritrovarla subito.<br>Ad esempio, i tag di una ditta che vende miele possono essere : miele, api, arnie, vasetti, acacia, castagno, biologico, artigianale ';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Modifica i dati del tuo GAS</h3>

        <form class="retegas_form" name="modifica_gas" method="POST" action="">

        
        <div>
        <h4>1</h4>
        <label for="descrizione_gas">Nome del GAS...</label>
        <input type="text" name="descrizione_gas" value="'.$descrizione_gas.'" size="50"></input>
        <h5 title="'.$help_descrizione_gas.'">Inf.</h5>
        </div>
        
        <div>
        <h4>2</h4>
        <h5 title="'.$help_nome_gas.'">Inf.</h5>
        <label for="nome_gas">Ragione Sociale, può comparire nelle intestazioni dei documenti.</label>
        <textarea id="nome_gas" class ="ckeditor" name="nome_gas" cols="28" style="display:inline-block;">'.$nome_gas.'</textarea>
        </div>        
        
        
        <div>
        <h4>3</h4>
        <label for="sede_gas">...indica il suo indirizzo e la sua città...</label>
        <input type="text" name="sede_gas" value="'.$sede_gas.'" size="50"></input>
        <h5 title="'.$help_sede_gas.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="website_gas">...scrivi l\'indirizzo internet del suo sito (se ne ha uno)...</label>
        <input type="text" name="website_gas" value="'.$website_gas.'" size="50"></input>
        <h5 title="'.$help_website_gas.'">Inf.</h5>
        </div>
        
        <div>
        <h4>5</h4>
        <label for="mail_gas">...ma soprattutto la sua mail...</label>
        <input type="text" name="mail_gas" value="'.$mail_gas.'" size="50"></input>
        <h5 title="'.$help_mail_gas.'">Inf.</h5>
        </div>

        
        <div>
        <h4>6</h4>
        <label for="comunicazione_referenti">Qua scrivi una breve comunicazione che comparirà in ogni ordine per descrivere la maggiorazione.</label>
        <input type="text" name="comunicazione_referenti" value="'.$comunicazione_referenti.'" size="50"></input>
        <h5 title="'.$help_comunicazione_referenti.'">Inf.</h5>
        </div>
        
        <div>
        <h4>7</h4>
        <label for="maggiorazione_ordini">..e qua scrivi il valore di percentuale sul valore degli ordini destinata al tuo gas.</label>
        <input type="text" name="maggiorazione_ordini" value="'.$maggiorazione_ordini.'" size="10"></input>
        <h5 title="'.$help_maggiorazione_ordini.'">Inf.</h5>
        </div>        
                        
        <div>
        <h4>8</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche !" align="center" >
        <input type="hidden" name="do" value="mod">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>'; 
      
      
      
	  $retegas->content  =  $h;

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>
