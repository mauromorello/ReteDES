<?php

$val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($totale / $val_ord) *100;
}

$trasporto_percentuale =valore_trasporto($id,$percent); 
$gestione_percentuale = valore_gestione($id,$percent); 
$costi = number_format(($trasporto_percentuale +  $gestione_percentuale),2,",","");
$somma_totale_utente =number_format(($totale + $trasporto_percentuale + $gestione_percentuale),2,",","");

 
$h_table.= "
				<tr class=\"total\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
					<td $col_1>$nbsp</td> 
					<td $col_2>$nbsp</td>    
					<td $col_3>$nbsp</td>
					<td $col_4>Totali</td>
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$totale</td>
					
					<td $col_10>$costi</td>
					<td $col_11>$somma_totale_utente</td>  
				</tr>
			";
?>
