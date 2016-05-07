<?php
function newGame()
{
    $_SESSION = [];
    $_SESSION["nbLaunch"] = 1;

    $_SESSION["dices"] = [0, 0, 0, 0, 0];
    $_SESSION["dices"] = drawDice($_SESSION["dices"]);
    $_SESSION["previous_dices"] = [];
    $_SESSION["choices"] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $_SESSION["score"] = 0;
    $_SESSION["choosen"] = false;
}

function drawDice($array)
{
    foreach ($array as &$value_tmp)
    {
        $value_tmp = rand(1, 6);
    }

    return $array;
}

function drawReplace($array1, $array2 = null)
{
    if (empty($array2))
    {
        foreach ($array1 as &$value_tmp)
        {
            $value_tmp = rand(1, 6);
        }
    }
    else
    {
        foreach ($array2 as $index)
        {
            // Car les values du form commencent à 1 et non 0...
            $array1[$index - 1] = rand(1, 6);
        }
    }

    return $array1;
}

function sumArray($array1)
{
    $sum = 0;

    foreach ($array1 as $value)
    {
        if (is_int($value) && $value > 0)
        {
            $sum += $value;
        }
    }

    return $sum;
}

function countValuesIn($array1, $needle)
{
    $count = 0;

    foreach ($array1 as $value)
    {
        if ($value === $needle)
        {
            $count++;
        }
    }

    return$count;
}

function checkManyOfAKind($tab, $nb)
{
    // On dédoublonne le tableau
    $arrNeedle = array_unique($tab);

    // On calcul sa taille
    $sizeArr = count($arrNeedle);

    // On prpare un array vide de la bonne taille pour acceuilir les resultats
    $arrCount = [];
    $arrCount = array_fill(0, $sizeArr, NULL);

    // Un petit compteur pour la route
    $i = 0;

    foreach ($arrNeedle as $needle)
    {
        foreach ($tab as $value)
        {
            if ($value === $needle)
            {
                $arrCount[$i]++;
            }
        }
        $i++;
    }

    if (in_array($nb, $arrCount))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function checkFull($tab)
{

    // On dédoublonne le tableau
    $arrNeedle = array_unique($tab);

    // On calcul sa taille
    $sizeArr = count($arrNeedle);

    // On prpare un array vide de la bonne taille pour acceuilir les resultats
    $arrCount = [];
    $arrCount = array_fill(0, $sizeArr, NULL);

    // Un petit compteur pour la route
    $i = 0;


    //Si le tableau comporte plus de 5 entrées
    if (count($tab) !== 5)
    {
        return false;
    }

    // On compte les occurences
    foreach ($arrNeedle as $needle)
    {
        foreach ($tab as $value)
        {
            if ($value === $needle)
            {
                $arrCount[$i]++;
            }
        }
        $i++;
    }

    // On trie le tableau pour faciliter le test
    sort($arrCount);

    // Si il y a plus ou moins de 2 compteurs, false
    if (count($arrCount) !== 2)
    {
        return false;
    }
    else
    {
        // Sinon, si ces compteurs sont bien 2 et 3, bingo
        if ($arrCount[0] === 2 && $arrCount[1])
        {
            return true;
        }
    }
}

function checkStraight($tab, $minLength)
{

    sort($tab);

    $arrSize = count($tab);

    $count = 1;

    for ($i=0; $i < $arrSize - 1; $i++)
    {
        if ($tab[$i] == $tab[$i + 1] - 1)
        {
            $count++;
        }
    }

    if ($count >= $minLength)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function checkChoices($array)
{
    if (countValuesIn($array, 1) > 0)
    {
        $_SESSION["choices"][0] = 1;
    }
    if (countValuesIn($array, 2) > 0)
    {
        $_SESSION["choices"][1] = 1;
    }
    if (countValuesIn($array, 3) > 0)
    {
        $_SESSION["choices"][2] = 1;
    }
    if (countValuesIn($array, 4) > 0)
    {
        $_SESSION["choices"][3] = 1;
    }
    if (countValuesIn($array, 5)  > 0)
    {
        $_SESSION["choices"][4] = 1;
    }
    if (countValuesIn($array, 6) > 0)
    {
        $_SESSION["choices"][5] = 1;
    }

    // Brelan
    if (checkManyOfAKind($array, 3))
    {
        $_SESSION["choices"][6] = 1;
    }

    // Carré
    if (checkManyOfAKind($array, 4))
    {
        $_SESSION["choices"][6] = 1;
        $_SESSION["choices"][10] = 1;
    }

    // Yatzhee
    if (checkManyOfAKind($array, 5))
    {
        $_SESSION["choices"][6] = 1;
        $_SESSION["choices"][10] = 1;
        $_SESSION["choices"][11] = 1;
    }

    // Full
    if (checkFull($array))
    {
        $_SESSION["choices"][9] = 1;
    }

    // Petite suite
    if (checkStraight($array, 4))
    {
        $_SESSION["choices"][7] = 1;
    }

    // Grande suite
    if (checkStraight($array, 5))
    {
        $_SESSION["choices"][8] = 1;
    }
}

function calculateScore($dices, $choice)
{
    $tmp_score = 0;

    switch ($choice)
    {
        case '1':
        $occu1 = countValuesIn($dices, 1);
        $tmp_score= 1 * $occu1;
        break;

        case '2':
        $occu2 = countValuesIn($dices, 2);
        $tmp_score= 2 * $occu2;
        break;

        case '3':
        $occu3 = countValuesIn($dices, 3);
        $tmp_score= 3 * $occu3;
        break;

        case '4':
        $occu4 = countValuesIn($dices, 4);
        $tmp_score= 4 * $occu4;
        break;

        case '5':
        $occu5 = countValuesIn($dices, 5);
        $tmp_score= 5 * $occu5;
        break;

        case '6':
        $occu6 = countValuesIn($dices, 6);
        $tmp_score= 6 * $occu6;
        break;

        case 'brelan':
        for ($i = 0 ; $i <= 6 ; $i++)
        {
            if (countValuesIn($dices, $i) == 3)
            {
                $tmp_score = $i * 3;
            }
        }
        break;

        case 'petite suite':
        $tmp_score = 30;
        break;

        case 'grande suite':
        $tmp_score = 40;
        break;

        case 'full':
        $tmp_score = 25;
        break;

        case 'carre':
        for ($i = 0 ; $i <= 6 ; $i++)
        {
            if (countValuesIn($dices, $i) == 4)
            {
                $tmp_score = $i * 4;
            }
        }
        break;

        case 'yahtzee':
        $tmp_score = 50;
        break;

        case 'chance':
        $tmp_score = sumArray($dices);
        break;
    }

    return $tmp_score;
}
?>