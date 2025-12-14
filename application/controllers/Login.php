<?php

defined('BASEPATH') or exit('Ação não permitida');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		// Carregar bibliotecas de autenticação social
		$this->load->library('facebook_auth');
		$this->load->library('google_auth');
		$this->load->model('core_model');
	}

	public function index()
	{
		// Se já estiver logado, redireciona para área restrita
		if ($this->ion_auth->logged_in()) {
			redirect('restrita');
		}

		$this->form_validation->set_rules('email', 'E-mail ou Usuário', 'trim|required');
		$this->form_validation->set_rules('password', 'Senha', 'trim|required');

		if ($this->form_validation->run()) {

			$identity = $this->input->post('email');
			$password = $this->input->post('password');
			$remember = ($this->input->post('remember') ? TRUE : FALSE);

			if ($this->ion_auth->login($identity, $password, $remember)) {

				$this->session->set_flashdata('sucesso', 'Bem-vindo(a) ao sistema!');
				redirect('restrita');
			} else {
				$this->session->set_flashdata('error', 'E-mail/usuário ou senha incorretos');
				redirect('login');
			}
		} else {
			$data = array(
				'titulo' => 'Login',
			);

			$this->load->view('login/index', $data);
		}
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('login');
	}

	public function esqueci_senha()
	{
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

		if ($this->form_validation->run()) {

			$email = $this->input->post('email');

			if ($this->ion_auth->forgotten_password($email)) {
				$this->session->set_flashdata('sucesso', 'Enviamos instruções para recuperação de senha em seu e-mail');
				redirect('login');
			} else {
				$this->session->set_flashdata('error', 'E-mail não encontrado');
				redirect('login/esqueci_senha');
			}
		} else {
			$data = array(
				'titulo' => 'Recuperar Senha',
			);

			$this->load->view('login/esqueci_senha', $data);
		}
	}

	/**
	 * Redireciona para autenticação do Facebook
	 */
	public function facebook()
	{
		$login_url = $this->facebook_auth->get_login_url();
		redirect($login_url);
	}

	/**
	 * Callback do Facebook
	 */
	public function facebook_callback()
	{
		$user_data = $this->facebook_auth->callback();

		if (isset($user_data['error'])) {
			$this->session->set_flashdata('error', 'Erro ao autenticar com Facebook: ' . $user_data['error']);
			redirect('login');
		}

		// Processar login social
		$this->process_social_login($user_data);
	}

	/**
	 * Redireciona para autenticação do Google
	 */
	public function google()
	{
		$login_url = $this->google_auth->get_login_url();
		redirect($login_url);
	}

	/**
	 * Callback do Google
	 */
	public function google_callback()
	{
		$user_data = $this->google_auth->callback();

		if (isset($user_data['error'])) {
			$this->session->set_flashdata('error', 'Erro ao autenticar com Google: ' . $user_data['error']);
			redirect('login');
		}

		// Processar login social
		$this->process_social_login($user_data);
	}

	/**
	 * Processa login via redes sociais
	 */
	private function process_social_login($user_data)
	{
		// Verificar se já existe um usuário com esse e-mail
		$email = $user_data['email'];

		if (!$email) {
			$this->session->set_flashdata('error', 'Não foi possível obter seu e-mail. Por favor, permita o acesso ao e-mail.');
			redirect('login');
		}

		$usuario = $this->core_model->get_by_id('users', array('email' => $email));

		if ($usuario) {
			// Usuário já existe - fazer login
			$user_id = $usuario->id;

			// Atualizar última data de login
			$this->ion_auth->update($user_id, array(
				'last_login' => date('Y-m-d H:i:s')
			));

			// Criar sessão manualmente
			$this->session->set_userdata('identity', $usuario->username);
			$this->session->set_userdata('user_id', $user_id);

			$this->session->set_flashdata('sucesso', 'Bem-vindo(a) de volta, ' . $usuario->first_name . '!');
			redirect('restrita');
		} else {
			// Criar novo usuário
			$username = $this->generate_unique_username($user_data['first_name'], $user_data['last_name']);
			$password = random_string('alnum', 16); // Senha aleatória
			$email = $user_data['email'];

			$additional_data = array(
				'first_name' => $user_data['first_name'],
				'last_name' => $user_data['last_name'],
				'active' => 1,
			);

			// Registrar novo usuário
			$user_id = $this->ion_auth->register($username, $password, $email, $additional_data);

			if ($user_id) {
				// Adicionar ao grupo de clientes (ID 2)
				$this->ion_auth->add_to_group(2, $user_id);

				// Criar sessão manualmente
				$this->session->set_userdata('identity', $username);
				$this->session->set_userdata('user_id', $user_id);

				$this->session->set_flashdata('sucesso', 'Bem-vindo(a) ao sistema, ' . $user_data['first_name'] . '!');
				redirect('restrita');
			} else {
				$this->session->set_flashdata('error', 'Erro ao criar sua conta. Por favor, tente novamente.');
				redirect('login');
			}
		}
	}

	/**
	 * Gera um nome de usuário único
	 */
	private function generate_unique_username($first_name, $last_name)
	{
		$base_username = strtolower($first_name . $last_name);
		$base_username = preg_replace('/[^a-z0-9]/', '', $base_username);
		$username = $base_username;
		$counter = 1;

		while ($this->core_model->get_by_id('users', array('username' => $username))) {
			$username = $base_username . $counter;
			$counter++;
		}

		return $username;
	}
}
