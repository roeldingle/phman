<?php

class Dashboard extends MX_Controller
{
   private $_ilimit = 5;
   
   public function __construct()
   {
      parent::__construct();
      
      $this->load->library('dashboard/dashboard_common');
      // Load core module app
      $this->load->module("core/app");      
      // Load site module template
      $this->load->module("site/template");      
      // Load dashboard_model model
      $this->load->model('dashboard_model');
      // Load hr_model model
      $this->load->model('hr_model');
      // Load stock_model model
      $this->load->model('stock_model');
      // Load expense_model model
      $this->load->model('expense_model');
      // Load user_activities_model model
      $this->load->model('settings/user_activities_model');
      
      // Load jquery.cookie.js
      $this->app->use_js(array("source"=>"site/libs/jquery.cookie","cache"=>false));      
      // Load table.sorter.js
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));      
      // Load dashboard.js
      $this->app->use_js(array("source"=>"dashboard/dashboard","cache"=>true));      
      // Load dashboard.css
      $this->app->use_css(array("source"=>"dashboard/dashboard","cache"=>true));
   }

   public function index()
   {  
      $adata = array();
      $sload_default_html = "";
      // Set allowed row number
      $aallowed = array(5,10,20,30);
      $irow = $this->_ilimit;
      $ilogs_row = 5;
      $iactivities_row = 5;
      $istock_row = 5;
      $stype = "logs";
      $swhere = "";
      $sdashboard_settings = "";
      $asequence_arrangement = array();
      // Gets user settings for dashboard
      $auser_settings = $this->dashboard_model->get_user_settings();      
      if( !$auser_settings ) {
         // Used default settings for dashboard if there is no user settings
         $adashboard_settings = $this->dashboard_model->get_dashboard_settings();
         if($adashboard_settings) {
            $sdashboard_settings = $adashboard_settings->tds_sequence;
         } else {
            $sdashboard_settings = "";
         }
      } else {
         $sdashboard_settings = $this->dashboard_model->get_user_settings()->tdus_sequence;
         if( !$sdashboard_settings ) {
            $sdashboard_settings = $this->dashboard_model->get_dashboard_settings()->tds_sequence;         
         }
      }
      
      if( $sdashboard_settings ) {
      
         $asequence_list = $this->dashboard_common->get_sequence($sdashboard_settings);

         // Create sequence in array
         // $asequence = explode(',', $sdashboard_settings);
         
         // Filter row validation
         if( $this->input->get('row') ) {
            $irow = filter_var( $this->input->get('row'), FILTER_VALIDATE_INT );
            if ( $irow ) {
               if(!in_array( $irow, $aallowed ) ) {
                  $irow = $this->_ilimit;
               }
            } else {
               $irow = $this->_ilimit;
            }         
         }
         // Filter type of view
         if( $this->input->get('type') ) {
            if( $this->input->get('type') == 'logs' || $this->input->get('type') == 'activities' ||$this->input->get('type') == 'stock' ) {
               $stype = $this->input->get('type');
            }
         }
         // Set Recent Logs row number
         if( $stype == 'logs' ) {
            $ilogs_row = $irow;
         }
         // Set Recent Activities row number
         if( $stype == 'activities' ) {
            $iactivities_row = $irow;
         }
               // Set Stock Management row number
         if( $stype == 'stock' ) {
            $istock_row = $irow;
         }
         
         if( $this->session->userdata('usergradeid') != '000001' ) {       
            $swhere = " WHERE tug.tug_idx = '{$this->session->userdata('usergradeid')}'";
         }
         // List of dashboard items "Recent Logs", "Recent Activities", "HR Management Summary"
         // "Stock Management Summary", "Expense Management Summary"
         $adashboard_id = array(
            "recent_logs"        => $this->_get_recent_logs( $swhere, $ilogs_row, $stype, (!isset($asequence_list['recent_logs'])) ? 'on' : $asequence_list['recent_logs']),
            "recent_activities"  => $this->_get_recent_activities( $swhere, $iactivities_row, $stype,(!isset($asequence_list['recent_activities'])) ? 'on' : $asequence_list['recent_activities'] ), 
            "hr_management"      => $this->_get_hr_management((!isset($asequence_list['hr_management'])) ? 'on' : $asequence_list['hr_management']), 
            "expense_management" => $this->_get_expense_management((!isset($asequence_list['expense_management'])) ? 'on' : $asequence_list['expense_management']), 
            "stock_management"   => $this->_get_stock_management($stype, (!isset($asequence_list['stock_management'])) ? 'on' : $asequence_list['stock_management'])
         );
         
         foreach( $asequence_list as  $key => $rows ) {
            if( array_key_exists( $key, $adashboard_id ) ) {
               $asequence_arrangement[] = $adashboard_id[$key];
            }
         }
      } else {
         $sload_default_html = $this->_get_default_html();
      }
      $adata['asequence_arrangement'] = $asequence_arrangement;            
      $adata['sload_default_html'] = $sload_default_html;            
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content('dashboard/dashboard',$adata);
      $this->template->footer();
   }
   
	/**
	 * _get_hr_management()
	 * Displays HR Management Summary 
	 * @access private
	 * @param datatype none
	 * @return string
	 */   
   private function _get_hr_management( $sshow_hide )
   {
      $adata = array();
      $ahired = array();
      $aretired = array();
      $atardiness = array();
      $adepartment_list = array();
      $adepartment = $this->hr_model->get_department();
      // Initialize total current employees
      $itotal_current_employees = 0;
      // Initialize probationary current employees
      $itotal_probationary_employees = 0;
      // Initialize new employees
      $itotal_new_employees = 0;
      // Initialize absences
      $itotal_absences = 0;
      
      // Loop for "Current Number of Employees" and "Probationary Employees"
      foreach( $adepartment as $rows ) {
         if( !empty( $rows->td_dept_name ) ) {
         
            // Get total count of current employees on each department
            $acount_current_employees = $this->hr_model->get_current_employees( $rows->td_idx );
            
            // Get total count of probationary employees on each department
            $icount_probationary_employees = $this->hr_model->get_probationary_employees( $rows->td_idx );
            
            // Get total count of new employees for last 30 days
            $icount_new_employees = $this->hr_model->get_new_employees( $rows->td_idx );
            
            // Get total count of new employees for last 30 days
            $icount_absences = $this->hr_model->get_absences( $rows->td_idx );
            
            // Add count of current employees
            // $itotal_current_employees += $icount_current_employees;
            
            // Add count of probationary employees
            $itotal_probationary_employees += $icount_probationary_employees;
            
            // Add count of new employees
            $itotal_new_employees += $icount_new_employees;      
            $itotal_absences += $icount_absences;
            $adepartment_list[] = array(
               'department_idx' => $rows->td_idx,
               'department_name' => $rows->td_dept_name,
               'total_current_employees' => count($acount_current_employees),
               'total_probationary_employees' => $icount_probationary_employees,
               'total_new_employees' => $icount_new_employees,
               'total_absences' => $icount_absences
            );
         }
      }
      // First 6 months array
      $amonth = $this->dashboard_common->get_given_month(6);
      
      // Total "Hired Employees"
      
      $itotal_hired = 0;
      // Total "Retired Employees"
      $itotal_retired = 0;      
      
      // Total "Tardniness"
      $itotal_tardiness = 0;      
      foreach( $amonth as $rows ) {
         // Get total "Hired employees"
         $itotal_hired_employees = $this->hr_model->get_hired_employees("{$rows['year']}-{$rows['month']}");
         
         // Get total "Retired Employees"
         $itotal_retired_employees = $this->hr_model->get_retired_employees("{$rows['year']}-{$rows['month']}");         
         
         // Get total "Tardinesss Employees"
         $itotal_tardiness_employees = $this->hr_model->get_tardiness("{$rows['year']}-{$rows['month']}");

         // Array list for "Hired Employees"
         $ahired[] = array(
            'year' => $rows['year'],
            'month' => $rows['month_str'],
            'total' => $itotal_hired_employees
         );         
         // Array list for "Retired Employees"
         $aretired[] = array(
            'year' => $rows['year'],
            'month' => $rows['month_str'],
            'total' => $itotal_retired_employees
         );
         // Array list for "Tardineess"
         $atardiness[] = array(
            'year' => $rows['year'],
            'month' => $rows['month_str'],
            'total' => $itotal_tardiness_employees
         );
                  
         $itotal_hired += $itotal_hired_employees;
         $itotal_retired += $itotal_retired_employees;
         $itotal_tardiness += $itotal_tardiness_employees;
      }
      
      // List of department
      $adata['adepartment'] = $adepartment_list;
      
      // Total "Current Employees"
      $adata['itotal_current_employees'] = $itotal_current_employees;
      
      // Total "Probationary Employees"
      $adata['itotal_probationary_employees'] = $itotal_probationary_employees;      
      
      // Total "New Employees"
      $adata['itotal_new_employees'] = $itotal_new_employees;           
      
      // Total "Absences"
      $adata['itotal_absences'] = $itotal_absences;          
                
      // List of "Hired Employees" on the first six month
      $adata['ahired'] = $ahired;  
      
      // Total "Hired Employees"
      $adata['itotal_hired'] = $itotal_hired;
      
      // List of "Tardiness" on the first six month
      $adata['atardiness'] = $atardiness;
      
      // Total "Tardiness"
      $adata['itotal_tardiness'] = $itotal_tardiness;     
      
      // List of "Retired Employees" on the first six month
      
      $adata['aretired'] = $aretired;  
      // Total "Retired Employees"
      $adata['itotal_retired'] = $itotal_retired;
      $adata['sshow_hide'] = $sshow_hide;
      // Return html output
      return $this->load->view('hr_management', $adata, TRUE);
   }
   
	/**
	 * _get_stock_management()
	 * Displays Stock Management Summary 
	 * @access private
	 * @param datatype none
	 * @return string
	 */	   
   public function _get_stock_management($stype, $sshow_hide)
   {
      $adata = array();
      $amain_category_list = array();
      $asub_category_list = array();
      $irow = ( $this->input->get('type') === 'stock' ) ? $this->input->get('row') : 5;
      $alimit = array("offset" => 0, "limit" => $irow );
      
      // Get main category list
      $amain_category = $this->stock_model->get_main_category();
      
      // Loop through main category list
      foreach( $amain_category as $rows ) {
         $itotal_item = 0;
         // Get sub category list
         $asub_category = $this->stock_model->get_sub_category( $rows->tsmc_smcid,  $alimit  );    
         // Loop through each subcategory list
         foreach( $asub_category as $rows_sub ) {
            $asub_category_list[$rows->tsmc_smcid][] = array(
               'sub_category_name' => $rows_sub->tssc_name,
               'total_item' => $rows_sub->total_item,
            );
            $itotal_item += $rows_sub->total_item;
         }
         $amain_category_list[] = array(
            'main_idx' => $rows->tsmc_smcid,
            'category_name' => $rows->tsmc_name,
            'total' => $itotal_item
         );         
      }
      $adata['stype'] = $stype;
      $adata['irow'] = $irow;
      $adata['amain_category'] = $amain_category_list;
      $adata['asub_category'] = $asub_category_list;
      $adata['sshow_hide'] =  $sshow_hide;
      // Return html output
      return $this->load->view('stock_management', $adata, TRUE);
   }
   
	/**
	 * _get_recent_logs()
	 * Displays Recent Logs
	 * @access private
	 * @param datatype none
	 * @return string
	 */   
   private function _get_recent_logs($swhere, $ilogs_row, $stype, $sshow_hide )
   {
      $adata = array();
      // Inititiate recent logs report
      $auser_result = array();      
      // Get recent logs data
      $auser_list = $this->dashboard_model->get_user_logs_list( $swhere, $ilogs_row );
      // Loop recent logs data
      foreach( $auser_list as $rows ) {
         $auser_result[] = array(
            'date_created' => $rows->date_created,
            'user_id' => $rows->user_id,
            'full_name' => $rows->full_name,
            'position' => $rows->position,
            'user_level' => $rows->user_level
         );
      }
      $adata['irow'] = $ilogs_row;
      $adata['stype'] = $stype;      
      // Assign recent logs list array      
      $adata['auser_list'] = $auser_result;
      $adata['sshow_hide'] =  $sshow_hide;      
      // Return html output
      return $this->load->view("recent_logs", $adata, TRUE);
   }
   
	/**
	 * _get_recent_activities()
	 * Displays Recent Activities
	 * @access private
	 * @param datatype none
	 * @return string
	 */     
   private function _get_recent_activities( $swhere, $iactivities_row, $stype, $sshow_hide )
   {
      $adata = array();
      // Initiate recent activities array
      $aactivies_result = array();   
      // Get total rows of recent activities
      $itotal_rows = $this->user_activities_model->get_list_count();
      // Generate LIMIT OFFSET for recent activities listing
      $alimit = array("offset" =>0, "limit" => $iactivities_row);
      // Get recent activities data
      $aactivities_list = $this->dashboard_model->get_logs_list($swhere,$alimit);       
      // Loop recent activities data
      foreach( $aactivities_list as $rows ) {
         $aactivies_result[] = array(
            'user' => $rows->user,
            'user_level' => $rows->user_level,
            'position' => $rows->position,
            'message_log' => $rows->message_log,
            'message_from' => $rows->message_from,
            'message_to' => $rows->message_to,
            'date_created' => $rows->date_created
         );
      }
      $adata['irow'] = $iactivities_row;
      $adata['stype'] = $stype;
      // Assign recent activities list array
      $adata['aactivities_list'] = $aactivies_result;
      $adata['sshow_hide'] =  $sshow_hide;
      // Return html output
      return $this->load->view("recent_activities", $adata, TRUE);
   }
   
	/**
	 * _get_expense_management()
	 * Displays Expense Managament
	 * @access private
	 * @param datatype none
	 * @return string
	 */   
   private function _get_expense_management( $sshow_hide )
   {
      $adata = array();
      $aexpense_list = array();
      $amonth = $this->dashboard_common->get_given_month(3);
      $icount_planned_budget = 0;
      $icount_expenses = 0;
      foreach( $amonth as $rows ) {
         $iplanned_budget_total = $this->expense_model->get_planned_budget($rows['year'], $rows['month_str_comp']);
         $iexpenses_total = $this->expense_model->get_expenses($rows['year'] . "-" . $rows['month']);
         $iplanned_budget_total = ( empty( $iplanned_budget_total ) ) ? 0.00 : $iplanned_budget_total;
         $iexpenses_total = ( empty( $iexpenses_total ) ) ? 0.00 : $iexpenses_total;
         $icount_planned_budget += $iplanned_budget_total;
         $icount_expenses += $iexpenses_total;
         $aexpense_list[] = array(
            'year' => $rows['year'],
            'month' => $rows['month_str'],
            'iplanned_budget' => number_format( $iplanned_budget_total, 2, '.', ','),
            'iexpenses' => number_format( $iexpenses_total, 2, '.', ','),
            'idifference' => number_format( $iplanned_budget_total - $iexpenses_total , 2, '.', ',')
         );
      }
      $adata['aexpense'] = $aexpense_list;
      $adata['iplanned_budget_total'] = number_format($icount_planned_budget, 2, '.', ',');
      $adata['iexpenses_total'] = number_format( $icount_expenses, 2, '.', ',');
      $adata['idifference_total'] = number_format( $icount_planned_budget - $icount_expenses, 2, '.', ',');
      $adata['sshow_hide'] =  $sshow_hide;

      // Return html output
      return $this->load->view('expense_management',$adata, TRUE);
   }
   
   private function _get_default_html()
   {
      $adata = array();
      return $this->load->view("dashboard/default", $adata, TRUE);
   }
         
   public function myaccount()
   {
      $this->load->module("dashboard/".__FUNCTION__);
   }
}