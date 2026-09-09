<?php 

$_POST = [
    " Name "  => "              Animesh               ",
    " EMaIL "  => " animesh.gupta@darveys.com ",
    " PhOne  "  => "     7275700865 ",
    " AGE  "    => " 25 "
];

function stringLength($text){
$stringLength = 0;

     while (isset($text[$stringLength])) {
        $stringLength++;
    }
    return $stringLength;
}

function trimString($text){

    $startPosition = 0;

    $endPosition = stringLength($text) -1;

    while ($startPosition < stringLength($text) && $text[$startPosition] == " ") {
        $startPosition++;
    }

    while ($endPosition >= 0 && $text[$endPosition] == " ") {
        $endPosition--;
    }

    // echo "Start from ".$startPosition."<br/>";
    // echo "End from ".$endPosition."<br/>";

    $result = "";

    for($i = $startPosition; $i<=$endPosition;$i++){
        $result = $result.$text[$i];
    }


    return $result;
}

function downcaseString($text){

    $result = "";

    $length = stringLength($text);

    for ($i = 0; $i < $length; $i++) {

    $code = ord($text[$i]);

    // echo $code."<br>";

    if($code>=65 && $code<=90 ){

    $code = $code + 32;

    $result = $result.chr($code);
        
    }elseif ($code >= 97 && $code <= 122) {

        $result = $result . $text[$i];

    }else{

        $result = $result . $text[$i];

    }
    }

    return $result;
}

function stripString($text, $allowedExtra = "@._- ,+"){
    $result = "";
    $length = stringLength($text);

    for ($i = 0; $i < $length; $i++) {

        $char = $text[$i];
        $code = ord($char);

        $isDigit = ($code >= 48 && $code <= 57);

        $isUpper = ($code >= 65 && $code <= 90);

        $isLower = ($code >= 97 && $code <= 122);

        $isExtra = (strpos($allowedExtra, $char) !== false);

        if ($isDigit || $isUpper || $isLower || $isExtra) {
            $result = $result . $char;
        }
    }

    return $result;
}

function stripPhone($text){
    $result = "";
    $length = stringLength($text);

    for ($i = 0; $i < $length; $i++) {

        $char = $text[$i];
        $code = ord($char);

        $isDigit = ($code >= 48 && $code <= 57);
        $isExtra = (strpos("+- ", $char) !== false);

        if ($isDigit || $isExtra) {
            $result = $result . $char;
        }
    }

    return $result;
}

function stripEmail($text){
    $result = "";
    $length = stringLength($text);

    for ($i = 0; $i < $length; $i++) {

        $char = $text[$i];
        $code = ord($char);

        $isDigit = ($code >= 48 && $code <= 57);
        $isUpper = ($code >= 65 && $code <= 90);
        $isLower = ($code >= 97 && $code <= 122);

        $isExtra = (strpos("@._-", $char) !== false);

        if ($isDigit || $isUpper || $isLower || $isExtra) {
            $result = $result . $char;
        }
    }

    return $result;
}

function sanitizeFormData($data){

   $cleanData = [];

    foreach ($data as $key => $value) {

        $trimKey = trimString($key);

        $downcaseKey = downcaseString($trimKey);

        $value = trimString($value);

        if ($downcaseKey == "name") {

            $value = stripString($value, " ");

        } elseif ($downcaseKey == "email") {

            $value = stripEmail($value);

        } elseif ($downcaseKey == "phone") {

            $value = stripPhone($value);

        } elseif ($downcaseKey == "age") {

            $value = stripString($value);

        }

        $cleanData[$downcaseKey] = $value;
    }

    return $cleanData;

}

$cleanPost = sanitizeFormData($_POST);


echo "<pre>";
print_r($cleanPost);
echo "</pre>";