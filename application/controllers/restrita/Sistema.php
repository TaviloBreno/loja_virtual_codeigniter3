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
		$this->form_validation->set_rules('sistema_razao_social', 'Razão Social', 'trim|required|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('sistema_nome_fantasia', 'Nome Fantasia', 'trim|required|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('sistema_cnpj', 'CNPJ', 'trim|required|min_length[18]|max_length[18]');
		$this->form_validation->set_rules('sistema_ie', 'Inscrição Estadual', 'trim|required|min_length[4]|max_length[25]');
		$this->form_validation->set_rules('sistema_telefone_fixo', 'Telefone Fixo', 'trim|required|min_length[14]|max_length[15]');
		$this->form_validation->set_rules('sistema_telefone_movel', 'Telefone Móvel', 'trim|required|min_length[14]|max_length[15]');
		$this->form_validation->set_rules('sistema_email', 'E-mail', 'trim|required|valid_email|max_length[100]');
		$this->form_validation->set_rules('sistema_endereco', 'Endereço', 'trim|required|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('sistema_cep', 'CEP', 'trim|required|min_length[9]|max_length[9]');
		$this->form_validation->set_rules('sistema_cidade', 'Cidade', 'trim|required|min_length[2]|max_length[145]');
		$this->form_validation->set_rules('sistema_estado', 'Estado', 'trim|required|min_length[2]|max_length[2]');
		$this->form_validation->set_rules('sistema_site_url', 'URL do Site', 'trim|required|min_length[4]|max_length[145]');
		$this->form_validation->set_rules('sistema_numero', 'Número', 'trim|required|min_length[1]|max_length[20]');
		$this->form_validation->set_rules('sistema_produtos_destaques', 'Produtos em Destaque', 'trim|required');
		$this->form_validation->set_rules('sistema_texto', 'Texto do sistema', 'trim|max_length[500]');

		if ($this->form_validation->run()) {

			$data = elements(
				array(
					'sistema_razao_social',
					'sistema_nome_fantasia',
					'sistema_cnpj',
					'sistema_ie',
					'sistema_telefone_fixo',
					'sistema_telefone_movel',
					'sistema_email',
					'sistema_endereco',
					'sistema_cep',
					'sistema_cidade',
					'sistema_estado',
					'sistema_site_url',
					'sistema_numero',
					'sistema_produtos_destaques',
					'sistema_texto',
				),
				$this->input->post()
			);

			$data['sistema_estado'] = strtoupper($data['sistema_estado']);

			$data = html_escape($data);

			$resultado = $this->core_model->update('sistema', $data, array('sistema_id' => 1));

			if ($resultado) {
				$this->session->set_flashdata('sucesso', 'Dados atualizados com sucesso');
			} else {
				$this->session->set_flashdata('error', 'Erro ao atualizar os dados');
			}

			redirect('restrita/sistema');
		} else {
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
}
