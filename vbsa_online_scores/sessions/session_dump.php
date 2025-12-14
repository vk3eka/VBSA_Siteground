<?php
if (!isset($_SESSION)) {
  session_start();
}
echo("<pre>");
echo(var_dump($_SESSION) . "<br>");
echo("</pre>");
?>
