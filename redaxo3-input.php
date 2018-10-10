<?php
//---------------------------------------------
// Formulargenerator 
// MODUL-EINGABE
// basiert auf:  REDAXO 3.2 Formulargenerator
// mit Captcha-Unterstützung
//  Stand: 16.04.2008
//---------------------------------------------
?>


<style type="text/css">
<!--
#formgenblock {
	background-color: #ECF0E6;
	width: 540px;
	border: 1px solid #333333;
	padding: 10px;
}
.formgenheadline {
	color: #FFF;
	background-color: #333;
	display: block;
	padding-left: 10px;
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #9C0;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}

.formgenalias {
	color: #999999;
	
}

.formgenerror {
	color: #FFFFFF;
	background-color: #990000;
	border: 1px dashed #000000;
	margin: 5px;
	padding: 5px;
}
.formgen_manual {
	background-color: #DDECEE;
	font-family: "Courier New", Courier, monospace;
	color: #333333;
	font-size: 1.1em;
}
.formgenconfig {
	background-color: #FFFFDD;
	font-family: "Courier New", Courier, monospace;
	color: #333333;
	font-size: 1.1em;
}
.formgen_sample {
	background-color: #DDECEE;
	font-family: "Courier New", Courier, monospace;
	color: #333333;
	font-size: 1.1em;
}
.sbm {
	color: #F90;
}
-->
</style>

<div id="formgenblock">
  <h2 class="formgenheadline"><span class="sbm">SBM</span> - Formular-Generator 3.1</h2>
  <p>Zur Erstellung von E-Mail-Formularen mit verschiedenen Validierungsmöglichkeiten.<br>
    Der Versand erfolgt formatiert mittels HTML und TEXT-Emails<br>
  <br>
  <a href="#anleitung" id="anzeige" onClick="javascript:document.getElementById('anleitung').style.display = 'block'" ><strong>Kurzanleitung - einblenden </strong></a> &nbsp; | <a href="http://wiki.redaxo.de/index.php?n=R4.DoForm" target="_blank">Ausführliche Hilfe zu diesem Modul</a></p>
  <p><br>
    
  </p>
  <p class="formgenheadline">Formularkonfiguration</p>
  <table width="100%" border="0" cellspacing="0" cellpadding="6">
    <tr>
      <td valign="top"><strong>Betreff:</strong><br />
        <input type="text" name="VALUE[4]" value="REX_VALUE[4]" class="inp100" />
        <strong><br>
        <br>
        Sprachkodierung:</strong> (Standard: Unicode) <br>
<select name="VALUE[9]">
  <option value='UTF-8' <? if ("REX_VALUE[9]" == 'UTF-8') echo 'selected'; ?>>Unicode / alle Sprachen</option>
  <option value='iso-8859-1' <? if ("REX_VALUE[9]" == 'iso-8859-1') echo 'selected'; ?>>Westeurop&auml;isch</option	>
  <option value='iso-8859-2' <? if ("REX_VALUE[9]" == 'iso-8859-2') echo 'selected'; ?>>Mitteleurop&auml;isch</option>
</select>
<br /></td>
      <td valign="top"><strong>Email geht an:</strong><br />
        <input type="text" name="VALUE[1]" value="REX_VALUE[1]" class="inp100" />
        <span class="formgenalias">(%Mail%)</span><br />
        <br />
        <strong>        Bezeichnung f&uuml;r Senden-Button:</strong><br />
        <input type="text" name="VALUE[7]" value="REX_VALUE[7]" class="inp100" />
        <br>
        <br></td>
    </tr>
  </table>
  <p class="formgenheadline">Bestätigungs-E-Mail an den Absender</p>
  <p><strong>Soll eine Best&auml;tigungs-Email erstellt werden? </strong> 
    <input name="VALUE[10]" type="checkbox" id="VALUE[10]" value="ok" <? if ("REX_VALUE[10]" == 'ok') echo 'checked="checked"'; ?>  />
JA </p>
  
 <div id="bestaetigung"> 
  <p><strong>(Ihre) Absenderadresse f&uuml;r die Best&auml;tigungs-Email:</strong><br />
    <input type="text" name="VALUE[2]" value="REX_VALUE[2]" class="inp100" />
    <span class="formgenalias">(%Absender%)</span><br>
    <br>
    <strong>Datei anhängen: </strong>REX_MEDIA_BUTTON[1] </p>
  <p><strong>Bestätigungstext</strong></p>
  <p>
    <textarea name="VALUE[5]" style="width:100%;height:80px;">REX_VALUE[5]</textarea>
    <span class="formgen_sample"><strong>Platzhalter:</strong> %Datum% , %Zeit%, %Absender%, %Mail%  </span><br>
    <br>
  </p>
 </div>
  <p class="formgenheadline"><strong>Danksagung</strong> (wird auf der Website nach dem Versand angezeigt)</p>
  <p>
    <textarea name="VALUE[6]" style="width:100%;height:80px;">REX_VALUE[6]</textarea>
  </p>
  <p class="formgenheadline">Formularfelder (siehe Beispiel)<br>
  </p>
  <p> typ|bezeichnung|pflicht|default|value/s|validierung <br>
    <textarea name="VALUE[3]" class="formgenconfig" style="width:100%;height:250px;">REX_VALUE[3]</textarea>
  </p>
  <p align="right"><a href="http://wiki.redaxo.de/index.php?n=Formular-Generator4.5" target="_blank">HILFE</a></p>
  <br>
<br>
<br>
<br>
<div id="anleitung" style="<?php echo (!$anleitung) ? 'display: none' : 'display: block'; ?>">
<p class="formgenheadline">Beispiel:</p>
  <p>
    <textarea name="demo" cols="80" rows="11" class="formgen_sample" style="width:100%;height:100px;">fieldstart|Kontaktdaten
text|Vorname *|1|||
text|Firma |
text|Straße|
text|PLZ*|1|||digit
text|Ort*|1|||
text|Telefon||||digit
text|Telefax||||digit
fieldend|
fieldstart|Weitere Angaben
divstart|cssklasse
radio|Geschlecht|0|Mann;Frau|m;w|
password|Ihr Passwort*|1|||alpha
text|E-Mail *|1|||absendermail
hinweistext|Bitte gebe Sie Ihre E-Mail-Adresse nochmals ein
text|E-Mail*|1|||echeck
divend|
select|Auswahl|1||Birne;Apfel;Kirsche
fieldend|
textarea|Ihre Nachricht: *|1|</textarea>
  </p>

  <p class="formgenheadline">Kurzbeschreibung:</p>
  <table width="538" class="formgen_manual">
    <tr>
      <td width="130" style="vertical-align:top;"><strong>Typen</strong></td>
      <td width="396" valign="top"> <p>text<br />
textarea<br />
select <br>
checkbox<br />
headline<br>
hinweistext<br>
radio<br>
password<br>
spamschutz (Setzt das Captchabild mit Info ein)<br>
</p>        
        <hr size="1">
        <p><strong>Gestaltungselemente:</strong><br>
          fieldstart|label<br>
          fieldend<br>
          divstart|css-klasse<br>
        divend</p></td>
    </tr>
    <tr>
      <td style="vertical-align:top;"><strong>Bezeichnung</strong></td>
      <td>Feldbezeichnung</td>
    </tr>
    <tr>
      <td style="vertical-align:top;"><strong>Pflicht</strong></td>
      <td>1 sonst 0 oder leer</td>
    </tr>
    <tr>
      <td style="vertical-align:top;"><strong>Default</strong></td>
      <td valign="top">Wert der bereits erscheinen wird.<br /></td>
    </tr>
    <tr>
      <td style="vertical-align:top;"><strong>Value/s</strong></td>
      <td>Werte für Checkbox, Radio und select, getrennt per ; </td>
    </tr>
    <tr>
      <td style="vertical-align:top;">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td style="vertical-align:top;"><strong>Validierung</strong></td>
      <td valign="top"><ul>
          <li>echeck prüft ob die Eingabe der Absendermail entspricht</li>
          <li>alpha (nur engl.Buchstaben) </li>
        <li>digit (nur Zahlen)</li>
        <li>plz (midestens 5 Zahlen)</li>
        <li>telefon (mindestens 6 Zahlen)</li>
        <li>letters (z.B. für Namen)</li>
        <li> mail (pr&uuml;ft eingegebene Email-Adressen) </li>
        <li> absendermail (diese Adresse wird als Absendermail eingesetzt)</li>
        <li>captcha - prüft die Captcha-Eingabe<br>
        </li>
      </ul></td>
    </tr>
  </table>
  <p>typ|bezeichnung|pflicht|default|value|validierung </p>
  <p>&nbsp;</p>
  </div>
  <div align="right"><a href="http://www.klxm.de" target="_blank">KLXM Crossmedia GmbH</a></div>
</div>
