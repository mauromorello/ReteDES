<?php      
 
// immette i file che contengono il motore del programma
include_once ("rend.php");
include_once ("retegas.class.php");
include_once ("utenti/utenti_render.php");
include_once ("function_engine/functionmsg.php");
$retegas = new sito;


if (_USER_LOGGED_IN){
    go("sommario");
}    

if($do=="do_login"){
    switch (do_login($username,$password,$remember)) {

    case 1:
        //LOGIN OK
        unset($q);
        go("sommario");
        break;
    case 2:
        $msg = "Username o password vuoti";
        unset($q);
        unset($username);
        unset($password);
        break;
    case 3:
        $msg = "Username o Password non riconosciuti :<br> ".
               '<a class="" href="'.$RG_addr["user_forgotten_pwd"].'">Password dimenticata ?</a>  <a class="" href="'.$RG_addr["user_forgotten_usn"].'">Username dimenticato ?</a>';
               break;
        unset($q);
        unset($username);
        unset($password);
        Logout($user);
        break;
    case 4:
        $msg = "Utente non ancora attivato";
        unset($q);
        unset($username);
        unset($password);
        break;
    case 5:
        $extra = read_option_text($id_user,"_NOTE_SUSPENDED");
        $msg = "Utente momentaneamente sospeso:<br>$extra";
        unset($q);
        unset($username);
        unset($password);
        break;         
    case 6:
        $msg = "Account disattivato.";
        unset($q);
        unset($username);
        unset($password);
        break;              
    default:
        pussa_via();
        exit;    
    }
    
}    
 
if($do=="do_register"){
          
          
          
              
          $username = sanitize($username);    
          if(is_empty($username)){
              $err_empty++;
              $msg .= "Manca il nome utente<br>";
          }
          
          
          if(is_empty($password)){
              $err_empty++;
              $msg .= "Manca la prima password<br>";
          }
          
          if(is_empty($password2)){
              $err_empty++;
              $msg .= "Manca la seconda password<br>";
          }
          
          $email = sanitize($email);
          if(is_empty($email)){
              $err_empty++;
              $msg .= "Manca la tua email<br>";
          }
          
          $fullname = sanitize($fullname);
          if(is_empty($fullname)){
                $err_empty++;
                $msg .= "Manca il tuo nome completo<br>";
          }
          
          $tel = sanitize($tel);
          if(is_empty($tel)){
                $err_empty++;
                $msg .= "Manca il tuo recapito telefonico<br>";
          }
          
          
          if($password != $password2){
                $msg .= "Le due password non coincidono<br>"; 
                $err_log++;
          }
          if(strlen($username)>15){
                $msg .= "Il nome utente non puo' essere più lungo di 15 caratteri<br>"; 
                $err_log++;   
          }
          
          if($consenso <> "1"){
                $msg .= "Manca il tuo consenso ad accettare le regole del sito.<br>"; 
                $err_log++;
          }
          
          if($gasappartenenza == "-1"){
                $msg .= "Devi scegliere un gas al quale iscriverti<br>"; 
                $err_log++;
          }
          
          
          if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
                $msg .= "La mail inserita non è stata accettata<br>"; 
                $err_log++;
          }
          
          
          
          $sql_email_check = $db->sql_query("SELECT email FROM maaking_users WHERE email='$email'");
          $sql_username_check = $db->sql_query("SELECT username FROM maaking_users WHERE username='$username'");
          $email_check = $db->sql_numrows($sql_email_check);
          $username_check = $db->sql_numrows($sql_username_check);

          

          if($email_check > 0){
                  $msg .= "Hai inserito una mail che esiste già<br>"; 
                  $err_log++;
                  unset($email);
          }

          if($username_check > 0){
                  $msg .= "Hai inserito un nome utente che esiste già<br>"; 
                  $err_log++;
                  unset($username);
          }


          $err_tot = $err_empty + $err_log;
          
          if($err_tot==0){
          
              
              
            $messaggio_referente = sanitize($messaggio_referente);
          
          
            // USer non ancora attivato
            $isactive = 0;
          
            $code = md5(time());
            $code = rand(0,999999999);
            $subject = "["._SITE_NAME."] Nuovo account - Validazione";

            $md5_password = md5($password);
            $gasappartenenza = intval($gasappartenenza); 
            $consenso=intval($consenso);
            $permessi = leggi_permessi_default($gasappartenenza);
          
            $result = $db->sql_query("INSERT INTO maaking_users (username,password,email,fullname,regdate,isactive,code,id_gas,consenso,tel,user_permission,profile) "
                                                       ."  VALUES('$username','$md5_password','$email','$fullname',NOW(),'$isactive','$code','$gasappartenenza','$consenso','$tel','$permessi','$messaggio_referente');");
            if (!$result) {
                log_me(0,0,"USR","NEW","DB FAIL - Nuovo user da START",null,$fullname." ".$email);
                die('Errore interno: ' . mysql_error().";");
            }
          
          $message_u = message_mail_utente($username,$password,strip_tags($messaggio_referente));
          $message_a = message_mail_admin_gas($username,$fullname,$tel,$code,strip_tags($messaggio_referente),gas_nome($gasappartenenza)); 
          
          
          
          $email_a = id_gas_mail($gasappartenenza);
          $headers = message_headers($site_name,_SITE_MAIL_REAL);
          
          $go1 =  mail($email,$subject,$message_u, $headers);
          sleep(1);
          $go2 =  mail($email_a,$subject,$message_a, $headers);
          sleep(1);
          $go3 =  mail(_SITE_MAIL_LOG,"Nuova attivazione",$message_a, $headers);    
          
          if(!$go1 or!$go2){
             $msg.="Problema durante l'invio della mail";
             log_me(0,0,"USR","NEW","MAIL ERROR da Nuovo user da START",null,$fullname." ".$email);
          }else{
             //go("sommario",0,null,"?q=registrazione_ok");
             log_me(0,0,"USR","NEW","Nuovo user da START",null,$fullname." ".$email);
             $msg = "Registrazione effettuata. Attendi l'attivazione da parte del tuo GAS.";
          }
          
          }    
        
        
    }
     
    // assegno la posizione che sarà indicata nella barra info 
    $retegas->posizione = "Home page";
      
    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = array("html_header",        // Chi sei e dove sei
                                "contenuti");
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = $mio_menu;
 
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standar per la maggior parte delle occasioni
    //$retegas->css = array_merge($retegas->css, $retegas->css_standard);
 
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array_merge(array("rg"), $retegas->java_headers);
    
    $retegas->java_scripts_header[]="<script type=\"text/javascript\" src=\"".$RG_addr["js_head_slide"]."\"></script>";
    $retegas->java_scripts_header[]= java_head_select2();
    $retegas->java_scripts_header[]='<script type="text/javascript">// VERSION: 2.2 LAST UPDATE: 13.03.2012
                                        /* 
                                         * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
                                         * 
                                         * Made by Wilq32, wilq32@gmail.com, Wroclaw, Poland, 01.2009
                                         * Website: http://code.google.com/p/jqueryrotate/ 
                                         */
                                        (function(j){for(var d,k=document.getElementsByTagName("head")[0].style,h=["transformProperty","WebkitTransform","OTransform","msTransform","MozTransform"],g=0;g<h.length;g++)void 0!==k[h[g]]&&(d=h[g]);var i="v"=="\v";jQuery.fn.extend({rotate:function(a){if(!(0===this.length||"undefined"==typeof a)){"number"==typeof a&&(a={angle:a});for(var b=[],c=0,f=this.length;c<f;c++){var e=this.get(c);if(!e.Wilq32||!e.Wilq32.PhotoEffect){var d=j.extend(!0,{},a),e=(new Wilq32.PhotoEffect(e,d))._rootObj;
                                        b.push(j(e))}else e.Wilq32.PhotoEffect._handleRotation(a)}return b}},getRotateAngle:function(){for(var a=[],b=0,c=this.length;b<c;b++){var f=this.get(b);f.Wilq32&&f.Wilq32.PhotoEffect&&(a[b]=f.Wilq32.PhotoEffect._angle)}return a},stopRotate:function(){for(var a=0,b=this.length;a<b;a++){var c=this.get(a);c.Wilq32&&c.Wilq32.PhotoEffect&&clearTimeout(c.Wilq32.PhotoEffect._timer)}}});Wilq32=window.Wilq32||{};Wilq32.PhotoEffect=function(){return d?function(a,b){a.Wilq32={PhotoEffect:this};this._img=this._rootObj=
                                        this._eventObj=a;this._handleRotation(b)}:function(a,b){this._img=a;this._rootObj=document.createElement("span");this._rootObj.style.display="inline-block";this._rootObj.Wilq32={PhotoEffect:this};a.parentNode.insertBefore(this._rootObj,a);if(a.complete)this._Loader(b);else{var c=this;jQuery(this._img).bind("load",function(){c._Loader(b)})}}}();Wilq32.PhotoEffect.prototype={_setupParameters:function(a){this._parameters=this._parameters||{};"number"!==typeof this._angle&&(this._angle=0);"number"===
                                        typeof a.angle&&(this._angle=a.angle);this._parameters.animateTo="number"===typeof a.animateTo?a.animateTo:this._angle;this._parameters.step=a.step||this._parameters.step||null;this._parameters.easing=a.easing||this._parameters.easing||function(a,c,f,e,d){return-e*((c=c/d-1)*c*c*c-1)+f};this._parameters.duration=a.duration||this._parameters.duration||1E3;this._parameters.callback=a.callback||this._parameters.callback||function(){};a.bind&&a.bind!=this._parameters.bind&&this._BindEvents(a.bind)},_handleRotation:function(a){this._setupParameters(a);
                                        this._angle==this._parameters.animateTo?this._rotate(this._angle):this._animateStart()},_BindEvents:function(a){if(a&&this._eventObj){if(this._parameters.bind){var b=this._parameters.bind,c;for(c in b)b.hasOwnProperty(c)&&jQuery(this._eventObj).unbind(c,b[c])}this._parameters.bind=a;for(c in a)a.hasOwnProperty(c)&&jQuery(this._eventObj).bind(c,a[c])}},_Loader:function(){return i?function(a){var b=this._img.width,c=this._img.height;this._img.parentNode.removeChild(this._img);this._vimage=this.createVMLNode("image");
                                        this._vimage.src=this._img.src;this._vimage.style.height=c+"px";this._vimage.style.width=b+"px";this._vimage.style.position="absolute";this._vimage.style.top="0px";this._vimage.style.left="0px";this._container=this.createVMLNode("group");this._container.style.width=b;this._container.style.height=c;this._container.style.position="absolute";this._container.setAttribute("coordsize",b-1+","+(c-1));this._container.appendChild(this._vimage);this._rootObj.appendChild(this._container);this._rootObj.style.position=
                                        "relative";this._rootObj.style.width=b+"px";this._rootObj.style.height=c+"px";this._rootObj.setAttribute("id",this._img.getAttribute("id"));this._rootObj.className=this._img.className;this._eventObj=this._rootObj;this._handleRotation(a)}:function(a){this._rootObj.setAttribute("id",this._img.getAttribute("id"));this._rootObj.className=this._img.className;this._width=this._img.width;this._height=this._img.height;this._widthHalf=this._width/2;this._heightHalf=this._height/2;var b=Math.sqrt(this._height*
                                        this._height+this._width*this._width);this._widthAdd=b-this._width;this._heightAdd=b-this._height;this._widthAddHalf=this._widthAdd/2;this._heightAddHalf=this._heightAdd/2;this._img.parentNode.removeChild(this._img);this._aspectW=(parseInt(this._img.style.width,10)||this._width)/this._img.width;this._aspectH=(parseInt(this._img.style.height,10)||this._height)/this._img.height;this._canvas=document.createElement("canvas");this._canvas.setAttribute("width",this._width);this._canvas.style.position="relative";
                                        this._canvas.style.left=-this._widthAddHalf+"px";this._canvas.style.top=-this._heightAddHalf+"px";this._canvas.Wilq32=this._rootObj.Wilq32;this._rootObj.appendChild(this._canvas);this._rootObj.style.width=this._width+"px";this._rootObj.style.height=this._height+"px";this._eventObj=this._canvas;this._cnv=this._canvas.getContext("2d");this._handleRotation(a)}}(),_animateStart:function(){this._timer&&clearTimeout(this._timer);this._animateStartTime=+new Date;this._animateStartAngle=this._angle;this._animate()},
                                        _animate:function(){var a=+new Date,b=a-this._animateStartTime>this._parameters.duration;if(b&&!this._parameters.animatedGif)clearTimeout(this._timer);else{(this._canvas||this._vimage||this._img)&&this._rotate(~~(10*this._parameters.easing(0,a-this._animateStartTime,this._animateStartAngle,this._parameters.animateTo-this._animateStartAngle,this._parameters.duration))/10);this._parameters.step&&this._parameters.step(this._angle);var c=this;this._timer=setTimeout(function(){c._animate.call(c)},10)}this._parameters.callback&&
                                        b&&(this._angle=this._parameters.animateTo,this._rotate(this._angle),this._parameters.callback.call(this._rootObj))},_rotate:function(){var a=Math.PI/180;return i?function(a){this._angle=a;this._container.style.rotation=a%360+"deg"}:d?function(a){this._angle=a;this._img.style[d]="rotate("+a%360+"deg)"}:function(b){this._angle=b;b=b%360*a;this._canvas.width=this._width+this._widthAdd;this._canvas.height=this._height+this._heightAdd;this._cnv.translate(this._widthAddHalf,this._heightAddHalf);this._cnv.translate(this._widthHalf,
                                        this._heightHalf);this._cnv.rotate(b);this._cnv.translate(-this._widthHalf,-this._heightHalf);this._cnv.scale(this._aspectW,this._aspectH);this._cnv.drawImage(this._img,0,0)}}()};i&&(Wilq32.PhotoEffect.prototype.createVMLNode=function(){document.createStyleSheet().addRule(".rvml","behavior:url(#default#VML)");try{return!document.namespaces.rvml&&document.namespaces.add("rvml","urn:schemas-microsoft-com:vml"),function(a){return document.createElement("<rvml:"+a+\' class="rvml">\')}}catch(a){return function(a){return document.createElement("<"+
                                        a+\' xmlns="urn:schemas-microsoft.com:vml" class="rvml">\')}}}())})(jQuery);
                                        </script>';
    //$retegas->css = $retegas->css_standard;
    
    $retegas->css_header[]="<link type=\"text/css\" href=\"".$RG_addr["css_slide"]."\" rel=\"Stylesheet\">";
    //$retegas->css_header[]="<link type=\"text/css\" href=\"".$RG_addr["css_qtip"]."\" rel=\"Stylesheet\">";
    $retegas->css_header[]="<link type=\"text/css\" href=\"".$RG_addr["css_grid_3"]."\" rel=\"Stylesheet\">";
    $retegas->css_header[]="<link type=\"text/css\" href=\"".$RG_addr["css_awesome"]."\" rel=\"Stylesheet\">";
    //$retegas->css_header[]="<link href='http://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet' type='text/css'>";                                 
    $retegas->css_header[]="<style type=\"text/css\">
                            html {font-family: 'Anaheim', sans-serif;}
                            a:link, a:visited{color:##585858}
                            a:hover {color:#585858}
                            a{color:#585858; text-decoration:none}
                            .retegas_form {
                                text-align : left;
                                background-image:rgba(200,200,200,0.8);
                                font-size:1.1em; border: 0;
                                padding:1em;
                            }
                            .retegas_form h5{display : inline-block; font-size: .8em;color : #580000;}
                            .retegas_form h4{display : inline-block; padding: 0.2em;font-size: 1.3em;color : #580000;margin-top:-0.5em;    margin-bottom: 0.5em; width:2em;}
                            .retegas_form h3{margin-top: -0.2em; margin-bottom : 1.5em; font-size:1em;} 
                            .retegas_form label{padding : 6px;display: inline-block; width : 10em;} 
                            .retegas_form input{font-size: 1em;text-align : left; }
                            .retegas_form input[type=\"submit\"]{
                            background: #008000  url(\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAyCAYAAACd+7GKAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAClJREFUeNpi/v//vwMTAwPDfzjBgMpFI/7hFSOT9Y8qRuF3JLoHAQIMAHYtMmRA+CugAAAAAElFTkSuQmCC\") repeat-x;     
                            display: inline-block;     
                            padding: 0.5em 1em 0.5em;     
                            color: #fff;     
                            text-decoration: none;    
                            -moz-border-radius: 5px;     
                            -webkit-border-radius: 5px;    
                            -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);    
                            -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);    
                            text-shadow: 0 -1px 1px rgba(0,0,0,0.25);    
                            border-bottom: 1px solid rgba(0,0,0,0.25);
                            border:0;    
                            position: relative;    
                            cursor: pointer;
                            font-weight:bold;
                            }       
                            .retegas_form input{margin:2px;padding:0.5em;border:solid 2px grey;}
                            .retegas_form select{margin:2px;padding:0.3em;border:solid 2px grey;}
                            .retegas_form input[type=\"submit\"]:hover{background: #00AE00  url(\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAyCAYAAACd+7GKAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAClJREFUeNpi/v//vwMTAwPDfzjBgMpFI/7hFSOT9Y8qRuF3JLoHAQIMAHYtMmRA+CugAAAAAElFTkSuQmCC\") repeat-x; text-align:center}
                            .retegas_form div{border: 1px solid #F0F0F0;}
                            .retegas_form fieldset{border: 0 !important}
                            
                            .ui-tooltip, .qtip{
                                position: absolute;
                                left: -28000px;
                                top: -28000px;
                                display: none;

                                max-width: 480px;
                                min-width: 50px;
                                

                                border-width: 2px;
                                border-style: solid;
                            }

                                /* Fluid class for determining actual width in IE */
                                .ui-tooltip-fluid{
                                    display: block;
                                    visibility: hidden;
                                    position: static !important;
                                    float: left !important;
                                }

                                .ui-tooltip-content{
                                    position: relative;
                                    padding: 5px 9px;
                                    overflow: hidden;

                                    text-align: left;
                                    word-wrap: break-word;
                                    overflow: hidden;
                                }

                                .ui-tooltip-titlebar{
                                    position: relative;
                                    min-height: 14px;
                                    padding: 5px 35px 5px 10px;
                                    overflow: hidden;

                                    border-width: 0 0 1px;
                                    font-weight: bold;
                                }

                                .ui-tooltip-titlebar + .ui-tooltip-content{ border-top-width: 0px !important; }

                                    /*! Default close button class */
                                    .ui-tooltip-titlebar .ui-state-default{
                                        position: absolute;
                                        right: 4px;
                                        top: 50%;
                                        margin-top: -9px;

                                        cursor: pointer;
                                        outline: medium none;

                                        border-width: 1px;
                                        border-style: solid;
                                    }
                                    
                                    * html .ui-tooltip-titlebar .ui-state-default{ top: 16px; } /* IE fix */

                                    .ui-tooltip-titlebar .ui-icon,
                                    .ui-tooltip-icon .ui-icon{
                                        display: block;
                                        text-indent: -1000em;
                                    }

                                    .ui-tooltip-icon, .ui-tooltip-icon .ui-icon{
                                        -moz-border-radius: 3px;
                                        -webkit-border-radius: 3px;
                                        border-radius: 3px;
                                    }

                                        .ui-tooltip-icon .ui-icon{
                                            width: 18px;
                                            height: 14px;

                                            text-align: center;
                                            text-indent: 0;
                                            font: normal bold 10px/13px Tahoma,sans-serif;

                                            color: inherit;
                                            background: transparent none no-repeat -100em -100em;
                                        }


                            /* Applied to 'focused' tooltips e.g. most recently displayed/interacted with */
                            .ui-tooltip-focus{

                            }

                            /* Applied on hover of tooltips i.e. added/removed on mouseenter/mouseleave respectively */
                            .ui-tooltip-hover{
                                
                            }


                            /*! Default tooltip style */
                            .ui-tooltip-default{
                                border-color: #F1D031;
                                background-color: #FFFFA3;
                                color: #555;
                            }

                                .ui-tooltip-default .ui-tooltip-titlebar{
                                    background-color: #FFEF93;
                                }

                                .ui-tooltip-default .ui-tooltip-icon{
                                    border-color: #CCC;
                                    background: #F1F1F1;
                                    color: #777;
                                }
                                
                                .ui-tooltip-default .ui-tooltip-titlebar .ui-state-hover{
                                    border-color: #AAA;
                                    color: #111;
                                }

                            /* Tips plugin */
                            .ui-tooltip .ui-tooltip-tip{
                                margin: 0 auto;
                                overflow: hidden;
                                z-index: 10;
                            }

                                .ui-tooltip .ui-tooltip-tip,
                                .ui-tooltip .ui-tooltip-tip *{
                                    position: absolute;
                                    
                                    line-height: 0.1px !important;
                                    font-size: 0.1px !important;
                                    color: #123456;

                                    background: transparent;
                                    border: 0px dashed transparent;
                                }
                                
                                .ui-tooltip .ui-tooltip-tip canvas{ top: 0; left: 0; }


                            /* Modal plugin */
                            #qtip-overlay{
                                position: fixed;
                                left: -10000em;
                                top: -10000em;
                            }

                                /* Applied to modals with show.modal.blur set to true */
                                #qtip-overlay.blurs{ cursor: pointer; }

                                /* Change opacity of overlay here */
                                #qtip-overlay div{
                                    position: absolute;
                                    left: 0; top: 0;
                                    width: 100%; height: 100%;

                                    background-color: black;

                                    opacity: 0.7;
                                    filter:alpha(opacity=70);
                                    -ms-filter:\"progid:DXImageTransform.Microsoft.Alpha(Opacity=70)\";
                                }

                            /*! Light tooltip style */
                            .ui-tooltip-light{
                                background-color: #F0F0F0;
                                border-color: #E2E2E2;
                                color: #454545;
                            }

                                .ui-tooltip-light .ui-tooltip-titlebar{
                                    background-color: #f1f1f1;
                                }
                             

                            /*! Add shadows to your tooltips in: FF3+, Chrome 2+, Opera 10.6+, IE9+, Safari 2+ */
                            .ui-tooltip-shadow{
                                -webkit-box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
                                -moz-box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
                                box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
                            }

                            /*! Add rounded corners to your tooltips in: FF3+, Chrome 2+, Opera 10.6+, IE9+, Safari 2+ */
                            .ui-tooltip-rounded,
                            .ui-tooltip-tipsy,
                            .ui-tooltip-bootstrap{
                                -moz-border-radius: 5px;
                                -webkit-border-radius: 5px;
                                border-radius: 5px;
                            }


                            /* Tipsy style */
                            .ui-tooltip-rg{
                                background: white;
                                background: rgba(240, 240, 250, .95);

                                color: #585858;
                                

                                font-size: 1.2em !important;
                                border: 0.3em solid rgba(60, 60, 60, .7) !important;
                            }

                                .ui-tooltip-rg .ui-tooltip-titlebar{
                                    padding: 6px 35px 0 10;
                                    background-color: transparent;
                                }

                                .ui-tooltip-rg .ui-tooltip-content{
                                    padding: 6px 10;
                                }
                                
                                .ui-tooltip-rg .ui-tooltip-icon{
                                    border-color: #222;
                                    text-shadow: none;
                                }

                                .ui-tooltip-rg .ui-tooltip-titlebar .ui-state-hover{
                                    border-color: #303030;
                                }

                             

                            /* IE9 fix - removes all filters */
                            .ui-tooltip:not(.ie9haxors) div.ui-tooltip-content,
                            .ui-tooltip:not(.ie9haxors) div.ui-tooltip-titlebar{
                                filter: none;
                                -ms-filter: none;
                                
                                
                            }
                            
                       
                            
                            </style>"; 
  
      
    
      $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");
      $retegas->java_scripts_bottom_body[]="<script type=\"text/javascript\">
                                            $('#slide_all').liquidSlider({  
                                                                            hashLinking: true,
                                                                            hashNames: false,
                                                                            crossLinks: true,
                                                                            dynamicTabs: false
                                                                            });                                                                                                                
                                            </script>";
     $retegas->java_scripts_bottom_body[] = ' <script>
                                                    $(document).ready(function() { $("#lista_gas").select2(); });
                                                </script>';                                                             

        $h .= "<div class=\"clear\" style=\"margin-top:1em\">&nbsp;</div>";
        $h .= "<div class=\"container_3\">";
            $h .= "<div class=\"grid_1\">";    
            $h .= "<div id=\"title_main\" style=\"font-size:4em\"><a href=\"#1\" data-liquidslider-ref=\"slide_all\" onClick=\"$('#logo_retedes').rotate({animateTo:0});\">ReteDes.it</a></div>";
            $h .= "</div>";
        
            $h .= "<div class=\"grid_1\"><center>";    
                $h .= "<img id=\"logo_retedes\" src=\"".$RG_addr["img_logo_retedes"]."\">";
            $h .= "</center></div>";
        
            $h .= "<div class=\"grid_1\">";    
            $h .= "<div style=\"font-size:1.5em; text-align:right\">Il gestionale artigianale<br>
                                                              che permette la condivisione di ordini tra reti di GAS.</div>";
            $h .= "</div>";
            $h .= "<div class=\"clear\">&nbsp;</div>";
        
        // BOTTONI GROSSI
        
            $h .= "<center>";
            $h .= "<div class=\"grid_1\" style=\"margin-top:1em; margin-bottom:1em;\">";
                $h .= "<a href=\"#2\" data-liquidslider-ref=\"slide_all\" class=\"awesome orange large\" style=\"font-size:1.5em;\" onClick=\"$('#logo_retedes').rotate({animateTo:-45});\">";
                   // $h .= "<div style=\"margin-top:1em; margin-bottom:1em;\">";
                        $h .= "LOGIN";    
                    //$h .= "</div>";
                $h .= "</a>";
            $h .= "</div>";
            
            $h .= "<div class=\"grid_1\" style=\"margin-top:1em; margin-bottom:1em;\">";
                $h .= "<a href=\"#3\" data-liquidslider-ref=\"slide_all\" class=\"awesome yellow large\" style=\"font-size:1.5em;\" onClick=\"$('#logo_retedes').rotate({animateTo:0});\">";
                    //$h .= "<div style=\"margin-top:1em; margin-bottom:1em;\">";
                        $h .= "REGISTRATI";
                    //$h .= "</div>";
                $h .= "</a>";
            $h .= "</div>";
            
            $h .= "<div class=\"grid_1\" style=\"margin-top:1em; margin-bottom:1em;\">";
                $h .= "<a href=\"#4\" data-liquidslider-ref=\"slide_all\" class=\"awesome green large\" data-liquidslider-ref=\"slide_all\" style=\"font-size:1.5em;\" onClick=\"$('#logo_retedes').rotate({animateTo:45});\">";
                    //$h .= "<div style=\"margin-top:1em; margin-bottom:1em;\">";
                        $h .= "CONTATTI";
                    //$h .= "</div>";
                $h .= "</a>";
            $h .= "</div>";
            $h .= "</center>";
            $h .= "<div class=\"clear\">&nbsp;</div>";
        
        //TABS
        
        
        
         
            $h .= "<div class=\"grid_3\" style=\"margin-top:2em; font-size:1.2em\">";
                $h .= "
                        <div class=\"liquid-slider\"  id=\"slide_all\" >
                            <section>
                                <h2 class=\"title\">Il gestionale</h2>
                                <p>ReteDES.it (Rete dei DES) è un social-strumento informatico a \"Km 0\" pensato e realizzato per
                                aiutare ad organizzare e semplificare la gestione degli acquisti all'interno dei GAS aderenti a questo progetto, 
                                creando sul territorio una rete di collaborazione flessibile e dinamica.
                                </p>
                                <p>Si rivolge a GAS, o gruppi GAS (DES) che stanno nascendo o che sono già consolidati, proponendo gratuitamente una
                                piattaforma in grado di essere adattata alle singole esigenze, mantenendo comunque una
                                struttura di base comune.
                                </p>
                                <h4>Alcune caratteristiche</h4>
                                <ul>
                                <li>Può gestire <cite>n</cite> DES.</li>
                                <li>I DES servono a raggruppare dati e statistiche a livello di macrozone. Ogni gas può comunque condividere 
                                i propri ordini con qualsiasi altro gas iscritto a retedes.</li>
                                <li>Può gestire <cite>n</cite> Gas.</li>
                                <li>Ogni Gas può usarlo indipendentemente oppure allacciandosi agli altri.</li>
                                <li>Ogni utente può gestire un sottoinsieme di <cite>n</cite> amici</li>
                                <li>Si possono inserire Fornitori, listini, articoli, che a scelta dell'utente potranno essere privati oppure condivisi con tutta la rete.</li>
                                <li>Si possono gestire gli ordini, sia Privati per GAS che Condivisi tra più gas a scelta.</li>
                                <li>L'ordine di ogni utente può essere ripartizionato liberamente alll'interno della cerchia dei suoi amici</li>
                                <li>Si possono assegnare livelli di operatività diversi utente per utente</li>
                                <li>C'è un sistema di messaggistica automatica (scadenze, nuovi ordini) configurabile da ogni utente</li>
                                <li>Ogni utente può personalizzare la propria HomePage, con una serie di widget già pronti, liberamente posizionabili sull'area di lavoro.</li>
                                <li>Gestione delle valutazioni, certificazioni, opinioni e commenti da parte degli organismi DES, dei gestori Ordini, e dei partecipanti.</li>
                                <li>Reportistica in HTML, excel, word e PDF</li>
                                <li><strong>gestione BARATTI, BANCA DEL TEMPO, attraverso la collaborazione con <a style=\"color:#800000\" href=\"http://www.coseinutili.it\" TARGET=\"_blank\">www.coseinutili.it</a></strong></li>
                                <li>Ogni gas e DES può avere un codice privato da usare in abbinamento con widget WordPress, in modo da avere sul proprio sito un dialogo personalizzato con il gestionale. (beta)</li>
                                <li>Statistiche spesa a livello di utente, Gas e DES</li>
                                <li>Hashtags twitter personalizzabili, ogni gas può generare tweet in base alle operazioni effettuate.</li>
                                <li>Ogni Gas può gestire un proprio flusso RSS</li>
                                <li><strong>Gestione virtuale della cassa (niente più monetine alle riunioni !!)</strong></li>
                                <li>Geolocalizzazione degli utenti, statistiche su accessi e operatività </li>
                                <li>Storici di tutti gli ordini a livello di utente, gas e Retegas, tabulari e grafici</li>
                                <li>Ampia parte di aiuto (wiki.retegas.info)</li>
                                <li>Versione per dispositivi mobili (beta), visibile su m.retegas.info</li>
                                <li>Si basa su software autoprodotto artigianalmente in cantina.</li>
                                <li>Codice libero e gratuito consultabile su <a style=\"color:#800000\" target=\"_blank\" href=\"https://github.com/mauromorello/ReteDES\">GitHUB</a></li>
                                
                                </ul>
                                
                            </section>
                            
                            
                            <section>
                            <h2 class=\"title\" >LOGIN</h2>
                                
                                <form method=\"POST\" action=\"index_start.php\">
                                    <div class=\"container_3\">
                                        
                                        <div class=\"grid_3\" style=\"color:#800000; font-size:1.3em; text-align:center; margin:1em; background-color:rgba(200,30,30,.2);\">
                                            $msg
                                        </div>
                                   
                                    <div class=\"clear\">&nbsp;</div>
                                    <div class=\"grid_1\">
                                        <center>
                                            <label for=\"username\">Username</label>
                                            <input style=\"font-size:1.2em;\" id=\"username\"  type=\"text\" size=\"15\" name=\"username\">
                                        </center>
                                    </div>
                                    <div class=\"grid_1\">
                                        <center>
                                            <label for=\"username\">Password</label>
                                            <input style=\"font-size:1.2em;\" id=\"password\"  type=\"password\" size=\"15\" name=\"password\">
                                        </center>
                                    </div>
                                    <div class=\"grid_1\">
                                        <center>
                                            <input type=\"hidden\"  name=\"do\" value=\"do_login\">
                                            <label for=\"remember\">Ricordami su questo computer</label><br>
                                            <input id=\"remember\" type=\"checkbox\"  name=\"remember\" value=\"remember\">
                                        </center>
                                    </div>
                                        <div class=\"clear\">&nbsp;</div>
                                        <div class=\"prefix_1 grid_1\" style=\"margin-top:1em; margin-bottom:1em;\">
                                            <center>
                                                <input style=\"font-size:1.4em; padding:1em;\" class=\"awesome silver\" type=\"submit\" name=\"submit\" value=\"ENTRA\">
                                            </center>
                                        </div>
                                    </div>
                                
                                 </form>
                                
                            </section>
                            <section>
                                <h2 class=\"title\">Registrazione</h2>
                                <div class=\"container_3\">
                                    <div class=\"grid_3\" style=\"color:#800000; font-size:1.3em; text-align:center; margin:1em; background-color:rgba(200,30,30,.2);\">
                                        $msg
                                    </div>
                                </div>
                                ".utenti_render_register_form()."
                            </section>
                            <section>
                                <h2 class=\"title\">Contatti & Links</h2>
                                <p>
                                <ul>
                                    <li>Autore: <b>Mauro Morello</b>, ma.morez (at) tiscali.it</li>
                                    <li>Info sul sito: <b>Amministratore</b>, retegas.ap (at) gmail.com</li>
                                    <li>Istruzioni, Disclaimer & regolamento: <a style=\"color:#800000\" href=\"http://wiki.retedes.it\" TARGET=\"_blank\"><b>wiki.retedes.it</b></a></li>
                                    <li>Confronta altri gestionali gas <a style=\"color:#800000\" href=\"http://it.wikipedia.org/wiki/Software_gestionale_GAS#Informazioni_generali\">QUA</a></li>
                                    <li>Feed RSS: Fare riferimento al proprio gas.</li>
                                    <li>Twitter: segui l'hashtag <b>#retedes</b></li>
                                    <li><a style=\"color:#800000\" href=\"http://www.coseinutili.it\" TARGET=\"_blank\"><b>Cose(in)utili</b></a>: Il sito di baratto e banca del tempo</li>
                                    <li>Codice sorgente su <a style=\"color:#800000\" target=\"_blank\" href=\"https://github.com/mauromorello/ReteDES\">GitHUB</a></li>
                                    
                                </ul>                 
                                </p>
                            </section>
                        </div>";
                        
            $h .= "</div>";
      $h .= "<div class=\"clear\">&nbsp;</div>";
      

      $h .= "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
      $h .= "";
      $h .= "";
      $h .= "";
      $h .= "";
      
          // qui ci va la pagina vera e proria  
      $retegas->content = $h;
      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      
      
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);