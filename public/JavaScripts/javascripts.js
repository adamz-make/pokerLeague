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
        if (resultJson.match != null && resultJson.user != null)
        {
            console.log(resultJson);
            console.log(resultJson.match.matchNr);
            alert('Użytkownik ma już zapisany wynik w podanym meczu');
        }
       
    });
    
    
  /*let xhr = new XMLHttpRequest();
  xhr.open ("GET", "/home/addResults/MatchAddedForUser", true);
  xhr.send();
  */
}






