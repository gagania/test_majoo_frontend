<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->helper('url');
//        $this->load->model(array('content_model','Contact_model','Banner_model',
//            'Plan_model','Switch_model','Port_model'));
    }

    function _remap() {
        if (!$this->session->userdata('user_name')) {
            redirect('/login');
        }
        $segment_1 = $this->uri->segment(1);
        $segment_2 = $this->uri->segment(2);
        if($segment_2 == '' && !strpos($segment_1,'.html')){ 
            $this->data['title'] = 'HOME';        
            $merchantData = $this->merchant_api();
            $this->data['merchant'] = $merchantData['data']['merchant'];
            $this->data['totaldata'] = sizeof($merchantData['data']['merchant']);
            $this->data['pnumber'] = 1;
            $this->data['limit'] = $this->_limit;
            $this->data['content'] = 'main/merchant';
            $this->load->view('layout_frontend_home', $this->data);
        } else if ($segment_2 == 'merchant') {
            $merchantData = $this->merchant_api();
            $this->data['merchant'] = $merchantData['data']['merchant'];
            $this->data['content'] = 'main/merchant';
            $this->data['totaldata'] = 1;
            $this->data['pnumber'] = 1;
            $this->data['limit'] = $this->_limit;
            $this->load->view('layout_frontend_home', $this->data);
        } else if ($segment_2 == 'outlet') {
            $merchantData = $this->merchant_api();
            $outletData = $this->outlet_all_api();
            $this->data['merchant'] = $merchantData['data']['merchant'];
            $this->data['outlet'] = $outletData['data']['outlets'];
            $this->data['content'] = 'main/outlet';
            $this->data['totaldata'] = 1;
            $this->data['pnumber'] = 1;
            $this->data['limit'] = $this->_limit;
            $this->load->view('layout_frontend_home', $this->data);
        } else if ($segment_2 == 'merch_report') {
            $merchantData = $this->merchant_api();
            $this->data['merchant'] = $merchantData['data']['merchant'];
            $this->data['content'] = 'main/merchant_report';
            $this->data['totaldata'] = 1;
            $this->data['pnumber'] = 1;
            $this->data['limit'] = $this->_limit;
            $this->load->view('layout_frontend_home', $this->data);
        } else if ($segment_2 == 'outlet_report') {
            $merchantData = $this->merchant_api();
            $this->data['merchant'] = $merchantData['data']['merchant'];
            $this->data['content'] = 'main/outlet_report';
            $this->data['totaldata'] = 1;
            $this->data['pnumber'] = 1;
            $this->data['limit'] = $this->_limit;
            $this->load->view('layout_frontend_home', $this->data);
        } else if ($segment_2 == 'paging') {
            $this->paging();
        } else if ($segment_2 == 'paging_outlet_all') {
            $this->paging_outlet_all();
        } else if ($segment_2 == 'paging_outlet') {
            $this->paging_outlet();
        } else if ($segment_2 == 'merchant_outlets') {
            $outlets = $this->outlet_api();
            $temp = '';
            $result = array();
            $result['success'] = false;
            if(!$outlets['error']) {
                foreach($outlets['data']['outlets'] as $index => $value) {
                    $temp .= '<option value="'.$value['id'].'">'.$value['outlet_name'].'</option>';
                }
                $result['success'] = true;
                $result['template'] = $temp;
            }
            
            echo json_encode($result);exit;
        } 
    }
    
    function paging() {
        $whereSearch = array();
        $pnumber = ($this->input->post('pnum')) ? $this->input->post('pnum') : 0;
        $paging = strtolower($this->input->post('page'));
        $limit = $this->input->post('limit');
        $totaldata = $this->input->post('totaldata');
        $merchantId = $this->input->post('merchant_id');
        $dateFrom = $this->input->post('date_from');
        $dateTo = $this->input->post('date_to');
        $page = $limit;
        
        if ($paging && $paging != 'page') {
            if ($paging == 'first') {
                $limit = 0;
                $page = $this->_limit;
                $pnumber = 1;
            } else if ($paging == 'last') {
                $pnumber = ceil($totaldata/$this->_limit);
                $limit = (floor($totaldata/$this->_limit))* $this->_limit;
                $this->_limit = $totaldata-$limit;
            } else if ($paging == 'next') {
                $page += 10;
                $limit = $page;
                $pnumber = $pnumber + 1;
            } else if ($paging == 'prev') {
                if ($limit > 0) {
                    $page -= 10;
                }
                $limit = $page;
                if ($pnumber > 1) {
                    $pnumber = $pnumber - 1;
                }
            } else {
                $limit = $totaldata;
                if ($pnumber > 1) {
                    $pnumber = 1;
                }
            }
        } else if ($paging == 'page') {
            if ($pnumber > 0) {
                $limit = (($this->_limit * $pnumber) - $this->_limit);
            } else if ($pnumber == 0) {
                $limit = 0;
            }
        }

        $merchantData = $this->merchant_omzet($this->session->userdata('user_id'),$merchantId,$dateFrom,$dateTo);
        $totalData = sizeof($merchantData['data']['merchant']);
        $result = $merchantData['data']['merchant'];
        $newData = '';
        if ($result) {
            $newData .= $this->searchTemplate($result);
        }
        
        $jsonData['result'] = true;
        $jsonData['pnumber'] = $pnumber;
        $jsonData['limit'] = isset($limit) ? $limit :0;
        $jsonData['totaldata'] = $totalData;
        $jsonData['template'] = $newData;
        echo json_encode($jsonData, true);
    }

    function searchTemplate($data) {
        $template = '';
        foreach ($data as $index => $value) {
            $template .= '<tr class="odd gradeX">
                                <td>' . $value['merchant_name'] . '</td>
                                <td>' . date('d/m/Y', strtotime($value['date'])) . '</td>
                                <td>' . $value['omzet'] . '</td>
                            </tr>';
        }
        return $template;
    }
    
    function paging_outlet_all() {
        $whereSearch = array();
        $pnumber = ($this->input->post('pnum')) ? $this->input->post('pnum') : 0;
        $paging = strtolower($this->input->post('page'));
        $limit = $this->input->post('limit');
        $totaldata = $this->input->post('totaldata');
        $merchantId = $this->input->post('merchant_id');
        $outletId = $this->input->post('outlet_id');
        $page = $limit;
        
        if ($paging && $paging != 'page') {
            if ($paging == 'first') {
                $limit = 0;
                $page = $this->_limit;
                $pnumber = 1;
            } else if ($paging == 'last') {
                $pnumber = ceil($totaldata/$this->_limit);
                $limit = (floor($totaldata/$this->_limit))* $this->_limit;
                $this->_limit = $totaldata-$limit;
            } else if ($paging == 'next') {
                $page += 10;
                $limit = $page;
                $pnumber = $pnumber + 1;
            } else if ($paging == 'prev') {
                if ($limit > 0) {
                    $page -= 10;
                }
                $limit = $page;
                if ($pnumber > 1) {
                    $pnumber = $pnumber - 1;
                }
            } else {
                $limit = $totaldata;
                if ($pnumber > 1) {
                    $pnumber = 1;
                }
            }
        } else if ($paging == 'page') {
            if ($pnumber > 0) {
                $limit = (($this->_limit * $pnumber) - $this->_limit);
            } else if ($pnumber == 0) {
                $limit = 0;
            }
        }

        $outletData = $this->outlet_all($merchantId,$outletId);
        $totalData = sizeof($outletData['data']['outlets']);
        $result = $outletData['data']['outlets'];
        $newData = '';
        if ($result) {
            $newData .= $this->searchTemplateOutletAll($result);
        }
        
        $jsonData['result'] = true;
        $jsonData['pnumber'] = $pnumber;
        $jsonData['limit'] = isset($limit) ? $limit :0;
        $jsonData['totaldata'] = $totalData;
        $jsonData['template'] = $newData;
        echo json_encode($jsonData, true);
    }

    function searchTemplateOutletAll($data) {
        $template = '';
        foreach ($data as $index => $value) {
            $template .= '<tr class="odd gradeX">
                                <td>' . $value['merchant_name'] . '</td>
                                <td>' . $value['outlet_name'] . '</td>
                            </tr>';
        }
        return $template;
    }
    function paging_outlet() {
        $whereSearch = array();
        $pnumber = ($this->input->post('pnum')) ? $this->input->post('pnum') : 0;
        $paging = strtolower($this->input->post('page'));
        $limit = $this->input->post('limit');
        $totaldata = $this->input->post('totaldata');
        $merchantId = $this->input->post('merchant_id');
        $outletId = $this->input->post('outlet_id');
        $dateFrom = $this->input->post('date_from');
        $dateTo = $this->input->post('date_to');
        $page = $limit;
        
        if ($paging && $paging != 'page') {
            if ($paging == 'first') {
                $limit = 0;
                $page = $this->_limit;
                $pnumber = 1;
            } else if ($paging == 'last') {
                $pnumber = ceil($totaldata/$this->_limit);
                $limit = (floor($totaldata/$this->_limit))* $this->_limit;
                $this->_limit = $totaldata-$limit;
            } else if ($paging == 'next') {
                $page += 10;
                $limit = $page;
                $pnumber = $pnumber + 1;
            } else if ($paging == 'prev') {
                if ($limit > 0) {
                    $page -= 10;
                }
                $limit = $page;
                if ($pnumber > 1) {
                    $pnumber = $pnumber - 1;
                }
            } else {
                $limit = $totaldata;
                if ($pnumber > 1) {
                    $pnumber = 1;
                }
            }
        } else if ($paging == 'page') {
            if ($pnumber > 0) {
                $limit = (($this->_limit * $pnumber) - $this->_limit);
            } else if ($pnumber == 0) {
                $limit = 0;
            }
        }

        $merchantData = $this->outlet_omzet($this->session->userdata('user_id'),$merchantId,$dateFrom,$dateTo,$outletId);
        $totalData = sizeof($merchantData['data']['outlet']);
        $result = $merchantData['data']['outlet'];
        $newData = '';
        if ($result) {
            $newData .= $this->searchTemplateOutlet($result);
        }
        
        $jsonData['result'] = true;
        $jsonData['pnumber'] = $pnumber;
        $jsonData['limit'] = isset($limit) ? $limit :0;
        $jsonData['totaldata'] = $totalData;
        $jsonData['template'] = $newData;
        echo json_encode($jsonData, true);
    }

    function searchTemplateOutlet($data) {
        $template = '';
        foreach ($data as $index => $value) {
            $template .= '<tr class="odd gradeX">
                                <td>' . $value['merchant_name'] . '</td>
                                <td>' . $value['outlet_name'] . '</td>
                                <td>' . date('d/m/Y', strtotime($value['date'])) . '</td>
                                <td>' . $value['omzet'] . '</td>
                            </tr>';
        }
        return $template;
    }
    
    function merchant_api($id='') {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/merchants";
        $ch = curl_init($url);
        $data_string = '';
         if ($this->session->userdata('user_id') != '') {
            $data = array('user_id'=>$this->session->userdata('user_id'));
            $data_string = http_build_query($data);
        }                  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response,true);
            if(isset($result['error']) && $result['error'] != '') {
                redirect('/login/logout');
            }
            return json_decode($response,true);
        }
    }

    function outlet_api() {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/outlets";
        $ch = curl_init($url);
        $data_string = '';
         if ($this->session->userdata('user_id') != '') {
            $data = array('merchant_id'=>$this->input->post('merchantid'));
            $data_string = http_build_query($data);
        }                  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response,true);
        }
    }
    function outlet_all_api() {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/outlets";
        $ch = curl_init($url);
        $data_string = '';
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response,true);
        }
    }
    
    function merchant_omzet($userId,$merchantId,$dateFrom,$dateTo) {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/merchant/omzet";
        $ch = curl_init($url);
        $data_string = '';
         if ($merchantId != '') {
            $data['user_id'] = $userId;
            $data['merchant_id'] = $merchantId;
            $data['date_from'] = date('Y-m-d',strtotime(str_replace('/','-',$dateFrom)));
            if ($dateTo == '') {
                $data['date_to'] = $data['date_from'];
            } else {
                $data['date_to'] = date('Y-m-d',strtotime(str_replace('/','-',$dateTo)));
            }
            $data_string = http_build_query($data);
        }          
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response,true);
            if(isset($result['error']) && $result['error'] != '') {
                redirect('/login/logout');
            }
            return json_decode($response,true);
        }
    }
    
    function outlet_all($merchantId,$outletId) {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/outlets/all";
        $ch = curl_init($url);
        $data_string = '';
         if ($outletId != '') {
            $data['merchant_id'] = $merchantId;
            $data['outlet_id'] = $outletId;
            $data_string = http_build_query($data);
        }          
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response,true);
        }
    }
    
    function outlet_omzet($userId,$merchantId,$dateFrom,$dateTo,$outletId) {
        if (!$this->session->userdata('token')) { 
            redirect('/');
        }
        $url = "http://localhost/majooapi/outlet/omzet";
        $ch = curl_init($url);
        $data_string = '';
         if ($outletId != '') {
            $data['user_id'] = $userId;
            $data['merchant_id'] = $merchantId;
            $data['outlet_id'] = $outletId;
            $data['date_from'] = date('Y-m-d',strtotime(str_replace('/','-',$dateFrom)));
            if ($dateTo == '') {
                $data['date_to'] = $data['date_from'];
            } else {
                $data['date_to'] = date('Y-m-d',strtotime(str_replace('/','-',$dateTo)));
            }
            $data_string = http_build_query($data);
        }          
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$this->session->userdata('token').'')
        );

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response,true);
            return json_decode($response,true);
        }
    }
}
