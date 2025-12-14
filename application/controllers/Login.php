<?php

defined('BASEPATH') or exit('Ação não permitida');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
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
}
