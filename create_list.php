<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1; initial-scale=1">
<link rel='stylesheet' href='votePage.css'>	
<title>Party Polling List Generator</title>


 <script type='text/javascript'>
        var i = 1;
		var userPwOk = false;
		var userVerifyOk = false;
		var adminPwOk = false;
		var adminVerifyOk = false;
		var listNameOk = false;
		var listNameExists = false;
		var pageUrl = 'www.asiamchowdhury.com/';
		var go = false;

		
		function setGoSignal()
		{
			var hr = new XMLHttpRequest();
			var url = "fileDuplicateScanner.php";
			hr.open("POST", url, false);
			listname = document.getElementById('listName').value;
			var postValues = "listName="+listname;
			hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			hr.onreadystatechange = function() 
			{
				if(hr.readyState == 4 && hr.status == 200) 
				{
					//alert(hr.responseText);
					if(hr.responseText==1)
					{
						//alert('File already Exists');
						listNameExists = true;
						document.getElementById('listNameExists').style.display = 'block';
						document.getElementById('plsWait').style.display = 'none'; 
						document.getElementById('fullForm').style.display = 'block';
					}
					else
					{
						document.getElementById('listNameExists').style.display = 'none';
					}
					//alert(	"userPwOk: "+userPwOk+
					//		"userVerifyOk: "+userVerifyOk+
					//		"adminPwOk: "+adminPwOk+
					//		"adminVerifyOk: "+adminVerifyOk+
					//		"listNameOk: "+listNameOk+
					//		"listNameExists: "+listNameExists);
					if(userPwOk&&userVerifyOk&&adminPwOk&&adminVerifyOk&&listNameOk&&!listNameExists)
					{
						//alert('in return true');
						go = true;
					}
					else
					{
						//alert('in return false');
						document.getElementById('plsWait').style.display = 'none'; 
						document.getElementById('fullForm').style.display = 'block';
						document.getElementById('listNameExists').style.display = 'block';
						go = false;
						
					}					
				}
			}
			listNameExists = false;
			hr.send(postValues);
				
		}

		function finalVerification()
		{
			document.getElementById('plsWait').style.display = 'block'; 
			document.getElementById('fullForm').style.display = 'none';
			setGoSignal();
			//alert("go value: "+go);
			return go;
		}

		function verifyAndModify(value,toShortDiv,illegalDiv)
		{
			var pwToShort;
			var PwIllegal;

			if(isShort(value))
	    	{
				toShortDiv.style.display = 'block';
				pwToShort = true;
	    	}
	    	else
	    	{
	    		toShortDiv.style.display = 'none';
	    		pwToShort = false;
	    	}

	    	if(isIllegal(value))
	    	{
	    		illegalDiv.style.display = 'block';
	    		PwIllegal = true;
	    	}
	    	else
	    	{
	    		illegalDiv.style.display = 'none';
	    		PwIllegal = false;
	    	}

			if(pwToShort||PwIllegal)
			{
				return false;
			}
			else
			{
				return true;
			}
	    	
		}

        function validatePw(object)
        {
          //alert(object.name);
          //alert('in function');
          //alert(object.value);
          //alert(object.value.length);
          //userToShortDiv  userIllegalCharDiv adminToShortDiv adminIllegalCharDiv   
          switch(object.name) 
          {
		    case 'listPassword':		    	
		    	userPwOk = verifyAndModify(	object.value,
				    						document.getElementById('userToShortDiv'),
				    						document.getElementById('userIllegalCharDiv')); //alert("user pw: " + userPwOk);
    			verifyFeild = document.getElementsByName("VerifyListPassword")[0];
    			if(verifyFeild.value!='')
    			{
    				validatePw(verifyFeild);
    			}	
		        break;
		    case 'VerifyListPassword':
		    	if(confirmMatch(document.getElementsByName("listPassword")[0].value,object.value))
		    	{
		    		document.getElementById('userPwMisMatch').style.display = 'none';
		    		userVerifyOk = true;
		    	}
		    	else
		    	{	
		    		document.getElementById('userPwMisMatch').style.display = 'block';
		    		userVerifyOk = false;
		    	}
		        break;
		    case 'adminPassword':
		    	adminPwOk = verifyAndModify(object.value,
		    								document.getElementById('adminToShortDiv'),
		    								document.getElementById('adminIllegalCharDiv'),adminPwOk);// alert("admin pw: " + adminPwOk);

		    	userPw = document.getElementsByName("listPassword")[0].value;

		    	if(confirmMatch(userPw,object.value))
		    	{
		    		document.getElementById('adminMatchsUser').style.display = 'block';
		    		adminPwOk = false;
		    	}
		    	else
		    	{
		    		document.getElementById('adminMatchsUser').style.display = 'none';
		    	}

				verifyFeild = document.getElementsByName("VerifyAdminPassword")[0];
		    	if(verifyFeild.value!='')
		    	{
		    		 validatePw(verifyFeild);
		    	}
		    									
		        break;
		    case 'VerifyAdminPassword':
		    	if(confirmMatch(document.getElementsByName("adminPassword")[0].value,object.value))
		    	{
		    		document.getElementById('adminPwMisMatch').style.display = 'none';
		    		adminVerifyOk = true;
		    	}
		    	else
		    	{
		    		document.getElementById('adminPwMisMatch').style.display = 'block';
		    		adminVerifyOk = false;
		    	}
		        break;
		    case 'list_name':
		    	/*listNameOk = verifyAndModify(	object.value,
											document.getElementById('listNameToShortDiv'),
											document.getElementById('listNameIllegalCharDiv')); //alert("user pw: " + userPwOk);
				/*
				listInstructionContainer = document.getElementById('ListNameInstructions');
				if(listInstructionContainer.lastChild!=null)
				{
					listInstructionContainer.removeChild(listInstructionContainer.lastChild);
				}
				listInstructionContainer.appendChild(document.createTextNode("This will be your playlist page: " + pageUrl + object.value));
				*/
				/[\W_]/
				var illegalChars = /[^a-zA-Z0-9\s]+/;
				if(illegalChars.test(object.value))
    			{
					document.getElementById('listNameIllegalCharDiv').style.display = 'block';
					listNameOk = false;
    			}
				else
				{
					document.getElementById('listNameIllegalCharDiv').style.display = 'none';
					listNameOk = true;
				}
    			
	            if(object.value.length<6)
		        {
	            	document.getElementById('listNameToShortDiv').style.display = 'block';
	            	listNameOk = false;
		        }
	            else
	            {
	            	document.getElementById('listNameToShortDiv').style.display = 'none';
	            	listNameOk = true;
	            }
				
				
			    break;
			    
		  }
        }
        
		function isShort(string)
		{
			if(string.length<6)
	          {
	            return true;
	          }	    
			return false;    
		}

		function isIllegal(string)
		{
			var illegalChars = /[\W_]/; 
            if(illegalChars.test(string))
            {
              return true;
            }
            return false;
		}

        function confirmMatch(string1,string2)
        {
        	if(string1==string2)
        	{
            	return true;
        	}
        	return false
        }

 		
            function toggleInstructions(instructionObject)
            {
            	clearInstructions();
				id = instructionObject.id+"Instruction";		
				document.getElementById(id).style.display = 'block';
            }

            function clearInstructions()
            {
            	document.getElementById('listNameExists').style.display = 'none';
            	document.getElementById('listNameInstruction').style.display = 'none';
				document.getElementById('userPwInstruction').style.display = 'none';
				document.getElementById('adminPwInstruction').style.display = 'none';
            }
            

          
        
    </script>



</head>
<body>
 <div class='left'><br></div>
 <div id="outerContainer" class='center' style='margin:0 0 10px 0'>
	<div class='card msg pageTitle'>Playlist Creator</div>
	<form id='fullForm' action="list_submitted.php" method="POST" onkeypress="return event.keyCode != 13;" onsubmit="return finalVerification()">
	  
	  <div class='card'>
	  <span class='inputTextTitle'>Playlist Name:</span><br>
	  <input type="text" id="listName" name="list_name" value="" oninput="validatePw(this)" onfocus="toggleInstructions(this)" class='customText customTextCreateList' autofocus/><br>
	  </div>
	  
	  <div id='listNameInstruction' class='card msg'>This will be the address of your playlist: Asiamchowdhury.com/poller/<br>your playlist name</div>
	  <div id='listNameToShortDiv' class='card errorMsg'>List Name must be at least 6 characters.</div>
	  <div id='listNameIllegalCharDiv' class='card errorMsg'>List Name Contains Illegal Characters. Only letters and numbers are allowed.</div>
	  <div id='listNameExists' class='card errorMsg'>A playlist by that name already exists.</div>
	  
	  <div class='card'>
	  <span class='inputTextTitle'>Voter(User) Password:</span><br>  
	  <input type="password" id="userPw" name="listPassword" value="password" oninput="validatePw(this)" onfocus="toggleInstructions(this)"  class='customText customTextCreateList' /><br>
	  </div>
	  
	  <div id='userPwInstruction' class='card msg'>This will be the password your voters will use to see the list.</div>
	  <div id='userToShortDiv' class='card errorMsg'>Password must be at least 6 characters.</div>
	  <div id='userIllegalCharDiv' class='card errorMsg'>Password Contains Illegal Characters. Only letters and numbers allowed.</div>
	  
	  <div class='card'>
	  <span class='inputTextTitle'>Verify Voter(User) Password:</span><br>
	  <input type="password" name="VerifyListPassword" value="password" oninput="validatePw(this)" onfocus="clearInstructions()"  class='customText customTextCreateList' /><br>
	  </div>
	  
	  <div id='userPwMisMatch' class='card errorMsg'>User Pasword does not match.</div>
	  
	  <div class='card'>
	  <span class='inputTextTitle'>DJ(Admin) Password:</span><br>
	  <input type="password" id="adminPw" name="adminPassword"   value="password1" oninput="validatePw(this)" onfocus="toggleInstructions(this)"  class='customText customTextCreateList' /><br>
	  </div>
	  
	  <div id='adminPwInstruction' class='card msg'>This will be the password the DJ will use to add songs and use the player.</div>
	  <div id='adminToShortDiv' class='card errorMsg'>Password must be at least 6 characters.</div>
	  <div id='adminIllegalCharDiv' class='card errorMsg'>Password Contains Illegal Characters. Only letters and numbers are allowed.</div>
	  <div id='adminMatchsUser' class='card errorMsg'>Admin Password and User Password cannot be the same.</div>
	  
	  <div class='card'>
	  <span class='inputTextTitle'>Verify DJ(Admin) Password:</span><br>
	  <input type="password" name="VerifyAdminPassword"  value="password1" oninput="validatePw(this)"  onfocus="clearInstructions()"  class='customText customTextCreateList' /><br>
	  </div>
	  
	  <div id='adminPwMisMatch' class='card errorMsg'>Admin Pasword does not match</div>
	  
	
	 
	 
	  <br><input type="submit" value="Submit" onsubmit="return finalVerification()" class='longButton'>
	</form>
<div id='plsWait' class='card msg'>Please Wait</div>

<br>
<span class='inputTextTitle' style='color:white;float:left;margin:0 0 0 20px'>Asiamchowdhury.com</span>
<span class='inputTextTitle' style='color:white;float:right;margin:0 30px 0 0'>PMGCRise.com</span>
</div><!-- close center div -->
 <div class='right'><br></div>
</body>
</html>

