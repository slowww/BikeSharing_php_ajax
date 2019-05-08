<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dario
 * Date: 03/05/2019
 * Time: 22:51
 */

$config = json_decode(file_get_contents('./config.json'));

// connection
$rifconn = new mysqli($config->server, $config->user, $config->pw, $config->db);
if ($rifconn->connect_errno) {
    // error 1049 = database not found
    if ($rifconn->connect_errno != 1049) {
        print_r($rifconn->connect_error);
        die();
    }

}
return $rifconn;
?>