var el = document.getElementById("queryForm"); 

if(el.addEventListener){
    el.addEventListener("submit", callback, false);  //Modern browsers
}else if(el.attachEvent){
    el.attachEvent('onsubmit', callback);            //Old IE
}


var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() { // Call a function when the state changes.
    if (this.readyState === XMLHttpRequest.DONE) {
		try{
			var response = JSON.parse(this.responseText);
			if(this.status === 200){
				document.getElementById('results').innerHTML = response.html; 
			} else {
				var msg = response.html;
				if(typeof msg == 'undefined'){
					msg = this.responseText;
				}
				throw {message: msg};
			}
		} catch(e) {
			var errorMsg = "<p class='error'>There was an error with the request. Got status "+this.status+".</p>";
			errorMsg += "<pre>"+e.message+"</pre>"
			document.getElementById('results').innerHTML = errorMsg; 
		}

	} else {
		document.getElementById('results').innerHTML = "<div class='spinner'></div>"; 
	}
	document.getElementById('results').style.display = 'block';
}

function callback(event){
	event.preventDefault();
	var form = event.target;
	var data = form.elements;
	console.log(data);

	var queryString = Object.keys(data)
			.filter(function(key){ 
				return form.elements[key].name != "";
			})
			.map(function(key){
				return encodeURIComponent(data[key].name) + '=' + encodeURIComponent(data[key].value)
			})
			.join('&');

	console.log(queryString);

	xhr.open("GET", "api.php?"+queryString, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(); 
}