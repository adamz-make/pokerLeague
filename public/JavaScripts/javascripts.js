function myFunction(){
 /*('#user').on('change',function myFunction(){*/
    document.getElementById("test").innerHTML ="siema";
    /*var value = $(this).val();*/
};

function userHasAddedMatch()
{
    /*document.getElementById("test").innerHTML ="siema";*/
    let user = document.getElementById("user");
    let matchNr = document.getElementById("nrMatch");
   $.ajax({
        url: "/home/addResults/MatchAddedForUser",
        type: "GET",
	data: "user=" + user.options[user.selectedIndex].value + "&matchNr=" + matchNr.value
    }).done(function(result) {

        document.getElementById("ResultForUserExist").innerHTML = "";
        document.getElementById("beers").value = "";
        document.getElementById("tokens").value = "";
        document.getElementById("points").value = "";

        let resultJson = JSON.parse(result);
        if (resultJson.result !== null)
        {
            document.getElementById("beers").value = resultJson.result.beers;
            document.getElementById("tokens").value = resultJson.result.tokens;
            document.getElementById("points").value = resultJson.result.points;
            document.getElementById("ResultForUserExist").innerHTML = "Użytkownik o loginie " + resultJson.user.login + " ma już dodany wynik do meczu nr " + resultJson.match.matchNr;
        }
        

    });
    
    
  /*let xhr = new XMLHttpRequest();
  xhr.open ("GET", "/home/addResults/MatchAddedForUser", true);
  xhr.send();
  */
}






