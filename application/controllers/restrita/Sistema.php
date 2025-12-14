<?php
defined('BASEPATH') or exit('Ação não permitida');

class Sistema extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('core_model');

		// Verificar se está logado
		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'titulo' => 'Configurações do sistema',
			'sistema' => $this->core_model->get_by_id('sistema', array('sistema_id' => 1)),
			'scripts' => array(
				'mask/jquery.mask.min.js',
				'mask/custom.js',
			),
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/sistema/index');
		$this->load->view('restrita/layout/footer');
	}
}
