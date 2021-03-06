<?php
if (stristr($_SERVER['PHP_SELF'], "panoramica_functions.php")) {
	Header("Location: ../index.php");
	die();
}

function create_timeline($id_user){
global $db;
global $RG_addr;
//echo "ID USER = ".$id_user;

$query = "SELECT * from retegas_ordini ORDER BY retegas_ordini.data_chiusura DESC LIMIT 100";
$ret = $db->sql_query($query);

//{     "start": "2010-03-10T06:00:00+00:00",
 //     "end": "2010-03-31T22:00:00+00:00",
  //                          "instant": false,
  //                            "title": "1",
   //                           "color": "#7FFFD4",
	//                      "textColor": "#000000",
	 //                       "caption": "1",
	  //                     "trackNum": 1,
	   //                   "classname": "",
		//                "description": "bar 1"},

$index=0;
$total_index=0;

date_default_timezone_set('Europe/Rome');

$tl ='<script type="text/javascript">
	var tl;
	';
$tl.='var event_data = 
{
			  "dateTimeFormat": "rfc2822",
			  "events":[ 
			  ';
// EVENTI da ORDINI, ricordati la virgola                      
while ($row=mysql_fetch_array($ret)){
$co="";

$cosa_sono = ordine_io_cosa_sono($row[0],$id_user);

if($cosa_sono==0){
    $mess_cs = "Non Partecipo";
    $mio_valore =0;
}


if($cosa_sono==1){
$mess_cs = "Non Partecipo";
}
if($cosa_sono==2){

$mio_valore = number_format(valore_totale_mio_ordine($row[0],$id_user),2,",","");
$mess_cs = "Sto Partecipando ($mio_valore Eu.)";
}
if($cosa_sono==3){
$mess_cs = "Sono REFERENTE GAS";
}
if($cosa_sono==4){

$mio_valore = number_format(valore_totale_ordine_qarr($row[0]),2,",","");
$mess_cs = "Sono il REFERENTE ORDINE,<br> che vale $mio_valore Eu.";  
}


if(id_gas_user($id_user) == id_gas_user(id_referente_ordine_proprio_gas($row[0],_USER_ID_GAS))){
   //ECHO "COSA SONO ord $row[0] utente $id_user".ordine_io_cosa_sono($row[0],$id_user)."<br>";

		$color="#8FF951";
        //$color="rgba(30,170,10,0.4)";
		$stato = " (APERTO)";
		$link = '<a class="small awesome green destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA LA SCHEDA</a><br>';  
		
		if(gas_mktime(conv_date_from_db($row[6]))>gas_mktime(date("d/m/Y H:i"))){
		$color="#80FFFF";
		//$color="rgba(10,20,170,0.4)";
        $stato = " (FUTURO - in attesa di apertura)";
		$link = '<a class="small awesome celeste destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA LA SCHEDA</a><br>';  
  
		}        
		if(gas_mktime(conv_date_from_db($row[7]))<gas_mktime(date("d/m/Y H:i"))){
		$color="#ff5c00";
		//$color="rgba(170,20,10,0.4)";
        $stato = " (CHIUSO - in attesa di convalida)";
		$link = '<a class="small awesome orange destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA LA SCHEDA</a><br>';  
		}
		if($row[17]==1){
		$color="#ACACAC";
		//$color="rgba(50,50,50,0.4)";
        $stato = " (CHIUSO - convalidato)";
		$link = '<a class="small awesome silver destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA LA SCHEDA</a><br>';  
		}
		 
		$propo = fullname_referente_ordine_globale($row[0]);
		$tipo = tipologia_nome_from_listino($row[1]);
		
        $de = 'Proponente : <b><a href="#">'.$propo.'</a></b><br>';

        $de .= 'Tipologia  : <b>'.$tipo.'</b><br>';
		$de .= $mess_cs.'<br>';
		$de.= $link;
		
		$de = str_replace("<","&lt;",$de);
		$de = str_replace(">","&gt;",$de);
		$de = str_replace('"',"&quot;",$de);
        
        $capt = html_entity_decode($row[3]);
        $capt = str_replace('"',"&quot",$row[3]);
        
		//$de.= 'Link : \<a href="#"\> Visualizza\<\/a\>\<br\>';
					
		if($total_index>0){
		$co .= ',
		';    
		}
		$index++;
		$total_index++;

		//echo $row[7]."<br>".date("c",strtotime($row[6])))." <br> ";

		$co .= '{    "start": "'.date("r",strtotime($row[6])).'",
		';
		$co .= '    "end": "'.date("r",strtotime($row[7])).'",
		';
		$co .='     "instant": false,
		';
		$co .='     "title": "'.$capt.$stato.'",
		';    
		$co .='     "color": "'.$color.'",
		';
		$co .='     "textColor": "#000000",
		';
		$co .='     "caption": "'.$capt.'",
		';
		//$co .='     "trackNum": "'.$index.'",
		//';
		$co .='     "classname": "ui-corner-all barretta",
		';
		$co .='     "description": "'.$de.'"}
		';    
		if($index>12){$index=0;}
		$tl .=$co;
		}//                             IO COSA SONO

}

$tl.='         ]
			  };
			  ';
$tl.=' 
	function onLoad() {
	var eventSource = new Timeline.DefaultEventSource();

   
   var theme = Timeline.ClassicTheme.create(); // create the theme
			
			theme.ether.backgroundColors[0] = "#222";
			theme.ether.backgroundColors[1] = "#AA0";
			theme.event.bubble.width = 240;   // modify it
			theme.event.bubble.height = 320;
			theme.event.tape.height = 16;
			theme.timeline_start = new Date(Date.UTC(2012, 1, 1));
   
   var bandInfos = [

	
   
	 Timeline.createBandInfo({
		
		 
		   
		 width:          "15%", 
		 intervalUnit:   Timeline.DateTime.MONTH, 
		 intervalPixels: 200
		 
		 
		 
	 }),
	 Timeline.createBandInfo({
		 
		 timeZone:       1,
		 eventSource:    eventSource,
		 width:          "85%", 
		 intervalUnit:   Timeline.DateTime.DAY, 
		 intervalPixels: 50,
		 theme: theme
	 }),
	 ];
	bandInfos[0].syncWith = 1;

	bandInfos[0].highlight = true;
   
	bandInfos[1].decorators = [
					new Timeline.SpanHighlightDecorator({
						startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
						endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
						color:      "#FFC080",
						opacity:    50,

					   cssClass: "t-highlight1"
					})                    
				];
	bandInfos[0].decorators = [
					new Timeline.SpanHighlightDecorator({
						startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
						endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
						color:      "#FFC080",
						opacity:    100,
						startLabel: "ordini passati...",
						endLabel:   "ordini futuri...",
					   // theme:      theme,
					   cssClass: "t-highlight1"
					})                    
				];
   
   
   

   tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);

   eventSource.loadJSON(event_data, document.location.href);
   
}

 
 var resizeTimerID = null;
 function onResize() {
	 if (resizeTimerID == null) {
		 resizeTimerID = window.setTimeout(function() {
			 resizeTimerID = null;
			 tl.layout();
		 }, 500);
	 }
 }
 </script>
 <style>
 .timeline-event-label{
		margin-top:-4px;
		margin-left:5px;
		background:none;
		text-decoration: none;
		font-weight:normal;
        font-size:.96em;
		line-height: 1;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		-moz-box-shadow:none;
		-webkit-box-shadow: none;
		text-shadow:none;
		border-bottom: none;
		}
 .timeline-highlight-label {margin-top:0px; }
 
 .t-highlight1 { background-color: #ccf; font-size:1em;}
 .p-highlight1 { background-color: #fcc; }
 
 .timeline-highlight-label-start .label_t-highlight1 { color: #800000; margin-top:-16px;  }
 .timeline-highlight-label-end .label_t-highlight1 { color: #0080FF; margin-top:-16px;  }
  
 .timeline-band-events .important { color: #f00; }
 .timeline-band-events .small-important { background: #c00; }       
</style>        
   ';              
			  
			  
// .timeline-band{padding-top:1em;}
// .timeline-band-layer-inner{height:95%;}
// .timeline-container{padding:1em;}              
	
	
	
return $tl;    
}
function create_timeline_anno($id_user){
global $db;
global $RG_addr;
//echo "ID USER = ".$id_user;

$query = "SELECT * from retegas_ordini ORDER BY retegas_ordini.data_chiusura DESC LIMIT 500;";
$ret = $db->sql_query($query);



$index=0;
$total_index=0;

date_default_timezone_set('Europe/Rome');

$tl ='<script type="text/javascript">
	var tl;
	';
$tl.='var event_data = 
{
			  "dateTimeFormat": "rfc2822",
			  "events":[ 
			  ';
// EVENTI da ORDINI, ricordati la virgola                      
while ($row=mysql_fetch_array($ret)){
$co="";

$cosa_sono = ordine_io_cosa_sono($row[0],$id_user);

if($cosa_sono==0){
    $mess_cs = "Non Partecipo";
    $mio_valore =0;
}


if($cosa_sono==1){
$mess_cs = "Non Partecipo";
}
if($cosa_sono==2){

$mio_valore = number_format(valore_totale_mio_ordine($row[0],$id_user),2,",","");
$mess_cs = "Sto Partecipando ($mio_valore Eu.)";
}
if($cosa_sono==3){
$mess_cs = "Sono REFERENTE GAS";
}
if($cosa_sono==4){

$mio_valore = number_format(valore_totale_ordine_qarr($row[0]),2,",","");
$mess_cs = "Sono il REFERENTE ORDINE, che vale $mio_valore Eu.";  
}

if(id_gas_user($id_user) == id_gas_user(id_referente_ordine_proprio_gas($row[0],_USER_ID_GAS))){
   //ECHO "COSA SONO ord $row[0] utente $id_user".ordine_io_cosa_sono($row[0],$id_user)."<br>";

		$color="#8FF951";
		$stato = " (APERTO)";
		$link = '<a class="small awesome green destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
		
		if(gas_mktime(conv_date_from_db($row[6]))>gas_mktime(date("d/m/Y H:i"))){
		$color="#80FFFF";
		$stato = " (FUTURO - in attesa di apertura)";
        $link = '<a class="small awesome celeste destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
 
		}        
		if(gas_mktime(conv_date_from_db($row[7]))<gas_mktime(date("d/m/Y H:i"))){
		$color="#ff5c00";
		$stato = " (CHIUSO - in attesa di convalida)";
		$link = '<a class="small awesome orange destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
		}
		if($row[17]==1){
		$color="#ACACAC";
		$stato = " (CHIUSO - convalidato)";
		$link = '<a class="small awesome silver destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
		}
		 
		$propo = fullname_referente_ordine_globale($row[0]);
		$tipo = tipologia_nome_from_listino($row[1]);
		
		$de = 'Proponente : <b><a href="#">'.$propo.'</a></b><br>';
        
        $de .= 'Tipologia  : <b>'.$tipo.'</b><br>';
		$de .= $mess_cs.'<br>';
		$de.= $link;
		
		$de = str_replace("<","&lt;",$de);
		$de = str_replace(">","&gt;",$de);
		$de = str_replace('"',"&quot;",$de);
		
        $capt = html_entity_decode($row[3]);
        $capt = str_replace('"',"&quot;",$row[3]);
        
        //$de.= 'Link : \<a href="#"\> Visualizza\<\/a\>\<br\>';
					
		if($total_index>0){
		$co .= ',
		';    
		}
		$index++;
		$total_index++;

		//echo $row[7]."<br>".date("c",strtotime($row[6])))." <br> ";

		$co .= '{    "start": "'.date("r",strtotime($row[6])).'",
		';
		$co .= '    "end": "'.date("r",strtotime($row[7])).'",
		';
		$co .='     "instant": false,
		';
		$co .='     "title": "'.substr($capt,0,15).'...",
		';    
		$co .='     "color": "'.$color.'",
		';
		$co .='     "textColor": "#000000",
		';
		$co .='     "caption": "'.$capt.'",
		';
		//$co .='     "trackNum": "'.$index.'",
		//';
		$co .='     "classname": "barretta_piccola",
		';
		$co .='     "description": "'.$de.'"}
		';    
		if($index>12){$index=0;}
		$tl .=$co;
		}//                             IO COSA SONO

}

$tl.='         ]
			  };
			  ';
$tl.=' 
	function onLoad() {
	var eventSource = new Timeline.DefaultEventSource();

   
   var theme = Timeline.ClassicTheme.create(); // create the theme
			
			theme.ether.backgroundColors[0] = "#222";
			theme.ether.backgroundColors[1] = "#AA0";
			theme.event.bubble.width = 240;   // modify it
			theme.event.bubble.height = 320;
			theme.event.tape.height = 8;
			theme.timeline_start = new Date(Date.UTC(2010, 1, 1));
   
   var bandInfos = [

	
   
	 Timeline.createBandInfo({
		
		 
		   
		 width:          "15%", 
		 intervalUnit:   Timeline.DateTime.YEAR, 
		 intervalPixels: 300
		 
		 
		 
	 }),
	 Timeline.createBandInfo({
		 
		 timeZone:       1,
		 eventSource:    eventSource,
		 width:          "85%", 
		 intervalUnit:   Timeline.DateTime.MONTH, 
		 intervalPixels: 100,
		 theme: theme
	 }),
	 ];
	bandInfos[0].syncWith = 1;

	bandInfos[0].highlight = true;
   
	bandInfos[1].decorators = [
					new Timeline.SpanHighlightDecorator({
						startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
						endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
						color:      "#FFC080",
						opacity:    50,
						//startLabel: "il Passato...",
						//endLabel:   "...il Futuro",
					   // theme:      theme,
					   cssClass: "t-highlight1"
					})                    
				];
	bandInfos[0].decorators = [
					new Timeline.SpanHighlightDecorator({
						startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
						endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
						color:      "#FFC080",
						opacity:    100,
						startLabel: "ordini passati...",
						endLabel:   "ordini futuri...",
					   // theme:      theme,
					   cssClass: "t-highlight1"
					})                    
				];
   
   
   

   tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);

   eventSource.loadJSON(event_data, document.location.href);
   
}

 
 var resizeTimerID = null;
 function onResize() {
	 if (resizeTimerID == null) {
		 resizeTimerID = window.setTimeout(function() {
			 resizeTimerID = null;
			 tl.layout();
		 }, 500);
	 }
 }
 </script>
 <style>
 .timeline-event-label{
		margin-top:-4px;
		margin-left:0;
		background:none;
		text-decoration: none;
		font-weight:normal;
		line-height: 1;
		font-size :0.86em;
		-moz-border-radius: 0px;
		-webkit-border-radius: 0px;
		-moz-box-shadow:none;
		-webkit-box-shadow: none;
		text-shadow:none;
		border-bottom: none;
		}
  .timeline-highlight-label {margin-top:0px; }
 
 .t-highlight1 { background-color: #ccf; }
 .p-highlight1 { background-color: #fcc; }
 
 .timeline-highlight-label-start .label_t-highlight1 { color: #800000; margin-top:-6px;  }
 .timeline-highlight-label-end .label_t-highlight1 { color: #0080FF; margin-top:-6px;  }
  
 .timeline-band-events .important { color: #f00; }
 .timeline-band-events .small-important { background: #c00; }       
</style>        
   ';              
			  
			  
// .timeline-band{padding-top:1em;}
// .timeline-band-layer-inner{height:95%;}
// .timeline-container{padding:1em;}              
	
	
	
return $tl;    
}
function create_timeline_anno_des($id_user){
global $db;
global $RG_addr;
//echo "ID USER = ".$id_user;

$query = "SELECT * from retegas_ordini ORDER BY retegas_ordini.data_chiusura DESC LIMIT 500;";
$ret = $db->sql_query($query);



$index=0;
$total_index=0;

date_default_timezone_set('Europe/Rome');

$tl ='<script type="text/javascript">
    var tl;
    ';
$tl.='var event_data = 
{
              "dateTimeFormat": "rfc2822",
              "events":[ 
              ';
// EVENTI da ORDINI, ricordati la virgola                      
while ($row=mysql_fetch_array($ret)){
$co="";

$cosa_sono = ordine_io_cosa_sono($row[0],$id_user);

if($cosa_sono==0){
    $mess_cs = "Non Partecipo";
    $mio_valore =0;
}


if($cosa_sono==1){
$mess_cs = "Non Partecipo";
}
if($cosa_sono==2){

$mio_valore = number_format(valore_totale_mio_ordine($row[0],$id_user),2,",","");
$mess_cs = "Sto Partecipando ($mio_valore Eu.)";
}
if($cosa_sono==3){
$mess_cs = "Sono REFERENTE GAS";
}
if($cosa_sono==4){

$mio_valore = number_format(valore_totale_ordine_qarr($row[0]),2,",","");
$mess_cs = "Sono il REFERENTE ORDINE, che vale $mio_valore Eu.";  
}

//if(id_gas_user($id_user) == id_gas_user(id_referente_ordine_proprio_gas($row[0],_USER_ID_GAS))){
   //ECHO "COSA SONO ord $row[0] utente $id_user".ordine_io_cosa_sono($row[0],$id_user)."<br>";

        $color="#8FF951";
        $stato = " (APERTO)";
        $link = '<a class="small awesome green destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
        
        if(gas_mktime(conv_date_from_db($row[6]))>gas_mktime(date("d/m/Y H:i"))){
        $color="#80FFFF";
        $stato = " (FUTURO - in attesa di apertura)";
        $link = '<a class="small awesome celeste destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
 
        }        
        if(gas_mktime(conv_date_from_db($row[7]))<gas_mktime(date("d/m/Y H:i"))){
        $color="#ff5c00";
        $stato = " (CHIUSO - in attesa di convalida)";
        $link = '<a class="small awesome orange destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
        }
        if($row[17]==1){
        $color="#ACACAC";
        $stato = " (CHIUSO - convalidato)";
        $link = '<a class="small awesome silver destra" style="margin:6px;" href="'.$RG_addr["ordini_form"].'?id_ordine='.$row[0].'">VISUALIZZA</a><br>';  
        }
         
        //$propo = fullname_referente_ordine_globale($row[0]);
        $propo = gas_nome(id_gas_user(id_referente_ordine_globale($row[0])));
        $tipo = tipologia_nome_from_listino($row[1]);
        
        $de = 'Proponente : '.$propo.'<br>';
        
        $de .= 'Tipologia  : <b>'.$tipo.'</b><br>';
        $de .= $mess_cs.'<br>';
        
        // per gli ordini globali non mostro il link
        //$de.= $link;
        
        $de = str_replace("<","&lt;",$de);
        $de = str_replace(">","&gt;",$de);
        $de = str_replace('"',"&quot;",$de);
        
        $capt = html_entity_decode($row[3]);
        $capt = str_replace('"',"&quot;",$row[3]);
        
        //$de.= 'Link : \<a href="#"\> Visualizza\<\/a\>\<br\>';
                    
        if($total_index>0){
        $co .= ',
        ';    
        }
        $index++;
        $total_index++;

        //echo $row[7]."<br>".date("c",strtotime($row[6])))." <br> ";

        $co .= '{    "start": "'.date("r",strtotime($row[6])).'",
        ';
        $co .= '    "end": "'.date("r",strtotime($row[7])).'",
        ';
        $co .='     "instant": false,
        ';
        $co .='     "title": "'.substr($capt,0,15).'...",
        ';    
        $co .='     "color": "'.$color.'",
        ';
        $co .='     "textColor": "#000000",
        ';
        $co .='     "caption": "'.$capt.'",
        ';
        //$co .='     "trackNum": "'.$index.'",
        //';
        $co .='     "classname": "barretta_piccola",
        ';
        $co .='     "description": "'.$de.'"}
        ';    
        if($index>12){$index=0;}
        $tl .=$co;
        //}//                             IO COSA SONO -- DA INSERIRE CONTROLLO DES

}

$tl.='         ]
              };
              ';
$tl.=' 
    function onLoad() {
    var eventSource = new Timeline.DefaultEventSource();

   
   var theme = Timeline.ClassicTheme.create(); // create the theme
            
            theme.ether.backgroundColors[0] = "#222";
            theme.ether.backgroundColors[1] = "#AA0";
            theme.event.bubble.width = 240;   // modify it
            theme.event.bubble.height = 320;
            theme.event.tape.height = 8;
            theme.timeline_start = new Date(Date.UTC(2010, 1, 1));
   
   var bandInfos = [

    
   
     Timeline.createBandInfo({
        
         
           
         width:          "15%", 
         intervalUnit:   Timeline.DateTime.YEAR, 
         intervalPixels: 300
         
         
         
     }),
     Timeline.createBandInfo({
         
         timeZone:       1,
         eventSource:    eventSource,
         width:          "85%", 
         intervalUnit:   Timeline.DateTime.MONTH, 
         intervalPixels: 100,
         theme: theme
     }),
     ];
    bandInfos[0].syncWith = 1;

    bandInfos[0].highlight = true;
   
    bandInfos[1].decorators = [
                    new Timeline.SpanHighlightDecorator({
                        startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
                        endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
                        color:      "#FFC080",
                        opacity:    50,
                        //startLabel: "il Passato...",
                        //endLabel:   "...il Futuro",
                       // theme:      theme,
                       cssClass: "t-highlight1"
                    })                    
                ];
    bandInfos[0].decorators = [
                    new Timeline.SpanHighlightDecorator({
                        startDate: "'.date("r",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))).'"  ,
                        endDate:   "'.date("r",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))).'"  ,
                        color:      "#FFC080",
                        opacity:    100,
                        startLabel: "ordini passati...",
                        endLabel:   "ordini futuri...",
                       // theme:      theme,
                       cssClass: "t-highlight1"
                    })                    
                ];
   
   
   

   tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);

   eventSource.loadJSON(event_data, document.location.href);
   
}

 
 var resizeTimerID = null;
 function onResize() {
     if (resizeTimerID == null) {
         resizeTimerID = window.setTimeout(function() {
             resizeTimerID = null;
             tl.layout();
         }, 500);
     }
 }
 </script>
 <style>
 .timeline-event-label{
        margin-top:-4px;
        margin-left:0;
        background:none;
        text-decoration: none;
        font-weight:normal;
        line-height: 1;
        font-size :0.86em;
        -moz-border-radius: 0px;
        -webkit-border-radius: 0px;
        -moz-box-shadow:none;
        -webkit-box-shadow: none;
        text-shadow:none;
        border-bottom: none;
        }
  .timeline-highlight-label {margin-top:0px; }
 
 .t-highlight1 { background-color: #ccf; }
 .p-highlight1 { background-color: #fcc; }
 
 .timeline-highlight-label-start .label_t-highlight1 { color: #800000; margin-top:-6px;  }
 .timeline-highlight-label-end .label_t-highlight1 { color: #0080FF; margin-top:-6px;  }
  
 .timeline-band-events .important { color: #f00; }
 .timeline-band-events .small-important { background: #c00; }       
</style>        
   ';              
              
              
// .timeline-band{padding-top:1em;}
// .timeline-band-layer-inner{height:95%;}
// .timeline-container{padding:1em;}              
    
    
    
return $tl;    
}

?>