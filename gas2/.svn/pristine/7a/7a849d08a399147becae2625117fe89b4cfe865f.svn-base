<?php
$page_start = array_sum(explode(' ', microtime()));

include ("../rend.php");
include ("ptp_functions.php");


if($do=="logout"){
         unset($user);
         setcookie("user","",time()-3600,"/gas2/");
         setcookie("user","",time()-3600,"/gas2");
         setcookie("user","",time()-3600,"/");
         setcookie("user","",time()-3600,"/gas2/mobile");
         setcookie("user","",time()-3600,"/gas2/ptp");
         setcookie("user","",time()-3600,"/gas2/mobile/");
         setcookie("user","",time()-3600,"/gas2/ptp/");
         header("Location: index.php");
}

if($do=="login"){
    switch (do_login($username,$password,$remember)) {

    case 1:
        //LOGIN OK
        header("Location: index.php");
        break;
    case 2:
        $msg = _EMPTY_UNAME_OR_PASSWORD;
        unset($q);
        unset($username);
        unset($password);
        break;
    case 3:
        $msg = _UNAME_OR_PWD_NOT_RECOGNIZED;
        break;
        unset($q);
        unset($username);
        unset($password);
        break;
    case 4:
        $msg = _NOT_YET_ACTIVED;
        unset($q);
        unset($username);
        unset($password);
        break;        
    default:
        pussa_via();
        exit;    
    }
    
}

$msg = "<h2>$msg</h2>";

if(!_USER_LOGGED_IN){
$h='<form method="POST">
    <label for="username">Nome utente</label>
    <input type="text" name="username"><br>
    <label for="password">Password</label>
    <input type="password" name="password"><br>
    <label for="remember">Ricordami</label>
    <input type="checkbox" name="remember" value="1">
    <input type="hidden" name="do" value="login">
    <input type="submit" value="login">
    </form>
    ';    
    
}else{
    
$h="<p>Utente ".fullname_from_id(_USER_ID)."</p>".ptp_menu();    
    
}
   
echo ptp_header().
     ptp_head().
     $msg.
     $h.
     ptp_footer(); 
?>   