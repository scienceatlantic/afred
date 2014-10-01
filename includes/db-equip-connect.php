<?php
/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */
 
$db = new mysqli('localhost', '', '', DB_EQUIP);
if ($db->connect_error) 
{
	die('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}
$db->set_charset("utf8");
?>