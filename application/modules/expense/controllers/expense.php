<?php
class Expense extends MX_Controller
{
    public $ilimit;
    public $year;
    public $month;

    public function __construct()
    {
        parent::__construct();

        $this->ilimit = 5;
        $this->year = date('Y');
        $this->month = date('m');

        $this->load->helper('download');
        $this->load->module("core/app");
        $this->load->module("site/template");
        $this->load->model("expense_model");
        $this->load->module("expense/expense_common");
        $this->load->library('site/PHPExcel/PHPExcel');
        $this->load->module('settings/logs');
        $this->app->use_js(array("source"=>"expense/expense"));
        $this->app->use_js(array("source"=>"expense/defaults"));
        $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
        $this->app->use_js(array("source"=>"site/jquery-formatcurrency/jquery.formatCurrency-1.4.0"));
        $this->app->use_js(array("source"=>"site/jquery-formatcurrency/i18n/jquery.formatCurrency.all"));
        $this->app->use_js(array("source"=>"site/libs/jquery.validate"));
    }

    public function index()
    {
        //get variables
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '0';
        $pf = isset($_GET['pf']) ? $_GET['pf'] : '';
        $pt = isset($_GET['pt']) ? $_GET['pt'] : '';
        $dept = isset($_GET['dept']) ? $_GET['dept'] : '';

        //uri data
        if($this->uri->segment(2) == 'index'){
            $month = $this->uri->segment(4);
            $year = $this->uri->segment(3);
        }else{
            $month = $this->month;
            $year = $this->year;
        }
        $adata = array();

        if(!isset($_POST['real_expense_search_string'])){
            $itotal_row = $this->expense_model->get_count($year,$month,$sort,$pf,$pt,$dept);
            $alimit = $this->common->sql_limit($itotal_row,$this->ilimit);
            $adata['list'] = $this->expense_model->get_list($year,$month,$alimit,$sort,$pf,$pt,$dept);
            $adata['searchstr'] = "";
        }else{
            $search_string = $_POST['real_expense_search_string'];
            $itotal_row = $this->expense_model->get_count_search($year,$month,$search_string);
            $alimit = $this->common->sql_limit($itotal_row,$this->ilimit);
            $adata['list'] = $this->expense_model->search($year,$month,$alimit,$search_string);
            $adata['searchstr'] = $_POST['real_expense_search_string'];
        }

        $adata['department_links'] = $this->expense_model->get_dept_links($year,$month);
        $adata['items'] = $this->expense_model->get_items();
        $adata['attachment'] = $this->expense_model->get_attachments();
        $adata['pager'] = $this->common->pager($itotal_row,$this->ilimit,array('active_class'=>'current'));

        $adata['requested_amt'] = $this->expense_model->get_requested_amt($year,$month,$pf,$pt);
        $adata['received_amt'] = $this->expense_model->get_receive_amt($year,$month,$pf,$pt);
        $adata['payment'] = $this->expense_model->get_payment($year,$month,$pf,$pt);
        $adata['quantity'] = $this->expense_model->get_quantity($year,$month,$pf,$pt);
        $adata['items_price'] = $this->expense_model->get_items_price($year,$month,$pf,$pt);
        $adata['cashonhand'] = $this->expense_model->get_total_cashonhand($year,$month,$pf,$pt);
        $adata['unionbankbal'] = $this->expense_model->get_union_bank_balance($year,$month,$pf,$pt);
        $adata['alimit'] = $alimit;

        $adata['all'] = $this->expense_model->get_all($year,$month);

        $adata['year'] = $year;
        $adata['month'] = date('F', mktime(0,0,0,$month,1,$year));

        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/real_expense/real_expenses",$adata);
        $this->template->footer();
    }

    public function real_expense_spreadsheet()
    {
        //get variables
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '0';
        $pf = isset($_GET['pf']) ? $_GET['pf'] : '';
        $pt = isset($_GET['pt']) ? $_GET['pt'] : '';
        $dept = isset($_GET['dept']) ? $_GET['dept'] : '';

        //uri data
        if($this->uri->segment(2) == 'real_expense_spreadsheet' && $this->uri->segment(3) != ""){
            $month = $this->uri->segment(4);
            $year = $this->uri->segment(3);
        }else{
            $month = $this->month;
            $year = $this->year;
        }

        $adata = array();

        if(!isset($_POST['real_expense_search_string'])){
            $itotal_row = $this->expense_model->get_count($this->year,$this->month,$sort,$pf,$pt,$dept);
            $alimit = $this->common->sql_limit($itotal_row,$this->ilimit);
            $adata['list'] = $this->expense_model->get_list_spreadsheet($year,$month,$alimit,$sort,$pf,$pt,$dept);
            $adata['searchstr'] = "";
        }else{
            $search_string = $_POST['real_expense_search_string'];
            $itotal_row = $this->expense_model->get_count_search($year,$month,$search_string);
            $alimit = $this->common->sql_limit($itotal_row,$this->ilimit);
            $adata['list'] = $this->expense_model->search($year,$month,$alimit,$search_string);
            $adata['searchstr'] = $_POST['real_expense_search_string'];
        }

        $adata['department_links'] = $this->expense_model->get_dept_links($year,$month);
        $adata['items'] = $this->expense_model->get_items();
        $adata['attachment'] = $this->expense_model->get_attachments();
        $adata['pager'] = $this->common->pager($itotal_row,$this->ilimit,array('active_class'=>'current'));

        $adata['requested_amt'] = $this->expense_model->get_requested_amt($year,$month,$pf,$pt);
        $adata['received_amt'] = $this->expense_model->get_receive_amt($year,$month,$pf,$pt);
        $adata['payment'] = $this->expense_model->get_payment($year,$month,$pf,$pt);
        $adata['quantity'] = $this->expense_model->get_quantity($year,$month,$pf,$pt);
        $adata['items_price'] = $this->expense_model->get_items_price($year,$month,$pf,$pt);
        $adata['cashonhand'] = $this->expense_model->get_total_cashonhand($year,$month,$pf,$pt);
        $adata['unionbankbal'] = $this->expense_model->get_union_bank_balance($year,$month,$pf,$pt);

        $adata['cashreceive'] = $this->expense_model->get_total_cash_receive($year,$month,$pf,$pt);
        $adata['cashpayment'] = $this->expense_model->get_total_cash_payment($year,$month,$pf,$pt);
        $adata['ubdeposit'] = $this->expense_model->get_total_ub_deposit($year,$month,$pf,$pt);
        $adata['ubtransfer'] = $this->expense_model->get_total_ub_transfer($year,$month,$pf,$pt);
        $adata['alimit'] = $alimit;

        $adata['all'] = $this->expense_model->get_all($year,$month);

        $adata['year'] = $year;
        $adata['month'] = date('F', mktime(0,0,0,$month,1,$year));
        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/real_expense/real_expense_spread_sheet_view", $adata);
        $this->template->footer();
    }

    public function add_new_expense()
    {
        $adata = array();
        $adata['adepartment'] = $this->expense_model->get_department();
        $adata['astatus'] = $this->expense_model->get_status();
        $adata['acategory'] = $this->expense_model->get_category();

        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/real_expense/add_new_expense", $adata);
        $this->template->footer();
    }

    public function edit_real_expense()
    {
        $edit_id = $this->uri->segment(3);

        $adata = array();
        $adata['adepartment'] = $this->expense_model->get_department();
        $adata['astatus'] = $this->expense_model->get_status();
        $adata['acategory'] = $this->expense_model->get_category();
        $adata['saved_data'] = $this->expense_model->get_saved_data($edit_id);

        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/real_expense/edit_real_expense", $adata);
        $this->template->footer();
    }

    public function export_expense()
    {
        //get variables
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '0';
        $pf = isset($_GET['pf']) ? $_GET['pf'] : '';
        $pt = isset($_GET['pt']) ? $_GET['pt'] : '';
        $dept = isset($_GET['dept']) ? $_GET['dept'] : '';
        $year = isset($_GET['year']) ? $_GET['year'] : $this->year;
        $month = isset($_GET['month']) ? $_GET['month'] : $this->month;
        $view = isset($_GET['view']) ? $_GET['view'] : 'index';

        //function calls
        $data = array();
        $data = $this->expense_model->export_list($year,$month,$sort,$pf,$pt,$dept);
        $attachment = $this->expense_model->get_attachments($year,$month,$sort,$pf,$pt,$dept);
        $cashonhand = $this->expense_model->get_total_cashonhand($year,$month,$pf,$pt);
        $cashreceive = $this->expense_model->get_total_cash_receive($year,$month,$pf,$pt);
        $cashpayment = $this->expense_model->get_total_cash_payment($year,$month,$pf,$pt);
        $ubdeposit = $this->expense_model->get_total_ub_deposit($year,$month,$pf,$pt);
        $ubtransfer = $this->expense_model->get_total_ub_transfer($year,$month,$pf,$pt);
        $cashonhand = $this->expense_model->get_total_cashonhand($year,$month,$pf,$pt);
        $unionbankbal = $this->expense_model->get_union_bank_balance($year,$month,$pf,$pt);

        //variables
        $month = date('F', mktime(0,0,0,$month,1,$year));
        $cell_num = 4;

        if(count($data)>0){
            $this->phpexcel->setActiveSheetIndex(0);

            //name the worksheet
            $this->phpexcel->getActiveSheet()->setTitle($month . $year . " Real Expense");

            //set main title
            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Journal for SIP-Staff');
            $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('A1:I3')->getFont()->setBold(true);

            //set expense journal headers
            $this->phpexcel->getActiveSheet()->setCellValue('A2', 'Date');
            $this->phpexcel->getActiveSheet()->setCellValue('B2', 'Transaction');
            $this->phpexcel->getActiveSheet()->setCellValue('C2', 'Union Bank');
            $this->phpexcel->getActiveSheet()->setCellValue('E2', 'Cash on Hand');
            $this->phpexcel->getActiveSheet()->setCellValue('G2', 'Evidence (in case of payment)');
            $this->phpexcel->getActiveSheet()->setCellValue('H2', 'Description (indispensable)');
            $this->phpexcel->getActiveSheet()->setCellValue('C3', 'Deposit');
            $this->phpexcel->getActiveSheet()->setCellValue('D3', 'Transfer');
            $this->phpexcel->getActiveSheet()->setCellValue('E3', 'Received');
            $this->phpexcel->getActiveSheet()->setCellValue('F3', 'Payment');
            $this->phpexcel->getDefaultStyle()->getFont()->setSize(10);

            //set cells content values
            foreach($data as $row){
            $aattachment = array();
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $row->new_tel_date);
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $row->tel_type);
            if($row->tel_tes_idx == '00000000001'){
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $row->tel_request_amt);
            }else{
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $row->tel_deposit_amt);
            }
            if($row->tel_tes_idx == '00000000003'){
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $row->tel_payment);
            }else{
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $row->tel_transfer_amt);
            }
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, $row->tel_receive_amt);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, $row->tel_payment);
            foreach($attachment as $a){
                if($a->tea_tel_idx == $row->tel_idx){
                    if($a->tea_filename != "" && $a->tea_attachment_type == "receipt"){
                        array_push($aattachment, 'OR#'.$a->tea_idx.';');
                    }
                }
            }

            if(!empty($aattachment)){
                $this->phpexcel->getActiveSheet()->setCellValue('G'.$cell_num, implode($aattachment));
            }else{
                $this->phpexcel->getActiveSheet()->setCellValue('G'.$cell_num, '');
            }
            $this->phpexcel->getActiveSheet()->setCellValue('H'.$cell_num, $row->tel_particulars);

            //set number format
            $this->phpexcel->getActiveSheet()->getStyle('C'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle('D'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle('E'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle('F'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            //set borders
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':H'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            //set font colors
            $this->phpexcel->getActiveSheet()->getStyle('C'.$cell_num)->getFont()->getColor()->setRGB('00B0F0');
            $this->phpexcel->getActiveSheet()->getStyle('F'.$cell_num)->getFont()->getColor()->setRGB('E46C0A');

            //set allignment
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $cell_num++;
            }

            //set cells footer content values
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $year . ' ' . $month . ' TOTAL');
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num)->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->setCellValue('A'.($cell_num+1),'TOTAL');
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+1))->getFont()->setBold(true);

            $ubdeposit = ($ubdeposit > 0) ? $ubdeposit : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('C'.($cell_num+1),$ubdeposit);
            $this->phpexcel->getActiveSheet()->getStyle('C'.($cell_num+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $ubtransfer = ($ubtransfer > 0) ? $ubtransfer : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('D'.($cell_num+1),$ubtransfer);
            $this->phpexcel->getActiveSheet()->getStyle('D'.($cell_num+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $cashreceive = ($cashreceive > 0) ? $cashreceive : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('E'.($cell_num+1),$cashreceive);
            $this->phpexcel->getActiveSheet()->getStyle('E'.($cell_num+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $cashpayment = ($cashpayment > 0) ? $cashpayment : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('F'.($cell_num+1),$cashpayment);
            $this->phpexcel->getActiveSheet()->getStyle('F'.($cell_num+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $this->phpexcel->getActiveSheet()->setCellValue('A'.($cell_num+2),'BALANCE');
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+2))->getFont()->setBold(true);

            $this->phpexcel->getActiveSheet()->setCellValue('C'.($cell_num+2),'UB(B):');
            $this->phpexcel->getActiveSheet()->getStyle('C'.($cell_num+2))->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('C'.($cell_num+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $unionbankbal = ($unionbankbal > 0) ? $unionbankbal : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('D'.($cell_num+2),$unionbankbal);
            $this->phpexcel->getActiveSheet()->getStyle('D'.($cell_num+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $this->phpexcel->getActiveSheet()->setCellValue('E'.($cell_num+2),'Cash on Hand:');
            $this->phpexcel->getActiveSheet()->getStyle('E'.($cell_num+2))->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('E'.($cell_num+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $cashonhand = ($cashonhand > 0) ? $cashonhand : 0.00;
            $this->phpexcel->getActiveSheet()->setCellValue('F'.($cell_num+2),$cashonhand);
            $this->phpexcel->getActiveSheet()->getStyle('F'.($cell_num+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


            //adjust column width
            $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            $this->phpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(60);

            //merge cell A1 until D1
            $this->phpexcel->getActiveSheet()->mergeCells('A1:H1');
            $this->phpexcel->getActiveSheet()->mergeCells('A2:A3');
            $this->phpexcel->getActiveSheet()->mergeCells('B2:B3');
            $this->phpexcel->getActiveSheet()->mergeCells('G2:G3');
            $this->phpexcel->getActiveSheet()->mergeCells('H2:H3');
            $this->phpexcel->getActiveSheet()->mergeCells('C2:D2');
            $this->phpexcel->getActiveSheet()->mergeCells('E2:F2');
            $this->phpexcel->getActiveSheet()->mergeCells('A'.$cell_num.':H'.$cell_num);
            $this->phpexcel->getActiveSheet()->mergeCells('A'.($cell_num+1).':B'.($cell_num+1));
            $this->phpexcel->getActiveSheet()->mergeCells('A'.($cell_num+2).':B'.($cell_num+2));

            //set fill color
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':H'.$cell_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

            //set aligment to center for that merged cell
            $this->phpexcel->getActiveSheet()->getStyle('A1:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A2:A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('B2:B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('G2:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('H2:H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            //set borders
            $this->phpexcel->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('B2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B2:B3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B2:B3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('G2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G2:G3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G2:G3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('H2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H2:H3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H2:H3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('C2:D2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('C2:D2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('C2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('D2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('E2:F2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('E2:F2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('E2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('F2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('C3:F3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':H'.$cell_num)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':H'.$cell_num)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H'.$cell_num)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B'.($cell_num+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+1).':B'.($cell_num+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+1).':B'.($cell_num+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+1).':H'.($cell_num+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+1))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H'.($cell_num+1))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+1).':H'.($cell_num+1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+1).':H'.($cell_num+1))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+2))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B'.($cell_num+2))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+2).':B'.($cell_num+2))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+2).':B'.($cell_num+2))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('A'.($cell_num+2).':G'.($cell_num+2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+2))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('H'.($cell_num+2))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+2).':H'.($cell_num+2))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('G'.($cell_num+2).':H'.($cell_num+2))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            //download and save excel file
            $filename=$month . ' ' . $year . ' Real Expense' . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
        }else{
            $message = "There is currently no data to be exported for ".$month." ".$year.".";
            $this->common->set_message($message,"my-save-message","warning");
            if($view == "index"){
                redirect('expense/');
            }else{
                redirect('expense/real_expense_spreadsheet');
            }
        }
    }
}