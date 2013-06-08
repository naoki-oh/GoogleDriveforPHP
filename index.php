<?php
        require('./google-api-php-client/src/Google_Client.php');
        require('./google-api-php-client/src/contrib/Google_DriveService.php');

        //session start
        session_start();

        $client = new Google_Client();

        //clientID
        $client->setClientId('59507635988-l3a65l3kn463bf76vnikprdeh1u9hnh3.apps.googleusercontent.com');
        //clientsecret
        $client->setClientSecret('FXExfhbfx8sEAMJfSAbgxpHf');
        $client->setRedirectUri('http://kfes.jp/php/');

        $service = new Google_DriveService($client);

        //
        //
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
                        echo "now";
                        /*とりあえずファイル追加*/
                        $file = new Google_DriveFile();
                        $file->setTitle('test.txt');
                        $file->setDescription('testFile');
                        $file->setMimeType('text/plain');
                        $parentId = 'root';
                        $parent = new Google_ParentReference();
                        $parent->setId($parentId);

                        $file->setParents(array($parent));

                        $date = "test";
                        $createdFile = $service->file->Insert($file, array(
                            'data' => $data,
                            'minType' => 'text/plain',
                        ));
                }catch(Google_Exception $e){
                        echo $e->getMessage();
                }
        }else{
                $authUrl = $client->createAuthUrl();
                echo '<a href="'.$authUrl.'">認可してください<a>';
        }

?>
