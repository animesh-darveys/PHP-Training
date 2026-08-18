<?php

// 1. strlen() — String ki length

$name = "Animesh";

echo "Animesh Length: " . strlen($name);
echo "<br />";
$password = "abc123";

if (strlen($password) < 8) {
    echo "Password must be at least 8 characters";
}

echo "<br />";

if (strlen($password) > 12) {
    echo "Password must be no more than 12 characters";
}

echo "<br />";

// 2. strtoupper() — Uppercase

$name = "Animesh";

echo strtoupper($name);

echo "<br />";

$status = "pending";

echo strtoupper($status);


echo "<br />";


// 3. strtolower() — Lowercase

$email = "   ANIMESH@EXAMPLE.COM                     ";

echo strtolower($email);

$email = strtolower(trim($email));

echo "<br />";
echo $email;

echo "<br />";


// 4. ucfirst() — First character uppercase

$name = "animesh";

echo ucfirst($name);

echo "<br />";

// 5. ucwords() — Har word ka first character uppercase

$name = "animesh gupta";

echo ucwords($name);

echo "<br />";

$city = "new delhi";

echo ucwords($city);


echo "<br />";

// 6. trim() — Extra spaces remove

$name = "   Animesh   ";

echo trim($name);

echo "<br />";


// 7. str_replace() — Text replace

$message = "Hello Rahul";

echo str_replace("Rahul", "Animesh", $message);

echo "<br />";

$url = "https://example.com/product/iphone";

$url = str_replace("https://", "", $url);

echo $url;

echo "<br />";

// 8. strpos() — String mein position find

$message = "Hello Animesh";

echo strpos($message, "Animesh");

echo "<br />";

$email = "animesh@example.com";

if (strpos($email, "@") !== false) {
    echo "Email contains @";
}

echo "<br />";

// 9. substr() — String ka portion extract

$name = "Animesh";

echo substr($name, 0, 3);

echo "<br />";

$orderId = "ORD123456789";

echo substr($orderId, 0, 6);

echo "<br />";

// 10. explode() — String → Array
$skills = "PHP,Laravel,MySQL";

$result = explode(",", $skills);

print_r($result);

echo "<br />";
echo "<br />";

// 11. implode() — Array → String

$tags = ["PHP", "Laravel", "Backend"];

$tagsString = implode(", ", $tags);

echo $tagsString;

echo "<br />";

// String Functions Summary
// strlen()       → length count
// strtoupper()   → UPPERCASE
// strtolower()   → lowercase
// ucfirst()      → First character uppercase
// ucwords()      → Every Word First Character Uppercase
// trim()         → start/end spaces remove
// str_replace()  → text replace
// strpos()       → position find
// substr()       → portion extract
// explode()      → String → Array
// implode()      → Array → String