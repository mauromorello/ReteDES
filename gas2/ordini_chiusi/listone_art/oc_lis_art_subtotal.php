<?php

  $somma_scatole = q_scatole_intere_articolo_ordine_arr($id,$c0);
  
  $h_table.= "<tr style=\"height:20px;\">
			<td $col_1 COLSPAN=9>$nbsp</td> 
		</tr>";    
  $h_table.= "<tr class=\"subtotal\">
					<td $col_1 COLSPAN=4>$vecchi_codici</td> 
					<td $col_5>$nbsp</td>
					<td $col_6>$nbsp</td>
					<td $col_7 COLSPAN=2>$somma_scatole</td>
					<td $col_9>$nbsp</td>  
				</tr>";

		
?>
