<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>
<body>
    <div class="container"> 

    <!-- Include header -->
<div class="visible-md visible-lg" style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">Victorian Billiards & Snooker Association Inc.</h1> 
  <h4 style="color:#900; text-align: center; padding-bottom: 10px;">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h4> 
</div>

<div class="visible-sm visible-xs"style="margin-left:5%; margin-right:5%">
  <h1 style="text-align: center; padding-bottom: 10px">VBSA</h1> 
  <h6 style="text-align: center; padding-bottom: 10px">(Victorian Billiards & Snooker Association Inc.)</h6>  
  <h5 style="color:#900; text-align: center; padding-bottom: 10px">Representing & Developing Billiards &amp; Snooker as a sport in Victoria</h5> 
</div>
<?php

// https://stackoverflow.com/questions/38575817/set-tabindex-in-vertical-order-of-columns

//include ("header.php");

$fullname = "Peter Johnson";
?>
<script type="text/javascript">
    
window.onload = function() 
{
  fixVerticalTabindex('.reset-tabindex');
}


function fixVerticalTabindex(selector) {
  if (typeof selector == 'undefined') {
    selector = '.reset-tabindex';
  }
  var tabindex = 1;
  $(selector).each(function(i, tbl) {
    $(tbl).find('tr').first().find('td').each(function(clmn, el) {
      $(tbl).find('tr td:nth-child(' + (clmn + 1) + ') input').each(function(j, input) {
        //$(input).attr('placeholder', tabindex);
        $(input).attr('tabindex', tabindex++);
      });
    });
  });
}

$(function() {
  $('#btn-fix').click(function() {
    fixVerticalTabindex('.reset-tabindex');
  });
});

</script>
<style type="text/css">
    
    table {
  border: 1px solid red;
}

input {
  border: 1px solid black;
  width: 50px;
  height: 25px;

  text-align: center;
}

</style>
<center>
<table class='table dt-responsive nowrap display'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display reset-tabindex'>
                <tr>
                    <td  align=center><b><input type='text' value='3'> 1</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text'> 10</b></td>
                    <td  align=center><b><input type='text'> 1</b></td>
                    <td  align=center><b><input type='text'> 2</b></td>
                    <td  align=center><b><input type='text'> 3</b></td>
                    <td  align=center><b><input type='text'> 4</b></td>
                    <td  align=center><b><input type='text'> 5</b></td>
                    <td  align=center><b><input type='text'> 6</b></td>
                    <td  align=center><b><input type='text'> 7</b></td>
                    <td  align=center><b><input type='text'> 8</b></td>
                    <td  align=center><b><input type='text'> 9</b></td>
                    <td  align=center><b><input type='text'> 10</b></td>
                </tr>
                <tr> 
                    <td  colspan = 10 align=center><button id="btn-fix">Click to fix vertical tabindex</button></td>
                    
                </tr>
            </table>
        </td>
    </tr>
</table>
</center>
<?php

include("footer.php"); 

?>
</body>
</html>
