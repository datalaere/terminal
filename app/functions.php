<?php

function paginate($page, $count = 0, $perPage = 5) {
    $page = isset($page) && is_numeric($page) ? intval($page) : 1;

    // Pagination settings
    $page = max(1, $page); // Ensure page is at least 1
    $offset = ($page - 1) * $perPage; // Calculate offset for SQL query

     // Total number
     $total = ceil($count / $perPage);

    return ['page' => $page, 'limit' => $perPage, 'offset' => $offset, 'total' => $total];
}

function isEmail($email) {
    //return filter_var($email, FILTER_VALIDATE_EMAIL);
    return str_contains($email, '@');
}

function request_url($url, $custom_query = 'query') {

    if(!empty($url)) {
        $url_components = parse_url($url);
        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components[$custom_query], $params);

        return $params;
    }

    return false;

}


/**
 * Function to count the number of matched characters in two strings.
 *
 * @param string $str1 The first string to compare.
 * @param string $str2 The second string to compare.
 * @return int The count of matched characters.
 */
function count_match_chars($str1, $str2) {
    $count = 0;
    $charCount = [];

    // Populate the charCount array with the frequency of each character in str2
    for ($i = 0; $i < strlen($str2); $i++) {
        $char = $str2[$i];
        if (isset($charCount[$char])) {
            $charCount[$char]++;
        } else {
            $charCount[$char] = 1;
        }
    }

    // Loop through each character in str1 and count matches
    for ($i = 0; $i < strlen($str1); $i++) {
        $char = $str1[$i];
        if (isset($charCount[$char]) && $charCount[$char] > 0) {
            $count++;
            $charCount[$char]--;
        }
    }

    return $count;
}


/**
 * Function to echo "#" character a specified number of times.
 *
 * @param $count The number of times to echo "#".
 */
function str_char_repeat($count, $char = '# ') {
    return str_repeat($char, $count);
}

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}


function random_username($string, $integer = '') {
    if(empty($integer)) {
       $integer = random_int(1, 99);
    }

    return vsprintf('%s%s%d', [...sscanf(strtolower("$string-"), '%s %2s'), $integer]);
}

function wordlist($file, $word_length = 7, $max_count = 12) {
    $words = file_get_contents($file);
    
    $words = explode(" ", $words);
    $retwords = [];
    $i=0;
    $index=0;
    $wordlen=0;
    $length = $word_length;
    $count =$max_count;
    $failsafe=0;
    
    do {
        $index = rand(0,count($words));
        if(!array_key_exists($index ,$words)) {
            return wordlist($file, $word_length, $max_count);
        }
        $wordlen = strlen($words[$index]);
        if ($wordlen == $length) {
            $retwords[] = strtoupper($words[$index]);
            $i++;
        } else {
            $failsafe++;
        }
        if ($failsafe > 1000) $i = $failsafe;
    } while ($i < $count);
    
    //$retwords = substr($retwords,0,strlen($retwords)-1);
    if(empty($retwords)) {
        return wordlist($file, $word_length, $max_count);
    }
    return $retwords;
}


function dot_replacer($input) {
    // Get the length of the input string
    $length = strlen($input);
    
    // Create a string of dots with the same length as the input string
    $dots = str_repeat('.', $length);
    
    return $dots;
}


// Function to generate a random string of characters
function rand_str($length = 7) {
    $special_chars = "!?,;.'[]={}@#$%^*()-_\/|";
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $special_chars[rand(0, strlen($special_chars) - 1)];
    }
    return $randomString;
}


// Function to generate a memory dump
function mem_dump($rows, $columns, $specialWords = [], $length = 7) {
    $memoryDump = array();

    // Insert special words into the specialPositions array
    $specialPositions = [];
    for ($i = 0; $i < count($specialWords); $i++) {
        $row = rand(0, $rows - 1);
        $col = rand(0, $columns - 1);
        $specialPositions[] = [$row, $col, strtoupper($specialWords[$i])];
    }

    // Generate random strings for each cell
    for ($i = 0; $i < $rows; $i++) {
        $row = array();
        for ($j = 0; $j < $columns; $j++) {
            $cell = rand_str($length);
            // Check if this cell is a special position
            foreach ($specialPositions as $index => $pos) {
                if ($pos[0] === $i && $pos[1] === $j) {
                    // Insert special word and remove it from specialPositions array
                    $cell = $pos[2];
                    unset($specialPositions[$index]);
                    break;
                }
            }
            $row[] = $cell;
        }
        $memoryDump[] = $row;
    }

    return $memoryDump;
}

// Function to format the memory dump with memory paths
function format_dump($memoryDump) {
    $formattedDump = "";
    $rowNumber = 0;

    foreach ($memoryDump as $row) {
        // Generate a random starting memory address for each line
        $memoryAddress = "0x" . dechex(rand(4096, 6553));
        $formattedDump .= $memoryAddress . " ";
        foreach ($row as $cell) {
            $formattedDump .= " " . $cell;
        }
        $formattedDump .= "\n";
    }

    return $formattedDump;
}


function random_ip() {
    return long2ip(rand(0, 4294967295));
}


function bootup() {
    $keyphrases = [' start memory discovery', ' CPUO starting cell relocation', 
                   ' CPUO launch EFIO', ' CPUO starting EFIO'];
    $middle_pieces = [' 1', ' 0', ' 0x0000A4', ' 0x00000000000000000', 
                      ' 0x000014', ' 0x000009', ' 0x000000000000E003D'];
    
    // Start the huge string with '*'
    $huge_string = '*';

    // Loop 70 times, similar to the Python code
    for ($i = 0; $i < 7; $i++) {
        // Randomly choose between 3 to 7 middle pieces
        $num_middle_pieces = rand(3, 7);
        
        // Build the middle piece string
        $middle_piece = '';
        for ($j = 0; $j < $num_middle_pieces; $j++) {
            $middle_piece .= $middle_pieces[array_rand($middle_pieces)];
        }
        
        // Append the middle piece and a random keyphrase to the huge string
        $huge_string .= $middle_piece;
        $huge_string .= $keyphrases[array_rand($keyphrases)];
    }

    return $huge_string;
}

/**
 * -----------------------------------------------------------------------------------------
 * Based on `https://github.com/mecha-cms/mecha-cms/blob/master/system/kernel/converter.php`
 * -----------------------------------------------------------------------------------------
 */

// HTML Minifier
function minify_html($input) {
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
            return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
        }, $input);
    }
    if(strpos($input, '</style>') !== false) {
      $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
        return '<style' . $matches[1] .'>'. minify_css($matches[2]) . '</style>';
      }, $input);
    }
    if(strpos($input, '</script>') !== false) {
      $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
        return '<script' . $matches[1] .'>'. minify_js($matches[2]) . '</script>';
      }, $input);
    }

    return preg_replace(
        array(
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
        ),
        array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
        ),
    $input);
}

// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
function minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);
}

// JavaScript Minifier
function minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
    $input);
}