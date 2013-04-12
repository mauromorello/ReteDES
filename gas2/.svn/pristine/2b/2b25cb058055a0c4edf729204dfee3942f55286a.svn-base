<?php


   $val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($tot_articoli / $val_ord) *100;
}

$trasporto_percentuale =(float)round(valore_trasporto($id,$percent),4); 
$gestione_percentuale = (float)round(valore_gestione($id,$percent),4);

//COSTO GAS
$valore_mio_gas = valore_totale_mio_gas($id,$id_g);
if($valore_mio_gas>0){
    $percent_gas = ($tot_articoli / valore_totale_mio_gas($id,$id_g)) * 100;   
}else{
    $percent_gas =0;
}
$costo_gas = (valore_assoluto_costo_mio_gas($id,$id_g) / 100) * $percent_gas;

//COSTO MAGGIORAZIONE
$percent_maggiorazione_gas = valore_percentuale_maggiorazione_mio_gas($id,$id_g);
$magg_gas = ($tot_articoli /100) * $percent_maggiorazione_gas;

//Echo "ID_G = $id_g, tot_articoli = $tot_articoli<br>"; 
//Echo "Percent_aggiorazione gas = $percent_maggiorazione_gas<br>";
//Echo "Costo = $costo_gas<br>";
//Echo "Percent_gas = $percent_gas<br>";
//Echo "Valore_mio_Gas = $valore_mio_gas<br>";
//Echo "valore_Assoluto_magg = " . valore_assoluto_costo_mio_gas($id,$id_g);  

//TOTALI
$costi_pubblici = number_format((float)round(($trasporto_percentuale +  $gestione_percentuale),2),2,",","");
$costi_privati = number_format((float)round(($trasporto_percentuale +  $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");
$somma_totale_utente_privato =number_format((float)round(($tot_articoli + $trasporto_percentuale + $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");
$somma_totale_utente_pubblico =number_format((float)round(($tot_articoli + $trasporto_percentuale + $gestione_percentuale),2),2,",","");

$totale =number_format((float)round(($tot_articoli),2),2,",","");




//---------------------


 
$h_table.= "
                <tr class=\"total\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Totale Pubblico</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$nbsp</td>
                    <td $col_9>$totale $euro</td>                   
                    <td $col_10>$costi_pubblici $euro</td>
                    <td $col_11>$somma_totale_utente_pubblico $euro</td>  
                </tr>
            ";
if($costi_privati>0){
        $h_table.= "
                        <tr class=\"total\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
                            <td $col_1>$nbsp</td> 
                            <td $col_2>$nbsp</td>    
                            <td $col_3>Totale Privato</td>
                            <td $col_4>$nbsp</td>
                            <td $col_5>$nbsp</td>
                            <td $col_6>$nbsp</td>
                            <td $col_7>$nbsp</td>
                            <td $col_8>$nbsp</td>
                            <td $col_9>$totale $euro</td>                   
                            <td $col_10>$costi_privati $euro</td>
                            <td $col_11>$somma_totale_utente_privato $euro</td>  
                        </tr>
                    ";
}            
?>
