<?php
try {
$conn = new mysqli('db403-mysql', 'root', 'P@ssw0rd', 'students_activity');
}
catch (Exception) {
  die('Connection error');
}
?>
