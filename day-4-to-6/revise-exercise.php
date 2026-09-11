<?php

$_POST= [
"     Name " => "AnImEsH GuPTA  ",
"mobile"=> "  ABC+91-7275700865 ",
"age"=> " 19 ",
"email"=> "animesh@gmail.com",
];

class StringUtils{

    function length($text){
        $length = 0;
        while(isset($text[$length])){
            $length++;
        }
        return $length;

    }

    function trimText($text){

        // echo "trimText";
        // echo "Length: " . $this->length($text);
        $length = $this->length($text);
        
        
    $startPosition = 0;

    $endPosition = ($this->length($text) -1);

    while ($startPosition < $length && $text[$startPosition] == " ") {
        $startPosition++;
    }


    while ($endPosition >=0 && $text[$endPosition] == " ") {
        $endPosition--;
    }

    $result = "";

    for($i = $startPosition; $i<=$endPosition; $i++){
        $result = $result.$text[$i];
    }

    return $result;

    }

    function downcase($text){
        $length = $this->length($text);
        $result = "";
        for($i = 0; $i < $length; $i++){
            $code = ord($text[$i]);

            if($code >= 65 && $code <= 90){
                $code = $code + 32;
                $result = $result.chr($code);
            }elseif($code >= 97 && $code <= 122){
                $result = $result.$text[$i];
            }else{
                $result = $result.$text[$i];
            }
        }

        return $result;


    }

        function upcase($text){
        $length = $this->length($text);
        $result = "";
        for($i = 0; $i < $length; $i++){
            $code = ord($text[$i]);
            if($code >= 65 && $code <= 90){
                $result = $result.$text[$i];
            }elseif($code >= 97 && $code <= 122){
                $code = $code - 32;
                $result = $result.chr($code);
            }else{
                $result = $result.$text[$i];
            }
        }
        return $result;
    }

    function titleCase($text){
        $text = $this->trimText($text);
        $length = $this->length($text);
        $result = "";
        for($i = 0; $i < $length; $i++){
            if ($i == 0 || $text[$i - 1] == " "){
                $code = ord($text[$i]);
                    if($code >= 65 && $code <= 90){
                    $result = $result.$text[$i];
                }elseif($code >= 97 && $code <= 122){
                    $code = $code - 32;
                    $result = $result.chr($code);
                }else{
                    $result = $result.$text[$i];
                }
            }
            else{
            $code = ord($text[$i]);

             if($code >= 65 && $code <= 90){
                $code = $code + 32;
                $result = $result.chr($code);
            }elseif($code >= 97 && $code <= 122){
                $result = $result.$text[$i];
            }else{
                $result = $result.$text[$i];
            }
            }
        }

        return $result;


    }
    function stripText($text)    {
        $length = $this->length($text);
        $result = "";

        for ($i = 0; $i < $length; $i++) {

            $code = ord($text[$i]);
            // A-Z
            if ($code >= 65 && $code <= 90) {
                $result = $result . $text[$i];
            }
            // a-z
            elseif ($code >= 97 && $code <= 122) {
                $result = $result . $text[$i];
            }
            // 0-9 
            elseif ($code >= 48 && $code <= 57) {
                $result = $result . $text[$i];
            }
            // space
            elseif ($code == 32) {
                $result = $result . $text[$i];
            }
            // @
            elseif ($code == 64) {
                 $result = $result . $text[$i]; 
            } 
            // . 
            elseif ($code == 46) {
                 $result = $result . $text[$i]; 
            } 
            // _ 
            elseif ($code == 95) {
                 $result = $result . $text[$i]; 
            }
            // _
            elseif ($code == 45) {
                 $result = $result . $text[$i]; 
            } // +
            elseif ($code == 43) {
                 $result = $result . $text[$i]; 
            }

        }

        return $result;
    }

    function stripPhone($text){
        $result = "";
        $length = $this->length($text);

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
        $length = $this->length($text);

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

}

$string = new StringUtils();

function sanitizeFormData($data, $string){

//    global $string;

   $cleanData = [];

   foreach($data as $key=>$value){
        
        $key = $string->trimText($key);

        $key = $string->downcase($key);

        if ($key == "name") {
            $value = $string->stripText($value); 
            $value = $string->trimText($value); 
            $value = $string->titleCase($value);
        }elseif ($key == "mobile"){
            $value = $string->stripPhone($value); 
            $value = $string->trimText($value);
        }elseif($key == "email"){
            $value = $string->stripEmail($value); 
            $value = $string->trimText($value);
        }else{
            $value = $string->stripText($value); 
            $value = $string->trimText($value);
        }

        $cleanData[$key] = $value;

   }

   return $cleanData;

}
echo "<pre>";

echo "<h3>Original Array</h3>";
print_r($_POST);

echo "<h3>Sanitize Array</h3>";
print_r(sanitizeFormData($_POST, $string));

echo "<h3>Get Class Methods</h3>";
print_r(get_class_methods("StringUtils"));

echo "</pre>";
