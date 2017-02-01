function insertToDB(columns, values, tableName, callback)
{
	var hr = new XMLHttpRequest();
	var url = '../db_functions.php';
	hr.open('POST', url, true);
	var postValues = 'functionName=db_insertRecord&tableName='+tableName+'&columns='+columns+'&values='+values;
	hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	hr.onreadystatechange = function() 
	{
		if(hr.readyState == 4 && hr.status == 200) 
		{
			//alert('hr.response: '+hr.responseText);
			callback(hr.responseText);
			/*
			if(hr.responseText)
			{
				//insertComplete = true;
				//var testObject = { 'pageName': pageName, 'timeSubMitted': 2, 'three': 3 };
				//alert('made it if after submit')
				// Put the object into storage
				
				if(callingFunction=='listSubmit')
				{
					var d = new Date();
					var n = d.getTime();
					localStorage.setItem(pageName, n);
					document.getElementById('ty').style.display='block';
					document.getElementById('wait').style.display='none';
				}
				
				// Retrieve the object from storage
				//var retrievedObject = localStorage.getItem('testObject');
				//console.log('retrievedObject: ', JSON.parse(retrievedObject));
			}
			else
			{
				//document.getElementById('submitError').style.display='block';
				//document.getElementById('wait').style.display='none';
			}
			*/
		}
	}
	hr.send(postValues);	
}

function listChildrenDivs(divContainer)
{
	 //alert('in list div');
	 //console.log('accepting div: '+divContainer + ' ' + divContainer.id);
	 // console.log('in listChildrenDivs() length of list: '+ divContainer.childNodes.length);
	 var listOfDivs = new Array();
	 //console.log('created new list array');
	 
	 var children = divContainer.childNodes;
	 //console.log('grabbed: children divs of list div');
	 
	 for(var i =0; i<children.length;i++)
	 {
	 	var childTag = children[i].tagName;
	 	//console.log('in listSOngsDiv for loop, child tag: ' + childTag);
		if(childTag!==undefined&&childTag==('DIV'))
		{
			//console.log(children[i].id);
			listOfDivs.push(children[i]);
			//console.log('add to list array: ' + children[i].id);
		}
	 }
	 //console.log('length of listofdivs: '+listOfDivs.length);
	 //insertBottom(listOfDivs[0],listOfDivs[4]);
	 //insertTop(listOfDivs[0],listOfDivs[4]);
	// console.log('______________________________________________________________');
	 //for(var i =0; i<listOfDivs.length;i++)
	 //{
		 //console.log('contents of index: '+ i);
		 //console.log(listOfDivs[i].id);
		 //console.log(getNameSpanOf(listOfDivs[i]).innerHTML);
	 //}
	 
	 return listOfDivs;
}

function getPw(tableName,pwType, callback)
{
	
	 	var hr = new XMLHttpRequest();
		// Create some variables we need to send to our PHP file
		var url = '../db_functions.php';
		//post value format is key=value&anotherKey=AnotherValue&....
		var postValues = 'functionName=db_getPwForPage&tableName='+tableName+'&pwType='+pwType;
		hr.open('POST', url, true);
		// Set content type header information for sending url encoded variables in the request
		hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// Access the onreadystatechange event for the XMLHttpRequest object
		hr.onreadystatechange = function() 
		{
			if(hr.readyState == 4 && hr.status == 200) 
		{
				var requestResult = hr.responseText;
				//alert('result: ' +requestResult.trim()); 
				callback(requestResult.trim());
			} 
	}

	hr.send(postValues); 
}

function clearRecords(tableName,callback)
{
		var hr = new XMLHttpRequest();
		// Create some variables we need to send to our PHP file
		var url = '../db_functions.php';
		//post value format is key=value&anotherKey=AnotherValue&....
		var postValues = 'functionName=db_clearRecords&tableName='+tableName;
		hr.open('POST', url, true);
		// Set content type header information for sending url encoded variables in the request
		hr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		// Access the onreadystatechange event for the XMLHttpRequest object
		hr.onreadystatechange = function() 
		{
			if(hr.readyState == 4 && hr.status == 200) 
		{
				var requestResult = hr.responseText;
				//alert('result: ' +requestResult.trim()); 
				callback(requestResult.trim());
			} 
	}

	hr.send(postValues); 
}
		