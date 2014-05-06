<?php

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");


function do_salva_carrello($box_id,$box_value,$ordine,$ordine_amico,$box_q_att,$box_q_min,$poi,$box_q_uni){



   global $user, $db;
   global $RG_addr;

    $cookie_read = explode("|", base64_decode($user));
    $id_user = $cookie_read[0];
    $username =$cookie_read[1];
    $gas = id_gas_user($id_user);
    $gas_name = gas_nome($gas);
    $fullname = fullname_from_id($id_user);

    $r=0;
    $msg="";
    $mail_necessaria = "NO";



    while (list ($key,$val) = @each ($box_id)) {

        $qarti=0;
        $arti=0;
        if(empty($box_q_att[$r])){$box_q_att[$r]=0;}

        //echo "Val $val , Valore $box_value[$r], Q att $box_q_att[$r], Ordine $ordine , Amico $ordine_amico, Q min $box_q_min[$r] <br>";


        if(is_numeric($box_value[$r])){
        if($box_value[$r]>0){

            //---------------Controllo se articolo doppio

            $ar_dopp =$db->sql_query("SELECT Count(retegas_dettaglio_ordini.id_articoli) AS ConteggioDiid_articoli,
                                        Sum(retegas_dettaglio_ordini.qta_ord) AS SommaDiqta_ord,
                                      retegas_dettaglio_ordini.id_utenti,
                                      retegas_dettaglio_ordini.id_amico,
                                      retegas_dettaglio_ordini.id_ordine,
                                      retegas_dettaglio_ordini.id_articoli,
                                      retegas_dettaglio_ordini.id_dettaglio_ordini
                                        FROM retegas_dettaglio_ordini
                                        GROUP BY retegas_dettaglio_ordini.id_utenti, retegas_dettaglio_ordini.id_amico, retegas_dettaglio_ordini.id_ordine, retegas_dettaglio_ordini.id_articoli
                                        HAVING (((retegas_dettaglio_ordini.id_utenti)='$id_user') AND ((retegas_dettaglio_ordini.id_amico)='$ordine_amico') AND ((retegas_dettaglio_ordini.id_ordine)='$ordine') AND ((retegas_dettaglio_ordini.id_articoli)='$val'));");



            $r_ar_dopp = mysql_fetch_row($ar_dopp);

            $key=$r_ar_dopp[6];

            if(empty($r_ar_dopp[0])){
                    $arti=0;
            }else{
                    $arti=$r_ar_dopp[0];
                    $qarti=$r_ar_dopp[1];
            }

            //---------------------------------------

            if($arti==0){// ------------------------------------------------ARTICOLO NUOVO

            // Metto q_arr = q_ord

            if(is_multiplo($box_q_min[$r],$box_value[$r])){

                // CICLO Che immette le quantità degli articoli come se fosser singole
                // a meno che il flag di univocità non esista
                // allora forzo la variabile ed esco



                for($i=$box_value[$r]; $i>0; $i=$i-$box_q_min[$r]){


                    // se non è settato il flag di univocità
                    // allora forzo la variabile

                    if($box_q_uni[$r]<>1){
                        //$i=$box_value[$r];
                        $i=0;
                        $valore_da_inserire = round($box_value[$r],4);
                        //echo "FORZATO CONTATORE per quantità $box_value[$r]<br>";
                    }else{
                        $valore_da_inserire = round($box_q_min[$r],4);
                        //echo "$i ciclo per Articolo unico Q 1<br>";
                    }


                    //PREZZO ARTICOLO ATTUALE
                    $prezzo_attuale = articolo_suo_prezzo($val);
                    $descrizione_attuale = sanitize(articolo_sua_descrizione($val));
                    $codice_attuale = sanitize(articolo_suo_codice($val));
                    $udm_attuale = sanitize(articolo_suo_udm($val));

                    $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini (
                                                    id_utenti,
                                                    id_articoli,
                                                    data_inserimento,
                                                    qta_ord,
                                                    id_amico,
                                                    id_ordine,
                                                    qta_arr,
                                                    prz_dett,
                                                    prz_dett_arr,
                                                    art_codice,
                                                    art_desc,
                                                    art_um)
                                                    VALUES (
                                                        '$id_user',
                                                        '$val',
                                                        NOW(),
                                                        '$valore_da_inserire',
                                                        '$ordine_amico',
                                                        '$ordine',
                                                        '$valore_da_inserire',
                                                        '$prezzo_attuale',
                                                        '$prezzo_attuale',
                                                        '$codice_attuale',
                                                        '$descrizione_attuale',
                                                        '$udm_attuale'
                                                        );";

                    $querona .= "INSERIMENTO ARTICOLO n. ".$r ."-->". $query_inserimento_articolo." <-- <br>";
                    //$result = $db->sql_query($query_inserimento_articolo);
                    $result = mysql_query($query_inserimento_articolo);
                    $mail_necessaria = "SI";

                    // scopro qual'è l'ultimo ID inserito (RIGA Dettaglio_ordine)

                    $res = mysql_query("SELECT LAST_INSERT_ID();");
                    $row = mysql_fetch_array($res);
                    $last_id=$row[0];
                    $querona .= "LAST ID n. ".$last_id ." <br>";
                    // aggiungo un record in dettaglio_spesa con l'articolo caricato in utente id_user

                    $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa (
                                             id_riga_dettaglio_ordine,
                                             id_amico,
                                             qta_ord,
                                             qta_arr,
                                             data_ins,
                                             id_articoli,
                                             id_user,
                                             id_ordine)
                                             VALUES (
                                                        '$last_id',
                                                        0,
                                                        '$valore_da_inserire',
                                                        '$valore_da_inserire',
                                                         NOW(),
                                                        '$val',
                                                        '$id_user',
                                                        '$ordine'
                                                        );";

                    $querona .= "DISTRIBUZIONE ARTICOLO n. ".$r ."-->". $query_distribuzione_spesa." <-- ";
                    $result_dettaglio_spesa = mysql_query($query_distribuzione_spesa);

                    //$output_html .= "INSERITO- - - - - - - - - - - - - -  - -- - <br>";


                    $msg .="($val) $valore_da_inserire x ".db_val_q("id_articoli",$val,"descrizione_articoli","retegas_articoli").", <br>";

                    } // FINE CICLO FOR PER GLI ARTICOLI

                }else{

                    $non_riuscito++;
                    $msg .= db_val_q("id_articoli",$val,"descrizione_articoli","retegas_articoli")." , Quantità ''$box_value[$r]'' ERRATA. <br>";

                }//$output_html .= "Q < Q min- - - - - - ";   } // > q minima

            //$querona .= $msg;
            $querona .= $msg;

            }


        } // is >0

        } // is numeric

        $r++;

    }





   if($non_riuscito>0){
        $msg .= "<br> n. $non_riuscito articoli non sono stati aggiunti all'ordine";
        $poi=1;
   }else{
        $msg .= "Tutti gli articoli sono stati inseriti correttamente<br>";
   }



   $id = $ordine;



   $vo = valore_totale_mio_ordine($id,_USER_ID);
   $no = descrizione_ordine_from_id_ordine($id);

   //UPDATE CASSA UTENTE SU MOVIMENTI NETTI

    if(_USER_USA_CASSA){
        $msg2 .= "<br>UTENTE CON CASSA<br>";
        //SE L'ORDINE E' IN MODALITA' PRENOTAZIONE ALLORA SALTA L'AGGIORNAMENTO DELLA CASSA
         if(read_option_prenotazione_ordine($id,_USER_ID)<>"SI"){
             $msg2 .= "NECESSARIO UPDATE CASSA<br>";
             cassa_update_ordine_utente($id,_USER_ID);
         }else{
             $msg2 .= "ORDINE IN PRENOTAZIONE<br>";
         }
    }


   //SCRIVO l'ora dell'operazione per evitare doppioni
   write_option_text($id_user,"PART_ORD",time());
   log_me($id,$id_user,"ORD","ART","Aggiunta di articoli all'ordine $id ($no), adesso il mio totale è $vo",$vo,$msg.$msg2.$querona);

   if($mail_necessaria=="SI"){
        rompi_le_balle($id,$id_user);
   }

   if($poi==1){
        go("ordine_partecipa",_USER_ID,$msg,"?id_ordine=$id");
   }else{
        go("ordini_form",_USER_ID,$msg,"?id_ordine=$id");
   }


}

//DEPRECATED DA ELIMINARE
function ridistribuisci_quantita_amici_part($key,$nq, &$msg=null){

global $db, $user,$a_hdr,$a_std,$a_alt;

// Ho la lista degli amici riferita all'articolo KEY

//    echo r_t_l2("DENTRO $key, $nq",$a_alt);



$qry ="SELECT
retegas_distribuzione_spesa.id_distribuzione,
retegas_distribuzione_spesa.id_riga_dettaglio_ordine,
retegas_distribuzione_spesa.qta_ord,
retegas_distribuzione_spesa.qta_arr,
retegas_distribuzione_spesa.id_amico
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$key'
ORDER BY
retegas_distribuzione_spesa.id_distribuzione DESC";

// Adesso la popolo con la nuova quantità partendo dall'ultima riga immessa;
// in realtà cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;

$result = $db->sql_query($qry);
$totalrows = mysql_num_rows($result);
$rimasto=$nq;

    while ($row = mysql_fetch_array($result)){
    //echo r_t_l2("°°°°°°°° $key rimasto $rimasto ",$a_alt);
        $a = $rimasto;// - $row['qta_ord'];
        $id_q = $row['id_distribuzione'];

        if($a>0){
            $q_a = $row['qta_ord'];
            $rimasto=$a;
        }else{
            $q_a = $rimasto;
            $rimasto=0;
        }

    // update

    $result2 = mysql_query("UPDATE retegas_distribuzione_spesa
                            SET retegas_distribuzione_spesa.qta_arr = '$q_a',
                            SET retegas_distribuzione_spesa.qta_ord = '$q_a',
                                retegas_distribuzione_spesa.data_ins = NOW()
                            WHERE (retegas_distribuzione_spesa.id_distribuzione='$id_q');");

     if($row['qta_ord']<>$q_a){

        // le quantita' sono diverse, ricalcolo le assegnazioni sugli amici

    $msg .= "";
    $amico = $row["id_amico"];

        //echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_alt);

    }else{

        //echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_std);

    }

   //echo r_t_l2("OPERAZIONE CONCLUSA: TORNA INDIETRO",$a_hdr,"ordini_chiusi_dettaglio_codice.php?do=vis1&id_ord=$id_ord");

    // CICLO DI UPDATE

    }


}


if(isset($the_next_step)){$poi = (int)$the_next_step;};
//echo $poi;

if(isset($id)){$id_ordine=$id;};

(int)$id_ordine;

if (!_USER_LOGGED_IN){
    pussa_via();
    exit;
}

$check = utente_attivo_partecipa_ordine($id_ordine);
if($check<>"OK"){
    log_me($id_ordine,_USER_ID,"CHK","ORD","Check : false",0,$check);
    go("ordini_form",_USER_ID,$check,"?id_ordine=$id_ordine");
    die();
}



    if($do=="salva_carrello"){

             $is_ok = "NO";

             $is_ok = utente_attivo_controllo_cassa($hidden_grand_total,$id_ordine);
             //log_me($id_ordine,_USER_ID,"CHK","XXX","Check new : $is_ok",0);

             $time_now = time();
             $time_last_op = read_option_text(_USER_ID,"PART_ORD");
             if(($time_now - $time_last_op) < 10){

                 $msg="Potrebbe essere stato premuto più volte il pulsante che salva l'ordine.<br>
                      Controllare gli articoli ordinati.";
             }else{

                //SE E' TUTTO A POSTO
                if($is_ok=="SI"){
                    do_salva_carrello($box_id,$box_value,$id_ordine,0,$box_q_att,$box_q_min,$poi,$box_q_uni);
                }else{
                    go("ordine_partecipa",_USER_ID,$is_ok,"?id_ordine=$id_ordine");
                }
             }



        }


    // ISTANZIO un nuovo oggetto "retegas"


    $retegas = new sito;
    $retegas->posizione = "Partecipa all'ordine";

    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard

    $retegas->sezioni = $retegas->html_standard;

    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento

    // Menu specifico per l'output
    $retegas->menu_sito = ordini_menu_all($id_ordine);

    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker";

    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg","jcalc");  // editor di testo

      // creo  gli scripts per la gestione dei menu

      $ref_table = "partecipa";

      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale
      $retegas->java_scripts_header[] = java_superfish();
      //$retegas->java_scripts_header[] = java_tablesorter($ref_table);


      $retegas->java_scripts_header[]='<script type="text/javascript">
                        $(document).ready(function()
                            {
                                $("#'.$ref_table.'").tablesorter({widgets: [\'zebra\',\'filter\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',
                                                        });
                                }
                            );
                            </script>';



      $retegas->java_scripts_header[] = java_head_fg_menu();


      //Jgrowl  CSS
      $retegas->java_scripts_header[] = "<link type=\"text/css\" href=\"".$RG_addr["css_jgrowl"]."\" media=\"screen\" rel=\"stylesheet\">\n";
      //Breadcumb CSS
      //$retegas->java_scripts_header[] = "<link type=\"text/css\" href=\"".$RG_addr["css_fg_menu"]."\" media=\"screen\" rel=\"stylesheet\">\n";


      $retegas->css_body[] = fg_css();


      $retegas->java_scripts_bottom_body[] = java_qtip_ajax($RG_addr["ajax_articoli_note"],null,null,$id_ordine);
      $retegas->java_scripts_bottom_body[] = java_qtip();


      if(read_option_prenotazione_ordine($id_ordine,_USER_ID)=="SI"){
          $show = "false";
      }else{
          $show = "true";
      }




      $retegas->java_scripts_bottom_body[] = '
      <script type="text/javascript">

            var to_show = '.$show.';


            $.ctrl = function(key, callback, args) {
                $(document).keydown(function(e) {
                    if(!args) args=[];
                    if(e.keyCode == key.charCodeAt(0) && e.ctrlKey) {
                        callback.apply(this, args);
                        e.preventDefault();
                        return false;
                    }
                });
            };

            $.ctrl(\'1\', function() {
                $(\'.assignment\').fadeToggle();

            });

            var is_edited = false;

            jQuery.fn.contentChange = function(callback){
                var elms = jQuery(this);
                elms.each(
                  function(i){
                    var elm = jQuery(this);
                    elm.data("lastContents", elm.html());
                    window.watchContentChange = window.watchContentChange ? window.watchContentChange : [];
                    window.watchContentChange.push({"element": elm, "callback": callback});
                  }
                )
                return elms;
              }
              setInterval(function(){
                if(window.watchContentChange){
                  for( i in window.watchContentChange){
                    if(window.watchContentChange[i].element.data("lastContents") != window.watchContentChange[i].element.html()){
                      window.watchContentChange[i].callback.apply(window.watchContentChange[i].element);
                      window.watchContentChange[i].element.data("lastContents", window.watchContentChange[i].element.html())
                    };
                  }
                }
              },500);

            function showChange(){
                //var element = $(this);
                //alert("it was \'"+element.data("lastContents")+"\' and now its \'"+element.html()+"\'");

                var cre = parseFloat($(\'#credito_info\').text());
                if (cre < '._GAS_CASSA_MIN_LEVEL.'){

                    $(\'#credito_info\').switchClass( "crediti", "crediti_alert", 1000 );
                    $(\'#credito_jgrowl\').switchClass( "crediti_jgrowl", "crediti_alert", 1000 );
                }else{
                    $(\'#credito_info\').switchClass( "crediti_alert", "crediti", 1000 );
                    $(\'#credito_jgrowl\').switchClass( "crediti_alert", "crediti_jgrowl", 1000 );
                }


            }

            if(to_show){
                $(\'#credito_info\').contentChange( showChange );
            }

            function checkIsEdit() {
                if(is_edited)
                    return "Stai per uscire senza aver salvato.";
            }

            function recalc(){

                    suma = $("input[name^=box_value]").sum();
                    $("#grandArt").text(suma.toFixed(2));


                    $("[id^=total_item]").calc("qty * price",
                                                            {
                                                        qty: $("input[name^=box_value]"),
                                                        price: $("input[name^=box_price]")
                                                            },
                                                            function (s){
                                                                return  s.toFixed(2);
                                                            },
                                                            function ($this){
                                                                var sum = $this.sum();
                                                                $("#grandTotal").text(sum.toFixed(2));
                                                                $("#hgt").val(sum.toFixed(2));

                                                                $.post(\'credito_residuo.php\', { ord_att : $("#grandTotal").text(), id_ordine: '.$id_ordine.'},
                                                                     function(data) {
                                                                      if(to_show){
                                                                        $(\'#credito_info\').html(data);
                                                                        $(\'#credito_jgrowl\').html(data);
                                                                      }
                                                                    });


                                                                $(\'#credito_info\').change(function() {



                                                                });




                                                                var n_ele = $("input[name^=box_price]").length;
                                                                $.jGrowl("Articoli : <b>" + suma.toFixed(2) + "</b><br><div style=\'font-size:18px;\'>Totale euro : <b>" + sum.toFixed(2) + "</div><div style=\'font-size:12px;\'>Credito residuo: <span id=\'credito_jgrowl\' class=\'crediti_jgrowl\'></span></div>", { header: \''.addslashes(descrizione_ordine_from_id_ordine($id_ordine)).'\', glue:"before" });                                         }
                                                        );

                    };


                    $("input").keyup(function() {
                       is_edited=true;
                       recalc();
                    });



                    $(".button").click(function() {

                        is_edited=true;

                        var $button = $(this);
                        var oldValue = $button.parent().find("input[id^=textbox_]").val();

                        if ($button.text() == "+") {
                              var newVal = parseFloat(oldValue) + +$button.parent().find("input[name^=box_q_min]").val();
                            } else {
                              var newVal = parseFloat(oldValue) - +$button.parent().find("input[name^=box_q_min]").val();
                              if (newVal < 0) {newVal=0;}
                            }
                            $button.parent().find("input[id^=textbox_]").val(newVal);
                            recalc();
                            return false;
                    });

                    recalc();

                    window.onbeforeunload = checkIsEdit;

            </script>';


      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){
        $retegas->messaggio = $msg;
      }else{
        $retegas->messaggio = read_option_text(_USER_ID,"MSG");
                 delete_option_text(_USER_ID,"MSG");
      }




      $testo_istruzioni = "<br>
                        <ul>
                        <h4>Gli amici che compaiono nelle maschere di ASSEGNAZIONE MERCE si possono scegliere dalla tabella \"I miei amici\"</h4>
                        <li><strong>Cod.Art.Fornitore:</strong> Il codice dell'articolo usato dal fornitore;
                        </li>
                        <li><strong>Descrizione:</strong> La descrizione dell'articolo; se è un articolo con diverse varianti (riga color rosa), cliccandoci sopra si può selezionare una sua variante specifica; Se è un articolo normale, cliccandoci sopra si visualizzano alcune sue informazioni.
                        </li>
                        <li><strong>Prezzo per Quantità</strong> La quantità rappresenta l'untità di vendita, e il prezzo si riferisce a questa.
                        </li>
                        <li><strong>Q Scatola</strong> Quante unità di vendita sono contenute in una scatola;
                        </li>
                        <li><strong>Q Minima:</strong> Il numero di unità minime acquistabili. (La Quantità scatola è un multiplo di questo valore);
                        </li>
                        <li><strong>Ordinativi:</strong> Se nel tuo ordine hai già ordinato questo articolo compariranno due pulsanti, M = Modifica e E = Elimina, che dovrai usare per modificare e/o cancellare l'ordine già fatto; Se invece non hai ancora
                        acquistato questo articolo digita la quantità desiderata in questo campo, oppure usa i pulsanti \"+\" e \"-\".<br>
                        <strong>LE QUANTITA' IMMESSE NON SONO REGISTRATE FINCHE' NON SI PREME IL PULSANTE VERDE IN FONDO ALLA PAGINA \"SALVA LA SPESA\".</strong>
                        </li>
                        <li><strong>Totale Riga:</strong> Nel caso dell'acquisto di più unità il totale riga indica il prezzo complessivo per quell'articolo;
                        </li>
                        <li><strong>Totali:</strong> Vengono indicati la quantità di articoli acquistati ed il loro totale.;
                        </li>
                        <li><strong>CTRL+1</strong> Abilita la possibilità di inserire gli articoli assegnandoli direttamente agli amici.;
                        </li>
                        </ul>
                        <p>Se si acquista un articolo UNIVOCO, la sua modifica e/o assegnazione sarà possibile
                        passando attraverso ad una maschera che nel dettaglio fa vedere TUTTI i pezzi singoli acquistati.<br>
                        Ogni pezzo singolo può essere diviso tra i propri amici. Per aggiungere altri pezzi UNIVOCI usare la prima riga della tabella, specificando a chi verranno assegnati.</p>
                        ";

      $is = rg_toggable("ISTRUZIONI","istru",$testo_istruzioni);
            // qui ci va la pagina vera e proria
      $retegas->content  =  schedina_ordine($id_ordine).
                            $is.
                            ordine_render_partecipa($ref_table,$id_ordine,_USER_ID);

      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);