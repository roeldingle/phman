<?php

class Employee extends MX_Controller
{
   private $module_name ='hr';

   public function __construct()
   {
      parent::__construct();
      $this->load->library('site/PHPWord/PHPWord');
      $this->load->model("hr_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->module('settings/logs');

      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name . "/employee/employee_list","cache"=>false));
      $this->unlinkimage();
   }
   
   private function unlinkimage(){
   
        $afile = glob (APPPATH . 'modules/hr/uploads/temp/'.md5($_SERVER['REMOTE_ADDR'])."-*");
         if($afile){
            foreach($afile as $rows){
               unlink($rows);
            }
         }
   }
   
   public function index()
   {
         $adata = array();
         $adata['title'] = "Hr Management";
         $adata['module_name'] = $this->module_name;
         $aOptions  = array(
               'source' => 'site/libs/require',
               'attributes'=>array('data-main' => $this->environment->assets_path.'site/js/apps/r_setup')
         );
         $this->app->use_js($aOptions);
         
         $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
         $RowPerPage = isset($_GET['row']) ? $_GET['row'] : '50';
         $Curpage = isset($_GET['page']) ? $_GET['page']:'1';
         
         $oresult = $this->hr_model->getEmployee_list($keyword,$RowPerPage,$Curpage);
         $adata['employee_list'] = $oresult->result();
         
         $itotal_row =  $this->hr_model->getTotal_rows($keyword);
         $alimit = $this->common->sql_limit($itotal_row,$RowPerPage);
         $adata['pager'] = $this->common->pager($itotal_row,$RowPerPage,array('active_class'=>'current'));
         
         $this->template->header();
         $this->template->sidebar();
         $this->template->breadcrumbs();
         $this->app->content($this->module_name . '/hr_management/index',$adata);
         $this->template->footer();
         $this->logs->set_log("Employee List","READ");  
         
   }
   public function image_uploader()
   {
               
      if($this->environment->module_path .'employee'==isset($_SERVER["HTTP_REFERER"]))
      {
         echo '
               <html></head><script src="'.$this->environment->assets_path.'site/js/libs/jquery-1.8.0.min.js"></script>
               <script src="'.$this->environment->assets_path.'hr/js/employee/uploader.js"></script> 
               </head>
            ';
      
         echo '   <body><form action="'.$this->environment->module_path.'employee/upload_image"  enctype="multipart/form-data" method="post" id="form_upload">
                  <div class="employee_image" style="width:200px;display:inline-block">
                     <div class="employee_image_box" style="position:relative;margin:0;padding:10px;border:1px solid #cecece;display:inline-block">
                        <span class="emp_img_con"></span>
                        <span class="default_img" style="position:relative;width:170px;height:170px;background:url('.$this->environment->assets_path.'site/images/blank_img.jpeg'.')center center no-repeat;display:inline-block"></span>
                     </div>
                     <input type="file" name="file" id="file" /> 
                     <input type="hidden" name="new_filename" id="new_filename" value="" /> 
                     <input type="hidden" name="save_filename" value="" />
                  <div>
                  </form>
                  </body></html>
               ';
      }else{
         show_404();
      }              
   }
   
   public function upload_image()
   {
      if($this->environment->module_path .'employee'==isset($_SERVER["HTTP_REFERER"]))
      {
         $filename = $_POST['new_filename'];
         
         $save_filename = md5($_SERVER['REMOTE_ADDR']).'-'.$filename;

          echo '
                  <html><head><script src="'.$this->environment->assets_path.'site/js/libs/jquery-1.8.0.min.js"></script>
                  <script src="'.$this->environment->assets_path.'hr/js/employee/uploader.js"></script></head><body> 
               '; 
               
         if ($_FILES["file"]["size"] > 1000000){
            echo '<font color="#ff0000">The file exceeds 1MB!</font>';
             $uploaded=false;
         }else  if(($_FILES["file"]["type"]!="image/gif") && ($_FILES["file"]["type"] != "image/jpeg") && ($_FILES["file"]["type"] != "image/jpg") && ($_FILES["file"]["type"] != "image/png")){
            echo "<font color='#ff0000'>Incorrect File Type</font>";
            $uploaded=false;
         }else{
            move_uploaded_file($_FILES["file"]["tmp_name"],
            APPPATH . 'modules/hr/uploads/temp/'.$save_filename );
            $uploaded=true;
         }
         
         $img_src =  $uploaded==true ? '<img src="'. $this->environment->getfile_path .'hr/uploads/temp/'.$save_filename .'?" style="position:absolute;width:170px;height:170px;z-index:99"/>' : '';
        
         echo '<form action="'.$this->environment->module_path.'employee/upload_image"  enctype="multipart/form-data" method="post" id="form_upload">
               <div class="employee_image" style="width:200px;display:inline-block">
                  <div class="employee_image_box" style="position:relative;margin:0;padding:10px;border:1px solid #cecece;display:inline-block">
                     <span class="emp_img_con">'. $img_src .'</span>
                     <span class="default_img" style="position:relative;width:170px;height:170px;background:url('.$this->environment->assets_path.'site/images/blank_img.jpeg'.')center center no-repeat;display:inline-block"></span>
                  </div>
                  <input type="file" name="file" id="file" value=""/> 
                  <input type="hidden" name="new_filename" id="new_filename" value="" />
                  <input type="hidden" name="save_filename" value="'.$save_filename.'" />
                  </form>
                  </body></html>
         ';
      }else{
         show_404();
      }      
   }
   

   public function export_to_word()
   {      
      if(!isset($_GET['emp_id'])){
            show_404();
      }else{
            
      $emp_idx = $_GET['emp_id'];
      
      $this->db->select('*');
      $this->db->from('tbl_employee');
      $this->db->where('te_idx',$emp_idx);
      $this->db->join('tbl_employee_company_record', 'tecr_te_idx = te_idx','left');
      $this->db->join('tbl_employee_employment_history', 'teeh_te_idx = te_idx','left');
      $query = $this->db->get()->result();
      
      $load_doc   =  $query[0]->tecr_tet_idx == 1 ? APPPATH . 'modules/hr/assets/template/empdata_freshgrad.docx' : APPPATH . 'modules/hr/assets/template/empdata.docx';
      
      $PHPWord = new PHPWord();

      $document = $PHPWord->loadTemplate($load_doc);

      $document->setValue('Employee_name', $query[0]->te_fname.' '.$query[0]->te_mname.' '.$query[0]->te_lname);
      $document->setValue('Address', $query[0]->te_address);
      $document->setValue('Contact_no', $query[0]->te_contact_number);
      $document->setValue('email_add', $query[0]->te_email_address);
      $document->setValue('bday', $query[0]->te_bdate);
      $document->setValue('status', $query[0]->te_status);
      $document->setValue('gender', $query[0]->te_gender=='f'?'Female':'Male');
      $document->setValue('emp_id', $query[0]->te_employee_id);
      
      $this->db->select('*');
      $this->db->from('tbl_position');
      $this->db->where('tp_idx',$query[0]->tecr_tp_idx);
      $query_pos = $this->db->get()->result();
      $document->setValue('position', $query_pos[0]->tp_position);
      
      $this->db->select('*');
      $this->db->from('tbl_department');
      $this->db->where('td_idx',$query[0]->tecr_td_idx);
      $query_dept = $this->db->get()->result();
      $document->setValue('department', $query_dept[0]->td_dept_name);
      
      $this->db->select('*');
      $this->db->from('tbl_employee_work_status');
      $this->db->where('tws_idx',$query[0]->tecr_tews_work_status);
      $query_tws = $this->db->get()->result();
      $document->setValue('work_stat', $query_tws[0]->tws_status_name);
      
      $document->setValue('hired_date', $query[0]->tecr_date_started);
      $document->setValue('reg_date', $query[0]->tecr_probationary_date_ended=='0000-00-00'?'n/a':$query[0]->tecr_probationary_date_ended);
      $document->setValue('res_date', $query[0]->tecr_date_ended=='0000-00-00'?'n/a':$query[0]->tecr_date_ended);
      
      $this->db->select('*');
      $this->db->from('tbl_employee_employment_type');
      $this->db->where('tet_idx',$query[0]->tecr_tet_idx);
      $query_et = $this->db->get()->result();
      $document->setValue('emp_type', $query_et[0]->tet_type_name);
      
      $document->setValue('sss', $query[0]->tecr_sss);
      $document->setValue('tin', $query[0]->tecr_tin);
      $document->setValue('philhealth', $query[0]->tecr_philhealth);
      $document->setValue('pag_track', $query[0]->tecr_pag_track);
      $document->setValue('pag_mid', $query[0]->tecr_pag_midno);
      $document->setValue('bank_name', $query[0]->tecr_bank_name);
      $document->setValue('bank_account', $query[0]->tecr_bank_account_number);
      $document->setValue('school', $query[0]->te_school==''?'n/a':$query[0]->te_school);
      $document->setValue('school_location', $query[0]->te_school_add==''?'n/a':$query[0]->te_school_add);
      $document->setValue('course', $query[0]->te_course==''?'n/a':$query[0]->te_course);
      $document->setValue('inclu_date', $query[0]->te_inc_dates==''?'n/a':$query[0]->te_inc_dates);
      $document->setValue('year_grad', $query[0]->te_year_grad==''?'n/a':$query[0]->te_year_grad);
      $document->setValue('degree', $query[0]->te_certi_deg==''?'n/a':$query[0]->te_certi_deg);
      $document->setValue('degree_comp', $query[0]->te_certi_deg_completed==''?'n/a':$query[0]->te_certi_deg_completed);
      $document->setValue('exp_year', $query[0]->teeh_tot_year=='0'?'n/a':$query[0]->teeh_tot_year);
      $document->setValue('exp_month', $query[0]->teeh_tot_month=='0'?'n/a':$query[0]->teeh_tot_month);
      $document->setValue('emp_period_from', $query[0]->teeh_employ_from==''?'n/a':$query[0]->teeh_employ_from);
      $document->setValue('emp_period_to', $query[0]->teeh_employ_to==''?'n/a':$query[0]->teeh_employ_to);
      $document->setValue('company_name', $query[0]->teeh_company_name==''?'n/a':$query[0]->teeh_company_name);
      $document->setValue('prev_pos', $query[0]->teeh_position==''?'n/a':$query[0]->teeh_position);
      $document->setValue('respon', $query[0]->teeh_responsibility==''?'n/a':$query[0]->teeh_responsibility);
      $document->setValue('prev_cn', $query[0]->teeh_contact==''?'n/a':$query[0]->teeh_contact);
      $document->setValue('prev_add', $query[0]->teeh_address==''?'n/a':$query[0]->teeh_address);
      $document->setValue('prev_start', $query[0]->teeh_salary_start=='0'?'n/a':$query[0]->teeh_salary_start);
      $document->setValue('prev_last', $query[0]->teeh_salary_last=='0'?'n/a':$query[0]->teeh_salary_last);
      $document->setValue('reason_living', $query[0]->teeh_reason_leave==''?'n/a':$query[0]->teeh_reason_leave);
      $document->setValue('notify_name', $query[0]->te_notify_name);
      $document->setValue('notify_rel', $query[0]->te_notify_rel);
      $document->setValue('notify_cn1', $query[0]->te_notify_no);
      $document->setValue('notify_cn2', $query[0]->te_notify_no2==''?'n/a':$query[0]->te_notify_no2);
      $document->setValue('notify_add', $query[0]->te_notify_add);
      
      $emp_img_path = $query[0]->te_image_path=='' ? APPPATH . 'modules/hr/uploads/emp_image/111111111.jpg' : APPPATH . 'modules/hr/uploads/emp_image/'.$query[0]->te_image_path;
      $emp_img = (file_exists($emp_img_path)) ? $emp_img_path : APPPATH . 'modules/site/assets/images/blank_img.jpeg';
      $document->replaceImage($emp_img,'image1.jpeg');

        $file= $query[0]->te_lname.', '.$query[0]->te_fname .'.docx'; //save our workbook as this file name

        $document->save(APPPATH . 'modules/hr/assets/template/'.$file);
    
       if(!$file) {        
           die('file not found'); 
       } 
       else {     
        header('Content-Type: application/vnd.ms-word'); //mime type
        header('Content-Disposition: attachment;filename="'.$file.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
  

           readfile(APPPATH . 'modules/hr/assets/template/'.$file); 
       }
       unlink(APPPATH . 'modules/hr/assets/template/'.$file);
       exit;
   }
   
   }
}