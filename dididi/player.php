<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>dididi</title>


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
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">

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
		var tableName = 'dididi';
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
			//alert(voterDiv.id);
			//var player = document.getElementById('player');

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
				//console.log('song ended');
				//getPollResults();
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

		function getFormattedTime(sec) {
			minutes = Math.floor((sec % 3600) / 60);
			seconds = Math.floor(sec % 60);
			if (seconds < 10) {
				zero = '0';
			} else {
				zero = '';
			}

			return minutes + ":" + zero + seconds;
		}

		function sendCurrentSong() {
			var startTimeValue = Date.now() / 1000.0;

			if (currentlyPlayingDiv) {
				var currentSong = currentlyPlayingDiv.querySelector('#songName').innerHTML;
				$.post("../SongDuration.php", {
						functionName: "setValue",
						pageName: tableName,
						songName: currentSong.replace('Now Playing: ', ''),
						startTime: startTimeValue,
						songDuration: player.duration - player.currentTime
					},
					function(data, status) {
						//alert(status+ " "+data);
					});
			}
		}

		function Song(name, src, dur, divId) {
			this.name = name;
			this.src = src;
			this.dur = dur;
			this.divId = divId;
		}

		function tempDivIDObject(id, pos) {
			this.id = id;
			this.pos = pos;
		}


		function getPollResults() {
			var hr = new XMLHttpRequest();
			var jsonRankingsObject;
			var orderResultsArray = new Array();
			pollResults = new Array();
			var url = '../db_functions.php';
			hr.open('POST', url, true);
			var postValues = 'functionName=getSongRanks&tableName=' + tableName;
			hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			hr.onreadystatechange = function() {
				if (hr.readyState == 4 && hr.status == 200) {
					//alert(hr.responseText);
					jsonRankingsObject = JSON.parse(hr.responseText);
					console.log('recieved response from DB');
					console.log(jsonRankingsObject);

					//console.log('recieved response from DB');

					/*
                listofsongdivs reflects the order of column names 
                function uses key to get the divId of the song that the key references
                that used along with the position is put into a array
                
                the array is sorted and then used to create new ordered array of div id's 
                that corresponds to vote income vote results
                
                
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
                
                */

					$.each(jsonRankingsObject, function(index, position) {
						//console.log('in jsonRankingsObject loop');
						console.log(position + ' ' + index);
						pollResults[position - 1] = listOfSongNames[index];
						//console.log(pollResults[position]);

					});

					listOfSongNames.forEach(function(div, index) {
						console.log('listOfSongNames ' + index + ': ' + div);
					});


					pollResults.forEach(function(div, index) {
						console.log('pollResults ' + index + ': ' + div);
					});

					pollResultsTrackerArray.forEach(function(div, index) {
						console.log('pollResultsTrackerArray ' + index + ': ' + div);
					});




					//check if its the first time a list has been sent from db
					//console.log(pollResultsTrackerArray.length);

					//check if incoming list has changed
					//console.log('length of tracker array: '+pollResultsTrackerArray.length); 
					//pollResultsTrackerArray.forEach(function(value, index){'from tracker array: '+console.log(index+": "+value)});
					//pollResults.forEach(function(value, index){'from results array: '+console.log(index+": "+value)});

					pollResults.forEach(function(item, index) {

						console.log('pollResults array             at index ' + index + ': ' + item);
						console.log('pollResultsTrackerArray array at index ' + index + ': ' + pollResultsTrackerArray[index]);
						if (item != pollResultsTrackerArray[index]) {
							console.log(hasBeenVotedUp(item, index));
							if (hasBeenVotedUp(item, index)) {
								console.log(item + "has gone to position " + index);
								putSongInSlot(item, index);
							}

							listDidNotChange = false;
							//changedIndex = index;
							//console.log('in pollresults foreach and item!=pollResultsTrackerArray');
							//putSongInSlot(item,index); //put new song into slot that it was voted into
						}
					});

					pollResultsTrackerArray = pollResults.slice(0);

					for (var index = 0; index < pollResults.length; index++) {
						//console.log('pollResults array at index '+index+': '+pollResults[index]);
					}

					if (!listDidNotChange) {
						//updatePlayList();
					}
				}
			}

			hr.send(postValues);
		}
		/*
		compare incoming array index value against its previous position using pollresults array as incoming and 
		pollresultstracker array as previous position
    
		incoming song id is the compared against song id's on tracker array
		*/
		function hasBeenVotedUp(incomingSongId, incomingSongIndex) {
			var isVotedUp = false;

			pollResultsTrackerArray.forEach(function(trackerSongId, trackerIndex) {
				if (trackerSongId == incomingSongId) {
					if (incomingSongIndex < trackerIndex) {

						isVotedUp = true;
					}
				}
			});
			return isVotedUp;
		}

		function putSongInSlot(incomingDivId, incomingIndex) {
			incomingIndex += 1;



			/*
			for each div in list of playlist songs add it back but if the current index is the index of the incoming song
			add the incoming song first
			*/
			var playList = listChildrenDivs(playListDiv);

			playList.forEach(function(songDiv, playlistIndex) {

				if (songDiv.id == incomingDivId) {
					playList.splice(playlistIndex, 1);
				}
			});

			playList.forEach(function(songDiv, index) {
				console.log("after deleteing " + index + ": " + songDiv.id);
			});


			playList.splice(incomingIndex, 0, document.getElementById(incomingDivId));

			playList.forEach(function(songDiv, index) {
				console.log("after inserting " + index + ": " + songDiv.id);
			});

			playList.forEach(function(songDiv, playlistIndex) {
				//console.log(index2+': '+value.id);

				//if(playlistIndex==incomingIndex) 
				//{
				//document.getElementById(incomingDivId).querySelector('#songName').innerHTML = document.getElementById(incomingDivId).querySelector('#songName').innerHTML + ' moved';
				//alert("puting " + songDiv.id + " in index " + playlistIndex); 
				//playListDiv.appendChild(document.getElementById(incomingDivId));
				playListDiv.appendChild(songDiv);


				// }


				//if(songDiv.id!=incomingDivId)
				//{
				//playListDiv.appendChild(songDiv);
				//}

			});

			//console.log('incoming Id: '+divId+'incoming index: '+index1);
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


		function startReadingVotes() {
			alert('starting interval calls');
			intervalCallToGetPollList = setInterval(getPollResults, 10000);
		}


		function updateVoterList() {
			var list = listChildrenDivs(playListDiv);
			var data1 = new Array();


			for (var i = 0; i < list.length; i++) {
				data1.push(list[i].id);
			}

			////console.log(data1);

			$.ajax({
				type: 'POST',
				url: '../updateVoteList.php',
				data: 'tableName=dididi&pageName=' + 'dididi&' + 'songName=' + JSON.stringify(data1)
			});

			//startReadingVotes();
		}

		function addColumns(sqlString) {
			//console.log(sqlString);
			var hr = new XMLHttpRequest();
			var url = '../db_functions.php';
			hr.open('POST', url, true);
			var postValues = 'functionName=db_addColumn&tableName=' + tableName + '&columns=' + sqlString;
			hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			hr.onreadystatechange = function() {
				if (hr.readyState == 4 && hr.status == 200) {
					//console.log(hr.responseText);
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

		function handleFileSelect(evt) {
			dz = document.getElementById('drop_zone');
			$(dz).removeClass('dropzoneflat');
			$(dz).addClass('dropzone');
			//$(dz).remove('#dropzonemsg');
			dz.removeChild(dz.querySelector('#dropzonemsg'));

			var files;

			if (evt instanceof FileList) {
				files = evt;
			} else if (evt instanceof DragEvent) {
				evt.stopPropagation();
				evt.preventDefault();
				files = evt.dataTransfer.files;
			}

			var addCulumnSql = '';
			var firstLine = true;
			for (var i = 0, f; f = files[i]; i++) {
				console.log(f.type);
				var acceptableType = false;
				if (f.type == 'audio/mp3' | f.type == 'audio/mpeg') {
					acceptableType = true;
				}

				if (!acceptableType) {
					alert(f.name + ' is not a mp3 audio file.');
					continue;
				}

				if (f.name.length > 128) {
					alert(f.name + ' is greater than 128 characters.');
					continue;
				}

				var blobsrc = URL.createObjectURL(f);
				var tempId = encodeIt(f.name.replace('.mp3', ''));
				//alert(blobsrc);
				//var t = document.querySelector('#songHolderTemplate');
				//var tempSongDiv = document.importNode(t.content, true);
				var tempSongDiv = getObjectOfTemplate('songHolderTemplate');


				tempSongDiv = tempSongDiv.querySelector('div');
				tempSongDiv.querySelector('#songName').innerHTML = f.name.replace('.mp3', '');
				tempSongDiv.id = tempId;
				tempSongDiv.querySelector('#songSrc').src = blobsrc;
				console.log(tempSongDiv);

				// 			if(importNodeSupported){
				// 				tempSongDiv.getElementById('songName').innerHTML = f.name.replace('.mp3',''); 
				// 				tempSongDiv.getElementById('playlistSongHolder').id = tempId;
				// 				tempSongDiv.getElementById('songSrc').src = blobsrc;
				// 				console.log(tempSongDiv);
				// 			}
				// 			else{
				// 				tempSongDiv = tempSongDiv.querySelector('div');
				// 				tempSongDiv.querySelector('#songName').innerHTML = f.name.replace('.mp3',''); 
				// 				tempSongDiv.id = tempId;
				// 				tempSongDiv.querySelector('#songSrc').src = blobsrc;
				// 				console.log(tempSongDiv);
				// 			}


				$('#' + playListDivID).append(tempSongDiv);

				listOfSongNames.push(tempId);

				if (countOfSongDivs == 0) {
					lastColumnAdded = tempId;
					addCulumnSql = 'ADD `' + countOfSongDivs + '` INT FIRST';

					document.getElementById('mp3Source').src = blobsrc;
					var audio = document.getElementById('player');
					audio.load();


					//audio.play();

					//alert(currentlyPlayingDiv);
					setNowPlaying(document.getElementById(tempId));
				} else {
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
				//console.log(addCulumnSql);
				countOfSongDivs++;
			}

			pollResultsTrackerArray = listOfSongNames.slice(0);
			addColumns(addCulumnSql);
		}



		function updateDuration(name, src, divname) {
			var tempAudio = new Audio();
			tempAudio.src = src;
			tempAudio.addEventListener('loadeddata', function() {
				////console.log('Audio data loaded');
				////console.log('Audio duration: ' + this.duration);
				tempduration = this.duration;
				////console.log(tempduration + ' ' + divname);


				document.getElementById(divname).querySelector('#songDuration').innerHTML = ' ' + sToMS(tempduration);
				//var tempSong = new Song(name,src,tempduration,divname);
				//arrayOfsongs.push(tempSong);
			});
		}

		function sToMS(s) {
			var minutes = parseInt(s / 60);
			var seconds = Math.round(s % 60);
			if (seconds <= 9) {
				seconds = '0' + seconds;
			}
			return minutes + ':' + seconds;
		}

		function handleDragOver(evt) {
			evt.stopPropagation();
			evt.preventDefault();
			evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
		}

		function alertIt(event) {
			//alert(event.target.parentElement.id);
			//alert(event.target.parentElement.querySelector('#songSrc').src);
			alert(getNameSpanOf(event.target.parentElement).innerHTML);
			alert(getDurationSpan(event.target.parentElement).innerHTML);
			alert(getSrc(event.target.parentElement));
		}

		// Setup the dnd listeners.



		function setNowPlaying(divParent) {
			//alert(divParent.id);

			var songName = divParent.querySelector('#songName').innerHTML;
			//console.log('in setnowPlaying attempting to insert now playing to '+divParent.id+' with value ' + songName);
			divParent.querySelector('#songName').innerHTML = 'Now Playing: ' + songName;

			$(divParent).addClass('nowplaying');

			//console.log('new value for '+divParent.id+': ' + divParent.querySelector('#songName').innerHTML);
			currentlyPlayingDiv = divParent;
			//console.log('set currently playing div: ' + currentlyPlayingDiv.id);
			//var listOfDivs = listChildrenDivs();

			//insertTop(currentlyPlayingDiv,listOfDivs[0]);
		}

		function clearNowPlaying() {
			//alert(divParent.id);
			//console.log('in clearNowPlaying()');
			//console.log('attempting to clear now play for div: '+currentlyPlayingDiv.id);
			var songName = currentlyPlayingDiv.querySelector('#songName').innerHTML;
			//console.log('value for '+currentlyPlayingDiv.id+': '+ songName);
			currentlyPlayingDiv.querySelector('#songName').innerHTML = songName.replace('Now Playing: ', '');
			$(currentlyPlayingDiv).removeClass('nowplaying');
			//console.log('new value for '+currentlyPlayingDiv.id+': '+ currentlyPlayingDiv.querySelector('#songName').innerHTML);
		}

		function loadNextSong() {
			var listDiv = document.getElementById(playListDivID);
			var audio = document.getElementById('player');

			//console.log('in loadNextSong');
			var removedDiv = listDiv.removeChild(currentlyPlayingDiv);
			//console.log('removed Div from list:'+removedDiv.id);
			listDiv.appendChild(removedDiv);



			//console.log('grabbed: '+ listDiv.id);
			//console.log('grabbed: '+ audio.id);
			//console.log('calling: listChildrenDivs()');
			var listOfDivs = listChildrenDivs(playListDiv);

			clearNowPlaying();

			//console.log('geting src from div: '+listOfDivs[0].id);
			//console.log('source is: '+ getSrc(listOfDivs[0]));
			document.getElementById('mp3Source').src = getSrc(listOfDivs[0]);

			audio.load();
			audio.play();
			//insertTop(listOfDivs[0],currentlyPlayingDiv);


			setNowPlaying(listOfDivs[0]);

		}

		function showArray() {
			for (var i = 0; i < arrayOfsongs.length; i++) {
				//console.log(arrayOfsongs[i].name);
				//console.log(arrayOfsongs[i].src);
				//console.log(arrayOfsongs[i].dur);
				//console.log(arrayOfsongs[i].divId);
			}
		}

		function listChildrenDivs(divContainer) {
			//alert('in list div');
			//console.log('accepting div: '+divContainer + ' ' + divContainer.id);
			//console.log('in listChildrenDivs() length of list: '+ divContainer.childNodes.length);
			var listOfDivs = new Array();
			//console.log('created new list array');

			var children = divContainer.childNodes;
			//console.log('grabbed: children divs of list div');

			for (var i = 0; i < children.length; i++) {
				var childTag = children[i].tagName;
				//console.log('in listSOngsDiv for loop, child tag: ' + childTag);
				if (childTag != undefined && childTag == ('DIV')) {
					////console.log(children[i].id);
					listOfDivs.push(children[i]);
					//console.log('add to list array: ' + children[i].id);
				}
			}
			////console.log('length of listofdivs: '+listOfDivs.length);
			//insertBottom(listOfDivs[0],listOfDivs[4]);
			//insertTop(listOfDivs[0],listOfDivs[4]);
			//console.log('______________________________________________________________');
			for (var i = 0; i < listOfDivs.length; i++) {
				//console.log('contents of index: '+ i);
				//console.log(listOfDivs[i].id);
				//console.log(getNameSpanOf(listOfDivs[i]).innerHTML);
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
			//var parent= document.getElementById(playListDivID);
			//parent.insertBefore(topNode,bottomNode);
			$(topNode).insertBefore(bottomNode);
		}

		function insertBottom(topNode, bottomNode) {
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

	<div class='container' style='padding-top:10px;background-color: rgba(231, 82, 45, 1);height:100%;'>
		
				<div class='row'>
				 <div class='col-lg-1'></div>
					<div class='col-lg-10 boxshadowed' style='background-color:white;border-top-left-radius:10px;border-top-right-radius:10px;margin-bottom:10px;text-align:center'>
						<div class='msg pageTitle'>dididi</div>
					</div>
					<div class='col-lg-1'></div>		
				</div>		
		
				<div class='row' style="margin-bottom:10px">
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
										<input id='seekBar' type="range" id="seek" value="0" max="" class='nospacing' />
									</div>
									<div class='col-lg-4 nospacing verticalCenterDiv' style='width:14%;'>
										<table class='nospacing' style='width:100%'>
											<tr>
												<td id='muteUnmuteContainer' style='width:25px;padding-left:10px;padding-right:10px'>
													<img id='muteUnmuteImage' src='https://maxcdn.icons8.com/iOS7/PNG/25/Mobile/speaker_filled-25.png' style='width:25px;height:25px;' />
												</td>
												<td>
													<input id='volumeBar' type="range" id="seek" value="30" max="100" />
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
								<td class='butonTableCell'><label for="addSongsButton" class='butt' style='margin-bottom:0'>Add Music</label></td>
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
							<div id='dropzonemsg' style="opacity:.7;padding-top:30px;height:100%">Drop MP3 files here to add to playlist</div>
						</div><br>
						
					</div>
				</div>
			</div>
			<div class='col-lg-1'></div>
		</div>
		
	</div>
	
</body>

</html>