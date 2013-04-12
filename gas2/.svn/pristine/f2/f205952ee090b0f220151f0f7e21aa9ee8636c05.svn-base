<?php
include_once("../rend.php");

if(isset($id_messaggio)){
    
    $sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio > '$id_messaggio' ORDER BY id_messaggio LIMIT 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    $next = "<a class=\"awesome transparent big\" onclick=\"
                                                            $.ajax({
                                                              url: '".$RG_addr["ajax_schedina_log"]."?id_messaggio=".$row[0]."',
                                                              success: function(data) {
                                                                $('#log_container').html(data);
                                                              }
                                                            });
                                                            \">SUCCESSIVO</a>";

    $sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio < '$id_messaggio' ORDER BY id_messaggio DESC LIMIT 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    $previous = "<a class=\"awesome transparent big\" onclick=\"
                                                        $.ajax({
                                                          url: '".$RG_addr["ajax_schedina_log"]."?id_messaggio=".$row[0]."',
                                                          success: function(data) {
                                                            $('#log_container').html(data);
                                                          }
                                                        });
                                                        \">PRECEDENTE</a>";

    $sql = "SELECT * FROM retegas_messaggi WHERE id_messaggio='$id_messaggio' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);

    //$h .="<div id=\"log_container\" class=\"rg_widget rg_widget_helper\">";
    $h .="<center>$previous <strong>$id_messaggio</strong> $next</center>";
    $h .="<p><strong>Id_messaggio: </strong>".$row["id_messaggio"]."</p>";
    $h .="<p><strong>id_user: </strong>".$row["id_user"]." - ".fullname_from_id($row[1])." ".gas_nome(id_gas_user($row[1]))."</p>";
    $h .="<p><strong>id_ordine: </strong>".$row["id_ordine"]." - ".fullname_from_id($row[2])." ".descrizione_ordine_from_id_ordine($row[2])."</p>";
    $h .="<p><strong>messaggio: </strong>".$row["messaggio"]."</p>";
    $h .="<p><strong>Timestamp: </strong>".$row["timbro"]."</p>";
    $h .="<p><strong>COD 1: </strong>".$row["tipo"]."</p>";
    $h .="<p><strong>COD 2: </strong>".$row["tipo2"]."</p>";
    $h .="<p><strong>Valore: </strong>".$row["valore"]."</p>";
    $h .="<p><strong>Verbose: </strong>".$row["query"]."</p>";
    //$h .="</div>";

    echo $h;

}  
?>