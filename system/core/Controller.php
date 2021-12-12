<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
    protected $_limit = 10;
    protected $gender = array('L'=>'Laki-laki','P'=>'Perempuan');
    protected $merchantID = 'D4990';
    protected $merchantKEY = 'aec52087632e26e058c78378ef2379fb';
    protected $merchantIDDEV = 'D7832';
    protected $merchantKEYDEV = 'f95ed8542a307c5c83a2bb8d8f921c45';
    /**
     * Reference to the CI singleton
     *
     * @var	object
     */
    private static $instance;

    /**
     * Class constructor
     *
     * @return	void
     */
    public function __construct() {
        self::$instance = & $this;

        // Assign all the class objects that were instantiated by the
        // bootstrap file (CodeIgniter.php) to local class variables
        // so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }

        $this->load = & load_class('Loader', 'core');
        $this->load->initialize();
        log_message('info', 'Controller Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Get the CI singleton
     *
     * @static
     * @return	object
     */
    public static function &get_instance() {
        return self::$instance;
    }

    function getAllMenuWeb($ctgr, $type = 'backend') {
        $type = "'".$type."'";
        $this->load->model(array('menu_model'));
        $result = $this->menu_model->getLeftMenu('', $ctgr, $type);

        $temp = array();
        if (count($result)) {
            foreach ($result as $row => $value) {
                //get child
                $temp[] = $value;
                $child = $this->menu_model->getLeftMenu($value['id'], $ctgr, $type);
                if (count($child)) {
                    foreach ($child as $rowChild => $valueChild) {
                        $temp[$row]['child'][] = $valueChild;

                        $childLevel = $this->menu_model->getLeftMenu($valueChild['id'], $ctgr, $type);
                        if ($childLevel) {
                            foreach ($childLevel as $rowChildLevel => $valueChildLevel) {
                                $temp[$row]['child'][$rowChild]['child'][] = $valueChildLevel;
                            }
                        }
                    }
                }
            }
        }
        return $temp;
    }
    
    function getToken() {
        $url = "https://api.qrlab.id/access/token";
        $ch = curl_init($url);
        $clientId = 'webclient';
        $clientSecret = 'browseme';
        $data = array("grant_type" => "password", "username" => "adminapi",
            "password" => "s3r3n4d3!", 'client_id' => "$clientId", 'client_secret' => "$clientSecret");

        $data_string = http_build_query($data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        if (curl_errno($ch)) {
            echo curl_errno($ch);
            echo curl_error($ch);
        } else {
            $response = curl_exec($ch);
            curl_close($ch);
            return json_decode($response, true);
        }
    }

}
