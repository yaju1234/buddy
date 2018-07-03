<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloadexcel extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin_model','Api_user_model'));
		$this->load->helper(array('url', 'form'));
		
	}
	
	public function index() {
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}
		$data = array();
		$data['title'] = 'Lawyers Management';
		$data['client_list'] = $this->admin_model->getLawyers();
		/*echo "<pre />";
		print_r($data['client_list']);exit;*/
		$this->load->view('template/header.php', $data);
		$this->load->view('admin/download_excel_view', $data);
		$this->load->view('template/footer.php');
	}


	public function exceldata(){

		$this->load->library('excel');
		
		$timefrom = strtotime($this->input->post('fromdate'));
		$timeto = strtotime($this->input->post('todate'));

		$fromdate = date('Y-m-d',$timefrom);
		$todate = date('Y-m-d',$timeto);

		$this->excel->setActiveSheetIndex(0);
        //name the worksheet
		$this->excel->getActiveSheet()->setTitle('Users list');


          // get all users in array formate
		$cases = $this->admin_model->getCaseExceldata($fromdate,$todate);
       // echo "<pre/>";
       // print_r($users);
        //exit();

        // read data to active sheet
		$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


		$this->excel->getActiveSheet()->getStyle("A1:I1")->applyFromArray(array("font" => array("bold" => true)));

		$this->excel->setActiveSheetIndex(0)->setCellValue('A1', 'SL');
		$this->excel->setActiveSheetIndex(0)->setCellValue('B1', 'CLIENT NAME');
		$this->excel->setActiveSheetIndex(0)->setCellValue('C1', 'CLIENT ADDRESS');
		$this->excel->setActiveSheetIndex(0)->setCellValue('D1', 'LAWYER NAME');
		$this->excel->setActiveSheetIndex(0)->setCellValue('E1', 'LAWYER ADDRESS');
		$this->excel->setActiveSheetIndex(0)->setCellValue('F1', 'CASE NUMBER');
		$this->excel->setActiveSheetIndex(0)->setCellValue('G1', 'CASE DESCRIPTION');
		$this->excel->setActiveSheetIndex(0)->setCellValue('H1', 'PRICE');
		$this->excel->setActiveSheetIndex(0)->setCellValue('I1', 'DATE');

		$rowNumber=2;
		foreach($cases as $k=>$v){
			$this->excel->getActiveSheet()->setCellValue('A'.$rowNumber, $rowNumber-1);
			$this->excel->getActiveSheet()->setCellValue('B'.$rowNumber, $v['client_first_name'].' '.$v['client_last_name']);
			$this->excel->getActiveSheet()->setCellValue('C'.$rowNumber, 'Canada,'.$v['client_state'].','.$v['client_city']);
			$this->excel->getActiveSheet()->setCellValue('D'.$rowNumber, $v['lawyer_first_name'].' '.$v['lawyer_first_name']);
			$this->excel->getActiveSheet()->setCellValue('E'.$rowNumber, 'Canada,'.$v['lawyer_state'].','.$v['lawyer_city']);
			$this->excel->getActiveSheet()->setCellValue('F'.$rowNumber, $v['case_number']);
			$this->excel->getActiveSheet()->setCellValue('G'.$rowNumber, $v['case_details']);
			$this->excel->getActiveSheet()->setCellValue('H'.$rowNumber, $v['bid_amount']);
			$this->excel->getActiveSheet()->setCellValue('I'.$rowNumber, $v['created_at']);
			$rowNumber++;
		}

// get all users in array formate
//$this->excel->getActiveSheet()->fromArray($users, null, 'A2');

// read data to active sheet
//$this->excel->getActiveSheet()->fromArray($users);

       // $this->excel->getActiveSheet()->fromArray($users);

        $filename='just_some_random_name.xls'; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); //mime type

        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as.XLSX Excel 2007 format

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 

        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
		//echo $fromdate;
		//die();

		/*$data['created'] = date('Y-m-d H:i:s');
		if($this->input->post('cid') && $this->input->post('cid') > 0){
			$st = $this->admin_model->updateBanner($data, $this->input->post('cid'));
		} else {
			$st = $this->admin_model->addNewBanner($data);
		}
		redirect('/admin/banners', 'refresh');*/
	}
	

}
