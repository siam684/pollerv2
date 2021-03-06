<?php ini_set('display_errors',1);  error_reporting(E_ALL);
include 'db_functions.php';
//print_r($_POST);
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
	
	
	RewriteCond %{HTTPS} !=on
 	RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [R,L]
		
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
		<table style='width:100%;vertical-align:top;'>
			<tr>
				<td  style='text-align:left;width:8%;'>
					<div id='voteCountDiv' style='border-color:white;border-style:solid;border-width:3px;display:inline-block;padding:10px;border-radius:5px;font-size:1.3em;'>
						<span id='voteCountSpan'>0</span>
					</div>
				</td>
				<td  style='text-align:left;  width:72%;'>
					<span id='songName'></span>
				</td>
				<td  style='text-align:right; width:20%;vertical-align:top'>
					<span id='songDuration'></span>
				</td>
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
			min-height: 100px;
			border-color: rgb(231, 82, 45);
			border-style: dotted;
			background-color: #5BC0DE;
			color: white;
			font-size: 20px;
			box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.0), 0 0px 0px 0 rgba(0, 0, 0, 0.0);
			margin-bottom:10px;
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
	<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src='../coder.js?1500'></script>
	<script src='../db_functions.js?1500'></script>
	<script type='text/javascript'>
		var persistentVotes;
		var countOfSongDivs = 0;
		var tempduration;
		var tempGlobali;
		var currentlyPlayingDiv;
		var arrayOfsongs = new Array();
		var playListDivID = 'drop_zone';
		var pageNameVar;
		var playListDiv;		
		var pwFeild; 
		var loginButton; 
		var loginContainer; 
		var dropZoneContainer; 
		var playerContainer;
		var pwIncorrectDiv;
		var adminPw;
		var lastColumnAdded;
		var tableName = '".$tableName."';
		var pollResults;
		var intervalCallToGetPollList;
		var listOfSongNames = new Array();
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
		var websiteAddress = 'www.ASiamChowdhury.com/poller';
		$('document').ready(function() {
			 
			pageNameVar = tableName;
			while(pageNameVar.includes('_'))
			{
				pageNameVar = pageNameVar.replace('_',' ');
				console.log(pageNameVar);
			}
			pageNameVar = encodeURI(pageNameVar);
			persistentVotes = false;	
			
			var link  = websiteAddress+'/'+pageNameVar;			
			document.getElementById('shareText').innerHTML = 'Share the link <a id=\'pageLink\' href=\'http://www.asiamchowdhury.com/poller/'+pageNameVar+'\' target=\'_blank\' style=\'target-new: tab;\'>'+link+'</a> with people you want voting for the next song to play.';
			getPw(tableName, 'admin',setPw);
			var dropZone = document.getElementById('drop_zone');
			var dropzonemsg = document.getElementById('dropzonemsg');
			document.getElementById('publish').addEventListener('click', function() {
				updateVoterList();
			}, false);
			dropZone.addEventListener('dragover', handleDragOver, false);
			dropZone.addEventListener('drop', handleFileSelect, false);
			dropzonemsg.addEventListener('dragover', handleDragOver, false);
			dropzonemsg.addEventListener('drop', handleFileSelect, false);
			playListDiv = document.getElementById(playListDivID);
			player = document.getElementById('player');
			playPuaseImage = document.getElementById('playPuaseImage');
			playPauseContainer = document.getElementById('playPauseContainer');
			currentTimeSpan = document.getElementById('currentTimeSpan');
			durationSpan = document.getElementById('durationSpan');
			seekBar = document.getElementById('seekBar');
			muteUnmuteContainer = document.getElementById('muteUnmuteContainer');
			muteUnmuteImage = document.getElementById('muteUnmuteImage');
			pwFeild = document.getElementById('pwFeild');
			loginButton = document.getElementById('loginButton');
			loginContainer = document.getElementById('loginContainer');
			dropZoneContainer = document.getElementById('dropZoneContainer');
			playerContainer = document.getElementById('playerContainer');
			pwIncorrectDiv = document.getElementById('pwIncorrect');
			player.volume = .3;
			playerContainer.style.display = 'none';
			dropZoneContainer.style.display = 'none';
			$('#player').on('ended', function() {
				clearRecords(tableName,function(requestResult){console.log(requestResult)});
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
			$('#helpSpan').on('click',function(){
				$(dropzonemsg).toggle();
			});
      getPollResults();
		});
		function setPw(pw)
		{
			adminPw = pw;
		}
		function showList()
			{				
				if (pwFeild.value == adminPw)
				{
					//show the two divs
					playerContainer.style.display = 'block';
					dropZoneContainer.style.display = 'block';
					loginContainer.style.display = 'none';
				}
				else
				{	
					//show wrong pw error
					pwIncorrectDiv.style.display = 'block';
				}
			}
		function enterKeyPressed()
		{
			if (event.keyCode == 13) loginButton.click();
		}
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
			var getTimeCall = $.get('../get_time.php');
			getTimeCall.done(function(data){
				console.log(data);
				var currentTime = data*1000;
				var startTimeValue = currentTime / 1000.0;
				if (currentlyPlayingDiv) 
				{
					var currentSong = currentlyPlayingDiv.querySelector('#songName').innerHTML;
					$.post('../SongDuration.php', {
							functionName: 'setValue',
							pageName: pageNameVar,
							songName: currentSong.replace('Now Playing: ', ''),
							startTime: startTimeValue,
							songDuration: player.duration - player.currentTime
						},
						function(data, status) {
							//alert(status+ ' '+data);
						});
				}
			});
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
			if(persistentVotes)
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
					if (hr.readyState == 4 && hr.status == 200)
					{
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
			else
			{
			}
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
		function updateVoteCounts()
		{
			//get the tracking object from ajax call (votecounts.json)
			//get list of song divs 
			//while the count on the div is less then the count on the tracker increase the count on the div
			var arrayOfSongVoteCountObject;
			var listOfsongDivs;			
			var getJsonRequest = $.getJSON('./voteCounts.json', function( data ) {
				arrayOfSongVoteCountObject = data;
			});
			getJsonRequest.done(function(){				
				arrayOfSongVoteCountObject.forEach(function(item,index){
					var id = encodeIt(item.songId);
					var highestVotedSongIDFromVoteCounts = id;
					var matchingDiv = document.getElementById(id);
					//console.log(item.voteCount+' '+matchingDiv.querySelector('#voteCountSpan').innerHTML);
					if(item.voteCount>parseInt(matchingDiv.querySelector('#voteCountSpan').innerHTML))
					{
								increaseVoteCountonDiv(id,item.voteCount);
					}
				});
				arrayOfSongVoteCountObject.sort(function(a,b){
					return b.voteCount-a.voteCount;
				});
				console.log(arrayOfSongVoteCountObject);
				indexCount = 0;
				arrayOfSongVoteCountObject.forEach(function(voteCountObject,index){
					var tempDiv = document.getElementById(encodeIt(voteCountObject.songId));
					console.log(tempDiv);
					if(tempDiv!=currentlyPlayingDiv)
					{
						//playListDiv.appendChild(tempDiv);
						if(voteCountObject.voteCount>0)
							{
								putSongInSlot(tempDiv.id,indexCount)
								indexCount++;
							}
					}				
				});
			});
		}
	  function increaseVoteCountonDiv(divId,count)
    {
      var div = document.getElementById(divId);
      var voteCountDiv = div.querySelector('#voteCountDiv');
      var voteCountSpan = div.querySelector('#voteCountSpan');
			console.log(divId)
			console.log(div);
			console.log(voteCountDiv);
			console.log(voteCountSpan);
			$(voteCountDiv).fadeOut('fast');
			$(voteCountDiv).fadeIn(function(){
				voteCountSpan.innerHTML = count;        
			});     
    }
		function clearAllVoteCounts()
		{
			var songDivs = listChildrenDivs(playListDiv);
			arrayOfSongVoteCountObject = new Array();
			songDivs.forEach(function(songDiv,index){
				songDiv.querySelector('#voteCountSpan').innerHTML = 0;
				var tempObject = new Object();
				tempObject.songId = songDiv.id;
				tempObject.voteCount = 0;
				arrayOfSongVoteCountObject.push(tempObject);
				writeToVoteCountPage(arrayOfSongVoteCountObject,false);
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
				if(list[i].id=='dropzonemsg')
				{
					continue;
				}
				data1.push(list[i].id);
			}
			
			$('#dropZoneContainer').hide();
			$('#playerContainer').hide();
			$('#waitDiv').show();
			
 			var publishRequest = $.ajax({
				type: 'POST',
 				url: '../updateVoteList.php',
 				data: 'tableName=".$tableName."&pageName=' + '".$filename."&' + 'songName=' + JSON.stringify(data1)
 			});
			
			publishRequest.done(function(){
				
				$('#dropZoneContainer').show();
				$('#playerContainer').show();
				$('#waitDiv').hide();
				document.getElementById('pageLink').click(); 
			});
			
			var arrayOfSongVoteCountObject = new Array();
			data1.forEach(function(item, index){
				var tempObject = new Object();
				tempObject.songId = item;
				tempObject.voteCount = 0;
				arrayOfSongVoteCountObject.push(tempObject);
			});
			writeToVoteCountPage(arrayOfSongVoteCountObject,true);
		}
		function writeToVoteCountPage(arrayOfSongVoteCountObject, setInterval)
		{
			$.post('../vote_stats.php', {
						functionName: 'createFile',
						pageName: pageNameVar,
						songIds: JSON.stringify(arrayOfSongVoteCountObject)
					},
					function(data, status) {
						//alert(status+ ' '+data);
						console.log('calling updateVoteCounts after creating voteCounte.json')		
						updateVoteCounts();
						if(setInterval)
						{
							voteCountReadInterval = window.setInterval(function(){updateVoteCounts();},15000);
						}
					});
		}
// 		function addColumns(sqlString) 
// 		{
// 			var hr = new XMLHttpRequest();
// 			var url = '../db_functions.php';
// 			hr.open('POST', url, true);
// 			//console.log(sqlString);
// 			var postValues = 'functionName=db_addColumn&tableName=' + tableName + '&columns=' + sqlString;
// 			hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
// 			hr.onreadystatechange = function() 
// 			{
// 				if (hr.readyState == 4 && hr.status == 200) 
// 				{
// 				  //console.log(hr.responseText);
// 				}
// 			}
// 			hr.send(postValues);		
// 		}
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
			$('#playlistHeader').show();
			$(dz).removeClass('dropzoneflat');
			$(dz).addClass('dropzone');
			$(dz).show();
			var dropzonemsg = document.getElementById('dropzonemsg');
			if(dropzonemsg)
			{
				$(dropzonemsg).hide();
				$(dropzonemsg).addClass('dropzoneflat');
			}
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
			//var addCulumnSql = '';
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
// 					addCulumnSql = 'ADD `' + countOfSongDivs + '` INT FIRST';
					document.getElementById('mp3Source').src = blobsrc;
					var audio = document.getElementById('player');
					audio.load();
					setNowPlaying(document.getElementById(tempId));
				} 
        else 
        {
					var incomingCol = tempId;
// 					var colon = '';
// 					if (i!=0)
// 					{
// 						colon = ',';
// 					}
// 					addCulumnSql = addCulumnSql + colon + ' ADD `' + countOfSongDivs + '` INT after `' + (countOfSongDivs - 1) + '`';
					lastColumnAdded = incomingCol;
				}
				updateDuration(f.name, blobsrc, tempId);
				countOfSongDivs++;
			}
			pollResultsTrackerArray = listOfSongNames.slice(0);
// 			addColumns(addCulumnSql);
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
			divParent.querySelector('#voteCountDiv').style.display = 'none';
      currentlyPlayingDiv = divParent;
		}
		function clearNowPlaying() 
    {
			var songName = currentlyPlayingDiv.querySelector('#songName').innerHTML;
			currentlyPlayingDiv.querySelector('#songName').innerHTML = songName.replace('Now Playing: ', '');
			currentlyPlayingDiv.querySelector('#voteCountDiv').style.display = 'inline-block';
			currentlyPlayingDiv.querySelector('#voteCountSpan').innerHTML = 0;
			$(currentlyPlayingDiv).removeClass('nowplaying');
			var arrayOfSongVoteCountObject;
			var getJsonRequest = $.getJSON('./voteCounts.json', function( data ) {
				arrayOfSongVoteCountObject = data;
			});
			getJsonRequest.done(function(){
				if(persistentVotes)
				{
					arrayOfSongVoteCountObject.forEach(function(voteObject, index){					
						if(currentlyPlayingDiv.id==voteObject.songId)
						{
							console.log('found matching object');
							console.log(voteObject);
							arrayOfSongVoteCountObject[index].voteCount = 0;
							console.log(voteObject);
						}
					});
					writeToVoteCountPage(arrayOfSongVoteCountObject,false);
				}
				else
				{
					clearAllVoteCounts();
				}
			});
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
	<div class='container' style='padding-top:10px;background-color: rgba(231, 82, 45, 1);height:auto;min-height:100%'>
				<div class='row'>
				 <div class='col-lg-1'></div>
					<div class='col-lg-10 boxshadowed' style='background-color:white;border-top-left-radius:10px;border-top-right-radius:10px;margin-bottom:10px;text-align:center'>
						<div class='msg pageTitle'>".$filename."</div>
					</div>
					<div class='col-lg-1'></div>		
				</div>		
				<div class='row' style='margin-bottom:10px' id='playerContainer'>
				 <div class='col-lg-1'></div>
					<div class='col-lg-10 boxshadowed' style='background-color:white;padding-bottom:10px'>
						<div class='row nospacing' style='padding-left:40px'>
							<audio id='player'>
										<source id='mp3Source' type='audio/mp3' src='../Metallic-Richard_-7878_hifi.mp3'/>
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
		<div class='row'  id='loginContainer'>
			<div class='col-lg-1'></div>
			<div class='col-lg-10 midcontainer boxshadowed'>
				<div class='container nospacing'>
					<div class='row nospacing'>
						<div style='padding-bottom:20px'>	 <!--style='display: none;'-->				
							<span style='text-align:left; font-family: Source Sans Pro, sans-serif;color:#E7522D'>Playlist Admin/DJ Password:</span><br>
							<input type='password' id='pwFeild' class='customText' onkeydown='enterKeyPressed()'/>
							<input id='loginButton' type='button' value='Login' class='longButton' onclick='showList()' style='margin-top:10px' />
						</div>
						<div id = 'pwIncorrect' class='msg' style='margin-bottom:10px'>Incorrect Password</div>
					</div>
				</div>
			</div>
			<div class='col-lg-1'></div>
		</div>
		<div id = 'waitDiv' class='row' style='display:none'>
			<div class='col-lg-1'></div>
			<div class='col-lg-10 midcontainer boxshadowed'>
				<div style='text-align:center;margin-bottom:2%;	font-family: Bungee, cursive;color:#E7522D;font-size:2em;'>Please Wait.</div>	
			</div>
			<div class='col-lg-1'></div>
		</div>
		<div class='row'  id='dropZoneContainer'>
			<div class='col-lg-1'></div>
			<div class='col-lg-10 midcontainer boxshadowed'>
				<div style='text-align:right'>
					<span id='helpSpan' class='inputTextTitle'>Help?</span>
				</div>
				<div class='container nospacing'>
					<div class='row nospacing'>
							<div id='dropzonemsg' style='opacity:.7;padding-top:30px;height:100%' class='dropzoneflat'>
									<div  style='margin:0 auto;width:80%;text-align:left; line-height: 200%;' >
											<ol>
												<li>Drop MP3 files into here or the playlist section to add  them to playlist.</li>
												<li>Press publish when your done adding music to setup your voter page.</li>
												<li id='shareText'></li>
											</ol>
									</div>							
						</div>
							<div class='songHolder' style='font-size:1.5em;display:none' id='playlistHeader'>
								<table style='width:100%;  vertical-align:top;'>
									<tr>
										<td  style='text-align:left;  width:8%;'><span>Votes</span></td>
										<td  style='text-align:left;  width:72%;'><span>Name</span></td>
										<td  style='text-align:right; width:20%;vertical-align:top'><span>Duration</span></td>
									</tr>
								</table>
							</div>	
						<div id='drop_zone' class='dropzoneflat' style='display:none'>
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
<?php
header("Location: ".$filename."/player.php");
exit();
?>