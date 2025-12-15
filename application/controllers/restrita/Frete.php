<?php

defined('BASEPATH') or exit('Ação não permitida');


class Frete extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Verificar se está logado
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_content_type('application/json');
			echo json_encode(array('success' => false, 'message' => 'Não autorizado'));
			exit;
		}

		$this->load->library('correios');
	}

	/**
	 * Consultar CEP via AJAX
	 */
	public function consultar_cep()
	{
		$this->output->set_content_type('application/json');

		$cep = $this->input->post('cep');

		if (!$cep) {
			echo json_encode(array(
				'success' => false,
				'message' => 'CEP não informado'
			));
			return;
		}

		$resultado = $this->correios->consulta_cep($cep);

		if ($resultado) {
			echo json_encode(array(
				'success' => true,
				'data' => $resultado
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'CEP não encontrado'
			));
		}
	}

	/**
	 * Calcular frete via AJAX
	 */
	public function calcular()
	{
		$this->output->set_content_type('application/json');

		$cep_origem = $this->input->post('cep_origem');
		$cep_destino = $this->input->post('cep_destino');
		$peso = $this->input->post('peso');
		$altura = $this->input->post('altura');
		$largura = $this->input->post('largura');
		$comprimento = $this->input->post('comprimento');
		$valor_declarado = $this->input->post('valor_declarado') ? $this->input->post('valor_declarado') : 0;

		if (!$cep_origem || !$cep_destino) {
			echo json_encode(array(
				'success' => false,
				'message' => 'CEPs de origem e destino são obrigatórios'
			));
			return;
		}

		$params = array(
			'cep_origem' => $cep_origem,
			'cep_destino' => $cep_destino,
			'peso' => $peso ? $peso : 300,
			'altura' => $altura ? $altura : 2,
			'largura' => $largura ? $largura : 11,
			'comprimento' => $comprimento ? $comprimento : 16,
			'valor_declarado' => $valor_declarado,
		);

		$resultados = $this->correios->calcula_frete($params);

		if ($resultados) {
			echo json_encode(array(
				'success' => true,
				'data' => $resultados
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Erro ao calcular frete'
			));
		}
	}

	/**
	 * Consultar prazo de entrega via AJAX
	 */
	public function consultar_prazo()
	{
		$this->output->set_content_type('application/json');

		$cep_origem = $this->input->post('cep_origem');
		$cep_destino = $this->input->post('cep_destino');

		if (!$cep_origem || !$cep_destino) {
			echo json_encode(array(
				'success' => false,
				'message' => 'CEPs de origem e destino são obrigatórios'
			));
			return;
		}

		$params = array(
			'cep_origem' => $cep_origem,
			'cep_destino' => $cep_destino,
		);

		$resultados = $this->correios->consulta_prazo($params);

		if ($resultados) {
			echo json_encode(array(
				'success' => true,
				'data' => $resultados
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Erro ao consultar prazo'
			));
		}
	}

	/**
	 * Rastrear encomenda via AJAX
	 */
	public function rastrear()
	{
		$this->output->set_content_type('application/json');

		$codigo_rastreio = $this->input->post('codigo_rastreio');

		if (!$codigo_rastreio) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Código de rastreio não informado'
			));
			return;
		}

		$resultado = $this->correios->rastrear_encomenda($codigo_rastreio);

		if ($resultado) {
			echo json_encode(array(
				'success' => true,
				'data' => $resultado
			));
		} else {
			echo json_encode(array(
				'success' => false,
				'message' => 'Código de rastreio não encontrado ou inválido'
			));
		}
	}

	/**
	 * Página de simulação de frete
	 */
	public function index()
	{
		$data = array(
			'titulo' => 'Simulador de Frete',
			'scripts' => array(
				'js/frete.js',
			),
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/frete/index');
		$this->load->view('restrita/layout/footer');
	}
}
