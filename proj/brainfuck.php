<?php

$inputCode = "";

$inputParametres = "";

$parametresArray = [];

$answer = "";

$flag = [];


//Забираем данные из формы и если они были назначены присваиваем их в переменные для дальнейшей обработки
if (isset($_POST["code"]) && isset($_POST["param"])) {
    $inputCode = $_POST["code"];

    $inputParametres = $_POST["param"];

    for ($i = 0; $i < strlen($inputParametres); $i++) {
        $parametresArray[$i] = ord($inputParametres[$i]);
    }
}


$parametresIndex = 0;

$newParametresArray = [];

$lastIndex = -1;


//При отсутствии параметров заполняем дефолтными значениями
if (empty($parametresArray)) {
    $newParametresArray = array_fill(0, 255, 0);
    $lastIndex = count($newParametresArray) - 1;
}

//Бежим циклом по коду и пошагово выполняем заданные команды
for ($i = 0; $i < strlen($inputCode);) {
    switch ($inputCode{$i}) {
        //Увеличиваем значение с учетов выхода за максимальное значение
        case "+":
            if ($newParametresArray[$lastIndex] == 255) {
                $newParametresArray[$lastIndex] = 0;
            } else {
                $newParametresArray[$lastIndex]++;
            }
            $i++;
            break;
        //Уменьшаем значение с учетом выхода за минимум
        case "-":
            if ($newParametresArray[$lastIndex] == 0) {
                $newParametresArray[$lastIndex] = 255;
            } else {
                $newParametresArray[$lastIndex]--;
            }
            $i++;
            break;
        //Смещаем флажок чтения вправо по параметрам
        case ">":
            if (count($newParametresArray) == $lastIndex + 1) {
                array_push($newParametresArray, 0);
            }
            $lastIndex++;
            $i++;
            break;
        //Смещаем флажок чтения влево по параметрам
        case "<":
            $lastIndex--;
            $i++;
            break;
        //Перепрыгиваем на закрывающую соответствующую скобку при нуле с подсчетом встречающихся скобок
        case "[":
            if ($newParametresArray[$lastIndex] == 0) {
                $endFlag = 1;
                while (true) {
                    $i++;

                    if ($inputCode[$i] == "[") {
                        $endFlag++;
                    }

                    if ($inputCode[$i] == "]") {
                        $endFlag--;
                    }

                    if ($endFlag == 0) {
                        break;
                    }
                }
            } else {
                array_push($flag, $i);
                $i++;
            }
            break;
        //Повторение итерации внутри соответствующей скобки при надобности
        case "]":
            if ($newParametresArray[$lastIndex] != 0) {
                $i = $flag[count($flag) - 1] + 1;
            } else {
                array_pop($flag);
                $i++;
            }
            break;
        //Считываем значение извне
        case ",":
            if ($lastIndex == -1) {
                $lastIndex++;
            }
            $newParametresArray[$lastIndex] = $parametresArray[$parametresIndex];
            $parametresIndex++;
            $i++;
            break;
        //Совершаем вывод символа
        case ".":
            $answer .= chr($newParametresArray[$lastIndex]);
            $i++;
            break;
        //Игнорирование всех неслужебных команд
        default:
            $i++;
            break;
    }
}


echo $answer;