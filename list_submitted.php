<?php 
include 'db_functions.php';

print_r($_POST);
$filename = $_POST["list_name"];
$tableName = str_replace(" ","_",$filename);
$submittedLoginPw = $_POST["listPassword"];
$submittedAdminPw = $_POST["adminPassword"];

$tableColumnString = "loginpw VARCHAR(12), adminpw VARCHAR(12)";

$r = new DbRequests($_SERVER['SCRIPT_FILENAME']);
if(!$r->createTable($tableName, $tableColumnString))
{
	echo file_get_contents('failedTableCreation.php');
	exit();
}

$r->db_insertRecord($tableName,"`loginpw`, `adminpw`","'".$submittedLoginPw."', '".$submittedAdminPw."'");
$r->closeIt();

mkdir($filename);
$adminPage = fopen($filename."/player.php", 'wa+');
$voterPage = fopen($filename."/index.php", 'wa+');
$adminPageLog = fopen($filename."/log.txt", 'a+');
$htaccess = fopen($filename.'/.htaccess','wa+');

fwrite($htaccess,'


	Options +FollowSymlinks -MultiViews +Indexes
	RewriteEngine On
	
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^([^\.]+)$ $1.aspx [NC,L]
	
	
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^([^\.]+)$ $1.php [NC,L]
	
	
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^([^\.]+)$ $1.html [NC,L]

	DirectoryIndex index.html
	DirectoryIndex index.php
		
		');


fwrite($adminPage,"
		
<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">

<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
<title>".$filename."</title>
		
		
<template id='songHolderTemplate'>
	<div id='playlistSongHolder' onclick='alertIt(event)'>
		<table style='width:100%;  vertical-align:top;'>
			<tr>
				<td  style='text-align:left;  width:80%;'><span id='songName'></span></td>
				<td  style='text-align:right; width:20%;vertical-align:top'><span id='songDuration'></span></td>
			</tr>
		</table>
		<source id='songSrc' src='' type='audio/mp3'/>
	</div>
</template>

<template id='voterSongListTemplate'>
	<div style='height:50px;background-color:#ff4d4d;display:flex;justify-content:center;align-items:center;margin:10px;'>
		<span id='songName'></span>
	</div>
</template>
		
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js' type='text/javascript'></script>
<script src='http://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
<script src='../coder.js'></script>
<script type='text/javascript'>
	
	var countOfSongDivs = 0;
	var tempduration;
	var tempGlobali;
	var currentlyPlayingDiv;
	var arrayOfsongs = new Array();
	var playListDivID = 'drop_zone';
	var playListDiv;
	var voterDivID = 'voterDiv';
	var voterDiv;
	var lastColumnAdded;
	var tableName = '".$tableName."';
	var pollResults;
	var intervalCallToGetPollList;    
    var listOfSongNames = new Array();
    var firstInsertDone = false;
		
			
	$('document').ready(function(){	  
		var dropZone = document.getElementById('drop_zone');
		document.getElementById('listNodes').addEventListener('click',function(){listChildrenDivs(playListDivID);} , false);
		document.getElementById('listVoterDiv').addEventListener('click',function(){listChildrenDivs(voterDivID)} , false);
		document.getElementById('updatePlayListButton').addEventListener('click',function(){getPollResults()} , false);
		document.getElementById('publish').addEventListener('click',function(){updateVoterList()} , false);
		dropZone.addEventListener('dragover', handleDragOver, false);
		dropZone.addEventListener('drop', handleFileSelect, false);
		playListDiv = document.getElementById(playListDivID);
		voterDiv = document.getElementById(voterDivID);
		//alert(voterDiv.id);
		
		
		$('#player').on('ended',function(){			
			console.log('song ended');
			loadNextSong();
		});
		
		$( '#'+voterDivID ).sortable();
		
		getPollResults();
		
	});
		
		function Song(name, src, dur, divId)
	{
		this.name = name;
		this.src = src;
		this.dur = dur;
		this.divId = divId;
	}
	
	function tempDivIDObject(id, pos)
	{
		this.id = id;
		this.pos = pos;
	}
	
	
	function getPollResults()
	{
		var hr = new XMLHttpRequest();
		var jsonRankingsObject;
		var orderResultsArray = new Array();
		pollResults = new Array();
		var url = '../db_functions.php';
		hr.open('POST', url, true);
		var postValues = 'functionName=getSongRanks&tableName='+tableName;
		hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		hr.onreadystatechange = function() 
		{
			if(hr.readyState == 4 && hr.status == 200) 
			{
				//alert(hr.responseText);
				jsonRankingsObject = JSON.parse(hr.responseText);
				console.log(jsonRankingsObject);
				
				
				for(key in jsonRankingsObject)
				{
					var divId = listOfSongNames[key];
					var pos = jsonRankingsObject[key];
					
					console.log(divId);
					console.log(pos);
					tempObject = new tempDivIDObject(divId,pos);
					orderResultsArray.push(tempObject);
				}
				
				orderResultsArray.sort(function(a,b){return a.pos-b.pos});
				
				for(var i = orderResultsArray.length-1; i>=0;i--)
				{
					console.log(i + ' ' + orderResultsArray[i].id);
					console.log(i + ' ' + orderResultsArray[i].pos);
					if(orderResultsArray[i].id!='pw1'&&orderResultsArray[i].id!='pw2')
					
					if(orderResultsArray[i].pos!=0)
					{
						pollResults.unshift(orderResultsArray[i].id);
					}
					else
					{
						pollResults.push(orderResultsArray[i].id);
					}
				}
				
				for(var index = 0; index<pollResults.length;index++)
				{
					console.log(pollResults[index]);
				}
		
				updatePlayList();
			}
		}
		hr.send(postValues);
	}
	
	function updatePlayList()
	{
		//remove all divs from playlist except for the now playing one
		//add divs back in order of pollresults except for now playinging
		
		for(var i = 0; i<pollResults.length;i++)
		{
			var div = document.getElementById(pollResults[i]);
			if(div!=currentlyPlayingDiv&&div.parentNode.id=='drop_zone')
			{
				playListDiv.appendChild(div);
			}
		}
		
	}
		
		
	function startReadingVotes()
	{
		alert('starting interval calls');
		intervalCallToGetPollList = setInterval(getPollResults, 10000);
	}


	function updateVoterList()
	{	
		var list = listChildrenDivs(playListDiv);
		var data1 = new Array(); 

		
		for(var i = 0; i<list.length;i++)
		{
			data1.push(list[i].id);
		}

		console.log(data1);
			
		$.ajax({
			  type: 'POST',
			  url: '../updateVoteList.php',
			  data: 'tableName=".$tableName."&pageName='+'".$filename."&'+'songName='+JSON.stringify(data1),
			  success: function(){startReadingVotes()}
			});
		
		startReadingVotes();
	}
	
	function addColumns(sqlString)
	{
		console.log(sqlString);
		var hr = new XMLHttpRequest();
		var url = '../db_functions.php';
		hr.open('POST', url, true);
		var postValues = 'functionName=db_addColumn&tableName='+tableName+'&columns='+sqlString;
		hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		hr.onreadystatechange = function() 
		{
			if(hr.readyState == 4 && hr.status == 200) 
			{
				console.log(hr.responseText);
			}
		}
		hr.send(postValues);
		
		/*
		
		for first song added to list where divcount = 0
		alter table add 'song title encoded' varchar(35) first;
		saved the 'song title encoded' as variable showing it was the last column added
		
		for subesquent songs 
		alter table add 'song title  1 encoded' varchar(35) after 'saved last col added', 
		add 'song title 2 encoded' varchar(35) after 'song title  1 encoded',.........., 
		add 'song title n' varchar(35) after 'song title n-1'
		
		after each use of the last column added variable update it to the latest column/song name 
		then use it again
		
		
		f.name().replace('.mp3','');
		global variable -> var lastColumnAdded;
		outside for loop var addCulumnSql = '';
		
		inside for loop ---------------------------------
		if(divcount=0)
		{
			lastColumnAdded = encodeit(f.name().replace('.mp3',''));
			addCulumnSql = 'add ' + lastColumnAdded + ' varchar(35) first';
		}
		else
		{
			var incomingCol = encodeit(f.name().replace('.mp3',''));
			addCulumnSql = 'add ' + incomingCol + ' varchar(35) after ' + lastColumnAdded;
			lastColumnAdded = incomingCol;
		}
		
		after for loop ----------------------------------------------
		
		call addCoulmuns passing addColumnSql string 
		
		addcolumns makes xmlhttprequest to db_functions passing name of table and sqp string and 
		function to call which is db_addColumn
		*/
	}
	
	function addTitleToVoteList(songTitle)
	{
		var tempVoterSongDiv = getObjectOfTemplate('voterSongListTemplate').querySelector('div');
		tempVoterSongDiv.id = songTitle;
		getNameSpanOf(tempVoterSongDiv).innerHTML = decodeIt(songTitle);
		voterDiv.appendChild(tempVoterSongDiv);
		//alert(tempVoterSongDiv);
		/*
		
		tempSpan.id = songTitle;
		tempSpan.innerHTML = decodeIt(songTitle);
		if(countOfSongDivs!=0)
		{
			voterDiv.appendChild(document.createElement('br'));
		}
		voterDiv.appendChild(tempSpan);
		
		*/
	}
	
	function getObjectOfTemplate(templateId)
	{
		var t = document.querySelector('#'+templateId);
		var templateObject = document.importNode(t.content, true);
		return templateObject;
	}
	
	function handleFileSelect(evt) 
	{
		
		var files;
        
        if(evt instanceof FileList)
        {
            files = evt;
        }
        else if(evt instanceof DragEvent)
        {
            evt.stopPropagation();
		    evt.preventDefault();		
		    files = evt.dataTransfer.files;
        }
        
		var addCulumnSql = '';
		var firstLine = true;
		for (var i = 0, f; f = files[i]; i++) 
		{
			if(f.type!='audio/mp3')
            {
                alert(f.name + ' is not a mp3 audio file.');
                continue;
            }
            
            if(f.name.length>128)
            {
                alert(f.name + ' is greater than 128 characters.');
                continue;
            }
            
            var blobsrc = URL.createObjectURL(f);
			var tempId = encodeIt(f.name.replace('.mp3',''));
			//alert(blobsrc);
			//var t = document.querySelector('#songHolderTemplate');
			//var tempSongDiv = document.importNode(t.content, true);
			var tempSongDiv = getObjectOfTemplate('songHolderTemplate');
			tempSongDiv.getElementById('songName').innerHTML = f.name.replace('.mp3',''); 
			tempSongDiv.getElementById('playlistSongHolder').id = tempId;
			tempSongDiv.getElementById('songSrc').src = blobsrc;
			$('#'+playListDivID).append(tempSongDiv);
			
			listOfSongNames.push(tempId);
			
			if(countOfSongDivs==0)
			{
				lastColumnAdded = tempId;
				addTitleToVoteList(lastColumnAdded);
				addCulumnSql = 'ADD `' + countOfSongDivs + '` INT FIRST';
				
				document.getElementById('mp3Source').src = blobsrc;
				var audio = document.getElementById('player');
				audio.load();
				
				
				//audio.play();
				
				//alert(currentlyPlayingDiv);
				setNowPlaying(document.getElementById(tempId));
			}
			else
			{
				var incomingCol = tempId;
				addTitleToVoteList(incomingCol);
                
                var colon = '';
                
                if(firstInsertDone&&firstLine)
                {
                     firstLine =false;
                }
                else
                {
                    colon = ',';
                }
                
                
				addCulumnSql = addCulumnSql + colon +' ADD `' + countOfSongDivs +'` INT after `' + (countOfSongDivs-1)+'`';
				lastColumnAdded = incomingCol;
			}
			
			updateDuration(f.name, blobsrc, tempId);
			console.log(addCulumnSql);
			countOfSongDivs++;
		}
		
		addColumns(addCulumnSql);
	}
	
	
	
	function updateDuration(name, src, divname)
	{
		var tempAudio = new Audio();
		tempAudio.src = src;
		tempAudio.addEventListener('loadeddata', function() 
		{
			//console.log('Audio data loaded');
			//console.log('Audio duration: ' + this.duration);
			tempduration = this.duration;
			//console.log(tempduration + ' ' + divname);
			
			
			document.getElementById(divname).querySelector('#songDuration').innerHTML =' ' + sToMS(tempduration);
			//var tempSong = new Song(name,src,tempduration,divname);
			//arrayOfsongs.push(tempSong);
		});		
	}
	
	function sToMS( s ) 
	{
	    var minutes = parseInt( s / 60 );
	    var seconds = Math.round(s % 60);
	    if(seconds<=9)
    	{
	    	seconds = '0'+seconds;
    	}
	    return minutes+':'+seconds;
	}
	
	function handleDragOver(evt) 
	{
		evt.stopPropagation();
		evt.preventDefault();
		evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
	}
	 
	function alertIt(event)
	{
		//alert(event.target.parentElement.id);
		//alert(event.target.parentElement.querySelector('#songSrc').src);
		alert(getNameSpanOf(event.target.parentElement).innerHTML);
		alert(getDurationSpan(event.target.parentElement).innerHTML);
		alert(getSrc(event.target.parentElement));
	}
	
	 // Setup the dnd listeners.
	 
	
	 
	function setNowPlaying(divParent)
	{
		//alert(divParent.id);
		
		var songName = divParent.querySelector('#songName').innerHTML;
		console.log('in setnowPlaying attempting to insert now playing to '+divParent.id+' with value ' + songName);
		divParent.querySelector('#songName').innerHTML = 'Now Playing: ' + songName;
		
		console.log('new value for '+divParent.id+': ' + divParent.querySelector('#songName').innerHTML);
		currentlyPlayingDiv = divParent;
		console.log('set currently playing div: ' + currentlyPlayingDiv.id);
		//var listOfDivs = listChildrenDivs();
		
		//insertTop(currentlyPlayingDiv,listOfDivs[0]);
	}
	
	function clearNowPlaying()
	{
		//alert(divParent.id);
		console.log('in clearNowPlaying()');
		console.log('attempting to clear now play for div: '+currentlyPlayingDiv.id);
		var songName = currentlyPlayingDiv.querySelector('#songName').innerHTML;
		console.log('value for '+currentlyPlayingDiv.id+': '+ songName);
		currentlyPlayingDiv.querySelector('#songName').innerHTML = songName.replace('Now Playing: ','');;
		console.log('new value for '+currentlyPlayingDiv.id+': '+ currentlyPlayingDiv.querySelector('#songName').innerHTML);
	}
	 
	 function loadNextSong()
	 {
		 var listDiv = document.getElementById(playListDivID);
		 var audio = document.getElementById('player');

		 console.log('in loadNextSong');
		 var removedDiv = listDiv.removeChild(currentlyPlayingDiv);
		 console.log('removed Div from list:'+removedDiv.id);
		 listDiv.appendChild(removedDiv);
		 
		
		
		console.log('grabbed: '+ listDiv.id);
		console.log('grabbed: '+ audio.id);
		console.log('calling: listChildrenDivs()');
		var listOfDivs = listChildrenDivs(playListDiv);
		
		clearNowPlaying();
		
		console.log('geting src from div: '+listOfDivs[0].id);
		console.log('source is: '+ getSrc(listOfDivs[0]));
		document.getElementById('mp3Source').src = getSrc(listOfDivs[0]);
		
		audio.load();
		audio.play();
		//insertTop(listOfDivs[0],currentlyPlayingDiv);
		
		
		setNowPlaying(listOfDivs[0]);
		
	 }
	 
	 function showArray()
	 {
		 for (var i = 0;i<arrayOfsongs.length; i++) 
		{
			console.log(arrayOfsongs[i].name);
			console.log(arrayOfsongs[i].src);
			console.log(arrayOfsongs[i].dur);
			console.log(arrayOfsongs[i].divId);
		}
	 }
	 
	 function listChildrenDivs(divContainer)
	 {
		 //alert('in list div');
		 console.log('accepting div: '+divContainer + ' ' + divContainer.id);
		 console.log('in listChildrenDivs() length of list: '+ divContainer.childNodes.length);
		 var listOfDivs = new Array();
		 console.log('created new list array');
		 
		 var children = divContainer.childNodes;
		 console.log('grabbed: children divs of list div');
		 
		 for(var i =0; i<children.length;i++)
		 {
		 	var childTag = children[i].tagName;
		 	console.log('in listSOngsDiv for loop, child tag: ' + childTag);
			if(childTag!=undefined&&childTag==('DIV'))
	 		{
	 			//console.log(children[i].id);
	 			listOfDivs.push(children[i]);
	 			console.log('add to list array: ' + children[i].id);
	 		}
		 }
		 //console.log('length of listofdivs: '+listOfDivs.length);
		 //insertBottom(listOfDivs[0],listOfDivs[4]);
		 //insertTop(listOfDivs[0],listOfDivs[4]);
		 console.log('______________________________________________________________');
		 for(var i =0; i<listOfDivs.length;i++)
		 {
			 console.log('contents of index: '+ i);
			 console.log(listOfDivs[i].id);
			 console.log(getNameSpanOf(listOfDivs[i]).innerHTML);
		 }
		 
		 return listOfDivs;
	 }
	 
	 function getNameSpanOf(divcontainer)
	 {
		 return divcontainer.querySelector('#songName');
	 }
	 
	 function getDurationSpan(divcontainer)
	 {
		 return divcontainer.querySelector('#songDuration');
	 }
	 
	 function getSrc(divcontainer)
	 {
		 return divcontainer.querySelector('#songSrc').src;
	 }
	 
	 
	 function insertTop(topNode,bottomNode)
	 {
		 //var parent= document.getElementById(playListDivID);
		 //parent.insertBefore(topNode,bottomNode);
		 $(topNode).insertBefore(bottomNode);
	 }
	 
	 function insertBottom(topNode,bottomNode)
	 {
		 //var parent= document.getElementById(playListDivID);
		 //parent.insertBefore(topNode,bottomNode);
		 $(topNode).insertAfter(bottomNode);
	 }
	 
	 

</script>

</head>
	<body>
		<!-- 
		<div id='drop_zone'
			style='width: 100px; height: 100px; border-style: solid'>Drop
			files here
		</div>
		 -->
		<audio id='player' controls style='width:70%'>
   			<source id='mp3Source' type='audio/mp3' />
  			Your browser does not support the audio element.
		</audio><br>  <br> 
		
		  <div 	id='drop_zone'
		  		style='	width: 			70%; 
		  				height: 		auto;
		  				min-height: 	100px; 
		  				border-style: 	solid;
		  				border-width: 	.5px;'></div><br>
		<input type='button' value='list array' onclick='showArray()'/>
		<input id ='listNodes' type='button' value='list child nodes'/>
		<input type='button' value='next' onclick='loadNextSong()'/>
		<input id='listVoterDiv' type='button' value='list voter div'/>
		<input id='updatePlayListButton' type='button' value='update playlist'/>
		<input id='publish' type='button' value='publish'/>
        <input id='addSongsButton' type='file' value='Add Songs'  multiple='multiple' onchange='handleFileSelect(this.files)'/>
		<br><br>
		
		<div 	id='voterDiv' 
				style='	width: 			70%; 
		  				height: 		auto;
		  				min-height: 	100px; 
		  				border-style: 	solid;
		  				border-width: 	.5px;'><span id='songName'/></div>
		
		
	</body>
</html>
		
		
		");


fclose($adminPage);
fclose($voterPage);
fclose($adminPageLog);
fclose($htaccess);

function encodeIt($string)
{
	/*
	 *  ` = !1
	 *  " = !2
	 *  \ = !3
	 *  + = !4
	 *  ' = !5
	 *  - = !6
	 *  # = !7
	 *  . = !8
	 */
	$tempString = str_replace("`",	"!000!",$string);
	$tempString = str_replace("\"",	"!001!",$tempString);
	$tempString = str_replace("\\",	"!010!",$tempString);
	$tempString = str_replace("+",	"!011!",$tempString);
	$tempString = str_replace("\'",	"!100!",$tempString);
	$tempString = str_replace("-",	"!101!",$tempString);
	$tempString = str_replace("#",	"!110!",$tempString);
	$tempString = str_replace(".",	"!111!",$tempString);

	return $tempString;

}

function decodeIt($string)
{
	$tempString = str_replace("!000!",	"`",	$string);
	$tempString = str_replace("!001!",	"\"",	$tempString);
	$tempString = str_replace("!010!",	"\\",	$tempString);
	$tempString = str_replace("!011!",	"+"	,	$tempString);
	$tempString = str_replace("!100!",	"\'",	$tempString);
	$tempString = str_replace("!101!",	"-",	$tempString);
	$tempString = str_replace("!110!",	"#",	$tempString);
	$tempString = str_replace("!111!",	".",	$tempString);

	return $tempString;
}


?>