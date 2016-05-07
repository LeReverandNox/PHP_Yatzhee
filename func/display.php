<?php
// var_dump($_POST);
// echo "<br/>";

if (empty($_SESSION) || isset($_POST["reset"]))
{
    newGame();
}

if (isset($_POST["roll"]))
{
    $_SESSION["choices"] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $_SESSION["choosen"] = false;
    $_SESSION["nbLaunch"]++;
    $_SESSION["previous_dices"] = $_SESSION["dices"];

    if (isset($_POST["change"]))
    {
        $_SESSION["dices"] = drawReplace($_SESSION["dices"], $_POST["change"]);
    }
    else
    {
        $_SESSION["dices"] = drawReplace($_SESSION["dices"]);
    }

}

checkChoices($_SESSION["dices"]);
// checkChoices([1, 1, 1, 4, 5]);

if (isset($_POST["categ"]))
{
    $_SESSION["score"] += calculateScore($_SESSION["dices"], $_POST["categ"]);
    $_SESSION["choosen"] = true;
}


// echo "Actuels : ";
// var_dump($_SESSION["dices"]);
// echo "<br/>";

// echo "Previous : ";
// var_dump($_SESSION["previous_dices"]);
// echo "<br/>";
?>