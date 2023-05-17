<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH.'libraries/pdf/App_pdf.php');

class Packing_list_pdf extends App_pdf
{
    protected $project;


    public function __construct($project, $tag = '')
    {
        $this->load_language($project->clientid);

        parent::__construct();

        if (!class_exists('Projects_model', false)) {
            $this->ci->load->model('projects_model');
        }

        $this->tag            = $tag;
        $this->project        = $project;

        $this->SetTitle($project->id);
    }

    public function prepare()
    {
        $this->with_number_to_word($this->project->clientid);

        $this->set_view_vars([
            'status'         => '',
            'invoice_number' => '',
            'payment_modes'  => '',
            'project'        => $this->project,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'packing_list';
    }

    protected function file_path()
    {
        $customPath = FCPATH . 'modules/packing_list/views/pdf.php';

        return $customPath;
    }

}
