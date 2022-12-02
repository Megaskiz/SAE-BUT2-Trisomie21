<?php
if (isset ($_POST['rows']))  {
    $rows = $_POST['rows'];
}
else{
    $rows = 0;
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <style>
            table, td {
            border-collapse:collapse;
            border:solid black 1px;
            padding: 1rem;
            }
            form {
                max-width:1000px;
                margin:auto;
            }
            label {
                display:block;
                background: rgb(212, 210, 210);
                padding:10px;
            }
        </style>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="" method="post" class="login-form">
            <label>combien de tache voulez vous inserer ? : </label>
            <input type="number" name="rows" value=<?php echo $rows ?>>
            <input id="bouton" type="submit" value="valider">
        </form>
        <form action="insert_systeme_bd.php" method="post">
            <label>Quel est le nom de ce système ?  : </label>
            <input type="text" name="nom">
            <table>
                <tr>
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p>lundi</p>
                    </td>
                    <td>
                        <p>mardi</p>
                    </td>
                    <td>
                        <p>mercredi</p>
                    </td>
                    <td>
                        <p>jeudi</p>
                    </td>
                    <td>
                        <p>vendredi</p>
                    </td>
                    <td>
                        <p>samedi</p>
                    </td>
                    <td>
                        <p>dimanche</p>
                    </td>
                </tr>

                <?php                         
                $i = 1;
                while( $i <= $rows ) {
                    echo"<tr>";
                    echo"<td><input type='text' name='tache$i.0000000'placeholder='tâche à faire'/ required='required'></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"<td></td>";
                    echo"</tr>";
                    $i++;
                }
                ?>
                </table>
            <input class="button" type="submit" value="Valider le système">
        </form>
    </body>
</html>


                