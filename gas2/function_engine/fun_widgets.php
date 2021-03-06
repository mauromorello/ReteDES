<?php

// Per serializzare in Jquery occorre che i widgets abbiano una numerazione come id
//
// rgw_1 = ordini_chiusi
// rgw_2 = ordini_aperti
// rgw_3 = ordini futuri
// rgw_4 = bachechina
// rgw_5 = Grafico utilizzo sito (torta)
// rgw_6 = Utenti Online
// rgw_7 = retegas Comunica
// rgw_8 = chat
// rgw_9 = Alerts utenti
// rgw_10 = tutti gli ordini
// rgw_11 = Ordini io coinvolto
// rgw_12 = Utenti mio gas
// rgw_13 = Movimenti Cassa Utente
// rgw_14 = Ordine sott'occhio
// rgw_15 = IDS (indice di solidariet?)


//ORDINI
function rgw_ordini_chiusi($site,$gas){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_1";

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
$site->java_scripts_bottom_body[]='
       <script type="text/javascript">

(function ($) {

  jQuery.expr[\':\'].Contains = function(a,i,m){
      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
  };

  function listFilter(header, list) { // header is any element, list is an unordered list
    // create and add the filter form to the header
    var form = $("<form>").attr({"style":"display: block","action":"#"}),
        input = $("<input>").attr({"style":"display: block","type":"text"});
    $(form).append(input).appendTo(header);

    $(input)
      .change( function () {
        var filter = $(this).val();
        if(filter) {
          // this finds all links in a list that contain the input,
          // and hide the ones not containing the input while showing the ones that do
          $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
          $(list).find("a:Contains(" + filter + ")").parent().slideDown();
        } else {
          $(list).find("li").slideDown();
        }
        return false;
      })
    .keyup( function () {
        // fire the above change event after every letter
        $(this).change();
    });
  }


  //ondomready
  $(function () {
    listFilter($("#header_chiusi"), $("#list"));
  });
}(jQuery));
  </script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Ordini Chiusi <cite style=\"font-size:.7em\">( ".n_ordini_chiusi($gas)." )</cite>";
$w->content = main_render_quick_ordini_chiusi($gas);
$w->footer = "Scheda con tutti gli ordini chiusi visibili dal ".gas_nome($gas);
$w->use_handler =false;
// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_ordini_aperti($site,$gas){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_2";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Ordini Aperti <cite style=\"font-size:.7em\">( ".n_ordini_partecipabili($gas)." )</cite>";
$w->toggle_state ="show";
$w->content = main_render_quick_ordini_aperti($gas);
$w->footer = "Ordini aperti visibili dal ".gas_nome($gas);
$w->use_handler =false;
// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_ordini_futuri($params){


//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$gas    = null; //il gas di appartenenza

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

// Nome id del widget
$w_name = "rgw_3";

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($gas)){return "<div clas=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"gas\"";exit;};


// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]=

// istanzio un nuovo oggetto widget
$w = new rg_widget();

//guardo quanti ordini futuri ci sono. se non ce ne sono nascondo il widget
$n_ordini_futuri = n_ordini_futuri($gas);
if($n_ordini_futuri>0){
    $w->toggle_state ="show";
    $w->can_toggle = true;
    $w->content = main_render_quick_ordini_futuri($gas);
}else{
    $w->toggle_state ="hide";
    $w->can_toggle = false;
    $w->content="";
}

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Ordini Futuri <cite style=\"font-size:.7em\">( ".$n_ordini_futuri." )</cite>";


$w->footer = "Ordini programmati visibili dal ".gas_nome($gas);
$w->use_handler =false;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_ordini_tutti($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 10;

//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$gas    = null; //il gas di appartenenza
$id_user =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($gas)){return "<div clas=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"gas\"";exit;};

// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
$site->java_scripts_bottom_body[]='
       <script type="text/javascript">

(function ($) {

  jQuery.expr[\':\'].Contains = function(a,i,m){
      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
  };

  function listFilter(header, list) {
    // create and add the filter form to the header
    var form = $("<form>").attr({"style":"display: block","action":"#"}),
        input = $("<input>").attr({"style":"display: block","type":"text"});
    $(form).append(input).appendTo(header);

    $(input)
      .change( function () {
        var filter = $(this).val();
        if(filter) {
          $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
          $(list).find("a:Contains(" + filter + ")").parent().slideDown();
        } else {
          $(list).find("li").slideDown();
        }
        return false;
      })
    .keyup( function () {
        // fire the above change event after every letter
        $(this).change();
    });
  }


  //ondomready
  $(function () {
    listFilter($("#header_tutti"), $("#list_tutti"));
  });
}(jQuery));
  </script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();



//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array("N_Ordini_da_mostrare"=>20,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array("N_Ordini_da_mostrare"=>"Numero di ordini da visualizzare",
                                "Aperto_al_caricamento"=>"Trova questa lista aperta al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}


$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));
CAST_TO_INT($N_Ordini_da_mostrare);


//RICERCA
$w->search= '<span class="small_link" id="header_tutti">Cerca tra questi ordini ...</span>';
$w->has_search = true;




// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Ultimi $N_Ordini_da_mostrare ordini <cite style=\"font-size:.7em\">( aperti / chiusi / futuri )</cite>";
$w->content = main_render_quick_ordini_tutti($gas,$N_Ordini_da_mostrare);
$w->footer = "Scheda con tutti gli ordini da ".gas_nome($gas);
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_ordini_io_coinvolto($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 11;

//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$gas    = null; //il gas di appartenenza
$id_user =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($id_user)){return "<div clas=\"ui-state_alert\"><strong>$id_widget : </strong>Parametro mancante \"id_user\"";exit;};
if(is_empty($gas)){return "<div clas=\"ui-state_alert\"><strong>$id_widget : </strong>Parametro mancante \"gas\"";exit;};



// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
$site->java_scripts_bottom_body[]='
       <script type="text/javascript">

(function ($) {

  jQuery.expr[\':\'].Contains = function(a,i,m){
      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
  };

  function listFilter(header, list) {
    // create and add the filter form to the header
    var form = $("<form>").attr({"style":"display: block","action":"#"}),
        input = $("<input>").attr({"style":"display: block","type":"text"});
    $(form).append(input).appendTo(header);

    $(input)
      .change( function () {
        var filter = $(this).val();
        if(filter) {
          $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
          $(list).find("a:Contains(" + filter + ")").parent().slideDown();
        } else {
          $(list).find("li").slideDown();
        }
        return false;
      })
    .keyup( function () {
        // fire the above change event after every letter
        $(this).change();
    });
  }


  //ondomready
  $(function () {
    listFilter($("#header_'.$w_name.'"), $("#list_'.$w_name.'"));
  });
}(jQuery));
  </script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();


//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array("N_Ordini_da_mostrare"=>20,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array("N_Ordini_da_mostrare"=>"Numero di ordini da visualizzare",
                                "Aperto_al_caricamento"=>"Trova questa lista aperta al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}


$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));
CAST_TO_INT($N_Ordini_da_mostrare);



// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Ordini che mi vedono coinvolto <cite style=\"font-size:.7em\">(Ultimi $N_Ordini_da_mostrare)</cite>";
$w->content = main_render_quick_ordini_io_coinvolto($w_name,$id_user,$gas,$N_Ordini_da_mostrare);
$w->footer = "Scheda con tutti gli ordini in cui io sono coinvolto (Partecipo o Gestisco)";
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

//WIDGET IDS
function rgw_ids($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 15;

//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$gas    = null; //il gas di appartenenza
$id_user =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($gas)){return "<div clas=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"gas\"";exit;};

// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
array_push($site->java_headers,"highcharts");


// istanzio un nuovo oggetto widget
$w = new rg_widget();



//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array("Finestra"=>30,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array("Finestra"=>"GG da considerare",
                                "Aperto_al_caricamento"=>"Trova questa lista aperta al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}


$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));
CAST_TO_INT($Finestra);


//RICERCA
$w->search= '<span class="small_link" id="header_tutti">Cerca tra questi ordini ...</span>';
$w->has_search = false;




// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="IDS - Indice di Solidarietà";
$ids = IDS($gas,$Finestra,$Finestra);
$ids_passato = IDS($gas,$Finestra*2,$Finestra);
$ids_diff = round((($ids-$ids_passato)/$ids_passato)*100,1);
if($ids_diff>0){$color=" style=\"font-size:1.5em; color:#008000\" ";}else{$color=" style=\"font-size:1.5em; color:#800000\" ";}
$a = "<div id=\"ids_widget_container\" style=\"height:12em;\"></div>";
$b = "<center><div><div>IDS attuale</div><strong>$ids</strong></div><br>
      <div><div>IDS passato</div><strong>$ids_passato</strong></div><br>
      <div><div>DIFFERENZA</div><strong $color>$ids_diff%</strong></div><br>
      <div><a href=".$RG_addr["gas_ids"]." class=\"awesome green small\">IDS storico</a></div></center>";

$w->content= render_container_table_2($a,$b,70,30);


$w->footer = gas_nome($gas);
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

//APPENDO JAVASCRIPT
$site->java_scripts_bottom_body[]= <<<JJJ
<script type="text/javascript">
$(function () {
    var chart = new Highcharts.Chart({

        chart: {
            renderTo: 'ids_widget_container',
            type: 'gauge',
            alignTicks: false,
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: ''
        },
        pane: {
            startAngle: -120,
            endAngle: 120
        },

        yAxis: [{
            min: 0,
            max: 100,
            tickPosition: 'outside',
            minorTickPosition: 'inside',
            tickColor: '#000000',
            minorTickColor: '#D0D0D0',
            tickLength: 10,
            minorTickLength: 5,
            labels: {
                distance: 12,
                rotation: 'auto'
            },

            plotBands: [{
                from: 60,
                to: 100,
                color: '#55BF3B' // green
            }, {
                from: 30,
                to: 60,
                color: '#DDDF0D' // yellow
            }, {
                from: 0,
                to: 30,
                color: '#DF5353' // red
            }]
        }],

        series: [{
            name: 'IDS',
            data: [$ids]

        }]

    });
});
</script>

JJJ;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}



//UTENTI
function rgw_utenti_mio_gas($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 12;

//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$gas    = null; //il gas di appartenenza
$id_user =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($gas)){return "<div clas=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"gas\"";exit;};

// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';
$site->java_scripts_bottom_body[]='
       <script type="text/javascript">

(function ($) {

  jQuery.expr[\':\'].Contains = function(a,i,m){
      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
  };

  function listFilter(header, list) {
    // create and add the filter form to the header
    var form = $("<form>").attr({"style":"display: block","action":"#"}),
        input = $("<input>").attr({"style":"display: block","type":"text"});
    $(form).append(input).appendTo(header);

    $(input)
      .change( function () {
        var filter = $(this).val();
        if(filter) {
          $(list).find("a:not(:Contains(" + filter + "))").parent().slideUp();
          $(list).find("a:Contains(" + filter + ")").parent().slideDown();
        } else {
          $(list).find("li").slideDown();
        }
        return false;
      })
    .keyup( function () {
        // fire the above change event after every letter
        $(this).change();
    });
  }


  //ondomready
  $(function () {
    listFilter($("#header_filter_'.$id_widget.'"), $("#list_filter_'.$id_widget.'"));
  });
}(jQuery));
  </script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();



//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array(//"N_Ordini_da_mostrare"=>20,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array(//"N_Ordini_da_mostrare"=>"Numero di ordini da visualizzare",
                                "Aperto_al_caricamento"=>"Trova questa lista aperta al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}


$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));



//RICERCA
$w->search= '<span class="small_link" id="header_filter_'.$id_widget.'">Cerca tra gli utenti ...</span>';
$w->has_search = true;




// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Utenti del mio GAS";
$w->content = crea_lista_user_mio_gas($gas);
$w->footer = "Utenti del ".gas_nome($gas);
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_utenti_online($site,$gas){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_6";

//SEZIONE CONTEGGIO UTENTI ATTIVI
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>Presenze GAS :<br></b>
                    '.crea_lista_gas_attivi(2).'
                    </div>';

//SEZIONE CONTEGGIO UTENTI ATTIVI

//se amministro
if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>User OnLine (Tutta '._SITE_NAME.'):<br></b>
                    '.crea_lista_user_attivi_pubblica(2).'
                    </div>';
}

//SEZIONE VISUALIZZAZIONE USER ONLINE

//SEZIONE CONTEGGIO UTENTI ATTIVI PROPRIO GAS
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>'.gas_nome($gas).'<br></b>
                    '.crea_lista_user_attivi_pubblica_gas(2,$gas).'
                    </div>';


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

//Guardo quanta gente c'?;
$online = crea_numero_user_attivi_totali(2);

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Utenti OnLine <cite style=\"font-size:.7em\">( ".$online." )</cite>";
$w->content = $h3;
$w->use_handler =false;
$w->footer = "Utenti che stanno in questo momento usando il sito.";

if($online>1){
    //$w->can_toggle=true;
    $w->toggle_state = "show";
}else{
    //$w->can_toggle=false;
    $w->toggle_state = "hide";
}

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

//RGW04 : BACHECA
include ("widget_04.php");


//FEEDBACK
function alert_feedback(){

   global $db,$RG_addr;

   //PASSO TUTTI GLI ORDINI CHIUSI AI QUALI HO PARTECIPATO o HO GESTITO

   $my_query = "SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE ((retegas_referenze.id_gas_referenze)='"._USER_ID_GAS."')
            ORDER BY retegas_ordini.data_apertura DESC
            LIMIT 100;";


      $result = $db->sql_query($my_query);

        $h_table = '<br><ul id="list_alert_feedback">';

         while ($row = $db->sql_fetchrow($result) AND $riga<20){

         if(is_printable_from_id_ord($row["id_ordini"])){
            $io_cosa_sono = ordine_io_cosa_sono($row["id_ordini"],_USER_ID);
             //echo "IO = $io_cosa_sono<br>";

             $commento="";
             $opinione="";


             switch($io_cosa_sono){


                 case 2:

                    $show=false;
                    $sql = "SELECT * FROM retegas_bacheca WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."' AND code_uno='9';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $commento = " lascia un <a href=\"".$RG_addr["opinione_partecipante"]."?id_ordine=".$row["id_ordini"]."\"><b>commento</b></a> come partecipante";
                        $show=true;

                    }

                    $sql = "SELECT * FROM retegas_opinioni WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $opinione = " lascia un'<a href=\"".$RG_addr["opinione_partecipante"]."?id_ordine=".$row["id_ordini"]."\"><b>opinione</b></a> ";
                        $show=true;
                    }

                    if($show){
                        $riga++;
                        $quanti++;
                        $h_table.=  '<li>Ord. '.$row["id_ordini"].' <b>'.$row["descrizione_ordini"].'</b>, io ho partecipato, '.$commento.', '.$opinione.'</li>';
                    }
                    break;
                 case 4:
                    $sql = "SELECT * FROM retegas_bacheca WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."' AND code_uno='12';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $h_table.=  '<li>Ord. '.$row["id_ordini"].' <b>'.$row["descrizione_ordini"].'</b>, io ho gestito, lascio un <a href="'.$RG_addr["opinione_referente"].'?id_ordine='.$row["id_ordini"].'"><b>commento e un opinione</a>.</b></li>';
                        $riga++;
                        $quanti++;
                    }


                    break;
             }

          }


        }//end while

       $pecche ="<p>Retedes.it è un sistema che si basa sulla fiducia reciproca tra i gasisti. Contribuisci anche tu a far crescere questo progetto dando un commento od una opinione sui fornitori dai quali hai acquistato merce o hai gestito gli ordini; Sarà utile a tutta la comunità, sapere se qualcosa è andato storto oppure è filato tutto liscio.</p>
       <p>L'avviso nella home scompare quando avrai dato un commento a tutti gli ultimi ordini in cui sei stato coinvolto.</p>
       <p>Puoi gestire i tuoi commenti (modificarli o cancellarli, o georeferenziarli), da questa pagina <a href=\"".$RG_addr["bacheca_user_table"]."\"><strong>qua</strong></a>, che trovi anche sotto il menu \"bacheca\"</p>";


       $h_table .='</ul>';

       $h .= "<div class=\"rg_widget rg_widget_helper\">";
       $h .= "<h3>Ci sono $quanti commenti o opinioni da lasciare !!</h3>";

       $h .= "<p><b>Perchè ?</b><br>$pecche</p>";
       //$h .= "<p>";
       $h .= $h_table;
       //$h .= "</p>";

       $h .= "</div>";
       if($quanti>0){
            return $h;
       }

       return;


}
function alert_feedback_num(){

   global $db,$RG_addr;

   //PASSO TUTTI GLI ORDINI CHIUSI AI QUALI HO PARTECIPATO o HO GESTITO

   $my_query = "SELECT retegas_ordini.id_ordini,
            retegas_ordini.descrizione_ordini,
            retegas_listini.descrizione_listini,
            retegas_ditte.descrizione_ditte,
            retegas_ordini.data_chiusura,
            retegas_gas.descrizione_gas,
            retegas_referenze.id_gas_referenze,
            maaking_users.userid,
            maaking_users.fullname,
            retegas_ordini.id_utente,
            retegas_ordini.id_listini,
            retegas_ditte.id_ditte,
            retegas_ordini.data_apertura
            FROM (((((retegas_ordini INNER JOIN retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze) LEFT JOIN maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid) INNER JOIN retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini) INNER JOIN retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte) INNER JOIN maaking_users AS maaking_users_1 ON retegas_ordini.id_utente = maaking_users_1.userid) INNER JOIN retegas_gas ON maaking_users_1.id_gas = retegas_gas.id_gas
            WHERE ((retegas_referenze.id_gas_referenze)='"._USER_ID_GAS."')
            ORDER BY retegas_ordini.data_apertura DESC
            LIMIT 100;";


      $result = $db->sql_query($my_query);

        $h_table = '<br><ul id="list_alert_feedback">';

         while ($row = $db->sql_fetchrow($result) AND $riga<20){

         if(is_printable_from_id_ord($row["id_ordini"])){
            $io_cosa_sono = ordine_io_cosa_sono($row["id_ordini"],_USER_ID);
             //echo "IO = $io_cosa_sono<br>";

             $commento="";
             $opinione="";


             switch($io_cosa_sono){


                 case 2:

                    $show=false;
                    $sql = "SELECT * FROM retegas_bacheca WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."' AND code_uno='9';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $commento = " lascia un <a href=\"".$RG_addr["opinione_partecipante"]."?id_ordine=".$row["id_ordini"]."\"><b>commento</b></a> come partecipante";
                        $show=true;

                    }

                    $sql = "SELECT * FROM retegas_opinioni WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $opinione = " lascia un'<a href=\"".$RG_addr["opinione_partecipante"]."?id_ordine=".$row["id_ordini"]."\"><b>opinione</b></a> ";
                        $show=true;
                    }

                    if($show){
                        $riga++;
                        $quanti++;
                        $h_table.=  '<li>Ord. '.$row["id_ordini"].' <b>'.$row["descrizione_ordini"].'</b>, io ho partecipato, '.$commento.', '.$opinione.'</li>';
                    }
                    break;
                 case 4:
                    $sql = "SELECT * FROM retegas_bacheca WHERE id_ordine='".$row["id_ordini"]."' AND id_utente='"._USER_ID."' AND code_uno='12';";
                    if($db->sql_numrows($db->sql_query($sql))==0){
                        $h_table.=  '<li>Ord. '.$row["id_ordini"].' <b>'.$row["descrizione_ordini"].'</b>, io ho gestito, lascio un <a href="'.$RG_addr["opinione_referente"].'?id_ordine='.$row["id_ordini"].'"><b>commento e un opinione</a>.</b></li>';
                        $riga++;
                        $quanti++;
                    }


                    break;
             }

          }


        }//end while




       if($quanti>0){
            return $quanti;
       }

       return 0;


}

//SERVIZIO
function rgw_retegas_comunica($site,$id_user){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_7";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="ReteDes.it comunica :";
$w->can_toggle = false;
$w->toggle_state ="show";
$w->content = main_render_messaggio_alla_nazione($id_user);

if($w->content==""){
    $w->rgw_class = "rg_widget rg_widget_alert not_sortable hidden";
}else{
    $w->rgw_class = "rg_widget rg_widget_alert not_sortable";
}
$w->footer = "";

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_retegas_comunica_alerts($site,$id_user){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "rgw_9";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="E' richiesta la tua attenzione:";
$w->can_toggle = false;
$w->toggle_state ="show";
$w->content = main_render_anomalie_utente($id_user);

if($w->content==""){
    $w->rgw_class = "rg_widget rg_widget_alert not_sortable hidden";
}else{
    $w->rgw_class = "rg_widget rg_widget_alert not_sortable";
}
$w->footer = "";

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}
function rgw_retegas_comunica_new_user($site,$id_user){

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Nome id del widget
$w_name = "";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Novità :";
$w->can_toggle = false;
$w->toggle_state ="show";
$w->content = "Aggiungi o togli elementi alla tua HomePage cliccando sul menu \"Personalizza questa pagina\", e riordina la loro disposizione trascinandoli con il mouse.";
if($w->content==""){
   unset ($w);
   return;
}

$w->rgw_class = "rg_widget rg_widget_alert not_sortable";
$w->footer = "";

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}


function rgw_grafico_utilizzo_sito($site){
global $db;
// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");
array_push($site->java_headers,"highcharts");

// Nome id del widget
$w_name = "rgw_5";


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

//--------------Costruzione Grafico
for($s=0;$s<50;$s++){
        $colo[$s]=random_color();
      }
      $sql1 = "SELECT
                    Count(maaking_users.id_gas) as Contgas,
                    maaking_users.id_gas,
                    retegas_gas.descrizione_gas
                    FROM  maaking_users
                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                    WHERE maaking_users.last_activity >=  DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)
                    GROUP BY maaking_users.id_gas
                    ORDER BY Contgas ASC";
      $res = $db->sql_query($sql1);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      while ($row = mysql_fetch_array($res)){
            $data_activity_1 .="{ name: '".str_replace("'","&apos;",$row["descrizione_gas"])."', y: ".$row["Contgas"].", color:'#".$colo[$row["id_gas"]]."'},
            ";
      }
      $data_activity_1 = rtrim($data_activity_1,", ");


      $sql2= "SELECT
                    Count(maaking_users.id_gas) as Contgas,
                    maaking_users.id_gas,
                    retegas_gas.descrizione_gas
                    FROM
                    maaking_users
                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                    WHERE
                    maaking_users.last_activity >=  DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                    GROUP BY
                    maaking_users.id_gas
                    ORDER BY Contgas ASC";
      $res = $db->sql_query($sql2);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      while ($row = $db->sql_fetchrow($res)){
            $data_activity_2 .="{ name: '".str_replace("'","&apos;",$row["descrizione_gas"])."', y: ".$row["Contgas"].", color: '#".$colo[$row["id_gas"]]."'},
            ";

      }
      $data_activity_2 = rtrim($data_activity_2,", ");



      $site->java_scripts_bottom_body[]="
          <script type=\"text/javascript\">

 $(document).ready(function() {

   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'container_chart',
         plotBackgroundColor: 'transparent',
         plotBorderWidth: 0,
         plotShadow: false,
         width:320,
         height:320,
         style: {
            margin: '0 auto'
         }
      },
      credits:{
                enabled:false
                },
      tooltip: {
         formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
               this.point.name +': '+ this.y +' accessi';
         }
      },
      title:{
         text :''
      },
       series: [{
         type: 'pie',
         name: 'Ultima settimana',
         size: '20%',
         innerSize: '5%',
         data: [".$data_activity_2."
         ],
         dataLabels: {
            enabled: false
         }
      }, {
         type: 'pie',
         allowPointSelect: true,
         name: 'Ultimo giorno',
         size: '50%',
         innerSize:'30%',
         data: [".$data_activity_1."],
         dataLabels: {
            enabled: true,
            connectorPadding:5,
            distance:10
         }
      }]
   });


});
          </script>
      ";
//------------------------------COSTRUZIONE GRAFICO



// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Utilizzo sito";
$w->toggle_state ="hide";
$w->content = '<div id="container_chart"></div>';
$w->footer = "Rappresentazione grafica degli ultimi accessi univoci al sito.";
$w->use_handler =false;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

function rgw_chat_old($site,$gas){

    global $user;
    global $RG_addr;

    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $options = leggi_opzioni_sito_utente($id_user);

    // Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
    array_push($site->css,"widgets_ui");

    if($options & opti::visibile_a_tutti){
          $site->java_scripts_bottom_body[]='<script type="text/javascript">
          $("#submitmsg").click(function(){
                var clientmsg = $("#usermsg").val();
                $.post("'.$RG_addr["ajax_post_chat"].'", {text: clientmsg});
                $("#usermsg").attr("value", "");
                return false;
            });
          function loadLog(){



             $.ajax({
                url: "'.$RG_addr["ajax_gasap_chat"].'",
                cache: false,
                success: function(html){
                    $("#chatbox").html(html);

                    //$("#chatbox").attr("scrollTop", $("#chatbox").attr("scrollHeight"));

                },
            });

          }
          setInterval (loadLog, 5000);

          </script>
          ';
      }

    // Nome id del widget
    $w_name = "rgw_8";


        if($options & opti::visibile_a_tutti){
        $form ='                    <form name="message" action="">

                                        <input name="usermsg" type="text" id="usermsg" size="40" />
                                        <input class="awesome medium green destra" name="submitmsg" type="submit"  id="submitmsg" value="Invia" />
                                    </form>';
       }else{
        $form ='Per poter partecipare alla chat, è necessario abilitare l\'opzione <b> visibile a tutti </b>. (menù Profilo - I miei dati)';

       }


          // qui ci va la pagina vera e proria
       $h  =  '<div>
                    '.$form.'
                    </div>
       <div id="chatbox"
                    style="  text-align:left;
                            margin-right:10px;
                            margin-bottom:15px;
                            padding:10px;

                            height:200px;
                            overflow:auto;">
                    '.$contents.'
                    </div>
                    ';


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Chat";
$w->toggle_state ="hide";
$w->content = $h;
$w->use_handler = false;
$w->footer = "Chat condivisa tra tutta ReteGas.AP";

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

function rgw_chat($site,$gas){

    global $user;
    global $RG_addr;

    $cookie_read     =explode("|", base64_decode($user));
    $id_user  = $cookie_read[0];
    $options = leggi_opzioni_sito_utente($id_user);

    // Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
    array_push($site->css,"widgets_ui");



    // Nome id del widget
    $w_name = "rgw_8";





          // qui ci va la pagina vera e proria
       $h  =  '';


// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();

// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Not Defined";
$w->toggle_state ="hide";
$w->content = $h;
$w->use_handler = false;
$w->footer = "Widget non ancora configurato";

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

function rgw_ordine_1($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 14;

//SETTO LE VARIABILI NECESSARIE
$site           = null; //il sito Retegas
//$id_ordine_1    = null; //Ordine oggetto
$id_user        =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
//if(is_empty($id_ordine_1)){return "<div clas=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"id_ordine\"";exit;};

// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");

// Negli array dei comandi java a fondo pagina inserisco le cose necessarie al widget
//$site->java_scripts_bottom_body[]='<script type="text/javascript">$("#'.$w_name.'").draggable({});</script>';

// istanzio un nuovo oggetto widget
$w = new rg_widget();



//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array("id_ordine_1"=>0,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array("id_ordine_1"=>"Codice dell'ordine da visualizzare",
                                "Aperto_al_caricamento"=>"Trova questo widget al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}






$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));
$id_ordine_1 = CAST_TO_INT($id_ordine_1);


//RICERCA
$w->search= '<span class="small_link" id="header_filter_'.$id_widget.'">Cerca tra gli utenti ...</span>';
$w->has_search = false;




// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="# " . $id_ordine_1 ."(".descrizione_ordine_from_id_ordine($id_ordine_1).")";
$w->content = ordine_schedina_widget($id_ordine_1);
$w->footer = "Totale: "._nf(valore_totale_ordine_qarr($id_ordine_1));
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}

//CASSA---------------------------------
function rgw_movimenti_cassa_utente($params){
global $RG_addr;

//ID_WIDGET
$id_widget = 13;

//SETTO LE VARIABILI NECESSARIE
$site   = null; //il sito Retegas
$id_user =null; //id user;

//Estraggo le variabili da params, ma solo quelle che esistono gi?
extract($params,EXTR_IF_EXISTS);

//Se mancano i paramtri avviso senza disegnare il widget
if(is_empty($id_user)){return "<div class=\"ui-state_alert\"><strong>$w_name : </strong>Parametro mancante \"id_user\"";exit;};

// Nome id del widget
$w_name = "rgw_".$id_widget;

// Nel caso non ci fossero altri widget inietto il comando per caricare il css dei widget nell'array della classe retegas
//array_push($site->css,"widgets_ui");



// istanzio un nuovo oggetto widget
$w = new rg_widget();



//Carico in settings l'array di settaggi personalizzati
$settings        = unserialize(base64_decode(read_option_text($id_user,"WGS_".$id_widget)));
$settings_labels = unserialize(base64_decode(read_option_text($id_user,"WGL_".$id_widget)));


//se non ci sono ancora i settings li creo
if($settings==""){
    $settings       =     array(//"N_Ordini_da_mostrare"=>20,
                                "Aperto_al_caricamento"=>"SI");
    write_option_text($id_user,"WGS_".$id_widget,base64_encode(serialize($settings)));
}
if($settings_labels==""){
    $settings_labels=     array(//"N_Ordini_da_mostrare"=>"Numero di ordini da visualizzare",
                                "Aperto_al_caricamento"=>"Trova questa lista aperta al caricamento (SI/NO)");
    write_option_text($id_user,"WGL_".$id_widget,base64_encode(serialize($settings_labels)));
}


$w->settings = rgw_widget_settings_render($id_widget,$settings,$settings_labels);

//Tiro fuori i settaggi
extract($settings);
$Aperto_al_caricamento = trim(CAST_TO_STRING($Aperto_al_caricamento));






// Imposto le propriet? del widget
$w->name = $w_name;
$w->title="Movimenti di cassa";
$w->content = cassa_mov_raggr_utente_widget($id_user);
$w->footer = "Saldo disponibile: ".cassa_saldo_utente_totale($id_user);
$w->use_handler =false;

if($Aperto_al_caricamento=="SI"){

    $w->toggle_state="show";
}else{
    $w->toggle_state="hide";
}

$w->has_settings=true;

// Eseguo il rendering
$h = $w->rgw_render();

// Distruggo il widget
unset($w);

//Ritorno l'HTML
return $h;


}




function rgw_widget_render($site,$widget_number,$id_user=null,$id_gas=null,$id_ordine=null){


    switch ($widget_number){


       case 1:
            return rgw_ordini_chiusi($site,$id_gas);
            break;
       case 2:
            return rgw_ordini_aperti($site,$id_gas);
            break;
       case 3:
            $params = array("site"=>$site,
                            "gas"=>$id_gas);
            return rgw_ordini_futuri($params);
            break;
       case 4:
            return rgw_bacheca($site,$id_gas);
            break;
       case 5:
            return rgw_grafico_utilizzo_sito($site);
            break;
       case 6:
            return rgw_utenti_online($site,$id_gas);
            break;
       case 7:
            return rgw_retegas_comunica($site,$id_user);
            break;
       case 8:
            return rgw_chat($site,$id_gas);
            break;
       case 9:
            return rgw_retegas_comunica_alerts($site,$id_user);
            break;
       case 10:
            $params = array("site"=>$site,
                            "gas"=>$id_gas,
                            "id_user"=>$id_user);
            return rgw_ordini_tutti($params);
            break;
       case 11:
            $params = array("site"=>$site,
                            "gas"=>$id_gas,
                            "id_user"=>$id_user);
            return rgw_ordini_io_coinvolto($params);
            break;
       case 12:
            $params = array("site"=>$site,
                            "gas"=>$id_gas,
                            "id_user"=>$id_user);
            return rgw_utenti_mio_gas($params);
            break;
       case 13:
            $params = array("site"=>$site,
                            "id_user"=>$id_user);
            return rgw_movimenti_cassa_utente($params);
            break;
       case 14:
       $params = array("site"=>$site,
                       "id_user"=>$id_user);
            return rgw_ordine_1($params);
            break;
       case 15:
       $params = array("site"=>$site,
                        "gas"=>$id_gas,
                       "id_user"=>$id_user);
            return rgw_ids($params);
            break;

       Default:

       Break;
       }

}
function rgw_widget_settings_render($id_widget,$settings,$settings_labels){
global $RG_addr;

//Costruisco la form con i settings

$f .= '<form id="settings_for_widget'.$id_widget.'" action="'.$RG_addr["ajax_widget_settings"].'">';
foreach($settings as $key=>$value){

$f .= '<label for="'.$key.'">'.$settings_labels[$key].'</label>
       <input type="text" name="'.$key.'" value="'.$value.' "size=5>
       <br>';
}

$f .= '<input type="hidden" name="id_widget" value="'.$id_widget.'">';
$f .= '<label for="do_set_widget_'.$id_widget.'">&nbsp</label><br>';
$f.= ' <input class="awesome green small" id="do_set_widget_'.$id_widget.'" type="submit" value="Salva !!"/>';
$f.= '</form>';

return $f;

}

?>