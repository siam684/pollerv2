function encodeIt(string)
	{
		// ` = !000!, ' = !001!, \ = !010!, + = !011!, ' = !100!, - = !101!, # = !110!, . = !111!
		
		tempString = string.replace(	'`',	'!0000!');
		tempString = tempString.replace('\"',	'!0001!');
		tempString = tempString.replace('\\' ,	'!0010!');
		tempString = tempString.replace('+',	'!0011!');
		tempString = tempString.replace('\'',	'!0100!');
		tempString = tempString.replace('-',	'!0101!');
		tempString = tempString.replace('#',	'!0110!');
		tempString = tempString.replace('.',	'!0111!');
		tempString = tempString.replace('&',	'!1000!');
		
		if(tempString.indexOf('`')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('\"')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('\\')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('\'')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('-')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('#')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('.')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('+')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('&')!=-1)
		{
				encodeIt(tempString);
		}
		
		
// 		while(	tempString.includes('`')||
// 				tempString.includes('\"')||
// 				tempString.includes('\\')||
// 				tempString.includes('\'')||
// 				tempString.includes('-')||
// 				tempString.includes('#')||
// 				tempString.includes('.')||
// 				tempString.includes('+')||
// 				tempString.includes('&'))
				
// 			{
// 			encodeIt(tempString);
// 			}
			
		return tempString;
		
	}

	function decodeIt(string)
	{
		tempString = string.replace(	'!0000!',	'`');
		tempString = tempString.replace('!0001!',	'\"');
		tempString = tempString.replace('!0010!',	'\\');
		tempString = tempString.replace('!0011!',	'+'	);
		tempString = tempString.replace('!0100!',	'\'');
		tempString = tempString.replace('!0101!',	'-');
		tempString = tempString.replace('!0110!',	'#');
		tempString = tempString.replace('!0111!',	'.');
		tempString = tempString.replace('!1000!',	'&');
		
				if(tempString.indexOf('!0000!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0001!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0010!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0011!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0100!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0101!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0111!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!0111!')!=-1)
		{
				encodeIt(tempString);
		}
		
		if(tempString.indexOf('!1000!')!=-1)
		{
				encodeIt(tempString);
		}
		
			
// 		while(	tempString.includes('!0000!')||
// 				tempString.includes('!0001!')||
// 				tempString.includes('!0010!')||
// 				tempString.includes('!0011!')||
// 				tempString.includes('!0100!')||
// 				tempString.includes('!0101!')||
// 				tempString.includes('!0110!')||
// 				tempString.includes('!0111!')||
// 				tempString.includes('!1000!'))
// 			{
// 			decodeIt(tempString);
// 			}
		return tempString;
	}
	