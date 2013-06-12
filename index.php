<?php
require('./google-api-php-client/src/Google_Client.php');
require('./google-api-php-client/src/contrib/Google_DriveService.php');
require_once './xml.php';

//session start
session_start();

$client = new Google_Client();

//clientID
$client->setClientId('59507635988-l3a65l3kn463bf76vnikprdeh1u9hnh3.apps.googleusercontent.com');
//clientsecret
$client->setClientSecret('FXExfhbfx8sEAMJfSAbgxpHf');
//redirectUri
$client->setRedirectUri('http://kfes.jp/php/');//�Q�l�T�C�g�ʂ肾�Ƃ��܂������Ȃ�

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
                // �ǉ��������t�@�C���I�u�W�F�N�g���쐬
                $file = new Google_DriveFile();
                $file->setTitle('test.xml');
                $file->setDescription('�e�X�g�t�@�C���ł���I');
                $file->setMimeType('application/xml');

                // �e�Ƃ������t�H���_�� ID
                $parentId = 'root';
                // �e�I�u�W�F�N�g
                $parent = new Google_ParentReference();
                $parent->setId($parentId);

                // �t�@�C���ɐe���Z�b�g
                $file->setParents(array($parent));

                // �t�@�C���f�[�^
                // ����̓e�L�X�g�Ƃ��ĂȂ̂ł����̕�����
                // xml�̒��g��p��
                $xml = new XML();
                $xmlData = array();
                $xmlData[0] = array(
                        'title' => 'ohkibi',
                        'url' => 'http://kfes.jp',
                        'description' => 'test');

                $createdFile = $service->files->insert($file, array(
                        'data' => createXML($xmlData),
                        'mimeType' => 'text/plain',
                ));
        }catch(Google_Exception $e){
                echo $e->getMessage();
        }
}else{
        $authUrl = $client->createAuthUrl();
        echo '<a href="'.$authUrl.'">�F���Ă�������<a>';
}

function createXML($xmlArray){
        //xml�̂ЂȌ`������
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


