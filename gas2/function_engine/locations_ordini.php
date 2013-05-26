<?php

if(isset($root) AND isset($iroot)){
$locations_ordini=Array(  "ordini_aperti"           =>$root."ordini/aperti/ordini_table_aperti.php",
                          "ordini_aperti_new"       =>$root."ordini/aperti/ordini_table_aperti.php", 
                          "prenota_attiva"          =>$root."ordini/prenota/prenota_attiva.php",
                          "prenota_conferma"        =>$root."ordini/prenota/prenota_conferma.php",
                          "ordini_ap_mod_q"         =>$root."ordini_aperti/ordini_aperti_mod_q.php",
                          "oa_export_printable"     =>$root."ordini/aperti/ordini_table_aperti_printable.php",
                          "oa_export_pdf"           =>$root."ordini/aperti/ordini_table_aperti_pdf.php",
                          "oa_export_excel"         =>$root."ordini/aperti/ordini_table_aperti_excel.php",
                          "oa_export_word"          =>$root."ordini/aperti/ordini_table_aperti_word.php",
                          "oa_export_csv"           =>$root."ordini/aperti/ordini_table_aperti_csv.php",
                          "oa_dett_ass"             =>$root."ordini_aperti/report/ordini_aperti_report_mia_spesa_articoli.php",
                          "oc_dett_ass"             =>$root."ordini_chiusi/mia_spesa/ordini_chiusi_report_mia_spesa_articoli.php",
                          "ordini_report_panel"     =>$root."ordini/reporting/report_panel.php",
                          "ordini_report_riep_all_gas"     =>$root."ordini/reporting/report_riepilogo_tutti_gas.php",                          
                          "partecipat_utenti"       =>$root."ordini/partecipanti/ordini_partecipanti.php",
                          "partecipat_cronologia"   =>$root."ordini/partecipanti/ordini_cronologia.php",
                          "partecipat_gastable"     =>$root."ordini/partecipanti/ordini_tabella_gas_partecipanti.php",
                          "nuovo_ordine_simple"     =>$root."ordini/add/ordini_form_add_simple_2.php",
                          "nuovo_ordine_completo"   =>$root."ordini/add/ordini_form_add_complete_2.php",
                          "ordini_gestione_riepgas"  =>$root."ordini/gestore/riepilogo_totali_gas.php",
                          "ordini_mia_spesa_riepilogo"=>$root."ordini/mia_spesa/ordini_mia_spesa_riepilogo.php",
                          "ordini_mia_spesa_dettaglio"=>$root."ordini/mia_spesa/ordini_mia_spesa_dettaglio.php",
                          "ordini_mia_spesa_riepami"  =>$root."ordini/mia_spesa/ordini_mia_spesa_riepilogo_amici.php",
                          "ordini_del_all_art"        =>$root."ordini/edit/ordini_delete_all.php",
                          "ordini_mod_ass_new"        =>$root."ordini/modifica_qta/ordini_modifica_assegnazione.php",
                          "ordini_mod_uni_new"        =>$root."ordini/modifica_qta/ordini_modifica_univoci.php",
                          "ordini_mod_uni_new_form"   =>$root."ordini/modifica_qta/ordini_modifica_univoci_form.php",
                          "ordine_partecipa"        =>$root."ordini/partecipa/ordini_partecipa.php",           
                          "ordine_partecipa_massivo"=>$root."ordini/partecipa/ordini_partecipa_massivo.php",           
                          "ordine_partecipa_out_csv"=>$root."ordini/partecipa/output_csv_massivo.php",           
                          "ordini_comunica"         =>$root."ordini/comunica/ordini_comunica.php", 
                          "comunica_alcuni_articoli"=>$root."ordini/comunica/comunica_alcuni_articoli.php",
                          "ordine_diventa_referente"=>$root."ordini/diventa_referente/ordini_diventa_referente.php", 
                          "ordini_form"             =>$root."ordini/scheda/ordini_form_main.php",
                          "ordini_form_new"         =>$root."ordini/scheda/ordini_form_main.php",
                          "ordini_form_contabilita" =>$root."ordini/contabilita/contabilita.php",
                          "ordini_listino_compilabile"=>$root."ordini/scheda/compilabile.php",
                          "ordini_aiutanti_table"   =>$root."ordini/aiutanti/aiutanti_table.php",
                          "ordini_aperti_form_old"  =>$root."ordini/scheda/ordini_form_main.php",
                          "ordini_chiusi"           =>$root."ordini/chiusi/ordini_chiusi.php",
                          "ordini_chiusi_form"      =>$root."ordini/scheda/ordini_form_main.php",
                          //EDIT ORDINI
                          "edit_spese_gas"          =>$root."ordini/edit/ordini_form_edit_gas_spese.php",
                          "edit_scadenze"           =>$root."ordini/edit/ordini_form_edit_date.php",
                          "edit_costi"              =>$root."ordini/edit/ordini_form_edit_costi.php",
                          "edit_descrizione"        =>$root."ordini/edit/ordini_form_edit_descrizione.php",
                          "edit_partecipazione"     =>$root."ordini/edit/ordini_form_edit_partecipazione.php",
                          "edit_switch_listino"     =>$root."ordini/edit/ordini_form_edit_switch_listino.php",
                          "edit_switch_gestore"     =>$root."ordini/edit/ordini_form_edit_switch_gestore.php",
                          "confirm_switch_gestore"  =>$root."ordini/edit/ordini_confirm_switch_gestore.php",
                          "convalida_ordine"        =>$root."ordini/edit/ordini_allow_print.php",
                          //GRAFICI
                          "grafico_1"               =>$root."ordini/grafici/g3.php",
                          
                          //OPINIONI
                          "opinione_partecipante"   =>$root."ordini/opinioni/opinione_partecipante.php",
                          "opinione_referente"      =>$root."ordini/opinioni/opinione_referente.php",
                          
                          //RETTIFICHE
                          "rettifica_totali"                    =>$root."ordini/rettifiche/rettifica_quantita_totali.php",
                          "rettifica_quantita_art_user"         =>$root."ordini/rettifiche/rettifica_quantita_art_user.php",
                          "rettifica_quantita_csv"              =>$root."ordini/rettifiche/rettifica_quantita_csv.php",
                          "rettifica_denaro_art_user"           =>$root."ordini/rettifiche/rettifica_denaro_art_user.php",
                          "output_csv_rettifica_qta"           =>$root."ordini/rettifiche/output_csv_rettifica_qta.php",
                          "rettifica_users"                     =>$root."ordini_chiusi/conferma_ordine/oc_modifica_q_arr_user.php",
                          "rettifica_denaro"                    =>$root."ordini_chiusi/conferma_ordine/oc_modifica_imp_user.php", 
                          "rettifica_denaro_totale"             =>$root."ordini/rettifiche/rettifica_denaro_totale.php", 
                          "rettifica_denaro_user"               =>$root."ordini/rettifiche/rettifica_denaro_user.php",       
                          "delete_ordine"                       =>$root."ordini/delete/ordini_form_delete.php");
}


  
?>