<?php

defined('BASEPATH') or exit('Ação não permitida');


class Usuarios extends CI_Controller
{
	private $core_model;

	public function __construct()
	{
		parent::__construct();

		$this->core_model = $this->load->model('core_model');
	}

	public function index()
	{
		$data = array(
			'usuarios' => $this->ion_auth->users()->result(),
			'titulo' => 'Usuários do sistema',
			'styles' => array(
				'bundles/datatables/datatables.min.css',
				'bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'bundles/datatables/datatables.min.js',
				'bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'bundles/jquery-ui/jquery-ui.min.js',
				'js/page/datatables.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/usuarios/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($usuario_id = NULL)
	{
		if (!$usuario_id) {
		} else {
			if (!$usuario = $this->ion_auth->user($usuario_id)->row()) {

				$this->session->set_flashdata('error', 'Usuário não encontrado');
				redirect('restrita/usuarios');
			} else {

				$this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[4]|max_length[45]');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|max_length[100]|callback_valida_email|is_unique[users.email]');
				$this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[4]|max_length[45]|callback_valida_usuario|is_unique[users.username]');
				$this->form_validation->set_rules('password', 'Senha', 'trim|min_length[6]|max_length[200]');
				$this->form_validation->set_rules('confirm_password', 'Confirmação de senha', 'trim|matches[password]');
				$this->form_validation->set_rules('active', 'Ativo', 'trim|required|in_list[0,1]');

				if ($this->form_validation->run()) {

					$data = elements(
						array(
							'first_name',
							'last_name',
							'email',
							'username',
							'active',
						),
						$this->input->post()
					);

					$this->core_model->update('users', $data, array('id' => $usuario->id));

					redirect('restrita/usuarios');
				} else {
					$data = array(
						'titulo' => 'Editar usuário',
						'usuario' => $usuario,
					);

					$this->load->view('restrita/layout/header', $data);
					$this->load->view('restrita/usuarios/core');
					$this->load->view('restrita/layout/footer');
				}
			}
		}
	}

	public function valida_email($email)
	{
		$usuario_id = $this->input->post('usuario_id');

		if (!$usuario_id) {
			if ($this->core_model->get_by_id('users', array('email' => $email))) {
				$this->form_validation->set_message('valida_email', 'Esse e-mail já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('users', array('email' => $email, 'id !=' => $usuario_id))) {
				$this->form_validation->set_message('valida_email', 'Esse e-mail já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	public function valida_usuario($username)
	{
		$usuario_id = $this->input->post('usuario_id');

		if (!$usuario_id) {
			if ($this->core_model->get_by_id('users', array('username' => $username))) {
				$this->form_validation->set_message('valida_usuario', 'Esse usuário já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			if ($this->core_model->get_by_id('users', array('username' => $username, 'id !=' => $usuario_id))) {
				$this->form_validation->set_message('valida_usuario', 'Esse usuário já existe');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
}
