<?php
if (eregi("utenti_form_permissions_zeus.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}


		// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_permission & perm::puo_creare_ordini){$checked_1=" CHECKED ";}
if($user_permission & perm::puo_partecipare_ordini){$checked_2=" CHECKED ";}	 
if($user_permission & perm::puo_creare_gas){$checked_3=" CHECKED ";}
if($user_permission & perm::puo_creare_ditte){$checked_4=" CHECKED ";}
if($user_permission & perm::puo_creare_listini){$checked_5=" CHECKED ";}
if($user_permission & perm::puo_mod_perm_user_gas){$checked_6=" CHECKED ";}
if($user_permission & perm::puo_avere_amici){$checked_7=" CHECKED ";} 
if($user_permission & perm::puo_postare_messaggi){$checked_8=" CHECKED ";}
if($user_permission & perm::puo_eliminare_messaggi){$checked_9=" CHECKED ";}
if($user_permission & perm::puo_gestire_utenti){$checked_10=" CHECKED ";}
if($user_permission & perm::puo_vedere_tutti_ordini){$checked_11=" CHECKED ";}
if($user_permission & perm::puo_operare_con_crediti){$checked_12=" CHECKED ";}

	  
$h_table .='
 <div class="ui-widget-header ui-corner-all padding-6px">
<div style="margin-bottom:16px;">Permessi utente</div>
<form  name="Modifica permessi avanzati" method="POST" action="utenti_form.php">
<table>

<tr>
		<th style="text-align:left;">PERMESSI SEMPLICI</th>
		<td>&nbsp</td>
	</tr>
		<tr class="odd">
		<th>Può creare ordini</th>
		<td><input '.$checked_1.'type="checkbox" name="p_c_o" value="'.perm::puo_creare_ordini.'"</td>
	</tr>
	<tr class="odd">
		<th >Può partecipare agli ordini</th>
		<td><input '.$checked_2.'type="checkbox" name="p_p_o" value="'.perm::puo_partecipare_ordini.'"</td>
	</tr>
		<tr class="odd">
		<th >Può creare nuove ditte</th>
		<td><input '.$checked_4.'type="checkbox" name="p_c_d" value="'.perm::puo_creare_ditte.'"</td>
	</tr>
	</tr>
		<tr class="odd">
		<th >Può creare nuovi listini</th>
		<td><input '.$checked_5.'type="checkbox" name="p_c_l" value="'.perm::puo_creare_listini.'"</td>
	</tr>
	</tr>
		<tr class="odd">
		<th >Può avere amici</th>
		<td><input '.$checked_7.'type="checkbox" name="p_a_a" value="'.perm::puo_avere_amici.'"</td>
	</tr>
	</tr>
	<tr class="odd">
		<th >Può scrivere in bacheca</th>
		<td><input '.$checked_8.'type="checkbox" name="p_s_b" value="'.perm::puo_postare_messaggi.'"</td>
	</tr>
	</tr>
		<tr>
		<th style="text-align:left;">PERMESSI AVANZATI</th>
		<td>&nbsp</td>
	</tr>
	<tr class="odd">
		<th >Può gestire il proprio GAS</th>
		<td><input '.$checked_3.'type="checkbox" name="p_c_g" value="'.perm::puo_creare_gas.'"</td>
	</tr>
		<tr class="odd">
		<th >Può modificare permessi utenti proprio GAS</th>
		<td><input '.$checked_6.'type="checkbox" name="p_m_p" value="'.perm::puo_mod_perm_user_gas.'"</td>
	</tr>
	</tr>
		<tr class="odd">
		<th >Può eliminare messaggi visibili dal proprio GAS</th>
		<td><input '.$checked_9.'type="checkbox" name="p_e_m" value="'.perm::puo_eliminare_messaggi.'"</td>
	</tr>
	</tr>
		<tr class="odd">
		<th >Può gestire utenti</th>
		<td><input '.$checked_10.'type="checkbox" name="i_n_u" value="'.perm::puo_gestire_utenti.'"</td>
	</tr>
    </tr>
        <tr class="odd">
        <th >Può vedere tutti gli ordini</th>
        <td><input '.$checked_11.'type="checkbox" name="p_v_t_o" value="'.perm::puo_vedere_tutti_ordini.'"</td>
    </tr>
    </tr>
        <tr class="odd">
        <th >Può operare con i crediti di altri utenti</th>
        <td><input '.$checked_12.'type="checkbox" name="p_o_c" value="'.perm::puo_operare_con_crediti.'"</td>
    </tr>
        </tr>
        <tr class="odd">
        <th >Può vedere ReteGAS</th>
        <td><input '.$checked_13.'type="checkbox" name="p_v_rg" value="'.perm::puo_vedere_retegas.'"</td>
    </tr>    
    
	
<table>
<input type="hidden" name="do"  value="change_user_permissions_zeus">
<input type="hidden" name="id_utente_permessi"  value="'.$c1.'">
<input class="large black awesome" style="margin:20px;text-align:right" type="submit" value="Salva">
</form>
</div>	
';
	  
	  
	  
	  //GESTIONE DEI PERMESSI ------------------------------------------------------

?>
