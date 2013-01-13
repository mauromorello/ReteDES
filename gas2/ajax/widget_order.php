 <?php 

include_once ("../rend.php");

$cookie_read     =explode("|", base64_decode($user));
$id_user  = $cookie_read[0];

  //print_r ($rgw);
//print_r ($order_lat);

if(isset($rgw)){

$data = base64_encode(serialize($rgw));
//echo "DATI = ".$data."<br>";
write_option_text($id_user,"WGO",$data);
write_option_integer($id_user,"WG_C",$c1);

}

if(isset($section)){
$data = base64_encode(serialize($section));    
write_option_text($id_user,"MNL",$data);    
} 