<?php
/**
 * @file lib/Lazy.php
 */

class AudioCaptcha_Tests_Basic extends  Vc_Tests_Base {
 
  public function testSidAndCodeInvalid() {
    // code not allow character Bb, Nn , Zz
    try{
      for ($i=0; $i < 10; $i++) { 
        $vcaudio = new Vc_Audio();  
        $vcaudio->setCode("2sas");
        $vcaudio->setSid($i);
        $vcaudio->buildAudioCode();
      }
    }
    catch(Audio_Captcha_Invalid_Sid_Exception $e) {
      $msgSid = "Sid have to numberic";
    }
    catch(Audio_Captcha_Invalid_Code_Exception $e) {
      $msgCode = "Code have to string or not in character 'Bb', 'Nn', 'Zz'";
    }
    $this->assertTrue(empty($msgSid), 'Test invalid Sid');
    $this->assertTrue(empty($msgCode),  'Test invalid code');
  }
}
