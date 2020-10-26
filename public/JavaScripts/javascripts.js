function myFunction(){
 /*('#user').on('change',function myFunction(){*/
    document.getElementById("test").innerHTML ="siema";
    /*var value = $(this).val();*/
};

function userHasAddedMatch()
{
    /*document.getElementById("test").innerHTML ="siema";*/
    //let user = document.getElementById("user" + i);
    let matchNr = document.getElementById("nrMatch");
   $.ajax({
        url: "/home/addResults/MatchAddedForUser",
        type: "GET",
	data: "matchNr=" + matchNr.value
    }).done(function(result) {
            let resultJson = JSON.parse(result);
            i = 0;
            if (resultJson.matchResult !== null)
            {
                for (var key in resultJson.matchResult.matchPlayers)
                {
                    let user = resultJson.matchResult.matchPlayers[key].user.login;
                    let beers = resultJson.matchResult.matchPlayers[key].beers;
                    let tokens = resultJson.matchResult.matchPlayers[key].tokens;
                    let points = resultJson.matchResult.matchPlayers[key].points;
                    addInputWithData (resultJson.matchResult.matchPlayers)
                    //addInputWithData (user, beers, tokens, points);
                    //document.getElementById("ResultForUserExist").innerHTML = "";
                    
                    //document.getElementById("beers" + i).value = resultJson.matchResult.matchPlayers[key].beers;
                   // document.getElementById("tokens" + i).value = resultJson.matchResult.matchPlayers[key].tokens;
                   // document.getElementById("points" + i).value = resultJson.matchResult.matchPlayers[key].points;
                    i += 1;
                }
                let saveBtn = document.querySelector('.savebtn');
                saveBtn.innerHTML = '<input id ="savebtn" type ="submit" value="Zaktualizuj Dane">';             
            }
            else
            {   
                let inputs = document.getElementsByClassName('inputResult');
                for (var i =0; i<inputs.length; ++i)
                {   
                    document.getElementById('points' + i).value = '';
                    document.getElementById('beers' + i).value = '';
                    document.getElementById ('tokens' + i).value = '';
                }
                let savebtn = document.querySelector('.savebtn');
                savebtn.innerHTML = '<input id ="savebtn" type ="submit" value="Zapisz">';
            }
    });    

}
function addInputWithData(matchPlayers)
//function addInputWithData(user, beers, tokens, points)
{
    for (var key in matchPlayers)
    {
        alert (matchPlayers[key].user.login);
    }
    let optionValue =document.getElementById('usersHtml').innerHTML;
    
    //'<input type="text" value ="' + user +'" readonly>';
    let string ='<div id="usersHtml">' + optionValue + '</div><div class ="inputResult">' +
                    '<div class ="inputTitle"><div class = "inputTitles">Punkty</div><div class ="inputTitles">Piwa</div><div class ="inputTitles">Żetony</div></div>' +
                            '<div class ="inputValue"><div class = "inputValues"><input type="text" id="points" name ="points[]" value="' + points + '"></div>' +
                                '<div class ="inputValues"><input  type="text" id="beers" name ="beers[]" value = "' + beers + '" ></div>' +
                                '<div class = "inputValues"><input type ="text" id="tokens" name ="tokens[]" value = "' + tokens +'"></div> </div></div>';
    document.getElementById('user').innerHTML += string;
}

$(document).ready(function() {
$('#matchType').on("change", function(){

    if ($(this).val() === 'Mecz ligowy')
    {
        document.getElementById('countTokensToBeersText').innerHTML = '';
        document.getElementById ('countTokensToBeersValue').innerHTML = '';
    }
    else
    {
        document.getElementById('countTokensToBeersText').innerHTML = 'Podaj przelicznik żetonów za jedno piwo';
        document.getElementById ('countTokensToBeersValue').innerHTML = '<input type="text" name="countTokensToBeers">';   
    }
})
$('#addNextUser').on("click", function(){
    let optionValue =document.getElementById('usersHtml').innerHTML;
    let string ='<div>' + optionValue + '</div><div class ="inputResult">' +
                    '<div class ="inputTitle"><div class = "inputTitles">Punkty</div><div class ="inputTitles">Piwa</div><div class ="inputTitles">Żetony</div></div>' +
                            '<div class ="inputValue"><div class = "inputValues"><input type="text" id="points" name ="points[]"></div>' +
                                '<div class ="inputValues"><input  type="text" id="beers" name ="beers[]" ></div>' +
                                '<div class = "inputValues"><input type ="text" id="tokens" name ="tokens[]"></div> </div></div>';

    document.getElementById('user').innerHTML += string;
})
});








