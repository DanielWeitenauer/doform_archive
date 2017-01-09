<?php
/**
 *=============================================
 * REDAXO-Modul: do form!  
 * Bereich: Eingabe
 * Version: 3.22
 * Eingabe: 4.1
 * Redaxo Version: 4.x
 * Module-id: 364
 * Hinweise:
 * 
 * Erforderliche Addons: TinyMCE, PHPMAiler
 *
 * Bearbeitung: KLXM Crossmedia, Thomas Skerbis 
 * www.klxm.de
 * Datum: 02.07.2010
 * Datum Eingabe: 11.05.2010
 * Ursprung: Formular-Generator Redaxo 3.2 Demo, do form! 2
 * Typ: Modifikation / Erweiterung  
 * Dank an: Koala, Markus "Zonk" Lorch, Markus Feustel, Harry Brader, 
 * brandes-webdesign, Simon Teufel, snaft (Marc), grizou (Christian), 
 * Markus Staab, Jan Kasper Münnich, Tito, Elricco (Tim), iLis, CHO
 * AMU,MS-EDDIE,MikeP 
 *=============================================
 */
// EINGABE EINSTELLUNGEN
// zur Vereinfachung der Eingabemaske
// Erweiterte Funktionen freischalten 

$uploadon=true;  // UPLOADS AKTIVIEREN true oder false
$sessionson=true;  // SESSIONS AKTIVIEREN true oder false
$bccon=true;  // BCC AKTIVIEREN true oder false

// Version
$doformversion="3.22";
 
// Definition des Standard-Formulars 
$defaultdata="
text|Name|1|||name
text|Vorname|1|||name
text|Firma |
text|Straße|
text|PLZ|1|||plz
text|Ort|1|||
text|Telefon||||tel
text|Telefax||||tel
text|E-Mail|1|||sender
textarea|Ihre Nachricht: |1|
";



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
      $value_length = strlen( $value );
      $qty = substr( $value, 0, $value_length - 1 );
      $unit = strtolower( substr( $value, $value_length - 1 ) );
      switch ( $unit ) {
          case 'g':
              $qty *= 1024;
          case 'm':
              $qty *= 1024;
          case 'k':
              $qty *= 1024;
      }
      return $qty;
    }
}
}







?>

 



<style type="text/css">
<!--
.formgenheadline {
	color: #FFF;
	background-color: #474955;
	display: block;
	padding-left: 10px;
	font-family: Tahoma, Geneva, sans-serif;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	font-weight: bold;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 3px;
	border-left-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	font-style: normal;
}
.doform {
	background-color: #F0F0F0;
	padding-left: 1.2em;
	padding-bottom: 1.2em;
}
.doleft {
	float: left;
	width: 240px;
	background-color: #FFF;
	margin-right: 1.2em;
	margin-top: 1.2em;
	padding: 0.5em;
	border: 1px solid #999;
}
.doform  .inp100 {
	background-color: #CCC;
	border: 1px solid #CCC;
}

.formbg {
	background-color:#E0E2E8;
}

.formgenerror {
  color: #FFFFFF;
  background-color: #990000;
  border: 6px dashed #FFCC00;
  margin: 5px;
  padding: 5px;
}
.formgen_manual {
  color: #333333;
  font-size: 1.2em;
  background-color: #eeeeee;
}
.formgenconfig {
	background-color: #F8F8F8;
	font-family: "Courier New", Courier, monospace;
	color: #063;
	font-size: 1.2em;
	width: 95%;
	margin-right: 2em;
	height: 250px;
	border: 1px solid #999;
}
.formgen_sample {
	background-color: #FFF;
	font-family: "Courier New", Courier, monospace;
	color: #333333;
	font-size: 1.2em;
	width: 95%;
	border: 1px solid #999;
}
.formgenalias { color: #999999;
}
#formgenblock {
  width: 540px;
  padding: 10px;
}
.infotext {color: #999999; font-style: italic; }
.formgentitle {
	color: #6E97C1;
	background-color: #F1F1F1;
	display: block;
	padding-left: 10px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	padding-top: 2px;
	padding-right: 2px;
	padding-bottom: 2px;
	font-weight: bolder;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 3px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-top-color: #CCCCCC;
	border-right-color: #333333;
	border-bottom-color: #999;
	border-left-color: #666666;
	font-style: italic;
	font-size: 20px;
	margin-bottom: 6px;
}
.infotext2 {
	color: #37D749;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}

.myDivs .formgenheadline {
	background-color: #FFBEA2;
	color: #333;
}
.formnavi {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	background-color: #eeeeee;
	padding-top: 4px;
	padding-right: 10px;
	padding-bottom: 15px;
	padding-left: 10px;
}
.formnavi a {
	border-bottom-width: 3px;
	border-bottom-style: solid;
	border-bottom-color: #333;
	padding: 0.3em;
	color: #333;
	font-weight: bold;
	text-decoration: none;
	margin-right: 0.5em;
	font-family: Tahoma, Geneva, sans-serif;
	margin-left: 0em;
	font-size: 1.2em;
}
.doleftdoc {
	float: left;
	width: 120px;
	background-color: #FFF;
	margin-right: 1.2em;
	margin-top: 1.2em;
	padding: 0.5em;
	border: 1px solid #999;
}
.doleftdoc2 {
	float: left;
	width: 420px;
	background-color: #FFF;
	margin-right: 1.2em;
	margin-top: 1.2em;
	padding: 0.5em;
	border: 1px solid #999;
}
.formnavi a:hover {
	border-bottom-width: 3px;
	border-bottom-style: solid;
	border-bottom-color: #CCC;
	padding: 0.3em;
	color: #333;
	font-weight: bold;
	text-decoration: none;
	margin-right: 0.5em;
	font-family: Tahoma, Geneva, sans-serif;
	margin-left: 0em;
	font-size: 1.2em;
}


-->
</style>

<script language="JavaScript" type="text/javascript"> 
<!-- 
function doIt(theValue) 
{ 
    var divs=document.getElementsByTagName("DIV"); 
    for (var i=0;i<divs.length;i++) 
    { 
        if (divs[i].className=="myDivs") 
        { 
        divs[i].style.display=(( theValue=="every" || divs[i].id==theValue)?"block":"none"); 
        }; 
    } 
} 
//--> 
</script>


<div class="formnavi"><a href="http://www.redaxo.de/165-0-moduldetails.html?module_id=364" target="_blank">Aktuelle Version hier</a><a href="http://wiki.redaxo.de/index.php?n=R4.DoForm" target="_blank">WIKI</a><a href="#anleitung" id="anzeige" onclick="javascript:document.getElementById('anleitung').style.display = 'block'" >Kurzanleitung-einblenden </a>&nbsp;do form!  - Version: <?php echo $doformversion; ?>&nbsp;|   <?php echo $REX['LANG']; ?></div>
<br/><?php $phpmcheck= OOAddon::isActivated('phpmailer'); 
if ($phpmcheck == 1)
{}
else { echo' <div class="formgenerror"> PHPMailer wurde nicht gefunden oder ist nicht aktiviert. <br/> Bitte installieren Sie das ADDON! </div>'; }
?>
<div class="formgenheadline"> Formularfelder</div>
<div class="doform" clas="doform">typ|label|pflicht|default|value/s|validierung <br/>
  <textarea name="VALUE[3]" class="formgenconfig"><?php if ("REX_VALUE[3]" == '') {echo $defaultdata;} else {echo "REX_VALUE[3]";}  ?>
  </textarea>
</div>
<br>
<br />



<div class="formgenheadline">Versandeinstellungen</div>
<div class="doform">
  <div class="doleft"><strong>Betreff:</strong><br />
      <input type="text" name="VALUE[4]" value="REX_VALUE[4]" class="inp100" />
     <br />
    <strong>Bezeichnung f&uuml;r Senden-Button:</strong><br />
      <input type="text" name="VALUE[7]" value="REX_VALUE[7]" class="inp100" />
      <br>
      <br>
    HTML-MAIL<span class="infotext"> 
<select   name="VALUE[12]">
  <option value='ja' <? if ("REX_VALUE[12]" == 'ja') echo 'selected'; ?>>ja</option>
  <option value='nein' <? if ("REX_VALUE[12]" == 'nein') echo 'selected'; ?>>nein</option >
</select>
<br>
(nicht in Best&#228;tigungsmail)</span><span class="infotext"></span><br />
  </div>
  <div class="doleft"><strong>Email geht an:</strong><br />
    <input type="text" name="VALUE[1]" value="REX_VALUE[1]" class="inp100" />
    <span class="formgenalias">(%Mail%)</span><br />
    <?php if ($bccon==true) { ?><strong>BCC an:</strong><br />
    <input type="text" name="VALUE[11]" value="REX_VALUE[11]" class="inp100" />
    <br /><?php } ?>
    <br>
    <strong>Soll eine Best&auml;tigungs-Email erstellt werden? </strong>
    <select name="VALUE[10]" id="mySelect" onChange="doIt(this.value)">
      <option value='Nein' <? if ("REX_VALUE[10]" == 'nein') echo 'selected'; ?>>Nein</option>
      <option value='ok' <? if ("REX_VALUE[10]" == 'ok') echo 'selected'; ?>>Ja</option>
    </select>
    <br />
  <em>(Funktioniert nur wenn Feld : |absendermail definiert ist)</em> </div><div style="clear:both"></div>
</div><?php if ($sessionson==true) { ?>
<div class="formgenheadline">Individuelle Sessionvariable (expert)</div>
  <div class="doform">
    <div class="doleft"><strong>Bezeichner für Sessionvariable:</strong><br/>
      <input type="text" name="VALUE[16]" value="REX_VALUE[16]" class="inp100" />
      <br />
    <span class="infotext">z.B.: Warenkorb,  nur für Session-Variablen erlaubte Zeichen, erntspricht: $_SESSION[&quot;Warenkorb&quot;]</span></div>
    <div class="doleft">
      <p><em><strong>Info</strong> Die Variable wird nach dem Versenden zurückgesetzt</em></p>
      <p>Einsatz per <strong>sessionvar|Daten</strong></p>
    </div>
    <div style="clear:both">Es handelt sich hierbei um ein hidden field. Eine Ausgabe muss selbst erstellt werden.</div>
  </div>
   <?php } ?>
<?php if ($uploadon==true) { ?>
<div class="formgenheadline">Uploads</div>
  <div class="doform">
    <div class="doleft"></span> <strong>Uploadordner:</strong> (z.B.: files/upload/)<br />
      <input type="text" name="VALUE[14]" value="REX_VALUE[14]" class="inp100" />
      <br/>
    
      <?php
echo 'Maximale Dateiuploadgr&#246;&#223;e: ' . convertBytes( ini_get( 'upload_max_filesize' ) ) / 1048576 . 'MB';
?>
   </div>
    <div class="doleft"><em>Einen </em>Upload als Anhang versenden?
<select name="VALUE[15]">
      <option value='Nein' <? if ("REX_VALUE[15]" == 'nein') echo 'selected'; ?>>Nein</option>
      <option value='Ja' <? if ("REX_VALUE[15]" == 'Ja') echo 'selected'; ?>>Ja</option>
      </select>
    </div>
    <div style="clear:both"></div>
  </div>
  
 <?php } ?>
  <br>
<div id="ok" <? if ("REX_VALUE[10]" == 'ok'){ echo 'style="display:block;"'; } else echo 'style="display:none;"'; ?> class="myDivs">
  <div class="formgenheadline">Best&#228;tigungs-Email an den Absender</div>
  <div class="doform">
    <div class="doleft"><strong>Absenderadresse </strong>f&uuml;r die Best&auml;tigungs-Email:<br />
      <input type="text" name="VALUE[2]" value="REX_VALUE[2]" class="inp100" />
      <span class="formgenalias">(%Absender%)</span><br/>
<strong>Absender-Name:</strong><br />
      <input type="text" name="VALUE[8]" value="REX_VALUE[8]" class="inp100" />
    </div>
    <div class="doleft"><strong>Original-Mail anh&auml;ngen?<br />
<select name="VALUE[13]">
          <option value='nein' <? if ("REX_VALUE[13]" == 'nein') echo 'selected'; ?>>nein</option >
          <option value='ja' <? if ("REX_VALUE[13]" == 'ja') echo 'selected'; ?>>ja</option>
      </select>
        <br/>
        <br/>
Datei anh&#228;ngen: </strong>REX_MEDIA_BUTTON[1] </div>
    <div style="clear:both"></div>
  </div>
  <div class="formgenheadline">E-Mail-Best&#228;tigungstext</div>
  <div class="doform"><textarea name="VALUE[5]" class="formgenconfig" style="width:100%;height:80px;">REX_VALUE[5]</textarea>
    <span class="formgen_sample1"><strong>Platzhalter für Bestätigungstext:</strong> %Betreff%, %Datum% , %Zeit%, %Absender%, %Mail% </span><br/>
  </div>
</div>
  <br/>
<div class="formgenheadline"><strong>Danksagung</strong> (wird auf der Website  angezeigt)</div>
  
    <?php 
// Ist TinyMCE 3 aktiviert?
if (class_exists('rexTinyMCEEditor'))
{
// Diese 3 Zeilen dürfen keine führenden Leerzeichen besitzen! 
$wysiwigvalue =<<<EOD
REX_VALUE[6]
EOD;

  // Neue Instanz der Klasse
  $tiny = new rexTinyMCEEditor();

  // Buttons setzen (hier alle Buttons der Standardkonfiguration)
  $tiny->buttons1 = 'bold,italic,underline,strikethrough,sub,sup,|,forecolor,backcolor,styleselect,formatselect,|,charmap,cleanup,removeformat,|,preview,code,fullscreen';
  $tiny->buttons2 = 'cut,copy,paste,pastetext,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,redaxoMedia,redaxoEmail,anchor,|,advhr,image,emotions,media';

  // zusätzliche Buttons für Undo/Redo, Tabellen und Template
  $tiny->buttons3 = 'undo,redo,|,tablecontrols,visualaid,|,template,help';
  $tiny->buttons4 = '';

  // Breite und Höhe des Editors
  $tiny->width = 555;
  $tiny->height = 350;

  // Valides XHTML generieren true/false
  $tiny->validxhtml = true;

  // Hier können eigene Werte über die Standardwerte überlagert werden
  //
  // Achtung:
  // bei einigen Parametern (z.B. Plugins) muss der Wert aus der
  // Standardkonfiguration e r w e i t e r t werden
  // Hier als Beispiel erweitert um die Plugins syntaxhl, table, template
$mytinyconfig =<<<EOD
	plugins : 'advhr,advimage,advlink,contextmenu,emotions,fullscreen,inlinepopups,media,paste,preview,redaxo,safari,visualchars,table,template',
	skin : 'o2k7',
	skin_variant : 'silver'
EOD;
//syntaxhl
  // eigene Konfiguration übernehmen
  $tiny->configuration = $mytinyconfig;

  // WYSIWYG-Content zuordnen
  $tiny->content = $wysiwigvalue;

  // Id des REX_VALUES zuordnen
  $tiny->id = 6;

  // WYSIWYG-Editor anzeigen
  $tiny->show();
}

else
{
  $tinycheck= OOAddon::isActivated('tinymce');
  if ($tinycheck == 1) {
  // Diese 3 Zeilen dürfen keine führenden Leerzeichen besitzen! 
$value1 =<<<TEXT
REX_VALUE[6]
TEXT;
  $editor = new rexTiny2Editor(); 
  $editor->id=6; 
  $editor->content=$value1; 
  $editor->show(); }
  else {
    echo' <div class="formgenerror"> TINYMCE wurde nicht gefunden. <br/> Bitte installieren Sie das ADDON! </div>';
  }
}
?>
<br/>
  <div align="right">Bearbeitung: <a href="http://www.klxm.de" target="_blank">Thomas Skerbis - KLXM Crossmedia GmbH</a></div>


<div id="anleitung" style="<?php echo (!isset ($anleitung) || !$anleitung) ? 'display: none' : 'display: block'; ?>"> 
  <div class="formgenheadline">Beispiel-Formular:</div>
  <div class="doform">
    <textarea name="demo" cols="80" rows="11" class="formgenconfig" style="width:95%;height:200px;">
fieldstart|Kontaktdaten
text|Name|1|||checkfield    
text|Name|1|||name
text|Firma
text|Straße
text|PLZ|1|||plz
text|Ort|1
text|Telefon||||tel
text|Telefax||||tel
fieldend|
fieldstart|Weitere Angaben
divstart|cssklasse
radio|Geschlecht|0|Mann;Frau|m;w|
password|Ihr Passwort|1|||alpha
text|E-Mail|1|||absendermail
divend|
select|Auswahl|1||Birne;Apfel;Kirsche
checkbox|AGB gelesen?
fieldend|
captchapic|Geben Sie bitte diesen Code oder nochmal Ihren Namen ein
text|Sicherheitscode|1|||captcha
textarea|Ihre Nachricht:|1|
upload|Upload JPG|0||jpg;jpeg;gif||0.5m
</textarea>
    <br/>
    <br/>
  </div>
  <div class="formgenheadline">Kurzbeschreibung:</div>
  do form! 3 basiert auf den in Redaxo 3.2 mitgelieferten Formular-Generator und do form! 2
  <br />
   Beim ersten Aufruf erstellt das Modul eine Konfiguration für ein Standard-Kontaktformular. <br/>
     Im Beispiel-Formular sehen Sie weitere Möglichkeiten zur Konfiguration. <br/>
     Eine genaue Dokumentation finden Sie im Wiki. <br>
     <br>
<br/>
     
     <br />
  <br />
   <div class="doform">
     <div class="doleftdoc"><strong>Typen</strong></div>
     <div class="doleftdoc2">
       <p>text<br />
         <br/>
         GET-Veriable kann in einem Textfeld ausgelesen werden<br/>
         Verwendug: text|Titel:|1|GET_Variablenname|<br/>
  <br/>
         textarea, select, checkbox <br/>
         radio<br/>
         password <br/>
         captchapic (Setzt das Captchabild mit Info ein)<br />
  <br/>
         date: Aktuell + 5 Jahre <br/>
         xdate: Jahr ab 1900 bis Jahr aktuell<br/>
         time: Auswahl einer Uhrzeit <br/>
  <strong><br/>
    upload</strong> / korrekte Funktion nur wenn in EingabeMaske aktiviert<br/>
       Verwendung: upload|File-Upload|0||zip||30.0m </p>
       <hr size="1">
       <p><strong>Gestaltungselemente:<br />
         </strong><em>Einige Gestaktungselemente werden nicht in der E-Mail &uuml;bertragen* </em><br />
         <br />
         info (Zwischentexte , HTML erlaubt, werden nicht übertragen)<br/>
         headline (Zwischenüberschriften , werden  übertragen) </p>
       <p>fieldstart|label<br />
         fieldend*<br />
         divstart|css-klasse* / divstart|#css-id<br />
         divend*<br />
         trennelement*</p>
       <p><strong>Sonstiges</strong><br/>
         ilink|ID|Name des Links<br />
         ilink2|id|parameter(&amp; = &amp;amp;)|Klasse|Bezeichnung <br />
  <br />
         sessionvar|Warenkorb<br>
       / korrekte Funktion nur wenn in EingabeMaske aktiviert       </p>
     </div>
     <div style="clear:both"></div>
  </div>
  <br>
   <br>
<br>
  <div class="doform">
    <div class="doleftdoc"><strong>Label</strong></div>
    <div class="doleftdoc2">
      <p>Feldbezeichnung / Titel</p>
</div>
    <div style="clear:both"></div>
  </div>
  <br>
  <br>
  <br>
  <div class="doform">
    <div class="doleftdoc"><strong>Pflicht</strong></div>
    <div class="doleftdoc2">
      <p>1 sonst 0 oder leer <br/>
(<em>Pflichtfelder werden automatisch mit einem * versehen.</em>)</p>
    </div>
    <div style="clear:both"></div>
  </div>
  <br>
  <br>
  <div class="doform">
    <div class="doleftdoc"><strong>Default</strong></div>
    <div class="doleftdoc2">
      <p>Wert der bereits erscheinen wird. (Standardeingabe)</p>
    </div>
    <div style="clear:both"></div>
  </div>
  <br>
  <br>
  <br>
  <div class="doform">
    <div class="doleftdoc"><strong>Value/s</strong></div>
    <div class="doleftdoc2">
      <p>Werte für  Radio und select, getrennt per ; </p>
    </div>
    <div style="clear:both"></div>
  </div>
  <br>
  <br>
  <br>
  <div class="doform">
    <div class="doleftdoc"><strong>Validierung</strong></div>
    <div class="doleftdoc2">
      <ul>
        <li>alpha (nur engl.Buchstaben) </li>
        <li>url (URL)</li>
        <li>digit (nur Zahlen)</li>
        <li>plz (5 Zahlen)</li>
        <li>plz4 (4 Zahlen)</li>
        <li>telefon (mindestens 6 Zahlen)</li>
        <li>name prüft Namen und z.B. übliche Firmenbezeichnungen</li>
        <li>mail (pr&uuml;ft eingegebene Email-Adressen) </li>
        <li>absendermail (diese Adresse wird als Absendermail eingesetzt und gepr&uuml;ft)</li>
        <li>check - Prüfen der Spamschutzeingabe (captchapic oder checkfield) <br/>
          entspricht sonst der Validierung: name</li>
        <li>checkfield (legt ein Vergleichsfeld fest das als Spamschutzcode gilt)</li>
      </ul>
      <p>&nbsp;</p>
    </div>
    <div style="clear:both"></div>
  </div>
  <br>
<br>
<p>typ|label|pflicht|default|value|validierung </p>
    <p>&nbsp;</p>
</div>
