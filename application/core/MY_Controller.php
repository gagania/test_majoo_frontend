<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $data = array();
    protected $menuCtgr = array(''=>'-- PILIH SATU --','header'=>'HEADER',
                                'left'=>'SUB MENU');
    protected $menuType = array(''=>'-- PILIH SATU --','backend'=>'BACKEND',
                                'frontend'=>'FRONTEND');
    protected $swithclevel = array(''=>'-- PILIH SATU --','leaf'=>'LEAF',
                                'spine'=>'SPINE');
    protected $portType = array(''=>'-- PILIH SATU --','customer'=>'CUSTOMER',
                                'service'=>'SERVICE','leaf'=>'LEAF','spine'=>'SPINE');
    
        
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('UTC');
        
        $this->load->helper('form');
        $this->load->helper('url');
        $login_status = $this->check_login();
        $this->load->model(array('Menu_model', 'Acl_Model'));
        $this->data['menus'] = $this->getMenus();
        $this->data['themes'] = $this->config->item('themes');
//        $this->data['modules'] = $this->modules;
    }
    
    function getMenus() {
        $acl = $this->getAcl($this->session->userdata('user_group')); 
        $menusAdmin = array();
        $menus = $this->getAllHeaderMenu();
        if ($this->session->userdata('user_group')=='00') {
            $menus = $this->getAllHeaderMenu();
        }
        $menuList = $this->getMenuListTOP($menus, $this->session->userdata('user_name'), $acl);
        return $menuList;
    }
    
    function getAcl($groups) {
        $groups = str_replace(',', "','", $groups);
        $groups = "'$groups'";
        $aclData = array();
        $acls = $this->Acl_Model->getAclsMenu($groups);
        if ($acls) {
            foreach($acls as $value){
                $aclData[] = $value['acl_resource'];
            }
            return $aclData;
        } else {
            return '';
        }

    }
    
    public function getMenuList($data, $user, $acl = null) {
        $result = '';
        if ($acl) {
            $checkAcl = false;
            if ($user and $acl) {
                $checkAcl = true;
            }

            foreach ($data as $item) {
                $resourceKey = 'menu'. $item['id'];
                if ($checkAcl) {
                    if (!in_array($resourceKey, $acl)) {
                        continue;
                    }
                }
                if ($this->session->flashdata('parent_menu_active') == $item['menu_name']) {
                    $parent_active = ' active';
                } else {
                    $parent_active = '';
                }
                $child = $this->Menu_model->getMenus($item['id']);
                if (sizeof($child) > 0) {
                    $result .= '<li class="treeview ' . $parent_active . '">';
                    $result .= '<a href="#">
                                    <i class="' . $item['menu_icon'] . '"></i>
                                    <span>' . $item['menu_name'] . '</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>';
                    $result .= $this->getChildMenu($child, $user, $acl);
                    $result .= '</li>';
                } else {
                    if ($item['menu_link'] != '') {
                        $result .= '<li class="treeview ' . $parent_active . '">';
                        $result .= '<a href="' . base_url() . $item['menu_link'] . '">
                                        <i class="' . $item['menu_icon'] . '"></i>
                                        <span>' . $item['menu_name'] . '</span>
                                    </a>';
                        $result .= '</li>';
                    }
                }
                $result .= '</li>';
            }
        }
        return $result;
    }
    
    public function getMenuListTOP($data, $user, $acl = null) {
        $result = '';
        if ($acl) {
            $checkAcl = false;
            if ($user and $acl) {
                $checkAcl = true;
            }

            foreach ($data as $item) {
                $resourceKey = 'menu'. $item['id'];
                if ($checkAcl) {
                    if (!in_array($resourceKey,$acl)) {
                        continue;
                    }
                }
                if (isset($item['child'])) {
                    $result .= '<li class="dropdown ">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-make-group position-left"></i> '.$item['menu_name'].'
                                <span class="caret"></span></a>';
                    $result .= $this->getMenuListChildren($item['child'], $user, $acl);
                } else {
                    
                    if ($item['menu_link'] != '') {
                        $result .= '<li><a href="'.base_url().$item['menu_link'].'">'.$item['menu_name'].'</a>';
                    } else {
                        $result .= '<li><a href="'.$item['menu_web_link'].'" target="_blank">'.$item['menu_name'].'</a>';
                    }
                }
                $result .= '</li>';
            }
        }
        return $result;
    }
    
    public function getMenuListChildren($data, $user, $acl = null) {
        $checkAcl = false;
        if ($user and $acl) {
            $checkAcl = true;
        }
        
        $result = '<ul class="dropdown-menu width-250"  role="menu">';
        foreach ($data as $index => $item) {
            
            $resourceKey = 'menu'. $item['id'];
            if ($checkAcl) {
                if (!in_array($resourceKey,$acl)) {
                    continue;
                }
            }
            
            if (isset($item['child'])) {
                $result .= '<li class="dropdown-submenu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-make-group position-left"></i> '.$item['menu_name'].'
                                </a>';
                $result .= $this->getMenuListChildren($item['child'], $user, $acl);
                
            } else {
                if ($item['menu_link'] != '') {
                    $result .= '<li><a href="'.base_url().$item['menu_link'].'">'.$item['menu_name'].'</a>';
                } 
                else {
//                    $result .= '<li><a href="'.$item['menu_web_link'].'">'.$item['menu_name'].'</a>';
//                    $result .= '<li><a href="'.$item['menu_web_link'].'">Please define controller</a>';
                }
                
            }
            $result .= '</li>';
        }
        $result .= '</ul>';
        
        return $result;
    }
    
    function getAllHeaderMenu($ctgr='left',$type = 'backend') {
        if (is_array($type)) {
            $type = implode("','",$type);
            $type = "'".$type."'";
        }
        $result = $this->Menu_model->getHeaderMenu(0,$ctgr,$type);
        
        $temp = array();
        if (count($result)) {
            foreach($result as $row => $value) {
                //get child
                $temp[] = $value;
                $child = $this->Menu_model->getHeaderMenu($value['id'],'',$type);
                if (count($child)) {
                    foreach($child as $rowChild => $valueChild) {
                        $temp[$row]['child'][] = $valueChild;
                        
                        $childLevel = $this->Menu_model->getHeaderMenu($valueChild['id'],'',$type);
                        if ($childLevel) {
                            foreach($childLevel as $rowChildLevel => $valueChildLevel) {
                                $temp[$row]['child'][$rowChild]['child'][] = $valueChildLevel;
                                
                                $childLevel2 = $this->Menu_model->getHeaderMenu($valueChildLevel['id'],'',$type);
                                if ($childLevel2) {
                                    foreach($childLevel2 as $rowChildLevel2 => $valueChildLevel2) {
                                        $temp[$row]['child'][$rowChild]['child'][$rowChildLevel]['child'][] = $valueChildLevel2;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $temp;
    }
    
    function getAllMenuWeb($ctgr,$type = 'backend',$order= '') {
        if (is_array($type)) { 
            $type = implode("','",$type);
            
        } 
        $type = "'".$type."'";
        $result = $this->Menu_model->getLeftMenu(0,$ctgr,$type,$order);
        $temp = array();
        if (count($result)) {
            foreach($result as $row => $value) {
                //get child
                
                $temp[] = $value;
//                $temp[] = $this->getChildNew($value,$ctgr,$type,$module,$row);
                //check if there is a child
                $child = $this->Menu_model->getLeftMenu($value['id'],'left',$type,'menu_order ASC');
                if (count($child)) {
                    foreach($child as $rowChild => $valueChild) {
                        $temp[$row]['child'][] = $valueChild;
                        $childLevel = $this->Menu_model->getLeftMenu($valueChild['id'],'left',$type,'menu_order ASC');
                        if ($childLevel) {
                            foreach($childLevel as $rowChildLevel => $valueChildLevel) {
                                $temp[$row]['child'][$rowChild]['child'][] = $valueChildLevel;
                                $childLevel2 = $this->Menu_model->getLeftMenu($valueChildLevel['id'],'left',$type,'menu_order ASC');
                                if ($childLevel2) {
                                    foreach($childLevel2 as $rowChildLevel2 => $valueChildLevel2) {
                                        $temp[$row]['child'][$rowChild]['child'][$rowChildLevel]['child'][] = $valueChildLevel2;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $temp;
    }
    
    function getChildNew($value,$ctgr,$type,$module,$row) {
        //check if got child
        $temp[] = $value;
        $child = $this->Menu_model->getLeftMenu($value['id'],'left',$type,$module);
        if (count($child)) {
            foreach($child as $rowChild => $valueChild) {
                $temp[$row]['child'][] = $valueChild;
//                $child2[] = $this->getChildNew($valueChild,$ctgr,$type,$module,$rowChild);
//                $temp[] = array_push($child2,$temp);
//                $temp2[] = $valueChild;
//                $child2 = $this->Menu_model->getLeftMenu($valueChild['id'],$ctgr,$type,$module);
//                if (count($child2)) {
//                    $tempChild = $this->getChildNew($temp2,$child2,$ctgr,$type,$module,$rowChild);
//                    print_r($tempChild);exit;
//                }
            }
       }
        return $temp;
    }
    
    function getChildMenu($data, $user, $acl) {
        $result = '';
        $result .= '<ul class="treeview-menu">';
        if ($acl) {
            $checkAcl = false;
            if ($user and $acl) {
                $checkAcl = true;
            }

            foreach ($data as $item) {
                $resourceKey = 'menu'. $item['id'];
                if ($checkAcl) {
                    if (!in_array($resourceKey, $acl)) {
                        continue;
                    }
                }
                if ($this->session->flashdata('parent_menu_active') == $item['menu_name']) {
                    $parent_active = ' active';
                } else {
                    $parent_active = '';
                }
                $child = $this->Menu_model->getMenus($item['id']);
                if (sizeof($child) > 0) {
                    $result .= '<li class="treeview ' . $parent_active . '">';
                    $result .= '<a href="#">
                                    <i class="' . $item['menu_icon'] . '"></i>
                                    <span>' . $item['menu_name'] . '</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>';
                    $result .= $this->getChildMenu($child, $user, $acl);
                    $result .= '</li>';
                } else {
                    if ($item['menu_link'] != '') {
                        $result .= '<li class="treeview ' . $parent_active . '">';
                        $result .= '<a href="' . base_url() . $item['menu_link'] . '">
                                        <i class="' . $item['menu_icon'] . '"></i>
                                        <span>' . $item['menu_name'] . '</span>
                                    </a>';
                        $result .= '</li>';
                    }
                }
                $result .= '</li>';
            }
        }
        $result .= '</ul>';
        return $result;
    }
    
    function getChildMenuOld($id,$ctgr,$type) {
        $child = $this->Menu_model->getLeftMenu($id,$ctgr,$type);
        if (count($child)) {
            foreach($child as $rowChild => $valueChild) {
                $temp[$row]['child'][] = $valueChild;

                $childLevel = $this->Menu_model->getLeftMenu($valueChild['id'],$ctgr);
                if ($childLevel) {
                    foreach($childLevel as $rowChildLevel => $valueChildLevel) {
                        $temp[$row]['child'][$rowChild]['child'][] = $valueChildLevel;
                    }
                }
            }
        }
    }
    
    function check_login() {
        
        if ($this->session->userdata('login') == TRUE) {
            return TRUE;
        } else {
            redirect('/login/index');
        }
    }

    function prepareList($data, $key, $value, $allowNull = false) {
        $result = array();
        if ($allowNull) {
            $result[''] = '-- PILIH SATU --';
        }

        if ($data) {
            if (is_string($value)) {
                $value = array($value);
            }

            foreach ($data as $item) {

                if ($item instanceof stdClass) {
                    $item = (array) $item;
                }

                if (is_array($key)) {
                    $textKey = '';
                    $flag = false;
                    foreach ($key as $indexKey) {
                        if ($textKey) {
                            $textKey .= '-';
                        }
                        if (isset($indexKey)) {
                            $textKey .= $item[$indexKey];
                        }

                        if (isset($item[$indexKey])) {
                            $flag = true;
                        } else {
                            $flag = false;
                        }
                    }

                    if ($flag) {

                        $text = '';
                        foreach ($value as $val) {
                            if ($text) {
                                $text .= ' - ';
                            }

                            if (isset($item[$val])) {
                                $text .= $item[$val];
                            }
                        }
                        $result[$textKey] = $text;
                    }
                } else {
                    if (isset($item[$key])) {

                        $text = '';
                        foreach ($value as $val) {
                            if ($text) {
                                $text .= ' - ';
                            }

                            if (isset($item[$val])) {
                                $text .= $item[$val];
                            }
                        }
                        $result[$item[$key]] = $text;
                    }
                }
            }
        }

        return $result;
    }
    
    function save_log($action) {
        if ($action != '') {
            $this->load->model(array('Util_User_Log_Model'));
            $logData = array();
            $logData['log_Date'] = date('Y-m-d h:i:s');
            $logData['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $logData['action'] = $action;
            $logData['id_user'] = $this->session->userdata('user_id');

            $this->Util_User_Log_Model->add($logData);
        }
    }

    public function detectDelimiter($csvFile) {
        $delimiters = array(
            ';' => 0,
            ',' => 0,
            "\t" => 0,
            "|" => 0
        );

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}