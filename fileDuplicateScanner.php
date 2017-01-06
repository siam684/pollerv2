<?php  //ini_set('display_errors',1);  error_reporting(E_ALL);
//$logWriter = fopen('fileDuplicateLog.txt','a');

$dir    = getcwd();
$files = scandir($dir);
//$newFile = $_POST['fileName'];


$newFile = $_POST['listName'];

fwrite($logWriter,date("\n"."M d y h:i:s a",time())." [Directory: ".$dir."] [size of array: ".count($files)."] [newFile: ".$newFile."]");

echo  matchFound($newFile);

function matchFound($inFile)
{
	global $files;
    global $dir;
    global $logWriter;
	$matches = false;
	//print_r($files);
    
    //fwrite($logWriter,date("\n"."M d y h:i:s a",time())." Directory: ".$dir." size of array: ".count($files)." infile: ".$inFile);
    
	foreach($files as $file)
	{
		//fwrite($logWriter,date("\n"."M d y h:i:s a",time())." comparing: ".$file." against: ".$inFile);
        
        
        $file = str_replace('.php','',$file);
		$file = str_replace('.txt','',$file);

		if(strcmp($inFile,$file)==0)
		{
			$matches = true;
		}

	}
    //fclose($logWriter);
	return $matches;
}

?>