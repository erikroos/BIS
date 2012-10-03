<?php

function xmlnews($url, $timeout = 0, $target = "_top", $makelist = "br", $links)
/* Maakt HTML van een XML (rss/rdf) newsfeed
* Voorbeelden:
*   echo xmlnews();
*   $hetnieuws = xmlnews( "http://www.knrb.nl/cmsrss.php", 3, "_blank", "li" );
* Parameters:
*   $url (string) = eigen URL ("http://etc.").
*   $timeout (integer) = als groter dan nul, de maximale tijd
*     in seconden dat het ophalen van de feed mag duren.
*     Standaard: 0 (dwz. geen limiet, behalve de PHP instellingen
*     voor file_get_contents() of file())
*   $target (string) = de waarde voor de 'target' parameter van
*     het HTML <a> element (de nieuwslinks). Standaard: "_top"
*   $makelist (string) = "br", "li" of "tr". De manier waarop de
*     nieuwslinks onder elkaar worden gezet, met HTML elementen
*     <br />, <li></li> of <tr><td></td></tr>. Standaard: "br"
*	$links (bool) = 0 (items worden niet als link weergegeven, bv. ingeval van
*	  weersinfo van Gyas; of 1 (items worden als link weergegeven, bv. bij nieuws)
*/
{
  if ( $timeout > 0 || ini_get( "allow_url_fopen" ) == "0" )
  { // als $timeout gezet is, of fopen wrappers niet geactiveerd zijn
/*
    // breek de url op in delen
    $pu = parse_url( $url );
    if ( isset( $pu['port'] ) )
    {
      if ( $pu['port'] != 80 && $pu['port'] != 8080 )
        $pu['port'] = 80;
    }
    else
      $pu['port'] = 80;

    // open socket (verbinding) met $timeout
    $errno = 0;
    $errstr = "";
    if ( $timeout <= 0 ) $timeout = 3;
    $t = time();
    $fp = fsockopen($pu['host'], $pu['port'], $errno, $errstr, $timeout);
    if (!$fp) return "Verbinding met gegevensbron mislukt...";

    // vraag en lees $data uit socket met $timeout minus verstreken tijd
    $data = "";
    $timeout -= (time() - $t);
    if ( $timeout <= 0 ) $timeout = 1;
    if ( function_exists( "stream_set_timeout" ) )
      stream_set_timeout( $fp, $timeout );
    elseif ( function_exists( "socket_set_timeout" ) )
      socket_set_timeout( $fp, $timeout );
    elseif ( function_exists( "set_socket_timeout" ) )
      set_socket_timeout( $fp, $timeout );
    fputs( $fp, "GET {$pu['path']} HTTP/1.0\r\nHost: {$pu['host']}:{$pu['port']}\r\n\r\n" );
    while ( !feof( $fp ) )
      $data .= fgets( $fp, 4096 );
    fclose( $fp );
    $data = substr( $data, strpos( $data, "\r\n\r\n" ) + 4 );
*/
    $data = file_get_contents($url);
  }
  elseif ( function_exists( "file_get_contents" ) )

    // betere file() functie vanaf php 4.3.0
    $data = file_get_contents( $url );

  else
    $data = implode( "", file( $url ) );

  // ivm. op dit moment niet geheel correcte feed van knrb
  $data = str_replace( "<br />", "", trim( $data ) );

  // parse de xml $data naar array $values met $tags als index
  // zie http://nl.php.net/manual/en/function.xml-parse-into-struct.php
  $parser = xml_parser_create();
  xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
  xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 1 );
  xml_parse_into_struct( $parser, $data, $values, $tags );
  xml_parser_free( $parser );

  // maak $html van de gestructureerde data in $values array
  $html = "";
  
  if (!$links) { // indien Gyas-weer, pubdate (uit laatste item) eronder zetten
	$pubdate = $values[$tags["LASTBUILDDATE"][0]]["value"];
	$timestamp = strtotime($pubdate);
	$pubdatestring = date("d-m-Y H:i", $timestamp);
	$html = "Laatst ververst:<br /><em>".$pubdatestring."</em><br />";
  }
  
  $node = $tags["ITEM"];  // gebruik alleen de index van ITEM nodes

  switch ( $makelist )  // kies de soort lijst (<li>, <tr> of <br />)
  {
    case "li": $pre = "<li>"; $post = "</li>\n\n"; break;
    case "tr": $pre = "<tr><td>"; $post = "</td></tr>\n"; break;
    default: $pre = ""; $post = "<br />\n";
  }

  for ( $i = 0; $i < count($node); $i += 2 )
  { // loop alle ITEM nodes af

    $item = array();
    for ( $j = $node[$i] + 1; $j < $node[$i+1]; ++$j )
    { // haal key=value data (zoals TITLE=x en LINK=y) uit deze ITEM node

      $k = $values[$j]["tag"];
      $v = $values[$j]["value"];
      $item[$k] = $v;

    }
	$skip = false;
    // maak een link (html 'anchor' tag) van de TITLE en LINK data uit de ITEM node
	if ($links) {
    	$lnk = "<a target=\"$target\" href=\"{$item['LINK']}\">".htmlspecialchars( $item['TITLE'] )."</a>";
	} else { // eigenlijk indien Gyas-weersinfo
		$titel = $item['TITLE'];
		$inhoud = $item['DESCRIPTION'];
		if ($titel == "Luchtvochtigheid") $skip = true;
		if ($titel == "Temperatuur") {
			// Parse het graden-teken eruit aangezien veel browsers dit niet kunnen renderen
			// Een of meer getallen (opgeslagen in $1), gevolgd door een of meer tekens niet zijnde "(", gevolgd door "C";
			// Vervang dit door alleen de getallen direct gevolgd door "C"
			$inhoud = preg_replace('/([0-9]+)[^(]+C/', '$1C', $inhoud);
		}
		if ($titel == "Vaarverbod") {
			if ($inhoud == "Geen vaarverbod") $skip = true;
			$titel = "Gyas-vaarverbod [<a href='http://www.hunze.nl/oud/index.php?subnav=subnav_04&amp;location=folder_04&amp;page=page_05&amp;leftnav=leftnav_04' target='_blank'>uitleg</a>]";
			$inhoud_opg = "<strong><font color=\"#FF0000\">".$inhoud."</font></strong>";
		} else {
			$inhoud_opg = "<em>".$inhoud."</em>";
		}
		$lnk = $titel.":<br />".$inhoud_opg;
	}
    // zet passende html tags voor en achter de link
    if (!$skip) $html .= $pre.$lnk.$post;
  } // end FOR
  
  return $html;
}