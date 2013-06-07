<?php
        require('./google-api-php-client/src/Google_Client.php');
        require('./google-api-php-client/src/contrib/Google_DriveService.php');

        //session start
        session_start();

        $client = new Google_Client();

        //clientID
        $client->setClientId('59507635988-u2id7o6e5odcv05e7ik1r0kkah4ruf2o.apps.googleusercontent.com');
        //clientsecret
        $client->setClientSecret('W10KUqy_6UFHz3qiJiUbfmOl');
        $client->setRedirectUri('http://localhost/oauth2callback');

        $service = new Google_DriveService($client);

        //
        //
        if(isset($_GET['code'])){
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
                header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
                exit;
        }

        if(isset($_SESSION['token'])){
                $client->setAccessToken($_SESSION['token']);
        }

        if($client->getAccessToken()){
                try{
                        
                }catch(Google_Exception $e){
                        echo $e->getMessage();
                }
        }else{
                $authUrl = $client->createAuthUrl();
                echo '<a href="'.$authUrl.'">アプリケーションのアクセスを許可してください<a>';
        }

?>
