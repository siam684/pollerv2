<?php //ini_set('display_errors',1);  error_reporting(E_ALL);
require  '../../rev1.0.inc';

//include 'countingTableResults.php';

//$r = new DbRequests("testPage");
//echo $r->checkPasswordMatch("newList4", "admin", 2);
//echo $r->db_getPwForPage("newList4", "user");
//echo $r->db_insertRecord("newList4","`song 1`,`song 2`,`song 3`,`song 4`,`song 5`","1, 2, 3, 4, 5");
//echo  $colVal = $r->getColumn("`song 1`", "newList4");
//$r->closeIt();




if(isset($_POST['functionName']))
{
	if($_POST['functionName']=='getSongRanks')
	{
		//logIt("\nin branching switch: getSongRanks");
		$tableName = $_POST['tableName'];
		$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
		echo $r->getSongRanks($tableName);
		$r->closeIt();
	}
	
	if($_POST['functionName']=='checkPasswordMatch')
	{
		$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
		$r->logIt("\nin branching switch: checkPasswordMatch");
		$submittedPw = $_POST['submittedPw'];
		$tableName = $_POST['tableName'];
		$pwType =  $_POST['pwType'];
		if($r->checkPasswordMatch($tableName, $pwType, $submittedPw))
		{
			echo 'true';
		}
		else
		{
			echo 'false';
		}
		$r->closeIt();
	}
	
	if($_POST['functionName']=='db_insertRecord')
	{
		$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
		$r->logIt("\nin branching switch: db_insertRecord");
		$tableName = $_POST['tableName'];
		$columns = $_POST['columns'];
		$values =  $_POST['values'];
	
		echo $r->db_insertRecord($tableName,$columns,$values);
		$r->closeIt();
	}
	
	if($_POST['functionName']=='db_getPwForPage')
	{
		$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
		$tableName = $_POST['tableName'];
		$pwType =  $_POST['pwType'];
		echo $r->db_getPwForPage($tableName,$pwType);
		$r->closeIt();
	}
	
	if($_POST['functionName']=='db_addColumn')
	{
		$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
		$tableName = $_POST['tableName'];
		$cols = $_POST['columns'];
		echo $r->addColumn($tableName, $cols);
		$r->closeIt();
	}
}




class DbRequests
{
	var $conn;
	var $logger;
	var $globalPageName;
	
	function closeIt()
	{
		global $conn;
		global $logger;
		$conn->close();
		fclose($logger);
	}
	
	function logIt($string)
	{
		global $logger;
		fwrite($logger,date("\n"."M d y h:i:s a",time())." ".$string);
	}
	
    function logIt2($log,$string)
	{
		global $logger;
		fwrite($logger,date("\n"."M d y h:i:s a",time())." ".$string);
        fclose($log);
	}
    
    
	function __construct($pageName) 
	{
		global $conn, $logger, $globalPageName,$servername,$username,$password,$dbname;
		date_default_timezone_set("America/New_York");
		$globalPageName = $pageName;
		
		$logger = fopen("master_log_file.txt","a");
		$conn = new mysqli($servername, $username, $password, $dbname);
		
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
			$this->logIt($pageName." could not connect to ".$dbname.": ".$conn->connect_error);
		}
		else
		{
			//echo "connected";
			$this->logIt($pageName." connected to ".$dbname);
		}
		
	}
	
	function addColumn($tableName, $cols)
	{
		global $conn,$globalPageName;
        
        		
		$sql = "ALTER TABLE ".$tableName." ".$cols;
		//echo $sql."<br>";
		$this->logIt($globalPageName." attempting sql: ".$sql);
		if ($conn->query($sql) === TRUE)
		{
			//return $sql. " completed\n";
			$this->logIt($globalPageName." executed sql: ".$sql);
			return true;
		}
		else
		{
			//return "error from createTable: ".$conn->error;
			$this->logIt($globalPageName." sql error: ".$conn->error);
			return false;
		}
	}
	
	function createTable($tableName, $columns)
	{
		//global $servername, $username, $password, $dbname;
		//$conn = new mysqli($servername, $username, $password, $dbname);
		global $conn, $logger,$globalPageName;
	
		$sql = "CREATE TABLE ".$tableName." (".$columns.")";
		//echo $sql."<br>";
		$this->logIt($globalPageName." attempting sql: ".$sql);
		if ($conn->query($sql) === TRUE)
		{
			//return $sql. " completed\n";
			$this->logIt($globalPageName." executed sql: ".$sql);
			return true;
		}
		else
		{
			//return "error from createTable: ".$conn->error;
			$this->logIt($globalPageName." sql error: ".$conn->error);
			return false;
		}
	}
	
	function getColumn($column, $table)
	{
		//global $servername, $username, $password, $dbname;
		//$conn = new mysqli($servername, $username, $password, $dbname);
		global $conn,$globalPageName;
	
		$sql = "SELECT ".$column." FROM ".$table;
		$result;
		if ($result = $conn->query($sql) === TRUE)
		{
			//ptfl("New record created successfully");
			$this->logIt($globalPageName." executed sql: ".$sql);			
			return $result;
		}
		else
		{
			$this->logIt($globalPageName." sql error: ".$sql." ".$conn->error);
			return $sql + " failed";
		}
	
		$conn->close();
	}
	
	function db_insertRecord($tableName,$columns,$values)
	{
		//global $servername, $username, $password, $dbname;
		//$conn = new mysqli($servername, $username, $password, $dbname);
		global $conn, $globalPageName;
	
		str_replace('undefined','',$columns);
		str_replace('undefined','',$values);
	
		$sql = "INSERT INTO ".$tableName." (".$columns.") VALUES (".$values.")";
	
		
		if ($conn->query($sql) === TRUE)
		{
			//ptfl("New record created successfully");
			$this->logIt($globalPageName." executed sql: ".$sql);
			return true;
		}
		else
		{
			$this->logIt($globalPageName." failed execution sql: ".$sql." ".$conn->error);
			return false + " ";
		}
	
		//$conn->close();
	}
	
	
	
	
	/*
	 function db_insert($tableName, $column, $value)
	 {
	 $conn = new mysqli($servername, $username, $password, $dbname);
	
	 $sql = "INSERT INTO ".$tableName." (".$column.") VALUES ('".$value."')";
	
	 if ($conn->query($sql) === TRUE) {
	 return troe;
	 } else {
	 return $conn->error;
	 }
	
	 $conn->close();
	
	 }
	
	
	 function db_getCol($tableName, $column)
	 {
	 $conn = new mysqli($servername, $username, $password, $dbname);
	
	 $sql = "SELECT ".$column." from ".$tableName;
	
	 return $conn->query($sql);
	
	 $conn->close();
	 }
	 */
	
	
	function db_getPwForPage($tablename,$pwType)
	{
		//global $servername, $username, $password, $dbname;
		//$conn = new mysqli($servername, $username, $password, $dbname);

		global $conn, $globalPageName;
		
		$pw;
	
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		else
		{
			//echo "connection made<br>";
		}
		
		/*SELECT column_name FROM table_name
		 ORDER BY column_name ASC
		 LIMIT 1;*/
	
		if($pwType=="user")
		{
			//$sql = "SELECT loginpw from ".$tablename;
			$sql = "SELECT loginpw FROM ".$tablename."  LIMIT 1";
			$colName = "loginpw";
		}
	
		if($pwType=="admin")
		{
			//$sql = "SELECT adminpw from ".$tablename;
			$sql = "SELECT adminpw FROM ".$tablename."  LIMIT 1";
			$colName = "adminpw";
		}
	
		if($result = mysqli_query($conn, $sql))
		{
			$this->logIt($globalPageName." attempting to get ".$pwType.' password from '.$tablename.' succeeded');
		}
		else
		{
			$this->logIt($globalPageName." attempting to get ".$pwType.' password from '.$tablename.' failed');
		}
	
	
		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				//echo "login pw: " . $row[$colName]. "<br>";
				$pw = $row[$colName];
				//. " - adminpw: " . $row["adminpw"]
			}
		} else {
			//echo "0 results";
		}
	
		return $pw;
	}
	
	function checkPasswordMatch($tableName, $pwType, $submittedPw)
	{
		global $globalPageName;		
		
		$pw = $this->db_getPwForPage($tableName,$pwType);
		//echo "pw from checkPasswordMatch: ".$pw." <br>";
		//echo "pw from submittedPw: ".$submittedPw." <br>";
		if($submittedPw==$pw)
		{
			//echo "match<br>";
			$this->logIt($globalPageName." testing ".$tableName." for pw type: ".$pwType." submited: ".$submittedPw." match found");
			return true;
			
		}
		else
		{
			//echo "no match<br>";
			$this->logIt($globalPageName." testing ".$tableName." for pw type: ".$pwType." submited: ".$submittedPw." not matched");
			return false;
		}
	}


	/*********************Song Ranks Methods***********************/
	

	
	var $arrayOfValues= array();
	var $arrayOfColumnNames = array();
	var $arrayOfColAndHighest = array();
	var $result;
	

	function getSongRanks($tableName)
	{
		global $conn, $arrayOfColAndHighest, $arrayOfValues, $arrayOfColumnNames;
		//$conn = new mysqli($servername, $username, $password, $dbname);
		$arrayOfColumnNames = array();
		$arrayOfColAndHighest = array();
		$arrayOfValues= array();
		
		$sql = "SELECT * FROM ".$tableName." limit 0,1000";
		//$result = $conn->query($sql)
		//$result=mysqli_query($conn,$sql)
		if($result = $conn->query($sql))
		{
			$finfo = $result->fetch_fields();
	
			foreach ($finfo as $val)
			{
				$colName = $val->name;
				if($colName!="loginpw"&&$colName!="adminpw")
				{
					//echo $colName."<br>";
					array_push($arrayOfColumnNames,$colName);
				}
					
				//echo("Name: ".$val->name."<br>");
			}
		}
	
		foreach($arrayOfColumnNames as $colName)
		{
			//echo $colName.": ";
			mysqli_data_seek($result,0);
			$this->getColumnValues($result,$colName);
			//echo "<br>";
		}
		/*
		 foreach($arrayOfColAndHighest as $x => $x_value)
		 {
		 echo "key: ".$x." value: ".$x_value."<br>";
		 }
		 */
		$final = json_encode($arrayOfColAndHighest);
		mysqli_free_result($result);
	
		$arrayOfValues= null;
		$arrayOfColumnNames = null;
		$arrayOfColAndHighest = null;
		//$conn->close();
		return $final;
	
	}
	
	function getColumnValues($result,$column)
	{
		global $arrayOfColumnNames,$arrayOfColAndVal;
		//echo "from getColumnValues: ".$column. " ".mysqli_num_rows($result)." <br>";
		$arrayOfValues = array();
		if (mysqli_num_rows($result) > 0)
		{
			// output data of each row
			//$j=0;
			while($row = mysqli_fetch_array($result))
			{
				//echo "from getColumnValues while loop: ".$column. " <br>";
				//echo "login pw: " . $row[$colName]. "<br>";
				//echo $row[$column];
				array_push($arrayOfValues,$row[$column]);
					
				/*
				 * each time this for loop iterates its moving
				 *
				 for($i = 0;$i<count($arrayOfColumnNames);$i++)
				 {
				 echo "<br>".$arrayOfColumnNames[$i].": ";
				 echo $row[$arrayOfColumnNames[$i]];
				 $arrayOfColAndVal[$j][$i] = $row[$arrayOfColumnNames[$i]];
	
				 }
				 echo '<br>';
				 //. " - adminpw: " . $row["adminpw"]
				 $j++;*/
			}
	
		}
		else
		{
			//echo "0 results";
		}
	
	
		//$finfo = $result->fetch_fields();
	
		$this->findMostFrequent($arrayOfValues,$column);
	
	}
	
	function findMostFrequent($array,$col)
	{
		global $arrayOfColAndHighest;
		sort($array);
		/*
		 foreach ($array as $value)
		 {
		 echo $value." ";
		 }*/
	
		$previous = $array[0];
		$popular = $array[0];
		$count = 1;
		$maxCount = 1;
	
		for($i = 1; $i < count($array); $i++)
		{
			if($array[$i] == $previous)
				$count++;
				else
				{
					if ($count > $maxCount)
					{
						$popular = $array[$i-1];
						$maxCount = $count;
					}
					$previous = $array[$i];
					$count = 1;
				}
		}
	
		//echo '<br>Highest: ';
		$highest = $count > $maxCount ? $array[count($array)-1] : $popular;
		if($highest!=null)
		{
			//echo $highest.'<br>';
			$arrayOfColAndHighest[$col] = $highest;
	
		}
		else
		{
			//echo '0<br>';
			$arrayOfColAndHighest[$col] = 0;
		}
	}
}?>