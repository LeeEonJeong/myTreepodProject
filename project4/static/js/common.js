function showObj(obj){
			var str="";
			for(key in obj){
				str  += key+"="+obj[key]+"\n";
			}
			alert('showObj\n'+str);
			return;
		}
