<?php
 include_once("../rend.php");
 //Echo "<html>CRON POSTINO<br>";
 
 $coda_eff = quante_mail_coda_effettiva();
 $coda_tot =quante_mail_coda_totale();
 
 echo "Coda totale = ".$coda_tot."<br>";
 echo "Coda effettiva = ".$coda_eff."<br>";
 //Echo "<hr></html>";
 
