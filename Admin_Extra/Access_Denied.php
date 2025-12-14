
<?php require_once('../Connections/connvbsa.php'); ?><?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    //$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$currentPage = $_SERVER["PHP_SELF"];

$queryString_MembHistory = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MembHistory") == false && 
        stristr($param, "totalRows_MembHistory") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MembHistory = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MembHistory = sprintf("&totalRows_MembHistory=%d%s", $totalRows_MembHistory, $queryString_MembHistory);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Extra Administation Area</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<td>
  <table width="869" align="center" cellpadding="5" cellspacing="5" class="page">
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><span class="extra_text">Sorry, you do not have access to this page. Please contact the <a href="mailto:scores@vbsa.org.au">VBSA Webmaster </a> for information regarding access</span></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><span class="extra_text"><a href="javascript:history.go(-1)">Return to previous page</a></span></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><span class="extra_text">OR your session has timed out</span></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><span class="extra_text"><a href="vbsa_extra_login.php">Login</a></span></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
<td>
	
	
	<p>&nbsp;</p>
	<p>&nbsp;</p>	<p>&nbsp;</p></td>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
</table>
<center>
  
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
</td>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
</table>
<center>
  
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
</body>
</html>
