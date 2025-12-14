<?php 
defined('BASEPATH') OR exit('Ação não permitida');

class Core_model extends CI_Model
{
	public function get_all($tabela = NULL, $condicoes = NULL)
	{
		if($tabela && $this->db->table_exists($tabela)) {

			if(is_array($condicoes)) {
				$this->db->where($condicoes);
			}

			return $this->db->get($tabela)->result();

		} else {
			return FALSE;
		}
	}

	public function get_by_id($tabela = NULL, $condicoes = NULL)
	{
		if($tabela && $this->db->table_exists($tabela) && is_array($condicoes)) {

			$this->db->where($condicoes);
			$this->db->limit(1);

			return $this->db->get($tabela)->row();
		} else {
			return FALSE;
		}
	}

	public function insert($tabela = NULL, $data = NULL, $get_last_id = NULL)
	{
		if($tabela && $this->db->table_exists($tabela) && is_array($data)) {

			$this->db->insert($tabela, $data);

			if($get_last_id){
				return $this->db->insert_id();
			}

			return $this->db->affected_rows() > 0;
		} else {
			return FALSE;
		}
	}

	public function update($tabela = NULL, $data = NULL, $condicoes = NULL)
	{
		if($tabela && $this->db->table_exists($tabela) && is_array($data) && is_array($condicoes)) {

			$this->db->where($condicoes);
			$update = $this->db->update($tabela, $data);

			if ($update) {
				return TRUE;
			}
			
			return FALSE;
		} else {
			return FALSE;
		}
	}

	public function delete($tabela = NULL, $condicoes = NULL)
	{
		if($tabela && $this->db->table_exists($tabela) && is_array($condicoes)) {

			$this->db->delete($tabela, $condicoes);

			return $this->db->affected_rows() > 0;
		} else {
			return FALSE;
		}
	}
}
