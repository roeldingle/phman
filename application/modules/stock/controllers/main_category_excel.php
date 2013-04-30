<?php
class Main_category_excel extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('catman_subs_model');
        $this->load->library('site/PHPExcel/PHPExcel');
    }
    
    public function result_export_to_excel()
    {
        $istart         = 2;
        $category_page  = $this->input->post('category_page', TRUE);
        
        if ($category_page == 'hardware') {
            $main_catId     = 9;
            $file_title     = 'Hardware';
            $model_version = 'Model';
        } else if ($category_page == 'accessories') {
            $main_catId     = 10;
            $file_title     = 'Accessories';
            $model_version  = 'Model';
        } else if ($category_page == 'software') {
            $main_catId     = 11;
            $file_title     = 'Software';
            $model_version  = 'Version';
        } else {
            $main_catId     = 12;
            $file_title     = 'Furnitures';
            $model_version  = 'Model';
        }
        
        
        $aResult = $this->catman_subs_model->record_list_model($main_catId);
        
        // --echo '<pre>';
        // --print_r($aResult['rows']);
        
        $this->phpexcel->setActiveSheetIndex(0); 

        // --Sets title of your spreadsheet
        $this->phpexcel->getActiveSheet()->setTitle($file_title.' Management ' . date('Y-m-d',time())); 

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Category');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', $model_version);
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Registered Date');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Last Updated');
      
        if ($aResult) {
            foreach ($aResult['rows'] as $row) {
                $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, $row->tssc_name);

                if ($category_page == 'software') {
                    $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->tsit_version);
                } else {
                    $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->tsit_model);
                }

                $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->reg_date);
                $this->phpexcel->getActiveSheet()->setCellValue('D'.$istart, $row->last_update);
                // --echo $istart.'<br />';
                // --echo $row->tssc_name.' - '.$row->tsit_model.' - '.$row->reg_date.' - '.$row->last_update.'<br />';
                $istart++;
            }
        }
        
        // --Changes the text style on the specified cell
        $title  = array('A1','B1','C1','D1');
        foreach ($title as $k=>$v){
            $this->phpexcel->getActiveSheet()->getStyle($v)->getFont()->setSize(10);
            $this->phpexcel->getActiveSheet()->getStyle($v)->getFont()->setBold(true);
        }

        // --Adjusts column width
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        
        // --This is for downloading and saving the excel file
        $filename=$file_title.' Management ' . '.xls'; // --Save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel'); // --Mime type
        header('Content-Disposition: attachment;filename="'.$file_title." Management - " . date('Y-m-d',time()).'.xls'); // --Tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        // --Save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        // --if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
        
        // --Force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');   
    }
}