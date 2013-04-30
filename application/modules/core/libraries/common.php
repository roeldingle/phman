<?php

class Common
{
   private $asort_field = array();
   private $adefault_field = '';
   private $sdefault_order = 'desc';
   private $CI;

   public function __construct()
   {
      $this->CI =& get_instance();
   }

   public function pager($itotal_rows , $ilimit,$aclass = array(), $spage_acr = "page" )
   {
       if($itotal_rows/$ilimit > 1){
          $aclass_options = array(
             'container_class' => isset($aclass['container_class']) ? $aclass['container_class'] : "pagination",
             'ul_class' => isset($aclass['ul_class']) ? $aclass['ul_class'] : "",
             'li_class' => isset($aclass['li_class']) ? $aclass['li_class'] : "",
             'disabled_class' => isset($aclass['disabled_class']) ? $aclass['disabled_class'] : "",
             'active_class' =>  isset($aclass['active_class']) ? $aclass['active_class'] : "",
             'inactive_class' =>  isset($aclass['active_class']) ? $aclass['active_class'] : "",
          );         
          $iinterval = 2;
          $sqry_string = $this->qry_str_builder($spage_acr);
          $iget_row = filter_var(@$_GET['row'],FILTER_VALIDATE_INT);
          $iget_row = ($iget_row==="") ? $ilimit : $iget_row;
          $iget_row = ($iget_row > $itotal_rows) ? $itotal_rows : $iget_row;
          
          $ilimit = isset($_GET['row']) ? $iget_row : $ilimit;
          $itotal_page = @ceil($itotal_rows/$ilimit);
          $iqry_page = isset($_GET[$spage_acr]) ? $_GET[$spage_acr] : 1;
          $ifilter_page = filter_var($iqry_page,FILTER_VALIDATE_INT);

          $ipage = ($ifilter_page === "") ? 1 : $ifilter_page;
          
          if($ipage > $itotal_page ) {
             $ipage = $itotal_page;
          }

          $snext = ($ipage == 1) ? "<li title='Previous' class='{$aclass_options['disabled_class']}'><a>&laquo; prev</a></li>" : "<li title='Previous'><a href='?{$spage_acr}=". ($ipage-1) . "{$sqry_string}' >&laquo; prev </a></li>";
          $sprev = ($ipage == $itotal_page) ? "<li title='Next' class='{$aclass_options['disabled_class']}'><a>next &raquo;</a></li>" : "<li title='Next'><a href='?{$spage_acr}=". ($ipage+1) . "{$sqry_string}' > next &raquo;</a></li>";

          $shtml = "";
          $shtml .= "<div class='{$aclass_options['container_class']}'>";
          $shtml .= "<ul class='{$aclass_options['ul_class']}'>";

          $shtml .= $snext ;
          for ($ilink = 1; $ilink <= $itotal_page; $ilink++){
             if ($ilink == 2 && $ipage >= ($iinterval + 3)){
                $shtml .= "<li title='Page 1' class='{$aclass_options['li_class']}'><a href='?{$spage_acr}=1{$sqry_string}' >1</a></li><li><a>&nbsp;&hellip;&nbsp;</a></li>";
             }elseif($ilink == 1 && $ipage == ($iinterval + 2)){
                $shtml .= "<li title='Page 1'><a href='?{$spage_acr}=1{$sqry_string}'>1</a>&nbsp;</li>";
             }
             if ($ilink == $ipage){
                $shtml .= "<li title='Page {$ipage}'><a class='{$aclass_options['active_class']}'>$ipage</a>&nbsp;</li>";
             }elseif ($ilink >= ($ipage - $iinterval)){
                $shtml .= "<li title='Page {$ilink}' class='{$aclass_options['li_class']}'><a href='?{$spage_acr}=$ilink{$sqry_string}' >$ilink</a>&nbsp;</li>";
             }
             if ($ilink >= ($ipage + $iinterval) && ($itotal_page - ($iinterval + 2)) >= $ipage){
                $shtml .= "<li title='Page {$itotal_page}'  class='{$aclass_options['li_class']}'><a>&hellip;&nbsp;</a></li><li><a href='?{$spage_acr}=$itotal_page{$sqry_string}'>$itotal_page</a></li>";
                break;
             }
          }
          $shtml .= $sprev;
          $shtml .="</ul>";
          $shtml .="</div>";
          return $shtml;
      }
   }

   public function qry_str_builder($skey)
   {
      $aqry_array = array();
      $anew_qry = array();
      $sqry = $_SERVER['QUERY_STRING'];
      parse_str($sqry,$aqry_array);

      foreach($aqry_array as $key=>$val){
         if($key!=$skey){
            $anew_qry[$key] = $val;
         }
      }
      $sseparator = ($anew_qry) ? "&" : "";
      $sqry_string = $sseparator . http_build_query($anew_qry,'','&');
      return $sqry_string;
   }
   
   public function qry_str_array_builder($akeys = array())
   {
      $aqry_array = array();
      $anew_qry = array();
      $sqry = $_SERVER['QUERY_STRING'];
      parse_str($sqry,$aqry_array);

      foreach($aqry_array as $key=>$val){
         // if($key!=$skey){
         if(!in_array($key, $akeys)){
            $anew_qry[$key] = $val;
         }
      }
      $sseparator = ($anew_qry) ? "&" : "";
      $sqry_string = $sseparator . http_build_query($anew_qry,'','&');
      return $sqry_string;
   }

   public function set_field_list($afield = array(),$adefault_field)
   {
      if($afield){
         $this->asort_field = $afield;
         $this->adefault_field = $adefault_field;
      }
   }
   
   public function sql_orderby()
   {
       $aallowed = array("desc","asc");
       $adefault = array_keys($this->adefault_field);
       $asort = array();
       if(isset($_GET['sort']) && isset($_GET['order'])){
           $ssort = $_GET['sort'];
           $sorder = in_array($_GET['order'],$aallowed) ? $_GET['order'] : "desc";
           if(array_key_exists($_GET['sort'],$this->asort_field)){
              $asort = array(
                "field" => $this->asort_field[$ssort],
                "order" => $sorder
              );
              return $asort;
           }else{
              $asort = array(
                "field" => $this->asort_field[$adefault[0]],
                "order" => $this->adefault_field[$adefault[0]]
              );
           
              return $asort;
           }
       }else{
         $asort = array(
            "field" => $this->asort_field[$adefault[0]],
            "order" => $this->adefault_field[$adefault[0]]
         );        
         return $asort;
       }
   }
   
   public function sql_limit($itotal_rows, $iper_page, $spage_acr = "page", $srow_acr = "row" )
   {
      $ipage = isset($_GET[$spage_acr]) ? $_GET[$spage_acr] : 1;
      $irow = isset($_GET[$srow_acr]) ? $_GET[$srow_acr] : $iper_page;
      $ifilter_page = filter_var($ipage,FILTER_VALIDATE_INT);
      $ifilter_row = filter_var($irow,FILTER_VALIDATE_INT);
      $irow = (!$ifilter_row) ? $iper_page : $irow;
      $ipage = (!$ifilter_page) ? 1 : $ifilter_page;
      $itotal_page = ceil($itotal_rows/$irow);

      if( $ipage > $itotal_page ) {
         $ipage = $itotal_page;
      }
      
      if( $itotal_rows==0 || $ipage <=0 ) {
         $ipage = 1;
      }
      
      $ioffset = ( $ipage - 1 ) * $irow;
      
      $alimit = array(
         'offset' => $ioffset,
         'limit' => $irow
      );
      return $alimit;

   }

   public function list_sorter($skey = "",$ssort_default = "desc")
   {
      $aqry_array = array();
      $anew_qry = array();
      $sqry = $_SERVER['QUERY_STRING'];

      $anew_field = array_merge(array('sort'=>'sort','order'=>'order'),$this->asort_field);
      $this->asort_field = $anew_field;
      $this->sdefault_sort = $ssort_default;

      if($this->asort_field){
         $aallowed = array("desc","asc");
         $ssort_default = strtolower($ssort_default);
         $ssort = "";
         if(!in_array($ssort_default,$aallowed)){
            return false;
         }

         if( ( isset($_GET['sort']) && $_GET['sort']) && (isset($_GET['order']) && $_GET['order']) ) {
		 
            if(in_array($_GET['order'],$aallowed)){
               $ssort = ($_GET['order']=="desc") ?  "asc" : "desc";
            }else{
              $ssort = $ssort_default;
            }
         
		 }else{
            
			if( $ssort_default = "desc" ) {
               $ssort = "desc";
            }else{
               $ssort = $aallowed[0] ;
            }
         }
         parse_str($sqry,$aqry_array);
         if( $aqry_array ) {
           foreach( $aqry_array as $key=>$val ) {
               if(!array_key_exists($key,$this->asort_field)){
                  $anew_qry[$key] = $val;
               }
           }
         }
         $afield = array_keys($this->asort_field,$this->asort_field[$skey]);

         $sseparator = ($anew_qry) ? "&" : "";
         $sqry_string = $sseparator . http_build_query( $anew_qry,'&');

         return "?sort={$afield[0]}&order=" . urlencode($ssort) .  $sqry_string;
      }
   }

   public function curl_request( $url , $param = null)
   {
      $cl = curl_init();
      $opts[CURLOPT_RETURNTRANSFER] = 1;
      $opts[CURLOPT_URL] = $url;

      if(is_null($param) === false){
         $opts[CURLOPT_POST] = true;
         $opts[CURLOPT_POSTFIELDS] = $param;
      }
      curl_setopt_array($cl, $opts);
      return curl_exec($cl);
  }

   public function form_submit( $aoptions )
   {
      $ainfo['aoptions'] = $aoptions;
      $this->CI->session->set_userdata(array('form-token' => md5(uniqid(rand(), true))));
      $ainfo['sform_token'] =  $this->CI->session->userdata('form-token');
      if( is_array( $aoptions ) ) {
         $sform = $this->CI->load->view('core/form-submit',$ainfo,TRUE);
         echo $sform;
      }
   }
   
   public function limit_string($sstr , $sper_str , $sstyle = '...')
   {
      $countStr = strlen($sstr);
      $resultStr = '';

      if ( $countStr < $sper_str ) {
         return $sstr;
      } else {
        for( $i = 0 ; $i < $sper_str ; $i++ ) {
           if( $i <= $sper_str  ) {
             $resultStr .= $sstr [$i];
           }
        }
        return $resultStr . $sstyle;
      }
   }
   
   public function set_message($smessage = "Message Here",$sunique_name = "core-message-server",$smessage_type)
   {
      if($smessage_type==="success"){
         $sclass_name = "core-message-success";
      }elseif($smessage_type==='warning'){
         $sclass_name = "core-message-warning";
      }else{
         $sclass_name = "core-message-success";      
      }
      $sunique = md5($sunique_name);
      if( $sunique_name!=$this->CI->session->userdata('unique') ) {
         $this->CI->session->set_userdata(array("message{$sunique}" =>$smessage,$sunique => $sunique,"message_type{$sunique}"=>$smessage_type,"class_name{$sunique}"=>$sclass_name));
      }
   }
   
   public function get_message($sunique_name = "core-message-server")
   {
      $sunique = md5($sunique_name);
      if( $sunique==$this->CI->session->userdata($sunique)){
         $adata["message"] = $this->CI->session->userdata('message'.$sunique);
         $adata["message_type"] = $this->CI->session->userdata('message_type'.$sunique);
         $adata["class_name"] = $this->CI->session->userdata('class_name'.$sunique);
         $adata["unique"] = $sunique;
         $this->CI->session->unset_userdata(array($sunique => ''));
         $this->CI->load->view("core/message",$adata);
      }
   }
   
   public function get_mime_type($sfile)
   {
      $mime_types = array(
         "pdf"=>"application/pdf"
         ,"exe"=>"application/octet-stream"
         ,"zip"=>"application/zip"
         ,"docx"=>"application/msword"
         ,"doc"=>"application/msword"
         ,"xlsx"=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
         ,"xls"=>"application/vnd.ms-excel"
         ,"ppt"=>"application/vnd.ms-powerpoint"
         ,"gif"=>"image/gif"
         ,"png"=>"image/png"
         ,"jpeg"=>"image/jpg"
         ,"jpg"=>"image/jpg"
         ,"mp3"=>"audio/mpeg"
         ,"wav"=>"audio/x-wav"
         ,"mpeg"=>"video/mpeg"
         ,"mpg"=>"video/mpeg"
         ,"mpe"=>"video/mpeg"
         ,"mov"=>"video/quicktime"
         ,"avi"=>"video/x-msvideo"
         ,"3gp"=>"video/3gpp"
         ,"css"=>"text/css"
         ,"jsc"=>"application/javascript"
         ,"js"=>"application/javascript"
         ,"php"=>"text/html"
         ,"htm"=>"text/html"
         ,"html"=>"text/html"
         ,"txt" => "text/plain"
         ,"xml" => "application/xml"
         ,"xsl" => "application/xml"
         ,"tar" => "application/x-tar"
         ,"swf" => "application/x-shockwave-flash"
         ,"odt" => "application/vnd.oasis.opendocument.text"
         ,"ods" => "application/vnd.oasis.opendocument.spreadsheet"
         ,"odp" => "application/vnd.oasis.opendocument.presentation"
      );

      $extension = strtolower(end(explode('.',$sfile)));
      return $mime_types[$extension];
   }

   public function vd($var)
   {
      echo "<pre>";
      var_dump($var);
      echo "</pre>";
   }   

   public function file_size($ifile_size)
   {
       if ($ifile_size < 1024) {
           return array($ifile_size, 'B');
       } elseif ($ifile_size < 1048576) {
           return array(round($ifile_size / 1024, 2),'KiB');
       } elseif ($ifile_size < 1073741824) {
           return array(round($ifile_size / 1048576, 2),'MiB');
       } elseif ($ifile_size < 1099511627776) {
           return array(round($ifile_size / 1073741824, 2), 'GiB');
       } elseif ($ifile_size < 1125899906842624) {
           return array(round($ifile_size / 1099511627776, 2),'TiB');
       } elseif ($ifile_size < 1152921504606846976) {
           return array(round($ifile_size / 1125899906842624, 2),'PiB');
       } elseif ($ifile_size < 1180591620717411303424) {
           return array(round($ifile_size / 1152921504606846976, 2),'EiB');
       } elseif ($ifile_size < 1208925819614629174706176) {
           return array(round($ifile_size / 1180591620717411303424, 2),'ZiB');
       } else {
           return array(round($ifile_size / 1208925819614629174706176, 2),'YiB');
       }
   }
}


function vd($var = "")
{
   echo"<pre>";
   var_dump($var);
   echo"</pre>";
}

function valid_date( $input, $date_format ) {

   $input = trim($input);
   $time = strtotime($input);

   $is_valid = date($date_format, $time) == $input;

   return $is_valid ? true : false;
}

function validateIpAddress($ip){
    return long2ip(ip2long($ip))==$ip;
}

function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}