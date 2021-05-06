<?php
	include 'includes/get-data.php';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

function Game(title, genre, age, img, rating, devices, modes, kidfriendly, score){
	this.title = title;
	this.genre = genre;
	this.age = Number(age);
	this.img = img;
	this.rating = Number(rating);
	this.devices = devices;
	this.modes = modes;
	this.kidfriendly = Number(kidfriendly);
	this.score = 0;

	this.modeValue = function() {
		if(this.modes == 'multiplayer'){
			return 1;
		}
		else if(this.modes == 'singleplayer'){
			return 2;
		}
		else if(this.modes == 'singleplayer multiplayer'){
			return 3;
		}
	};
}
//testing for navigation menu
function openNav() {
	document.getElementById("nav_container").style.width = "250px";
}

function closeNav() {
	document.getElementById("nav_container").style.width = "0px";
}
/*
function togglePlatformSelection() {
	var acc = document.getElementsByClassName("accordian");
   	this.classList.toggle("active");
   	var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
    	panel.style.display = "none";
	} else {
    	panel.style.display = "block";
    }
}
*/
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

//Finds unique games that have not been previously selected
function findUniqueGames(selectedGames, allGames){
	var possibleResults = [];
	var z = 0;

	for (i = 0; i < allGames.length; i++){
		for (k = 0; k < selectedGames.length; k++){
			if(allGames[i].title == selectedGames[k].title){
				break;
			}
			else if((allGames[i].title != selectedGames[k].title) && (k == (selectedGames.length - 1))){
				possibleResults[z] = allGames[i];
				z++;
			}
		}
	}
	return possibleResults;
}
//finds unique genres of selected games to match with
function findUniqueGenres(games){
	var splitGenres = [];
	var uniqueGenres = [];

	for (i = 0; i < games.length; i++){
		splitGenres[i] = games[i].genre.split(" ");
		for (k = 0; k < splitGenres[i].length; k++){
			uniqueGenres.push(splitGenres[i][k]);
		}
	}
	let genres = [...new Set(uniqueGenres)];
	return genres;
}
//finds all genres (so you can count occurances of specific genres) - not in use currently
function findGenres(games){
	var splitGenres = [];
	var allGenres = [];

	for (i = 0; i < games.length; i++){
		splitGenres[i] = games[i].genre.split(" ");
		for (k = 0; k < splitGenres[i].length; k++){
			allGenres.push(splitGenres[i][k]);
		}
	}
	return allGenres;
}

function getModes(numbers){
    var modes = [], count = [], i, number, maxIndex = 0;
 
    for (i = 0; i < numbers.length; i += 1) {
        number = numbers[i];
        count[number] = (count[number] || 0) + 1;
        if (count[number] > maxIndex) {
            maxIndex = count[number];
        }
    }
    for (i in count)
        if (count.hasOwnProperty(i)) {
            if (count[i] === maxIndex) {
                modes.push(Number(i));
            }
        }
    return modes;
}

//takes selected games by the user and compares with all games in db to find good matches
//good matches being?
	//genre match
	//
	//50% rating
	//20% age
	//cont.,.
	//remove imagepicker creation to main, and use recommender as a inbetween to find recommended games DONE
	//how should singleplayer/multiplayer be treated? 
function recommender(selectedGames, allGames){
	var results = [];
	var modes = [];
	var possibleResults = findUniqueGames(selectedGames, allGames);
	var selectedGenres = findUniqueGenres(selectedGames);

	for(i = 0; i < selectedGames.length; i++){
		modes[i] = selectedGames[i].modeValue();
	}
	var selectedModes = getModes(modes);

	var z = 0;
	for (i = 0; i < possibleResults.length; i++){
		for(k = 0; k < selectedGenres.length; k++){
			if(possibleResults[i].genre.includes(selectedGenres[k])){ //checks genre match and mode match of possible results with previous selections
				results[z] = possibleResults[i];
				z++;
				break;
			}
		}
	}
	//Now we have results with genre matched titles that are unique. Implement equation here to find best match

	for (i = 0; i < results.length; i++){
		results[i].score += results[i].rating; //50% weighting on rating
		if(selectedModes.includes(results[i].modeValue()) || results[i].modeValue() == 3){
			results[i].score += 10; //10% weighting on mode match or if has both modes
		}
		if(results[i].age >= 2015){
			results[i].score += 10; //10% weighting on if a fairly new game (last 5 years)
		}
	}
	results.sort((a, b) => b.score - a.score);

	for(i = 0; i < results.length; i++){
		console.log(results[i].title + " and a score of : " + results[i].score);
	}

	return results;
}

//displays the games that match with first selections to the second selection area
function displayMatches(results){
	var node = document.createElement("p");
	var nodeText = document.createTextNode("Great! Now select a few more");
	node.appendChild(nodeText);
	document.getElementById("instructionsForSecondChoice").appendChild(node);
	
	var newOptions = document.createElement("select");
	newOptions.setAttribute("class", "image-picker");
	newOptions.setAttribute("name", "newOptions");
	newOptions.setAttribute("data-limit", "6");
	newOptions.setAttribute("multiple", "multiple");
	for (var i=0; i < results.length; i++){
		var optionSelection = document.createElement("option");
		optionSelection.setAttribute("data-img-src", results[i].img);
		optionSelection.setAttribute("value", i+1);
		newOptions.appendChild(optionSelection);
	}
	document.getElementById("option2").appendChild(newOptions);
}

// Gets game data from database, stores games in array
function getGames(){
	var games = <?php echo json_encode(GetData(), JSON_HEX_TAG); ?>;
	var gameLibrary = [];
	for (i = 0; i < games.length; i++){
		gameLibrary[i] = new Game(games[i].title, games[i].genre, games[i].age, games[i].img, games[i].rating, games[i].devices, games[i].modes, games[i].kidfriendly);
	}
	return gameLibrary;
}

//displays initial possible game selections that is refreshable for different choices
function initialGames(allGames){
	var options = document.createElement("select");
	options.setAttribute("class", "image-picker");
	options.setAttribute("id", "pics");
	options.setAttribute("data-limit", "3");
	options.setAttribute("multiple", "multiple");
	for (var i=0; i < 8; i++){
		var optionSelection = document.createElement("option");
		optionSelection.setAttribute("data-img-src", allGames[i].img); //get img names from db
		optionSelection.setAttribute("value", i+1); 
		options.appendChild(optionSelection);
	}
	document.getElementById("option1").appendChild(options);
}

//displays results of the recommend function to the final selection area
function displayResults(selectedGames){
	var node = document.createElement("H1");
	var nodeText = document.createTextNode("Results");
	node.appendChild(nodeText);
	document.getElementById("displayResults").appendChild(node);

	var newOptions = document.createElement("select");
	newOptions.setAttribute("class", "image-picker");
	newOptions.setAttribute("name", "results");
	//newOptions.setAttribute("data-limit", "6");
	newOptions.setAttribute("multiple", "multiple");
	for (var i=0; i < selectedGames.length; i++){
		if(i > 6){ //arbitrary stop for scored results
			break;
		}
		var optionSelection = document.createElement("option");
		optionSelection.setAttribute("data-img-src", selectedGames[i].img);
		optionSelection.setAttribute("value", i+1);
		newOptions.appendChild(optionSelection);
	}
	document.getElementById("option3").appendChild(newOptions);
}

//displays possible devices for users to select
/*
function displayDevices(devices){
	var deviceOptions = document.createElement("select");
	deviceOptions.setAttribute("class", "image-picker");
	deviceOptions.setAttribute("id", "devices");
	deviceOptions.setAttribute("data-limit", "5");
	deviceOptions.setAttribute("multiple", "multiple");
	for (var i =0; i < devices.length; i++){
		var optionSelection = document.createElement("option");
		optionSelection.setAttribute("data-img-src", devices[i].img);
		optionSelection.setAttribute("value", i+1);
		deviceOptions.appendChild(optionSelection);
	}
	document.getElementById("devices").appendChild(deviceOptions);
}
*/

// Sorting based on genre not working well as genres are shared/too broad to recommend based on alone (eg outer worlds is/can be a shooter or action rpg vs CoD)
// Handles 
$(document).ready(function () {
	/*
	var devices = [];
	devices.push({name: "xbox", img: "xbox.jpg"}, {name: "ps4", img: "ps4.jpg"}, {name:"switch", img:"switch.png"}, {name:"windows", img:"windows.png"});
	*/
	var initialSelections = [];
	var matches;
	var secondMatches = [];
	var complete = false;
	var games = getGames();
	shuffleArray(games);

	// Display device selection
	/*
	displayDevices(devices);
	$("select[name=deviceOptions]").imagepicker();
	*/
	// Initialize the image picker
	initialGames(games, 0);
	$("select").imagepicker();
	
	// Get selection(s) on click
	document.getElementById("submit").onclick = function (){
		var selections = $("select").data("picker").selected_values();
		for (z = 0; z < selections.length; z++){
				initialSelections[z] = games[selections[z] - 1];
		}
		if(($("select[name=newOptions]").length == 0) && selections.length){
			//matches = recommendGames(selections); //change selections (a 1 to something number in array of selections in imagepicker) to initialSelections, a list of games selected
			matches = recommender(initialSelections, games);
			displayMatches(matches);
			$("select[name=newOptions]").imagepicker();


			//testing
			//$("select").stop();
			//console.log("attempting to turn off selection of first block");
		}
		else if (complete == false){
			var secondSelections = $("select[name=newOptions]").data("picker").selected_values();
			//TEST
			//$("select[name=newOptions]").data("picker").toggle();
			//TEST
			for (i = 0, j = 0; i <= matches.length; i++){
				for(k = 0; k < secondSelections.length; k++){
					if((secondSelections[k] - 1) == i){
						secondMatches[j] = matches[i];
						j++;
					}
				}
			}
			var overall_played = initialSelections.concat(secondMatches);	
			var results = recommender(overall_played, games);
			displayResults(results);
			$("select[name=results]").imagepicker();
			complete = true;
		}
	}
	//Refreshes page back to original state with newly shuffled selections
	document.getElementById("refresh").onclick = function() {
		$("#option1").empty();
		$("#option2").empty();
		$("#option3").empty();
		$("#option4").empty();
		$("#recommends").empty();
		$("#displayResults").empty();
		$("#instructionsForSecondChoice").empty();
		console.log("refreshing");
		for (x of games){ //resets scoring
			x.score = 0;
		}
		shuffleArray(games); //create new list of games
		initialGames(games);
		$("select").imagepicker();
		complete = false;
	}
});

//recieves selections of games from the 2nd tier, returns recommended games based on genre matching
/* DEPRECATED - but sorting results based on genre still good idea
function recommendGenresAndGames(selections){
	var matches = [];
	var recommendations = [];
	recommendations[1] = {title:"Battlefield 5", genre:"shooter", age:"2018", img:"bf5.jpg"};
	recommendations[2] = {title:"Fallout: New Vegas", genre:"shooter rpg", age:"2010", img:"fonv.jpg"};
	recommendations[3] = {title:"Pillars of Eternity", genre:"rpg", age:"2015", img:"pillars.jpg"};
	recommendations[4] = {title:"Tom Clancy's Rainbow Six Siege", genre:"shooter", age:"2015", img:"siege.jpg"};
	recommendations[5] = {title:"FIFA 20", genre:"soccer", age:"2019", img:"fifa20.jpg"};
	recommendations[6] = {title:"Overwatch", genre:"shooter", age:"2016", img:"overwatch.jpg"};
	
	var line = document.createElement("hr");
	document.getElementById("recommends").appendChild(line);
	var h = document.createElement("H1");
	var t = document.createTextNode("Recommendations");
	h.appendChild(t);
	document.getElementById("recommends").appendChild(h);
	
	var allGenres = [];
	for(i = 0; i < selections.length; i++){
		allGenres[i] = selections[i].genre;
	}
	let genres = [...new Set(allGenres)];
	for (i = 0; i < genres.length; i++){
		var h = document.createElement("H1");
		var t = document.createTextNode(genres[i]);
		h.appendChild(t);
		
		var newOptions = document.createElement("select");
		newOptions.setAttribute("class", "image-picker");
		newOptions.setAttribute("name", genres[i].replace(/\s+/g, ''));
		newOptions.setAttribute("data-limit", "2");
		newOptions.setAttribute("multiple", "multiple");
		for (var j = 1; j < recommendations.length; j++){
			//var gameGenres = recommendations[j].genre.split(" ");
			if(recommendations[j].genre == genres[i]){ //NOT WORKING CORRECTLY due to multiple genres
				var optionSelection = document.createElement("option");
				optionSelection.setAttribute("data-img-src", recommendations[j].img);
				optionSelection.setAttribute("value", j);
				newOptions.appendChild(optionSelection);
			}
		}
		if (i > 0){
			//document.getElementById("firstGenre").appendChild(h);
			document.getElementById("option4").appendChild(newOptions);
		} else {
			//document.getElementById("secondGenre").appendChild(h);
			document.getElementById("option3").appendChild(newOptions);
		}
	}
	return genres;
}
*/
</script>