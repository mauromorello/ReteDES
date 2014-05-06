<?php


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;
}

	//COntrollo permessi

	if(id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS)<>_USER_ID){
		pussa_via();
		exit;
	}

	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}


	if($do=="mod"){

            $id_ordine = CAST_TO_INT($id_ordine);
            $data_distribuzione_start_db = conv_date_to_db($data_distribuzione_start);
            $data_distribuzione_end_db = conv_date_to_db($data_distribuzione_end);

            $luogo_distribuzione_db = sanitize($luogo_distribuzione);
            $testo_distribuzione_db = sanitize($testo_distribuzione);
            $lat_distribuzione = CAST_TO_FLOAT($lat_distribuzione);
            $lng_distribuzione = CAST_TO_FLOAT($lng_distribuzione);

             $query = "UPDATE retegas_referenze SET
                                data_distribuzione_start='$data_distribuzione_start_db',
                                data_distribuzione_end='$data_distribuzione_end_db',
                                luogo_distribuzione = '$luogo_distribuzione_db',
                                testo_distribuzione = '$testo_distribuzione_db',
                                lat_distribuzione = '$lat_distribuzione',
                                lng_distribuzione = '$lng_distribuzione'
                                WHERE id_ordine_referenze='$id_ordine'
                                AND id_gas_referenze='"._USER_ID_GAS."'
                                LIMIT 1;";
             $result = $db->sql_query($query);



	}

    //Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Distribuzione merce";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[] = c1_ext_javascript_datetimepicker("#data_distribuzione_start");
$r->javascripts[] = c1_ext_javascript_datetimepicker("#data_distribuzione_end");
$r->javascripts[] = get_geocoding("luogo_distribuzione","lat","lng","img");



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

    $query = "SELECT * FROM retegas_referenze WHERE id_ordine_referenze = '$id_ordine' AND id_gas_referenze = '"._USER_ID_GAS."' LIMIT 1;";
    $result = $db->sql_query($query);
    $row = $db->sql_fetchrow($result);

    if(!isset($data_distribuzione_start)){$data_distribuzione_start=conv_datetime_from_db($row["data_distribuzione_start"]);}
    if($data_distribuzione_start=="// 00:00"){
        $data_distribuzione_start ="";
    }

    if(!isset($data_distribuzione_end)){$data_distribuzione_end=conv_datetime_from_db($row["data_distribuzione_end"]);}
    if($data_distribuzione_end=="// 00:00"){
        $data_distribuzione_end ="";
    }
    if(!isset($testo_distribuzione)){$testo_distribuzione=$row["testo_distribuzione"];}
    if(!isset($luogo_distribuzione)){$luogo_distribuzione=$row["luogo_distribuzione"];}
    if(!isset($lat_distribuzione)){$lat_distribuzione=$row["lat_distribuzione"];}
    if(!isset($lng_distribuzione)){$lng_distribuzione=$row["lng_distribuzione"];}
    if($lng_distribuzione>0){
        $src = 'http://maps.googleapis.com/maps/api/staticmap?size=300x300&maptype=roadmap&markers=color:green%7Clabel:D%7C' . $lng_distribuzione . ',' . $lat_distribuzione . '&zoom=14&sensor=false';
    }

//Contenuto
    $h = '<div class="rg_widget rg_widget_helper">
        <h3>Modifica i dati di distribuzione merce</h3>
        <table>
        <tr>
        <td style="width:70%;vertical-align:top">
        <form class="retegas_form" name="" method="POST" action="">

        <div>
        <h4>1</h4>
        <label for="data_distribuzione_start">La data e l\'ora dell\' inizio distribuzione</label>
        <input style="text-align:right;" id="data_distribuzione_start" type="text" name="data_distribuzione_start" value="'.$data_distribuzione_start.'" ></input>
        <h5 title="'.$help_data_distribuzione_start.'">Inf.</h5>

        </div>
        <div>
        <h4>2</h4>
        <label for="data_distribuzione_end">La data e l\'ora della fine distribuzione</label>
        <input style="text-align:right;" id="data_distribuzione_end" type="text" name="data_distribuzione_end" value="'.$data_distribuzione_end.'" ></input>
        <h5 title="'.$help_data_distribuzione_end.'">Inf.</h5>

        </div>
        <div>
        <h4>3</h4>
        <label for="luogo_distribuzione">Il luogo della distribuzione</label>
        <input style="text-align:right;" id="luogo_distribuzione" type="text" name="luogo_distribuzione" value="'.$luogo_distribuzione.'" autocomplete="off"></input>
        <h5 title="'.$help_luogo_distribuzione.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="testo_distribuzione">Un comunicato riguardante la distribuzione</label>
        <input style="text-align:right;" id="testo_distribuzione" type="text" name="testo_distribuzione" value="'.$testo_distribuzione.'"></input>
        <h5 title="'.$help_testo_distribuzione.'">Inf.</h5>
        </div>

        <div>
        <h4>5</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche" align="center">
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="mod">
        <input type="hidden" id="lng" name="lat_distribuzione" value="'.$lat_distribuzione.'">
        <input type="hidden" id="lat" name="lng_distribuzione" value="'.$lng_distribuzione.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>


        </form>
        </td>
        <td>
        <img id="img" src="'.$src.'" style="width:100%;">
        </td>
        </tr>
        </table>
        </div>';


//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);


