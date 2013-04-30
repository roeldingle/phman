<?php

class Total_cost_report extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('download');
        $this->load->module("core/app");
        $this->load->module("site/template");
        $this->load->model("total_cost_report_model");
        $this->load->module("expense/expense_common");
        $this->load->library('site/PHPExcel/PHPExcel');
        $this->app->use_js(array("source"=>"site/libs/highcharts"));    
        $this->app->use_js(array("source"=>"expense/total_cost_report/total_cost_report"));
        $this->app->use_js(array("source"=>"expense/defaults"));
        $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
    }
    
    public function index()
    {
        //normal variables
        $ya_ctr = 1;
        
        //GET variables
        $view = isset($_GET['view']) ? $_GET['view'] : 'all';
        $sp_from = isset($_GET['from']) ? $_GET['from'] : '';
        $sp_to = isset($_GET['to']) ? $_GET['to'] : '';
        
        //array
        $year = array();
        $adata = array();
        $adata['monthly_cost'] = array();
        $adata['total_cost'] = array();
        $adata['annual_savings'] = array();
        $adata['monthly_average'] = array();
        $adata['yearly_average'] = array();
        $adata['quarterly_average1'] = array();
        $adata['quarterly_average2'] = array();
        $adata['quarterly_average3'] = array();
        $adata['quarterly_average4'] = array();
        
        $adata['quarter_month'] = array('January', 'April', 'July', 'October');
        $adata['month'] = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        
        $adata['year'] = $this->total_cost_report_model->get_year($view,$sp_from,$sp_to);
        
        //gets current monthly average cost
        $adata['current_monthly_average'] = $this->total_cost_report_model->get_current_monthly_average();
        
        //gets previous - current monthly average cost
        $adata['previous_current_monthly_average'] = $this->total_cost_report_model->get_previous_current_monthly_average();
        
        //gets previous - current quarterly average cost
        $adata['previous_current_quarterly_average'] = $this->total_cost_report_model->get_previous_current_quarterly_average();
        
        foreach($adata['year'] as $pyear){
            //gets the total payment for each month
            for($imonth=1;$imonth<=12;$imonth++){
                $pmonth = date('F', mktime(0,0,0,$imonth,1,$pyear->pyear));
                $smonth = ($imonth<10) ? '0' . $imonth : $imonth;
                
                $monthly_cost = $this->total_cost_report_model->get_total_payment($pyear->pyear,$smonth);
                array_push($adata['monthly_cost'], array(
                                                        "month"=>$pmonth,
                                                        "year"=>$pyear->pyear,
                                                        "total_payment"=>$monthly_cost
                                                    )
                );
            }
            
            //get the total payment for each year
            $total_cost = $this->total_cost_report_model->get_total_cost($pyear->pyear);
            array_push($adata['total_cost'], array(
                                                "year" => $pyear->pyear,
                                                "total_cost" =>$total_cost
                                             )
            );
            
            //gets the annual savings for each year
            array_push($year, $pyear->pyear);
            $as_year = $pyear->pyear - 1;
            $syear = (string)$as_year;
           
            if(!in_array($syear, $year)){
               array_push($adata['annual_savings'], array(
                                                        "year" => $pyear->pyear,
                                                        "annual_savings" => "-"
                                                    )
               );
            }else{
                $annual_savings = $this->total_cost_report_model->get_annual_savings($pyear->pyear);
                array_push($adata['annual_savings'], array(
                                                        "year" => $pyear->pyear,
                                                        "annual_savings" => $annual_savings
                                                    )
               );
            }
            
            //gets the monthly average for each year
            $monthly_average = $this->total_cost_report_model->get_monthly_average($pyear->pyear);
            array_push($adata['monthly_average'], array(
                                                "year" => $pyear->pyear,
                                                "monthly_average" =>$monthly_average
                                             )
            );
            
            //gets yearly average for each year
            if(!in_array($syear, $year)){
               array_push($adata['yearly_average'], array(
                                                        "year" => $pyear->pyear,
                                                        "yearly_average" => "-"
                                                    )
                );
            }else{
                $ya_ctr++;
                $yearly_average = $this->total_cost_report_model->get_yearly_average($pyear->pyear,$ya_ctr);
                array_push($adata['yearly_average'], array(
                                                        "year" => $pyear->pyear,
                                                        "yearly_average" => $yearly_average
                                                    )
                );
            }
            
            //gets 1st quarterly average for each year
            $aq1_months = array('01','02','03');
            $quarterly_average1 = $this->total_cost_report_model->get_quarterly_average1($pyear->pyear,$aq1_months);
            array_push($adata['quarterly_average1'], array(
                                                        "year" => $pyear->pyear,
                                                        "quarterly_average1" => $quarterly_average1
                                                    )
            );
            
            //gets 2nd quarterly average for each year
            $aq2_months = array('04','05','06');
            $quarterly_average2 = $this->total_cost_report_model->get_quarterly_average2($pyear->pyear,$aq2_months);
            array_push($adata['quarterly_average2'], array(
                                                        "year" => $pyear->pyear,
                                                        "quarterly_average2" => $quarterly_average2
                                                    )
            );
            
            //gets 3rd quarterly average for each year
            $aq3_months = array('07','08','09');
            $quarterly_average3 = $this->total_cost_report_model->get_quarterly_average3($pyear->pyear,$aq3_months);
            array_push($adata['quarterly_average3'], array(
                                                        "year" => $pyear->pyear,
                                                        "quarterly_average3" => $quarterly_average3
                                                    )
            );
            
            //gets 4th quarterly average for each year
            $aq4_months = array('10','11','12');
            $quarterly_average4 = $this->total_cost_report_model->get_quarterly_average4($pyear->pyear,$aq4_months);
            array_push($adata['quarterly_average4'], array(
                                                        "year" => $pyear->pyear,
                                                        "quarterly_average4" => $quarterly_average4
                                                    )
            );
        }   
    
        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/total_cost_report/total_cost_analysis",$adata);
        $this->template->footer();
    }
    
    public function total_cost_graph()
    {
        $adata = array();
        $adata['year'] = $this->total_cost_report_model->get_graph_year();
        
        $this->template->header();
        $this->expense_common->sidebar();
        $this->template->breadcrumbs();
        $this->app->content("expense/total_cost_report/total_cost_graph",$adata);
        $this->template->footer();
    }
    
    public function total_cost_report_export()
    {
        $gsp_from = isset($_GET['specific_period_from']) ? $_GET['specific_period_from'] : "";
        $gsp_to = isset($_GET['specific_period_to']) ? $_GET['specific_period_to'] : "";
        $gcop_from = isset($_GET['date_from']) ? $_GET['date_from'] : "";
        $gcop_to = isset($_GET['date_to']) ? $_GET['date_to'] : "";
        $view_year = isset($_GET['view']) ? $_GET['view'] : "";
        
        $limit = ($view_year=="0" || $view_year=="all") ? "" : "ORDER BY pyear LIMIT ".$view_year." OFFSET 0";
        $where = ($gsp_from=="0" && $gsp_to=="0") ? "" : "WHERE EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) BETWEEN '{$gsp_from}' AND '{$gsp_to}'";
        
        $cyear = $this->db->query("SELECT COUNT(DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date))) as pyear
                                    FROM tbl_expense_list
                                    {$where}
                                    {$limit}");
        $year = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) as pyear
                                    FROM tbl_expense_list
                                    {$where}
                                    {$limit}");
        $year = $year->result();
        
        if((int)$cyear->row()->pyear > 0){
            $this->phpexcel->setActiveSheetIndex(0);

            //name the worksheet
            $this->phpexcel->getActiveSheet()->setTitle("Total Cost Report");

            //set cell headers
            $this->phpexcel->getActiveSheet()->setCellValue('A1', '');
            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Compare Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('A2', '');
            $this->phpexcel->getActiveSheet()->setCellValue('B2', 'Period');
            $this->phpexcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);

            //set quarters
            $this->phpexcel->getActiveSheet()->setCellValue('A3', '1st Quarter');
            $this->phpexcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->setCellValue('A6', '2nd Quarter');
            $this->phpexcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->setCellValue('A9', '3rd Quarter');
            $this->phpexcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->setCellValue('A12', '4th Quarter');
            $this->phpexcel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->setCellValue('A24', 'Budget Forecasts:');
            $this->phpexcel->getActiveSheet()->getStyle('A24')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('A12')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('A24')->getFont()->setBold(true);

            //set months
            $this->phpexcel->getActiveSheet()->setCellValue('B3', 'January');
            $this->phpexcel->getActiveSheet()->setCellValue('B4', 'February');
            $this->phpexcel->getActiveSheet()->setCellValue('B5', 'March');
            $this->phpexcel->getActiveSheet()->setCellValue('B6', 'April');
            $this->phpexcel->getActiveSheet()->setCellValue('B7', 'May');
            $this->phpexcel->getActiveSheet()->setCellValue('B8', 'June');
            $this->phpexcel->getActiveSheet()->setCellValue('B9', 'July');
            $this->phpexcel->getActiveSheet()->setCellValue('B10', 'August');
            $this->phpexcel->getActiveSheet()->setCellValue('B11', 'Septmeber');
            $this->phpexcel->getActiveSheet()->setCellValue('B12', 'October');
            $this->phpexcel->getActiveSheet()->setCellValue('B13', 'November');
            $this->phpexcel->getActiveSheet()->setCellValue('B14', 'December');

            //set total costs and averages per year
            $this->phpexcel->getActiveSheet()->setCellValue('B15', 'Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B16', 'Annual Savings');
            $this->phpexcel->getActiveSheet()->setCellValue('B17', 'Monthly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B18', 'Yearly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B19', 'Q1 Quarterly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B20', 'Q2 Quarterly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B21', 'Q3 Quarterly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B22', 'Q4 Quarterly Average of Total Cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B23', '');
            $this->phpexcel->getActiveSheet()->setCellValue('B24', 'Current monthly average cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B25', 'Previous - Current monthly average cost');
            $this->phpexcel->getActiveSheet()->setCellValue('B26', 'Previous - Current quarterly average cost');
            $this->phpexcel->getActiveSheet()->getStyle('B15')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('B16')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('B17')->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('B18')->getFont()->setBold(true);

            $ialpha = 67;
            $imc = 0;
            $curr_year_alpha = '';
            $amonthly_cost = array();
            $last_salpha = "";
            foreach($year as $y){
                //set cell values
                $salpha = chr($ialpha);
                
                $curr_year_alpha = ($y->pyear == date('Y')) ? $salpha : '';
                
                //set years
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'2', $y->pyear);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                //get total cost per month
                $imc_cell = 3;
                for($imonth=1;$imonth<=12;$imonth++){
                    $pmonth = date('F', mktime(0,0,0,$imonth,1,$y->pyear));
                    $smonth = ($imonth<10) ? '0' . $imonth : $imonth;
                    
                    $monthly_cost = $this->total_cost_report_model->get_total_payment($y->pyear,$smonth);
                    array_push($amonthly_cost, $monthly_cost);
                    $this->phpexcel->getActiveSheet()->setCellValue($salpha.$imc_cell, $monthly_cost);
                    $this->phpexcel->getActiveSheet()->getStyle($salpha.$imc_cell)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $imc_cell++;
                }
                
                //get the total payment for each year
                $total_cost = $this->total_cost_report_model->get_total_cost($y->pyear);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'15', $total_cost);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'15')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                //gets the annual savings for each year
                array_push($year, $y->pyear);
                $as_year = $y->pyear - 1;
                $syear = (string)$as_year;
               
                if(!in_array($syear, $year)){
                   $this->phpexcel->getActiveSheet()->setCellValue($salpha.'16', '-');
                   $this->phpexcel->getActiveSheet()->getStyle($salpha.'16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }else{
                    $annual_savings = $this->total_cost_report_model->get_annual_savings($y->pyear);
                    $this->phpexcel->getActiveSheet()->setCellValue($salpha.'16', $annual_savings);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'16')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                
                //gets the monthly average for each year
                $monthly_average = $this->total_cost_report_model->get_monthly_average($y->pyear);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'17', $monthly_average);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'17')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                //gets yearly average for each year
                if(!in_array($syear, $year)){
                   $this->phpexcel->getActiveSheet()->setCellValue($salpha.'18', '-');
                   $this->phpexcel->getActiveSheet()->getStyle($salpha.'18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }else{
                    $ya_ctr++;
                    $yearly_average = $this->total_cost_report_model->get_yearly_average($y->pyear,$ya_ctr);
                    $this->phpexcel->getActiveSheet()->setCellValue($salpha.'18', $yearly_average);
                    $this->phpexcel->getActiveSheet()->getStyle($salpha.'18')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                
                //gets 1st quarterly average for each year
                $aq1_months = array('01','02','03');
                $quarterly_average1 = $this->total_cost_report_model->get_quarterly_average1($y->pyear,$aq1_months);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'19', $quarterly_average1);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'19')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                //gets 2nd quarterly average for each year
                $aq2_months = array('04','05','06');
                $quarterly_average2 = $this->total_cost_report_model->get_quarterly_average2($y->pyear,$aq2_months);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'20', $quarterly_average2);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'20')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                //gets 3rd quarterly average for each year
                $aq3_months = array('07','08','09');
                $quarterly_average3 = $this->total_cost_report_model->get_quarterly_average3($y->pyear,$aq3_months);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'21', $quarterly_average3);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'21')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                //gets 4th quarterly average for each year
                $aq4_months = array('10','11','12');
                $quarterly_average4 = $this->total_cost_report_model->get_quarterly_average4($y->pyear,$aq4_months);
                $this->phpexcel->getActiveSheet()->setCellValue($salpha.'22', $quarterly_average4);
                $this->phpexcel->getActiveSheet()->getStyle($salpha.'22')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
                $ialpha++;
                $this->phpexcel->getActiveSheet()->getColumnDimension($salpha)->setWidth(20);
            }
            
            $last_salpha = $salpha;
            
            //gets current monthly average cost
            $current_monthly_average = $this->total_cost_report_model->get_current_monthly_average();
            $this->phpexcel->getActiveSheet()->setCellValue($last_salpha.'24', $current_monthly_average);
            $this->phpexcel->getActiveSheet()->getStyle($salpha.'24')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'24')->getFont()->setBold(true);
            
            //gets previous - current monthly average cost
            $previous_current_monthly_average = $this->total_cost_report_model->get_previous_current_monthly_average();
            $this->phpexcel->getActiveSheet()->setCellValue($last_salpha.'25', $previous_current_monthly_average);
            $this->phpexcel->getActiveSheet()->getStyle($salpha.'25')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'25')->getFont()->setBold(true);
            
            //gets previous - current quarterly average cost
            $previous_current_quarterly_average = $this->total_cost_report_model->get_previous_current_quarterly_average();
            $this->phpexcel->getActiveSheet()->setCellValue($last_salpha.'26', $previous_current_quarterly_average);
            $this->phpexcel->getActiveSheet()->getStyle($salpha.'26')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'26')->getFont()->setBold(true);
            
            //adjust column width
            $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            
        }  
            
            //merge cells
            $this->phpexcel->getActiveSheet()->mergeCells('B1:'.$last_salpha.'1');
            $this->phpexcel->getActiveSheet()->mergeCells('A3:A5');
            $this->phpexcel->getActiveSheet()->mergeCells('A6:A8');
            $this->phpexcel->getActiveSheet()->mergeCells('A9:A11');
            $this->phpexcel->getActiveSheet()->mergeCells('A12:A14');
            
            $this->phpexcel->getActiveSheet()->getStyle('A3:A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A6:A8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A9:A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle('A12:A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
            // //set font size
            // $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(20);
            
            //set aligment to center for that merged cell
            $this->phpexcel->getActiveSheet()->getStyle('B1:'.$last_salpha.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
            //set borders
            $this->phpexcel->getActiveSheet()->getStyle('A1:'.$last_salpha.'1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'1:'.$last_salpha.'26')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle('A26:'.$last_salpha.'26')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle('A3:'.$last_salpha.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            
            $this->phpexcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A1:A3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('B1:'.$last_salpha.'1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $this->phpexcel->getActiveSheet()->getStyle('B2:'.$last_salpha.'3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A4:'.$last_salpha.'26')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle('A1:A26')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle($last_salpha.'1:'.$last_salpha.'26')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle('A3:'.$last_salpha.'3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
            $this->phpexcel->getActiveSheet()->getStyle('A26:'.$last_salpha.'26')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
           
            //download and save exported file
            $filename='Total Cost Report' . '.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache
                         
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
            
    }
}