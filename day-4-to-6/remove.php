<?php
/**
 * =========================================================================
 * MANUAL $_POST SANITIZER (beginner friendly, no shortcut functions)
 * =========================================================================
 *
 * Goal: Given a raw $_POST array where the keys might be "Name ", " EMAIL",
 * "UserId" etc. (mixed case, stray spaces), we want to end up with a clean
 * array like:
 *   [
 *     "name"   => "John",
 *     "email"  => "john@example.com",
 *     "userid" => "123",
 *   ]
 *
 * We do this WITHOUT using PHP's built-in trim(), strtolower(),
 * preg_replace(), array_map(), etc. Instead, we walk through each string
 * character by character, so you can see exactly what "trimming",
 * "lowercasing" and "stripping" actually mean under the hood.
 *
 * NOTE: We still use very basic language building blocks that every PHP
 * script needs (loops, strlen(), ord(), chr(), string concatenation).
 * Those are not "sanitization shortcuts" - they're just how you read
 * individual characters in PHP. The functions we are deliberately
 * avoiding are the ones that would do the whole job FOR us in one call.
 * =========================================================================
 */


/**
 * STEP 1: Manual "trim"
 * ----------------------
 * Removes whitespace (space, tab \t, newline \n, carriage return \r)
 * from the START and END of a string only. Whitespace in the middle
 * of the string is left alone.
 */
function my_trim($input)
{
    $length = strlen($input);

    // --- find where the "real" content starts ---
    $start = 0;
    while ($start < $length && is_whitespace_char($input[$start])) {
        $start++;
    }

    // If the whole string was whitespace, return empty string early.
    if ($start === $length) {
        return "";
    }

    // --- find where the "real" content ends ---
    $end = $length - 1;
    while ($end > $start && is_whitespace_char($input[$end])) {
        $end--;
    }

    // --- rebuild the string from $start to $end (inclusive) ---
    $result = "";
    for ($i = $start; $i <= $end; $i++) {
        $result .= $input[$i];
    }

    return $result;
}

/**
 * Helper: checks if a single character counts as whitespace.
 */
function is_whitespace_char($char)
{
    return $char === " "
        || $char === "\t"
        || $char === "\n"
        || $char === "\r"
        || $char === "\0"
        || $char === "\x0B"; // vertical tab
}


/**
 * STEP 2: Manual "lowercase"
 * ----------------------------
 * Converts A-Z to a-z by checking each character's ASCII code.
 * 'A' is 65 and 'Z' is 90 in ASCII. If a character's code falls in
 * that range, we shift it by 32 to get the lowercase version
 * ('a' is 97, so 65 + 32 = 97... it lines up).
 */
function my_lower($input)
{
    $result = "";
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++) {
        $char = $input[$i];
        $code = ord($char); // ord() just reads the character's number code

        if ($code >= 65 && $code <= 90) {
            // It's an uppercase letter, shift it down to lowercase.
            $result .= chr($code + 32); // chr() turns a number back into a character
        } else {
            // Leave numbers, symbols, lowercase letters, etc. untouched.
            $result .= $char;
        }
    }

    return $result;
}


/**
 * STEP 3: Manual "strip unexpected characters"
 * ------------------------------------------------
 * Keeps only characters we consider "safe" for a general form field:
 * letters, digits, spaces, and a small allow-list of punctuation
 * (@ . _ - ,). Everything else (like <, >, ;, quotes, control
 * characters) gets thrown away.
 *
 * Adjust $allowed_extra below if a specific field needs different rules.
 */
function my_strip($input, $allowed_extra = "@._- ,")
{
    $result = "";
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++) {
        $char = $input[$i];
        $code = ord($char);

        $is_digit  = ($code >= 48 && $code <= 57);   // 0-9
        $is_upper  = ($code >= 65 && $code <= 90);   // A-Z
        $is_lower  = ($code >= 97 && $code <= 122);  // a-z
        $is_extra  = (strpos($allowed_extra, $char) !== false);

        if ($is_digit || $is_upper || $is_lower || $is_extra) {
            $result .= $char;
        }
        // else: silently drop the character (this is the "stripping" part)
    }

    return $result;
}


/**
 * STEP 4: Put it all together for one value
 * --------------------------------------------
 * Order matters: trim first (remove edge whitespace), then strip
 * unexpected characters, THEN lowercase if needed. We keep value
 * casing as-is by default (you don't want to lowercase a password!)
 * but we normalize KEYS to lowercase so array access is predictable.
 */
function sanitize_value($value)
{
    $value = my_trim($value);
    $value = my_strip($value);
    return $value;
}

function sanitize_key($key)
{
    $key = my_trim($key);
    $key = my_lower($key);
    $key = my_strip($key, "_"); // keys: letters, digits, underscore only
    return $key;
}


/**
 * STEP 5: Sanitize the whole $_POST array
 * -------------------------------------------
 * Loops over every key/value pair manually (no array_map/array_walk),
 * cleans both the key and the value, and builds a brand new array.
 */
function sanitize_post_array($raw_post)
{
    $clean = array();

    foreach ($raw_post as $raw_key => $raw_value) {

        // Only handle simple string values here. If a value is itself
        // an array (e.g. from checkboxes named field[]), sanitize each
        // item inside it too.
        if (is_array($raw_value)) {
            $clean_sub = array();
            foreach ($raw_value as $sub_value) {
                $clean_sub[] = sanitize_value((string) $sub_value);
            }
            $clean_value = $clean_sub;
        } else {
            $clean_value = sanitize_value((string) $raw_value);
        }

        $clean_key = sanitize_key((string) $raw_key);

        // Skip entries that became empty after cleaning (e.g. a key
        // that was just spaces).
        if ($clean_key === "") {
            continue;
        }

        $clean[$clean_key] = $clean_value;
    }

    return $clean;
}


/**
 * =========================================================================
 * EXAMPLE USAGE
 * =========================================================================
 */

// Simulate a messy raw $_POST array for demonstration.
$_POST = array(
    "  Name "   => "  John<Doe>  ",
    "EMAIL"     => " John@Example.com ",
    "UserId "   => "123;DROP TABLE",
    " Tags[] "  => array(" php ", "WEB Dev!!"),
);

$clean_post = sanitize_post_array($_POST);

echo "Cleaned array:\n";
foreach ($clean_post as $key => $value) {
    if (is_array($value)) {
        echo "$key => [" . implode(", ", $value) . "]\n";
    } else {
        echo "$key => $value\n";
    }
}

/**
 * -------------------------------------------------------------------
 * Once you have $clean_post, run YOUR validation rules on it, e.g.:
 *
 *   if ($clean_post["email"] === "" || strpos($clean_post["email"], "@") === false) {
 *       // reject: invalid email
 *   }
 *
 * Sanitizing and validating are two separate jobs on purpose:
 * sanitizing makes the data safe/consistent to LOOK at,
 * validating decides whether the data is actually ACCEPTABLE.
 * -------------------------------------------------------------------
 */