<?php

defined('BASEPATH') or exit('No direct script access allowed');

function project_packing_list_pdf($project, $tag = '')
{
    return app_pdf('invoice', FCPATH . 'modules/packing_list/libraries/pdf/Packing_list_pdf', $project, $tag);
}

if (!function_exists('packing_lists_format_packing_list_info')) {
    
    function packing_lists_format_packing_list_info($packing_list, $for = '')
    {
        $format = get_option('proposal_info_format');

        $countryCode = '';
        $countryName = '';

        if ($country = get_country($packing_list->country)) {
            $countryCode = $country->iso2;
            $countryName = $country->short_name;
        }

        $packing_listTo = '<b>' . $packing_list->primary_contact['firstname']. ' ' . $packing_list->primary_contact['lastname'] . '</b>';
        $phone      = $packing_list->phonenumber;
        $email      = $packing_list->primary_contact['email'];

        if ($for == 'admin') {
            $hrefAttrs = '';
            //$hrefAttrs = ' href="' . admin_url('clients/client/' . $packing_list->rel_id) . '" data-toggle="tooltip" data-title="' . _l('client') . '"';
            
            $packing_listTo = '<a' . $hrefAttrs . '>' . $packing_listTo . '</a>';
        }

        if ($for == 'html' || $for == 'admin') {
            $phone = '<a href="tel:' . $packing_list->phone . '">' . $packing_list->phone . '</a>';
            $email = '<a href="mailto:' . $packing_list->primary_contact['email'] . '">' . $packing_list->primary_contact['email'] . '</a>';
        }

        $format = _info_format_replace('proposal_to', $packing_listTo, $format);
        $format = _info_format_replace('address', $packing_list->address, $format);
        $format = _info_format_replace('city', $packing_list->city, $format);
        $format = _info_format_replace('state', $packing_list->state, $format);

        $format = _info_format_replace('country_code', $countryCode, $format);
        $format = _info_format_replace('country_name', $countryName, $format);

        $format = _info_format_replace('zip_code', $packing_list->zip, $format);
        $format = _info_format_replace('phone', $phone, $format);
        $format = _info_format_replace('email', $email, $format);

        $whereCF = [];
        if (is_custom_fields_for_customers_portal()) {
            $whereCF['show_on_client_portal'] = 1;
        }
        
        $format = _maybe_remove_first_and_last_br_tag($format);

        // Remove multiple white spaces
        $format = preg_replace('/\s+/', ' ', $format);
        $format = trim($format);


        return hooks()->apply_filters('packing_list_info_text', $format, ['packing_list' => $packing_list, 'for' => $for]);
    }
}