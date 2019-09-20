<?php 

if ($REX['REDAXO']!=1) {

 if($_SERVER['SERVER_PORT']  != 443)  
   { 
        $datei = $_SERVER['REQUEST_URI']; 
        $ziel = 'https://www.yourdomain.tld'.$datei;      
        header("Location: $ziel"); 
        exit();    
   } 

}



// HTML-Vorlage 
// HEADER
$doformhtml='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=REX_VALUE[9]" />
<title>do form! message</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 0.86em;
	color: #003366;
	line-height: 1em;
	background-color: #F9F9F9;
}
h1 { color: #003366;
	background-color: #FFFFCC;
	display: block;
	clear: both;
	font-size: 1.2em;
	}

.dfheader {
	border-top-width: 6px;
	border-top-style: solid;
	border-top-color: #999999;
	color: #FFFFFF;
	background-color: #003366;;
	padding-top: 0px;
	padding-right: 2px;
	padding-bottom: 0px;
	padding-left: 2px;
	text-align: center;
	margin: 0px;
}
.slabel {
	width: 230px;
	display: block;
	float: left;
	margin-right: 5px;
	color: #666666;
	font-weight: normal;
}
br {
	clear: both;
	display: block;
}
-->
</style>

</head>
<body>
<div class="dfheader">
  Online-Formular - REX_VALUE[4]
</div>
';
// footer
$doformhtmlfooter='<hr size="1" /><br />
<strong>SBM-Mail-System 2</strong> Diese Daten stammen von: https://www.domain.tld'.$_SERVER["REQUEST_URI"].'<br /> </body></html>';

$nonhtmlfooter="\n----------------------------------
\n  Verwendetes Formular: https://www.domain.tld".$_SERVER["REQUEST_URI"]." ";








//--------------------------------------------- 
// Formulargenerator 
// MODUL-EINGABE
// basiert auf:  REDAXO 3.2 Formulargenerator
// mit Captcha-Unterstützung
// SPAMSCHUTZ per ZEITABFRAGE
// Stand: 16.04.2008
// Erweitert von: Thomas Skerbis
// www.klxm.de
//
// 
// Basiert auf das Original aus Redaxo 3.2 Demo 
//
// Danke an: 
// Markus "Zonk" Lorch, Markus Feustel, Harry Brader 
//--------------------------------------------- 


define ('Zeit', time()); // Startzeit des Scripts setzen


#### Achtung! Hinter <<< END darf kein Leerzeichen stehen.
$rex_form_data = <<<End
REX_HTML_VALUE[3]
End;

#### Achtung! Hinter <<< END darf kein Leerzeichen stehen.
$mailbody = <<<End
End;

$responsemail = <<<End
REX_HTML_VALUE[5]
End;

$responsemail = <<<End
REX_HTML_VALUE[5]
End;

$formdatum = date("d.m.Y");
$formzeit = date("H:i")." Uhr"; 
#echo $formdatum." ".$formzeit; 
$responsemail  = str_replace("%Datum%", $formdatum, $responsemail);
$responsemail  = str_replace("%Zeit%", $formzeit, $responsemail);
//Adresse die als Absenderadresse der Bestätigungs-Email eingegeben wurde
$responsemail  = str_replace("%Absender%", "REX_VALUE[2]", $responsemail);
//Empfänderadresse die im Modul angegeben wurde
$responsemail  = str_replace("%Mail%", "REX_VALUE[1]", $responsemail);



#$dcid="22"; // ID zum Captcha-Artikel
#$captchasource= rex_getUrl($dcid);
##########################################
// 
// Das Template gibt es hier:
// http://www.redaxo.de/168-Templatedetails.html?template_id=82
###### Externe Einbindung eines Captchas  ##########
$captchasource="/captcha/index.php";
####################################################




$formname = "rexform"; 
# $formtitel = "REX_VALUE[8]"; 
$submitlabel = "REX_VALUE[7]"; 
// $FORM = rex_post('FORM', 'array'); // Wird für Redaxo 4.x benötigt
$formoutput = array(); 
$warning = array(); 
$form_elements = array(); 
$form_elements = explode("\n", $rex_form_data); 

##### Definition Ausgabe der Fehlermeldung ########
// Stildefinition 
$style = ' class="formerror"'; // siehe CSS
#$style = ' style="color:#990000;"'; // Alternative


// Meldung:
# Sprache 0 // Hier Deustch 
if ($REX['CUR_CLANG']=="0")
{
#### Achtung! Hinter <<< END darf kein Leerzeichen stehen.
$fError= <<<EOD
Bei der Eingabe traten Fehler auf. <br /> Bitte korrigieren Sie Ihre Angaben.
EOD;
}
# Sprache 1 //z.B. Englisch
if ($REX['CUR_CLANG']=="1")
{
#### Achtung! Hinter <<< END darf kein Leerzeichen stehen.
$fError= <<<EOD
Please correct your Input
EOD;
}





for($i=0;$i<count($form_elements);$i++){ 

   $element = explode("|", $form_elements[$i]); 
   $AFE[$i] = $element; 

   switch($element[0]){ 

      case "headline": 
         $formoutput[] = '<div class="formheadline">'.$element[1].'</div>'; 
      break; 
	  
	   case "hinweistext": 
         $formoutput[] = '<div class="formhinweis">'.$element[1].'</div>'; 
      break; 
       
      case "trennelement": 
         $formoutput[] = '<div class="formtrenn"></div>'; 
      break; 
	  
	  
	 case "fieldend": 
         $formoutput[] = '</fieldset>'; 
     $formfield="1";
      break; 
      
     case "divend": 
         $formoutput[] = '</div>'; 
     $formfield="1"; 
      break;  
        
case "fieldstart": 
         $formoutput[] = '<fieldset class="fieldset"><legend>'.$element[1].'</legend><input type="hidden" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.$element[1].'"/>' ;	 		 
     $formfield="1"; 



      break; 
case "divstart": 
         $formoutput[] = '<div class="'.$element[1].'">';
     $formfield="1"; 
      break;

	  
	  
	  
	  
       
        case "password":
         if($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"]){
            $FORM[$formname]["el_".$i] = trim($element[3]);
         }
      
         if($element[2] == 1 && (trim($FORM[$formname]["el_".$i]) == "" || trim($FORM[$formname]["el_".$i]) == trim($element[3])) && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i] = $style;
            $e=1;
         }
         
         $formlabel[$i] = '<label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label>';
         $formoutput[$i] =$formlabel[$i].'<input type="password" class="formpassword" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.htmlentities(stripslashes($FORM[$formname]["el_".$i])).'" />';
         if($element[2] == 1){$formoutput[$i] .= '&nbsp;*';}
         $formoutput[$i] .= '<br />';
      break;
			 
##### Radio-Buttons  von Markus Feustel 07.01.2008 #####			 
 case "radio":
         
       if((trim($FORM[$formname]["el_".$i] )== "1" ) || ($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"] && $element[3] == 1)){ 
            $checked = ' checked="checked"'; 
            $hidden=""; 
         } 
         else { 
            $checked = ""; 
            $hidden = '<input type="hidden" name="FORM['.$formname.'][el_'.$i.']" value="0" />'; 
         } 
         if(trim($FORM[$formname]["el_".$i]) == '' && trim($element[5]) !=''){ 
           $FORM[$formname]["el_".$i] = trim($element[5]); 
         } 
          
         if($element[2] == 1 && trim($FORM[$formname]["el_".$i]) == "" && $FORM[$formname][$formname."send"] == 1){ 
            $warning["el_".$i] = $style; 
            $e=1; 
         } 
          
         $ro=explode(';',trim($element[3])); 
         $val=explode(';',trim($element[4])); 
         $formlabel[$i] = '<label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label>'; 



         $fo = $formlabel[$i].'<table border="0" cellspacing="5" cellpadding="0">'."\n"; 
         for($xi=0;$xi<count($ro);$xi++){ 
            if($val[$xi] == trim($FORM[$formname]["el_".$i] )){$checked = ' checked="checked"';}else{$checked = "";} 
            $fo .='<tr>'."\n"; 
            $fo .= '<td><input type="radio" class="formradio" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.$val[$xi].'" '.$checked.' /></td>'."\n"; 
            $fo .= '<td><label class="formradio" '.$warning["el_".$i].' for="el_'.$i.'" >'.$ro[$xi].'</label></td>'."\n"; 
            $fo .='</tr>'."\n"; 
         } 
         $fo .='</table>'."\n"; 
         $formoutput[$i] = $fo;
         
      break;		 
##### Ende Radio-Buttons #############################       

      case "text": 
         if($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"]){ 
            $FORM[$formname]["el_".$i] = trim($element[3]); 
         } 
       
         if($element[2] == 1 && (trim($FORM[$formname]["el_".$i]) == "" || trim($FORM[$formname]["el_".$i]) == trim($element[3])) && $FORM[$formname][$formname."send"] == 1){ 
            $warning["el_".$i] = $style; 
         } 
          
         // ### Validierung 
         // falls Pflichtelement und Inhalt da und Formular abgeschickt 
         if( ($element[2] == 1) && (trim($FORM[$formname]["el_".$i]) != "") && ($FORM[$formname][$formname."send"] == 1) ) { 
           // checken, ob und welches Validierungsmodell gewaehlt 
                      
            if (trim($element[5]) != "") { 
              // falls Validierung gefordert 
              $valid_ok = TRUE; 
               $inhalt = trim($FORM[$formname]["el_".$i]); 
                
              switch(trim($element[5])) { 
                 case "mail": 

                             if (!ereg("^.+@(.+\.)+([a-zA-Z]{2,6})$",$inhalt)) $valid_ok = FALSE; 
                                     break; 
                            
             case "absendermail": 
                     $absendermail=$inhalt; 
					 $_SESSION["absendermail"]=$inhalt;
                             if (!ereg("^.+@(.+\.)+([a-zA-Z]{2,6})$",$inhalt)) $valid_ok = FALSE; 
                                     break;   
									 
									 
			 						 
             #Telefonnummern mindestens 6 Zahlen
			 case "telefon": 
                    

					 if (preg_match("#^[ 0-9\/-]{6,}+$#",$inhalt)) {break;} else {$valid_ok = FALSE; }
					  break; 
					  
		   #Postleitzahlen 
			 case "plz": 
                      if (preg_match ("/^[0-9]{5}$/",$inhalt))  {break;} else {$valid_ok = FALSE; }
					  break; 
			
			#Prüft ob die eingegebenen Zeichen Buchstaben sind
			case "letters": 
                    
					 if (preg_match("/^[ a-zA-Zäöüß]+$/i",$inhalt)) {break;} else {$valid_ok = FALSE; }
					  break; 		
			
				 
			 #Nur Zahlen 
			  case "digit": 
                              if (!ctype_digit($inhalt)) $valid_ok = FALSE; 
                                     break; 
              #Nur Buchstaben
			  case "alpha": 
                              if (!ctype_alpha($inhalt)) $valid_ok = FALSE; 
                                     break;
			 
			 # URL 
    case "url":
      $inhalt=trim($inhalt); 
      if (preg_match("#^(http|https)+(://www.)+([a-z0-9-_.]{2,}\.[a-z]{2,4})$#i",$inhalt)) {break;} else {$valid_ok = FALSE; }
    break; 

			 
			 
			// Vorbereitet für eine Captchaabfrage  
			 
			  case "captcha": 
                     if ($_SESSION["kcode"]==$inhalt ) 
                     {break;} 
                     else 
                     { 
                     $valid_ok = FALSE; 
                                    break;     } 
                            

                                                                                                                          
           
			  
			  
			  case "echeck": 
                     if ($_SESSION["absendermail"]==$inhalt ) 
                     {break;} 
                     else 
                     { 
                     $valid_ok = FALSE; 
                                    break;     } 
                            

                                                                                                                          
              }
			  
			  
			  
			  
			  
			  
			  // switch                                  
                
               if (!$valid_ok) $warning["el_".$i] = $style; 
            } // falls Validierung gefordert            
              
         } 
         // ### /Validierung      
          
          
          
          
          
          
          
          
          
          
         #$fput=htmlentities(stripslashes($FORM[$formname]["el_".$i]),string 'utf-8'); 
          
          
         $formoutput[] = ' 
            <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label> 
            <input type="text" class="formtext" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.htmlspecialchars(stripslashes($FORM[$formname]["el_".$i])).'" /><br /> 
            '; 
      break; 
       
      case "textarea": 
         if($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"]){ 
            $FORM[$formname]["el_".$i] = $element[3]; 
         } 
       
         if($element[2] == 1 && (trim($FORM[$formname]["el_".$i]) == "" || trim($FORM[$formname]["el_".$i]) == trim($element[3])) && $FORM[$formname][$formname."send"] == 1){ 
            $warning["el_".$i] = $style; 
         } 
       
       
      // ### Validierung 
         // falls Pflichtelement und Inhalt da und Formular abgeschickt 
         if( ($element[2] == 1) && (trim($FORM[$formname]["el_".$i]) != "") && ($FORM[$formname][$formname."send"] == 1) ) { 
           // checken, ob und welches Validierungsmodell gewaehlt 
                      
            if (trim($element[5]) != "") { 
              // falls Validierung gefordert 
              $valid_ok = TRUE; 
               $inhalt = trim($FORM[$formname]["el_".$i]); 
                
              switch(trim($element[5])) { 
                 case "mail": 

                              if (!ereg("^.+@(.+\.)+([a-zA-Z]{2,6})$",$inhalt)) $valid_ok = FALSE; 

                 case "digit": 
                              if (!ctype_digit($inhalt)) $valid_ok = FALSE; 
                                     break; 
                 case "alpha": 
                              if (!ctype_alpha($inhalt)) $valid_ok = FALSE; 
                                     break;                                                                                                          
              } // switch                                  
                
               if (!$valid_ok) $warning["el_".$i] = $style; 
            } // falls Validierung gefordert            
              
         } 
         // ### /Validierung      
       
       
       
       
       
       
       
       
       
       
       
         $formoutput[] = ' 
            <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label> 
            <textarea class="formtextfield" cols="40" rows="10" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'">'.htmlspecialchars(stripslashes($FORM[$formname]["el_".$i])).'</textarea><br /> 
            '; 
      break; 
    
    
      case "select": 

         $SEL = new select(); 
         $SEL->set_name("FORM[".$formname."][el_".$i."]"); 
         $SEL->set_id("el_".$i); 
         $SEL->set_size(1); 
         $SEL->set_style(' class="formselect"'); 
       
         if($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"]){ 
            $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i]); 
         } 

         foreach(explode(";", trim($element[4])) as $v){ 
            $SEL->add_option( $v, $v); 
         } 
       
         if($element[2] == 1 && trim($FORM[$formname]["el_".$i] )== "" && $FORM[$formname][$formname."send"] == 1){ 
            $warning["el_".$i] = $style; 
         } 
       
         $formoutput[] = ' 
            <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label> 
            '.$SEL->out().'<br />'; 
      break; 
          
      case "checkbox": 
       
         if(   (trim($FORM[$formname]["el_".$i] )== "1" ) || ($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"] && $element[3] == 1)){ 
            $cchecked = ' checked="checked"'; 
            $hidden=""; 
         } 
         else { 
            $cchecked = ""; 
            $hidden = '<input type="hidden" name="FORM['.$formname.'][el_'.$i.']" value="0" /><br />'; 
         } 

         if($element[2] == 1 && $cchecked=="" && $FORM[$formname][$formname."send"] ) { 
            $warning["el_".$i] = $style; 
         } 
       
         $formoutput[] = 
               $hidden.' 
               <div class="divcheck"> <input type="checkbox" title="'.$element[1].'" class="formcheck" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="1" '.$cchecked.' /><label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].'</label> 

               </div>
               '; 
      break; 

       
     case "spamschutz": 
       //Session-Variable prüfen:
    if( !session_is_registered("kcode") ) 
      { 
         session_register("kcode"); 
      }
     if($REX['REDAXO']==1) {$formoutput[] = "im Backend wird das Captchabild nicht angezeigt";}
     else {
        $formoutput[] = '<img src="'.$captchasource.'" class="formcaptcha" alt="Security-Code" title="Security-Code" /><br/>'; 
     }
      break; 
       
   } 
} 




$charset="REX_VALUE[9]";
if ($charset=="UTF-8") { $acharset='accept-charset="UTF-8"';}
else {$acharset="";}


#Ausgabe Kopf
$out = ' 
   <div  class="formgen"> 
   <form id="'.$formname.'" action="'. $_SERVER["REQUEST_URI"].'" '.$acharset.' method="post"> 
      <input type="hidden" name="FORM['.$formname.']['.$formname.'send]" value="1" />'; 
# weiterer Code bis zum Formular:
$out .= '<input name="date" type="hidden" value="'.time().'" />';


#Formular
foreach($formoutput as $v){ 

  if ($formfield !="1")
    {
   $out .= '<div class="formblock">'.$v.'</div>'; 
  } 
  else
    {
   $out .= $v; 
    }
  }


#Ausgabe Fuss
$out .= ' 
      <div class="formblock"> 
         <input type="submit" name="FORM['.$formname.']['.$formname.'submit]" value="'.$submitlabel.'" class="formsubmit" /> 
      </div> 
      </form> 
   </div>'; 
    
	
	
if($FORM[$formname][$formname."send"] == 1)
{	
if (!isset($_POST['date'])) {$spamtime="spam";}
elseif (!is_numeric($_POST['date'])) {$spamtime="spam";}
elseif (intval($_POST['date']) > Zeit-10) {$spamtime="spam";}
elseif (intval($_POST['date']) < Zeit-10*3600) {$spamtime="spam";}
else { $spamtime="nospam"; }
}	
	
	if ($absendermail=="") {$absendermail="kontaktformular@sbm-moers.de";}
	
	
	
   if($FORM[$formname][$formname."send"] == 1 && count($warning)==0) 
   { 
    unset($_SESSION["kcode"]); //Captcha-Variable zurücksetzen
    unset($_SESSION["absendermail"]);
   $mail = new PHPMailer();
   $mail->AddAddress("REX_VALUE[1]"); 
   $mail->Sender = $absendermail; 
   $mail->From = $absendermail; 
   $mail->FromName = $absendermail; 
   $mail->Subject = "REX_VALUE[4]"; 
   $mail->CharSet = "REX_VALUE[9]"; 
   $fcounter = 0;
   $xcounter = 0;
    
    foreach($FORM[$formname] as $k=>$v){ 
      if($k != $formname."submit" && $k != $formname."send" && stripslashes($v)!="keine" && stripslashes($v)!="") 
	  {
       if ($AFE[ereg_replace("el_","",$k)][0]=="fieldstart")
	  	{ $mailbodyhtml.='<h1>'.stripslashes($v).'</h1>';
	  }
	  else
	  	{	 
	  		$mailbodyhtml.= '<span class="slabel">'.$fcounter.'. '.$AFE[ereg_replace("el_","",$k)][1].": </span>".stripslashes($v).'<br />';
      		$fcounter++;
	  	}
	  }
   } 
   
     foreach($FORM[$formname] as $k=>$v){ 
      if($k != $formname."submit" && $k != $formname."send" && stripslashes($v)!="keine" && stripslashes($v)!="") 
	  {
	  if ($AFE[ereg_replace("el_","",$k)][0]=="fieldstart")
	  	{ $mailbody.="\n".'***'.stripslashes($v)."\n".'---------------------------------------------------------'."\n";
	  }
	  
	  else
	  {
      $mailbody .= $xcounter.'. '.$AFE[ereg_replace("el_","",$k)][1].":".stripslashes($v)."\n";  
      $xcounter++;
      }
	  }
   } 
    
   // $mail->Body = $mailbody;
   $mail->Body = $doformhtml.$mailbodyhtml.$doformhtmlfooter;
   $mail->AltBody = $mailbody.$nonhtmlfooter;
   $mail->Send(); 
    
    
   ############################# Mail Responder ############################################### 
   $responder="REX_VALUE[10]"; 
   if($FORM[$formname][$formname."send"] == 1 && $responder=="ok" && count($warning)==0) 
   { 

   $mail = new PHPMailer();
   $mail->AddAddress("".$absendermail."");
   $mail->Sender = "REX_VALUE[2]"; 
   $mail->From = "REX_VALUE[2]"; 
   $mail->FromName = $formname."|".$REX['SERVERNAME']; 
   $mail->Subject = "REX_VALUE[4]"; 
   $mail->CharSet = "REX_VALUE[9]"; 
  
 if ("REX_FILE[1]"!="") 
   {
   $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/files/"."REX_FILE[1]"); 
   }   
  $mail->Body = $responsemail; 
   $mail->Send(); 
   
   } 
   ###########################ENDE Mail Responder ########################################### 
    
?>
<div id="form-module-thanks">REX_VALUE[6]</div> 
  
<? $noform="1";} 

if (count($warning)>0) 
{
 echo '<div class="forminfo">'; echo($fError); echo'</div>';
 print $out; 
}
else {    
if ($noform!="1"){   
print $out; }
}
#echo "dddddd".$spamtime.$_POST['date'];

?>
