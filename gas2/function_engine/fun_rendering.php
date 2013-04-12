<?php

//CONVERSIONE ACCENTI
function sistema_accenti($h){
$conversion_chars = array (    "à" => "&agrave;", 
                               "è" => "&egrave;", 
                               "é" => "&egrave;", 
                               "ì" => "&igrave;", 
                               "ò" => "&ograve;", 
                               "ù" => "&ugrave;",
                               "°" => "&#176;",
                               "&" => "&#38;"); 
$h = str_replace (array_keys ($conversion_chars), array_values($conversion_chars), $h);
return $h;
}

//STYLESHEETS
function c1_ext_stylesheets(){

global $RG_addr;
    
$h ="<link type=\"text/css\" href=\"".$RG_addr["css_jquery_ui"]."\" rel=\"Stylesheet\" />
";
$h.="<link type=\"text/css\" href=\"".$RG_addr["css_layer_sito"]."\" rel=\"Stylesheet\" />
";
 $h.="<link type=\"text/css\" href=\"".$RG_addr["css_tabelle"]."\" rel=\"Stylesheet\" />
";
 $h.="<!--[if !IE]><!--><link type=\"text/css\" href=\"".$RG_addr["css_awesome"]."\" rel=\"Stylesheet\" /><!--<![endif]-->
";

$h.="<!--[if IE]><link rel=\"stylesheet\" href=\"".$RG_addr["css_tabelle_ie"]."\" type=\"text/css\" /><![endif]-->
 ";
return $h; 
//<!--[if !IE]><!--><link type=\"text/css\" href=\"/gas2/css/beta2/jdMenu.slate.css\" rel=\"Stylesheet\" /><!--<![endif]--> 

   
}
function fg_css(){
global $RG_addr;    
$h ="<link type=\"text/css\" href=\"".$RG_addr["css_fg_menu"]."\" media=\"screen\" rel=\"stylesheet\">";

return $h; 

   
}
function css_pdf(){

global $RG_addr;
    
$h ="<link type=\"text/css\" href=\"".$RG_addr["css_jquery_ui"]."\" rel=\"Stylesheet\" />
";
$h.="<link type=\"text/css\" href=\"".$RG_addr["css_layer_sito"]."\" rel=\"Stylesheet\" />
";
 $h.="<link type=\"text/css\" href=\"".$RG_addr["css_tabelle"]."\" rel=\"Stylesheet\" />
";

return $h; 
//<!--[if !IE]><!--><link type=\"text/css\" href=\"/gas2/css/beta2/jdMenu.slate.css\" rel=\"Stylesheet\" /><!--<![endif]--> 

   
}
//STYLESHEETS 



//HEADER PER PDF E RAW HTML
function load_pdf_header($img_path){
$h = "
<table>
<tr>
    <td width=\"50%\" align=\"left\">
        <a href=\"\">
        <img align=\"left\" src=\"$img_path\" border=\"0\" width=\"300\" height=\"75\" alt=\"ReteDES.it\">
        </a>
    </td>
    <td style=\"padding-right:10px; text-align:right\">";

$h.="Rete dei GAS dell'Alto Piemonte<br />
    <span class=\"small_link \">da un'idea ed un progetto
    del GAS Borgomanero</span><br /> 
    <span class=\"small_link\">ReteDES.it@gmail.com</span><br />
    <span class=\"small_link \">Sviluppo :</span>ma.morez@tiscali.it<br />
    ";
                       
$h.="</td>
    </tr>
</table>
<hr>
";

return $h;
}
function load_pdf_styles($prefix){
    return "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
            <link type=\"text/css\" href=\"".$prefix."my_v3.css\" rel=\"Stylesheet\" />
            <link type=\"text/css\" href=\"".$prefix."tables.css\" rel=\"Stylesheet\" />
            <style type=\"text/css\">
                body {padding:10px;margin:10px;font-size:11px;}
                table {font-size:10px;}
                .column_hide{display:none;}
                .titolino td{background:#ACACAC;text-align:left}
                .scheda th{background:#E0E0E0;text-align:left}
            </style>";
}

//WORD EXPORT
function word_export($content,$filename){
//$content = "This is test page";

// Size ? Denotes A4, Legal, A3, etc ??- size:8.5in 11.0in; for Legal size
// Margin ? Set the margin of the word document ? margin:0.5in 0.31in 0.42in 0.25in; [margin: top right bottom left]

$word_xmlns = "xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'";
$word_xml_settings = "<xml><w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom></w:WordDocument></xml>";
$word_landscape_style = "@page {size:8.5in 11.0in; margin:0.5in 0.31in 0.42in 0.25in;} div.Section1{page:Section1;}";
$word_landscape_div_start = "<div class='Section1'>";
$word_landscape_div_end = "</div>";
$content = '
            <html '.$word_xmlns.'>
            <head>'.$word_xml_settings.'<style type="text/css">
            '.$word_landscape_style.' table,td {border:0px solid #FFFFFF;} </style>
            </head>
            <body>'.$word_landscape_div_start.$content.$word_landscape_div_end.'</body>
            </html>
            ';

@header('Content-Type: application/msword');
@header('Content-Length: '.strlen($content));
@header('Content-disposition: inline; filename="'.$filename.'"'); 
echo $content;    
die();    
}


//HEADER JAVASCRIPT
function java_head_jquery(){
global $RG_addr;    
return  "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery"]."\"></script>
";
        
}
function java_head_jquery_ui_1_8_5(){
global $RG_addr;
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_ui"]."\"></script>
";
        
}
function java_head_jquery_tooltip(){
return    "<script type=\"text/javascript\" src=\"/gas2/js/jquery.tooltip.js\"></script>
";
        
}
function java_head_jquery_datepicker(){
global $RG_addr;    
    
return      "<script type=\"text/javascript\" src=\"".$RG_addr["js_datepicker_loc"]."\"></script>
";
}
function java_head_jquery_superfish(){
Global $RG_addr;    
    
return '<script type="text/javascript" src="'.$RG_addr["js_superfish_hoverintent"].'"></script>
        <script type="text/javascript" src="'.$RG_addr["js_superfish_bigframe"].'"></script>
        <script type="text/javascript" src="'.$RG_addr["js_superfish"].'"></script>
        <link rel="stylesheet" type="text/css" href="'.$RG_addr["css_superfish"].'" media="screen">
        '; 
}
function java_head_jquery_qtip(){
    global $RG_addr;
    return '
    <script type="text/javascript" src="'.$RG_addr["js_qtip"].'"></script>
    ';
}
function java_head_jquery_progressbar(){
    //NUOVA PROGRESSBAR    
return "<script type=\"text/javascript\" src=\"/gas2/js/jquery.progressbar/js/jquery.progressbar.js\"></script>
"; 
}
function java_head_jquery_accordion(){
global $RG_addr;    
    //HEAD ACCORDION   
return      "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_accordion"]."\"></script>
";
}
function java_head_jquery_ui_widgets(){
    //HEAD ACCORDION   
return      "<script type=\"text/javascript\" src=\"/gas2/js/jquery.ui.widget.js\"></script>
";
}
function java_head_jquery_tablesorter(){
    global $RG_addr;   
    return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_tablesorter"]."\"></script>
    ";       
}
function java_head_jquery_sparkline(){
    global $RG_addr;
    return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_sparkline"]."\"></script>
    "; 
      
}
function java_head_jquery_progression(){
global $RG_addr; 
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_progression"]."\"></script>
          <style>
          .progressbar {
                            border: 0;
                            width: 8em;
                            height: 3px;
                            line-height: 3px;
                            text-align: right;
                            aTextColor: '#FFFFFF';
                            }
         </style>                   
"; 
      
}
function java_head_jquery_metadata(){
global $RG_addr;  
    return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_metadata"]."\"></script>                  
    "; 
      
}
function java_head_jquery_tablegroup(){
global $RG_addr;  
return   "
<script type=\"text/javascript\" src=\"".$RG_addr["js_tablegroup"]."\"></script>                  
<script type=\"text/javascript\" src=\"".$RG_addr["js_tablesorter_pack"]."\"></script>                  
"; 
      
}
function java_head_jquery_jcarousel(){
global $RG_addr;  
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_jcarousel"]."\"></script>                  
          <link rel=\"stylesheet\" type=\"text/css\" href=\"".$RG_addr["css_jcarousel"]."\" />                  
          "; 
      
}
function java_head_jquery_iphone_switch(){
global $RG_addr;  
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_iphone-switch"]."\"></script>                  
          "; 
      
}
function java_head_jquery_dirtyform(){
global $RG_addr;  
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_dirtyform"]."\"></script>                  
          "; 
      
}

function java_head_ckeditor(){
global $RG_addr;   
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_ckeditor"]."\"></script>
"; 
      
}
function java_head_datetimepicker(){
global $RG_addr;  
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_datetimepicker"]."\"></script>
          <script type=\"text/javascript\" src=\"".$RG_addr["js_datepicker_loc"]."\"></script>
"; 
      
}
function java_head_fg_menu(){
global $RG_addr;
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_fg_menu"]."\"></script>
"; 
      
}
function java_head_highcharts(){
global $RG_addr;    
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_highcharts"]."\"></script>
          <script type=\"text/javascript\" src=\"".$RG_addr["js_highcharts_export"]."\"></script>  
"; 
      
}
function java_head_highstocks(){
global $RG_addr;    
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_highstocks"]."\"></script>
          <script type=\"text/javascript\" src=\"".$RG_addr["js_highstocks_export"]."\"></script>  
"; 
      
}
function java_head_table2csv(){
global $RG_addr;
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_table2csv"]."\"></script>
"; 
      
}
function java_head_jeditable(){
global $RG_addr;
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_jeditable"]."\"></script>
"; 
      
}
function java_head_rateit(){
global $RG_addr;
return   "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$RG_addr["css_rateit"]."\" media=\"screen\">
          <script type=\"text/javascript\" src=\"".$RG_addr["js_rateit"]."\"></script>
"; 
      
}
function java_head_select2(){
global $RG_addr;
return   "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$RG_addr["css_select2"]."\" media=\"screen\">
          <script type=\"text/javascript\" src=\"".$RG_addr["js_select2"]."\"></script>
"; 
      
}
function java_head_oms(){
global $RG_addr;
return   "<script type=\"text/javascript\" src=\"".$RG_addr["js_oms"]."\"></script>"; 
      
}

//HEADER JAVASCRIPT 

// JAVASCRIPT                          
function java_tooltip(){
$h = "
<script type=\"text/javascript\">
$(document).ready(function() {
    $(\"#$id_tooltip\").tooltip({cssClass:\"$class_tooltip\"});
});
</script>
";    
    
return $h;    
}
function java_datepicker($reference,$opt1=null,$opt2=null){
// DATEPICKER CON Referenza e Opzioni
// $('#datepicker').datepicker({minDate: 0});  
$output_html .= "<script type=\"text/javascript\">
    $(function() {
        $opt1
        $opt2        
        $('#$reference').datepicker('option', 'dateFormat', 'dd/mm/yy');
        $('#$reference').datepicker($.datepicker.regional['it']);
  
    });
    </script>";
     
return $output_html;    
}
function java_accordion($reference=null,$id_menu=null){
global $RG_addr;

//RIORDINO VOCI MENU
// 0 = USER
// 1 = GAS
// 2 = ORDINI
// 3 = ANAGRAFICHE
// 4 = DES
// 5 = AIUTO
// 6 = BACHECA
// 7 = Cose(in)utili

switch ($id_menu){
    case 0:              //USER
        $id_menu=2;
        break;
    case 1:              //GAS
        $id_menu=1;
        break;
    case 2:              //DES
        $id_menu=3;
        break;     
    case 3:              //ORDINI
        $id_menu=4;
        break;
    case 4:              //ANAGRAFICHE
        $id_menu=0;
        break;
    case 5:              //HELP
        $id_menu=5;
        break;
    case 6:              //BACHECA
        $id_menu=6;
        break;
    case 7:              //Cose(in)utili
        $id_menu=7;
        break;                        
}    


$ml = read_option_text(_USER_ID,"MNL");
if(!empty($ml)){        
    $MENU_LAT = unserialize(base64_decode($ml));
    // TROVO LA POSIZIONE DEL MENU CHE DEVE ESSERE APERTO DALLA PAGINA
    $id_menu = array_search($id_menu+1,$MENU_LAT);
}
 
if(empty($reference)){
    $reference ="#accordion";
}  
    

if(is_empty($id_menu)){$id_menu=0;}




$h ="
<script type=\"text/javascript\">
var stop = false;
$(function(){
    $(\"#icon_rg\").hide();
    $( \"$reference h3\" ).click(function( event ) {
      if ( stop ) {
        event.stopImmediatePropagation();
        event.preventDefault();
        stop = false;
      }
    });
    $( \"$reference\" )
      .accordion({
        icons: false,
        autoHeight: false,
        collapsible : true,
        header: '> div > h3',
        active:$id_menu
      })
      .sortable({
        axis: 'y',
        handle: 'h3',
        stop: function() {
          stop = true;
        },
        update: function() {
          var order_lat = $(this).sortable('serialize');
          $(\"#info\").load(\"".$RG_addr["ajax_widget_order"]."?\"+order_lat);
          $(\"#icon_rg\").show();
          $(\"#icon_rg\").fadeOut(1000); 
        }
      });
});  
</script>";
return $h;
    
}
function java_dialog($reference=null,$msg=null){
$h = "<script type=\"text/javascript\">
$(function() {
        
        $(\"#dialog-message\").dialog({
            modal: true,
            height:240,
            width:400,
            draggable: false,
            resizable : true,
            position : 'center',
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        
    });
    
      
</script>";
return $h;    
}
function java_superfish($reference=null){
    
$h = "<script type=\"text/javascript\"> 
                $(function(){
                    $('ul.sf-menu').superfish();
                    
                });
     </script>\n";
  return $h;
}
function java_jcarouserl($reference=null){
    
$h = '
    <style type="text/css">
        .jcarousel-skin-tango .jcarousel-container-vertical {
            width: 80%;
        }
        .jcarousel-skin-tango .jcarousel-clip-vertical {
            width: 100%;
        }
        .jcarousel-skin-tango .jcarousel-item {
            width : auto;
            height: auto;
        }
        .jcarousel-skin-tango .jcarousel-next-vertical {
            display:none;
        }
        .jcarousel-skin-tango .jcarousel-prev-vertical {
            display:none;
        }
    </style>
<script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("'.$reference.'").jcarousel({
                      vertical: true,        
                      scroll: 1,
                      auto:5,
                      wrap:"circular"
                });
            });
        </script>';
  return $h;
}

function java_tablesorter($reference=null){
$h .= '<script type="text/javascript">                
$(document).ready(function() 
         {
         $("#'.$reference.'").tablesorter({widgets: [\'zebra\',\'saveSort\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
} 
);
</script>';
return $h;    
    
}
function java_tablegroup($reference=null){
    return '<script type="text/javascript">

<!--


$(document).ready(function()

{

    $(\'#'.$reference.'\').tableSorter({sortColumn: 1}).sortStop(function(e, col){

        //ungroup first

        $(this).tableUnGroup();

        switch (col)

        {
            case 3:

                $(this).tableGroup({groupColumn: 4, groupClass: "mygroups", useNumChars: 0});

                break;

            case 2:

                $(this).tableGroup({groupColumn: 3, groupClass: "mygroups", useNumChars: 0});

                break;

            case 1:

                $(this).tableGroup({groupColumn: 2, groupClass: "mygroups", useNumChars: 0});

                break;

            case 0:

            default:

                $(this).tableGroup({groupColumn: 1, groupClass: "mygroups", useNumChars: 0});

                break;

        }

    });

});

-->

</script>

<style type="text/css">

tr.mygroups td

{
    font-size:1.2em;
    
    text-align:left;
    
    font-weight: bold;

    padding: 10px 20px;

    color: brown;

    font-variant: small-caps;

}

</style>
';
}
function java_dialog_msg($msg){
    
    if(!empty($msg)){
        return "<div id=\"dialog-message\" title=\"ReteGas A.P.\">
                <p>
                    <span class=\"ui-icon ui-icon-circle-check\" style=\"float:left; margin:0 7px 50px 0;\"></span>
                    $msg
                    </p>
                </div>";
    }else{
        return "";
    }             
}
function java_qtip($ref=null,$style=null){
    
    if(empty($ref)){
        $ref="body [title]";    
    }
    if(empty($style)){
        $style="light";    
    }
        
        return '<script type="text/javascript"> 
                    $("'.$ref.'").qtip({
                                            style: {
                                                widget: true,
                                                tip: true,
                                                classes: "ui-tooltip-rg ui-tooltip-shadow ui-tooltip-rounded"                                                
                                            },
                                            position: {
                                                viewport: $(window)
                                            },
                                            hide: {
                                                fixed: true,
                                                delay: 300,
                                                event: "mouseout"
                                            }
                                        }); 
                </script>';
            
}
function java_qtip_old($ref=null,$style=null){
    
    if(empty($ref)){
        $ref="a[title]";    
    }
    if(empty($style)){
        $style="light";    
    }
        
        return "<script type=\"text/javascript\">
                    $(document).ready(function()
                        {
                            $('$ref').qtip();
                        }); 
                    
                </script>";
            
}
function java_qtip_ajax($file_input,$ref=null,$style=null){
    
    if(empty($ref)){
        $ref=".display_full_message";    
    }
                
      return "
      <script type=\"text/javascript\">
                       $('$ref').each (function() {     
                            var myvar = $(this).attr('rel');
                            $(this).qtip({
                                    content: {
                                        text: 'Loading...', // The text to use whilst the AJAX request is loading
                                        ajax: {
                                            url: '".$file_input."', // URL to the local file
                                            type: 'GET', // POST or GET
                                            data: {vid_link: myvar}, // Data to pass along with your request
                                            success: function(data, status) {
                                                // Process the data
                                 
                                                // Set the content manually (required!)
                                                this.set('content.text', data);
                                            }
                                        }
                                    },
                                    style: {
                                            tip: true,
                                            classes: 'ui-tooltip-rg ui-tooltip-shadow ui-tooltip-rounded'                                                
                                            },
                                    position: {
                                        my: 'center', // ...at the center of the viewport
                                        at: 'center',
                                        target: $(window)
                                    }
                                });
                       });         
    </script> 
                                ";          
                
            
}
function java_sparkline($range_max = null){
//$(".inlinesparkline").sparkline();        

return '<script type="text/javascript">
    $(function() {
         
         $(".inlinesparkline").sparkline("html", {type: "discrete", height:"2.5em", chartRangeMax:"'.$range_max.'", lineColor: "black", xwidth: 18, lineHeight :2, thresholdValue : "1", thresholdColor: "#ccc"}); 
     });
    </script>';
            
}
function java_progression($reference = null){
//$(".inlinesparkline").sparkline();        

return '<script type="text/javascript">    
            jQuery(document).ready(function(){
            jQuery("'.$reference.'").progression();
        });
    </script>';            
}
function java_sparkline_pie($reference){
//$(".inlinesparkline").sparkline();        

return '<script type="text/javascript">
    $(function() {
         
         $("'.$reference.'").sparkline("html", {type: "pie", height:"2em", sliceColors:["#DDD","#800"]}); 
     });
    </script>';
            
}
function java_list_filter($id_ul=null,$id_field=null){

if(is_null($id_ul)){$id_ul='header_chiusi';};
if(is_null($id_field)){$id_field='filtrum';};    
    
return '<script>
        (function ($) {
          jQuery.expr[\':\'].Contains = function(a,i,m){
              return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
          };


          function listFilter(header, list) { // header is any element, list is an unordered list
            var form = $("<form>").attr({"style":"display: block","action":"#"}),
                input = $("<input>").attr({"style":"display: block","type":"text"});
            $(form).append(input).appendTo(header);
            
            $(input)
              .change( function () {
                var filter = $(this).val();
                if(filter) {
                  $(list).find(".'.$id_field.':not(:Contains(" + filter + "))").parent().slideUp();
                  $(list).find(".'.$id_field.':Contains(" + filter + ")").parent().slideDown();
                } else {
                  $(list).find("li").slideDown();
                }

                return false;
              })
            .keyup( function () {
                $(this).change();
                
            });
          }


          
          $(function () {
            listFilter($("#'.$id_ul.'"), $("#list"));
          });
        }(jQuery));

          </script>';

}

function java_auto_close_accordion($millisec){
    return '
    <script type="text/javascript">
    (function($){
                var mousestop = function(evt){
                    $("#accordion").accordion({ active: false})
                 },
                thread = null;
                $.fn.saveStops = function() {
                    return this.bind("mousemove.clickmap", function(evt) {
                        clearTimeout(thread);
                        thread = setTimeout(function(){mousestop(evt);}, '.$millisec.');
                    });
                };
            }(jQuery));
            
            $("#container").saveStops();
            
            </script>';
}


// JAVASCRIPT 

//VECCHI RIFERIMENTI------------------------------------------------------ NON CANCELLARE  
function c1_ext_javascript($id_user=null){
    
global $RG_addr;    
    
$h  = "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery"]."\"></script>
";
 
$h .= "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_ui"]."\"></script>
";
$hp .= "<script type=\"text/javascript\" src=\"/gas2/js/jquery.tooltip.js\"></script>
";
$h .= "<script type=\"text/javascript\" src=\"".$RG_addr["js_datepicker_loc"]."\"></script>
<script type=\"text/javascript\" src=\"".$RG_addr["js_datetimepicker"]."\"></script>
"; 
 
$h .= ' <script type="text/javascript" src="'.$RG_addr["js_superfish_hoverintent"].'"></script>
        <script type="text/javascript" src="'.$RG_addr["js_superfish_bigframe"].'"></script>
        <script type="text/javascript" src="'.$RG_addr["js_superfish"].'"></script>
        <link rel="stylesheet" type="text/css" href="'.$RG_addr["css_superfish"].'" media="screen">'; 

return $h;    
}
function c1_ext_javascript_progressbar(){    //VECCHIA PROGRESSBAR
    
$h  = "<script type=\"text/javascript\" src=\"/gas2/js/jquery.progressbar/js/jquery.progressbar.js\"></script>
"; 
 

return $h;    
}
function c1_ext_javascript_tooltip($id_tooltip,$class_tooltip){
$h = "
<script type=\"text/javascript\">
$(document).ready(function() {
    $(\"#$id_tooltip\").tooltip({cssClass:\"$class_tooltip\"});
});
</script>
";    
    
return $h;    
}
function c1_ext_javascript_datepicker(){
// DATEPICKER CON DATA A PARTIRE DA OGGI
// $('#datepicker').datepicker({minDate: 0});  
$output_html .= "<script type=\"text/javascript\">
    $(function() {        
        $('#datepicker').datepicker('option', 'dateFormat', 'dd/mm/yy');
        $('#datepicker').datepicker($.datepicker.regional['it'])
        $('#datepicker2').datepicker();
        $('#datepicker2').datepicker('option', 'dateFormat', 'dd/mm/yy');
        $('#datepicker2').datepicker($.datepicker.regional['it'])
    });
    </script>";
$output_html .= "";
$output_html .= "";
$output_html .= "";
    
return $output_html;    
}
function c1_ext_javascript_datetimepicker($ref,$alt=null){
// DATEPICKER CON DATA A PARTIRE DA OGGI
// $('#datepicker').datepicker({minDate: 0});
if(is_null($alt)){$alt="#alternate";};
  
$output_html .= "<style type=\"text/css\"> 
                    /* css for timepicker */
                    .ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
                    .ui-timepicker-div dl{ text-align: left; }
                    .ui-timepicker-div dl dt{ height: 25px; }
                    .ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
                    .ui-timepicker-div td { font-size: 90%; }
                    </style> 
                <script type=\"text/javascript\">
                $('$ref').datetimepicker();
                </script>";
$output_html .= "";
$output_html .= "";
$output_html .= "";
    
return $output_html;    
}
function c1_javascript_timeline($id_user,$periodo = null){
global $RG_addr;
// BUONO DAL SITO SIMILE-WIDGETS 2 RIGHRE SOTTO    
//$h .= ' <script src="http://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></script>
//      ';
      
      
      //
//$h .= ' <script src="'.$RG_addr["js_timeline_ajax"].'?bundle=true" type="text/javascript"></script>
//    ';      
//$h .= ' <script src="'.$RG_addr["js_timeline"].'?bundle=true&timeline-use-local-resources=true" type="text/javascript"></script>
//    '; 
$h .= ' <script src="'.$RG_addr["js_timeline"].'" type="text/javascript"></script>
    ';    
         
//echo "ID USER JAVASCRIPT = ".$id_user;
//<link rel="stylesheet" href="http://simile.mit.edu/timeline/api/bundle.css" type="text/css">
//?timeline-use-local-resources=true

if ($periodo=="anno"){
    $h .= create_timeline_anno($id_user);
    return $h;
    exit;
}
if ($periodo=="des"){
    $h .= create_timeline_anno_des($id_user);
    return $h;
    exit;
}
         
$h .= create_timeline($id_user);    


//$h=""; // DA TOGLIERE SE LA TIMELINE FUNZIONA
    
 return $h;   
}
function configure_qtip(){
    return '
    <script type="text/javascript">
                
                $("#content a[title]").qtip({ style: { name: "cream", tip: true } }); 
              
   });           
    </script>
    ';
}
function c1_javascript($id_menu = "false"){
$h = "<script type=\"text/javascript\">

$(function() {
        $(\"#accordion\").accordion({
        active:$id_menu,    
        icons:false,
        collapsible : true,
        autoHeight : false
        });
    });
</script>


<script type=\"text/javascript\">
$(function() { 
        $(\"#accordion_help\").accordion({
        active:0,    
        icons:false,
        collapsible : true,
        autoHeight : false
        });
    });
      
</script>


<script type=\"text/javascript\">

$(function() {
        // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
        $(\"#dialog\").dialog(\"destroy\");
    
        $(\"#dialog-message\").dialog({
            modal: true,
            height:180,
            width:400,
            draggable: true,
            resizable : true,
            position : 'center',
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        
    });
    
      
</script>

<script type=\"text/javascript\"> 
$(function(){
            jQuery('ul.sf-menu').superfish();
        });
        
     
 </script> 


";



        
return $h;    
}
//VECCHI RIFERIMENTI    

//VARIE
function c1_close_header(){
return "</head>";    
}
function c1_open_body(){
return "<body onload=\"onLoad();\" onresize=\"onResize();\">";    
}
function c1_open_div($name,$attrib = null){
return "<div id=\"$name\" $attrib>";        
}
function c1_close_div(){
return "</div>";        
}
function c1_close_all(){;
return"</body>
</html>";  
}
function footer_user(){
  $h .= c1_close_div();
  $h .= c1_close_div();
  $h .= c1_close_all();
  return $h;    
}
function pallino($colore,$dimensione=10){
    global $RG_addr;
    switch ($colore){
        case "verde";
        case "Verde";
        case "Green";
        case "green";
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'"  style="height:'.$dimensione.' px; width:'.$dimensione.'px;vertical_align:middle;border=0;">';
            break;
        case "rosso";
        case "Rosso";
        case "red";
        case "Red";
        
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'"  style="height:'.$dimensione.'px; width:'.$dimensione.'px;vertical_align:middle;border=0;">';
            break;
        case "grigio";
        case "Grigio";
        case "grey";
        case "Grey";
        
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'"  style="height:'.$dimensione.'px; width:'.$dimensione.'px;vertical_align:middle;border=0;">';
            break;
        case "blu";
        case "Blu";
        case "bleu";
        case "Bleu";
        
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_blu"].'"  style="height:'.$dimensione.'px; width:'.$dimensione.'px;vertical_align:middle;border=0;">';
            break;
        case "marrone";
        case "Marrone";
        case "brown";
        case "Brown";
        
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_marrone"].'"  style="height:'.$dimensione.'px; width:'.$dimensione.'px;vertical_align:middle;border=0;">';
            break;                                 
            
    }
    return $pal;
    
}

//VARIE 



//SEZIONI
function c1_header(){
$h="

<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<title>ReteDES.it (Rete dei Gas dell'alto Piemonte)</title>
<meta http-equiv=\"P3P\" content=\"CP='IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA'\">
<meta http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\">
<meta http-equiv=\"Pragma\" content=\"no-cache\">
<meta http-equiv=\"Cache-Control\" content=\"no-cache\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
<meta http-equiv=\"Lang\" content=\"it\">
<meta name=\"author\" content=\"Mimmoz01\">
<meta http-equiv=\"Reply-to\" content=\""._SITE_MAIL_LOG."\">
<meta name=\"generator\" content=\"Notepad\">
<meta name=\"description\" content=\"Rete Gas Alto Piemonte, rete dei gruppi di acquisto solidali per gestire ordini e collaborazioni.\">
<meta name=\"keywords\" content=\"Gruppi acquisto solidale, borgomanero, associazione, territorio novarese\">
<meta name=\"creation-date\" content=\"".date("m/d/y")."\">
<meta name=\"revisit-after\" content=\"15 days\">
<meta name=\"title\" content=\"ReteDES.it (Rete dei GAS dell'alto Piemonte)\" />

";    
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
//   "http://www.w3.org/TR/html4/loose.dtd">    
return $h;    
} 
function c1_header_page($usr = null, $gas=null, $menu=null, $posizione = null){
global $RG_addr;
    
$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir<>"gas2"){
    $pa = "../";
}else{
    $pa="";
}


$h='
 <div class="ie_header_fix">
 <div style=" margin:0;
              padding:0;
              float:left;">
 
              <a href="'.$RG_addr["sommario"].'">
                <img align="left" src="'.$RG_addr["img_logo_retedes"].'" border="0" width="75" height="75" alt="ReteDES.it">
              </a>
 
    </div>
     <div style=" margin:0;
              padding:0;
              float:left;">
 
              <a href="'.$RG_addr["sommario"].'">
                <img align="left" src="'.$RG_addr["img_logo"].'" border="0" width="125" height="75" alt="ReteDES.it">
              </a>
 
    </div>
    
    
    <div style="margin:0;
                padding-right:0;
                float:right;
                text-align:right;">Rete dei GAS dell\'Alto Piemonte<br />
              <span class="small_link">da un\'idea ed un progetto
              del GAS di Borgomanero</span><br> 
              <span class="small_link">'._SITE_MAIL_LOG.'</span><br>
              <span class="small_link">Sviluppo :</span>ma.morez@tiscali.it
        </div>
  </div>
 <div style="clear:both;"></div>

';

    
if(!empty($usr)){    
//$h .="<div id=\"small-panel\" class=\"ui-widget-content ui-corner-all\">";

$h .='<!--[if IE]>
<br>
<![endif]-->';

$h .="<div id=\"small-panel\" class=\"ui-widget-content  ui-corner-all\" style=\"clear:both\">";
//        <span class=\"small_link \">Operazioni eseguibili : </span>";

$h.= "              <a class=\"small awesome silver\" href=\"".$RG_addr["sommario"]."\"><img src=\"".$RG_addr["img_home_piccola"]."\" widht=16px; height=16px;></a>
                    <a class=\"small awesome silver destra\" href=\"".$RG_addr["pag_users"]."?q=logout\"><img src=\"".$RG_addr["img_logout"]."\" widht=16px; height=16px;></a>
                    <span class=\"small_link \">Utente connesso: </span>$usr - 
                    <span class=\"small_link \">Gas appartenenza : </span>$gas - 
                    <span class=\"small_link \">Posizione : </span>$posizione
                    <div style=\"clear:both\"> </div>
                    ";
              

$h.="</div>"; 
}


                    

    

            
return $h;                    
}       
function c1_navigation_1(){
$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="help"){
    $pa = "../";
}else{
    $pa="";
}    
    

// ricordami : <span class=\"small_link\">Ricordami :</span><input type=\"checkbox\" name=\"remember\" value=\"ON\"><br>
    
$h = " <div class=\"ui-widget-header ui-corner-all\" style=\"padding:2px;\">
            <form method=\"POST\" action=\"index.php\" name=\"loginform\">
            <b>Login</b><br>
            <span class=\"small_link\">Username</span><br><input type=\"text\" name=\"username\" size=\"16\" title=\"Username\" ><br>
            <span class=\"small_link\">Password</span><br><input type=\"password\" name=\"password\" size=\"16\" title=\"Password\"><br>
            <input type=\"hidden\" name=\"q\" value=\"do_login\"><br>
            <input type=\"submit\" value=\"Accedi\" class=\"medium awesome silver\">
        </form>
                    
       </div>      

        
        <!--[if IE]>
        <p>Con qualche versione di internet explorer alcune funzionalità del sito sono inaccessibili.</p>
        <![endif]-->
        
            ";
if(!is_chrome()){
 $h .="       
        <div class=\"ui-state-error\" style=\"margin-top:10px; padding:10px; font-size: 86%;\">
        <a href=\"http://www.google.com/chrome/index.html?hl=it\" target=\"_blank\">
        <img src=\"../../images/chrome-logo-300x291.jpg\" BORDER=0 height=\"30px\" width=\"30px\" align=\"right\" >
        Sito ottimizzato per Google Chrome.
        </a>
        </div>
        
        
        
        
        
         ";
        }
$h8.="
<br>
<script type=\"text/javascript\">
//<![CDATA[
document.write('<s'+'cript type=\"text/javascript\" src=\"http://ad.altervista.org/js.ad/size=125X125/r='+new Date().getTime()+'\"><\/s'+'cript>');
//]]>
</script>";
$h8.="<script type=\"text/javascript\">
//<![CDATA[
document.write('<s'+'cript type=\"text/javascript\" src=\"http://ad.altervista.org/js.ad/size=125X125/r='+new Date().getTime()+'\"><\/s'+'cript>');
//]]>
</script>";        
        
    
return $h;    
}
function c1_navigation_2($id_user = null, $id_gas=null){

global $RG_addr;
    
if(user_level($id_user)>1){
$amministra =" <a href=\"".$RG_addr["amministra"]."\">Amministra</a><br>";    
} 
    
        $h = "<div id=\"accordion\">
                        <h3><a href=\"#\" class=\"medium silver awesome\" style=\"margin:6px\">Profilo</a></h3>
                        <div>
                         $amministra
                         <a href=\"".$RG_addr["pag_amici_table"]."\">I miei amici</a><br>
                         <a href=\"".$RG_addr["pag_users_form_mia"]."\">I miei dati</a><br>
                         <a href=\"".$RG_addr["pag_users_form_password"]."\">Cambia password</a><br>
                         <a href=\"".$RG_addr["pag_users"]."?q=logout\">Logout</a><br>
                        </div>
                    <h3><a href=\"#\" class=\"medium silver awesome\" style=\"margin:6px\">Anagrafiche</a></h3>
                        <div>
                                <a href=\"".$RG_addr["tipologie"]."\">Tipologie</a><br> 
                                <a href=\"".$RG_addr["tutte_le_ditte"]."\">Ditte</a><br>
                                <a href=\"".$RG_addr["miei_listini"]."\">Miei listini</a><br>
                        </div>
                    <h3><a href=\"#\" class=\"medium silver awesome\" style=\"margin:6px\">ReteGas A.P.</a></h3>
                        <div>
                                <a href=\"".$RG_addr["bacheca"]."\">Bacheca</a><br>
                                <a href=\"".$RG_addr["gas_table"]."\">GAS associati</a><br>
                                <a href=\"".$RG_addr["gas_form"]."\">Il mio GAS</a><br>";    
        $h       .="    </div>
                    <h3><a href=\"#\" class=\"medium silver awesome\" style=\"margin:6px\">Ordini</a></h3>
                        <div>
                                <a href=\"".$RG_addr["panoramica"]."\">Panoramica</a><br>
                                <a href=\"".$RG_addr["ordini_aperti"]."\">Aperti</a><br>
                                <a href=\"".$RG_addr["ordini_chiusi"]."\">Chiusi</a><br>
                                <a href=\"".$RG_addr["dareavere"]."\">Dare-Avere</a><br>
                        </div>
                    <h3><a href=\"#\" class=\"medium silver awesome\" style=\"margin:6px\">Aiuto</a></h3>
                        <div>
                                <a href=\"".$RG_addr["wiki"]."\">Istruzioni</a><br>
                                <a href=\"".$RG_addr["disclaimer"]."\">Disclaimer</a><br>
                        </div>

                            
                </div>
                
                <!--[if IE]>
                <p>Sito ottimizzato per <a href=\"http://www.google.com/chrome/index.html?hl=it\">Google Chrome.</a><br>
                Con qualche versione di internet explorer alcune funzionalità del sito sono inaccessibili.</p>
                <![endif]-->";
//SEZIONE CONTEGGIO UTENTI ATTIVI
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>Presenze GAS :<br></b>
                    '.crea_lista_gas_attivi(2).'
                    </div><hr>';
//SEZIONE CONTEGGIO UTENTI ATTIVI
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>User OnLine :<br></b>
                    '.crea_lista_user_attivi_pubblica(2).'
                    </div><hr>';
//SEZIONE VISUALIZZAZIONE USER ONLINE

//SEZIONE CONTEGGIO UTENTI ATTIVI PROPRIO GAS
$h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                    <b>'.gas_nome($id_gas).'<br></b>
                    '.crea_lista_user_attivi_pubblica_gas(2,$id_gas).'
                    </div>';
//SEZIONE VISUALIZZAZIONE USER ONLINE


        
if(!is_chrome()){
 $h .="       
        <div class=\"ui-state-error\" style=\"margin-top:10px; padding:10px; font-size: 76%;\">
        <a href=\"http://www.google.com/chrome/index.html?hl=it\" target=\"_blank\">
        <img src=\"../../images/chrome-logo-300x291.jpg\" BORDER=0 height=\"30px\" width=\"30px\" align=\"right\" >
        Sito ottimizzato per Google Chrome.
        </a>
        </div> ";
        }
        
    
            
    
return $h;    
}
function c1_content_start(){
    
$h="<div class=\"ui-widget ui-corner-all padding_6px\">
            <h3>Benvenuti nel sito della Rete dei GAS dell' Alto Piemonte.</h3> 
            </div>
<div class=\"ui-widget-content ui-corner-all indent\" style=\"padding:30px;\">
<p>1. Cosa sono i GAS</p>    
Un G.A.S. (Gruppo di acquisto solidale) è un gruppo di persone che hanno deciso di acquistare collettivamente dei prodotti direttamente dal produttore e di redistribuirli poi per uso interno.
Per avere maggiori informazioni sui GAS segui i link in fondo alla pagina.

<p>2. Cos'è RETE GASAP</p>    
RETE GASAP è uno strumento WEB, il cui scopo è quello di aiutare la raccolta e la gestione degli ordini dei GAS limitrofi alla zona dell'alto Piemonte, in particolare, le zone del Novarese, Valsesia, Bassa Val D'Ossola e sponda occidentale del lago Maggiore.<br>
Ecco alcuni screenshot del programma;<br>
<h3>
<img src=\"/images/ss_1.jpg\" border=\"0\" width=\"200\" height=\"130\" title=\"\"; align=\"left\">
<img src=\"/images/ss_2.jpg\" border=\"0\" width=\"200\" height=\"130\" title=\"\"; align=\"left\">
<img src=\"/images/ss_3.jpg\" border=\"0\" width=\"200\" height=\"130\" title=\"\"; align=\"bottom\">
</h3>

<p>3. Per iscriversi al sito</p>    
Le istruzioni per l'iscrizione al sito si trovano cliccando nel menu' a sinistra sotto la voce \"AIUTO -- Domande generiche -- come posso iscrivermi\". Una volta 
iscritti al sito, sempre nella voce \"AIUTO\" saranno disponibili menu' dettagliati per ogni funzione del programma. Le ultime novità, gli aggiornamenti e le eventuali problematiche riguardanti l'utilizzo del sito si possono ritrovare nel BLOG pubbilco
a questo indirizzo :<a href=\"http://retegas.blogspot.com\">retegas.blogspot.com</a>

<p>4. Per partecipare con un nuovo GAS</p>    
Per partecipare a RETE GASAP è necessario creare un nuovo GAS contattando ReteDES.it(@)gmail.com, e successivamente iscriversi (gratuitamente) a questo sito.

<p>5. Che cosa mi serve...</p>    
Per formare e svolgere attività proprie dei gas basterebbero solo buona volontà e parecchie telefonate (ed anche una buona dose di pazienza); Per sfruttare al meglio questo sito occorrono
un computer qualunque, una connessione internet (in teoria anche con le vecchie 56k dovrebbe funzionare tutto ugualmente) ed un browser aggiornato (possibilmente NON internet explorer).<br>Si consiglia di usare Chrome, o Firefow, od Opera.
Anche sui telefonini che ho provato io, la resa è accettabile, e tutte le funzionalità (a parte la stampa) sono attive.

<p>6. C'è della pubblicità ?????</p>    
La pubblicità che c'è qua di fianco  quella che permette la sopravvivenza del sito, ed il suo contenuto  casuale e deciso da Google.

<p>7. Link utili</p>    
<a  href=\"http://www.retegas.org\">www.retegas.org</a> - Rete nazionale di collegamento dei GAS<br>
<a  href=\"http://www.economia-solidale.org\">www.economia-solidale.org</a> - Gruppi di acquisto solidale

 <br>
            </div>";    
    
return $h;    
}

function navigation_top($id_user){
$h_menu .='<li><a class="medium silver awesome"><b>Sito</b></a>'; 
    $h_menu .='<ul>';
        $h_menu .='<li><a class="medium silver awesome">Utente</a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium silver awesome">Miei Dati</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Miei Amici</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Cambia Password</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Log Out</a></li>';
            $h_menu .='</ul>';
        $h_menu .='</li>';     
        $h_menu .='<li><a class="medium silver awesome">Anagrafiche</a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium silver awesome">Tipologie</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Ditte</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Miei listini</a></li>';
            $h_menu .='</ul>';
        $h_menu .='</li>';    
        $h_menu .='<li><a class="medium silver awesome">ReteDES.it</a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium silver awesome">Bacheca</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Associati</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Mio Gas</a></li>';
            $h_menu .='</ul>';
        $h_menu .='</li>';    
        $h_menu .='<li><a class="medium silver awesome">Ordini</a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium silver awesome">Panoramica</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Aperti</a></li>';
                $h_menu .='<li><a class="medium silver awesome">Chiusi</a></li>';
            $h_menu .='</ul>';
        $h_menu .='</li>';    
        $h_menu .='<li><a class="medium silver awesome"><b>Real time:</b><br>A,b,c,d</a></li>';
    $h_menu .='</ul>';
$h_menu .='</li>';

    
return $h_menu;    
}
function c1_content_start_3(){
    
$h="        <div class=\"ui-widget-content ui-corner-all\" style=\"padding:30px; text-align:left;\">
            <span style=\"font-size:1.2em;\">
            <p>ReteDES.it (Rete dei DES) è un social-strumento informatico a \"Km 0\" nato per
            aiutare ad organizzare e semplificare la gestione degli acquisti all'interno dei GAS aderenti a questo progetto, 
            creando sul territorio una rete di collaborazione flessibile e dinamica.<br>
            </p>
            <p>Si rivolge a GAS, o gruppi GAS (DES) che stanno nascendo o che sono già consolidati, proponendo gratuitamente una
            piattaforma in grado di essere adattata alle singole esigenze, mantenendo comunque una
            struttura di base comune.
            </p>
            <h4>Internet Explorer richiede l'installazione di <a href=\"http://www.google.com/chromeframe?quickenable=true\">Chrome Frame</a> per poter funzionare correttamente.</h4>
            <span><a href=\"http://sites.google.com/site/retegasapwiki/f-a-q-domande-frequenti\">Qua</a> i motivi</span>
            <p><strong>Il browser gratuito migliore per usare ReteDES.it ? <a href = \"http://www.google.com/chrome?hl=it\">GOOGLE CHROME</a>, installabile da questo <a href=\"http://www.google.com/chrome/eula.html?hl=it\">LINK</a></p>
            </strong>
            </p>
            </span>
            <br>
            </div>";    
    
return $h;    
}
//SEZIONI 

//REINDIRIZZAMENTO
function c1_go_away($q=null){

global $RG_addr;    
  
header("Location: ".$RG_addr["sommario"]."$q");
 
exit;
}
function pussa_via($message=null){ 
global $RG_addr;    
header("Location: ".$RG_addr["sommario"]."?q=no_permission");
exit;
}


//FUNZIONI INDEX
function choose_msg($q){
    global $RG_addr;
    
    switch ($q){

        case "1":
               $msg = _VALIDATION_DONE;
               break;
        case "2":
               $msg = _PASSWORD_SENT;
               break;    
       case "3":
               $msg = _UNAME_OR_PWD_NOT_RECOGNIZED.
               '<br>
               <br>
               <a class="awesome red medium" href="'.$RG_addr["user_forgotten_pwd"].'">Password dimenticata ?</a><br>';
               break;
       case "login_empty":
               $msg = _EMPTY_UNAME_OR_PASSWORD;
               break;
       case "login_not_active":
               $msg = _NOT_YET_ACTIVED;
               break;
       case "no_permission":
               $msg = _NOT_ALLOWED;
               break;
       case "logout":
               $msg = _THANKS_FOR_VISITING;
               break;  
       case "registrazione_ok":
               $msg = _REGISTRATION_ENDED_OK;
               break;
       case "chg_passw":
               $msg = _PASSWORD_CHANGED;
               break;
       case "not_allowed":
               $msg = _NOT_ALLOWED;
               break;
       case "41":
               $msg = _USER_SAME_IP;
               break;
       case "43":
               $msg = "Una mail ti è stata mandata con la nuova password.";
               break;
       case "44":
               $msg = "Una mail ti è stata mandata con il tuo username.";
               break;        
       case "7":
               $msg = _NEW_CONFIGURATION_SAVED;
               break;
       case "9":
               $msg = _CREDIT_ADDED_OK;
               break;
       case "10":
               $msg = _CREDIT_DECREASED_OK;
               break;
       case "11":
               $msg = _MAIL_SENT;
               break;
       case "90":
               $msg = _OP_DOUBLED;
               break;
       case "121":
               $msg = "Occorre aver eseguito il login per poter accaparrarsi l'ordine.";
               break;
       case "np":
               $msg = "La nuova password è stata generata casualmente ed inviata all'indirizzo mail indicato.";
               break;                                                                                                       
       Default:
               $msg = "";
       Break;
 }
 
return $msg; 
}

function main_render_quick_ordini_aperti($gas){
global $RG_addr;

//echo $site->posizione;
      
$my_query="SELECT retegas_ordini.id_ordini, 
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
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        

          
         while ($row = mysql_fetch_array($result)){
         $riga++;
         
         //TEMPO ALLA CHIUSURA
         $inittime=time();
         $datexmas=strtotime($row["data_chiusura"]);
         $timediff = $datexmas - $inittime;
     
            $days=intval($timediff/86400);
            $remaining=$timediff%86400;
             
            $hours=intval($remaining/3600);
            $remaining=$remaining%3600;
             
            $mins=intval($remaining/60);
            $secs=$remaining%60;
         
            if ($days<2){
                $color = "style=\"color:red\"";
            }else{
                unset($color);
            }
         
            
         if(id_referente_ordine_proprio_gas($row["id_ordini"],$gas)>0){
             $pal = '<a title="Ordine partecipabile"><IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>';
             $vis="OK";
         }else{
             if(check_option_order_blacklist($gas,$row["id_ordini"])==0){
                $pal = '<a title="Manca il referente per il tuo GAS"><IMG SRC="'.$RG_addr["img_pallino_marrone"].'" ALT="NON Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>'; 
                $vis="OK";
             }else{
                $vis=""; 
             }
         }
        
        if($vis=="OK"){
         
            $h_table.=  '<div>
                         '.$pal.'
                         <b><a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></b>, di '.fullname_from_id($row["id_utente"]).'  
                         <span class="small_link" '.$color.'>(Chiude tra '.$days.' gg e '.$hours.' h).</span>
                         
                         </div>
                        ';
        }             
         }//end while
         

         
return $h_table;          
    
}
function main_render_quick_ordini_futuri($gas){

      
$my_query="SELECT retegas_ordini.id_ordini, 
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
            WHERE (((retegas_ordini.data_apertura)>NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      $result = $db->sql_query($my_query);
        

  
        $riga=0;
         
        
          
         while ($row = mysql_fetch_array($result)){
         $riga++;
        
        $data_adesso = time();
        $data_apertura = gas_mktime(conv_datetime_from_db($row["data_apertura"]));
        $date_diff = $data_apertura - $data_adesso;
        $giorni_all_apertura = floor($date_diff/(60*60*24));
        
            
        $pal = '<a title="Ordine FUTURO"><IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Programmato" style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE="Ordine partecipabile"></a>';
        
         
        $h_table.=  '<div>
        '.$pal.'
        <b><a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'">'.$row["descrizione_ordini"].'</a></b>, di '.fullname_from_id($row["id_utente"]).'
        <span style="color:#808080">(apre tra '.$giorni_all_apertura.' giorni, il '.conv_only_date_from_db($row["data_apertura"]).')</span>
        </div>
        ';
         }//end while
         
         
       
       return $h_table;
         
    
}
function main_render_quick_ordini_chiusi($gas){

    
      
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
            WHERE (((retegas_ordini.data_chiusura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura DESC
            LIMIT 100 ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      
      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        
   
        $h_table = '<span class="small_link" id="header_chiusi">Cerca tra gli ordini chiusi...</span>
                    <ul style="list-style-type: none; padding: 0; margin: 0;" id="list">';
          
         while ($row = mysql_fetch_array($result)){
         $riga++;
         if(is_printable_from_id_ord($row["id_ordini"])){
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="Stampabile" style="height:10px; width:10px;vertical_align:middle;border=0;">';
         }else{
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="NON Stampabile" style="height:10px; width:10px;vertical_align:middle;border=0;">'; 
         }    

         
        $h_table.=  '<li class="ui-widget" style="padding:0.1em; font-size:0.8em; font-weight:normal">
        '.$pal.'
        <a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'"><b>'.$row["descrizione_ordini"].'</b></a>, di '.fullname_from_id($row["id_utente"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.
                     </li>
                    ';
         }//end while
         
         
       $h_table .='</ul>';
         
return $h_table;          
    
}
function main_render_quick_ordini_tutti($gas,$limit=20){

    
      
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
            WHERE ((retegas_referenze.id_gas_referenze)=$gas)
            ORDER BY retegas_ordini.data_apertura DESC
            LIMIT $limit ;";
            //WHERE ((retegas_referenze.id_gas_referenze)=$gas)
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      
      $result = $db->sql_query($my_query);
        

  
        $riga=0;
 
        $h_table='<ul style="list-style-type: none; padding: 0; margin: 0;" id="list_tutti">';
          
         while ($row = mysql_fetch_array($result)){
         $riga++;
         
         
         if(gas_mktime(conv_datetime_from_db($row["data_apertura"]))>gas_mktime(date("d/m/Y H:i"))){
                $pal = '<a title="Ordine futuro"><IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Futuro" style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE="Ordine partecipabile"></a>';   
                $vis="OK";
         }else{
                if(gas_mktime(conv_datetime_from_db($row["data_chiusura"]))>gas_mktime(date("d/m/Y H:i"))){
                      if(id_referente_ordine_proprio_gas($row["id_ordini"],$gas)>0){ 
                            $pal = '<a title="Ordine partecipabile"><IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE="Ordine partecipabile"></a>';
                            $vis="OK";
                      }else{
                          if(check_option_order_blacklist($gas,$row["id_ordini"])==0){
                            $pal = '<a title="Manca il referente per il tuo GAS"><IMG SRC="'.$RG_addr["img_pallino_marrone"].'" ALT="NON Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>'; 
                            $vis="OK";
                          }else{
                            $vis="";
                              
                          }
                      }    
                }else{
                      if(is_printable_from_id_ord($row["id_ordini"])){
                             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'"  style="height:10px; width:10px;vertical_align:middle;border=0;">';
                             $vis="OK";
                      }else{
                             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'"  style="height:10px; width:10px;vertical_align:middle;border=0;">'; 
                             $vis="OK";
                      }
                    
                
                }
         } 

        
            
        
        if($vis=="OK"){
         
        $h_table.=  '<li>
        '.$pal.'
        '.$row["id_ordini"].' 
        <a href="'.$RG_addr["ordini_form"].'?id_ordine='.$row["id_ordini"].'"><b>'.$row["descrizione_ordini"].'</b></a>, di '.fullname_from_id($row["id_utente"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.
                     </li>
                    ';
        }            
                    
        }//end while
         
         
       $h_table .='</ul>';
         
return $h_table;          
    
}

function main_render_quick_ordini_io_coinvolto($w_name,$id_user,$gas,$limit=20){

    
      
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
            WHERE ((retegas_referenze.id_gas_referenze)=$gas)
            ORDER BY retegas_ordini.data_apertura DESC;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      
      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        
   
        $h_table = '<span class="small_link" id="header_'.$w_name.'">Cerca tra questi ordini ...</span>
                    <ul style="list-style-type: none; padding: 0; margin: 0;" id="list_'.$w_name.'">';
          
         while ($row = mysql_fetch_array($result)){
         
         
         $io_cosa_sono = ordine_io_cosa_sono($row["id_ordini"],$id_user);
         //echo "IO = $io_cosa_sono<br>";
         switch($io_cosa_sono){
             case 1:$io_sono = "";break;
             case 2:$io_sono = "Partecipo";$riga++;break;
             case 3:$io_sono = "Gestisco per il mio GAS";$riga++;break;
             case 4:$io_sono = "Gestisco l'ordine";$riga++;break;  
         }
         
         if(gas_mktime(conv_datetime_from_db($row["data_apertura"]))>gas_mktime(date("d/m/Y H:i"))){
                $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_blu"].'" ALT="Futuro" style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE="Ordine partecipabile"></a>';   
         }else{
                if(gas_mktime(conv_datetime_from_db($row["data_chiusura"]))>gas_mktime(date("d/m/Y H:i"))){
                      if(id_referente_ordine_proprio_gas($row["id_ordini"],$gas)>0){ 
                            $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;" TITLE="Ordine partecipabile"></a>';
                      }else{
                            $pal = '<a><IMG SRC="'.$RG_addr["img_pallino_marrone"].'" ALT="NON Partecipabile" style="height:10px; width:10px;vertical_align:middle;border=0;"></a>'; 
                      }    
                }else{
                      if(is_printable_from_id_ord($row["id_ordini"])){
                             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'" ALT="Stampabile" style="height:10px; width:10px;vertical_align:middle;border=0;">';
                      }else{
                             $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="NON Stampabile" style="height:10px; width:10px;vertical_align:middle;border=0;">'; 
                      }
                    
                
                }
         } 

        if(($io_cosa_sono>1) AND ($riga<($limit+1))){
            $h_table.=  '<li>
            '.$pal.'
            <a href="'.$RG_addr["ordini_form"].'?id='.$row["id_ordini"].'"><b>'.$row["descrizione_ordini"].'</b></a>, io '.$io_sono.' 
                         </li>
                        ';
            }           
        }//end while
         
         
       $h_table .='</ul>';
         
return $h_table;          
    
}

function main_render_quick_messaggi($gas){
     global $RG_addr;
     global $id_user;
     global $RG_lista_argomenti_messaggi,$RG_visibility_messaggi;
    
    // QUERY
      $my_query="SELECT retegas_bacheca.*
                FROM retegas_bacheca
                ORDER BY
                timbro_bacheca DESC
                LIMIT 2;";
      

      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
    $h_table .='<div style="padding:0;margin:0;">
                  <h3>Ultime news :</h3>';
      
         while ($row = mysql_fetch_array($result)){
         
              $c1 = $row["id_bacheca"];
              $c2 = strip_tags($row["titolo_messaggio"]);
              $c6 = substr(strip_tags($row["messaggio"]),0,200)." ...";
              $c4 = $RG_lista_argomenti_messaggi[$row["code_uno"]];
              $c9 = conv_datetime_from_db($row["timbro_bacheca"]);
              $c8 = fullname_from_id($row["id_utente"]);
              $c7 = conv_date_from_db($row["scadenza"]);
              $c5 = $RG_visibility_messaggi[$row["visibility"]];
              
              $c11 = gas_nome(id_gas_user($row["id_utente"]));
              
              
              unset ($op1);
              unset ($op2);
              
             
              
              $permission = $cookie_read[6];
                      
              $is_visible= true;
              $color_vi = "#C8F7F7";
              
              if($row["visibility"]==1){$is_visible= true;$color_vi = "#F7D3C8";}
              if($row["visibility"]==2){
                 
                  if(id_gas_user($row["id_utente"])==$gas){$is_visible= true;$color_vi = "#F4F48A";}else{$is_visible= false;$color_vi = "#969696";}
              }
              if($row["visibility"]==3){ 
                  if($row["id_utente"]==$id_user){$is_visible= true;$color_vi = "#F4CAF4";}else{$is_visible= false;$color_vi = "#969696";}
              }
              
              
              if(user_level($id_user)==5){$is_visible= true;}
        
        $color_visibility = "background-image:-webkit-gradient(linear,0 0,0 100,from($color_vi),to(#DDDDDD));";      
                  
        if($is_visible){                  
        $h_table .= '<div class="ui-widget ui-corner-all padding-6px" style="'.$color_visibility.'">
                    <table>
                    <tr>
                    <td width="10%" style="text-align:left;">
                    <div class="small_link">Argomento:</div>
                    '.$c4.'
                    </td>
                    <td  style="text-align:left;">
                    <div class="small_link">
                    Il '.$c9.', <b>'.$c8.'</b> ('.$c11.') ha scritto:
                    </div>
                    <a href="'.$RG_addr["bacheca_form"].'?id='.$c1.'" rel="'.$c1.'" class="display_full_message"><b>'.$c2.'</b></a><br>
                    <div class="small_link">'.$c6.'</div>
                    </td> 
                    </tr>    
                    </table>
                    </div>
                    <br>';
        }// is visible        
              
         }//end while

      $h_table .='
      </div>
      ';
      
      return $h_table;
}
function main_render_quick_messaggi_pubblici(){
     global $RG_addr;
     global $id_user;
     global $RG_lista_argomenti_messaggi,$RG_visibility_messaggi;
    
    // QUERY
      $my_query="SELECT retegas_bacheca.*
                FROM retegas_bacheca
                WHERE retegas_bacheca.visibility='0'
                ORDER BY
                timbro_bacheca DESC
                ";
      

      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
    $h_table .='<div style="padding:0;margin:0;">
                  <h3>Ultime news :</h3>';
      
         while ($row = mysql_fetch_array($result)){
         
              $c1 = $row["id_bacheca"];
              $c2 = strip_tags($row["titolo_messaggio"]);
              //echo $c2."<br>";
              $c6 = substr(strip_tags($row["messaggio"]),0,200)." ...";
              $c4 = $RG_lista_argomenti_messaggi[$row["code_uno"]];
              $c9 = conv_datetime_from_db($row["timbro_bacheca"]);
              $c8 = fullname_from_id($row["id_utente"]);
              $c7 = conv_date_from_db($row["scadenza"]);
              $c5 = $RG_visibility_messaggi[$row["visibility"]];
              
              $c11 = gas_nome(id_gas_user($row["id_utente"]));
              
              
              unset ($op1);
              unset ($op2);
              
             
              
              //$permission = $cookie_read[6];
                      
              $is_visible= true;
              $color_vi = "#C8F7F7";
              
              if($row["visibility"]==1){$is_visible= true;$color_vi = "#F7D3C8";}
              if($row["visibility"]==2){
                 
                  if(id_gas_user($row["id_utente"])==$gas){$is_visible= true;$color_vi = "#F4F48A";}else{$is_visible= false;$color_vi = "#969696";}
              }
              if($row["visibility"]==3){ 
                  if($row["id_utente"]==$id_user){$is_visible= true;$color_vi = "#F4CAF4";}else{$is_visible= false;$color_vi = "#969696";}
              }
              
              
              if(user_level($id_user)==5){$is_visible= true;}
        
        $color_visibility = "background-image:-webkit-gradient(linear,0 0,0 100,from($color_vi),to(#DDDDDD));";      
                  
        if($is_visible){                  
        $h_table .= '<div class="ui-widget ui-corner-all padding-6px" style="'.$color_visibility.'">
                    <table>
                    <tr>
                    <td width="10%" style="text-align:left;">
                    <div class="small_link">Argomento:</div>
                    '.$c4.'
                    </td>
                    <td  style="text-align:left;">
                    <div class="small_link">
                    '.$c11.' Comunica:
                    </div>
                    <a href="'.$RG_addr["bacheca_form"].'?id='.$c1.'" rel="'.$c1.'" class="display_full_message"><b>'.$c2.'</b></a><br>
                    <div class="small_link">'.$c6.'</div>
                    </td> 
                    </tr>    
                    </table>
                    </div>
                    <br>';
        }// is visible        
              
         }//end while

      $h_table .='
      </div>
      ';
      
      return $h_table;
}
function main_render_quick_messaggi_coda($gas){
     global $RG_addr;
     global $id_user;
     global $RG_lista_argomenti_messaggi,$RG_visibility_messaggi;
    
    // QUERY
      $my_query="SELECT retegas_bacheca.*
                FROM retegas_bacheca
                ORDER BY
                timbro_bacheca DESC
                LIMIT 10;";
//                OFFSET 2;";
      

      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
    
      
         while ($row = mysql_fetch_array($result)){
         
              $c1 = $row["id_bacheca"];
              $c2 = strip_tags($row["titolo_messaggio"]);
              $c6 = substr(strip_tags($row["messaggio"]),0,200)." ...";
              $c4 = $RG_lista_argomenti_messaggi[$row["code_uno"]];
              $c9 = conv_datetime_from_db($row["timbro_bacheca"]);
              $c8 = fullname_from_id($row["id_utente"]);
              $c7 = conv_date_from_db($row["scadenza"]);
              $c5 = $RG_visibility_messaggi[$row["visibility"]];
              
              $c11 = gas_nome(id_gas_user($row["id_utente"]));
              
              
              unset ($op1);
              unset ($op2);
              
             
              
              $permission = $cookie_read[6];
                      
              $is_visible= true;
              $color_vi = "#C8F7F7";
              
              if($row["visibility"]==1){$is_visible= true;$color_vi = "#F7D3C8";}
              if($row["visibility"]==2){
                 
                  if(id_gas_user($row["id_utente"])==$gas){$is_visible= true;$color_vi = "#F4F48A";}else{$is_visible= false;$color_vi = "#969696";}
              }
              if($row["visibility"]==3){ 
                  if($row["id_utente"]==$id_user){$is_visible= true;$color_vi = "#F4CAF4";}else{$is_visible= false;$color_vi = "#969696";}
              }
              
              
              if(user_level($id_user)==5){$is_visible= true;}
        
        $color_visibility = "background-image:-webkit-gradient(linear,0 0,0 100,from($color_vi),to(#DDDDDD));";      
                  
        if($is_visible){                  
        $h_table .= '<div class="ui-widget ui-corner-all;" style="'.$color_visibility.' padding:3px;">
                    <table>
                    <tr>
                    <td>                
                    <div class="small_link" style="text-align:left;">
                    '.$c9.', <b>'.$c8.'</b> ('.$c11.')
                    </div>
                    <div style="text-align:left;">
                    <a href="'.$RG_addr["bacheca_form"].'?id='.$c1.'" rel="'.$c1.'"><b>'.$c2.'</b></a>
                    </div>
                    </td>
                    </tr>
                    </table>
                    </div>
                    <br>';
        }// is visible        
              
         }//end while

      
      
      return $h_table;
}
function main_render_anomalie_utente($id_user){
    global $RG_addr, $db;
    
    
    //SE NON HA INSERITO UN INDIRIZZO VALIDO
    if(lat_lon_from_id($id_user)=='0'){
    $h .='<div class="ui-state-error ui-corner-all padding_6px" style="font-size:1.2em;"><strong>Non è stato trovato nessun indirizzo valido nei dati personali.</strong> Cliccando <a class="medium silver awesome" href="'.$RG_addr["pag_users_form_mia_edit"].'" style="margin-top:.5em"> QUI </a> si può eliminare questo problema.<br>
          Il sito richiede il vostro indirizzo per poter calcolare quanti Km percorre la merce ordinata, e per poter posizionare un "segnalino" corrispondente ad ogni utente nella mappa del GAS.<br>
          Questa mappa è approssimativa, ed è sostanzialmente anonima, per cui gli altri utenti non vedono dove abitate.<br>
          Inoltre il livello di Zoom è bloccato, per cui non ci si può avvicinare più di tanto.</div>';
    $h .='<br>';
    }

    //SE C'E' SOLO IL NOME 
    if(strpos(trim(fullname_from_id($id_user))," ")=== false){      

    $h .='<div class="ui-state-error ui-corner-all padding_6px" style="font-size:4em;"><strong>Non è stato riconosciuto il nome utente (manca il nome o il cognome)</strong> Cliccando <a class="medium silver awesome" href="'.$RG_addr["pag_users_form_mia"].'" style="margin-top:.5em"> QUI </a> si può eliminare questo problema.<br>
    Come linea di principio (leggere il disclaimer) il sito non accetta utenti non identificabili in una persona fisica.</div>';
    $h .='<br>';
    }

    //SE HA UN TELEFONO TROPPO CORTO          
    if(strlen(trim(telefono_from_id($id_user)))<8){      
    $h .='<div class="ui-state-error ui-corner-all padding_6px"><strong>Sembra che il telefono immesso non sia valido.</strong> Cliccando <a class="medium silver awesome" href="'.$RG_addr["pag_users_form_mia"].'" style="margin-top:.5em"> QUI </a> si può eliminare questo problema.</div>';
    $h .='<br>';            
    }                        
    
    
    //SE HA ORDINI DA CONVALIDARE DA PIU' DI 2 SETTIMANE
    $ordini_non_confermati = n_ordini_non_confermati($id_user,20);
    
    if($ordini_non_confermati==1){
        $frase = "C'è un ordine chiuso da più di 20 giorni ma non confermato";
    }else{
        $frase = "Ci sono $ordini_non_confermati ordini chiusi da più di un mese ma non confermati ";
    }
    
    if($ordini_non_confermati > 0){      
    $h .='<div class="ui-state-error ui-corner-all padding_6px" style="font-size:3.5em;"><strong>'.$frase.'</strong></div>';
    
    $h .='<br>';            
    }
    
    //SE HA ORDINI PRENOTATI
    $ordini_prenotati = crea_lista_prenotazioni_attive(_USER_ID);
    if($ordini_prenotati<>""){
        $h .='<div class="ui-state-highlight ui-corner-all padding_6px" style="font-size:1em;">
        <h4>Ricordati che hai questi ordini prenotati !</h4>
        '.$ordini_prenotati.
        '</div>';
    
        $h .='<br>';    
    }
    
    
    //SE HA MESSAGGI NON LETTI
    //$query = "SELECT * FROM retegas_options WHERE id_user='"._USER_ID."' AND chiave='MSG';";
    //$res = $db->sql_query($query);
    //$nr = $db->sql_numrows($query);
    //if ($nr>1){
    //$h_wait .='<div class="ui-state-error ui-corner-all padding_6px" style="font-size:1em;"><strong>Ci sono messaggi del sistema che sembra non ti siano stati presentati.</strong></div>';
    //
    //$h_wait .='<br>';    
    //    
    //}
    
    
    
    
    //echo utenti_attesa_attivazione(id_gas_user($id_user))."-----------";
    $permissions = leggi_permessi_utente($id_user);
    if($permissions & perm::puo_gestire_utenti){
        if(utenti_attesa_attivazione(id_gas_user($id_user))>0){
                    
                    $h .='<div class="ui-state-highlight ui-corner-all padding_6px"><strong>Ci sono nuovi utenti da attivare !!!.</strong> Cliccando <a class="medium silver awesome" href="'.$RG_addr["gas_user_activate"].'" style="margin-top:.5em"> QUI </a> si va nella pagina apposita.</div>';
                    $h .='<br>';  
            
            
        }
    }
    
    if($permissions & perm::puo_gestire_la_cassa){
         $mnr = db_nr_q_condition(" id_gas = '".id_gas_user($id_user)."' AND (registrato = 'no') ","retegas_cassa_utenti");
         if($mnr<>0){
               $h .='<div class="ui-state-highlight ui-corner-all padding_6px"><strong>Ci sono <strong>'.$mnr.'</strong> movimenti di cassa da registrare</strong> <a class="medium silver awesome" href="'.$RG_addr["cassa_movimenti_reg"].'" style="margin-top:.5em">Registra ora</a></div>';
               $h .='<br>';
         }
        
    }
    
        
    
    return $h;
}
function main_render_messaggio_alla_nazione($id_user){
    global $RG_addr;
    
    $man = read_option_text(0,"M_A_NAZIONE");
    
    if(strlen($man)>0){
      $h .='<div class="ui-state-error ui-corner-all padding_6px">'.$man.'</div>';

    }


    
        
    
    return $man;
}

//MOBILE
function main_render_quick_ordini_aperti_mobile($gas){
global $RG_addr;
      
$my_query="SELECT retegas_ordini.id_ordini, 
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
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;

      $result = $db->sql_query($my_query);
        

  
        $riga=0;
        
             
         while ($row = mysql_fetch_array($result)){
         $riga++;
            
          
        $h_table.=  $row["descrizione_ordini"].' , di '.fullname_from_id($row["id_utente"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.
                    <hr>        
                    ';
         }//end while
         
         

         
return $h_table;          
    
}
function main_render_quick_ordini_futuri_mobile($gas){

      
$my_query="SELECT retegas_ordini.id_ordini, 
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
            WHERE (((retegas_ordini.data_apertura)>NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      $result = $db->sql_query($my_query);
        

  
        $riga=0;

          
         while ($row = mysql_fetch_array($result)){

  
         
        $h_table.=  $row["descrizione_ordini"].'</a></b>, di '.fullname_from_id($row["id_utente"]).'
                    <hr>';
         }//end while

         
return $h_table;          
    
}
function main_render_quick_ordini_chiusi_mobile($gas){

      
$my_query="SELECT retegas_ordini.id_ordini, 
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
            WHERE (((retegas_ordini.data_chiusura)<NOW()) AND ((retegas_referenze.id_gas_referenze)=$gas))
            ORDER BY retegas_ordini.data_chiusura DESC
            LIMIT 20 ;";
      
      //echo $my_query;
      
      // COSTRUZIONE TABELLA  -----------------------------------------------------------------------
      global $db;
      global $RG_addr;
      
      $result = $db->sql_query($my_query);


         while ($row = mysql_fetch_array($result)){

         
        $h_table.=  $row["descrizione_ordini"].'</a></b>, di '.fullname_from_id($row["id_utente"]).', tot. '.round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.
                     <hr>
                    ';
         }//end while
         
         
         
return $h_table;          
    
}

function render_form_element_text($position,$variable_name,$variable,$description,$help){

$h='<div>
        <h4>'.$position.'</h4>
        <label for="'.$variable_name.'">'.$description.'</label>
        <input type="text" name="'.$variable_name.'" value="'.$variable.'" size="50"></input>
        <h5 title="'.$help.'">Inf.</h5>
        </div>';
return $h;
      
}
function render_form_element_password($position,$variable_name,$variable,$description,$help){

$h='<div>
        <h4>'.$position.'</h4>
        <label for="'.$variable_name.'">'.$description.'</label>
        <input type="password" name="'.$variable_name.'" value="'.$variable.'" size="50"></input>
        <h5 title="'.$help.'">Inf.</h5>
        </div>';
return $h;
}
function screenshot_sito(){
global $RG_addr;
$h .='
    <h3>Immagini dall\'interno :</h3>
    <ul id="carousel" class="jcarousel-skin-tango">
       <li><img src="'.$RG_addr["img_ss1"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss2"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss3"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss4"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss5"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss6"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss7"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss8"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss9"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss10"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss11"].'" width="320" height="200" alt=""></li>
       <li><img src="'.$RG_addr["img_ss12"].'" width="320" height="200" alt=""></li>          
   </ul>';




return $h;
    
}

//FUNCTION PER FORM
function render_option_from_array($array,$key_selected){
    
if(!empty($array)){
foreach ($array as $i) {
        $h .= $i;
} 
}    

return $h;    
}

//FUNCTION PER RENDER ORDINI (DA SPOSTARE
function render_scheda_pdf_ordine($id_ordine){
            global $db, $RG_addr;
     
     $nome_ordine = descrizione_ordine_from_id_ordine($id_ordine);
     $gas_ordine = gas_nome(id_gas_user(id_referente_ordine_globale($id_ordine)));
     $proprio_gas = gas_nome(_USER_ID_GAS);
     $referente_generale = fullname_referente_ordine_globale($id_ordine);
     $telefono_referente_generale = telefono_from_id(id_referente_ordine_globale($id_ordine));
     $mail_referente_generale = mail_referente_ordine_globale($id_ordine);
     
     $referente_proprio_Gas = fullname_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS);
     $telefono_referente_proprio_gas = telefono_from_id(id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS));
     $mail_referente_proprio_gas = mail_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS);
     
     $listino = id_listino_from_id_ordine($id_ordine); 
     $fornitore = ditta_nome_from_listino($listino);
     $indirizzo_fornitore = ditta_indirizzo_from_listino($listino);
     $mail_fornitore  =ditta_mail_from_listino($listino);
     $telefono_fornitore = ditta_telefono_from_listino($listino);
     
     $data_chiusura = conv_date_from_db(db_val_q("id_ordini",$id_ordine,"data_chiusura","retegas_ordini"));
     $tot_scatole = q_scatole_intere_ordine_arr($id_ordine);
     $tot_articoli = _nf(n_articoli_arrivati_da_id_ordine($id_ordine));
     
     $estremi_gas = strip_tags(gas_estremi(_USER_ID_GAS));
     
     $h_table .=  " <h3>Ordine n.$id_ordine ($nome_ordine)</h3>
                    <table>
                        <tr>
                            <td width=\"50%\" style=\"vertical-align:top\">
                                <table>
                                <tr class=\"titolino\">
                                    <td colspan=2>
                                    Fornitore
                                    </td>
                                </tr>
                                <tr class=\"scheda\">
                                    <th $col_1>Nome</th>
                                    <td $col_2>$fornitore</div>
                                    </td>
                                </tr>      
                                <tr class=\"scheda\">
                                    <th $col_1>Indirizzo</th>
                                    <td $col_2>$indirizzo_fornitore</td>
                                </tr>
                                <tr class=\"scheda\">
                                    <th $col_1>Mail</th>
                                    <td $col_2>$mail_fornitore</td>
                                </tr>
                                <tr class=\"scheda\">
                                    <th $col_1>Telefono</th>
                                    <td $col_2>$telefono_fornitore</td>
                                </tr>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Referente Ordine ($gas_ordine)
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Nome</th>
                                        <td $col_2>$referente_generale</div>
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Telefono</th>
                                        <td $col_2>$telefono_referente_generale</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Mail referente</th>
                                        <td $col_2>$mail_referente_generale</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Estremi GAS</th>
                                        <td $col_2>$estremi_gas</td>
                                    </tr>
                                    
                                </table>
                            </td>
                            <td width=\"50%\" style=\"vertical-align:top\">
                                <table>
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Referente Proprio GAS ($proprio_gas)
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Nome</th>
                                        <td $col_2>$referente_proprio_Gas</div>
                                        </td>
                                    </tr>
                                          
                                    <tr class=\"scheda\">
                                        <th $col_1>Telefono</th>
                                        <td $col_2>$telefono_referente_proprio_gas</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Mail referente</th>
                                        <td $col_2>$mail_referente_proprio_gas</td>
                                    </tr>
                                    
                                    <tr class=\"titolino\">
                                        <td colspan=2>
                                        Ordine
                                        </td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Cod. ordine (uso gas)</th>
                                        <td $col_2>$id_ordine</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Data chiusura</th>
                                        <td $col_2>$data_chiusura</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Tot articoli</th>
                                        <td $col_2>$tot_articoli</td>
                                    </tr>
                                    <tr class=\"scheda\">
                                        <th $col_1>Tot scatole</th>
                                        <td $col_2>$tot_scatole</td>
                                    </tr>                                        
                                   </tr>  
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    ";
return $h_table;                    
}


//FUNCTION VARIE
function rg_toggable($title,$target,$content,$open=false){
        $toggle_note ="<!--
                    <script type=\"text/javascript\">
                    $(document).ready(function() 
                        {
                            var a = localStorage.getItem('$target');
                            if(a!='1px'){
                                /*alert(' $target Diverso da 1 px'); */
                                $('#$target').css('display','none');
                            }else{
                               /* alert(' $target UGUALE a 1 px'); */
                               $('#$target').animate({'height': 'toggle'});
                            }
                        });    
                    </script> --> 
                    <a class=\"small awesome silver destra\"  onclick=\" 
                    $('#$target').animate({'height': 'toggle'}, { duration: 1000}); 
                    localStorage.setItem('$target', $('#$target').css('height'));
                    return false;
                    \"><span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span></a>";
         
         if(!$open){
             $class="style=\"display:none\"";
         }
         $h_table .=" <div class=\"rg_widget rg_widget_helper\">
                    ".$toggle_note."<strong>$title</strong> 
                        <div style=\"float:right\">
                        <span class =\"small_link\">Clicca qua per espandere o ridurre...</span>
                        </div>
                        <div id=\"".$target."\" $class>                    
                            $content
                        </div>
                    </div>
                    ";
    return $h_table;
}


function rg_tooltip($text,$override=false){
    
    if(_USER_USA_TOOLTIPS OR $override){
        return " title=\"$text\" ";
    }else{
        return;
    }
} 
function rg_birra($id_utente){
    global $RG_addr,$db;
    
    //BIRRA------------------------------------------
            $size = rand(10,110);
            $postfix = rand(1,3);
            $limit_day = 180;
            $sql_users= "SELECT DATEDIFF(NOW(),regdate) as diff_date FROM `maaking_users` 
                WHERE userid='$id_utente';";
            $result_days = $db->sql_query($sql_users);
            $row_users = $db->sql_fetchrow($result_days);
            
            $days_of_use = $row_users["diff_date"];
            
            
            if ($days_of_use>$limit_day){
            $birra = "<div id=\"container_birra\" ALIGN=\"CENTER\">
                      <a href=\"".$RG_addr["des_donate"]."\">
                      <img SRC=\"".$RG_addr["birra_".$postfix]."\" 
                                style=\"height:".$size."px;width:".$size."px\" 
                                ".rg_tooltip("Stai usando ReteDes.it da <b>$days_of_use</b> giorni. Pensi che questo strumento ti stia aiutando nel gestire i tuoi acquisti od i tuoi ordini? Valuta allora l'opportunità di offrire una birra al suo autore.<br>
                                         (Clicca sull'immagine per maggiori informazioni)").">
                      </a>
                      </div>";
            }else{
            $birra="";    
            }
            return $birra;
}

function render_container_table_2($a,$b,$perc_a = 50,$perc_b = 50){
    
return <<<TTT
<table>
<tr>
<td style="width:$perc_a%;vertical-align:top">$a</td>
<td style="width:$perc_b%;vertical-align:top">$b</td>
</tr>
</table>    
TTT;
    
}