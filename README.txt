
- When module audio_captcha works, it creates a folder named "vcaudio"  in file public system

- When audio_captcha works , it creates a mp3 file in {file public system}/vcaudio

- When cron runs, the files mp3 will be deleted.

- Lib is the folder used to test audio_captcha
  - File Basic.php is used to test library "Vc_Audio.php" to know whether it exports the mp3 captcha file in
  {file public system}/vcaudio or not.

  - File Folder.php is used to: 
    
      + To test whether "vcaudio" is created in folder {file public system}/vcaudio or not.

      + To test whether the delete files with the extension .mp3 are created in {file public system}/vcaudio or not