<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_list extends AdminController
{
    private $_module = 'packing_list';

    public function __construct(){
        parent::__construct();
        $this->load->model($this->_module.'_model','module_model');
    }

    public function table()
    {
        $this->app->get_table_data(module_views_path($this->_module,'table'));
    }

    public function get_item_data(){
        $item_id = $this->input->post('item_id');
        $this->load->model('invoice_items_model');
        $item_data = $this->invoice_items_model->get($item_id);

        echo json_encode($item_data);
        exit();

    }

    public function create()
    {
        if(!is_admin()){
            ajax_access_denied();
        }
        $data = $this->input->post();
        $id = $data['id'];

        //add new record
        if ($id == '') {
            unset($data['id']);
            $id = $this->module_model->save($data);
            if ($id) {
                $message_status = 'success';
                $message = _l('added_successfully');
            }else{
                $message_status = 'danger';
                $message = _l('problem_in_adding');
            }
        //update existing record
        } else {
            $updated = $this->module_model->save($data, $id);
            $message_status = 'success';
            $message = _l('updated_successfully');

        } 
        set_alert($message_status,$message);

        exit();
        
    }


    public function delete($id = '')
    {   
        if(!is_admin()){
            ajax_access_denied();
        }
        
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $response = $this->module_model->delete($id);
        if ($response == true) {
            $message_status = 'success';
            $message = _l('deleted_successfully');
        } else {
            $message_status = 'danger';
            $message = _l('problem_in_deleting');
        }
        set_alert($message_status,$message);
        exit();
        
    }
    public function get_list_data(){
        $list_id = $this->input->post('list_id');
        $list_data = $this->module_model->get($list_id);

        echo json_encode($list_data);
        exit();

    }

    public function pdf($project_id)
    {
        if (!$project_id) {
            redirect(admin_url());
        }

        $project = $this->projects_model->get($project_id);

        try {
            $pdf = project_packing_list_pdf($project);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'I';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(mb_strtoupper('packing_list_project_'.$project_id) . '.pdf', $type);
    }
}