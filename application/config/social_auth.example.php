<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| EXEMPLO DE CONFIGURAÇÃO - RENOMEIE PARA social_auth.php
| -------------------------------------------------------------------
|
| INSTRUÇÕES:
| 1. Renomeie este arquivo para: social_auth.php
| 2. Substitua os valores "SEU_..." pelas suas credenciais reais
| 3. Siga o guia em LOGIN_SOCIAL.md para obter as credenciais
|
*/

$config['social_auth'] = array(
    
    // ============================================
    // FACEBOOK
    // ============================================
    // Obtenha suas credenciais em:
    // https://developers.facebook.com/
    //
    // Passos:
    // 1. Crie um novo App
    // 2. Adicione o produto "Facebook Login"
    // 3. Configure a URL de redirecionamento:
    //    http://localhost/loja_virtual/login/facebook_callback
    //    http://seu-dominio.com/login/facebook_callback
    // 4. Copie o App ID e App Secret
    //
    'facebook' => array(
        'enabled' => TRUE,  // Mude para FALSE para desabilitar
        'app_id' => 'SEU_FACEBOOK_APP_ID_AQUI',
        'app_secret' => 'SEU_FACEBOOK_APP_SECRET_AQUI',
        'callback_url' => base_url('login/facebook_callback'),
        'scope' => 'email,public_profile',
        'graph_api_version' => 'v18.0'
    ),
    
    // ============================================
    // GOOGLE
    // ============================================
    // Obtenha suas credenciais em:
    // https://console.developers.google.com/
    //
    // Passos:
    // 1. Crie um novo projeto
    // 2. Ative a Google+ API
    // 3. Crie credenciais OAuth 2.0
    // 4. Configure a URL de redirecionamento:
    //    http://localhost/loja_virtual/login/google_callback
    //    http://seu-dominio.com/login/google_callback
    // 5. Copie o Client ID e Client Secret
    //
    'google' => array(
        'enabled' => TRUE,  // Mude para FALSE para desabilitar
        'client_id' => 'SEU_GOOGLE_CLIENT_ID_AQUI.apps.googleusercontent.com',
        'client_secret' => 'SEU_GOOGLE_CLIENT_SECRET_AQUI',
        'callback_url' => base_url('login/google_callback'),
        'scope' => 'email profile',
        'access_type' => 'online'
    )
);

/*
| -------------------------------------------------------------------
| EXEMPLO DE CONFIGURAÇÃO COMPLETA
| -------------------------------------------------------------------
|
| Facebook:
| 'app_id' => '123456789012345',
| 'app_secret' => 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
|
| Google:
| 'client_id' => '123456789012-abcdefghijklmnopqrstuvwxyz012345.apps.googleusercontent.com',
| 'client_secret' => 'ABCDEF-ghijklMNOPQRstuvwxyz',
|
*/
