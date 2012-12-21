<?php

class AudioCaptcha_Tests_Folder extends Vc_Tests_Base {
    // action in hook install
    public function testFolderInvalid() {
      $folder_name = file_public_path_vcaudio();
      $check_folder = file_prepare_directory($folder_name);
      if (empty($check_folder)) {
        if (!@drupal_mkdir($folder_name, 0777)) {
           $this->assertTrue($check_folder, 'Check Not Create Folder');
        }
      }
    }
    
    public function testDeleteFileInvalid() {
      $folder_name = file_public_path_vcaudio();
      $check_folder = file_prepare_directory($folder_name);
      if ($check_folder) {
        $files = file_scan_directory($folder_name, '/.*.mp3/');
        foreach ($files as $file) {
            $check_delete = unlink($file->uri);
            $this->assertTrue($check_delete, "Delete file {$file->uri} complete");
        }
      }
    }
}
