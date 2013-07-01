<?php
require('./google-api-php-client/src/Google_Client.php');
require('./google-api-php-client/src/contrib/Google_DriveService.php');
require_once './xml.php';
require_once './GoogleDrive.php';

//session start
session_start();

$client = new Google_Client();

//clientID
$client->setClientId('59507635988-l3a65l3kn463bf76vnikprdeh1u9hnh3.apps.googleusercontent.com');
//clientsecret
$client->setClientSecret('FXExfhbfx8sEAMJfSAbgxpHf');
//redirectUri
$client->setRedirectUri('http://kfes.jp/php/');//参考サイト通りだとうまくいかない

$service = new Google_DriveService($client);
if(isset($_GET['code'])){
        $client->authenticate();
        $_SESSION['token'] = $client->getAccessToken();
        header('Location: http://kfes.jp/php/index.php');
        exit;
}

if(isset($_SESSION['token'])){
        $client->setAccessToken($_SESSION['token']);
}

if($client->getAccessToken()){
        try{
                // 追加したいファイルオブジェクトを作成
                $file = new Google_DriveFile();
                $file->setTitle('test.xml');
                $file->setDescription('テストファイルですよ！');
                $file->setMimeType('application/xml');

                // 親としたいフォルダの ID
                $parentId = 'root';
                // 親オブジェクト
                $parent = new Google_ParentReference();
                $parent->setId($parentId);

                // ファイルに親をセット
                $file->setParents(array($parent));

                // ファイルデータ
                // 今回はテキストとしてなのでただの文字列
                // xmlの中身を用意
                $xmlElement = array('name', 'age');
                $xml = new XML($xmlElement);
                $xmlData = array();
                $xmlData[0] = array(
                        'name' => 'naoki',
                        'age' => '19');
                $xmlData[1] = array(
                        'name' => 'matsuo',
                        'age' => '16'
                );

                //echo createXML(array('title' => 'aaa', 'url' => 'aaa', 'description' => 'de'));

                $createdFile = $service->files->insert($file, array(
                        'data' => $xml->outputXML($xmlData),
                        'mimeType' => 'application/xml',
                ));
                $xml->inputXML();
        }catch(Google_Exception $e){
                echo $e->getMessage();
        }
}else{
        $authUrl = $client->createAuthUrl();
        echo '<a href="'.$authUrl.'">認可してください<a>';
}

function createXML($xmlArray){
        //xmlのひな形をつくる
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xmlString .= "<list>" . "\n";
        foreach($xmlArray as $value){
                $xmlString .= "<item>";
                $xmlString .= "<title>".$value['title']."</title>";
                $xmlString .= "<url>".$value['url']."</url>";
                $xmlString .= "<description>".$value['description']."</description>";
                $xmlString .= "</item>" . "\n";
        }
        $xmlString .= "</list>" . "\n";
        return $xmlString;
}
?>


