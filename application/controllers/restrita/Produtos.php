<?php

defined('BASEPATH') or exit('Ação não permitida');


class Produtos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		// Verificar se está logado
		if (!$this->ion_auth->logged_in()) {
			redirect('login');
		}

		$this->load->model('core_model');
	}

	public function index()
	{
		$data = array(
			'produtos' => $this->core_model->get_all('produtos'),
			'titulo' => 'Produtos cadastrados',
			'styles' => array(
				'bundles/datatables/datatables.min.css',
				'bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'bundles/datatables/datatables.min.js',
				'bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js',
				'bundles/jquery-ui/jquery-ui.min.js',
				'js/page/datatables.js',
				'js/produtos.js',
			)
		);

		$this->load->view('restrita/layout/header', $data);
		$this->load->view('restrita/produtos/index');
		$this->load->view('restrita/layout/footer');
	}

	public function core($produto_id = NULL)
	{
		// Buscar dados para os selects
		$marcas = $this->core_model->get_all('marcas', array('marca_ativa' => 1));
		$categorias = $this->core_model->get_all('categorias', array('categoria_ativa' => 1));
		
		if (!$produto_id) {
			// Cadastrar novo produto
			$this->form_validation->set_rules('produto_codigo', 'Código do produto', 'trim|required|min_length[2]|max_length[45]|callback_valida_codigo');
			$this->form_validation->set_rules('produto_nome', 'Nome do produto', 'trim|required|min_length[2]|max_length[255]|callback_valida_produto');
			$this->form_validation->set_rules('produto_meta_link', 'Meta link do produto', 'trim|required|min_length[2]|max_length[255]');
			$this->form_validation->set_rules('produto_categoria_id', 'Categoria', 'trim|required|integer');
			$this->form_validation->set_rules('produto_marca_id', 'Marca', 'trim|required|integer');
			$this->form_validation->set_rules('produto_peso', 'Peso', 'trim|integer');
			$this->form_validation->set_rules('produto_altura', 'Altura', 'trim|integer');
			$this->form_validation->set_rules('produto_largura', 'Largura', 'trim|integer');
			$this->form_validation->set_rules('produto_comprimento', 'Comprimento', 'trim|integer');
			$this->form_validation->set_rules('produto_valor', 'Valor', 'trim|required');
			$this->form_validation->set_rules('produto_destaque', 'Produto destaque', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_controlar_estoque', 'Controlar estoque', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_quantidade_estoque', 'Quantidade em estoque', 'trim|integer');
			$this->form_validation->set_rules('produto_ativo', 'Situação do produto', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_resumo', 'Resumo do produto', 'trim|required|min_length[10]');
			$this->form_validation->set_rules('produto_descricao', 'Descrição do produto', 'trim|min_length[10]');

			if ($this->form_validation->run()) {
				$data = elements(
					array(
						'produto_codigo',
						'produto_nome',
						'produto_meta_link',
						'produto_categoria_id',
						'produto_marca_id',
						'produto_peso',
						'produto_altura',
						'produto_largura',
						'produto_comprimento',
						'produto_valor',
						'produto_destaque',
						'produto_controlar_estoque',
						'produto_quantidade_estoque',
						'produto_ativo',
						'produto_resumo',
						'produto_descricao'
					),
					$this->input->post()
				);

				$data = html_escape($data);

				$produto_id = $this->core_model->insert('produtos', $data, TRUE);
				
				if ($produto_id) {
					// Processar upload de imagens
					$this->do_upload($produto_id);
					$this->session->set_flashdata('sucesso', 'Produto cadastrado com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao cadastrar o produto');
				}

				redirect('restrita/produtos');
			} else {
				$data = array(
					'titulo' => 'Cadastrar produto',
					'marcas' => $marcas,
					'categorias' => $categorias,
					'scripts' => array(
						'js/produtos_form.js',
					),
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/produtos/core');
				$this->load->view('restrita/layout/footer');
			}
		} else {
			// Editar produto existente
			$produto = $this->core_model->get_by_id('produtos', array('produto_id' => $produto_id));

			if (!$produto) {
				$this->session->set_flashdata('error', 'Produto não encontrado');
				redirect('restrita/produtos');
			}

			$this->form_validation->set_rules('produto_codigo', 'Código do produto', 'trim|required|min_length[2]|max_length[45]|callback_valida_codigo');
			$this->form_validation->set_rules('produto_nome', 'Nome do produto', 'trim|required|min_length[2]|max_length[255]|callback_valida_produto');
			$this->form_validation->set_rules('produto_meta_link', 'Meta link do produto', 'trim|required|min_length[2]|max_length[255]');
			$this->form_validation->set_rules('produto_categoria_id', 'Categoria', 'trim|required|integer');
			$this->form_validation->set_rules('produto_marca_id', 'Marca', 'trim|required|integer');
			$this->form_validation->set_rules('produto_peso', 'Peso', 'trim|integer');
			$this->form_validation->set_rules('produto_altura', 'Altura', 'trim|integer');
			$this->form_validation->set_rules('produto_largura', 'Largura', 'trim|integer');
			$this->form_validation->set_rules('produto_comprimento', 'Comprimento', 'trim|integer');
			$this->form_validation->set_rules('produto_valor', 'Valor', 'trim|required');
			$this->form_validation->set_rules('produto_destaque', 'Produto destaque', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_controlar_estoque', 'Controlar estoque', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_quantidade_estoque', 'Quantidade em estoque', 'trim|integer');
			$this->form_validation->set_rules('produto_ativo', 'Situação do produto', 'trim|required|in_list[0,1]');
			$this->form_validation->set_rules('produto_resumo', 'Resumo do produto', 'trim|required|min_length[10]');
			$this->form_validation->set_rules('produto_descricao', 'Descrição do produto', 'trim|min_length[10]');

			if ($this->form_validation->run()) {
				$data = elements(
					array(
						'produto_codigo',
						'produto_nome',
						'produto_meta_link',
						'produto_categoria_id',
						'produto_marca_id',
						'produto_peso',
						'produto_altura',
						'produto_largura',
						'produto_comprimento',
						'produto_valor',
						'produto_destaque',
						'produto_controlar_estoque',
						'produto_quantidade_estoque',
						'produto_ativo',
						'produto_resumo',
						'produto_descricao'
					),
					$this->input->post()
				);

				$data = html_escape($data);

				if ($this->core_model->update('produtos', $data, array('produto_id' => $produto_id))) {
					// Processar upload de novas imagens
					$this->do_upload($produto_id);
					$this->session->set_flashdata('sucesso', 'Produto atualizado com sucesso');
				} else {
					$this->session->set_flashdata('error', 'Erro ao atualizar o produto');
				}

				// Buscar imagens do produto
				$imagens = $this->core_model->get_all('produtos_imagens', array('produto_id' => $produto_id));
				
				$data = array(
					'titulo' => 'Editar produto',
					'produto' => $produto,
					'imagens' => $imagens,
					'marcas' => $marcas,
					'categorias' => $categorias,
					'scripts' => array(
						'js/produtos_form.js',
					),
				);

				$this->load->view('restrita/layout/header', $data);
				$this->load->view('restrita/produtos/core');
				$this->load->view('restrita/layout/footer');
			}
		}
	}

	// Método para fazer upload de imagens
	private function do_upload($produto_id)
	{
		if (isset($_FILES['produto_imagens']) && !empty($_FILES['produto_imagens']['name'][0])) {
			$config['upload_path'] = './public/uploads/produtos/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
			$config['max_size'] = 2048; // 2MB

			$this->load->library('upload', $config);

			$files = $_FILES['produto_imagens'];
			$filesCount = count($files['name']);

			for ($i = 0; $i < $filesCount; $i++) {
				if (!empty($files['name'][$i])) {
					$_FILES['userfile']['name'] = $files['name'][$i];
					$_FILES['userfile']['type'] = $files['type'][$i];
					$_FILES['userfile']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['userfile']['error'] = $files['error'][$i];
					$_FILES['userfile']['size'] = $files['size'][$i];

					// Gerar nome único para o arquivo
					$ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
					$new_name = 'produto_' . $produto_id . '_' . time() . '_' . $i . '.' . $ext;
					$config['file_name'] = $new_name;

					$this->upload->initialize($config);

					if ($this->upload->do_upload('userfile')) {
						$uploadData = $this->upload->data();
						
						// Verificar se é a primeira imagem para definir como principal
						$imagens_existentes = $this->core_model->get_all('produtos_imagens', array('produto_id' => $produto_id));
						$principal = (empty($imagens_existentes) && $i == 0) ? 1 : 0;

						// Salvar no banco de dados
						$data_imagem = array(
							'produto_id' => $produto_id,
							'produto_imagem_nome' => $uploadData['file_name'],
							'produto_imagem_principal' => $principal
						);

						$this->core_model->insert('produtos_imagens', $data_imagem);
					}
				}
			}
		}
	}

	// Método para excluir imagem
	public function excluir_imagem($imagem_id = NULL)
	{
		if (!$imagem_id) {
			$this->session->set_flashdata('error', 'Imagem não encontrada');
			redirect($_SERVER['HTTP_REFERER']);
		}

		$imagem = $this->core_model->get_by_id('produtos_imagens', array('produto_imagem_id' => $imagem_id));

		if (!$imagem) {
			$this->session->set_flashdata('error', 'Imagem não encontrada');
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Excluir arquivo físico
		$file_path = './public/uploads/produtos/' . $imagem->produto_imagem_nome;
		if (file_exists($file_path)) {
			unlink($file_path);
		}

		// Excluir do banco de dados
		if ($this->core_model->delete('produtos_imagens', array('produto_imagem_id' => $imagem_id))) {
			$this->session->set_flashdata('sucesso', 'Imagem excluída com sucesso');
		} else {
			$this->session->set_flashdata('error', 'Erro ao excluir a imagem');
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	// Método para definir imagem como principal
	public function imagem_principal($imagem_id = NULL)
	{
		if (!$imagem_id) {
			$this->session->set_flashdata('error', 'Imagem não encontrada');
			redirect($_SERVER['HTTP_REFERER']);
		}

		$imagem = $this->core_model->get_by_id('produtos_imagens', array('produto_imagem_id' => $imagem_id));

		if (!$imagem) {
			$this->session->set_flashdata('error', 'Imagem não encontrada');
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Remover principal de todas as imagens do produto
		$this->db->where('produto_id', $imagem->produto_id);
		$this->db->update('produtos_imagens', array('produto_imagem_principal' => 0));

		// Definir esta como principal
		if ($this->core_model->update('produtos_imagens', array('produto_imagem_principal' => 1), array('produto_imagem_id' => $imagem_id))) {
			$this->session->set_flashdata('sucesso', 'Imagem definida como principal');
		} else {
			$this->session->set_flashdata('error', 'Erro ao definir imagem como principal');
		}

		redirect($_SERVER['HTTP_REFERER']);
	}

	public function excluir($produto_id = NULL)
	{
		if (!$produto_id || !$this->core_model->get_by_id('produtos', array('produto_id' => $produto_id))) {
			$this->session->set_flashdata('error', 'Produto não encontrado');
			redirect('restrita/produtos');
		}

		if ($this->core_model->delete('produtos', array('produto_id' => $produto_id))) {
			$this->session->set_flashdata('sucesso', 'Produto excluído com sucesso');
		} else {
			$this->session->set_flashdata('error', 'Erro ao excluir o produto');
		}

		redirect('restrita/produtos');
	}

	// Validação de código único
	public function valida_codigo($produto_codigo)
	{
		$produto_id = $this->input->post('produto_id');

		if ($produto_id) {
			// Editando - verificar se o código já existe em outro registro
			$this->db->where('produto_codigo', $produto_codigo);
			$this->db->where('produto_id !=', $produto_id);
			$produto = $this->db->get('produtos')->row();
		} else {
			// Cadastrando - verificar se o código já existe
			$produto = $this->core_model->get_by_id('produtos', array('produto_codigo' => $produto_codigo));
		}

		if ($produto) {
			$this->form_validation->set_message('valida_codigo', 'Esse código de produto já existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// Validação de nome único
	public function valida_produto($produto_nome)
	{
		$produto_id = $this->input->post('produto_id');

		if ($produto_id) {
			// Editando - verificar se o nome já existe em outro registro
			$this->db->where('produto_nome', $produto_nome);
			$this->db->where('produto_id !=', $produto_id);
			$produto = $this->db->get('produtos')->row();
		} else {
			// Cadastrando - verificar se o nome já existe
			$produto = $this->core_model->get_by_id('produtos', array('produto_nome' => $produto_nome));
		}

		if ($produto) {
			$this->form_validation->set_message('valida_produto', 'Esse produto já existe');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
