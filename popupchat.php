<?php

// Set this to false to avoid logging.
define('DEBUG', false);

// Set this to true for simple CSRF protection, it will compare the values
// of the $_SESSION['popupchat_csrf'] and $_REQUEST['csrf'] variables.
define('CSRF', true);

// Do not store more than MAX_MSGS lines of data in any chatdata file.
define('MAX_MSGS', 20);

// The directory the popupchat.php files are located.
define('BASEDIR', $_SERVER['DOCUMENT_ROOT'].'/popupchat');

// The directory the chat data files should be stored, this can be anywhere
// that PHP has read/write access to, bus should be inaccessible by the 
// web server of course, or protected with a .htaccess file.
define('DIR_CHATDATA',  BASEDIR.'/chatdata');

// Only of DEBUG is enabled.
define('LOGFILENAME',   BASEDIR.'/log.txt');

/*
 * To have some protection against CSRF there should be a token stored
 * in the variable $_SESSION['popupchat_csrf'] on the server and the
 * same token should be used to initialize the chat on the client side.
 * The token will be sent and validated each time a GET or POST is sent.
 * 
 * It's your job to validate that a user "1" can actually initiate a
 * chat with a user "2" and then set the $_SESSION['popupchat_csrf']
 * variable on that user's session as well as serve HTML to that user
 * that includes the same token string.
 */
if (CSRF) {
    session_start();
    if (empty($_SESSION['popupchat_csrf'])) {
        dielog('Basic CSRF protection is enabled, yet no session value set for "popupchat_csrf".');
    }
}

/*
 * Possible JSON reply formats:
 * 
 * {'success':1,'last':TIME,'msgs':[{'u':USERID,'m':MESSAGE,'t':TIME},...]}
 * 
 * {'success':0,'error':'Here is an error message.'}
 * 
 */

/*
 * Possible values to send to popupchat.php:
 * 
 * GET popupchat.php csrf=<STR>&me=<INT>&them=<INT>&last=<TIMESTAMP>
 * Return all chat strings (max. 20) newer than <TIMESTAMP>.
 * 
 * POST popupchat.php csrf=<STR>&me=<INT>&them=<INT>&last=<TIMESTAMP>&msg=<STR>
 * The same as the above GET, but adds a new message to the chat.
 */

// On error send json.success=0 and json.error message to chat client.
popupchat_check_input($_REQUEST['me'], $_REQUEST['them'], $_REQUEST['csrf']);

// Append a posted message, if any.
if (!empty($_POST['msg'])) {
    // If this is a POST then append a message to the user pair's file.
    // FORMAT: USERID|TIMESTAMP|MESSAGE\n
    $f = popupchat_get_chatdata_filename($_REQUEST['me'], $_REQUEST['them']);

    // Make sure the sub directory is there.
    $d = dirname($f);
    if (!file_exists($d)) { mkdir($d, 0777); }
    if (!is_dir($d)) { dielog('This should be a directory! ['.$d.']'); }

    // Clean up user input.
    $msg = str_replace('<', '&lt;', $_POST['msg']);
    $msg = str_replace('>', '&gt;', $msg);
    $msg = trim(preg_replace('/[\0\t\s]+/', ' ', $msg));
    $s = $_REQUEST['me'].'|'.time().'|'.$msg."\n";

    // Write data to file.
    $bytes = file_put_contents($f, $s, FILE_APPEND | LOCK_EX);
}

// Send the latest messages to client.
$msgs = popupchat_lastest_msgs($_REQUEST['me'], $_REQUEST['them'], @$_REQUEST['last']);

// Send them as JSON or HTML.
echo(json_encode(array('success' => 1, 'msgs' => $msgs, 'last' => time())));
exit(0);

// --- HELPER ------------

// Make a string from the two user IDs, lower ID first.
function popupchat_userpair_string($me, $them) {
    $me = (int)$me;
    $them = (int)$them;
    return ($me < $them) ? $me.'_'.$them : $them.'_'.$me;
}

// Find the directory name for two user IDs.
function popupchat_userpair_subdir($me, $them) {
    $me1 = substr((string)$me, 0, 1);
    $them1 = substr((string)$them, 0, 1);
    return (int)$me < (int)$them ? $me1.$them1 : $them1.$me1;
}

// Basic check of user input, return error and die in not pass.
function popupchat_check_input($me, $them, $csrf) {
    if (!preg_match('/^[0-9]{1,11}$/', $me)) {
        dielog('ID wrong for user A.');
    }
    if (!preg_match('/^[0-9]{1,11}$/', $them)) {
        dielog('ID wrong for user B.');
    }
    if (CSRF && $_SESSION['popupchat_me'] != $me) {
        dielog('User A not found!');
    }
    if (CSRF && empty($_SESSION['popupchat_csrf'])) {
        dielog('Required CSRF not set.');
    }
    if (CSRF && $_SESSION['popupchat_csrf'] != $csrf) {
        dielog('CSRF wrong.');
    }
    return true; 
}

// Return the chatdata file name.
function popupchat_get_chatdata_filename($me, $them) {
    $d = DIR_CHATDATA.'/'.popupchat_userpair_subdir($me, $them);

    return $d.'/'.popupchat_userpair_string($me, $them).'.txt';
}

function popupchat_lastest_msgs($me, $them, $last=0, $max=20) {
    // Returns an array of messages: [{'u':USERID,'m':MESSAGE,'t':TIME},...]
    // 
    // $last is a timestamp, only newer messeges will be returned.
    // $max Maximum return the $count newest messges.
    $f = popupchat_get_chatdata_filename($me, $them);
    
    // If the chatdata file does not exist, there are no messages.
    if (!file_exists($f)) { return array(); }

    // Read the complete file.
    $arr = file($f, FILE_SKIP_EMPTY_LINES);

    // If the file is empty, there are no messages.
    if (empty($arr)) { return array(); }

    // Check for array length, should not be more than <MAX_MSGS>.
    if (count($arr) > MAX_MSGS) {
        array_shift($arr);
        $bytes = file_put_contents($f, implode($arr), LOCK_EX);
    }

    // Filter messages with a timestamp older than $last.
    foreach ($arr as $k => $v) {
        $line = explode('|', trim($v), 3);
        //logme('Preparing line: "'.json_encode($line).'".');
        if ($line[1] > $last) {
            $arr[$k] = array('u' => $line[0], 't' => $line[1], 'm' => $line[2]);
            //logme('last=='.$last.' < t=='.$line[1].' --> GOOD! --> '.$line[2]);
        } else {
            unset($arr[$k]);
            //logme('last=='.$last.' > t=='.$line[1].' --> NOPE! --> '.$line[2]);
        }
    }

    $len = count($arr);
    $arr = array_values($len > $max ? array_slice($arr, ($len - $max), $max) : $arr);
    logme('Remaining "'.$len.'" messages for last=="'.$last.'".');
    logme(json_encode($arr));
    return $arr;
}

function logme($msg) {
    if (DEBUG) {
        $t = date('Y-m-d H:i:s');
        file_put_contents(LOGFILENAME, "{$t} {$msg}\n", FILE_APPEND);
    }
}

function dielog($msg) {
    logme($msg);
    echo(json_encode(array('success' => 0, 'error' => DEBUG ? 'DEBUG MODE: '.$msg : 'Invalid request.')));
    exit(0);
}
