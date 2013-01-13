<?php

if(isset($root) AND isset($iroot)){
    $locations_extra=Array( "extra_dashboard"            =>$root."extra/extra_dashboard.php",
                            "extra_blacklist"            =>$root."extra/extra_blacklist.php",
                            "ordini_form_delete_all"            =>$root."ordini/delete/ordini_form_delete_all.php");
}