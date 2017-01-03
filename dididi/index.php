
		
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" >
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<title>dididi</title>
		
		<link rel='stylesheet' href='http://necolas.github.com/normalize.css/2.0.1/normalize.css'>
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
		<script src='http://code.jquery.com/ui/1.10.4/jquery-ui.js'></script>
		<script src='../jquery.ui.touch-punch.min.js'></script>
		<script src='../coder.js'></script>
		<script src='../db_functions.js'></script>
		
		<script type='text/javascript'>
		
			var tableName = 'dididi';
			var userPw;
			var subButton;
			var submitErrorDiv;
			var tyDiv;
			var waitDiv;
			var loginButton; 
			var songListDiv;
			var pwFeild;
			var loginDiv;
			var pwIncorrectDiv;
			var waitToSubmitDiv;
			var currentSongDetails;
			var canVoteAtTime;
			var updateVoteCountDownInterval;
			
			$('document').ready(function(){
				
				$('#songListContainer').sortable({placeholder: 'highlight'},
					{start: function(event, ui)
					{
	            		//$(ui.item).width('200%');
	            		//alert($(ui.item.id));
	            		$(ui.helper).addClass('ui-draggable-helper');
					}},{helper:'clone'},{ axis: 'y' });
	
				getPw(tableName, 'user',setPw);
	
				init();
			});
	
			function init(	 subButton,
							 submitErrorDiv,
							 tyDiv,
							 waitDiv,
							 loginButton, 
							 songListDiv,
							 pwFeild,
							 loginDiv,
							 pwIncorrectDiv,
							 waitToSubmitDiv)
			{
				
				window.subButton = document.getElementById('submitButton');
				window.submitErrorDiv = document.getElementById('submitError');
				window.tyDiv = document.getElementById('ty');
				window.waitDiv = document.getElementById('wait'); 
				window.loginButton = document.getElementById('loginButton'); 
				window.songListDiv = document.getElementById('songListContainer'); 
				window.pwFeild = document.getElementById('pwFeild'); 
				window.loginDiv = document.getElementById('loginContainer');
				window.pwIncorrectDiv = document.getElementById('pwIncorrect');
				window.waitToSubmitDiv = document.getElementById('waitToSubmit');
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
					console.log('can vote is true');
				}
				else
				{
					console.log('can vote is false');
				}
				
				if (pwFeild.value == userPw)
				{
					console.log(hasVoted);
					if(hasVoted==1)
					{
						console.log('passed localStorage.getItem(tableName)==true');
						getCurrentSongDetails();
						recordVotedAndStartCountDown();
					}
					else
					{
						hideAllExcept(songListDiv, subButton);	
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
						//alert(arguments.length); // Outputs: 10

					subButton.style.display = 'none';
					submitErrorDiv.style.display = 'none';
					tyDiv.style.display = 'none';
					waitDiv.style.display = 'none';
					loginButton.style.display = 'none'; 
					songListDiv.style.display = 'none';
					loginDiv.style.display = 'none';
					pwIncorrectDiv.style.display = 'none';
					waitToSubmitDiv.style.display = 'none';
					voteCountDown.style.display = 'none';

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
					//alert('timePassedSinceLastVote: ' + timePassedSinceLastVote);
					timePassedSinceLastVote = 300001;
					if(timePassedSinceLastVote<=300000)
					{
						var waitTime = 300000-timePassedSinceLastVote;

						var ms = waitTime,
						   min = (ms/1000/60) << 0,
						   sec = (ms/1000) % 60;

						//alert('Sorrry you\'ll have to wait '+ min + 'm'+Math.round(sec)+'s to vote again.' );
						
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
						hideAllExcept(waitToSubmitDiv,songListDiv,subButton);
						return;
					}
				}

				var songContainer = document.getElementById('songListContainer');
				var list = listChildrenDivs(songContainer);
				var values = '';
				var columns = '' ;
				
				hideAllExcept(waitDiv);

				

				
				for (j = 0; j < list.length; j++)
				{
					//tempId = list[j].id;
					//tempId = '`' + tempId + '`';
					//values = values + list[j].value; 
					//columns = columns + j;
	
					columns = columns + '`' + list[j].getAttribute('data-colNum') + '`';
					values = values + (j+1);
					if(j!=(list.length-1))
					{
						values = values + ', ';
						columns = columns + ', ';
					}
				}
	
				console.log(columns);
				console.log(values);
				insertToDB(columns, values, tableName,selectionsSubmitted);				
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
 									console.log(status);		
	 								console.log(data);
									console.log(data.currentSongPlaying.songName);
									currentSongDetails = data.currentSongPlaying;
									if(currentSongDetails.songDuration=='NaN'){
										currentSongDetails.songDuration = 300;
									}
								},
								error:function(jqXHR, textStatus, errorThrown){
									console.log(textStatus);
									console.log(errorThrown);
								}
							});	
			}
	
			function selectionsSubmitted(response)
			{
				if(response==1)
				{
					//hideAllExcept(songListDiv,subButton,tyDiv);
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
					console.log('from selectionsSubmitted hr response 1: '+currentSongDetails.songDuration);
					console.log('from selectionsSubmitted hr response 1: '+currentSongDetails.songName);
					console.log('from selectionsSubmitted hr response 1: '+currentSongDetails.startTime);
					canVoteAtTime = parseFloat(currentSongDetails.songDuration) +parseFloat(currentSongDetails.startTime);					
					document.getElementById('voteCountDownSongName').innerHTML = currentSongDetails.songName;
					updateVoteCountDownInterval = setInterval(updateVoteCountDown, 1000);
			}
			
			function updateVoteCountDown()
			{
				
				var minutesString;
				var currentTime = Date.now()/1000.0;
				var timeLeft = (canVoteAtTime-currentTime);
				
				console.log('from updateVoteCountDown: currentime: '+currentTime);
				
				if(timeLeft<1)
				{
						clearTimeout(updateVoteCountDownInterval);
					  localStorage.setItem(tableName,0);
					  hideAllExcept(songListDiv,submitButton);
				}
				else
				{
						
						var minutes = Math.floor(timeLeft / 60);
						var seconds = timeLeft - minutes * 60;
					
						if(minutes>0)
						{
							var minutesString = minutes + ' minutes and ';
						}
						else if(minutes=1)
						{
							var minutesString = minutes + ' minute and ';
						}
						else
						{
							minutesString = '';
						}
					
					
						console.log('from updateVoteCountDown: minutes: '+minutes);
					  console.log('from updateVoteCountDown: seconds: '+seconds);
						document.getElementById('voteCountDownMin').innerHTML = minutesString;
						document.getElementById('voteCountDownSec').innerHTML = Math.round(seconds);
				}
				
				console.log('from updateVoteCountDown currentTime: '+currentTime);
				console.log('from updateVoteCountDown canVoteAtTime: '+canVoteAtTime);
				
				
			}
		</script>
					
	</head>
	<body>
		<div class='left'><br></div>
		<div class='center'>		
			<div class='card msg pageTitle'>dididi</div>
			<div id='loginContainer' class='card' style=''>	 <!--style='display: none;'-->				
				<span style='text-align:left; font-family: Source Sans Pro, sans-serif;color:#E7522D; font-size:.7em;'>Playlist Password:</span><br>
				<input type='password' id='pwFeild' class='customText' onkeydown='enterKeyPressed()'/>
			</div>
			<input id='loginButton' type='button' value='Login' class='longButton' onclick='showList()' />
			<div id='songListContainer' style='display:none; padding: 0 0 12px 0;'>  
	

		
				<div class='card msg' id='song 1' style='display:block' data-colNum=0>
					<span id='song 1_span'>song 1</span>
				</div>
			
			
				<div class='card msg' id='song 2' style='display:block' data-colNum=1>
					<span id='song 2_span'>song 2</span>
				</div>
			
			
				<div class='card msg' id='song 3' style='display:block' data-colNum=2>
					<span id='song 3_span'>song 3</span>
				</div>
			
			
				<div class='card msg' id='song 4' style='display:block' data-colNum=3>
					<span id='song 4_span'>song 4</span>
				</div>
			
			
				<div class='card msg' id='song 5' style='display:block' data-colNum=4>
					<span id='song 5_span'>song 5</span>
				</div>
			
			
				<div class='card msg' id='song 6' style='display:block' data-colNum=5>
					<span id='song 6_span'>song 6</span>
				</div>
			
			
				<div class='card msg' id='song 7' style='display:block' data-colNum=6>
					<span id='song 7_span'>song 7</span>
				</div>
			
			
				<div class='card msg' id='song 8' style='display:block' data-colNum=7>
					<span id='song 8_span'>song 8</span>
				</div>
			
			
				<div class='card msg' id='song 9' style='display:block' data-colNum=8>
					<span id='song 9_span'>song 9</span>
				</div>
			
			
				<div class='card msg' id='song 10' style='display:block' data-colNum=9>
					<span id='song 10_span'>song 10</span>
				</div>
			
			
				<div class='card msg' id='song 11' style='display:block' data-colNum=10>
					<span id='song 11_span'>song 11</span>
				</div>
			
			
		
		</div> <!--song list container close -->	
		<input type='button' value='submit' id='submitButton' onClick='listSubmit()' class='longButton' style='display:none'/>
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
		