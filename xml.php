<?php

class XML {

        //include "exampleXML.php";

        private $_xmlElement = array();
        private $xmlString = '';

        /**
         * コンストラクタ 
         * xmlに盛り込む要素を引数で受け取る
         */
        function __construct($xmlElement) {
                $this->_xmlElement = $xmlElement;
        }

        /**
         * コンストラクタで設定した要素に対してのデータを受け取り
         * xml形式に直したデータをリターンする
         */
        function outputXML($xmlData) {
                $this->xmlString .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $this->xmlString .= "<list>" . "\n";
                foreach ($xmlData as $data) {
                        /**入力されたデータのキーとコンストラクタで受け取ったキーが一致していれば**/
                        if ($this->checkInputData($data)) {
                                $this->xmlString .= "<item>" . "\n";
                                for ($j = 0; $j < count($this->_xmlElement); $j++) {
                                        $this->xmlString .="\t" . '<' . $this->_xmlElement[$j] . '>' . $data[$this->_xmlElement[$j]] . '</' . $this->_xmlElement[$j] . '>' . "\n";
                                }
                                $this->xmlString .= "</item>" . "\n";
                        } else {//一致していない
                                continue;
                        }
                }
                $this->xmlString .= "</list>";
                return $this->xmlString;
        }

        /**
         * 入力されたデータのキーがコンストラクタで受け取ったキーと一致するかチェックする
         * @param type $data
         * @return boolean
         */
        function checkInputData($data) {
                if (count($data) != count($this->_xmlElement)) {
                        return false;
                } else {
                        $keys = array_keys($data);
                        for ($i = 0; $i < count($this->_xmlElement); $i++) {
                                if ($keys[$i] != $this->_xmlElement[$i]) {
                                        return false;
                                }
                        }
                }
                return true;
        }

        function inputXML() {
$xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<movies>
 <movie>
  <title>PHP: Behind the Parser</title>
  <characters>
   <character>
    <name>Ms. Coder</name>
    <actor>Onlivia Actora</actor>
   </character>
   <character>
    <name>Mr. Coder</name>
    <actor>El Act&#211;r</actor>
   </character>
  </characters>
  <plot>
   So, this language. It's like, a programming language. Or is it a
   scripting language? All is revealed in this thrilling horror spoof
   of a documentary.
  </plot>
  <great-lines>
   <line>PHP solves all my web problems</line>
  </great-lines>
  <rating type="thumbs">7</rating>
  <rating type="stars">5</rating>
 </movie>
</movies>
XML;
                /**XMLを解析して配列に収める**/

                $xml = new SimpleXMLElement($xmlstr);
                echo $xml->movie[0]->plot;
                //echo $xmlstr;
        }
}
?>
