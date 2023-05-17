<?php

defined('BASEPATH') or exit('No direct script access allowed');
$dimensions = $pdf->getPageDimensions();

$pdf_logo_url = pdf_logo_url();
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'L', true);

$pdf->ln(4);
// Get Y position for the separation
$y = $pdf->getY();

$packing_list_info = '<div style="color:#424242;">';
    $packing_list_info .= format_organization_info();
$packing_list_info .= '</div>';

$pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $packing_list_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

$rowcount = max([$pdf->getNumLines($packing_list_info, 80)]);

$CI = &get_instance();
$project_client_details = $CI->db->where('userid' , $project->clientid)->get(db_prefix().'clients')->row();
$project_client_details->primary_contact = $CI->db->where('userid',$project_client_details->userid)->where('is_primary',1)->get(db_prefix().'contacts')->row_array();
$client_details = '<b>' . _l('proposal_to') . '</b>';
$client_details .= '<div style="color:#424242;">';
    $client_details .= packing_lists_format_packing_list_info($project_client_details, 'pdf');
$client_details .= '</div>';

$pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount * 7, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

$pdf->ln(6);


// The items table
// $items = packing_list_get_items_table_data($project, 'project_packing_list', 'pdf')
//         ->set_headings('project_packing_list');

//$items_html = $items->table();
$packing_list_items = $CI->db->where('project_id',$project->id)->get(db_prefix().'_project_packing_list')->result_array();
$items_html = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">
<thead>
<tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
<th width="50%" align="left">' . _l('estimate_table_item_heading', '', false) . '</th>
<th width="50%" align="left">' . _l('estimate_table_quantity_heading', '', false) . '</th>
</tr>
</thead>';
$items_html .= '<tbody>';

foreach($packing_list_items as $item){ 
    $items_html .= '<tr><td>'.$item['description'].'</td>';
    $items_html .= '<td>'.$item['qty'].'</td></tr>';
}

$items_html .= '</tbody></table>';

$items_html .= '<br /><br />';
$items_html .= '';
// $items_html .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';

// $items_html .= '
// <tr>
//     <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
//     <td align="right" width="15%"></td>
// </tr>';


$html = <<<EOF
<br />
<div style="width:675px !important;">
$items_html
</div>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');
