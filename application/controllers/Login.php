<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->helper('url');
    }

    function _remap() {
        $segment_1 = $this->uri->segment(1);
        $segment_2 = $this->uri->segment(2);
            
        $this->data['title'] = 'LOGIN';
        $this->data['menu_active'] = 'Home';
        $this->data['content'] = 'login/index';
        if ($segment_2 == '' && !strpos($segment_1, '.html')) {
            
        } else if ($segment_2 == 'test') {
            $this->data['content'] = 'login/test';
        } else if ($segment_2 == 'callback') {
            $this->data['content'] = 'login/callback';
        } else if ($segment_2 == 'auth') {
            $this->login();
        } else if ($segment_2 == 'logout') {
            $this->logout();
        } else if ($segment_2 == 'save') {
            $data = $this->input->post();
            if ($_FILES) {
                $ext = pathinfo($_FILES['user_image_path']['name'], PATHINFO_EXTENSION);
                if ($ext != '' && ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')) {
                    $remote_file = '/domains/qrlab.id/public_html/auth/uploaded_files/' . $_FILES['user_image_path']['name'];
                    /* FTP Account (Remote Server) */
                    $ftp_host = '151.106.117.38'; /* host */
                    $ftp_user_name = 'u597445917'; /* username */
                    $ftp_user_pass = 'K*5m#2KK'; /* password */
                    /* File and path to send to remote FTP server */
                    $local_file = $_FILES['user_image_path']['tmp_name'];
                    /* Connect using basic FTP */
                    $connect_it = ftp_connect($ftp_host);
                    /* Login to FTP */
                    $login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);
                    if ($login_result) {
                        /* Send $local_file to FTP */
                        if (ftp_put($connect_it, $remote_file, $local_file, FTP_BINARY)) {
                            $data['user_image_path'] = 'uploaded_files/' . $_FILES['user_image_path']['name'];
                        } else {
                            echo "Upload file error";
                        }
                    }
                    /* Close the connection */
                    ftp_close($connect_it);
                }
            }
            
            $result = $this->save($data);
            if (isset($result['error'])) {
                $this->session->set_flashdata('message', 'e-Mail sudah terdaftar silahkan masukkan e-Mail lain.');
                redirect('login/register');
            }
            if ($result) {
                $data['id'] = $result['id'];
            }
//            $this->sendEmail($data);
            if ($result) {
                $this->session->set_flashdata('message', 'Registrasi berhasil, silahkan cek email anda untuk verifikasi.');
                redirect('/login');
            }
            
        } else if ($segment_2 == 'register') {
            $this->data['gender'] = $this->gender;
            $this->data['content'] = 'login/register';
        } else if ($segment_2 == 'verification') {
            $this->data['content'] = 'login/verify';
            $this->update_user_api($this->uri->segment(3));
        } else if ($segment_2 == 'changepass') {
            $this->changepass_api($this->input->post('user_email'));
            $this->session->set_flashdata('message', 'Silahkan cek email anda untuk mengganti password.');
            redirect('login/');
        }else if ($segment_2 == 'confirmpass') {
            $userToken = $this->uri->segment(3);
            //checkuser exist
            if ($userToken == '') {
                redirect('/login');
            }
            $userData = $this->checkuser_api($userToken);
            if (isset($userData['error'])) {
                redirect('/login');
            }
            $this->data['id'] = $userData[0]['id'];
            $this->data['content'] = 'login/confirm';
//            $this->update_user_api($this->uri->segment(3));
        } else if ($segment_2 == 'change_pass') {
            $id = $this->input->post('id');
            if ($id != '') {
                $newPass = $this->input->post('user_pass');
                $this->update_pass_api($id,$newPass);
                $this->session->set_flashdata('message', 'Password berhasil di ganti.');
                $this->session->sess_destroy();
            }
        } else if ($segment_2 == 'forgotpass') {
            $this->data['content'] = 'login/forgot';
        }
        $this->load->view('layout_frontend_login', $this->data);
    }

    function update_user_api($id) {
        $token = $this->getToken();
        if (isset($token['error'])) {
            redirect('/');
        }
        $url = "https://api.qrlab.id/user/updateStatus";
        $ch = curl_init($url);

        $data = array('id'=>$id,'is_verified'=>'Y');
        $data_string = http_build_query($data);                                                                                 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token['access_token'].'')
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
    
    function sendemail($dataEmail) {
        $this->load->library('email'); 
        $this->email->from("no-reply@qrlab.id"); 
        $this->email->to($dataEmail['user_email']); 
        $this->email->subject("Konfirmasi Registrasi [QR-LAB]");  

        $this->data['users'] = $dataEmail;
        $message=$this->load->view('login/template-email', $this->data, TRUE);
        $this->email->message($message); 
        $this->email->send();
    }
    
    function login() {
        $postData = $this->input->post();
        $username = trim($postData['user_name']);
        $password = trim($postData['user_pass']);
        $result = array();
        $result['success'] = true;
        $result['login'] = false;
        
        $user = $this->login_api($username, $password);
        if (!isset($user['error'])) {
            if (!$this->session->userdata('token')) {
                $this->session->set_userdata(array('token'=>$user['token']));
            }
            $input_session = array('user_id' => $user['user_id'], 'user_name' => $user['user_name'], 'login' => TRUE);
            $this->session->set_userdata($input_session);
            $result['success'] = true;
        } else {
            $result['success'] = false;
        }
        header("Content-type: application/json");
        echo json_encode($result);exit;
    }
    
//    function loginold() {
//        $postData = $this->input->post();
//        $username = trim($postData['user_name']);
//        $password = trim($postData['user_pass']);
//        $input = array('user_name' => $username);
//        $newLogin = true;
//        $result = array();
//        $user = $this->login_api($username, $password);exit;
//        $checkSession = $this->User_frontend_model->getByCategory(array('user_name' => $username));
//        $result['success'] = false;
//        $result['reset'] = false;
//
//        $this->session->sess_expiration = 60; // 4 Hours
//        if (sizeof($checkSession) > 0) {
////            && $this->session->userdata('is_login')
//            if ($checkSession[0]->user_sessionid != '') {
//                $datetime1 = new DateTime($checkSession[0]->user_lastlogin);
//                $datetime2 = new DateTime(date('Y-m-d H:i:s'));
//                //karena session per browser beda...gmn ya
//                $interval = $datetime1->diff($datetime2);
//                $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
//                if (($minutes * 60) > 7200) {
//                    $newLogin = true;
//                } else {
//                    $data['success'] = true;
//                    $result['login'] = true;
//                    $newLogin = false;
//                }
//            } else {
//                $newLogin = true;
//            }
//        }
//
//        if ($newLogin) {
//            $data = $this->User_frontend_model->check_user($input);
//            if ($data) {
//                if ($data['user_status'] == 'N') {
//                    $result['reset'] = true;
//                } else if ($data['user_status'] == 'Y') {
//                    if ($data['attempt'] > 5) {
//                        $this->user_admin_model->update(array('id' => $data['id'], 'user_status' => 'N'));
//                        $result['reset'] = true;
//                    } else {
//                        if (password_verify($password, $data['user_pass'])) {
//                            $result['success'] = true;
////                            $groupCodes = explode(",", $data['user_group']);
////                            if (in_array('00', $groupCodes) || in_array('01', $groupCodes) || in_array('02', $groupCodes)) {
////                                $isAdmin = true;
////                            }
//
//                            $input_session = array('user_id' => $data['id'], 'user_name' => $data['user_name'], 'login' => TRUE,
//                                'is_new' => $data['is_new'], 'user_image' => $data['user_image']);
//                            //update logins status
//                            $this->User_frontend_model->update(array('id' => $data['id'], 'user_lastlogin' => date('Y-m-d H:i:s')
//                                , 'user_sessionid' => session_id(), 'attempt' => 0));
//                            $this->session->set_userdata($input_session);
//                        } else {
//                            $data['attempt'] = $data['attempt'] + 1;
//                            $this->User_frontend_model->update(array('id' => $data['id'], 'attempt' => $data['attempt']));
//                            $result['success'] = false;
//                        }
//                    }
//                }
//            } else {
//                $result['success'] = false;
//            }
//        }
//        header("Content-type: application/json");
//        print json_encode($result);
//        exit;
//    }

    function save($data) {
        $token = $this->getToken();
        if (isset($token['error'])) {
            redirect('/');
        }
        $url = "https://api.qrlab.id/user/create";
        $ch = curl_init($url);

//        $data['user_birthdate'] = date('Y-m-d',strtotime(str_replace("/","-",$data['user_birthdate'])));
//        $options = array('cost' => 11);
//        $data['user_pass'] = password_hash((string) $data['user_pass'], PASSWORD_BCRYPT, $options);
        unset($data['user_pass_retype']);
        $data_string = http_build_query($data);                                                                                   
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token['access_token'].'')
        );

        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }
    
    function login_api($username, $password) {
//        $token = $this->getToken();
//        if (isset($token['error'])) {
//            redirect('/');
//        }
//        if (!$this->session->userdata('token')) {
//            $this->session->set_userdata(array('token'=>$token['access_token']));
//        }
        
//        $url = "https://api.qrlab.id/user/login";
        $url = "http://localhost/majooapi/login";
        $ch = curl_init($url);

        $data = array('user_name'=>$username,"password"=>$password);
        $data_string = http_build_query($data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded')
        );

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }

    function changepass_api($email) {
        $token = $this->getToken();
        if (isset($token['error'])) {
            redirect('/');
        }
        $url = "https://api.qrlab.id/user/change_pass";
        $ch = curl_init($url);

        $data_string = '';
        if ($email != '') {
            $data = array('useremail'=>$email);
            $data_string = http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token['access_token'].'')
        );

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
            return false;
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response,true);
            if(isset($result['error'])) {
                redirect('/login/logout');
            }
            return json_decode($response,true);
        }
    }
    
    function checkuser_api($tokenUser) {
        $token = $this->getToken();
        if (isset($token['error'])) {
            redirect('/');
        }
        
        $url = "https://api.qrlab.id/user/check_token";
        $ch = curl_init($url);

        $data_string = '';
        if ($tokenUser != '') {
            $data = array('usertoken'=>$tokenUser);
            $data_string = http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token['access_token'].'')
        );

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        } else {
            
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }

    function update_pass_api($id,$pass) {
        $token = $this->getToken();
        if (isset($token['error'])) {
            redirect('/');
        }
        $url = "https://api.qrlab.id/user/updatePass";
        $ch = curl_init($url);

        $data['id'] = $id;
        $options = array('cost' => 11);
        $data['user_pass'] = password_hash((string) $pass, PASSWORD_BCRYPT, $options);
        $data_string = http_build_query($data);                                                                                 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$token['access_token'].'')
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

    function logout() {
//        $this->load->model(array('User_frontend_model'));
//        $this->User_frontend_model->update(array('id' => $this->session->userdata('user_id'), 'user_lastlogout' => date('Y-m-d H:i:s'), 'user_sessionid' => ''));
        $this->session->sess_destroy();
        redirect('/');
    }

}
