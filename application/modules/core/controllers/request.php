<?php

class Request extends MX_Controller
{
   public $aforbidden_dir = array(
         "cache",
         "config",
         "controllers",
         "core",
         "errors",
         "helpers",
         "hooks",
         "language",
         "libraries",
         "logs",
         "models",
         "third_party",
         "views",
         "modules");
   
   public function __construct()
   {
      parent::__construct();
      $this->load->model('core_model');      
   }

   public function exec()
   {
      $oinput = $this->input;
      $srequest_type = $_SERVER['REQUEST_METHOD'];
      
      if( $oinput->post('mod') || $oinput->get('mod') ) {
         $amod = explode('|',$oinput->get_post('mod'));
         
         if(isset($amod[0])===false || isset( $amod[1] ) === false || isset( $amod[2] ) === false ) {
            show_404();
            exit();
         }
         
         $smodule_name = strtolower($amod[0]);
         $smodule_name = strtolower($amod[0]);
         $smodule_controller = strtolower($amod[1]);
         $smodule_method = strtolower($amod[2]);
         
         if( $srequest_type==="POST" ) {
            if( $this->load->module($smodule_name . '/' . $smodule_controller ) ) {
               $this->$smodule_controller->$smodule_method();
            } else {
               show_404();
               exit();
            }          
         }elseif( $srequest_type==="GET" ) {
            if( $this->load->module($smodule_name . '/' . $smodule_controller) ) {
               $this->$smodule_controller->$smodule_method();
            } else {
               show_404();
               exit();
            }
         }
      }
   }
   
   public function ajax()
   {
      $oinput = $this->input;
      $srequest_type = $_SERVER['REQUEST_METHOD'];
      
      if($oinput->post('mod') || $oinput->get('mod')){
         $amod = explode('|',$oinput->get_post('mod'));
         if(isset($amod[0])===false || isset($amod[1])===false || isset($amod[2])===false){
            show_404();
            exit();
         }
         $smodule_name = strtolower($amod[0]);
         $smodule_controller = strtolower($amod[1]);
         $smodule_method = strtolower($amod[2]);
         
         if($srequest_type==="POST"){
            if($this->load->module($smodule_name . '/' . $smodule_controller)){
               $this->$smodule_controller->$smodule_method();
            }else{
               show_404();
               exit();
            }          
         }elseif($srequest_type==="GET"){
            if($this->load->module($smodule_name . '/' . $smodule_controller)){
               $this->$smodule_controller->$smodule_method();
            }else{
               show_404();
               exit();
            }                  
         }
      }else{
         show_404();
      }
   }
   
   public function assets()
   {
      $suse_lang = "en";
      $smodule_name = $this->uri->rsegment(3);
      $smodule_path = APPPATH . 'modules/' . $smodule_name;
      $bcache = ($this->input->get('cac') && $this->input->get('cac')==='true') ? true : false;
      //Localization Language Translation 
      if($this->input->get('local')){         
         $atemp_local = array();
         $alocal = explode('|',$this->input->get('local'));
         
         if($alocal){
            $slang = $alocal[0];
            $smodule_lang = $alocal[1];
            $slang_dirname = "/language/{$slang}/";
            $atemp_local = array_splice($alocal,2,count($alocal));
            
            $slang_path = implode('/',$atemp_local);
            $slang_main_path = MODULES_PATH . $smodule_lang . $slang_dirname  . $slang_path . '_lang.php';
            
            if(file_exists($slang_main_path)){               
               $suse_lang = $slang;
               $this->lang->load($smodule_lang . '/' . $slang_path, $slang);
            }
         }         
      }
      
      if($smodule_name){
          $asegment = $this->uri->rsegment_array();
          array_splice($asegment,0,3);
          if($asegment){
             $snext_path = "";
             foreach($asegment as $key=>$val){
                $snext_path .= (($key==0) ? "" : "/" ) . $val;
             }
             $srequest_file = $smodule_path . '/assets/'. $snext_path;
             if(file_exists($smodule_path . '/assets/'. $snext_path)){
                $apath_info = pathinfo($srequest_file);
               if(!isset($apath_info['extension'])){
               
                  show_404();
               }
               $sfolder = "";
               
               // If CSS, JS, TXT Process code here
               if($apath_info['extension']==="css" || $apath_info['extension']=="js"){
                  $atexttype_file = array("js"=>"js","css" => "css","txt" =>"txt");
                  
                  $sfolder = $atexttype_file[$apath_info['extension']];
                  $stextheader_type = "";
                  $stextheader_type = $this->common->get_mime_type($srequest_file);
                  $expires = 60;
                  $search = array('/\}[^\S ]+/s','/[^\S ]+\{/s','/(\s)+/s');
                  $replace = array('}','{','\\1');                  
                  
                  if($bcache === true){                  
                     $scache_path = $smodule_path . '/cache/' . $sfolder . '/';
                     $sfile_path = $smodule_path . '/assets/'. $snext_path;
                     $sfile_name = $apath_info['filename'] . ".{$apath_info['extension']}";
                     $sfile_modified = filemtime($sfile_path);
                     $sencrypt = md5($sfile_modified) . '-' . md5($sfile_name) . "-{$suse_lang}" . ".{$apath_info['extension']}";
                     
                     if(!is_readable($scache_path)){
                        header('HTTP/1.1 500 Internal Server Error');
                        echo 'Error: the cache directory is not readable';
                        exit();                     
                     }elseif(!is_writable($scache_path)){
                        header('HTTP/1.1 500 Internal Server Error');
                        echo 'Error: the cache directory is not writable';
                        exit();
                     }
              
                     if(is_dir($scache_path)){
                        $scached_file = $scache_path . $sencrypt;
                        
                        if(!file_exists($scached_file)){

                           $afile = glob ( $scache_path . '*-' . md5($sfile_name). "-*" . "{$apath_info['extension']}" );

                           if($afile){
                              foreach($afile as $rows){
                                 unlink($rows);
                              }
                           }
                           header("Content-type: {$stextheader_type}; charset=UTF-8", true);
                           header('Cache-Control: max-age='.$expires.', must-revalidate');
                           header('Pragma: public');
                           header('Expires: '. gmdate('D, d M Y H:i:s', time()+$expires).'GMT');  
                           
                           ob_start();
                           require_once($srequest_file);
                           $soutput = ob_get_clean();
                           $soutput =  str_replace("ASSETS",$this->environment->asset_path,$soutput);
                           $soutput = preg_replace($search, $replace, $soutput);
                           $soutput = $this->_translate_content($soutput);
                           $screate_cache = fopen($scached_file ,'w');
                           fwrite($screate_cache,$soutput);
                           fclose($screate_cache);
                           echo $soutput;
                           exit();
                        }else{      
                           header("Content-type: {$stextheader_type}; charset=UTF-8", true);
                           header('Cache-Control: max-age='.$expires.', must-revalidate');
                           header('Pragma: public');
                           header('Expires: '. gmdate('D, d M Y H:i:s', time()+$expires).'GMT');                         
                        
                           require_once($scache_path . $sencrypt); 
                           exit();
                        }
                     }else{

                     }
                  }else{
                     header("Content-type: {$stextheader_type}; charset=UTF-8", true);                  
                     ob_start();
                     require_once($srequest_file);   
                     $soutput = ob_get_clean();
                     echo $this->_translate_content($soutput);
                     exit();
                  }
               // IF Image here process some logic here cache.
               }else if($apath_info['extension']==="jpg" || $apath_info['extension']=="gif" || $apath_info['extension']=="png"){
                  $this->_image_loader($srequest_file,$smodule_path . '/cache/images/');
               }else{
                  $sheader_type = $this->common->get_mime_type($srequest_file);
                  $ifsize = filesize($srequest_file); 
                  header("Pragma: public"); // required 
                  header("Expires: 0"); 
                  header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
                  header("Cache-Control: private",false); // required for certain browsers                   
                  header("Content-type: " . $sheader_type . '; charset=utf-8',true);     
                  header("Content-Transfer-Encoding: binary"); 
                  header("Content-Length: ".$ifsize);                   
                  ob_clean(); 
                  flush(); 
                  readfile( $srequest_file );     
                  exit();
               }
             }else{
                show_404();
             }
          }else{
              show_404();
          }
      }else{
         show_404();
      }
   }
   
   private function findSharp($iOriginal, $iFinal)
   {
      $iFinal = $iFinal * (750.0 / $iOriginal);
      $a = 52;
      $b = -0.27810650887573124;
      $c = .00047337278106508946;

      $iResult = $a + $b * $iFinal + $c * $iFinal * $iFinal;

      return max(round($iResult), 0);
   }

   private function doConditionalGet($sEncryptedTag, $lastModified)
   {
      header("Last-Modified: " . $lastModified);
      header("ETag: \"" . $sEncryptedTag . "\"");

      $bMatch = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) :  false;

      $bModified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;

      if (!$bModified && !$bMatch) return;
      if ($bMatch && $bMatch != $sEncryptedTag && $bMatch != '"' . $sEncryptedTag . '"') return;
      if ($bModified && $bModified != $lastModified) return;

      header("HTTP/1.1 304 Not Modified");
      exit();
   }   
   
   public function _image_loader($srequest_file,$scache_dir)
   {
      define('MEMORY_TO_ALLOCATE', '100M');
      define('DEFAULT_QUALITY', 80);
      define('DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT']);
      define('IMAGE_DIR', $srequest_file);
      define('CACHE_DIR_NAME',$scache_dir);
      define('CACHE_DIR', CACHE_DIR_NAME);
      define('MAX_IMAGE_WIDTH', 2000);
      define('MAX_IMAGE_HEIGHT', 1000);
      $aSize = GetImageSize(IMAGE_DIR);

      $sMime = $aSize['mime'];

      if (substr($sMime, 0, 6) != 'image/'){
         header('HTTP/1.1 400 Bad Request');
         echo 'Error: requested file is not an accepted type: ' . $sImage;
         exit();
      }
      
      $iWidth = $aSize[0];
      $iHeight = $aSize[1];
      $iMaxWidth = (isset($_GET['w'])) ? (int) $_GET['w'] : 0;
      $iMaxHeight = (isset($_GET['h'])) ? (int) $_GET['h'] : 0;
      $sCropRation = (isset($_GET['cr'])) ? (string) $_GET['cr'] : null;
      $sColor = (isset($_GET['color'])) ? preg_replace('/[^0-9a-fA-F]/', '', (string) $_GET['color']) : FALSE;
      $iQuality = (isset($_GET['quality'])) ? (int) $_GET['quality'] : DEFAULT_QUALITY;

      if (isset($_GET['type'])){
         switch ($_GET['type']){
            case "pt" : 
               $iMaxWidth = 50;
               $iMaxHeight = 50;
               $sCropRation = "4:4";
               break;
            case "ct" :
               $iMaxWidth = 40;
               $iMaxHeight = 40;
               $sCropRation = "4:4";
               break;
            case "sc" :
               $iMaxWidth = 160;
               $iMaxHeight = 0;
               break;
            case "pp" :
               $iMaxWidth = 190;
               $iMaxHeight = 400;
               break;
            case "ba" :
               $iMaxWidth = 125;
               $iMaxHeight = 94;
               $sCropRation = "4:3";
               break;
            case "max" :
               $iMaxWidth = MAX_IMAGE_WIDTH;
               $iMaxHeight = MAX_IMAGE_HEIGHT;
               break;
         }
      }

      if (!$iMaxWidth && $iMaxHeight) $iMaxWidth = 99999999999999;
      else if ($iMaxWidth && !$iMaxHeight) $iMaxHeight = 99999999999999;
      else if ($sColor && !$iMaxWidth && !$iMaxHeight){
         $iMaxWidth = $iWidth;
         $iMaxHeight = $iHeight;
      }

      if ((!$iMaxWidth && !$iMaxHeight) || (!$sColor && $iMaxWidth >= $iWidth && $iMaxHeight >= $iHeight)){
         $sData = file_get_contents($srequest_file);
         $dLastModifiedString = gmdate('D, d M Y H:i:s', filemtime($srequest_file)) . ' GMT';
         $sEncryptedTag = md5($sData);

         $this->doConditionalGet($sEncryptedTag, $dLastModifiedString);

         header("Content-type: " . $sMime);
         header("Content-Length: " . strlen($sData));
         
         echo $sData;
         exit();
      }

      $iOffsetX = 0;
      $iOffsetY = 0;

      if ($sCropRation != null){
         $aCropRatio = explode(':', (string) $sCropRation);
         if (count($aCropRatio) == 2){
            $iRatioComputed = $iWidth / $iHeight;
            $aCropRatioComputed = (float) $aCropRatio[0] / (float) $aCropRatio[1];

            if ($iRatioComputed < $aCropRatioComputed){
               $iOriginalHeight = $iHeight;
               $iHeight = $iWidth / $aCropRatioComputed;
               $iOffsetY = ($iOriginalHeight - $iHeight) / 2;
            }
            else if ($iRatioComputed > $aCropRatioComputed){
               $iOriginalWidth = $iWidth;
               $iWidth = $iHeight * $aCropRatioComputed;
               $iOffsetX = ($iOriginalWidth - $iWidth) / 2;
            }
         }
      }

      $xRatio = $iMaxWidth / $iWidth;
      $yRatio = $iMaxHeight / $iHeight;

      if ($xRatio * $iHeight < $iMaxHeight){
         $sTargetHeight = ceil($xRatio * $iHeight);
         $sTargetWidth = $iMaxWidth;
      }
      else {
         $sTargetWidth = ceil($yRatio * $iWidth);
         $sTargetHeight = $iMaxHeight;
      }

      if ($sTargetWidth <= 500 && $sTargetHeight <= 500) $iQuality = 100;

      $sResizedImageSource = $sTargetWidth . 'x' . $sTargetHeight . 'x' . $iQuality;

      if ($sColor) $sResizedImageSource .= 'x' . $sColor;
      if (isset($_GET['cropratio'])) $sResizedImageSource .= 'x' . (string) $_GET['cropratio'];

      $sResizedImageSource .= '-' . $srequest_file;
      $sResizedImage = md5($sResizedImageSource);
      $sResized = CACHE_DIR . $sResizedImage;

      if (!isset($_GET['nocache']) && file_exists($sResized)){
         $sImageModified = filemtime($srequest_file);
         $sThumbModified = filemtime($sResized);

         if($sImageModified < $sThumbModified){
            $sData = file_get_contents($sResized);

            $dLastModifiedString = gmdate('D, d M Y H:i:s', $sThumbModified) . ' GMT';
            $sEncryptedTag = md5($sData);

            $this->doConditionalGet($sEncryptedTag, $dLastModifiedString);
            
            header("Content-type: " . $sMime);
            header("Content-Length: " . strlen($sData));
            echo $sData;
            exit();
         }
      }

      ini_set('memory_limit', MEMORY_TO_ALLOCATE);
      $oNewImage = imagecreatetruecolor($sTargetWidth, $sTargetHeight);

      switch ($aSize['mime']){
         case 'image/gif':
            $sCreationFunction = 'ImageCreateFromGif';
            $sOutputFunction = 'ImagePng';
            $sMime = 'image/png';
            $bDoSharpen = FALSE;
            $iQuality = round(10 - ($iQuality / 10));
         break;

         case 'image/x-png':
         case 'image/png':
            $sCreationFunction = 'ImageCreateFromPng';
            $sOutputFunction = 'ImagePng';
            $bDoSharpen = FALSE;
            $iQuality = round(10 - ($iQuality / 10));
         break;

         default:
            $sCreationFunction = 'ImageCreateFromJpeg';
            $sOutputFunction = 'ImageJpeg';
            $bDoSharpen = TRUE;
         break;
      }

      $sLocation = $sCreationFunction($srequest_file);

      if (in_array($aSize['mime'], array('image/gif', 'image/png'))){
         if (!$sColor){
            imagealphablending($oNewImage, false);
            imagesavealpha($oNewImage, true);
         }
         else {
            if ($sColor[0] == '#')
            $sColor = substr($sColor, 1);

            $bBackground = FALSE;

            if (strlen($sColor) == 6) $bBackground = imagecolorallocate($oNewImage, hexdec($sColor[0].$sColor[1]), hexdec($sColor[2].$sColor[3]), hexdec($sColor[4].$sColor[5]));
            else if (strlen($sColor) == 3) $bBackground = imagecolorallocate($oNewImage, hexdec($sColor[0].$sColor[0]), hexdec($sColor[1].$sColor[1]), hexdec($sColor[2].$sColor[2]));
            if ($bBackground) imagefill($oNewImage, 0, 0, $bBackground);
         }
      }

      ImageCopyResampled($oNewImage, $sLocation, 0, 0, $iOffsetX, $iOffsetY, $sTargetWidth, $sTargetHeight, $iWidth, $iHeight);   

      if ($bDoSharpen){
         $sharpness = $this->findSharp($iWidth, $sTargetWidth);

         $sharpenMatrix = array(
                        array(-1, -2, -1),
                        array(-2, $sharpness + 12, -2),
                        array(-1, -2, -1)
                     );
         
         $divisor = $sharpness;
         $offset= 0;
         
         imageconvolution($oNewImage, $sharpenMatrix, $divisor, $offset);
      }

      if (!file_exists(CACHE_DIR)) mkdir(CACHE_DIR, 0755);

      if (!is_readable(CACHE_DIR)){
         header('HTTP/1.1 500 Internal Server Error');
         echo 'Error: the cache directory is not readable';
         exit();
      }
      else if (!is_writable(CACHE_DIR)){
         header('HTTP/1.1 500 Internal Server Error');
         echo 'Error: the cache directory is not writable';
         exit();
      }

      $sOutputFunction($oNewImage, $sResized, $iQuality);

      ob_start();
      $sOutputFunction($oNewImage, null, $iQuality);
      $sData = ob_get_contents();
      ob_end_clean();

      ImageDestroy($sLocation);
      ImageDestroy($oNewImage);

      $dLastModifiedString = gmdate('D, d M Y H:i:s', filemtime($sResized)) . ' GMT';
      $sEncryptedTag = md5($sData);

      $this->doConditionalGet($sEncryptedTag, $dLastModifiedString);
      header("Content-type: " . $sMime,true);
      header("Content-Length: " . strlen($sData));
      header("Cache-Control: private, max-age=10800, pre-check=10800");
      header("Pragma: private");
      header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));
      echo $sData;     
      exit();
   }

   public function getfile()
   {
      $bdownload = ($this->input->get("download")) ? $this->input->get('download') : false;
      $bdownload = ($bdownload==="true") ? true : false;
      
      $smodule_name = $this->uri->rsegment(3);
      $ssecond_dir = $this->uri->rsegment(4);
      $smodule_path = MODULES_PATH . $smodule_name . '/';
      
      if ( $smodule_name ) {
      
         $asegment = $this->uri->rsegment_array();
         array_splice($asegment,0,3);
         
         if ( $asegment ) {
            $snext_path = "";
            
            foreach ( $asegment as $key => $val ) {
               $snext_path .= ( ( $key == 0 ) ? "" : "/" ) . $val;
            }
            
            $srequest_file = $smodule_path. $snext_path;
            
            if( file_exists ( $srequest_file ) ) {
               
               $apath_info = pathinfo($srequest_file);
               
               if ( !is_dir( $srequest_file ) && isset ( $apath_info['extension'] ) && !in_array( $ssecond_dir , $this->aforbidden_dir ) ) {
                  
                     $apath_info = pathinfo( $srequest_file );
                     $smime = $this->common->get_mime_type($srequest_file);               
                     $ifsize = filesize($srequest_file); 
                     $apath_info = pathinfo($srequest_file);          
                     if ( $bdownload === true ) {                         
                        header("Pragma: public"); // required 
                        header("Expires: 0"); 
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
                        header("Cache-Control: private",false); // required for certain browsers 
                        header("Content-Type: {$smime}"); 
                        header("Content-Disposition: attachment; filename=\"" . basename($srequest_file) . "\";" ); 
                        header("Content-Transfer-Encoding: binary"); 
                        header("Content-Length: ".$ifsize); 
                        ob_clean(); 
                        flush(); 
                        readfile( $srequest_file );   
                        exit();                           
                     } else {
                        if ( $apath_info['extension'] === 'jpg' || $apath_info['extension']==='png' || $apath_info['extension']==='gif' ) {                        
                           $this->_image_loader($srequest_file,$smodule_path . '/cache/images/');                        
                        } else {                           
                           header("Content-type: {$smime}", true);
                           header("Cache-Control: private, max-age=10800, pre-check=10800");
                           header("Pragma: private");
                           header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));          
                           readfile($srequest_file);
                           exit();       
                        }
                     }
               }else{
                  show_404();
               }              
            }else{
               show_404();
            }
         }
      }else{
         show_404();
      }
   } 
   
   public function u()
   {
      $adata = array();
      $stemp_dir = MODULES_PATH . 'core/uploads/temp/';
      $oinput = $this->input;
      $this->load->library('user_agent');
      $sbrowser = $this->agent->browser();
      $sbrowser_enc = md5($sbrowser);
      $sip_enc = md5($_SERVER['REMOTE_ADDR']);
      
      if ( $this->input->post('perform')=='0' ) {                     
         if( $oinput->post('upload_id') ) {         
            $supload_name = $oinput->post('upload_id');         
            if ( $this->session->userdata($supload_name ) ) {               
               if ( isset( $_FILES['core-file'] ) ) {                  
                  $afile = $_FILES['core-file'];
                  $aoptions = $this->session->userdata($supload_name);
                  $sfilename = $afile['name'];
                  $stmp_filename = $afile['tmp_name'];
                  $afileinfo = pathinfo($sfilename);
                  $sinfo_ext = $afileinfo['extension'];
                  $sinfo_filename = $afileinfo['filename'];
                  $sinfo_filename_enc = md5($sinfo_filename);
                  $iw = NULL;
                  $ih = NULL;
                  //New name pattern : File name Encrypted | Time | Browser Name (encrypted) | IP Address (encrypted) | Extension.
                  $snewname = $sinfo_filename_enc . '-' . time() . '-' . $sbrowser_enc . '-' . $sip_enc . ".{$sinfo_ext}";

                  if ( in_array($sinfo_ext,$aoptions['aextensions'] ) ) {                     
                     $afilesize = $this->common->file_size($afile['size']);                     
                     if ( $sinfo_ext == 'jpg' || $sinfo_ext=='gif' || $sinfo_ext=='png' ) {                        
                        if ( getimagesize($stmp_filename) ) {
                           
                           list( $iwidth , $iheight ) = getimagesize($stmp_filename); 
                           $iw = $iwidth;
                           $ih = $iheight;
                        }                         
                     }
                     
                     $sfile_size = "{$afilesize[0]} {$afilesize[1]}";
                     
                     $iraw_size = $afile['size'];
                     
                     if ( $aoptions['afile_size']) {                                             
                        if ( array_key_exists($sinfo_ext,$aoptions['afile_size']) ) {                           
                           if ( $afile['size'] < $aoptions['afile_size'][$sinfo_ext]) {                           
                              if ( count( $this->session->userdata($supload_name .'-files')) < $aoptions['total_upload']) {                                 
                                 // $this->_set_file_list( $aoptions,$sfilename,$snewname,$sinfo_ext,"{$afilesize[0]} {$afilesize[1]}",$afile['size'],$iw,$ih);                              
                                 $adata['filename'] = $sfilename;
                                 $adata['newname'] = $snewname;                        
                                 $adata['extension'] = $sinfo_ext;  
                                 $adata['filesize'] = $sfile_size;
                                 $adata['rawsize'] = $iraw_size;
                                 $adata['width'] = $iw;
                                 $adata['height'] = $ih;    
                                 move_uploaded_file($stmp_filename, $stemp_dir. $snewname);                                
                                 $adata['status'] = 'ok';
                                 
                              } else {                                 
                                 $adata['status'] = 'exceeded';
                              }                              
                           } else {                           
                              $adata['status'] = "large";                                                      
                           }                        
                        } else {                  
                           
                           // $this->_set_file_list( $aoptions,$sfilename,$snewname,$sinfo_ext,"{$afilesize[0]} {$afilesize[1]}",$afile['size'],$iw,$ih);                              
                           $adata['filename'] = $sfilename;
                           $adata['newname'] = $snewname;                        
                           $adata['extension'] = $sinfo_ext;  
                           $adata['filesize'] = $sfile_size;
                           $adata['rawsize'] = $iraw_size;
                           $adata['width'] = $iw;
                           $adata['height'] = $ih;  
                           
                           move_uploaded_file($stmp_filename, $stemp_dir. $snewname);                            
                           $adata['status'] = 'ok';                     
                        }                     
                     } else {                     
                        if ( count( $this->session->userdata($supload_name .'-files')) < $aoptions['total_upload'] ) {
                           // $this->_set_file_list( $aoptions,$sfilename,$snewname,$sinfo_ext,"{$afilesize[0]} {$afilesize[1]}",$afile['size'],$iw,$ih);                           
                           $adata['filename'] = $sfilename;
                           $adata['newname'] = $snewname;                        
                           $adata['extension'] = $sinfo_ext;  
                           $adata['filesize'] = $sfile_size;
                           $adata['rawsize'] = $iraw_size;
                           $adata['width'] = $iw;
                           $adata['height'] = $ih;    
                           move_uploaded_file($stmp_filename, $stemp_dir. $snewname);                            
                           $adata['status'] = 'ok';                        
                        }
                     }
                  } else {
                     $adata['status'] = 'invalid';               
                  }
               } else {
                  $adata['status'] = 'error';
               }
            } else {
               $adata['status'] = 'error';
            }
         }         
      } elseif ( $this->input->post('perform') == '1' ) {         
         if ( $this->input->post('file')) {            
            $sfilename = $this->input->post('file');            
            $sfile_path = $stemp_dir . $sfilename;            
            if ( is_readable ( $sfile_path ) ) {               
               if ( is_writable( $sfile_path ) ) {                  
                  if ( $oinput->post('upload_id')) {                     
                     $supload_name = $oinput->post('upload_id');                     
                     if ( $this->session->userdata("{$supload_name}-files") ) {                        
                        $afile_list = $this->session->userdata("{$supload_name}-files");                        
                        if ( $afile_list ) {                        
                           $ikey_pos = "";                           
                           foreach ( $afile_list as $key => $val ) {                              
                              if ( $sfilename === $val['newname'] ) {                              
                                 $ikey_pos = $key;                              
                              }                           
                           }                           
                           unset($afile_list[$ikey_pos]);                           
                           $this->session->set_userdata(array("{$supload_name}-files"=>$afile_list));                           
                           $adata['status'] = 'deleted';
                        }
                     }                     
                  } else {                     
                     $adata['status'] = 'error';                  
                  }                  
               } else {               
                  $adata['status'] = 'error';
               }               
            } else {               
               $adata['status'] = 'error';            
            }            
         } else {            
            $adata['status'] = 'error';
         }         
      } else {                  
         $adata['status'] = 'error';      
      }      
      echo json_encode($adata);
   }
   
   
   private function _set_file_list($aopts,$sfilename,$snewname,$sextension,$sfilesize,$irawsize,$iw = null,$ih = null)
   {
      $aoptions = array(
         "filename" => $sfilename,
         "newname" => $snewname,
         "extension" => $sextension,
         "filesize" => $sfilesize,
         "rawsize" => $irawsize,
         "width" => $iw,
         "height" => $ih
      );
      
      $supload_name = md5($aopts['uploadname']);
      
      if ( !$this->session->userdata($supload_name .'-files') ) {
         
         $alist = array(
            "{$supload_name}-files" => array(
               0 => $aoptions
            )
         );
         
         $this->session->set_userdata($alist);     
      } else {
         
         $alist = $this->session->userdata($supload_name .'-files');         
         $anewfile[] = $aoptions;                  
         $anewlist = array_merge($anewfile,$alist);         
         $this->session->unset_userdata(array("{$supload_name}-files" => ""));         
         $this->session->set_userdata(array(
            $supload_name .'-files' => $anewlist
         ));
      }   
   }

   private function _translate_content($soutput)
   {
      $acontent = explode("__",$soutput);
      if ( $acontent ) {      
         $alang = $this->lang->language;//Force language hack         
         for ( $i = 0,$k = count ( $acontent ) ; $i < $k ; $i++ ) {            
            if ( $i%2 == 1 ) {                  
               foreach ( $alang as $key => $val ) {                  
                  if ( $key == $acontent[ $i ] ) {
                     $soutput = str_replace("__".$acontent[$i]."__",$val,$soutput);
                  }               
               }
            }         
         }         
         return $soutput;
      } else {
         return $soutput;
      }
   }   
}