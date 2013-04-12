<?php

$val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($totale / $val_ord) *100;
}

$trasporto_percentuale =(float)round(valore_trasporto($id,$percent),4); 
$gestione_percentuale = (float)round(valore_gestione($id,$percent),4);

//COSTO GAS
$valore_mio_gas = valore_totale_mio_gas($id,$id_gas);
if($valore_mio_gas>0){
    $percent_gas = ($totale / valore_totale_mio_gas($id,$id_gas)) * 100;
    
}else{
    $percent_gas =0;
}
$costo_gas = (valore_assoluto_costo_mio_gas($id,$id_gas) / 100) * $percent_gas;

//COSTO MAGGIORAZIONE
$percent_maggiorazione_gas = valore_percentuale_maggiorazione_mio_gas($id,$id_gas);
$magg_gas = ($totale /100) * $percent_maggiorazione_gas;


//TOTALI
$costi = number_format((float)round(($trasporto_percentuale +  $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");
$somma_totale_utente =number_format((float)round(($totale + $trasporto_percentuale + $gestione_percentuale +$costo_gas + $magg_gas),2),2,",","");
$totale =number_format((float)round(($totale),2),2,",","");




//---------------------


 
$h_table.= "
				<tr class=\"total\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
					<td $col_1>$nbsp</td> 
					<td $col_2>$nbsp</td>    
					<td $col_3>$nbsp</td>
					<td $col_4>Totali</td>
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$totale $euro</td>
					
					<td $col_10>$costi $euro</td>
					<td $col_11>$somma_totale_utente $euro</td>  
				</tr>
			";
?>
