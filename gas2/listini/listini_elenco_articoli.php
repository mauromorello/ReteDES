<?php

include_once ("../rend.php"); 

//echo "ID LISTINO = $id_listino<br>
//	  DATA    = $data";
//exit ;
$query = "SELECT * FROM retegas_articoli WHERE id_listini='$data';";
  $res = $db->sql_query($query);
  
  echo'<thead>
	   <tr>
	   <th>#</th>
	   <th>Codice</th> 
	   <th>Descrizione</th> 
	   <th>Q.ta x prezzo</th> 
	   <th>Scatola</th>
	   <th>Multiplo</th>
	   <th>Prezzo</th>
	   <th>Scegli</th>  
	   </tr>
	   </thead>
	   <tbody>';
  
  while ($row = $db->sql_fetchrow($res)){
 
	  
	echo '<tr>
			<td>'.$row["id_articoli"].'</td>
			<td>'.$row["codice"].'</td>
			<td><input type="text" value="'.$row["descrizione_articoli"].'" name="box_descrizione['.$row["id_articoli"].']" size="32" /></td>
			<td>'.$row["u_misura"].' '.$row["misura"].' x Eu '.$row["prezzo"].'</td>
			<td>'.$row["qta_scatola"].' </td>
			<td>'.$row["qta_minima"].'</td>
			<td>
			<input type="text" value="'.$row["prezzo"].'" name="box_prezzi['.$row["id_articoli"].']" size="5" />
			<input type="hidden" value="'.$row["codice"].'" name="box_codici['.$row["id_articoli"].']"/>
			</td>
			<td><input type="checkbox" value="'.$row["id_articoli"].'" name="box_id_articoli[]" /></td>
			
		  </tr>';
	

	 
	 
		  
  }

   echo "</tbody>";
?>