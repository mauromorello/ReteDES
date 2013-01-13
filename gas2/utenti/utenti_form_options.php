<?php
if (eregi("utenti_form_options.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

//echo "USER OPT = $user_options";
$opz = leggi_opzioni_sito_utente($id_user);

if($user_options<>$opz){$msg="ATTENZIONE : la nuova configurazione di opzioni si attiverà dal prossimo LOGIN";} 
//$user_options=$opz;

// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_options & opti::visibile_al_proprio_gas){$checked_1=" CHECKED ";}
if($user_options & opti::visibile_a_tutti){$checked_2=" CHECKED ";}
if($user_options & opti::aggiornami_nuovi_ordini){$checked_3=" CHECKED ";}
if($user_options & opti::avvisami_scadenza_3gg){$checked_4=" CHECKED ";}
if($user_options & opti::acconsento_comunica_tutti){$checked_5=" CHECKED ";}
if($user_options & opti::stampe_senza_intestazioni){$checked_6=" CHECKED ";} 
if($user_options & opti::sito_senza_header){$checked_7=" CHECKED ";} 

$h_table .='
<div class="ui-widget-header ui-corner-all padding-6px">
<div style="margin-bottom:16px;">Opzioni sito</div>
<form  name="Modifica permessi" method="POST" action="utenti_form_mia.php">
<table>
	<tr class="odd">
		<th>Divento visibile quando sono online (solo mio GAS)</th>
		<td><input '.$checked_1.'type="checkbox" name="v_m_g" value="'.opti::visibile_al_proprio_gas.'"</td>
	</tr>
	<tr class="odd">
		<th >Divento visibile quando sono online (tutta la RETEGAS.AP)</th>
		<td><input '.$checked_2.'type="checkbox" name="v_t_r" value="'.opti::visibile_a_tutti.'"</td>
	</tr>
	<tr class="odd">
		<th>Aggiornami per mail sui nuovi ordini</th>
		<td><input '.$checked_3.'type="checkbox" name="a_n_o" value="'.opti::aggiornami_nuovi_ordini.'"</td>
	</tr>
	<tr class="odd">
		<th >Avvisami 3 giorni prima che si chiuda un ordine al quale io posso partecipare o sto già partecipando</th>
		<td><input '.$checked_4.'type="checkbox" name="a_s_3" value="'.opti::avvisami_scadenza_3gg.'"</td>
	</tr>
	<tr class="odd">
		<th>Acconsento ad essere incluso nel : "comunica a tutti"</th>
		<td><input '.$checked_5.'type="checkbox" name="a_c_t" value="'.opti::acconsento_comunica_tutti.'"</td>
	</tr>
    <tr class="odd">
        <th>Stampe senza intestazioni</th>
        <td><input '.$checked_6.'type="checkbox" name="s_s_i" value="'.opti::stampe_senza_intestazioni.'"</td>
    </tr>
    <tr class="odd">
        <th>Pagine sito senza intestazione (più spazio per i dati)</th>
        <td><input '.$checked_7.'type="checkbox" name="s_s_h" value="'.opti::sito_senza_header.'"</td>
    </tr>    
<table>

 
<input type="hidden" name="do"  value="change_user_options">
<input type="hidden" name="id_utente_opzioni"  value="'.$c1.'">
<center>
<input class="large green awesome" style="margin:20px;" type="submit" value="Salva le modifiche">
</center>
</form>
</div>
   
';
	  
	  
	  
	  //GESTIONE DEI PERMESSI ------------------------------------------------------

?>