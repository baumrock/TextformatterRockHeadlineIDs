<?php namespace ProcessWire;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Bernhard Baumrock, 26.01.2022
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class TextformatterRockHeadlineIDs extends Textformatter {

  public $ids = [];

  public static function getModuleInfo() {
    return [
      'title' => 'RockHeadlineIDs',
      'version' => '1.0.1',
      'summary' => 'Demo Textformatter',
    ];
  }

  public function format(&$str, $dump = false) {
    $ids = $this->ids;
    $regex = '/(\<h[1-6](.*?))\>(.*?)(<\/h[1-6]>)/i';
    $str = preg_replace_callback($regex, function($matches) use($dump, &$ids) {
      if($dump) db($matches, 'matches');
      $markup = $matches[0];
      $starttag = $matches[1];
      $endtag = $matches[4];
      preg_match("/(id=['\"]?(\S+?)['\"]+)|(id=(\S+))>?/", $starttag, $findID);
      if($dump) db($findID, 'findID');
      if(count($findID)===3) {
        $rawid = $findID[2];
        $id = $this->getID($rawid);
        while(in_array($id, $ids)) $id.="-";
        $ids[] = $id;
        return str_replace($rawid, $id, $markup);
      }
      elseif(count($findID)===5) {
        $rawid = $findID[4];
        $id = $this->getID($rawid);
        while(in_array($id, $ids)) $id.="-";
        $ids[] = $id;
        return str_replace($rawid, $id, $markup);
      }
      if(stripos($starttag, 'id=')) return $markup;
      $inner = $matches[3];

      // get new id
      // append dashes if it already exists
      $id = $this->getID($inner);
      while(in_array($id, $ids)) $id.="-";

      $ids[] = $id;
      return "$starttag id='$id'>{$inner}$endtag";
    }, $str);
    if($dump) db($ids, 'ids');
  }

  /**
   * Get id from text
   */
  public function ___getID($str) {
    $str = strip_tags($str);
    return $this->wire->sanitizer->pageNameTranslate($str);
  }

  /**
   * Usage (in tracy console):
   * $format = new TextformatterRockHeadlineIDs();
   * $format->test();
   */
  public function test() {
    $str = "
      <h1 class='foobar'><span id='test'>test das ist jö</span></h1><h2 id='test2' class='foo'>test2</h2>
      <h2 id='same-id-test'>same id test1</h2>
      <h2 id='same-id-test'>same id test2</h2>
      <h3>noch ein test</h3>
      <h4>foo bar</h4>
      <h3>same id</h3>
      <h4>same id</h4>
      <h5 id=foo>test without quotes</h5>
      <h5 id=foo class=bar>test without quotes</h5>";
    $this->format($str, true);
    db($str, "result");
  }

}
