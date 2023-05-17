<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packing_list_model extends App_Model
{
    protected $_table_name = 'tbl_project_packing_list';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';

    function __construct()
    {
        parent::__construct();
    }

    public function get($id, $where = [])
    {
        $this->db->where('id', $id);
        $this->db->where($where);
        $result = $this->db->get($this->_table_name)->row();
        return $result;
    }

    public function save($data, $id = NULL){

        if ($id === NULL) {
            $this->db->insert($this->_table_name,$data);
            $id = $this->db->insert_id();

            if($id){
                return $id;
            }
            
        }
      // Update
        else {
            $updated = false;
            $this->db->where('id', $id);
            $this->db->update($this->_table_name,$data);
            
            if($this->db->affected_rows() > 0){
                $updated = true;
            }
            
            if($updated){
                return true;
            }
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $deleted = $this->db->delete($this->_table_name);

        if ($deleted) {
            return true;
        }
        return false;
    }

    
}