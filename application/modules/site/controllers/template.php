<?php
class Template extends MX_Controller
{
   private $sModule;
   
   public function __construct()
   {
      parent::__construct();
          
      $this->load->module("login/login_checker");
      $this->load->module("core/app");
      $this->load->model('site_model');      

      $this->app->use_css(array("source" =>"site/style","cache"=>true));
      $this->app->use_js(array("source" =>"site/jquery-vertical-accordion-menu/jquery.cookie","cache"=>false));
      $this->app->use_js(array("source" =>"site/jquery-vertical-accordion-menu/jquery.dcjqaccordion.2.7","cache"=>false));
      $this->app->use_js(array("source" =>"site/jquery-vertical-accordion-menu/jquery.hoverIntent.minified","cache"=>true));
      $this->app->use_js(array("source" =>"site/libs/jquery.qtip-1.0.0-rc3.min","cache"=>true));
      $this->app->use_js(array("source" =>"site/site"));      
   }
   
    public function header()
    {
        $this->app->header('site/header');
    }
    
    public function footer()
    {
        $this->app->footer('site/footer');
    }
    
    public function sidebar($sTempPath = null)
    {
        if($sTempPath != null){
            $this->app->content($sTempPath);
        }else{
            $this->app->content('site/sidebar');
        }
    } 
    
    public function menu()
    {
      $sData = '';
      $aUrl = explode('/',$_SERVER['REQUEST_URI']);
      $usergradeid = $this->session->userdata('usergradeid');
      $usergradedata = $this->site_model->get_select_data(null,'tbl_user_grade',array('tug_idx',$usergradeid),'row');
      $aMenu = $this->site_model->get_menu_data($usergradedata['tug_tm_idx']);
      
      if($usergradedata['tug_idx'] == '000008'){
        unset($aMenu[4]); #for settings
      }
      
      $aAccessModules = array();
      foreach($aMenu as $k=>$v){
        array_push($aAccessModules,$v['tm_dirname']);
      }
      
      $sData .= '<div class="mnb_bg center"><ul class="mnb nl ar mainmenu">';
      
        if(in_array($this->uri->segment(1), $aAccessModules) != true){
            show_404();
        }else{
            foreach($aMenu as $key=>$val){
                $sCurrentPage = ($this->uri->segment(1) == $val['tm_dirname']) ? 'current' : '';
                $sData .= '<li class="fnt '.$sCurrentPage.'"><a href="'.base_url().$val['tm_dirname'].'" title="'.ucwords($val['tm_desc']).'" >'.ucwords($val['tm_label']).'</a></li>';

            }
            $sData .= '</div>';

            return $sData;
        }
      
    }   
    
    public function side_submenu(){
        /*set variables*/
        $sData = '';
        /*remove the ? for get variables*/
        if(preg_match('/\?/', $_SERVER['REQUEST_URI'])) {
            $aUrl = explode('?',$_SERVER['REQUEST_URI']);
            $aUrl = explode('/',$aUrl[0]);
        }else{
            $aUrl = explode('/',$_SERVER['REQUEST_URI']);
        }
        $aSubMenu = $this->site_model->get_submenu_data($aUrl[1]);
        
        if(!empty($aSubMenu)){
        
            $sData .= '<div class="side_box">';#main container
                $sData .= '<ul class="smb fnt nl np nm sidebar-menu no-display">';#ul container
                
                foreach($aSubMenu as $key=>$val){
                    $sCurrentCss = (isset($aUrl[2]) && $aUrl[2] == $val['tsu_action']) ? 'class="current_sidebar"': '';#give css style for current menu selected
                    
                    $aSecondSubMenu = $this->site_model->get_select_data(null,'tbl_submenu',array('tsu_sub_idx',$val['tsu_idx']));
                    
                    if(!empty($aSecondSubMenu)){
                        $sData .= '<li>';#start li for main menu
                    }else{
                        $sData .= '<li '.$sCurrentCss.'>';#start li for main menu
                    }
                    
                    if(!empty($aSecondSubMenu)){
                        $sData .= '<dl class="smb_sub nl np">';#dl container
                        $sData .= '<dt>';
                        
                        $sCurrentCss = ($sCurrentCss == 'class="current_sidebar"') ? 'class="active"' : '';
                        
                        if($val['tsu_redirect'] == "0"){
                            $sData .= '<a href="#'.$val['tsu_action'].'" title="'.ucwords($val['tsu_desc']).'" alt="'.$val['tsu_action'].'" '.$sCurrentCss.' >'.$val['tsu_label'].'</a>';
                        }else{
                            $sData .= '<a href="'.$this->environment->module_path.$val['tsu_action'].'" title="'.ucwords($val['tsu_desc']).'" alt="'.$val['tsu_action'].'" '.$sCurrentCss.' >'.$val['tsu_label'].'</a>';
                        }
                        $sData .= '</dt>';
                        /*loop the second submenu data*/
                        foreach($aSecondSubMenu as $k=>$v){
                            $sCurrentCss = (isset($aUrl[3]) && $aUrl[3] == $v['tsu_action'] && $aUrl[2] == 'category_management') ? 'class="current_sidebar"': '';
                           
                            $sData .= '<dd '.$sCurrentCss.'>';
                            if($v['tsu_redirect'] == "0"){
                                $sData .= '<a href="#'.$v['tsu_action'].'" title="'.ucwords($v['tsu_desc']).'" alt="'.$v['tsu_action'].'" >'.$v['tsu_label'].'</a>';
                            }else{
                                $sData .= '<a href="'.$this->environment->module_path.$val['tsu_action'].'/'.$v['tsu_action'].'" title="'.ucwords($v['tsu_desc']).'" alt="'.$v['tsu_action'].'" >'.$v['tsu_label'].'</a>';
                            }
                            $sData .= '</dd>';
                        }
                      $sData .= '</dl>';
                    }else{
                        //$sData .= $sCurrentCss.'>';
                        if($val['tsu_redirect'] == "0"){
                            $sData .= '<a href="#'.$val['tsu_action'].'" title="'.ucwords($val['tsu_desc']).'" >'.$val['tsu_label'].'</a>';
                        }else{
                            $sData .= '<a href="'.$this->environment->module_path.$val['tsu_action'].'" title="'.ucwords($val['tsu_desc']).'" >'.$val['tsu_label'].'</a>';
                        }
                    }
                    
                    $sData .= '</li>';#end li for main menu
                }
                $sData .= '</ul>';#end ul container
                
            $sData .= '</div>';#end main container
        }
        return $sData;
 
    }

    public function breadcrumbs()
    {
      $adata['breadcrumbs'] = $this->generate_breadcrumbs();
      $this->app->content('site/breadcrumbs',$adata);
    }      
    
    public function login_status()
    {
      $userid = $this->session->userdata('userid');
      $employeeid = $this->session->userdata('employeeid');
      $usergradeid = $this->session->userdata('usergradeid');
      
      $user_data = $this->site_model->get_select_data(null,'tbl_user',array('tu_idx',$userid),'row');
      $usergrade_data = $this->site_model->get_select_data(null,'tbl_user_grade',array('tug_idx',$usergradeid),'row');
      $employee_data = $this->site_model->get_select_data(null,'tbl_employee',array('te_idx',$employeeid),'row');
      
      $aData['user_grade'] = $usergrade_data['tug_name'];
      
      //$mi = $employee_data['te_mname']=='' ? '' : substr($employee_data['te_mname'],0,1).'.';
      
     // $aData['employee_name'] = ucwords($employee_data['te_fname']).' '.$mi.' '.ucwords($employee_data['te_lname']);
     $aData['employee_name'] = $user_data['tu_username'];
      $this->app->content('site/login-status',$aData);
    }
    
   public function generate_breadcrumbs()
   {
      $auri = $this->uri->segment_array();  
    
      $smodulename = $auri[ 1 ];
      $itotal_segments = $this->uri->total_segments();
      $scontroller_name = $this->router->fetch_class();      
      $smethod_name = $this->router->fetch_method();      
      $asearch = array($scontroller_name,$smethod_name);      
    
      $sbreadrumbs = "";      
      $sother_uri = "";         
      $ilast = 0;      
      $iparent_idx = 0;      
      $subdir = "/";
      $acontroller_temp = array();
      $amethod_temp = array();
      
      foreach( $auri as $key => $val ) {
      
         if ( in_array( $val , $asearch ) ) {
         
            if ( $val == $scontroller_name  && !in_array( $val,$acontroller_temp ) ) {                 
               if ( $key == 3 ) {
                  $subdir =  '/' . $auri[ $key - 1 ] . '/';
               }
               $abreadcrumbs = $this->site_model->get_parent_breadcrumbs($smodulename,$scontroller_name);
               
               if ( $abreadcrumbs ) {               
                  if ( $val == $auri[ $itotal_segments ] ) {
                     $sbreadrumbs .= ' <span>' . $abreadcrumbs->tb_label . '</span> ';               
                  } else {
                     $sbreadrumbs .= ' <a href="' . base_url() . $smodulename . $subdir .  $val .'">' . $abreadcrumbs->tb_label . '</a> ';                                 
                  }                  
                  $iparent_idx = $abreadcrumbs->tb_idx;
                  $ilast = $key;
                  $acontroller_temp[] = $scontroller_name;                  
               } else {               
                  return false;               
               }
            }
            
            if ( $val == $smethod_name && !in_array ( $val,$amethod_temp ) ) {       
               $abreadcrumbs = $this->site_model->get_sub_breadcrumbs( $smodulename , $smethod_name , $iparent_idx );
               if ( $abreadcrumbs ) { 
                  if ( $val == $auri[ $itotal_segments ] ) {
                     $sbreadrumbs .= ' &gt; <span>' . $abreadcrumbs->tb_label . '</span> ';                     
                  
                  } else {
                     $sbreadrumbs .= ' &gt; <a href="' . base_url() . $smodulename . $subdir . $scontroller_name . '/' . $val .'">' . $abreadcrumbs->tb_label . '</a> ';                     
                  }
                  $ilast = $key;
                  $amethod_temp[] = $smethod_name;  
               
               } else {
                  return false;
               }
            }
         }
      }

      if ($ilast) {
         $surl = base_url() . $smodulename . '/' . $scontroller_name . '/' . $smethod_name . '/';         
         for( $i = $ilast + 1 ; $i <= $itotal_segments ; $i++ ) {         
            if ( $i > 2 ) {               
               $sother_uri .= $auri [ $i ] . '/';               
               if ( $i == $itotal_segments ) {               
                  $sbreadrumbs .= ' &gt; <span>' . urldecode ( ucwords ( $auri[ $i ] ) ) . '</span>';               
               } else {               
                  $sbreadrumbs .= ' &gt; <a href="' . base_url() . $smodulename . $subdir . $scontroller_name . '/' . $smethod_name . '/' . $sother_uri .'">' . ucwords($auri[ $i ]) . '</a>';                           
               }
            }   
         }
      }      
      return $sbreadrumbs;      
   }
}