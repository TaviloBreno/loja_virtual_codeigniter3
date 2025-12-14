<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| CONFIGURAÇÕES DE LOGIN SOCIAL
| -------------------------------------------------------------------
| Configurações para autenticação via redes sociais
| 
| Para obter suas credenciais:
| 
| Facebook:
| 1. Acesse: https://developers.facebook.com/
| 2. Crie um novo App
| 3. Configure o App ID e App Secret
| 4. Configure URL de redirecionamento: http://seu-dominio.com/login/facebook_callback
|
| Google:
| 1. Acesse: https://console.developers.google.com/
| 2. Crie um novo projeto
| 3. Ative a Google+ API
| 4. Configure o Client ID e Client Secret
| 5. Configure URL de redirecionamento: http://seu-dominio.com/login/google_callback
*/

$config['social_auth'] = array(
    // Facebook
    'facebook' => array(
        'enabled' => TRUE,
        'app_id' => 'SEU_FACEBOOK_APP_ID',
        'app_secret' => 'SEU_FACEBOOK_APP_SECRET',
        'callback_url' => base_url('login/facebook_callback'),
        'scope' => 'email,public_profile',
        'graph_api_version' => 'v18.0'
    ),
    
    // Google
    'google' => array(
        'enabled' => TRUE,
        'client_id' => 'SEU_GOOGLE_CLIENT_ID',
        'client_secret' => 'SEU_GOOGLE_CLIENT_SECRET',
        'callback_url' => base_url('login/google_callback'),
        'scope' => 'email profile',
        'access_type' => 'online'
    )
);
