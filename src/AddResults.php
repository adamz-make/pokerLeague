
<?php

function __autoload($class)
{
    include_once($class.".php");
}

?>
<html>
    <head>
     <meta charset="UTF-8">
     <link rel="stylesheet" href="newCascadeStyleSheet.css">   
    </head>
    <body>
        
        <?php
        session_start();
        if ($_SESSION['loggedIn']==false)
        {

            header ('Location: LogIn.php');
        }
        $_SESSION["nrMatch"] = Db::getNrMatch()[0][0] +1;
        ?>
        <div class ="header">
            <nav>
                <ul>
                    <li><a href ="AddResults.php">Dodaj Wyniki</a></li>
                    <li><a href ="Ranking.php">Ranking</a></li>
                    <li><a href ="">Nagrody</a></li>
                    <li><a href ="Logout.php">Wyloguj się</a></li>
                </ul>
            </nav>
        </div>
        
        <form action ="AddResultsClass.php" method ="GET">
               <?php
               
               echo "<p>Dodaj Wyniki do kolejki<input type ='text' name ='nrMatch' value ='".$_SESSION['nrMatch']."'></p>";
                $users = Db::getAllUsers();
                echo "<select name ='user'>";
                foreach ($users as $user)
                {
                    
                   /*echo "<p>Gracz<input type='text' name ='user".$i."' value =".$user[0]."></p>";
                   echo"<p>Punkty<input  type='text' name ='points".$i."'></p>
                    <p>Piwa<input  type='text' name ='beers".$i."'></p>
                    <p>Żetony<input type ='text' name ='tokens".$i."'></p>";*/
                    echo "<p>Gracz<option value ='$user[0]'>$user[0]</option></p>";
                }
                  
                ?>   
                </select>
              <p>Punkty<input  type="text" name ="points"></p>
            <p>Piwa<input  type="text" name ="beers" ></p>
            <p>Żetony<input type ="text" name ="tokens"></p></p>
            <p><input type ="submit" value="Zapisz">
            
        </form>
        <?php
        
         ?>
    </body>
</html>
