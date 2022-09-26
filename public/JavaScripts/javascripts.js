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
                let firstUser = true;
                for (var matchPlayerNumber in resultJson.matchResult.matchPlayers)
                {
                    let user = resultJson.matchResult.matchPlayers[matchPlayerNumber].user.login;
                    let beers = resultJson.matchResult.matchPlayers[matchPlayerNumber].beers;
                    let tokens = resultJson.matchResult.matchPlayers[matchPlayerNumber].tokens;
                    let points = resultJson.matchResult.matchPlayers[matchPlayerNumber].points; 
                    addInputWithData (user, beers, tokens, points, firstUser);
                    firstUser = false;
                }
                let saveBtn = document.querySelector('.savebtn');
                saveBtn.innerHTML = '<input id ="savebtn" type ="submit" value="Zaktualizuj Dane">';             
            }
            else
            {   
                
                let optionValue =document.getElementById('usersHtml').innerHTML;
                alert (optionValue);
                optionValue = optionValue.replace('<option value="' + user + '"','<option selected="" value="' + user + '"');
                
                
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

function addInputWithData(user, beers, tokens, points, firstUser)
{
    let optionValue =document.getElementById('usersHtml').innerHTML;
    optionValue = optionValue.replace('<option value="' + user + '"','<option selected="" value="' + user + '"'); 
    
    let string ='<div id="usersHtml">' + optionValue + '</div><div class ="inputResult">' +
                    '<div class ="inputTitle"><div class ="inputTitles">Żetony</div></div>' +
                            '<div class ="inputValue">' +'\
                    <div class = "inputValues"><input type ="text" id="tokens" name ="tokens[]" value = "' + tokens +'"></div></div>';
    if (firstUser === true)
    {
       document.getElementById('user').innerHTML = string;
    }
    else
    {
        document.getElementById('user').innerHTML += string;
    } 
}

$(document).ready(function() {
    /*prompt('halo','alo');
    ("#dialog_komunikat2").dialog({
        autoOpen: false,
        title: 'Basic Dialog'
    });*/
    $('.btnTypeMatch').on("click", function () {
        var type = document.getElementById('matchType').getAttribute('value');
        if (type.replace('Checked','') === 'meczLigowy') {
            document.getElementById('TokensToBeersConversionText').innerHTML = '';
            document.getElementById('TokensToBeersConversionValue').innerHTML = '';
            if(document.getElementById('TokensToBeersConversionVisible') != null){
                document.getElementById('TokensToBeersConversionVisible').setAttribute('id','TokensToBeersConversion');
            }

 //           document.getElementById('TokensToBeersConversion').innerHTML = '<div class="TokensToBeersConversion" style = "display:none">';
        } else {
            if(document.getElementById('TokensToBeersConversion') != null){
                document.getElementById('TokensToBeersConversion').setAttribute('id','TokensToBeersConversionVisible');
            }
            document.getElementById('TokensToBeersConversionText').innerHTML = 'Podaj przelicznik żetonów za jedno piwo';
            document.getElementById('TokensToBeersConversionValue').innerHTML = '<input type="text" id="convertPointsToBeer" name="countTokensToBeers">';
        }
    })

$('.addNextUser').on("click", function(){
    let optionValue =document.getElementById('usersHtml').innerHTML;
    window.alert(optionValue);
    let string ='<div>' + optionValue + '</div><div class ="inputResult">' +
                    /*'<div class ="inputTitle"><div class ="inputTitles">Żetony</div></div>' +
                            '<div class ="inputValue"><div class = "inputValues"><div class = "inputValues"><input type ="text" id="tokens" name ="tokens[]"></div> </div>*/'</div>';
    document.getElementById('user').innerHTML += string;
})
$('.showResults').on("click", function(){
    ('#dialog_komunikat2').dialog('open');
});
});








