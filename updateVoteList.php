<?php
require  'coder.php';
ini_set('display_errors',1);  error_reporting(E_ALL);
$pageName = $_POST['pageName'];
$tableName = $_POST['tableName'];
$votePage  = fopen($pageName."/index.php", 'wa+');
//$testFile = fopen("testFile.txt", 'wa+');
//mkdir('testFolder');
$arrayOfSongNames = json_decode($_POST["songName"], true);
fwrite($votePage, "<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' >
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<title>".$pageName."</title>
		<link rel='stylesheet' href='https://necolas.github.com/normalize.css/2.0.1/normalize.css'>
		<link rel='stylesheet' href='../votePage.css'>
		<style>
			.ui-draggable-helper
			{
				margin-left:-23.1%;
			}
			#songListContainer .ui-selected 
			{
				position: relative;
				left: 50%;
				margin: 10px 10% 13px -47.1%;
				padding:10px 10px 15px 10px ;
			}
			#songListContainer .ui-selecting 
			{	
				background-color: #ccccff;
				color: white;
				background-image: none;
				position: relative;
				left: 50%;
				margin: 10px 10% 13px -47.1%;
				padding:10px 10px 15px 10px ;
			}
			.highlight 
			{
				height:30px;
				width:90%;
				position: relative;
				left: 50%;
				margin: 10px 10% 13px -47.1%;
				padding:10px 10px 15px 10px ;
			    font-weight: bold;
			    font-size: 45px;
			    background-color: lightblue;
			}
			@media only screen 
			and (min-device-width : 320px) 
			and (max-device-width : 480px)
			{
				.left{
				width:5%;
				}
				.center{
					width:90%;
					padding:0;
				}
				.right{
					width:5%;
				}
				.card
				{
					background-color:white;
					height:auto;
					width:90%;
					position: relative;
					left: 50%;
					margin: 10px 5% 13px -48.1%;
					padding: auto ;
					box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
				}
				.ui-draggable-helper
				{
					margin-left:-43.1%;
				}
				.longButton
				{
					margin: 0 0px 0 6px;
					width:96%;
					border:0;
					font-family: Bungee, cursive;
					color:white;
					background-color:#5facd3;
					box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
					height: 50px;
				}
			}
		</style>
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js' type='text/javascript'></script>
		<script src='https://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
		<!--<script src='../jquery.ui.touch-punch.min.js'></script>-->
		<script src='../coder.js'></script>
		<script src='../db_functions.js?1500'></script>
		<script type='text/javascript'>
			var tableName = '".$tableName."';
			var userPw;
			var submitErrorDiv;
			var tyDiv;
			var waitDiv;
			var loginButton; 
			var songListDiv;
			var pwFeild;
			var loginDiv;
			var pwIncorrectDiv;
			var waitToSubmitDiv;
			var instructionDiv;	  
			var currentSongDetails;
			var canVoteAtTime;
			var updateVoteCountDownInterval;
			$('document').ready(function(){
				$('#songListContainer').sortable({placeholder: 'highlight'},
					{start: function(event, ui)
					{
	            		$(ui.helper).addClass('ui-draggable-helper');
					}},{helper:'clone'},{ axis: 'y' });
				getPw(tableName, 'user',setPw);
				init();
			});
			function init(submitErrorDiv,
							 tyDiv,
							 waitDiv,
							 loginButton, 
							 songListDiv,
							 pwFeild,
							 loginDiv,
							 pwIncorrectDiv,
							 waitToSubmitDiv)
			{
				window.submitErrorDiv = document.getElementById('submitError');
				window.tyDiv = document.getElementById('ty');
				window.waitDiv = document.getElementById('wait'); 
				window.loginButton = document.getElementById('loginButton'); 
				window.songListDiv = document.getElementById('songListContainer'); 
				window.pwFeild = document.getElementById('pwFeild'); 
				window.loginDiv = document.getElementById('loginContainer');
				window.pwIncorrectDiv = document.getElementById('pwIncorrect');
				window.waitToSubmitDiv = document.getElementById('waitToSubmit');
				window.instructionDiv = document.getElementById('instructionDiv');													  
			}
			function setPw(pw)
			{
				userPw = pw;
			}
			function showList()
			{
				hideAllExcept(waitDiv);
				if(localStorage.getItem(tableName)===null)
				{
					localStorage.setItem(tableName,0);
				}
				var hasVoted = localStorage.getItem(tableName);
				if(hasVoted==1)
				{
				}
				else
				{
				}
				if (pwFeild.value == userPw)
				{
					if(hasVoted==1)
					{
						getCurrentSongDetails();
						recordVotedAndStartCountDown();
					}
					else
					{
						hideAllExcept(songListDiv,instructionDiv);	
					}
				}
				else
				{	
					hideAllExcept(loginButton,loginDiv, pwIncorrectDiv);
				}
			}
				function enterKeyPressed()
				{
					if (event.keyCode == 13) loginButton.click();
				}
				var hideAllExcept = function() 
				{
					submitErrorDiv.style.display = 'none';
					tyDiv.style.display = 'none';
					waitDiv.style.display = 'none';
					loginButton.style.display = 'none'; 
					songListDiv.style.display = 'none';
					loginDiv.style.display = 'none';
					pwIncorrectDiv.style.display = 'none';
					waitToSubmitDiv.style.display = 'none';
					voteCountDown.style.display = 'none';
					instructionDiv.style.display = 'none';					   
					for(var i = 0; i< arguments.length;i++)
					{
						arguments[i].style.display = 'block';
					}
				}
			function listSubmit()
			{
				var timePassedSinceLastVote;
				if(timeOfLastVote = localStorage.getItem(tableName))
				{
					var now = (new Date()).getTime();
					var timePassedSinceLastVote = now - timeOfLastVote;
					timePassedSinceLastVote = 300001;
					if(timePassedSinceLastVote<=300000)
					{
						var waitTime = 300000-timePassedSinceLastVote;
						var ms = waitTime,
						   min = (ms/1000/60) << 0,
						   sec = (ms/1000) % 60;
						var waitString;
						if(min>0)
						{
							waitString  = 'You\'ll have to wait '+ min + ' minute '+Math.round(sec)+' seconds to vote again.'; 
						}
						else
						{
							waitString = 'You\'ll have to wait '+Math.round(sec)+' seconds to vote again.' 
						}
						waitToSubmitDiv.innerHTML = waitString;
						hideAllExcept(waitToSubmitDiv,songListDiv);
						return;
					}
				}
				var songContainer = document.getElementById('songListContainer');
				var list = listChildrenDivs(songContainer);
// 				var values = '';
// 				var columns = '' ;
				hideAllExcept(waitDiv);
// 				for (j = 0; j < list.length; j++)
// 				{
// 					columns = columns + '`' + list[j].getAttribute('data-colNum') + '`';
// 					values = values + (j+1);
// 					if(j!=(list.length-1))
// 					{
// 						values = values + ', ';
// 						columns = columns + ', ';
// 					}
// 				}
				var songListContainer = document.getElementById('songListContainer');
				updateVoteStats(list[0].id);
// 				insertToDB(columns, values, tableName,selectionsSubmitted);				
			}
			
			function songDivClicked(div)
			{
				console.log(div);
				$(songListDiv).prepend(div);
				listSubmit();
			}
			
			function updateVoteStats(topSong)
			{
				var pageNameVar = tableName;
				var arrayOfSongVoteCountObject;
				while(pageNameVar.includes('_'))
				{
					pageNameVar = pageNameVar.replace('_',' ');
				}
				var getJsonRequest = $.getJSON('./voteCounts.json', function( data ) {
					arrayOfSongVoteCountObject = data;
					arrayOfSongVoteCountObject.forEach(function(item,index){
						if(item.songId==topSong)
						{
								item.voteCount += 1;
						}
					})				
				});
				getJsonRequest.done(function(){
					$.post('../vote_stats.php', {
						functionName: 'createFile',
						pageName: pageNameVar,
						songIds: JSON.stringify(arrayOfSongVoteCountObject)
					},
					function(data, status) {
						selectionsSubmitted(1);
					});
				});
				getJsonRequest.fail(function(err){
					console.log('failed attempt');
					console.log(err);
				});
			}
			function getCurrentSongDetails()
			{
				var reqeust = 'currentSong.json';
				$.ajax({
								dataType:'json',
								url: reqeust,
								async:false,
    						cache: false,
								success: function(data, status){
									currentSongDetails = data.currentSongPlaying;
									if(currentSongDetails.songDuration=='NaN'){
										currentSongDetails.songDuration = 300;
									}
								},
								error:function(jqXHR, textStatus, errorThrown){
								}
							});	
			}
			function selectionsSubmitted(response)
			{
				if(response==1)
				{
					getCurrentSongDetails();
					localStorage.setItem(tableName, 1);
					recordVotedAndStartCountDown();
				}
				else
				{
					hideAllExcept(submitErrorDiv);
				}
			}
			function recordVotedAndStartCountDown()
			{
					hideAllExcept(tyDiv,voteCountDown);	
					canVoteAtTime = parseFloat(currentSongDetails.songDuration) +parseFloat(currentSongDetails.startTime);					
					document.getElementById('voteCountDownSongName').innerHTML = currentSongDetails.songName;
					var getTimeCall = $.get('../get_time.php');
					getTimeCall.done(function(data){
						console.log(data);
						var currentTime = data*1000;
						updateVoteCountDownInterval = setInterval(function(){
							currentTime = currentTime+1000;
							updateVoteCountDown(currentTime);
						}, 1000);
					});
			}
			function updateVoteCountDown(incomingCurrentTime)
			{
				var minutesString;
				var currentTime = incomingCurrentTime/1000;
				var timeLeft = (canVoteAtTime-currentTime);
				if(timeLeft<1)
				{
						clearTimeout(updateVoteCountDownInterval);
					  localStorage.setItem(tableName,0);
					  hideAllExcept(songListDiv);
				}
				else
				{
						var minutes = Math.floor(timeLeft / 60);
						var seconds = timeLeft - minutes * 60;
						if(minutes>1)
						{
							var minutesString = minutes + ' minutes and ';
						}
						else if(minutes==1)
						{
							var minutesString = minutes + ' minute and ';
						}
						else if(minutes<=0)
						{
							minutesString = '';
						}
						document.getElementById('voteCountDownMin').innerHTML = minutesString;
						document.getElementById('voteCountDownSec').innerHTML = Math.round(seconds);
				}
			}
		</script>
	</head>
	<body>
		<div class='left'><br></div>
		<div class='center'>		
			<div class='card msg pageTitle'>".str_replace("_"," ",$tableName)."</div>
			<div id='loginContainer' class='card' style=''>	 <!--style='display: none;'-->				
				<span style='text-align:left; font-family: Source Sans Pro, sans-serif;color:#E7522D; font-size:.7em;'>Playlist Password:</span><br>
				<input type='password' id='pwFeild' class='customText' onkeydown='enterKeyPressed()'/>
			</div>
			<input id='loginButton' type='button' value='Login' class='longButton' onclick='showList()' />
   
			<div id=instructionDiv class='card msg'>
				Select the song you want to hear next and if it has enough votes it will play.
			</div>
   
			<div id='songListContainer' style='display:none; padding: 0 0 12px 0;'>");
$colNum = 0;
foreach ($arrayOfSongNames as $value) 
{
	fwrite($votePage,"
				<div class='card msg' id='".$value."' style='display:block;cursor: pointer;' data-colNum=".$colNum." onClick='songDivClicked(this)'>
					<span id='".$value."_span'".">".decodeIt($value)."</span>

				</div>
			");
    $colNum++;
}
fwrite($votePage," 
</div> <!--song list container close -->	
		<div id = 'waitToSubmit' class='card msg';></div>
		<div id = 'wait' class='card msg'>Please Wait.</div>		
		<div id = 'ty' class='card msg'>Thank You!</div>
		<div id = 'pwIncorrect' class='card msg'>Incorrect Password</div>
		<div id = 'submitError' class='card msg'>There was a Error submitting your selections. Try again later.</div>	
		<div id = 'voteCountDown' class='card msg'>You'll have to wait for <span id='voteCountDownSongName'></span> to end in <span id='voteCountDownMin'></span><span id='voteCountDownSec'></span> seconds to vote again.</div>
		<br><br>
		<span class='inputTextTitle' style='color:white;float:left;margin:0 0 0 20px'>Asiamchowdhury.com</span>	
		</div>
		<div class='right'><br></div>
		</body>
</html>
  
		");
//fwrite($votePage);
fclose($votePage);
//fclose($testFile);
?>