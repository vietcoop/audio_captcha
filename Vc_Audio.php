<?php

class Vc_Audio {
  protected $code;
  protected $sid;
  public  function __construct() {
  }
  
  public function setCode($code) {
    $this->code = $code;
  }
  
  // position is count audio
  public function setSid($sid){
    $this->sid = $sid;
  }
  
  public function  GetAudio($file = '', $dir = 'mp3/') {
    $dir = dirname(__FILE__) . '/' . $dir;
    $file = strtolower($file); // make sure the filename is lowercase or the fileload might fail...
    $handle = fopen($dir . $file, "rb"); // Read as binary
    $size = filesize($dir . $file);
    // If PHP5 is being used, use stream_get_line() function, else use fread()
    if (function_exists('stream_get_line')) {
      $load = stream_get_line($handle, $size);  // Reads files faster than fread and fgets! ;)
    } 
    else {
      $load = fread($handle, $size);
    }
    fclose($handle);
    return array("mp3" => $load, "size" => $size);
  }
  
  public function buildAudioCode() {
    if(!is_numeric($this->sid) || $this->sid < 0) {
      throw new Audio_Captcha_Invalid_Sid_Exception();
    }
    
    if (empty($this->code) || !is_string($this->code) || !$this->invalidCode($this->code) || strlen($this->code) <= 2) {
      throw new Audio_Captcha_Invalid_Code_Exception();
    }
    
    $captcha = $this->code;
    $order = array("intro", $captcha{0});
    
    for ($i=1; $i < strlen($captcha)-1; $i++) { 
      $order[] = "and";
      $order[] = $captcha{$i};
    }
    
    $order[] = "finally";
    $order[] = $captcha{strlen($captcha)-1};
    
    foreach($order as $key => $value) {
      // Is it a number or a CAPITAL letter?
      if (ctype_digit($value)) {
        $audio[] = $this->GetAudio("number.mp3");    // to clarify it�s a coming number
      } 
      elseif (ctype_upper($value)) {
        $audio[] = $this->GetAudio("uppercase.mp3"); // to clarify a coming UPPERCASE letter
      } 
      elseif (strlen($value) == 1 && ctype_lower($value)) {
        $audio[] = $this->GetAudio("lowercase.mp3"); // to clarify a coming lowercase letter
      }
      //... and load the actual char!
      $audio[] = $this->GetAudio("$value.mp3");
    } 
    
    // Parse the soundfiles and sum the filesize
    // size[mp3]
    // size[size]
    $mp3 = array();
    $size = 0;
    foreach ($audio as $key => $value) {
      $mp3 .= $audio[$key]['mp3'];
      $size += $audio[$key]['size'];
    }
    unset($order, $audio);
    
    $folder_name = file_public_path_vcaudio();
    
    // $folder_name = getcwd() . "/sites/default/files/vcaudio";
    $check_folder = file_prepare_directory($folder_name);
    if(empty($check_folder)) {
      if (!@drupal_mkdir($folder_name, 0777)) {
       drupal_set_message('Do not create folder vcaudio');
      }
    }
        
    $filemp3= $folder_name . "/{$this->sid}.mp3";
    $f=file_put_contents($filemp3, $mp3);
  }
  
  public function invalidCode($code = '')
  {
    $allowed_chars = str_replace(" ", "", $code);
    $allowed_chars = str_split($allowed_chars);
    //Bb, Nn , Zz
    for($i = 0; $i < count($allowed_chars); $i++) {
      $isnumber = preg_replace("/[^0-9]/", 0,$allowed_chars[$i]);
      $not_allowed_chars = array("b", "n", "z" );
      // is string and lower not in array
      if(!$isnumber && in_array(strtolower($allowed_chars[$i]), $not_allowed_chars)) {
         return FALSE;
      }
    }
    return TRUE;
  }
}


/**
 * Exception of invalid service.
 */
class Audio_Captcha_Invalid_Code_Exception extends Exception {
  
}

/**
 * Exception of invalid Sid.
 */
class Audio_Captcha_Invalid_Sid_Exception extends Exception {
  
}
