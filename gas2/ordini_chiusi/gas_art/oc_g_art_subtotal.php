<?php


$val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($subt_gas / $val_ord) *100;
}

$trasporto_percentuale =valore_trasporto($id,$percent); 
$gestione_percentuale = valore_gestione($id,$percent);

//COSTO GAS
$valore_mio_gas = valore_totale_mio_gas($id,$gas);

if($valore_mio_gas>0){
    $percent_gas = ($subt_gas / valore_totale_mio_gas($id,$gas)) * 100;
    
}else{
    $percent_gas =0;
}
$costo_gas = (valore_assoluto_costo_mio_gas($id,$gas) / 100) * $percent_gas;

//COSTO MAGGIORAZIONE
$percent_maggiorazione_gas = valore_percentuale_maggiorazione_mio_gas($id,$gas);
$magg_gas = ($subt_gas /100) * $percent_maggiorazione_gas;


//TOTALI
$costi = number_format((float)round(($trasporto_percentuale +  $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");

//FORMATTAZIONE

$subt_nome_gas = gas_nome($gas);
$gp = number_format($gestione_percentuale,2,",","");
$tp = number_format($trasporto_percentuale,2,",","");




//----------------------------
if($trasporto_percentuale>0){
    

    
    
    //$tp = number_format($trasporto_percentuale,2,",","");
    
    $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>Costo trasporto</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>

                    <td $col_8>$tp $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}
if($gestione_percentuale>0){

    
    
    //$gp = number_format($gestione_percentuale,2,",",""); 
    $h_table.= "
                <tr class=\"costo2\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>Costo Gestione</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$gp $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}

//COSTI PUBBLICI
  $somma_pubblica_gas =number_format((float)round(($subt_gas + $trasporto_percentuale + $gestione_percentuale),2),2,",","");
  $sa = number_format((float)round($subt_gas,2),2,",","");
  $cpu = number_format((float)round($trasporto_percentuale+$gestione_percentuale,2),2,",","");
  $h_table.= "
                <tr class=\"subtotal\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>Subtotale ($subt_nome_gas) Pubblico</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$cpu $euro</td>
                    <td $col_9>$somma_pubblica_gas $euro</td>  
                </tr>
            ";



/// Costo mio GAS
if($costo_gas>0){
    $riga++;
    if(is_integer($riga/2)){  
            $class= "odd";    // Colore Riga
        }else{
            $class= "";    
        }
      
      $costo_gas = number_format((float)round($costo_gas,2),2,",","");
      $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>Costo mio GAS</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$costo_gas $euro</td>
                    <td $col_9>$nbsp</td>
                      
                </tr>
            ";        
    
}

/// Maggiorazione mio GAS
if($percent_maggiorazione_gas>0){
    $riga++;
    $percent_maggiorazione_gas = number_format((float)round($percent_maggiorazione_gas,2),2,",","");
    $mg = number_format((float)round($magg_gas,2),2,",","");
    if(is_integer($riga/2)){  
            $class= "odd";    // Colore Riga
        }else{
            $class= "";    
        }
      $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>Maggiorazione GAS ($percent_maggiorazione_gas %)</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>              
                    <td $col_8>$mg $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}





  $sa = number_format((float)round($subt_gas,2),2,",","");
  $cpr = number_format((float)round($costo_gas + $magg_gas,2),2,",","");
  $somma_privata_gas =number_format((float)round(($subt_gas + $trasporto_percentuale + $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");
  if($cpr>0){
  $h_table.= "
                <tr class=\"subtotal\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                   <td $col_2>Subtotale Privato ($subt_nome_gas)</td>    
                    <td $col_3>$nbsp</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$cpr $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";
  }          
  unset($subt_gas);          
            
?>
