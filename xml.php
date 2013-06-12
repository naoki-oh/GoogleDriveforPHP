<?php
class XML{

        private $_xmlElement = array();

        /**
         * コンストラクタ 
         * xmlに盛り込む要素を引数で受け取る
         */
        function __construct($xmlElement){
                $this->_xmlElement = $xmlElement;   
        }
        
        /**
         * コンストラクタで設定した要素に対してのデータを受け取り
         * xml形式に直したデータをリターンする
         */
        function outputXML($xmlData){
                $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $xmlString .= "<list>" . "\n";
                foreach($xmlData as $data){
                        $xmlString .= "<item>";
                        for($j = 0; $j<count($this->_xmlElement); $j++){
                                $xmlString .= '<"'. $this->_xmlElement[$j] .'">' .$data[$this->_xmlElement[$j]];
                        } 
                        $xmlString .= "</item>";
                }
                $xmlString .= "</list>";
                return $xmlString;
        }

        function inputXML($inpuXmlData){
        
        }
}
?>
