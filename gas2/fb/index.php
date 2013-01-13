<?php
     include("../rend.php");

     $app_id = "442853135751017";

     $canvas_page = "http://retegas.altervista.org/gas2/fb/";

     $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($canvas_page);

     $signed_request = $_REQUEST["signed_request"];

     list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

     $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

     if (empty($data["user_id"])) {
            echo("<script> top.location.href='" . $auth_url . "'</script>");
     } else {
            echo ("Welcome User: " . $data["user_id"]);
            //go("start");
     } 

    ?>