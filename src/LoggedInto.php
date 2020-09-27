

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
            //echo "siema";
            header ('Location: LogIn.php');
        }
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
        
    </body>
 
</html>


