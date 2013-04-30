<?php

class App extends MX_Controller
{
   public $ajs_source = array();
   public $acss_source = array();
   public $awrite_script = array();
   public $awrite_style = array();
   private $bload_jquery = true;
   private $bfileupload = false;
   public $slang_options = "";

   private static $ijs_counter = 4;   
   
   public function __construct()
   {
      parent::__construct();
      $ainfo['assets_path'] = $this->environment->assets_path;
      $ainfo['exec_path'] = $this->environment->exec_path;
      $ainfo['ajax_path'] = $this->environment->ajax_path;
      $ainfo['request_path'] = $this->environment->request_path;
      $ainfo['upload_path'] = $this->environment->upload_path;
      $ainfo['getfile_path'] = $this->environment->getfile_path;
      $ainfo['module_path'] = $this->environment->module_path;
      $ainfo['module_assets_path'] = $this->environment->module_assets_path;
      $this->load->vars($ainfo);
   }

   public function _remap()
   {    
      show_404();
   }

   public function header($sfile_override = "",$aheader_info = array())
   {
      $adata = array();
      $ainfo = array();
      $sview = "";
      $bjquery_ui_css = true;
      if ( $aheader_info !== NULL ) {      
          if ( array_key_exists ( 'jquery_ui_css' , $aheader_info ) ) {             
             if ( $aheader_info ['jquery_ui_css'] === false ) {                
                $bjquery_ui_css = false;             
             }          
          }
      }
      if ( $bjquery_ui_css === true ) {
         $this->acss_source[] = array('sfile' => $this->environment->sjquery_ui_css_url,"cache" => true );                 
      }
      
      $this->acss_source[] = array('sfile' => $this->environment->score_css,"cache" => true );    
      $ainfo['acss_source'] = $this->acss_source;
      $ainfo['awrite_style'] = $this->awrite_style;
      
      $this->load->vars( $ainfo );
      if ( $sfile_override=="" ) {
         return false;
      } else {
        $sview = $this->load->view($sfile_override,$aheader_info,TRUE);
      }
      ob_start();
      echo $sview;
      $soutput = ob_get_clean();
      $scss_output = $this->load->view('css-loader','',TRUE);
      $swrite_style = $this->load->view('write-style','',TRUE);
      $output = preg_replace("|</head>.*?<body>|is", "{$scss_output}{$swrite_style}</head>\n<body>", $soutput);
      $adata['soutput'] = $output;
      $this->load->view('create-header',$adata);
   }

   public function footer($sfile_override = "",$aheader_info = array())
   {
      $ainfo = array();
      $adata = array();
      $sview = "";
      $bjquery = true;
      $bjquery_ui_js = true;

      if ( $aheader_info !== NULL ) {
         if( array_key_exists ( 'jquery' , $aheader_info ) ) {
            if ( $aheader_info['jquery'] === false ) {
               $bjquery = false;
            }
         }
         
         if ( array_key_exists ( 'jquery_ui_js' , $aheader_info ) ) {
            if ( $aheader_info['jquery_ui_js'] === false ) {
               $bjquery_ui_js = false;
            }
         }           
      } 
      
      if ( $bjquery === true ) { 
         $this->ajs_source[0] = array("sfile" => $this->environment->sjquery_js_url, "cache" => true);      
      }

      if ( $bjquery_ui_js === true ) {
         $this->ajs_source[1] = array("sfile" => $this->environment->sjquery_ui_js_url, "cache" => true);
      }
      
      $ainfo['bjquery'] = $bjquery;
      
      if ( $this->bfileupload === true ) {
         $this->ajs_source[2] = array("sfile" => $this->environment->assets_path . 'core/js/upclick.min.js', "cache" => false);
      }
      
      $this->ajs_source[3] = array("sfile" => $this->environment->ssite_js, "cache" => true);
      ksort( $this->ajs_source );
      
      $ainfo['ajs_source'] = $this->ajs_source;
      $ainfo['slang_options'] = $this->slang_options;
      $ainfo['awrite_script'] = $this->awrite_script;
      
      $this->load->vars($ainfo);
      
      if ( $sfile_override == "" ) {
        $sview = $this->load->view(__FUNCTION__,'',TRUE);
      } else {
        $sview = $this->load->view($sfile_override,$aheader_info,TRUE);
      }
      
      ob_start();
      echo $sview;
      $soutput = ob_get_clean();

      $sjs_output = $this->load->view('script-loader','',TRUE);
      $swrite_script = $this->load->view('write-script','',TRUE);
      $output = preg_replace("|</body>.*?</html>|is", "{$sjs_output}{$swrite_script}</body></html>", $soutput);

      $adata['soutput'] = $output;
      $this->load->view('create-footer',$adata);
   }
   
   public function content($sfile,$ainfo = array())
   {
      $this->load->view($sfile,$ainfo);
   }
   
   private function _get_file_path($sfile,$stype)
   {
      $spath = "";
      
      $apath_info = pathinfo($sfile);
      
      if ( $apath_info['dirname'] ==='.' ) {      
        return false;            
      } else {      
         $asegment = explode('/',$apath_info['dirname']);         
         $asegment_temp = $asegment;         
         $smodule_name = $asegment[0];
         array_splice($asegment,0,1);         
         $sfile_path = "";

         if ( $asegment_temp ) {         
            $spath = "";
            foreach ( $asegment as $key => $val ) {            
               $spath .= ( ( $key==0 ) ? "" : "/"  ) . $val;            
            }
            $spath_separator = ( count ( $asegment ) === 0 ) ? '' : '/';            
            $sfile_path = $this->environment->assets_path . $smodule_name . "/{$stype}" . $spath . $spath_separator . $apath_info['basename'];            
            return $sfile_path;
         }
      }
   }
   
   public function use_css($aoptions = array() )
   {   
      $sfile = ( isset ( $aoptions['source'] ) ) ? $aoptions['source'] : "";      
      $bcache = ( isset ( $aoptions['cache'] ) ) ? $aoptions['cache'] : "";      
      $buse_dir = ( isset ( $aoptions['currentdir'] ) ) ? $aoptions['currentdir'] : true;      
      $aattributes = (isset($aoptions['attributes'] ) ) ? $aoptions['attributes'] : array();
      
      $sdir = ($buse_dir === true) ? "css/" : "";
      if( filter_var ( $sfile , FILTER_VALIDATE_URL ) ) {      
         $aurl = parse_url($sfile);         
         if ( $_SERVER['HTTP_HOST'] === $aurl['host'] ) {         
            $this->acss_source[] = array('sfile' => $sfile,"cache" => false,"attributes" => $aattribute);         
         } else {            
            $this->acss_source[] = array('sfile' => $sfile,"cache" => $bcache,"attributes" => $aattribute);         
         }         
      } else {      
         $apath_info = pathinfo($sfile);         
         if ( $apath_info['dirname'] ==='.' ) {         
            return false;         
         } else {         
            $this->acss_source[] = array("sfile" => $this->_get_file_path($sfile,$sdir) . '.css',"cache" => $bcache,"attributes" => $aattributes);         
         }      
      }    
   }   
   
   public function use_js($aoptions = array())
   {  
      $sfile = ( isset ( $aoptions['source'] ) ) ? $aoptions['source'] : "";
      $bcache = ( isset ( $aoptions['cache'] ) ) ? $aoptions['cache'] : "";
      $buse_dir = ( isset ( $aoptions['currentdir'] ) ) ? $aoptions['currentdir'] : true;
      $aattributes = ( isset ( $aoptions['attributes'] ) ) ? $aoptions['attributes'] : array();
      $slang = (isset($aoptions['lang'])) ? $aoptions['lang'] : "";
      $sdir = ( $buse_dir === true )  ? "js/" : "";
      
      if ( filter_var ( $sfile , FILTER_VALIDATE_URL ) ) {
         $aurl = parse_url ( $sfile );
         if ( $_SERVER['HTTP_HOST']===$aurl['host'] ) {
            $this->ajs_source[ self::$ijs_counter++ ] = array('sfile' => $sfile,"cache" => false);         
         } else {           
            $this->ajs_source[self::$ijs_counter++] = array('sfile' => $sfile,"cache" => $bcache);
         }        
      } else {
         $apath_info = pathinfo($sfile);      
         $this->ajs_source[self::$ijs_counter++] = array("sfile" => $this->_get_file_path($sfile,$sdir) . '.js',"cache" => $bcache,"attributes" => $aattributes,"lang" => $slang);              
      }     
   }
   
   public function language($sfile,$slanguage)
   {
      $afile = explode("/",$sfile);
      $snewfile = implode("|",$afile);
      $this->lang->load($sfile,$slanguage);      
      $this->slang_options = "{$slanguage}|{$snewfile}"; 
   }

   public function write_script($sscript)
   {
      if ( $sscript ) {
         $this->awrite_script[] = $sscript;
      }     
   }
   
   public function write_style($sstyle)
   {
      if ( $sstyle ) {
         $this->awrite_style[] = $sstyle;
      }
   }
   
   public function show_rows($idefault_row = 20,$arows = array(10,20,30,50))
   {
      $ainfo = array();
      $iset_row = isset( $_GET['row'] ) ? $_GET['row'] : $idefault_row;
	   $irow = filter_var ( $iset_row , FILTER_VALIDATE_INT );
      $irow = ( $irow === "" ) ? $idef_row : $irow;
	  
      $ainfo['idefault_row'] = $idefault_row;      
      $ainfo['arows'] = $arows;
      $ainfo['irow'] = $irow;
      
	   $this->load->view('show-rows',$ainfo);
   }

   public function set_fileupload($aoptions = array())
   {
      $aerror = array();
      $adata = array();
      $atemp_ext = array();
      $this->bfileupload = true; //load upclick.js library
      $this->load->library('user_agent'); //load user agent library
      $sbrowser = $this->agent->browser(); // get browser name
      $sbrowser_enc = md5( $sbrowser ); // encrypt browser name
      $sip_enc = md5 ( $_SERVER['REMOTE_ADDR'] ); // encrypt ip address
      $sdate = date("Y-m-d", time() );
      $idate = time();
      $score_temp_path = MODULES_PATH . 'core/uploads/temp/';      
      $sfind_pattern = "*-*-{$sbrowser_enc}-{$sip_enc}.*";      
      
      $smodulename = ""; //initialize module name variable
      $suploadname = ""; //initialize upload name variable
      $sdirectory = ""; //initialize destination directory variable
      $sbutton_text = isset( $aoptions['button_text'] ) ? $aoptions['button_text'] : "Browse File"; //Set button text
      $itotal_upload = isset( $aoptions['total_upload'] ) ? $aoptions['total_upload'] : 1; //Set allowable number of upload
      $aextensions = isset( $aoptions['extensions'] ) ? $aoptions['extensions'] : array("jpg","png","gif"); //Set allowed file extension
      $afile_size = isset( $aoptions['file_size'] ) ? $aoptions['file_size'] : array(); //Set allowed file size 
      
      if (isset( $aoptions['modulename'] ) && $aoptions['modulename']!=="" ) {
         $smodulename = $aoptions['modulename'];
      } else {
         $aerror[] = "File Upload: Module Name Missing.";
      }
      
      if ( isset( $aoptions['uploadname'] ) && $aoptions['uploadname']!=="" ) {
         $suploadname = $aoptions['uploadname'];
      } else {
         $aerror[] = "File Upload: Upload Name Missing.";
      }

      if ( isset($aoptions['directory']) && $aoptions['directory']!=="" ) {
         $sdirectory = $aoptions['directory'];
      } else {
         $aerror[] = "File Upload: Directory Name Missing.";
      }
      
      if ( $aerror ) {
         foreach ( $aerror as $rows) {
            echo "{$rows}<br />";
         }
         exit();
      }
      
      // Check and delete the file if the user refresh the page. Delete the file if created 3 days ago.
      $atemp_file_list = glob( $score_temp_path . $sfind_pattern );
      if ( $atemp_file_list ) {
         foreach ( $atemp_file_list as $rows ) {
            if ( is_readable( $rows ) ) {
               if ( is_writable( $rows ) ) {
                  $aexplode_file = explode('-', $rows );
                  $aexplode_file = array_reverse( $aexplode_file );
                  $idate_range = ceil( abs ( $idate - $aexplode_file[2] ) / 86400 );
                  if ( $idate_range >= 3 ) {
                     @unlink( $rows );
                  } else {
                     @unlink( $rows );
                  }
               }
            }
         }
      }      
      //Add quotes to file extension.
      if ( $aextensions) {
         foreach ( $aextensions as $rows ) {
            $sext = str_pad( $rows , strlen( $rows ) + 1 , "'" , STR_PAD_LEFT );
            $atemp_ext[] = str_pad( $sext , strlen( $sext ) + 1, "'", STR_PAD_RIGHT );
         }
      }
      $sextensions = implode (',', $atemp_ext );
      $suploadname_enc = md5( $suploadname );
      
      $this->session->unset_userdata( array( "{$suploadname_enc}-files" => "") );
      $this->session->unset_userdata( array( $suploadname_enc => "" ) );
      $aoptions = array(
         $suploadname_enc => array(
            "modulename" => $smodulename,
            "uploadname" => $suploadname,
            "directory" => $sdirectory,
            "total_upload" => $itotal_upload,
            "sextensions" => $sextensions,
            "aextensions" => $aextensions,
            "afile_size" => $afile_size
         )
      );
      
      $this->session->set_userdata($aoptions);
      //If all are set start the process.
      $adata['modulename'] = $smodulename;
      $adata['uploadname'] = $suploadname;
      $adata['uploadname_enc'] = $suploadname_enc;
      $adata['button_text'] = $sbutton_text;
      $adata['directory'] = $sdirectory;
      $adata['total_upload'] = $itotal_upload;      
      $adata['extensions'] = $sextensions;
      $sscript = $this->load->view('set-fileupload-script',$adata,TRUE);
      
      $search = array('/\}[^\S ]+/s','/[^\S ]+\{/s','/(\s)+/s');
      $replace = array('}','{','\\1');
      $sscript =  str_replace("ASSETS",$this->environment->asset_path,$sscript);
      $sscript = preg_replace($search, $replace, $sscript);      
      $this->write_script($sscript);
      $this->load->view('set-fileupload',$adata);
   }   
   
   public function get_fileupload($supload_name,$bexecute = false)
   {   
      $ainfo = array();
      $alist = array();
      $sreal_upname = $supload_name;
      $supload_name = md5( $supload_name );
      $aupload_info = $this->session->userdata("{$supload_name}");     
      $iw = NULL;
      $ih = NULL;
      
      if ( $aupload_info && $this->input->post("{$supload_name}-new")!==false  && $this->input->post("{$supload_name}-name")!==false ) {         
         $stmp_directory = MODULES_PATH  . 'core/uploads/temp/';
         $sdirectory = MODULES_PATH  . $aupload_info['modulename'] . "/uploads/{$aupload_info['directory']}/";        
         $aname = $this->input->post("{$supload_name}-name");
         $afiles = $this->input->post("{$supload_name}-new");         
         if ( $afiles ) {                     
            if ( is_writable( $sdirectory ) ) {               
               foreach ( $afiles as $key => $val ) {                  
                  if ( is_readable( $stmp_directory . $val ) ) {                     
                     $stemp_file = $stmp_directory . $val;                     
                     $afileinfo = pathinfo($stemp_file);                        
                     $sinfo_ext = $afileinfo['extension'];                      
                     if ( $sinfo_ext == 'jpg' || $sinfo_ext=='gif' || $sinfo_ext=='png' ) {                        
                        if ( getimagesize ( $stemp_file ) ) {                           
                           list( $iwidth, $iheight ) = getimagesize( $stemp_file ); 
                           $iw = $iwidth;
                           $ih = $iheight;
                        }                         
                     }                     
                     $sfilename = $aname[ $key ];//file name                     
                     $afilesize = $this->common->file_size( filesize( $stemp_file ) );//file size array                     
                     $ssize = "{$afilesize[0]} {$afilesize[1]}"; //original size                     
                     $irawsize = filesize( $stemp_file ); //original size                     
                     $iwidth = $iw; //if image return width                     
                     $iheight = $ih; //if image return height                   
                     
                     if ( $bexecute === true ) {                        
                        if ( is_writable( $sdirectory ) ) {                             
                           copy( $stemp_file , $sdirectory . $val );                        
                        }
                     }                     
                     $alist[] = array(
                        'filename' => $sfilename,
                        'newfilename' => $val,
                        'extension' => $sinfo_ext,
                        'size' => $ssize,
                        'rawsize' => $irawsize,
                        'width' => $iwidth,
                        'height' => $iheight
                     );
                  }
               }
               $ainfo['upload-info'] = $aupload_info;
               $ainfo['files'] = $alist;
               return $ainfo;          
               
            } else {
               return false;
            }
         }         
      } else {
         return false;
      }
   }
}