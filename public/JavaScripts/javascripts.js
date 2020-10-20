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
            if (resultJson.result !== null)
            {
                alert (resultJson.matchResult.matchNr + "mecz");
                for (var key in resultJson.matchResult.matchPlayers)
                {
                    document.getElementById("ResultForUserExist").innerHTML = "";
                    document.getElementById("beers" + i).value = "";
                    document.getElementById("tokens" + i).value = "";
                    document.getElementById("points" + i).value = "";
                    alert (key + "key");
                    let value = resultJson.matchResult.matchPlayers[key].beers;
                    alert (value + "alo");
                    document.getElementById("beers" + i).value = resultJson.matchResult.matchPlayers[key].beers;
                    document.getElementById("tokens" + i).value = resultJson.matchResult.matchPlayers[key].tokens;
                    document.getElementById("points" + i).value = resultJson.matchResult.matchPlayers[key].points;
                    //document.getElementById("ResultForUserExist").innerHTML = "Użytkownik o loginie " + resultJson.user.login + " ma już dodany wynik do meczu nr " + resultJson.match.matchNr;
                    i += 1;
                }
                // = resultJson.match.matchPlayers;
            }
    });    
  /*let xhr = new XMLHttpRequest();
  xhr.open ("GET", "/home/addResults/MatchAddedForUser", true);
  xhr.send();
  */
}






