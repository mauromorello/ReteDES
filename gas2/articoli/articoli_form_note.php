<?php
  
  include_once ("../rend.php");
  $vid_link = CAST_TO_INT($vid_link);
  $id_ordine = CAST_TO_INT($id_ordine);
	  
  // QUERY
	  
	  //echo $vid_link;
	  $my_query="SELECT retegas_articoli.*
				FROM retegas_articoli
				WHERE
				id_articoli='$vid_link';";
  
	  $result = $db->sql_query($my_query);   
	  $row = $db->sql_fetchrow($result);
	  
      $articoli = _nf(qta_ord_ordine_articolo($id_ordine,$vid_link));
      $scatole_intere = (int)q_scatole_intere_articolo_ordine($id_ordine,$vid_link);
      $avanzo_articolo = (float)round(q_articoli_avanzo_articolo_ordine($id_ordine,$vid_link),2);
      
	  
	  $h .= '<span style="text-size=14px">Articolo : '.$row["id_articoli"].', codice : '.$row["codice"].'</span>';  
	  $h .= '<h3>'.$row["descrizione_articoli"].'</h3>';
	  $h .= '<div>'.$row["ingombro"].'</div>';
      $h .= '<div>'.$row["articoli_note"].'</div>';
      if($row["articoli_opz_1"]<>""){
        $h .= '<h4>Variante:</h4>';
        $h .= '<div>'.$row["articoli_opz_1"].' - '.$row["articoli_opz_2"].' - '.$row["articoli_opz_3"].'</div>';
	  }
      if(CAST_TO_INT($articoli)>0){
        $h .= '<p>In questo ordine acquistati <strong>'.$articoli.'</strong> articoli (<strong>'.$scatole_intere.'</strong> scatole e <strong>'.$avanzo_articolo.'</strong> avanzi)</p>';
	  }
      echo $h;
   