<?php




// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("listini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;
}
   // ISTANZIO un nuovo oggetto "retegas"
	$id=$id_listino;

	if($do=="add"){
		  $msg = listini_add_selected_maga($id_listino,$box_id_articoli,$box_prezzi,$box_codici,$box_scatola,$box_descrizione,$box_minimo);

    }


	$retegas = new sito;




	$retegas->posizione = "Scegli listino Magazzino";




	 $ref_table ="output";


	// assegno la posizione che sarà indicata nella barra info



	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard

	$retegas->sezioni = $retegas->html_standard;


	$retegas->menu_sito[]=listini_menu($user,$id);

	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	$retegas->css_body[] = '<style type="text/css">
								#selectionresult td{padding:0.5em;text-align: left; font-size:0.7em;vertical-align:middle;}
	                            </style>';
	$retegas->java_scripts_top_body[]='
	  <script type="text/javascript">
	  $(function(){
		$("#selection").selectmenu({
			style:"popup",
			width: "25em",
			format: addressFormatting,
			maxHeight : 200
		});
	  });

	  //a custom format option callback
		var addressFormatting = function(text){
			var newText = text;
			//array of find replaces
			var findreps = [
				{find:/^([^\~]+) \~ /g, rep: \'<span class="ui-selectmenu-item-header">$1</span>\'},
				{find:/([^\|><]+) \| /g, rep: \'<span class="ui-selectmenu-item-content">$1</span>\'},
				{find:/([^\|><\(\)]+) (\()/g, rep: \'<span class="ui-selectmenu-item-content">$1</span>$2\'},
				{find:/([^\|><\(\)]+)$/g, rep: \'<span class="ui-selectmenu-item-content">$1</span>\'},
				{find:/(\([^\|><]+\})$/g, rep: \'<span class="ui-selectmenu-item-footer">$1</span>\'}
			];

			for(var i in findreps){
				newText = newText.replace(findreps[i].find, findreps[i].rep);
			}
			return newText;
		}

	  </script>';
	$retegas->java_scripts_top_body[]='
<script type="text/javascript">
$(document).ready(function(){
	$("#selection").change( function() {
		$("#selectionresult").hide();
		$.ajax({
			type: "POST",
			data: "data=" + $(this).val(),
			url: "'.$RG_addr["ajax_articoli"].'",
			success: function(msg){
				$("#result").html("");
				if (msg != ""){
					$("#selectionresult").html(msg);
					$("#selectionresult").show();
					$("#selectionresult").tablesorter({widgets: [\'zebra\'],
												   cancelSelection : true,
												   dateFormat: \'uk\'
					});



				}
				else{
					$("#selectionresult").html("<tr><td><b>Nessun articolo per questo listino...</b></td></tr>");
					$("#selectionresult").show();
				}
			}
		});
	});
});
</script>

	  ';

	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","selectmenu");  // editor di testo

	  // creo  gli scripts per la gestione dei menu

	  $retegas->java_scripts_header[] = java_accordion(null,1); // laterale
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_tablesorter("selectionresult");

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){
		$retegas->messaggio=$msg;
	  }



			// qui ci va la pagina vera e proria

	  $retegas->content  =   listini_form($id)
							.listini_select_maga($id);

	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);



?>
