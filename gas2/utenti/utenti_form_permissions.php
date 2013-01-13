<?php
if (eregi("utenti_form_permissions.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}


// GESTIONE DEI PERMESSI ----------------------------------------------------
if($user_permission & perm::puo_creare_ordini){$checked_1=" CHECKED ";}
if($user_permission & perm::puo_partecipare_ordini){$checked_2=" CHECKED ";}
if($user_permission & perm::puo_creare_gas){$hidden_3=perm::puo_creare_gas;}      
if($user_permission & perm::puo_creare_ditte){$checked_4=" CHECKED ";}
if($user_permission & perm::puo_creare_listini){$checked_5=" CHECKED ";}
if($user_permission & perm::puo_mod_perm_user_gas){$hidden_6=perm::puo_mod_perm_user_gas;}
if($user_permission & perm::puo_avere_amici){$checked_7=" CHECKED ";}
if($user_permission & perm::puo_postare_messaggi){$checked_8=" CHECKED ";}
if($user_permission & perm::puo_eliminare_messaggi){$hidden_9=perm::puo_eliminare_messaggi;}	  
if($user_permission & perm::puo_gestire_utenti){$hidden_10=perm::puo_gestire_utenti;}
if($user_permission & perm::puo_vedere_tutti_ordini){$hidden_11=perm::puo_vedere_tutti_ordini;}
if($user_permission & perm::puo_operare_con_crediti){$checked_12=" CHECKED ";}
if($user_permission & perm::puo_vedere_retegas){$hidden_13=perm::puo_vedere_retegas;}

$h_table .='
<div class="ui-widget-header ui-corner-all padding-6px">
<div style="margin-bottom:16px;">Permessi utente</div>
<form  name="Modifica permessi" method="POST" action="utenti_form.php">
<table>
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
		<th >Può avere amici (...)</th>
		<td><input '.$checked_7.'type="checkbox" name="p_a_a" value="'.perm::puo_avere_amici.'"</td>
	</tr>
		</tr>
		<tr class="odd">
		<th >Può scrivere in bacheca</th>
		<td><input '.$checked_8.'type="checkbox" name="p_s_b" value="'.perm::puo_postare_messaggi.'"</td>
	</tr>
    </tr>
        <tr class="odd">
        <th >Può operare con i crediti degli altri utenti</th>
        <td><input '.$checked_12.'type="checkbox" name="p_o_c" value="'.perm::puo_operare_con_crediti.'"</td>
    </tr>
<table>

<input type="hidden" name="p_c_g"  value="'.$hidden_3.'">
<input type="hidden" name="p_m_p"  value="'.$hidden_6.'">
<input type="hidden" name="p_e_m"  value="'.$hidden_9.'">
<input type="hidden" name="p_g_u"  value="'.$hidden_10.'">
<input type="hidden" name="p_v_t_o"  value="'.$hidden_11.'">
<input type="hidden" name="p_v_rg"  value="'.$hidden_13.'">
 
<input type="hidden" name="do"  value="change_user_permissions">
<input type="hidden" name="id_utente_permessi"  value="'.$c1.'">
<center>
<input class="large green awesome" style="margin:20px;" type="submit" value="Salva">
</center>
</form>
</div>
   
';
	  