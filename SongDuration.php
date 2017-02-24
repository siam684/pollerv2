<?php ini_set('display_errors',1);  error_reporting(E_ALL);
	
	if(isset($_POST['functionName']))
	{
		if($_POST['functionName']=='setValue')
		{
			$file = fopen($_POST['pageName']."/currentSong.json",'w');
			fwrite($file,
	"{
\"currentSongPlaying\":
	{
		\"songName\":\"".$_POST['songName']."\",
		\"songDuration\":\"".$_POST['songDuration']."\",
		\"startTime\":\"".$_POST['startTime']."\"
	}
}");
			fclose($file);
		}
		
		if($_POST['functionName']=='getValue')
		{
			echo file_get_contents("../pollerv2/".$_POST['pageName'].".txt");
		}
	}
?>