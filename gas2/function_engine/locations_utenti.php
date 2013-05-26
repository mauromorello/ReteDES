<?php

if(isset($root) AND isset($iroot)){
$locations_utenti=Array(// USERS
                          //"pag_users"               =>$root."users.php",
                          "pag_users_form"          =>$root."utenti/utenti_form_public.php",
                          "pag_users_form_mia"      =>$root."utenti/utenti_form_mia.php",
                          "pag_users_form_mia_edit" =>$root."utenti/utenti_form_mia_edit.php",
                          "pag_users_form_password" =>$root."utenti/utenti_form_passw.php",
                          "pag_users_form_widgets"  =>$root."utenti/utenti_gestione_widgets.php",
                          "pag_users_theme_select"  =>$root."utenti/utenti_theme_select.php",

                          "user_form_public"        =>$root."utenti/utenti_form_public.php",
                          "user_option_sito"        =>$root."utenti/utenti_option_sito.php",
                          "user_permission_site"    =>$root."utenti/utenti_permessi_sito.php",
                          "user_registrazione"      =>$root."utenti/utenti_register.php",
                          "user_logout"             =>$root."utenti/utenti_logout.php",
                          "user_forgotten_pwd"      =>$root."utenti/utenti_forgotten_password.php",
                          "user_forgotten_usn"      =>$root."utenti/utenti_forgotten_username.php",
                          "utenti_nuova_password"   =>$root."utenti/utenti_nuova_password.php",
                          "utenti_superpoteri"      =>$root."utenti/utenti_superpoteri.php",
                          "utenti_chifacosa"        =>$root."utenti/utenti_chifacosa.php",
                          "user_help_pers"          =>$root."utenti/utenti_spiegazione_personalizza.php",
                          "user_suspended"          =>$root."gas/gas_users_suspended.php",
                          "user_note_suspended"     =>$root."utenti/utenti_suspended_notes.php",
                          "user_deleted"            =>$root."gas/gas_users_deleted.php",
                          "user_to_activate"        =>$root."gas/gas_user_activate.php",
                          "user_add"                =>$root."gas/gas_utente_add.php",
                          "user_activity"           =>$root."gas/gas_user_activity.php");
}

  
?>