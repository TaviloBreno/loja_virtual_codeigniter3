<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Biblioteca para autenticação com Facebook
 */
class Facebook_auth
{
    protected $CI;
    private $app_id;
    private $app_secret;
    private $callback_url;
    private $scope;
    private $graph_api_version;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('social_auth');
        
        $config = $this->CI->config->item('social_auth');
        $facebook = $config['facebook'];
        
        $this->app_id = $facebook['app_id'];
        $this->app_secret = $facebook['app_secret'];
        $this->callback_url = $facebook['callback_url'];
        $this->scope = $facebook['scope'];
        $this->graph_api_version = $facebook['graph_api_version'];
    }

    /**
     * Retorna a URL de autenticação do Facebook
     */
    public function get_login_url()
    {
        $state = md5(uniqid(rand(), TRUE));
        $this->CI->session->set_userdata('fb_state', $state);

        $params = array(
            'client_id' => $this->app_id,
            'redirect_uri' => $this->callback_url,
            'scope' => $this->scope,
            'state' => $state,
            'response_type' => 'code'
        );

        return 'https://www.facebook.com/' . $this->graph_api_version . '/dialog/oauth?' . http_build_query($params);
    }

    /**
     * Processa o callback do Facebook
     */
    public function callback()
    {
        $code = $this->CI->input->get('code');
        $state = $this->CI->input->get('state');
        $stored_state = $this->CI->session->userdata('fb_state');

        // Validar state para prevenir CSRF
        if (!$state || $state !== $stored_state) {
            return array('error' => 'Estado de autenticação inválido');
        }

        // Trocar código por token de acesso
        $token_url = 'https://graph.facebook.com/' . $this->graph_api_version . '/oauth/access_token';
        $params = array(
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'redirect_uri' => $this->callback_url,
            'code' => $code
        );

        $response = $this->make_request($token_url . '?' . http_build_query($params));
        
        if (!$response || isset($response['error'])) {
            return array('error' => 'Erro ao obter token de acesso');
        }

        $access_token = $response['access_token'];

        // Buscar dados do usuário
        $user_data = $this->get_user_data($access_token);

        return $user_data;
    }

    /**
     * Busca dados do usuário no Facebook
     */
    private function get_user_data($access_token)
    {
        $user_url = 'https://graph.facebook.com/' . $this->graph_api_version . '/me';
        $params = array(
            'fields' => 'id,name,email,first_name,last_name,picture.type(large)',
            'access_token' => $access_token
        );

        $user_data = $this->make_request($user_url . '?' . http_build_query($params));

        if (!$user_data || isset($user_data['error'])) {
            return array('error' => 'Erro ao buscar dados do usuário');
        }

        return array(
            'id' => $user_data['id'],
            'email' => isset($user_data['email']) ? $user_data['email'] : null,
            'first_name' => $user_data['first_name'],
            'last_name' => $user_data['last_name'],
            'name' => $user_data['name'],
            'picture' => $user_data['picture']['data']['url'],
            'provider' => 'facebook'
        );
    }

    /**
     * Faz requisição HTTP
     */
    private function make_request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
