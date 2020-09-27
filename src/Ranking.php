<?php
function __autoload($class)
{
    include_once($class.".php");
}
?>

 <?php
        session_start();
         if ($_SESSION['loggedIn']==false)
        {
            //echo "siema";
            header ('Location: LogIn.php');
        }
        ?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="newCascadeStyleSheet.css">
    <body>
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
        
        <table>
            <?php
            $users = Db::getAllUsers();
            $i = 1;
            foreach ($users as $user)
            {
                $points = DB::getAttributeForUserLogin($user[0],"liczbaPunktow");
                $beers = DB::getAttributeForUserLogin($user[0],"liczbaPiw");
                $tokens = DB::getAttributeForUserLogin($user[0],"liczbaZetonow");
                echo "<tr>
                <td>".$i.".</td><td>".$user[0]."</td><td>".$points[0][0].". pkt</td><td>".$beers[0][0]."piw/".$tokens[0][0]."zetonow</td>           
                </tr>";
                $i+=1;
            }
          
            ?>
        </table>
        <?php
         

        ?>
    </body>
    </head>
</html>

<?php

?>





