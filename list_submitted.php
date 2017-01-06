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
		
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>


<html>

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
	<title>".$filename."</title>


	<template id='songHolderTemplate'>
	<div id='playlistSongHolder' onclick='alertIt(event)' class='songHolder'>
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

	<link rel='stylesheet' href='../votePage.css'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet'>

	<style>
		
		#addSongsButton {
			width: 0.1px;
			height: 0.1px;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			z-index: -1;
		}
		
		.butt {
			-moz-box-shadow:inset 0px 1px 0px 0px #9fb4f2;
			-webkit-box-shadow:inset 0px 1px 0px 0px #9fb4f2;
			box-shadow:inset 0px 1px 0px 0px #9fb4f2;
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #5bc0de), color-stop(1, #83daf2));
			background:-moz-linear-gradient(top, #5bc0de 5%, #83daf2 100%);
			background:-webkit-linear-gradient(top, #5bc0de 5%, #83daf2 100%);
			background:-o-linear-gradient(top, #5bc0de 5%, #83daf2 100%);
			background:-ms-linear-gradient(top, #5bc0de 5%, #83daf2 100%);
			background:linear-gradient(to bottom, #5bc0de 5%, #83daf2 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#83daf2',GradientType=0);
			background-color:#5bc0de;
			border:1px solid #ffffff;
			display:inline-block;
			cursor:pointer;
			color:#ffffff;
			padding:6px 24px;
			text-decoration:none;
		}
		.butt:hover {
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #83daf2), color-stop(1, #5bc0de));
			background:-moz-linear-gradient(top, #83daf2 5%, #5bc0de 100%);
			background:-webkit-linear-gradient(top, #83daf2 5%, #5bc0de 100%);
			background:-o-linear-gradient(top, #83daf2 5%, #5bc0de 100%);
			background:-ms-linear-gradient(top, #83daf2 5%, #5bc0de 100%);
			background:linear-gradient(to bottom, #83daf2 5%, #5bc0de 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#83daf2', endColorstr='#5bc0de',GradientType=0);
			background-color:#83daf2;
		}
		.butt:active {
			position:relative;
			top:1px;
		}

		.boxshadowed
		{
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		
		.midcontainer {
			padding-top:20px;
			background-color: white;
			margin-bottom: 10px;
			border-bottom-left-radius:10px;
			border-bottom-right-radius:10px;
		}
		
		.butonTableCell{
			height:10px;
		}
		
		html {
			height: 100%;
		}
		
		body {
			
			height: 100%;
		}
		
		.dropzone {
			width: 100%;
			height: auto;
			min-height: 100px;
			border-width: .5px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		
		.dropzoneflat {
			text-align: center;
			vertical-align: middle;
			font-family: Oswald, sans-serif;
			width: 100%;
			height: 100px;
			border-color: rgb(231, 82, 45);
			border-style: dotted;
			background-color: #5BC0DE;
			color: white;
			font-size: 20px;
			box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.0), 0 0px 0px 0 rgba(0, 0, 0, 0.0);
		}
		
		.songHolder {
			font-family: Oswald, sans-serif;
			width: 100%;
			height: 100%;
			padding: 15px;
			background-color: #5BC0DE;
			color: white;
			border-bottom: solid;
			border-width: 2px;
			border-color: white;
		}
		
		.nowplaying {
			background-color: #e7522d;
			color: white;
		}
		
		.nospacing {
			margin: 0px;
			padding: 0px;
			width: 100%;
			left;
			0px;
			right: 0px;
		}
		
		.pageTitle{
			font-size:100px;
		}
		
		
		input[type=range] {
			/*removes default webkit styles*/
			-webkit-appearance: none;
			padding: 8px 5px;
			background: transparent;
			transition: border 0.2s linear, box-shadow 0.2s linear;
			/*required for proper track sizing in FF*/
			width: 100%;
		}
		
		input[type=range]:active {
			border-color: rgba(82, 168, 236, 0.8);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		}
		
		input[type=range]:focus {
			outline: none;
		}
		
		input[type=range]::-webkit-slider-runnable-track {
			width: 100%;
			height: 5px;
			background: #5bc0de;
			border: none;
			border-radius: 10px;
		}
		
		input[type=range]::-webkit-slider-thumb {
			-webkit-appearance: none;
			border: none;
			display: block;
			height: 16px;
			width: 16px;
			border-radius: 50%;
			border: 1px solid #ddd;
			background: #fafafa;
			cursor: pointer;
			margin-top: -5px;
		}
		
		input[type=range]::-webkit-slider-thumb:hover {
			background-position: 50% 50%;
		}
		
		input[type=range]:focus::-webkit-slider-runnable-track {
			background: #5bc0de;
		}
		
		input[type=range]::-moz-range-track {
			width: 100%;
			height: 5px;
			background: #5bc0de;
			border: none;
			border-radius: 3px;
		}
		
		input[type=range]::-moz-range-thumb {
			border: none;
			display: block;
			height: 16px;
			width: 16px;
			border-radius: 50%;
			border: 1px solid #ddd;
			background: #fafafa;
			cursor: pointer;
			margin-top: -5px;
		}
		/*hide the outline behind the border*/
		
		input[type=range]:-moz-focusring {
			outline: 1px solid white;
			outline-offset: -1px;
		}
		
		input[type=range]::-ms-track {
			width: 100%;
			height: 5px;
			background: transparent;
			/* Hides the slider so custom styles can be added */
			border-color: transparent;
			border-width: 7px 0;
			color: transparent;
		}
		
		input[type=range]::-ms-fill-lower {
			background: #5bc0de;
			border-radius: 10px;
		}
		
		input[type=range]::-ms-fill-upper {
			background: #5bc0de;
			border-radius: 10px;
		}
		
		input[type=range]::-ms-thumb {
			border: none;
			height: 16px;
			width: 16px;
			border-radius: 50%;
			border: 1px solid #ddd;
			background: #fafafa;
		}
		
		.verticalCenterDiv {
			height: 30px;
			margin-bottom: 22px;
		}
	</style>

	<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js' type='text/javascript'></script>
	<script src='http://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src='../coder.js'></script>
	<script type='text/javascript'>
    
		var countOfSongDivs = 0;
		var tempduration;
		var tempGlobali;
		var currentlyPlayingDiv;
		var arrayOfsongs = new Array();
		var playListDivID = 'drop_zone';
		var playListDiv;

		var lastColumnAdded;
		var tableName = '".$tableName."';
		var pollResults;
		var intervalCallToGetPollList;
		var listOfSongNames = new Array();
		var firstInsertDone = false;
		var pollResultsTrackerArray = new Array(0);
		var listDidNotChange;
		var changedIndex;
		var doneUpdateingPerSong = false;

		var player;
		var playButtonImage = 'https://maxcdn.icons8.com/iOS7/PNG/25/Media_Controls/play_filled-25.png';
		var pauseButtonImage = 'https://maxcdn.icons8.com/iOS7/PNG/25/Media_Controls/pause_filled-25.png';
		var mutedImage = 'https://maxcdn.icons8.com/iOS7/PNG/25/Media_Controls/mute_filled-25.png';
		var unmutedImage = 'https://maxcdn.icons8.com/iOS7/PNG/25/Mobile/speaker_filled-25.png';
		var zero;


		$('document').ready(function() {
			var dropZone = document.getElementById('drop_zone');
			document.getElementById('publish').addEventListener('click', function() {
				updateVoterList()
			}, false);
			dropZone.addEventListener('dragover', handleDragOver, false);
			dropZone.addEventListener('drop', handleFileSelect, false);
			playListDiv = document.getElementById(playListDivID);

			player = document.getElementById('player');
			playPuaseImage = document.getElementById('playPuaseImage');
			playPauseContainer = document.getElementById('playPauseContainer');
			currentTimeSpan = document.getElementById('currentTimeSpan');
			durationSpan = document.getElementById('durationSpan');
			seekBar = document.getElementById('seekBar');
			muteUnmuteContainer = document.getElementById('muteUnmuteContainer');
			muteUnmuteImage = document.getElementById('muteUnmuteImage');
			player.volume = .3;




			$('#player').on('ended', function() {
				loadNextSong();
				doneUpdateingPerSong = false;
			});

			$('#player').on('timeupdate', function() {
				console.log(player.duration - player.currentTime);

				var remainingTime = player.duration - player.currentTime;

				if ((remainingTime < 30) && !doneUpdateingPerSong) {
					getPollResults();
					doneUpdateingPerSong = true;
				}
			});

			$('#player').on('loadeddata', function() {
				sendCurrentSong();
			});

			$('#player').on('play', function() {
				sendCurrentSong();
			});


			$(player).on('loadedmetadata', function() {
				currentTimeSpan.innerHTML = getFormattedTime(player.currentTime);
				durationSpan.innerHTML = getFormattedTime(player.duration);
				seekBar.max = player.duration;
			});

			$(player).on('timeupdate', function() {
				currentTimeSpan.innerHTML = getFormattedTime(player.currentTime);
				seekBar.value = player.currentTime;
			});

			$(seekBar).on('mouseup', function() {
				player.currentTime = seekBar.value;
			});

			$(volumeBar).on('mouseup', function() {
				player.volume = volumeBar.value / 100;
			});

			$(playPauseContainer).on('click', function() {
				if (player.paused) {
					playPuaseImage.src = pauseButtonImage;
					player.play();
				} else {
					playPuaseImage.src = playButtonImage;
					player.pause();
				}
			});

			$(muteUnmuteContainer).on('click', function() {
				if (player.muted) {
					muteUnmuteImage.src = unmutedImage;
					player.muted = false;
				} else {
					muteUnmuteImage.src = mutedImage;
					player.muted = true;
				}
			});
      getPollResults();
		});

		function getFormattedTime(sec) 
    {
			minutes = Math.floor((sec % 3600) / 60);
			seconds = Math.floor(sec % 60);
			if (seconds < 10) 
      {
				zero = '0';
			} 
      else 
      {
				zero = '';
			}
			return minutes + ':' + zero + seconds;
		}

		function sendCurrentSong() 
    {
			var startTimeValue = Date.now() / 1000.0;

			if (currentlyPlayingDiv) 
      {
				var currentSong = currentlyPlayingDiv.querySelector('#songName').innerHTML;
				$.post('../SongDuration.php', {
						functionName: 'setValue',
						pageName: tableName,
						songName: currentSong.replace('Now Playing: ', ''),
						startTime: startTimeValue,
						songDuration: player.duration - player.currentTime
					},
					function(data, status) {
						//alert(status+ ' '+data);
					});
			}
		}

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
			var postValues = 'functionName=getSongRanks&tableName=' + tableName;
			hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			hr.onreadystatechange = function() 
      {
				if (hr.readyState == 4 && hr.status == 200) {
					jsonRankingsObject = JSON.parse(hr.responseText);
					$.each(jsonRankingsObject, function(index, position) 
          {
						pollResults[position - 1] = listOfSongNames[index];
					});
          
					pollResults.forEach(function(item, index) 
          {
						if (item != pollResultsTrackerArray[index]) 
            {
							
							if (hasBeenVotedUp(item, index)) 
              {
								console.log(item + 'has gone to position ' + index);
								putSongInSlot(item, index);
							}
							listDidNotChange = false;
						}
					});
					pollResultsTrackerArray = pollResults.slice(0);      
				}
			}
			hr.send(postValues);
		}
		/*
		compare incoming array index value against its previous position using pollresults array as incoming and 
		pollresultstracker array as previous position
    
		incoming song id is the compared against song id's on tracker array
		*/
		function hasBeenVotedUp(incomingSongId, incomingSongIndex) 
    {
			var isVotedUp = false;
			pollResultsTrackerArray.forEach(function(trackerSongId, trackerIndex) 
      {
				if (trackerSongId == incomingSongId) {
					if (incomingSongIndex < trackerIndex) {

						isVotedUp = true;
					}
				}
			});
			return isVotedUp;
		}

		function putSongInSlot(incomingDivId, incomingIndex) 
    {
			incomingIndex += 1;
			/*
			for each div in list of playlist songs add it back but if the current index is the index of the incoming song
			add the incoming song first
			*/
			var playList = listChildrenDivs(playListDiv);

			playList.forEach(function(songDiv, playlistIndex) 
      {
				if (songDiv.id == incomingDivId) 
        {
					playList.splice(playlistIndex, 1);
				}
			});

			playList.splice(incomingIndex, 0, document.getElementById(incomingDivId));
			playList.forEach(function(songDiv, playlistIndex) 
      {
				playListDiv.appendChild(songDiv);
			});
		}


		function updatePlayList() {
			//remove all divs from playlist except for the now playing one
			//add divs back in order of pollresults except for now playinging
			for (var i = 0; i < pollResults.length; i++) {
				var div = document.getElementById(pollResults[i]);
				if (div != currentlyPlayingDiv && div.parentNode.id == 'drop_zone') {
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
      
			for (var i = 0; i < list.length; i++) {
				data1.push(list[i].id);
			}

			$.ajax({
				type: 'POST',
				url: '../updateVoteList.php',
				data: 'tableName=".$tableName."&pageName=' + '".$filename."&' + 'songName=' + JSON.stringify(data1)
			});
		}

		function addColumns(sqlString) 
    {
			var hr = new XMLHttpRequest();
			var url = '../db_functions.php';
			hr.open('POST', url, true);
			var postValues = 'functionName=db_addColumn&tableName=' + tableName + '&columns=' + sqlString;
			hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			hr.onreadystatechange = function() 
      {
        if (hr.readyState == 4 && hr.status == 200) 
        {
          //console.log(hr.responseText);
        }
			}
			hr.send(postValues);		
		}

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
		function getObjectOfTemplate(templateId) {
			var t = document.querySelector('#' + templateId);
			var templateObject;

			try {
				templateObject = document.importNode(t.content, true);
			} catch (err) {
				templateObject = document.createElement('document');
				$(t).clone(true).appendTo(templateObject);
			}
			return templateObject;
		}

		function handleFileSelect(evt) 
    {
			dz = document.getElementById('drop_zone');
			$(dz).removeClass('dropzoneflat');
			$(dz).addClass('dropzone');
			dz.removeChild(dz.querySelector('#dropzonemsg'));

			var files;

			if (evt instanceof FileList) 
      {
				files = evt;
			} 
      else if (evt instanceof DragEvent) 
      {
				evt.stopPropagation();
				evt.preventDefault();
				files = evt.dataTransfer.files;
			}

			var addCulumnSql = '';
			var firstLine = true;
			for (var i = 0, f; f = files[i]; i++) 
      {
				console.log(f.type);
				var acceptableType = false;
				if (f.type == 'audio/mp3' | f.type == 'audio/mpeg') {
					acceptableType = true;
				}

				if (!acceptableType) 
        {
					alert(f.name + ' is not a mp3 audio file.');
					continue;
				}

				if (f.name.length > 128) 
        {
					alert(f.name + ' is greater than 128 characters.');
					continue;
				}

				var blobsrc = URL.createObjectURL(f);
				var tempId = encodeIt(f.name.replace('.mp3', ''));
				var tempSongDiv = getObjectOfTemplate('songHolderTemplate');
				tempSongDiv = tempSongDiv.querySelector('div');
				tempSongDiv.querySelector('#songName').innerHTML = f.name.replace('.mp3', '');
				tempSongDiv.id = tempId;
				tempSongDiv.querySelector('#songSrc').src = blobsrc;
				console.log(tempSongDiv);
				$('#' + playListDivID).append(tempSongDiv);
				listOfSongNames.push(tempId);
				if (countOfSongDivs == 0) 
        {
					lastColumnAdded = tempId;
					addCulumnSql = 'ADD `' + countOfSongDivs + '` INT FIRST';
					document.getElementById('mp3Source').src = blobsrc;
					var audio = document.getElementById('player');
					audio.load();
					setNowPlaying(document.getElementById(tempId));
				} 
        else 
        {
					var incomingCol = tempId;
					var colon = '';
					if (firstInsertDone && firstLine) {
						firstLine = false;
					} else {
						colon = ',';
					}
					addCulumnSql = addCulumnSql + colon + ' ADD `' + countOfSongDivs + '` INT after `' + (countOfSongDivs - 1) + '`';
					lastColumnAdded = incomingCol;
				}
				updateDuration(f.name, blobsrc, tempId);
				countOfSongDivs++;
			}
			pollResultsTrackerArray = listOfSongNames.slice(0);
			addColumns(addCulumnSql);
		}



		function updateDuration(name, src, divname) 
    {
			var tempAudio = new Audio();
			tempAudio.src = src;
			tempAudio.addEventListener('loadeddata', function() 
      {
				tempduration = this.duration;
				document.getElementById(divname).querySelector('#songDuration').innerHTML = ' ' + sToMS(tempduration);
      });
		}

		function sToMS(s) 
    {
			var minutes = parseInt(s / 60);
			var seconds = Math.round(s % 60);
			if (seconds <= 9) {
				seconds = '0' + seconds;
			}
			return minutes + ':' + seconds;
		}

		function handleDragOver(evt) 
    {
			evt.stopPropagation();
			evt.preventDefault();
			evt.dataTransfer.dropEffect = 'copy';
		}

		function alertIt(event) {
      alert(getNameSpanOf(event.target.parentElement).innerHTML);
			alert(getDurationSpan(event.target.parentElement).innerHTML);
			alert(getSrc(event.target.parentElement));
		}

		function setNowPlaying(divParent) 
    {
			var songName = divParent.querySelector('#songName').innerHTML;
			divParent.querySelector('#songName').innerHTML = 'Now Playing: ' + songName;
			$(divParent).addClass('nowplaying');
      currentlyPlayingDiv = divParent;
		}

		function clearNowPlaying() 
    {
			var songName = currentlyPlayingDiv.querySelector('#songName').innerHTML;
			currentlyPlayingDiv.querySelector('#songName').innerHTML = songName.replace('Now Playing: ', '');
			$(currentlyPlayingDiv).removeClass('nowplaying');
    }

		function loadNextSong() 
    {
			var listDiv = document.getElementById(playListDivID);
			var audio = document.getElementById('player');
			var removedDiv = listDiv.removeChild(currentlyPlayingDiv);
			listDiv.appendChild(removedDiv);
			var listOfDivs = listChildrenDivs(playListDiv);
			clearNowPlaying();
			document.getElementById('mp3Source').src = getSrc(listOfDivs[0]);
			audio.load();
			audio.play();
			setNowPlaying(listOfDivs[0]);
		}

		function listChildrenDivs(divContainer) 
    {
			var listOfDivs = new Array();
			var children = divContainer.childNodes;
			for (var i = 0; i < children.length; i++) 
      {
				var childTag = children[i].tagName;
				if (childTag != undefined && childTag == ('DIV')) 
        {
					listOfDivs.push(children[i]);;
				}
			}
		  return listOfDivs;
		}

		function getNameSpanOf(divcontainer) {
			return divcontainer.querySelector('#songName');
		}

		function getDurationSpan(divcontainer) {
			return divcontainer.querySelector('#songDuration');
		}

		function getSrc(divcontainer) {
			return divcontainer.querySelector('#songSrc').src;
		}


		function insertTop(topNode, bottomNode) {
			$(topNode).insertBefore(bottomNode);
		}

		function insertBottom(topNode, bottomNode) {
			$(topNode).insertAfter(bottomNode);
		}
	</script>

</head>

<body>

	<div class='container' style='padding-top:10px;background-color: rgba(231, 82, 45, 1);height:100%;'>
		
				<div class='row'>
				 <div class='col-lg-1'></div>
					<div class='col-lg-10 boxshadowed' style='background-color:white;border-top-left-radius:10px;border-top-right-radius:10px;margin-bottom:10px;text-align:center'>
						<div class='msg pageTitle'>".$filename."</div>
					</div>
					<div class='col-lg-1'></div>		
				</div>		
		
				<div class='row' style='margin-bottom:10px'>
				 <div class='col-lg-1'></div>
					<div class='col-lg-10 boxshadowed' style='background-color:white;padding-bottom:10px'>
						<div class='row nospacing' style='padding-left:40px'>
							<audio id='player'>
										<source id='mp3Source' type='audio/mp3' src='http://www.flashkit.com/imagesvr_ce/flashkit/soundfx/Interfaces/Blips/Metallic-Richard_-7878/Metallic-Richard_-7878_hifi.mp3'/>
										Your browser does not support the audio element.
								</audio><br>
							<div class='container nospacing'>
								<div class='row nospacing'>
									<div class='col-lg-4 nospacing verticalCenterDiv' style='width:8%;padding-top:2px;'>
										<table class='nospacing' style='width:100%;height:100%'>
											<tr>
												<td id='playPauseContainer' style='height:100%'>
													<img id='playPuaseImage' src='https://maxcdn.icons8.com/iOS7/PNG/25/Media_Controls/play_filled-25.png' style='width:25px;height:25px;margin-right:5px;margin-left:5px' />
												</td>
												<td style='height:100%;font-family: Oswald, sans-serif;'>
													<span id='currentTimeSpan'>0:00</span>/<span id='durationSpan'>0:00</span>
												</td>
											</tr>
										</table>
									</div>
									<div class='col-lg-4 nospacing verticalCenterDiv' style='width:70%;padding-top:2px;margin-left:3%;'>
										<input id='seekBar' type='range' id='seek' value='0' max='' class='nospacing' />
									</div>
									<div class='col-lg-4 nospacing verticalCenterDiv' style='width:14%;'>
										<table class='nospacing' style='width:100%'>
											<tr>
												<td id='muteUnmuteContainer' style='width:25px;padding-left:10px;padding-right:10px'>
													<img id='muteUnmuteImage' src='https://maxcdn.icons8.com/iOS7/PNG/25/Mobile/speaker_filled-25.png' style='width:25px;height:25px;' />
												</td>
												<td>
													<input id='volumeBar' type='range' id='seek' value='30' max='100' />
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<table style='margin-left:45px'>
							<tr>
								<td class='butonTableCell'><input class='butt' type='button' value='Next on playlist' onclick='loadNextSong()' /></td>
								<td class='butonTableCell'><input class='butt' id='publish' type='button' value='publish' /></td>
								<td class='butonTableCell'><input class='' id='addSongsButton' type='file' value='Add Music' multiple='multiple' onchange='handleFileSelect(this.files)' /></td>
								<td class='butonTableCell'><label for='addSongsButton' class='butt' style='margin-bottom:0'>Add Music</label></td>
							</tr>
						</table>
						
						
					</div>
					<div class='col-lg-1'></div>		
			</div>
				
		<div class='row'>
			<div class='col-lg-1'></div>
			<div class='col-lg-10 midcontainer boxshadowed'>
				<div class='container nospacing'>
					<div class='row nospacing'>
						<div id='drop_zone' class='dropzoneflat'>
							<div id='dropzonemsg' style='opacity:.7;padding-top:30px;height:100%'>Drop MP3 files here to add to playlist</div>
						</div><br>
						
					</div>
				</div>
			</div>
			<div class='col-lg-1'></div>
		</div>
		
	</div>
	
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