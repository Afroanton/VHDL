<?php

if (isset($_POST['signup-submit'])) //kollar om användaren kom hit genom att klicka på submit i skapa användare sidan
{

    require 'dbh.inc.php';

    $användarnamn  = $_POST['skapa_användarnamn'];
    $lösenord1 = $_POST['skapa_lösenord'];
    $lösenord2 = $_POST['skapa_lösenord2'];

    if(empty($användarnamn)||empty($lösenord1)||empty($lösenord2) )
    {
        header("Location: ../skapa_användare.php?error=tomt-fält&skapa_användarnamn=".$användarnamn );
        exit();
    }
    else if($lösenord1 !== $lösenord2)
    {
        header("Location: ../skapa_användare.php?error=lösenord-matchar-ej&skapa_användarnamn=".$användarnamn );
        exit();
    }
    else 
    {
        $sql = "SELECT användarnamn FROM användare WHERE användarnamn = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql))
        {
            header("Location: ../skapa_användare.php?error=sqlerror&skapa_användarnamn=".$användarnamn );
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "s", $användarnamn);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultcheck = mysqli_stmt_num_row($stmt);
            if ($resultcheck > 0)
            {
                header("Location: ../skapa_användare.php?error=Användarnamn_upptaget");
                exit();
            }
            else 
            {
                $sql = "INSERT INTO användare (användernamn,lösenord) VALUES (?,?)" ;
                $stmt = mysqli_stmt_init($conn);
            
                if (!mysqli_stmt_prepare($stmt, $sql))
                {
                    header("Location: ../skapa_användare.php?error=sqlerror");
                    exit();
                }  
                else 
                {
                    mysqli_stmt_bind_param($stmt, "ss", $användarnamn,$lösenord);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../skapa_användare.php?skapa användare=lyckad");
                    exit();
                }     
            }
     
        }

    }
    mysqli_stmt_close($stmt)
    mysqli_close($conn)
}
else 
{
    header("Location: ../skapa_användare.php");
    exit();
}
