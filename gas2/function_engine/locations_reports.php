<?php

if(isset($root) AND isset($iroot)){
$locations_reports=Array(// Posizione Pagine Ajax
"report_articoli_per_codice"                    =>$root."ordini/reporting/report_riepilogo_articoli_codice.php",
"report_articoli_per_codice_2"                    =>$root."ordini/reporting/report_riepilogo_articoli_codice_2.php",
"report_riepilogo_note"                    =>$root."ordini/reporting/report_riepilogo_note.php",
"report_note_articolo"                    =>$root."ordini/reporting/report_note_articolo.php",
"report_scatole_intere"             =>$root."ordini/reporting/report_scatole_intere.php",
"report_articoli_per_codice_simple"             =>$root."ordini/reporting/report_riepilogo_articoli_codice_simple.php"

                          );
}



?>