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
        let resultJson = JSON.parse(result);
        if (resultJson.match != null && resultJson.user != null && reusltJson.result != null)
        {
            document.getElementsByName("beers").innerHTML = resultJson.result.beers;
            document.getElement(ResultForUserExist).innerHTML = "Użytkownik o loginie " + resultJson.user.login + " ma już dodany wynik do meczu nr" + resultJson.match.matchNr;
        }
       
    });
    
    
  /*let xhr = new XMLHttpRequest();
  xhr.open ("GET", "/home/addResults/MatchAddedForUser", true);
  xhr.send();
  */
}






