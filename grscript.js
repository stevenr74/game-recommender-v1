$(document).ready(function () {
	var matches;
	var secondMatches = [];
	var complete = false;
	// Initialize the image picker
    $("select").imagepicker();
	// Get selection(s) on click
	document.getElementById("submit").onclick = function (){
		var selections = $("select").data("picker").selected_values();
		if(($("select[name=newOptions]").length == 0) && selections.length){
			matches = recommendGames(selections);
			$("select[name=newOptions]").imagepicker();
		}
		else if (complete == false){
			var secondSelections = $("select[name=newOptions]").data("picker").selected_values();
			for (i = 0, j = 0; i <= matches.length; i++){
				for(k = 0; k < secondSelections.length; k++){
					if((secondSelections[k] - 1) == i){
						secondMatches[j] = matches[i];
						j++;
					}
				}
			}
			
			var genres = recommendGenresAndGames(secondMatches);
			for (i = 0; i < genres.length; i++){
				$("select[name=" + genres[i] + "]").imagepicker();
			}
			complete = true;
		}
	}
	
	document.getElementById("refresh").onclick = function() {

		$("#option2").empty();
		$("#option3").empty();
		$("#option4").empty();
		$("#recommends").empty();
		console.log("refreshing");

		complete = false;
	}
});

//matches initial selections with possible 2nd tier selections based on genre, after which the user selects again to narrow down results
function recommendGames(selections){
	//var selections = $("select").data("picker").selected_values();
	var games = [];
	games[1] = {title:"Fortnite", genre:"shooter", age:"2018-2019", img:"fortnite.jpg"};
	games[2] = {title:"World of Warcraft", genre:"mmo rpg", age:"2004-2019", img:"wow.jpg"};
	games[3] = {title:"The Witcher 3", genre:"rpg", age:"2015", img:"witcher3.jpg"};
	games[4] = {title:"Call of Duty Black Ops 4", genre:"shooter", age:"2018", img:"codbo4.jpg"};
	games[5] = {title:"Minecraft", genre:"builder", age:"2019", img:"minecraft.png"};
	games[6] = {title:"Stardew Valley", genre:"simulation rpg", age:"2016", img:"stardew.jpg"};
	
	var recommendations = [];
	recommendations[1] = {title:"Call of Duty Modern Warfare", genre:"shooter", age:"2019", img:"codmw.jpg"};
	recommendations[2] = {title:"Apex Legends", genre:"shooter", age:"2019", img: "apexlegends.jpg"};
	recommendations[3] = {title:"Rocket League", genre:"vehicle soccer", age:"2015", img:"rocketleague.jpg"};
	recommendations[4] = {title:"Divinity: Original Sin 2", genre:"rpg", age:"2017", img:"divinity2.jpg"};
	recommendations[5] = {title:"Terraria", genre:"builder", age:"2011", img:"terraria.jpg"};
	
	var matches = [];
	var z = 0;
	
	for (i=1; i < recommendations.length; i++){
		selection_loop:
		for (j=0; j < selections.length; j++){
			var keywordSelections = games[selections[j]].genre.split(" ");
				for(k=0; k < keywordSelections.length; k++){
					if(recommendations[i].genre.includes(keywordSelections[k])) {
						matches[z] = recommendations[i];
						z++;
						break selection_loop;
					}
				}
		}
	}
	
	var newOptions = document.createElement("select");
	newOptions.setAttribute("class", "image-picker");
	newOptions.setAttribute("name", "newOptions");
	newOptions.setAttribute("data-limit", "2");
	newOptions.setAttribute("multiple", "multiple");
	for (var i=0; i < matches.length; i++){
		var optionSelection = document.createElement("option");
		optionSelection.setAttribute("data-img-src", matches[i].img);
		optionSelection.setAttribute("value", i+1);
		newOptions.appendChild(optionSelection);
	}
	document.getElementById("option2").appendChild(newOptions);
	
	return matches;
}

//recieves selections of games from the 2nd tier, returns recommended games based on genre matching
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
		newOptions.setAttribute("name", genres[i]);
		newOptions.setAttribute("data-limit", "2");
		newOptions.setAttribute("multiple", "multiple");
		for (var j = 1; j < recommendations.length; j++){
			if(recommendations[j].genre == genres[i]){
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