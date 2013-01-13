<?php

$val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($subt_amico / $val_ord) *100;
}

$trasporto_percentuale =valore_trasporto($id,$percent); 
$gestione_percentuale = valore_gestione($id,$percent); 
$costi = number_format(($trasporto_percentuale +  $gestione_percentuale),2,",","");
$somma_totale_utente =number_format(($subt_amico + $trasporto_percentuale + $gestione_percentuale),2,",","");

if($trasporto_percentuale>0){
	

	
	
	$tp = number_format($trasporto_percentuale,2,",","");
	
	$h_table.= "
  
				<tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
					<td $col_1>$nbsp</td> 
					<td $col_2>$nbsp</td>    
					<td $col_3>$nbsp</td>
					<td $col_4>Costo trasporto</td>
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$nbsp</td>
					 
					<td $col_10>$tp $euro</td>
					<td $col_11>$nbsp</td>  
				</tr>
			";		
	
}
if($gestione_percentuale>0){

	
	
	$gp = number_format($gestione_percentuale,2,",",""); 
	$h_table.= "
				<tr class=\"costo2\" style=\"border-bottom: 1px solid #000\">
					<td $col_1>$nbsp</td> 
					<td $col_2>$nbsp</td>    
					<td $col_3>$nbsp</td>
					<td $col_4>Costo Gestione</td>
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$nbsp</td>
					
					<td $col_10>$gp $euro</td>
					<td $col_11>$nbsp</td>  
				</tr>
			";        
	
}


  $h_table.= "
				<tr class=\"subtotal\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
					<td $col_1>$nbsp</td> 
					<td $col_2>$nbsp</td>    
					<td $col_3>$nbsp</td>
					<td $col_4>Subtotale ($subt_nome_amico)</td>
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7>$nbsp</td>
					<td $col_8>$subt_amico $euro</td>
					
					<td $col_10>$costi $euro</td>
					<td $col_11>$somma_totale_utente $euro</td>  
				</tr>
			";
?>
