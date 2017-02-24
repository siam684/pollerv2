<?php ini_set('display_errors',1);  error_reporting(E_ALL);
	
	if(isset($_POST['functionName']))
	{
		if($_POST['functionName']=='createFile')
		{
			$file = fopen($_POST['pageName']."/voteCounts.json",'w');
			fwrite($file,$_POST['songIds']);
			fclose($file);
		}
	}
?>