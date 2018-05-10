<?php
class MY_Model extends CI_Model {
	public $READ;
	public $WRITE;
    function __construct(){
        parent::__construct();
        $this->db->reconnect();
		$this->WRITE = $this->load->database('default', TRUE);
		$this->READ = $this->load->database('read', TRUE);
    }
}
