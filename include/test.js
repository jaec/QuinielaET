window.onload = function () { 
	if(typeof results != 'undefined') {
		for (i in results) { 

			   $.find("input.score").map(function (i,v) {
				      re = /p(\d+)\[(v|l)\]/;
				    	res =  re.exec(i.name);
				    	i.value = results[res[1]][res[2]];
			    	  

				   })
			        
			//$.find("[name="+i+"[v]]")[0].value =
			//results[i]["v"]; $.find("[name="+i+"[l]]")[0].value = results[i]["l"]; }
	}

		  setInterval( countdown , "1000");
	      		

}