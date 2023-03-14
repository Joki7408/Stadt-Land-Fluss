//Startseite anzeigen
window.onload = showLoginDialog;

//Variablen
g_user = "";
g_host = "http://localhost/";
g_timer = false;
g_dontPoll = false;
g_poller = null;
g_opponent = "";
g_letter = "";
g_gameAlreadyFinished = false;
g_gameHasEnded = false;
g_gameStarted = false;

//Login
function showLoginDialog() {
	 document.getElementById("login").style.display   = "block";
	 document.getElementById("titleLogin").style.display   = "block";
	 document.getElementById("registerLink").style.display   = "block";
	 document.getElementById("btnLogin").style.display   = "block";
     document.getElementById("menue").style.display   = "none";
     document.getElementById("game").style.display = "none";
	 document.getElementById("waitingScreen").style.display = "none";
	 document.getElementById("inputPassword2").style.display = "none";
	 document.getElementById("titleRegister").style.display = "none";
	 document.getElementById("btnRegister").style.display = "none";
	 document.getElementById("btnZurueck").style.display = "none";

	 document.getElementById("inputUsername").value   = "";
	 document.getElementById("inputPassword").value = "";
	 document.getElementById("inputPassword2").value = "";
     document.getElementById("inputUsername").focus();

     document.getElementById("btnLogin").onclick = loginUser;
	 document.getElementById("registerLink").onclick = showRegisterDialog;
}

//Registrierung
function showRegisterDialog() {
	document.getElementById("inputPassword2").style.display = "inline";
	document.getElementById("titleRegister").style.display = "inline";
	document.getElementById("titleLogin").style.display = "none";
	document.getElementById("btnRegister").style.display = "inline";
	document.getElementById("btnZurueck").style.display = "inline";
	document.getElementById("btnLogin").style.display = "none";
	document.getElementById("registerLink").style.display = "none";
	
	document.getElementById("btnRegister").onclick = registerUser;
	document.getElementById("btnZurueck").onclick = showLoginDialog;
}

//Hauptmenue
function showMenue() {	
	document.getElementById("login").style.display = "none";
	document.getElementById("game").style.display = "none";
	document.getElementById("waitingScreen").style.display = "none";
	document.getElementById("menue").style.display = "block";
	document.getElementById("benutzerName").innerHTML = "Willkommen: " + g_user;
	
	document.getElementById("btnLogout").onclick = logoutUser;
}

//Spielseite
function showGame() {
	getRandomLetter();
	document.getElementById("menue").style.display = "none";
	document.getElementById("waitingScreen").style.display = "none";
	document.getElementById("game").style.display = "inline";
	
	document.getElementById("validate1").style.display = "none";
	document.getElementById("validate2").style.display = "none";
	document.getElementById("validate3").style.display = "none";
	document.getElementById("validate4").style.display = "none";
	document.getElementById("validate5").style.display = "none";	
	document.getElementById("btnEndGame").style.display = "none";
	document.getElementById("btnValidateWords").style.display = "inline";

    document.getElementById("input1").focus();
	document.getElementById("btnValidateWords").onclick = validateWords;	
}

//Anzeigen der Gegnereingaben zur Validierung
function showValidateWords() {	
	$.ajax({
		type: "POST",
		url: g_host + "getWords.php",
		data: {player:g_opponent},
		datatype: "xml"
	}).done(function( request ) {	 
		wordsString = request.getElementsByTagName('words')[0].childNodes[0].nodeValue;
		words = wordsString.split("|");

		document.getElementById("input1").value = words[0];
		document.getElementById("input2").value = words[1];
		document.getElementById("input3").value = words[2];
		document.getElementById("input4").value = words[3];
		document.getElementById("input5").value = words[4];

		document.getElementById("validate1").style.display = "inline";
		document.getElementById("validate2").style.display = "inline";
		document.getElementById("validate3").style.display = "inline";
		document.getElementById("validate4").style.display = "inline";
		document.getElementById("validate5").style.display = "inline";
		document.getElementById("btnEndGame").style.display = "inline";
		document.getElementById("btnValidateWords").style.display = "none";
		document.getElementById("btnEndGame").onclick = endGame;

	});		
}

//User einloggen, Aufruf login.php
function loginUser() {
	
	//Einlesen von Username und Passwort
	var username = document.getElementById('inputUsername').value;
	var password = document.getElementById('inputPassword').value;	 
	 
	//Aufruf login.php, poller starten
	$.ajax({
	  type: "POST",
	  url: g_host + "login.php",
	  data: {username:username,password:password},
	  datatype: "xml"
	}).done(function( request ) {	 
		 error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;	
		 if( error == '0' ) {
			// Erfolg 
			g_dontPoll = false; 
			g_user = username;
			g_timer = setInterval("registerPoller()", 1000);
			
			showMenue();
		 } else {
			// Fehler
			window.alert("Benutzerdaten falsch!");
		 }
	});
}

//User ausloggen, Aufruf logout.php
function logoutUser(){
	g_dontPoll = true;
	showLoginDialog();
}

//Gegner herausfordern, Aufruf offer.php
function challenge(challenged) { 
	$.ajax({
	type: "POST",
	url: g_host + "offer.php",
	data: {username:g_user, challenged:challenged},
	datatype: "xml"
	}).done(function( request ) {	 
		error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;	

		if( error == '0' ) {
			// Erfolg  
			challengeButtons = document.getElementsByClassName('challengeButton');

			//Nach Anfrage Button disabled
			for(let i = 0; i < challengeButtons.length; i++){
				challengeButtons[i].disabled = true;
			}
		} else {
			// Fehler
			window.alert("Es ist ein Fehler aufgetreten!");
		}
	});
}

//Aufruf accept.php
function accept(offerer){
	g_letter = generateRandomLetter();
	$.ajax({
		type: "POST",
		url: g_host + "accept.php",
		data: {username:g_user, opponent:offerer, letter:g_letter},
		datatype: "xml"
		}).done(function( request ) {	 
			error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;	
	
			if( error != '0' ) {
				window.alert("Es ist ein Fehler aufgetreten");
			} 
		});
}

function endGame() { 
	let value = 0;

	for (let i = 1; i < 6; i++){
		if (document.getElementById('checkbox'+(i)).checked == true){
			value += 1;
		}
	}
	
	$.ajax({
		type: "POST",
		url: g_host + "validateWords.php",
		data: {username:g_user, opponent:g_opponent, value:value},
		datatype: "xml"
	}).done(function( request ) {
		error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;
		if(error == 0){
			document.getElementById("login").style.display = "none";
			document.getElementById("game").style.display = "none";
			document.getElementById("waitingScreen").style.display = "block";
		}
		else{
			window.alert("Es ist ein Fehler aufgetreten!");
		}
	});
}

function validateWords() { 
	//PHP-Kram
	$.ajax({
		type: "POST",
		url: g_host + "gameFinished.php",
		data: {username:g_user},
		datatype: "xml"
	}).done(function( request ) {
		error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;
		if(error == 0){
			//showValidateWords();
		}
		else{
			window.alert("Es ist ein Fehler aufgetreten!");
		}
	});
	
}

//Random Buchstabe zum Spielen
function generateRandomLetter(){
	const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	const randomCharacter = alphabet[Math.floor(Math.random() * alphabet.length)];
	return randomCharacter;
}

function getRandomLetter(){
	$.ajax({
		type: "POST",
		url: g_host + "getLetter.php",
		data: {username:g_user},
		datatype: "xml"
	}).done(function( request ) {
		g_letter = request.getElementsByTagName('letter')[0].childNodes[0].nodeValue;
		const letterWithDots = g_letter + "..."
		document.getElementById("input1").placeholder = letterWithDots;
		document.getElementById("input2").placeholder = letterWithDots;
		document.getElementById("input3").placeholder = letterWithDots;
		document.getElementById("input4").placeholder = letterWithDots;
		document.getElementById("input5").placeholder = letterWithDots;
	});

}

//User registrieren, Aufruf register.php
function registerUser(){
	var username = document.getElementById("inputUsername").value;
	var passwordOne = document.getElementById("inputPassword").value;
	var passwordTwo = document.getElementById("inputPassword2").value;

	//Abfrage ob Eingaben korrekt
	if(passwordOne == passwordTwo && passwordOne != "" && username != ""){
		$.ajax({
			type: "POST",
			url: g_host + "register.php",
			data: {username:username,password:passwordOne},
			datatype: "xml"
		}).done(function( request ) {
			error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;
			if( error == '0' ) {
				// Erfolg  		
				showLoginDialog();
			} else {
				// Fehler
				window.alert("Benutzer schon vorhanden!");
			}
		});
	}else{
		window.alert("Es ist ein Fehler aufgetreten!");
	};	
}

//Staendiger refresh der Daten, Aufruf der poller.php
function registerPoller(interval) {
	if(g_dontPoll == false){
		var userList = document.getElementById("user-list");
		var offersList = document.getElementById("offers-list");

		$.ajax({
			type: "POST",
			url: g_host + "poller.php",
			data: {username:g_user},
			datatype: "xml"
		}).done(function( request ) {
			usersString = request.getElementsByTagName('users')[0].childNodes[0].nodeValue;
			users = usersString.split("|");

			userList.innerHTML = ""; // Leere die Liste
			offersList.innerHTML = "";

			//Schleife zur Anzeige der online Spieler
			for(let i = 0; i < users.length - 1; i++){
				if (users[i].toLowerCase() != g_user.toLowerCase()){
					userList.innerHTML += "<li class='list-group-item d-flex justify-content-between align-items-center'>" + users[i] + "<button onClick='challenge(" + '"'+ users[i] + '"' +")' type='button' class='btn btn-primary btn-xs challengeButton' >Herausfordern</button>";
					userList.innerHTML += "</li>";	
				}
			}

			if(request.getElementsByTagName('offerers')[0].childNodes[0] != undefined){
				offerersString = request.getElementsByTagName('offerers')[0].childNodes[0].nodeValue;
				offerers = offerersString.split("|");

				//Schleife zur Anzeige der Spielangebote
				for(let i = 0; i < offerers.length - 1; i++){

					if (offerers[i].toLowerCase() != g_user.toLowerCase()){
						offersList.innerHTML += "<li class='list-group-item d-flex justify-content-between align-items-center'>" + offerers[i] + "<button onClick='accept(" + '"'+ offerers[i] + '"' +")' type='button' class='btn btn-primary btn-xs challengeButton' >Annehmen</button>";
						offersList.innerHTML += "</li>";
					}		
				}
			}

			//Opponent

			if (request.getElementsByTagName('opponent')[0].childNodes[0] != undefined && g_opponent == ""){
				showGame();
				g_gameStarted = true;
				g_opponent = request.getElementsByTagName('opponent')[0].childNodes[0].nodeValue;
			}

			if (request.getElementsByTagName('finished')[0].childNodes[0] != undefined && g_gameAlreadyFinished == false){
				if(request.getElementsByTagName('finished')[0].childNodes[0].nodeValue == 1){

					//Wörter in DB schreiben

					word1 = document.getElementById("input1").value;
					word2 = document.getElementById("input2").value;
					word3 = document.getElementById("input3").value;
					word4 = document.getElementById("input4").value;
					word5 = document.getElementById("input5").value;
					$.ajax({
						type: "POST",
						url: g_host + "saveWords.php",
						data: {player:g_user, word1:word1, word2:word2, word3:word3, word4:word4, word5:word5},
						datatype: "xml"
					}).done(function( request ) {			
						error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;
						if( error == '0' ) {
							// Erfolg  
							g_gameAlreadyFinished = true;	
							showValidateWords();
						} else {
							// Fehler
							window.alert("Benutzer schon vorhanden!");
						}
					});				
				}
			}

			if (request.getElementsByTagName('validated')[0].childNodes[0] != undefined && g_gameHasEnded == false && g_gameStarted == true){
				if(request.getElementsByTagName('validated')[0].childNodes[0].nodeValue == 2){
					$.ajax({
						type: "POST",
						url: g_host + "gameEnded.php",
						data: {username:g_user, opponent:g_opponent},
						datatype: "xml"
					}).done(function( request ) {
						resultString = request.getElementsByTagName('result')[0].childNodes[0].nodeValue;
						result = resultString.split("|");

						if(result[0] > result[1]){
							window.alert("Du hast gewonnen!");
						} else if (result[0] < result[1]){
							window.alert("Du hast verloren!");
						} else{
							window.alert("Unentschieden!");	
						}
						timeout = setTimeout(clearEverything, 300);
						showMenue()
					});
					g_gameHasEnded = true;
				}
			}
		});
	}
	

    
};

function clearEverything(){
	$.ajax({
		type: "POST",
		url: g_host + "clearDB.php",
		data: {username:g_user},
		datatype: "xml"
	}).done(function( request ) {
		error = request.getElementsByTagName('error')[0].childNodes[0].nodeValue;

		if(error == 0){
			g_opponent = "";
			g_gameAlreadyFinished = false;
			g_gameHasEnded = false;
			g_gameStarted = false;

			document.getElementById("input1").value = "";
			document.getElementById("input2").value = "";
			document.getElementById("input3").value = "";
			document.getElementById("input4").value = "";
			document.getElementById("input5").value = "";

			document.getElementById('checkbox1').checked = false;
			document.getElementById('checkbox2').checked = false;
			document.getElementById('checkbox3').checked = false;
			document.getElementById('checkbox4').checked = false;
			document.getElementById('checkbox5').checked = false;
			
		} else{
			window.alert("Es ist ein Fehler aufgetreten!");	
		}
	});
}