<?php
/**
 *=============================================
 * REDAXO-Modul: do form!4
 * Bereich: Ausgabe
 * Version: 4.3
 * Module-id: 364
 * Redaxo Version: 4.3 
 *
 * Hinweise:
 *
 * Erforderliche Addons: TinyMCE, PHPMAiler
 *
 * Datum: 7.06.2011
 * Ursprung: Formular-Generator Redaxo 3.2 Demo, do form! 2
 * Typ: Modifikation / Erweiterung
 *=============================================
* 
 * VALUE[1] - Email geht an 
 * VALUE[2] - (Ihre) Absenderadresse fuer die Bestaetigungs-Email 
 * VALUE[3] - Formularfelder 
 * VALUE[4] - Betreff 
 * VALUE[5] - E-Mail-Bestaetigungstext 
 * VALUE[6] - TinyMCE 
 * VALUE[7] - Bezeichnung fuer Senden-Button 
 * VALUE[8] - Absender-Name 
 * VALUE[9] -  frei
 * VALUE[10] - Soll eine Bestaetigungs-Email erstellt werden? 
 * VALUE[11] - BCC an 
 * VALUE[12] - HTML-EMAIL JA /NEIN
 * VALUE[13] - Original anhaengen? JA / NEIN
 * VALUE[14] - Uploadordner
 * VALUE[15] - Upload als Mail versenden
 * VALUE[16] - Bezeichner der Session-Variable
 * VALUE[17] - Bestätigungsbetreff
 * VALUE[18] - SSL-Schalter (JA/NEIN)
 * VALUE[19] - frei
 * VALUE[20] - frei
 * REX_FILE[1] - Dateianhang an Absender (z.B. AGB) 
 * 
 */
 
// =============================================
// Beim Classic-Mode entspricht der Absender der E-Mail dem Empfänger
// Bei false wird der Absender des PHPMailers verwendet
$fromclassic = true; // Absender = Empfänger? 
$ftitel='<strong>klxm do form!</strong> - REX_VALUE[4]'; // Überschrift / Betreff der HTML-E-Mail
$ssldomain = 'domain.tld'; // Domain ohne https://, kein Slash am Ende 
$style = 'class="formerror"'; // Stildefinition Fehler
$formname = "doform"."REX_SLICE_ID"; // Formular ID generiert aus SLICE ID
$formdatum = date("d.m.Y"); // Datum
$formzeit = date("H:i"); // Uhrzeit
$formreq='&nbsp;<strong class="formreq">*</strong>'; // Markierung von Pflichtfeldern
$formbcc="REX_VALUE[11]"; // BCC-Feld
$sendfullmail="REX_VALUE[13]"; //Original in Bestätigungsmail
// Welche Felder sollen nicht in der E-Mail  übertragen werden?
$ignore = array('captcha','sicherheitscode','ilink', 'ilink2', 'divstart', 'divend', 'fieldend', 'info','exlink'); 
// Bezeichnung des Sende-Buttons
$submitlabel = "REX_VALUE[7]";
 
// =============================================
//   KONFIGURATION
// =============================================
// Captcha-Konfiguration / OPTIONAL
// Die folgende Variable muss angepasst werden:
// =============================================
// ID zum Captcha-Artikel der das Captcha-Template nutzt
$captchaID = 000;
$captchasource = htmlspecialchars(rex_getUrl($captchaID));
//==============================================
// Alternative: Externe Einbindung eines Captchas
// $captchasource="/redaxo/captcha/captcha.php";
// =============================================
// siehe: http://www.rexvideo.de/module/formulargenerator.html
 
// =============================================
// Pfad zum Dateianhang bei Bestätigungs-Email
// =============================================
$redaxofile = $REX['HTDOCS_PATH']."files/"."REX_FILE[1]";
/**
 * Soll der komplette Upload-Pfad mit der Mail verschickt werden oder
 * nur der Dateiname?
 * Beispiel: files/upload/Foo.jpg
 * default: false
 * 
 * @param bool  true - kompletter Pfad wird geschickt, false - nur der Dateiname
 */
$uploadpfad_mit_mailschicken = true;
$absendermail=""; 
 
/* --------------------------- SSL-Schalter ------------ */
 
if ('REX_VALUE[18]'=="SSL")
{
// SSL - SCHALTER
if ($REX['REDAXO']!=1) {
 
if($_SERVER['SERVER_PORT']  != 443)  
   { 
        $datei = $_SERVER['REQUEST_URI']; 
        // Domain anpassen
        $ziel = $ssldomain.$datei;      
        header("Location: $ziel"); 
        exit();    
   } 
}
}
 
 
// Fehlermeldungen:
// =============================================
// Sprache 0 -- Hier Deutsch
 
if ($REX['CUR_CLANG'] == 0)
{
#### Achtung! Hinter <<< EOD darf kein Leerzeichen stehen.
$fError= <<<EOD
Bei der Eingabe traten Fehler auf. <br /> Bitte korrigieren Sie Ihre Eingaben.
EOD;
$frel="<br />Sie haben versucht die Seite neu zu laden. Ihre Nachricht wurde bereits versandt";
 
}
// Sprache 1 -- z.B. Englisch
if ($REX['CUR_CLANG'] == 1)
{
#### Achtung! Hinter <<< EOD darf kein Leerzeichen stehen.
$fError= <<<EOD
Please correct your Input
EOD;
$frel="<br />You have tried to reload this page. Your message has already been sent.";
}
 
$charset = $REX['LANG'];
if ($charset == 'en_gb' ) {
  $acharset = '';
  $mcharset='iso-8859-1';
}
else {
  $acharset = '';
  $mcharset='iso-8859-2';
}
if ($charset == 'de_de_utf8' or $charset == 'en_gb_utf8' ) {
  $acharset = 'accept-charset="UTF-8"';
  $mcharset='UTF-8';
} 
 
 
 
// HTML-Vorlage 
// HEADER
$doformhtml='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$mcharset.'" />
<title>do form! message</title>
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1em;
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
h2 { color: #003366;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #999999;
	display: block;
	clear: both;
	font-size: 0.9em;
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
  '.$ftitel.'
</div>
';
// footer
$doformhtmlfooter='<hr size="1" /><br />
<br /></body></html>';
 
$nonhtmlfooter="\n----------------------------------\n
 ";
 
 
 
// =============================================
// Ende der allgemeinen Konfiguration
//=============================================
$cupload=0;
$fcounter = 1;
$xcounter = 1;
 
/**
 * prueft ob die Mindestanzahl an Argumenten mit der Vorgabe uebereinstimmt
 * 
 * Achtung! Die Mindestanzahl an Elementen muss mit Array-Zaehlweise angegeben werden.
 * D.h., die Zahlung beginnt inkl. der Null.
 * 
 * @param int     $mustHave - Mindestanzahl an Elementen 
 * @param array   $elements - Elementa-Array
 * @param string  $formelement - Name des Elementes in dem der Check ausgefuehrt wird
 * @return string
 */
if (!function_exists('doform_checkElements')) { 
function doform_checkElements($mustHave, $elements, $formelement) {
  global $REX;
  // Diese Information ist nur im Backend zu sehen
  if ($REX['REDAXO']) {
    // $formelement darf nicht leer sein
    if ($formelement == '') { return 'Der Formelementename wurde nicht erkannt. Siehe Funktion "doform_checkElements"<br />'; }
    // $mustHave muss mind. 2 sein
    if ((int) $mustHave < 2) { return $formelement.': Die Vorgabezahl darf nicht kleiner als 2 sein!<br />'; }
    // $elements muss ein Array sein
    if (!is_array($elements)) { return $formelement.': Das ubergebene Element ist kein Array.<br />'; }
 
    $anzahlElemente = count ($elements);
    if ($mustHave > count ($elements)) {
      $fehlermeldung = 'Es wurden nicht genuegend Argumente fuer das Formualarfeld "'.$formelement.'" angegeben.<br />';
      $fehlermeldung .= 'Angegeben wurden '.$anzahlElemente.' Argumente, benoetigt werden aber mind. '.$mustHave.' Argumente!<br />'."\n";
      return $fehlermeldung;
    } else {
      return '';
    }
  } else {
    return '';
  }
}
}
 
/**
 * Gibt eine Fehlermeldung vom Upload zurueck
 * 
 * @param $error_code
 * @see http://de.php.net/manual/en/features.file-upload.errors.php
 * @return string   Fehlermeldung
 */
if (!function_exists('file_upload_error_message')) { 
function file_upload_error_message($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE: // Fehler Nr.: 1
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE: // Fehler Nr.: 2
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL: // Fehler Nr.: 3
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE: // Fehler Nr.: 4
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR: // Fehler Nr.: 6 (Introduced in PHP 4.3.10 and PHP 5.0.3.)
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE: // Fehler Nr.: 7 (Introduced in PHP 5.1.0.)
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION: // Fehler Nr.: 8 (Introduced in PHP 5.2.0.)
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
} 
}
 
/**
 * Convert a shorthand byte value from a PHP configuration directive to an integer value
 * @param    string   $value
 * @return   int
 */
if (!function_exists('convertBytes')) { 
function convertBytes( $value ) {
    if ( is_numeric( $value ) ) {
        return $value;
    } else {
        $value = trim ($value);
        $value_length = strlen($value);
        $qty = substr( $value, 0, $value_length - 1 );
        $unit = strtolower( substr( $value, $value_length - 1 ) );
        switch ( $unit ) {
            case 'g':
                $qty *= 1024;
            case 'm':
                $qty *= 1024;
            case 'k':
                $qty *= 1024;
            case 'b':
                $qty = $qty;
 
        }
        return $qty;
    }
}
}
#### Achtung! Hinter <<< End darf kein Leerzeichen stehen.
$rex_form_data = <<<End
REX_HTML_VALUE[3]
End;
 
#### Achtung! Hinter <<< End darf kein Leerzeichen stehen.
$mailbody = <<<End
End;
 
$responsemail = <<<End
REX_HTML_VALUE[5]
End;
 
 
 
if (isset($_POST['eingabe'])) { $eingabe = $_POST['eingabe']; }
$FORM = rex_post('FORM', 'array');
$formoutput = array();
$warning = array();
$warning_set = 0; // wird zu 1, wenn eine Fehler auftritt
$form_elements = array();
$form_elements = explode("\n", $rex_form_data);
$responsemail  = str_replace("%Datum%", $formdatum, $responsemail);
$responsemail  = str_replace("%Zeit%", $formzeit, $responsemail);
//Adresse die als Absenderadresse der Bestätigungs-Email eingegeben wurde
$responsemail  = str_replace("%Absender%", "REX_VALUE[2]", $responsemail);
//Empfänderadresse die im Modul angegeben wurde
$responsemail  = str_replace("%Mail%", "REX_VALUE[1]", $responsemail);
$responsemail  = str_replace("%Betreff%", "REX_VALUE[4]", $responsemail);
$token = md5(uniqid('token'));
$formcaptcha = null;
$dfreload  = null;
$mailbodyhtml = ''; 
$form_enctype = '';
/**
 * Enthaelt die Dateiangaben der uebertragenen Datei und den Namen der Zieldatei
 * Form: array ( targetFile => tempFile )
 * 
 * @var array
 */
$upload_File = array();
 
for ($i=0; $i<count($form_elements); $i++) {
 
  // ueberspringe Leerzeilen
  if (trim($form_elements[$i]) == '') {
  	continue;
  }
 
  $element = explode("|", $form_elements[$i]);
  $AFE[$i] = $element;
  $formfield = 0;
 
  if (!isset ($FORM[$formname]['el_'.$i])) { $FORM[$formname]['el_'.$i] = ''; }
  if (!isset ($FORM[$formname][$formname.'send'])) { $FORM[$formname][$formname.'send'] = ''; }
  if (!isset ($warning["el_".$i])) { $warning["el_".$i] = NULL; }
 
  switch ($element[0]) {
 
 
    case "sessionvar":
          $formoutput[] = '
          <input type="hidden" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.$_SESSION["REX_VALUE[16]"].'" />' ;
          break;
 
    //  Gestaltungselemente
 
    case "headline":
          $formoutput[] = '<div class="formheadline">'.$element[1].'<input type="hidden" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.$element[1].'"/></div>';
          break;
 
    case "info":
          $formoutput[] = '<div class="formhinweis">'.$element[1].'</div>';
          break;
 
	case "HTML":
          $formoutput[] = '<div class="formhtml">'.$element[1].'</div>';
          break;
 
    case "ilink":
          $formoutput[] = '<div class="formlink"><a href="'.rex_getUrl($element[1]).'">'.$element[2].'</a></div>';
          break;
	 case "exlink":
          $formoutput[] = '<div class="formlink"><a href="'.$element[1].'" onclick="window.open(this.href); return false;">'.$element[2].'</a></div>';
          break;	  
 
    case "ilink2": 
          $formoutput[] = '<div class="formlink"><a class="'.$element[3].'" href="'.rex_getUrl($element[1]).$element[2].'">'.$element[4].'</a></div>'; 
          break; 
 
    case "trennelement":
          $formoutput[] = '<div class="formtrenn"><hr/></div>';
          break;
 
	case "fieldend":
          $formoutput[] = '</fieldset>';
          $formfield = "on";
          break;
 
    case "divstart": 
          $str = $element[1]; 
          $first = $str[0]; 
          $id = str_replace("#",'',$str); 
          if ($first == '#') { 
            $formoutput[] = '<div id="'.$id.'">'.$element[2]; 
          } 
          else { 
            $formoutput[] = '<div class="'.$element[1].'">'.$element[2]; 
          } 
          $formfield = "on"; 
          break;
 
    case "divend":
          $formoutput[] = '</div>';
          $formfield = "on";
          break;
    case "fieldstart":
          $formoutput[] = '<fieldset class="fieldset"><legend>'.$element[1].'</legend><input type="hidden" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.$element[1].'"/>';
          $formfield = "on";
          break;
 
  	// Formular-Felder 
 
 case "checkbox":
          $req='';
		  $cchecked="";
          if (isset($element[2]) && $element[2] == 1) { $req = $formreq; }
 
          if ((trim($FORM[$formname]["el_".$i] ) == "X" ) || ($FORM[$formname]["el_".$i] == '' && !$FORM[$formname][$formname."send"] && $element[3] == 1)){
            $cchecked = ' checked="checked"';
            $hidden="";
          }
          else {
            $cchecked = '';
            #$hidden = '<div><input type="hidden" name="FORM['.$formname.'][el_'.$i.']" value="0" /></div>';
			 $hidden="";
          }
 
          if (isset($element[2]) && $element[2] == 1 && $cchecked=="" && $FORM[$formname][$formname."send"] ) {
            $warning["el_".$i] = $style;
			$e = 1;
			$warning_set = 1;
          }
 
          $formoutput[] =
                $hidden.'
               <span class="checkspan"><label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>
                <input type="checkbox" title="'.$element[1].'" class="formcheck" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'"value="X" '.$cchecked.' /></span><br/>';
       break;
 
 
 
    // Radio-Buttons von Markus Feustel 07.01.2008
    case "radio":
          $req='';
          if (isset($element[2]) && $element[2] == 1) {$req = $formreq;}
 
          if ((trim($FORM[$formname]["el_".$i] ) == 1 ) || ($FORM[$formname]["el_".$i] == '' && !$FORM[$formname][$formname."send"] && $element[3] == 1)) {
            $checked = ' checked="checked"';
            $hidden = '';
          }
          else {
            $checked = "";
            $hidden = '<input type="hidden" name="FORM['.$formname.'][el_'.$i.']" value="0" />';
          }
          if (trim ($FORM[$formname]["el_".$i]) == '' && trim ($element[5]) != '') {
            $FORM[$formname]["el_".$i] = trim($element[5]);
          }
          if (isset($element[2]) && $element[2] == 1 && trim($FORM[$formname]["el_".$i]) == "" && $FORM[$formname][$formname."send"] == 1) {
            $warning["el_".$i] = $style;
            $warning_set = 1;
            $e=1;
          }
          $ro  = explode(';',trim($element[3]));
          $val = explode(';',trim($element[4]));
          $formlabel[$i] = '<label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>';
 
        // Inspiriert durch grizou 
          $fo = $formlabel[$i].'<div id="el_'.$i.'" >'."\n";
          for ($xi=0; $xi < count ($ro); $xi++) {
            if ($val[$xi] == trim($FORM[$formname]["el_".$i] )){ $checked = ' checked="checked"'; } else { $checked = ''; }
            $fo .= '<br/><input type="radio" class="formradio" name="FORM['.$formname.'][el_'.$i.']" id="r'.$i.'_Rel_'.$xi.'" value="'.$val[$xi].'" '.$checked.' />'."\n";
            $fo .= '<label class="formradio" '.$warning["el_".$i].'for="r'.$i.'_Rel_'.$xi.'" >'.$ro[$xi].'</label>'."\n";
          }
          $fo .='</div><br />'."\n";
 
          $formoutput[$i] = $fo.'<br/>';
          break;
          //  Ende Radio-Buttons
    case "hidden":
    case "password":
    case "text":
          $req='';
          if (isset($element[2]) && $element[2] == 1) { $req = $formreq; }
 
          // 14.08.2009: GET-VARIABLENABFRAGE von Tito übernommen, 
          // siehe http://forum.redaxo.de/ftopic11635-30.html
 
          if ($FORM[$formname]["el_".$i] == '' && !$FORM[$formname][$formname.'send']) { 
            if (strchr($element[3],'GET_')) { 
              $get = explode('GET_',$element[3]); 
              $element[3] = rex_get($get[1]); 
            } 
            $FORM[$formname]["el_".$i] = trim($element[3]); 
          }
 
          if (isset($element[2]) && $element[2] == 1 && (trim($FORM[$formname]["el_".$i]) == "" || trim($FORM[$formname]["el_".$i]) == trim($element[3])) && $FORM[$formname][$formname."send"] == 1) {
            $warning["el_".$i] = $style;
            $warning_set = 1;
          }
 
          // ### Validierung
          // falls Pflichtelement oder Inhalt da und Formular abgeschickt
          if ( (isset($element[2]) && $element[2] == 1) && (trim($FORM[$formname]["el_".$i]) != "") && ($FORM[$formname][$formname."send"] == 1 ) || (trim($element[5])!="" &&  $FORM[$formname][$formname."send"] == 1 && $element[2] != 1  && trim($FORM[$formname]["el_".$i]) != "") ) {
          // checken, ob und welches Validierungsmodell gewaehlt
 
            if (trim($element[5]) != '') {
              // falls Validierung gefordert
              $valid_ok = TRUE;
              $inhalt = trim($FORM[$formname]["el_".$i]);
 
              switch (trim($element[5])) {
                case "mail":
                      if (!preg_match("#^.+@(.+\.)+([a-zA-Z]{2,6})$#",$inhalt)) $valid_ok = FALSE; 
                      break;
                case "sender":
                case "absendermail":
                      if (!preg_match("#^.+@(.+\.)+([a-zA-Z]{2,6})$#",$inhalt)) {$valid_ok = FALSE;} else {$absendermail = $inhalt;} 
                      break;
                #Telefonnummern mindestens 6 Zahlen
                case "tel":
                case "telefon":
                      if (preg_match("#^[ \(\)\+0-9\/-]{6,}+$#",$inhalt)) {break;} else {$valid_ok = FALSE; }  // Neu: 04.04.2011
                      break;  
                #Postleitzahlen
                case "plz":
                      if (preg_match ("/^[0-9]{5}$/",$inhalt))  {break;} else {$valid_ok = FALSE; }
                      break;
                case "plz4":
                      if (preg_match ("/^[0-9]{4}$/",$inhalt))  {break;} else {$valid_ok = FALSE; }
                      break;
                #Namens-Prüfung bestimmte Zeichen sind nicht erlaubt 05.01.2010
                case "name":
                case "fname":
                case "sname":
                case "letters":
                 if ( preg_match("/^[^;,@%:._#+*'!\"§$\/()=?]+$/i", $inhalt) ) {break;} else {$valid_ok = FALSE; }
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
                      if (preg_match("#^(http|https|ftp)+(://www.)+([a-z0-9-_.]{2,}\.[a-z]{2,4})$#i",$inhalt)) {break;} else {$valid_ok = FALSE; }
                      break;
                # legt das zu per check zu prüfende Feld fest
               	case "checkfield":
          						if (preg_match("/[\w\p{L}]/u",$inhalt)) {$_SESSION["formcheck"]=$inhalt; break;} else {$valid_ok = FALSE; }						
                      break;
 
    			 // Captchaabfrage
                case "check":
        	    case "captcha":
				  if(isset($_SESSION['token'])) {
                  	if($_SESSION['token'] == $_POST['token']) {
                  		$formcaptcha = 'off'; 
                  		$valid_ok = FALSE;
                  		$dfreload =$frel;
                  		break;
                		}
     						  }
 
                  if ($_SESSION["kcode"] == $inhalt) { $valid_ok = TRUE; break; } 
                  if ($_SESSION["formcheck"]== $inhalt ) {
                    $valid_ok = TRUE; break;
                  }	else {
                    $formcaptcha = 'off';
                    $valid_ok = FALSE; break;
                  }
              } // switch (trim($element[5]))
 
              if (!$valid_ok) {
                $warning["el_".$i] = $style;
                $warning_set = 1;
              }
            } // falls Validierung gefordert
          }
 
          // ### /Validierung
          if($element[0]=="hidden")
				{
					$inptype="hidden";
				}
 
 
          if($element[0]=="password")
				{
					$inptype="password";
				}
				else
				{
					$inptype="text";
				}
 
		  if ($formcaptcha == 'off')
    			{ 
 
                if ($inptype=='hidden')
                {
                $formoutput[] = '
                <input type="'.$inptype.'" class="formtext" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="" />';
                }
                else
                {
				$formoutput[] = '
                    <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>
                    <input type="'.$inptype.'" class="formtext" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="" /><br />
                    ';
      			}
      			$formcaptcha = 'on';
    			}	else	{
      			$formoutput[] = '
                    <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>
                    <input type="'.$inptype.'" class="formtext" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'" value="'.htmlspecialchars(stripslashes($FORM[$formname]["el_".$i])).'" /><br />
                    ';
   			  }
          break;
 
    case "textarea":
          $req='';
          $fehlerImFormaufbau = doform_checkElements(2, $element, 'textarea');
          if (isset($element[2]) && $element[2] == 1) {$req = $formreq;}
          if (isset($element[3]) && $FORM[$formname]["el_".$i] == '' && !$FORM[$formname][$formname."send"]){
             $FORM[$formname]["el_".$i] = $element[3];
          }
 
          if (isset($element[2]) && isset($element[3]) && $element[2] == 1 && 
              (trim($FORM[$formname]["el_".$i]) == "" || trim($FORM[$formname]["el_".$i]) == trim($element[3])) && 
              $FORM[$formname][$formname."send"] == 1) {
            $warning["el_".$i] = $style;
            $warning_set = 1;
          }
          $formoutput[] = $fehlerImFormaufbau.'
           <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>
           <textarea class="formtextfield" cols="40" rows="10" title="'.$element[1].'" name="FORM['.$formname.'][el_'.$i.']" id="el_'.$i.'">'.htmlspecialchars(stripslashes($FORM[$formname]["el_".$i])).'</textarea><br />';
          break;
 
    case "select":
          $req='';
          if (isset($element[2]) && $element[2] == 1) { $req = $formreq; }
		      $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."]");
          $SEL->set_id("el_".$i);
          $SEL->set_size(1);
          $SEL->set_style(' class="formselect"');
 
          if ($FORM[$formname]["el_".$i] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i]);
          }
 
          foreach (explode(";", trim($element[4])) as $v) {
            $SEL->add_option( $v, $v);
          }
 
          if (isset($element[2]) && $element[2] == 1 && trim($FORM[$formname]["el_".$i] )== "" && $FORM[$formname][$formname."send"] == 1) {
            $warning["el_".$i] = $style;
            $warning_set = 1;
          }
 
          $formoutput[] = '
              <label '.$warning["el_".$i].' for="el_'.$i.'" >'.$element[1].$req.'</label>
              '.$SEL->out().'<br />';
          break;
 
   case "captchapic":
   case "spamschutz":
           //Session-Variable prüfen:
          if ( !isset($_SESSION["kcode"]) ) { 
            session_start(); $_SESSION["kcode"];
          }
          if ($REX['REDAXO'] == 1) { $formoutput[] = 'im Backend wird das Captchabild nicht angezeigt'; }
          else {
            $formoutput[] = '<img src="'.$captchasource.'" class="formcaptcha" alt="Security-Code" title="Security-Code" />'.$element[1].'<br/><br/>';
          }
          break;
 
 
	  case "date":
	  case "xdate":
          $req=($element[2] == 1)?$formreq:'';
          // TAGE
          $AFE[$i.'_d'] = $element;
          $form_element_ids[md5(strtolower(trim($element[1])).'_d')] = 'el_'.$i.'_d';
          $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."_d]");
          $SEL->set_id("el_".$i.'_d');
          $SEL->set_size(1);
          $SEL->set_style(' class="date_day"');
 
          if ($FORM[$formname]["el_".$i.'_d'] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i.'_d']);
          }
 
          $SEL->add_option( '', '');
          foreach (range(1,31) as $v){
              $v = sprintf('%02d',$v);
              $SEL->add_option( $v, $v);
          }
 
          if ($element[2] == 1 && trim($FORM[$formname]["el_".$i.'_d'] )== "" && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i.'_d'] = $style;
            $warning_set = 1;
          }
          if ($element[2] == 1 && trim($FORM[$formname]["el_".$i.'_m'] )== "" && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i.'_d'] = $style;
            $warning_set = 1;
          }
          if ($element[2] == 1 && trim($FORM[$formname]["el_".$i.'_y'] )== "" && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i.'_d'] = $style;
            $warning_set = 1;
          }
 
          $formoutput[] = '
              <label '.$warning["el_".$i.'_d'].' for="el_'.$i.'_d" >'.$element[1].$req.'</label>
              '.$SEL->out();
 
          // MONATE
          $AFE[$i.'_m'] = $element;
          $form_element_ids[md5(strtolower(trim($element[1])).'_m')] = 'el_'.$i.'_m';
          $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."_m]");
          $SEL->set_id("el_".$i.'_m');
          $SEL->set_size(1);
          $SEL->set_style(' class="date_month"');
 
          if ($FORM[$formname]["el_".$i.'_m'] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i.'_m']);
          }
 
          $SEL->add_option( '', '');
          foreach (range(1,12) as $v){
              $v = sprintf('%02d',$v);
              $SEL->add_option( $v, $v);
          }
 
          $formoutput[] = '
              '.$SEL->out();
 
          // JAHRE
          $AFE[$i.'_y'] = $element;
          $form_element_ids[md5(strtolower(trim($element[1])).'_y')] = 'el_'.$i.'_y';
          $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."_y]");
          $SEL->set_id("el_".$i.'_y');
          $SEL->set_size(1);
          $SEL->set_style(' class="date_year"');
 
          if ($FORM[$formname]["el_".$i.'_y'] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i.'_y']);
          }
 
          $SEL->add_option( '', '');
 
         if ($element[0]=="date")
         {
 
          $year = intval(date('Y'));
          if ($element[4]=="")
          {
          $iival="5";
          }
          else
          {
          $iival=$element[4];
          }
          for($v=$year;$v<($year+$iival);$v++) {
              $SEL->add_option( $v, $v);
          }
        }
        else
        {
        $year = $element[3];
        if ($year=="")
          {
          $year="1900";
          }
 
           $today=intval(date('Y'));
          for($v=$year;$v<($today);$v++) {
              $SEL->add_option( $v, $v);
          }
        }
 
 
          $formoutput[] = '
              '.$SEL->out().'<br />';
          break;
 
 
      case "time":
          $req=($element[2] == 1)?$formreq:'';
          // STUNDEN
          $AFE[$i.'_h'] = $element;
          $form_element_ids[md5(strtolower(trim($element[1])).'_h')] = 'el_'.$i.'_h';
          $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."_h]");
          $SEL->set_id("el_".$i.'_h');
          $SEL->set_size(1);
          $SEL->set_style(' class="time_hours"');
 
          if ($FORM[$formname]["el_".$i.'_h'] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i.'_h']);
          }
 
          $SEL->add_option( '', '');
          foreach (range(0,23) as $v){
              $v = sprintf('%02d',$v);
              $SEL->add_option( $v, $v);
          }
 
          if ($element[2] == 1 && trim($FORM[$formname]["el_".$i.'_h'] )== "" && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i.'_h'] = $style;
            $warning_set = 1;
          }
          if ($element[2] == 1 && trim($FORM[$formname]["el_".$i.'_min'] )== "" && $FORM[$formname][$formname."send"] == 1){
            $warning["el_".$i.'_h'] = $style;
            $warning_set = 1;
          }
 
          $formoutput[] = '
              <label '.$warning["el_".$i.'_h'].' for="el_'.$i.'_h" >'.$element[1].$req.'</label>
              '.$SEL->out();
 
          // MINUTEN
          $AFE[$i.'_min'] = $element;
          $form_element_ids[md5(strtolower(trim($element[1])).'_min')] = 'el_'.$i.'_min';
          $SEL = new select();
          $SEL->set_name("FORM[".$formname."][el_".$i."_min]");
          $SEL->set_id("el_".$i.'_min');
          $SEL->set_size(1);
          $SEL->set_style(' class="time_minutes"');
 
          if ($FORM[$formname]["el_".$i.'_min'] == "" && !$FORM[$formname][$formname."send"]){
              $SEL->set_selected($element[3]); } else { $SEL->set_selected($FORM[$formname]["el_".$i.'_min']);
          }
 
          $SEL->add_option( '', '');
          foreach (range(0,59,15) as $v){
              $v = sprintf('%02d',$v);
              $SEL->add_option( $v, $v);
          }
 
          $formoutput[] = '
              '.$SEL->out().'<br />';
          break;
 
	  //############
	  // ENDE DATUMSABFRAGE
 
	// Upload
    case "upload":
          $fehlerImFormaufbau = doform_checkElements(5, $element, 'Upload');
           $req = '';
          $error_message = '';
          // wird true, wenn keine Datei uebergeben wurde
          $upload_keineDateivorhanden = false;
          if (isset($element[2]) && $element[2] == 1) {
            $req = $formreq;
          }
          /*if ($FORM[$formname]['el_'.$i] == '' && !$FORM[$formname][$formname.'send']) {
              $FORM[$formname]["el_".$i] = trim($element[3]);
          } */
          if (isset($element[6]) && trim ($element[6]) != '')
          {
            $upload_MaxSice = trim ($element[6]);
          } else {
            $upload_MaxSice = 0;
          }
          //DBO('before: '.$upload_MaxSice);
          /*
           * Hier muesste noch eine Pruefung auf Dateiendungen rein.
           * Wie kÃ¶nnen diese Dateiendungen pro Uploadfeld einzeln uebergeben werden?
           * Antwort:
           *  Wie bei einem Select-Feld (-: 
           * 
           */
          if (!empty($_FILES)) {
            //DBO($_FILES);
 
            // Dieses IF-Konstrukt kann man bestimmt noch kuerzer schreiben!? 
            if ($_FILES['FORM']['error'][$formname]['el_'.$i] === UPLOAD_ERR_OK) {
              // upload ok
            } elseif ($req == '' && $_FILES['FORM']['error'][$formname]['el_'.$i] === UPLOAD_ERR_NO_FILE) {
            	// upload ok aber keine Datei vorhanden
            	$upload_keineDateivorhanden = true;
            } else {
              $error_message .= file_upload_error_message($_FILES['FORM']['error'][$formname]['el_'.$i]);
              $warning["el_".$i] = $style;
              $warning_set = 1;
            }
 
            // verarbeite die Dateiuebergabe nur, wenn auch ein Datei vorhanden ist und 
            // kein weiterer Fehler auftrat
            // alexplus: http://forum.redaxo.de/ftopic11635-150.html          
			 if (!$upload_keineDateivorhanden && $error_message == '') { 
              $targetPath     = "REX_VALUE[14]"; 
              $tempFile       = $_FILES['FORM']['tmp_name'][$formname]['el_'.$i]; 
              $preTarget     = time()."_".$_FILES['FORM']['name'][$formname]['el_'.$i]; 
              // Leerzeichen ersetzen durch _
			  $targetFile     = str_replace(" ","_", $preTarget ); 
              $targetPathFile = str_replace('//','/',$targetPath) . $targetFile;
              // Multimail
              $cupload++;
			  $domailfile[$cupload]=$targetFile;
 
              /**
               * Beispielaufbau:
               * array (
               *   0 => 'jpg',
               *   1 => 'jpeg',
               *   2 => 'gif',
               * )
               * @var upload_Extensions
               */
              $upload_Extensions = array();
              $upload_Extensions_errormessage = '';
              $zaehler_element = count(explode(";", trim($element[4])));
              $zaehler_element_z = 0;
              foreach (explode(";", trim($element[4])) as $v) {
                if ($v != '') {
                  $upload_Extensions[] = $v;
                  $upload_Extensions_errormessage .= '.'.$v;
                }
                $zaehler_element_z++;
                if ($zaehler_element_z < $zaehler_element) {
                  $upload_Extensions_errormessage .= ' | ';
                }
              }
 
              $fileParts  = pathinfo($_FILES['FORM']['name'][$formname]['el_'.$i]);
              if (isset ($fileParts['extension']) and $fileParts['extension'] != '' and in_array ($fileParts['extension'], $upload_Extensions))
              {
                $upload_File[$targetPathFile] = $tempFile;
                $FORM[$formname]['el_'.$i] = ($uploadpfad_mit_mailschicken) ? $targetPathFile : $targetFile;
              } else {
                // Warnung ueber nicht erlaubte Datei ausgeben
                $warning["el_".$i] = $style;
                $warning_set = 1;
                $error_message .= '<div class="forminfo">Die Datei kann nicht hochgeladen werden. Evtl. liegt es an einem falschen Dateityp. Erlaubt ist hier nur: '.$upload_Extensions_errormessage.'</div>';
              }
 
              if ($_FILES['FORM']['size'][$formname]['el_'.$i] < convertBytes($upload_MaxSice))
              {
                // alles ok
              } else {
                // Warnung ueber zu grosse Datei ausgeben
                $warning["el_".$i] = $style;
                $warning_set = 1;
                $error_message .= 'Die Datei "'.htmlspecialchars($targetFile).'" ist zu gro&#223;!<br />' ;
                $error_message .= 'Erlaubt sind maximal '. convertBytes($upload_MaxSice)  / 1048576 . ' MB';
              }
 
 
            } // if (!$upload_keineDateivorhanden && $error_message == '')
          } // if (!empty($_FILES))
 
          if (isset ($error_message) and $error_message != '') {
          	$error_message = '<p>'. $error_message .'</p>';
          } else {
            $error_message = '';
          }
 
          $form_tmp = '';
          $form_tmp .= $fehlerImFormaufbau;
          $form_tmp .= $error_message;
          $form_tmp .= "\n".'<label '.$warning["el_".$i].' for="FORM['.$formname.'][el_'.$i.']" >'.$element[1].$req.'</label>'."\n";
          $form_tmp .= '<input type="file" name="FORM['.$formname.'][el_'.$i.']" id="FORM['.$formname.'][el_'.$i.']" /><br/>'."\n";
 
          $formoutput[] = $form_tmp;
          $form_enctype = 'enctype="multipart/form-data"';
 
        break;
  }
}
 
 
// BEGIN :: Uploadverarbeitung
$uploadpfad = "REX_VALUE[14]";
 
// pruefe Pfad auf Vorhandensein und Schreibrechte
// Wenn Pfad nicht vorhanden, ignoriere die weitere Verarbeitung.
if (isset ($uploadpfad) and $uploadpfad != '' and $REX['REDAXO']) {
  // ... dum die dum ... Pfadpruefung erfolgt hier ...
  // beginnt der Uploadpfad nicht mit einem Slash, muss es sich um einen lokalen
  // Ordner handeln der vom Backend aus erweitert werden muss
  if (substr ($uploadpfad, 0, 1) != '/') {
    $uploadpfad_tmp = '../'.$uploadpfad;
  } else {
    $uploadpfad_tmp = $uploadpfad;
  }
  if (rex_is_writable($uploadpfad_tmp) !== true) {
    echo rex_warning('Der Uploadpfad "'.$uploadpfad_tmp.'" ist nicht beschreibbar.<br />
                      Pruefe die Schreibrechte oder lasse die Angaben zum Uploadordner leer, wenn kein Uploadfeld genutzt wird.');
  }
}
 
 
// =================AUSGABE-KOPF============================
$out = '
   <div class="formgen">
   <form id="'.$formname.'" action="'.rex_getUrl(REX_ARTICLE_ID).'" '.$acharset.' method="post" '.$form_enctype.'>
      <div><input type="hidden" name="FORM['.$formname.']['.$formname.'send]" value="1" /><input type="hidden" name="ctype" value="ctype" /></div>
      <input type="hidden" name="token" value="'.$token.'" />';  
 
// =================Formular-generieren=====================
foreach ($formoutput as $v){
 
  if ($formfield != "on") //wenn keine DIVs oder Fieldsets verwendet werden
    {
   $out .= '<div class="formblock">'.$v.'</div>';
  }
  else
    {
   $out .= $v;
    }
  }
 
 
// =================AUSGABE-FUSS============================
$out .= '
 
 
      <div class="formblock">
         <input type="submit" name="FORM['.$formname.']['.$formname.'submit]" value="'.$submitlabel.'" class="formsubmit" />
      </div>
      </form>
   </div>';
 
 
// =================SEND MAIL===============================
if (isset($FORM[$formname][$formname.'send']) && $FORM[$formname][$formname.'send'] == 1 && !$warning_set ) {
 
  // BEGIN :: Uploadverarbeitung
  //$uploadpfad = "REX_VALUE[14]";
 
  // pruefe Pfad auf Vorhandensein und Schreibrechte
  // Wenn Pfad nicht vorhanden, ignoriere die weitere Verarbeitung.
  if (isset ($uploadpfad) and $uploadpfad != '' and count ($upload_File) > 0) {
    // ... dum die dum ... Pfadpruefung erfolgt hier ...
 
    foreach ($upload_File as $targetFile => $tempFile) {
      move_uploaded_file ($tempFile, $targetFile);
    }
 
 
  } // if (isset ($uploadpfad) and $uploadpfad != '')
 
  // END :: Uploadverarbeitung
 
  $_SESSION['token'] = $_POST['token'];
  unset($_SESSION["kcode"]); //Captcha-Variable zurücksetzen
  unset($_SESSION["formcheck"]); // Vergleichsfeld festlegen
  // Selbsdefinierte Sessionvariable zurücksetzen 
  if ("REX_VALUE[16]" !="")
  {
	unset($_SESSION["REX_VALUE[16]"]);
  }
 
 $mail = new rex_mailer(); // Mailer initialisieren
 $mail->AddAddress("REX_VALUE[1]"); // Empfänger
 if ($fromclassic==true)
 {
  $mail->Sender   = "REX_VALUE[1]";    //Absenderadresse als Return-Path
  $mail->From     = "REX_VALUE[1]";  //Absenderadresse 
  $mail->FromName = "REX_VALUE[1]"; // Abdendername entspricht Empfängeradresse 
 }
 
 if ( $absendermail != '') 
  { 
     $mail->AddReplyTo($absendermail); // Antwort an Absender per Reply-To -  Besucher
  }
 
 
  if ($formbcc != '')
  {
    $mail->AddBCC($formbcc);
  }
  $mail->Subject = "REX_VALUE[4]"; // Betreff
  $mail->CharSet = $mcharset; // Zeichensatz
  //Ausgabe der einzelnen E-Mail-Zeilen
  //dbo($FORM[$formname]);
 
 
  foreach ($FORM[$formname] as $k => $v) 
  {
    $matches = array();
    if(preg_match('~el_[0-9]+_(d|m|y|h|n)~',$k,$matches)) {
      switch($matches[1]) {
        case 'd': // TAG
         $mailbodyhtml.= '<span class="slabel">'.$fcounter.'. '.$AFE[preg_replace("#el_#","",$k)][1].": </span>".stripslashes($v);
         $mailbody .= $xcounter.'. '.$AFE[preg_replace("#el_#","",$k)][1].":".stripslashes($v);  
         $fcounter++;$xcounter++;
        break;
        case 'm': // MONAT
        $mailbodyhtml.= '.'.stripslashes($v);
         $mailbody .= '.'.stripslashes($v);  
        break;
        case 'y': // JAHR
        $mailbodyhtml.= '.'.stripslashes($v).'<br />';
         $mailbody .= '.'.stripslashes($v)."\n";  
        break;
        case 'h': // STUNDEN
        $mailbodyhtml.= '<span class="slabel">'.$fcounter.'. '.$AFE[preg_replace('#_.*#','',preg_replace("#el_#","",$k))][1].": </span>".stripslashes($v);
        $mailbody .= $xcounter.'. '.$AFE[preg_replace("#el_#","",$k)][1].":".stripslashes($v);  
        break;
        case 'n': // MINUTEN
        $mailbodyhtml.= ':'.stripslashes($v).'<br />';
         $mailbody .= ':'.stripslashes($v)."\n";  
         $fcounter++;$xcounter++;
        break;
      }
    }
    else {
      // HTML-AUSGABE und Plaintext erstellen
 
      $key = preg_replace('#el_#','',$k);
       if ($k != $formname.'submit' && $k != $formname.'send' && 
          (!isset ($AFE[$key][5]) || $AFE[$key][5] != 'captcha') && 
          stripslashes($v) != '' && isset ($AFE[$key][1]) && 
          !in_array($AFE[$key][0], $ignore))
      {
        $v = strip_tags($v);
 
 
        switch ($AFE[$key][0])
        {
          case "fieldstart":
              $mailbodyhtml.='<h1>'.stripslashes($v).'</h1>';
              $mailbody.="\n".'***'.stripslashes($v)."\n".'---------------------------------------------------------'."\n";
              break;
          case "headline":
              $mailbodyhtml.='<h2>'.stripslashes($v).'</h2>';
              $mailbody.="\n".'---'.stripslashes($v)."\n".'---------------------------------------------------------'."\n";
              break;
          default:
             $mailbodyhtml.= '<span class="slabel">'.$fcounter.'. '.$AFE[$key][1].": </span>".stripslashes($v).'<br />';
             $mailbody .= $xcounter.'. '.$AFE[$key][1].": ".stripslashes($v)."\n";  
             $fcounter++;
             $xcounter++;
        }
      }
    }
  }
 
 
  // HTML-EMAIL JA /NEIN
  if ("REX_VALUE[12]" == 'ja')
  {
    $mail->IsHTML(true);
	$mail->Body = $doformhtml.nl2br($mailbodyhtml).$doformhtmlfooter;
    $mail->AltBody = $mailbody.$nonhtmlfooter;
  }
  else 
  {
    $mail->Body = $mailbody.$nonhtmlfooter;
  }
 
 // Dateianhänge versenden. 01.04.2011
 if (is_array($domailfile)and "REX_VALUE[15]"=="Ja" and $cupload>"0")
  {
  foreach ($domailfile as $dfile)
  {
  $mail->AddAttachment("REX_VALUE[14]".$dfile); 
 
  }
  }
 
 
 
if(!function_exists('doppelversand')){ 
function doppelversand(){ 
} 
  $mail->Send(); // Versenden an Empfänger
}
 
 
 
 
// =================MAIL-RESPONDER============================
  $responder = "REX_VALUE[10]";
  if (isset($FORM[$formname][$formname.'send']) && $FORM[$formname][$formname.'send'] == 1 && 
      $responder == 'ok' && !$warning_set && isset($absendermail)) 
{
 
    $mail = new rex_mailer();
    $mail->AddAddress($absendermail);
    $mail->Sender = "REX_VALUE[2]";
    $mail->From = "REX_VALUE[2]";
    $mail->FromName = "REX_VALUE[8]";
    $mail->Subject = "REX_VALUE[17]";
    $mail->CharSet = $mcharset;
 
    #### Datei (z.B. AGB) versenden ####
 
    if ("REX_FILE[1]" != '') {
      $mail->AddAttachment($redaxofile);
    }
 
    if ($sendfullmail != 'ja') 
    {
    	$mail->Body = $responsemail.$nonhtmlfooter;
    }	
    else 
    {
 		if ("REX_VALUE[12]" == 'ja')
       {
       $mail->IsHTML(true);
	   $mail->Body = $doformhtml.nl2br($responsemail).'<hr/>'.nl2br($mailbodyhtml).$doformhtmlfooter;
       $mail->AltBody = $mailbody.$nonhtmlfooter;
       }
        else 
        {
        $mail->Body = $responsemail."\n-----------------------------------------------\n".$mailbody.$nonhtmlfooter;
        }
 
 
  	}
/*
Doppelversand verhindern
Doppelversand verhindern, siehe: http://forum.redaxo.de/ftopic11974.html  
*/
    if(!function_exists('doppelversand2'))
    { 
    function doppelversand2() {} 
    $mail->Send(); // Versenden an Absender
    }
}
// =================MAIL-RESPONDER-ENDE=========================
?>
<div id="form-module-thanks">REX_HTML_VALUE[6]</div>
 
<?php
  $noform = 1;
}
else {
  $noform = 0;
}
 
 
if ($warning_set) {
  echo '<div class="forminfo">'; echo($fError.$dfreload ); echo '</div>';
  print $out;
} else {
  if ($noform != 1) {
    print $out;
  }
}
 
 
 
 
 
 
?>