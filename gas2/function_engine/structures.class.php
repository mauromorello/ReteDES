<?php


//--------------------------PERMESSI
class gas_perm{
        const puo_partecipare_ordini_esterni=1;
        const puo_proporre_ordini_esterni   =2;
        const possiede_la_cassa             =4;
        const consente_ordini_superprivati  =8;
}
class perm{
		const puo_creare_ordini         =1;     //0
		const puo_partecipare_ordini    =2;     //1
		const puo_creare_gas            =4;     //2     SUPER USER
		const puo_creare_ditte          =8;     //3
		const puo_creare_listini        =16;    //4
		const puo_mod_perm_user_gas     =32;    //5     SUPER USER
		const puo_vedere_messaggi       =64;    //6     SUPER USER
		const puo_vedere_users_attesa   =128;   //7     SUPER USER
		const puo_avere_amici           =256;   //8
		const puo_postare_messaggi      =512;   //9
		const puo_eliminare_messaggi    =1024;  //10    SUPER USER
		const puo_gestire_utenti        =2048;  //11    SUPER USER
        const puo_vedere_tutti_ordini   =4096;  //12    SUPER USER
        const puo_gestire_la_cassa      =8192;  //13
        const puo_operare_con_crediti   =16384; //14
        const puo_vedere_retegas        =32768; //15    SUPER USER
        const puo_gestire_retegas       =65536; //16    ZEUS USER
	}
//----------------------------------

//---------------------------OPZIONI
class opti{
		const visibile_al_proprio_gas       =1;
		const visibile_a_tutti              =2;
		const aggiornami_nuovi_ordini       =4;
		const avvisami_scadenza_3gg         =8;
		const acconsento_comunica_tutti     =16;
		const comunicazioni_manu_ordini     =32;
        const stampe_senza_intestazioni     =64;
        const sito_senza_header             =128;
}
//----------------------------------

//---------------------MENU_LATERALI
class menu_lat{
        const user= 0;
        const gas = 1;
        const ordini = 2;
        const anagrafiche = 3;
        const des = 4;
        const aiuto = 5;
        const bacheca = 6;
        const coseinutili = 7;
}
//----------------------------------

//---------------------MENU_LATERALI
class dettaglio{
        const rettifica = "@@ RETTIFICA";
        const trasporto = "@@ TRASPORTO";
        const gestione = "@@ GESTIONE";
}
//----------------------------------


//---------------------------CASSA
//Movimenti CASSA --> TESTO
$__movcas = array(  "0"=>"Non definito",
                    "1"=>"Ricarica",
                    "2"=>"Pagamento",
                    "3"=>"Rettifica",
                    "4"=>"Scarico",
                    "5"=>"Finanziamento GAS",
                    "6"=>"Restituzione Credito",
                    "7"=>"Scarico (Merce)",
                    "8"=>"Scarico (Trasporto)",
                    "9"=>"Scarico (Gestione)",
                    "10"=>"Scarico (Finanziamento GAS)",
                    "11"=>"Scarico (Anticipo per copertura)",
                    "12"=>"Scarico (Costi GAS)",
                    "13"=>"Scarico (Maggiorazione GAS)");
//Movimenti CASSA --> VALORI
class movimento{
    Const non_definito = 0 ;
    Const carico_credito = 1 ;
    Const pagamento_ordine = 2 ;
    Const rettifica = 3 ;
    Const scarico_credito = 4 ;
    Const finanziamento_gas = 5 ;
    Const restituzione_credito = 6 ;
    Const scarico_per_pagamento_netto = 7 ;
    const scarico_per_pagamento_trasporto = 8;
    const scarico_per_pagamento_gestione = 9;
    const scarico_per_finanziamento_gas = 10 ;
    const scarico_per_anticipo_copertura = 11;
    const scarico_per_costi_gas = 12 ;
    const scarico_per_maggiorazione_gas = 13 ;

}
//--------------------------------


//---------------------------BACHECA
class ruoli{
    const partecipante = "PARTECIPANTE";
    const referente ="REFERENTE";
    const certificante = "CERTIFICANTE";
    const relazionante = "RELAZIONANTE";
}
class argomenti{
    Const indefinito = 0 ;
    Const riunione = 3;
    Const evento = 4;
    Const comunicato = 5;
    Const opinione = 6;
    const sito = 7;
    const DES = 8;
    const commento = 9;
    const certificazione = 10;
    const relazione = 11;
    const valutazione = 12;
}
class opinioni{

        //PARTECIPANTE
        const qualita       =   "OPI_QUALITA";
        const affare        =   "OPI_AFFARE";

        //DES
        const sociale       =   "OPI_SOCIALE";
        const finanza       =   "OPI_FINANZA";
        const ambiente      =   "OPI_AMBIENTE";

        //GESTORE
        const logistica     =   "OPI_LOGISTICA";
        const rapporti      =   "OPI_RAPPORTI";
        const tutte         =   "OPI_%"; //TODO da verificare
        const velocita      =   "OPI_VELOCITA";

        //GAS
        const pulizia       =   "OPI_PULIZIA";
        const artigianalita =   "OPI_ARTIGIANALITA";
        const disponibilita =   "OPI_DISPONIBILITA";
   }
//----------------------------------

//Tipologie Messaggi
class argo {

public $argomenti;

public function __construct() {

	  $this->argomenti = array("0" => "Indefinito",
							   "1" => "Appunti",
							   "3" => "Riunione",
							   "4" => "Evento",
							   "5" => "Comunicato",
							   "6" => "Opinione",
							   "7" => "Sito",
                               "8" => "DES",
                               "9" => "Commento",
                               argomenti::certificazione => "Certificazione",
                               argomenti::relazione => "Relazione",
                               argomenti::valutazione => "Valutazione");
   }

}

//Tipologie visibilità
class visi {

public $visibility;

public function __construct() {

	  $this->visibility = array("0" => "Tutto il mondo",
								 "1" => "Tutti gli utenti di "._SITE_NAME,
								 "2" => "dagli utenti del mio GAS soltanto",
								 "3" => "Me medesimo soltanto");
   }

}

//MAPPATURA INDIRIZZI PAGINE ---------------------------------- IMPORTANTE
class addresses{

public $addr;

public function __construct($site,$local,$root = null,$image=null) {

	  global $db;
	  // controllo che $root è un drive locale piuttosto che un http
	  if (substr($root,1,1)<>":"){
		$root = $site;
        if(!is_empty($image)){
            $iroot = $image;
        }else{
            $iroot = $site;
        }
	  }else{
		$root = $local;
        $iroot = $local;
	  }
	  // Posizione Pagine

      //echo $site;die();


	  $this->addr = array("sommario"                =>$root."index.php",

                          "start"                   =>$root."index_start.php",
                          "disclaimer"              =>$root."amministra/amministra_disclaimer.php",
						  "pannello_report"         =>$root."ordini/ordini_panel_select_report.php",
						  "backup_temp"             =>$root."backup/temp/",
                          "coseinutili"             =>$root."coseinutili/ci_presentazione.php",


                          //ARTICOLI
                          "articoli_form"           =>$root."articoli/articoli_form_new.php",
                          "articoli_edit"           =>$root."articoli/articoli_form_edit.php",
                          "articoli_delete"         =>$root."articoli/articoli_form_delete.php",

						  //PANORAMICA
						  "panoramica"              =>$root."panoramica/panoramica_mese_form.php",
						  "panoramica_anno"         =>$root."panoramica/panoramica_anno_form.php",
						  "panoramica_anno_des"     =>$root."panoramica/panoramica_anno_des.php",
						  //BACHECA
						  "bacheca"                 =>$root."bacheca/bacheca_table.php",
                          "bacheca_form"            =>$root."bacheca/bacheca_form.php",





						  //REPORT
						  "rep_situazione_gas"      =>$root."ordini_chiusi/altri_gas/ag_table.php",
						  "rep_dettaglio_articoli_c"=>$root."ordini_chiusi/listone_art/oc_lis_art_report.php",
						  "rep_riepilogo_articoli_c"=>$root."ordini_chiusi/articoli_ordinati/oc_art_ord_report.php",
						  "rep_dettaglio_articoli_a"=>$root."ordini_aperti/articoli_dettaglio/oa_art_dett_report.php",
						  "rep_riepilogo_articoli_a"=>$root."ordini_aperti/articoli_ordinati/oa_art_ord_report.php",

						  "rep_avanzo_ammanco"      =>$root."ordini_chiusi/articoli_ordinati_av_amm/oc_art_ord_av_amm_report.php",
						  "rep_scatole_intere"      =>$root."ordini_chiusi/articoli_ordinati_scatole_intere/oc_art_ord_si_report.php",

						  "rep_dettaglio_gas_c"     =>$root."ordini_chiusi/gas_dett/oc_g_dett_report.php",
						  "rep_articoli_gas_c"      =>$root."ordini_chiusi/gas_art/oc_g_art_report.php",
						  "rep_dett_art_m_sp_a"     =>$root."ordini_aperti/report/ordini_aperti_report_mia_spesa_articoli.php",
						  "rep_dett_art_m_sp_c"     =>$root."ordini_chiusi/mia_spesa/ordini_chiusi_report_mia_spesa_articoli.php",
						  "rep_riep_art_m_sp_a"     =>$root."ordini_aperti/articoli_ordinati/oa_art_ord_report.php",
						  "rep_riep_art_m_sp_c"     =>$root."ordini_chiusi/miei_art/oc_m_art_report.php",

						  "select_listino_maga"     =>$root."listini/listini_form_maga.php",

                          //grafici

                          "grafico_global_mon"      =>$root."gas/gasap_money_global.php",

						  //GESTIONE ORDINI
						   "pannello_gestisci"      =>$root."ordini/edit/ordini_panel_gestisci.php",



                          //ALTRO
						  "wiki"                    =>"http://wiki.retegas.info",
	                      "lang_ita"                =>$root."lang/italian.php",
                          "lang_val"                =>$root."lang/valsesian.php"



						  );
    include_once("locations_gas.php");
    $this->addr = $this->addr + $locations_gas;

    include_once("locations_storici.php");
    $this->addr = $this->addr + $locations_storici;

    include_once("locations_bacheca.php");
    $this->addr = $this->addr + $locations_bacheca;

    include_once("locations_des.php");
    $this->addr = $this->addr + $locations_des;

    include_once("locations_utenti.php");
    $this->addr = $this->addr + $locations_utenti;

    include_once("locations_amici.php");
    $this->addr = $this->addr + $locations_amici;

    include_once("locations_cassa.php");
    $this->addr = $this->addr + $locations_cassa;

    include_once("locations_images.php");
    $this->addr = $this->addr + $locations_images;

    include_once("locations_listini.php");
    $this->addr = $this->addr + $locations_listini;

    include_once("locations_amministra.php");
    $this->addr = $this->addr + $locations_amministra;

    include_once("locations_css.php");
    $this->addr = $this->addr + $locations_css;

    include_once("locations_mobile.php");
    $this->addr = $this->addr + $locations_mobile;

    include_once("locations_js.php");
    $this->addr = $this->addr + $locations_js;

    include_once("locations_themes.php");
    $this->addr = $this->addr + $locations_themes;

    include_once("locations_ajax.php");
    $this->addr = $this->addr + $locations_ajax;

    include_once("locations_ditte.php");
    $this->addr = $this->addr + $locations_ditte;

    include_once("locations_reports.php");
    $this->addr = $this->addr + $locations_reports;

    include_once("locations_coseinutili.php");
    $this->addr = $this->addr + $locations_coseinutili;

    include_once("locations_aiutanti.php");
    $this->addr = $this->addr + $locations_aiutanti;

    include_once("locations_ordini.php");
    $this->addr = $this->addr + $locations_ordini;

    include_once("locations_extra.php");
    $this->addr = $this->addr + $locations_extra;
   }


}
