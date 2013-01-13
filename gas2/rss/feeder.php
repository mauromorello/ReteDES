<?php

include("../rend.php");
include("../lib/rss_feeder/FeedWriter.php");

if(is_empty($auth)){
    die("No AUTH");
}  

if(is_empty($req)){
    die("No REQ");
}else{
    $req=CAST_TO_INT($req,0);
}

$id_gas = mimmo_decode($auth);


switch($req){

    
//ORDINI CHIUSI 
case 2:
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
            WHERE (((retegas_ordini.data_chiusura)<NOW())  AND ((retegas_referenze.id_gas_referenze)='$id_gas'))
            ORDER BY retegas_ordini.data_chiusura DESC LIMIT 20;";
      

   $result = $db->sql_query($my_query);


            //Creating an instance of FeedWriter class. 
    //The constant ATOM is passed to mention the version
    $TestFeed = new FeedWriter(ATOM);

    //Setting the channel elements
    //Use wrapper functions for common elements
    $TestFeed->setTitle('Ordini chiusi '.gas_nome($id_gas));
    $TestFeed->setLink($RG_addr["ordini_chiusi"]);
    
    //For other channel elements, use setChannelElement() function
    $TestFeed->setChannelElement('updated', date(DATE_ATOM , time()));
    $TestFeed->setChannelElement('author', array('name'=>gas_nome($id_gas)));

    //Adding a feed. Genarally this protion will be in a loop and add all feeds.

    while ($row = $db->sql_fetchrow($result)){
            
        if(check_option_order_blacklist($id_gas,$row["id_ordini"])==0){       
            //Create an empty FeedItem
            $newItem = $TestFeed->createNewItem();
            
            //Add elements to the feed item
            //Use wrapper functions to add common feed elements
            $msg = "Ord. ".$row["id_ordini"]." ".$row["descrizione_ordini"].", di ".fullname_from_id($row["id_utente"]); 
            $msg_2 ="Chiuso il ".conv_datetime_from_db($row["data_chiusura"]).", per un totale di ".round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.<br>
                     Ditta <a>'.$row["descrizione_ditte"]."</a>, Gestito da ".$row["descrizione_gas"];
            //echo $msg_2."<br>";
            $newItem->setTitle($msg);
            $newItem->setLink($RG_addr["ordini_form"].'?id_ordine='.$row["id_ordini"]);
            $newItem->setDate(time());
            //Internally changed to "summary" tag for ATOM feed
            $newItem->setDescription($msg_2);
            
            
            //Now add the feed item    
            $TestFeed->addItem($newItem);
        }
    
    }//end while
    

    
    //OK. Everything is done. Now genarate the feed.
    $TestFeed->genarateFeed();
    die();


//ORDINI APERTI    
case 1:
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
            WHERE (((retegas_ordini.data_chiusura)>NOW()) AND ((retegas_ordini.data_apertura)<NOW()) AND ((retegas_referenze.id_gas_referenze)='$id_gas'))
            ORDER BY retegas_ordini.data_chiusura ASC ;";
      

   $result = $db->sql_query($my_query);


            //Creating an instance of FeedWriter class. 
    //The constant ATOM is passed to mention the version
    $TestFeed = new FeedWriter(ATOM);

    //Setting the channel elements
    //Use wrapper functions for common elements
    $TestFeed->setTitle('Ordini aperti '.gas_nome($id_gas));
    $TestFeed->setLink($RG_addr["ordini_aperti"]);
    
    //For other channel elements, use setChannelElement() function
    $TestFeed->setChannelElement('updated', date(DATE_ATOM , time()));
    $TestFeed->setChannelElement('author', array('name'=>gas_nome($id_gas)));

    //Adding a feed. Genarally this protion will be in a loop and add all feeds.

    while ($row = $db->sql_fetchrow($result)){
            
         if(id_referente_ordine_proprio_gas($row["id_ordini"],$id_gas)>0){
             $pal = 'PARTECIPABILE';
         }else{
             $pal = 'MANCA REFERENTE'; 
         }
         
               
        //Create an empty FeedItem
        $newItem = $TestFeed->createNewItem();
        
        //Add elements to the feed item
        //Use wrapper functions to add common feed elements
        $newItem->setTitle("Ord. ".$row["id_ordini"]." ".$row["descrizione_ordini"].", di ".fullname_from_id($row["id_utente"]));
        $newItem->setLink($RG_addr["ordini_form_new"].'?id_ordine='.$row["id_ordini"]);
        $newItem->setDate(time());
        //Internally changed to "summary" tag for ATOM feed
        $newItem->setDescription($pal.", scade il ".conv_datetime_from_db($row["data_chiusura"]).", ad oggi vale ".round(valore_totale_ordine($row["id_ordini"]),2) .' Eu.');
        
        
        //Now add the feed item    
        $TestFeed->addItem($newItem);

    
    }//end while
    

    
    //OK. Everything is done. Now genarate the feed.
    $TestFeed->genarateFeed();
    die();
       
}