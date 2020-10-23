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
                    //document.getElementById("ResultForUserExist").innerHTML = "";
                    document.getElementById("beers" + i).value = "";
                    document.getElementById("tokens" + i).value = "";
                    document.getElementById("points" + i).value = "";
                    document.getElementById("beers" + i).value = resultJson.matchResult.matchPlayers[key].beers;
                    document.getElementById("tokens" + i).value = resultJson.matchResult.matchPlayers[key].tokens;
                    document.getElementById("points" + i).value = resultJson.matchResult.matchPlayers[key].points;
                   
                    //document.getElementById("ResultForUserExist").innerHTML = "Użytkownik o loginie " + resultJson.user.login + " ma już dodany wynik do meczu nr " + resultJson.match.matchNr;
                    i += 1;
                }
                let saveBtn = document.querySelector('.savebtn');
                saveBtn.innerHTML = '';
                let flagUpdate = document.getElementById('flagUpdate');
                flagUpdate.value = 1;                
                let updateBtn = document.querySelector('.updatebtn');
                updateBtn.innerHTML = '<input id ="updatebtn" type ="submit" value="Zaktualizuj Dane">';
            }
            else
            {   /* powinno zerować poszczególne inputy, nie wiem jak to zrobić
                let inputs = document.getElementsByClassName('inputValues');
                for (var i =0; i<inputs.length; ++i)
                {
                    let texts = inputs[i].element.getElementById('points2');
                    alert (texts);
                    inputs[i].style.backgroundColor = "red";
                    inputs[i]
                    //inputs[i] = "";
                }*/
                let savebtn = document.querySelector('.savebtn');
                savebtn.innerHTML = '<input id ="savebtn" type ="submit" name ="flagUpdate" value="Zapisz">';
                let updateBtn  = document.querySelector('.updatebtn');
                updateBtn.innerHTML = "";
            }
    });    
  /*let xhr = new XMLHttpRequest();
  xhr.open ("GET", "/home/addResults/MatchAddedForUser", true);
  xhr.send();
  */
}







