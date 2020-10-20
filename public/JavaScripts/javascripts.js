function myFunction(){
 /*('#user').on('change',function myFunction(){*/
    document.getElementById("test").innerHTML ="siema";
    /*var value = $(this).val();*/
};

function userHasAddedMatch(i = null)
{
    /*document.getElementById("test").innerHTML ="siema";*/
    //let user = document.getElementById("user" + i);
    let matchNr = document.getElementById("nrMatch");
   $.ajax({
        url: "/home/addResults/MatchAddedForUser",
        type: "GET",
	data: "matchNr=" + matchNr.value
    }).done(function(result) {
        
        
        document.getElementById("ResultForUserExist" + i).innerHTML = "";
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






