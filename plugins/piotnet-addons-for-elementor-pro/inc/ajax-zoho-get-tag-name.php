<?php
    require_once(__DIR__.'/helper/functions.php');
    add_action( 'wp_ajax_zoho_get_tag_name', 'zoho_get_tag_name' );
    add_action( 'wp_ajax_nopriv_zoho_get_tag_name', 'zoho_get_tag_name');
    
    function zoho_get_tag_name(){
        $zoho_api_domain = get_option('zoho_api_domain');
        $zoho_access_token = get_option('zoho_access_token');
        $module = $_REQUEST['module'];
        $helper = new PAFE_Helper();
        $request_url = $zoho_api_domain.'/crm/v2/settings/fields?module='.$module;
        $result = $helper->zohocrm_get_record($request_url, $zoho_access_token);
        $result = json_decode($result);
        if(!empty($result->code) && $result->code == 'INVALID_TOKEN'){
            $helper->zoho_refresh_token();
            return zoho_get_tag_name();
        }else{
            if(!empty($result) && empty($result->status)){
                $html = '';
                $result = $result->fields;
                foreach($result as $field){
                    $html .= '<div class="piotnet-zoho-field"><label>'.$field->field_label.'</label><div class="piotnet-zoho-field__value"><input type="text" value="'.$field->api_name.'" readonly></div></div>';
                }
                echo $html;
                wp_die();
            }else{
                echo "An error occurred";
                wp_die();
            }
        }
    }