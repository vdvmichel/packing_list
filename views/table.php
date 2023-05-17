<?php
defined('BASEPATH') or exit('No direct script access allowed');

if(is_admin()){
    $aColumns = [
    db_prefix().'_project_packing_list.description as description',
    db_prefix().'_project_packing_list.long_description as long_description',
    db_prefix().'_project_packing_list.qty as qty',
    db_prefix().'_project_packing_list.rate as rate',
    
];
}else{
    $aColumns = [
    db_prefix().'_project_packing_list.description as description',
    db_prefix().'_project_packing_list.long_description as long_description',
    db_prefix().'_project_packing_list.qty as qty'
    
];
}



$sIndexColumn = 'id';
$sTable = db_prefix() . '_project_packing_list';
$CI = &get_instance();
$where = array();

if(isset($_GET['project_id']) && !empty($_GET['project_id'])){
    array_push($where,'AND project_id = '.$_GET['project_id']);
}
$join = array();
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(db_prefix().'_project_packing_list.id as id'));
$output = $result['output'];
$rResult = $result['rResult'];
$footer_data = ['total' => 0];
$currency = get_base_currency();
foreach ($rResult as $aRow) {

    $row = [];
    $name = $aRow['description'];
    $name .= '<br/><a href="#" class="packing_list_edit" data-id="'.$aRow['id'].'">'. _l('edit').'</a>';
    $name .= ' | <a href="#" class="text-danger packing_list_delete"  data-id="'.$aRow['id'].'">' . _l('delete') . '</a>';
    $row[] = $name;
    $row[] = $aRow['long_description'];
    $row[] = $aRow['qty'];

    if(is_admin()){
        $total = 0;
        $total = $aRow['rate'] * $aRow['qty'];
        $row[] = app_format_money($aRow['rate'], get_base_currency());

        $row[] = app_format_money($aRow['rate'] * $aRow['qty'], get_base_currency());

        $options = "";
        $options .= icon_btn('#', 'pencil-square-o','btn-primary packing_list_edit',['data-id' => $aRow['id']]);
        $options .= icon_btn('#', 'remove', 'btn-danger packing_list_delete',['data-id' => $aRow['id']]);
        
        //$row[] = $options;
        $footer_data['total'] += $total;
    }


    



    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}

foreach ($footer_data as $key => $total) {
    
    $footer_data[$key] = $total;
   
    
}

$footer_data['total'] = app_format_money($footer_data['total'],get_base_currency());

$output['sums'] = $footer_data;

