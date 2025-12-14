<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Biblioteca para autenticação com Google
 */
class Google_auth
{
    protected $CI;
    private $client_id;
    private $client_secret;
    private $callback_url;
    private $scope;
    private $access_type;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('social_auth');
        
        $config = $this->CI->config->item('social_auth');
        $google = $config['google'];
        
        $this->client_id = $google['client_id'];
        $this->client_secret = $google['client_secret'];
        $this->callback_url = $google['callback_url'];
        $this->scope = $google['scope'];
        $this->access_type = $google['access_type'];
    }

    /**
     * Retorna a URL de autenticação do Google
     */
    public function get_login_url()
    {
        $state = md5(uniqid(rand(), TRUE));
        $this->CI->session->set_userdata('google_state', $state);

        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $this->callback_url,
            'response_type' => 'code',
            'scope' => $this->scope,
            'access_type' => $this->access_type,
            'state' => $state
        );

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Processa o callback do Google
     */
    public function callback()
    {
        $code = $this->CI->input->get('code');
        $state = $this->CI->input->get('state');
        $stored_state = $this->CI->session->userdata('google_state');

        // Validar state para prevenir CSRF
        if (!$state || $state !== $stored_state) {
            return array('error' => 'Estado de autenticação inválido');
        }

        // Trocar código por token de acesso
        $token_url = 'https://oauth2.googleapis.com/token';
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->callback_url,
            'grant_type' => 'authorization_code',
            'code' => $code
        );

        $response = $this->make_post_request($token_url, $params);
        
        if (!$response || isset($response['error'])) {
            return array('error' => 'Erro ao obter token de acesso');
        }

        $access_token = $response['access_token'];

        // Buscar dados do usuário
        $user_data = $this->get_user_data($access_token);

        return $user_data;
    }

    /**
     * Busca dados do usuário no Google
     */
    private function get_user_data($access_token)
    {
        $user_url = 'https://www.googleapis.com/oauth2/v2/userinfo';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $access_token
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        $user_data = json_decode($response, true);

        if (!$user_data || isset($user_data['error'])) {
            return array('error' => 'Erro ao buscar dados do usuário');
        }

        // Separar nome em first_name e last_name
        $name_parts = explode(' ', $user_data['name'], 2);
        $first_name = $name_parts[0];
        $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

        return array(
            'id' => $user_data['id'],
            'email' => $user_data['email'],
            'first_name' => $first_name,
            'last_name' => $last_name,
            'name' => $user_data['name'],
            'picture' => $user_data['picture'],
            'provider' => 'google'
        );
    }

    /**
     * Faz requisição POST
     */
    private function make_post_request($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
