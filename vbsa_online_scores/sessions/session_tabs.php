<?php
if (!isset($_SESSION)) {
  session_start();
}
include('server_name.php');
include('session_header.php');

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

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<center>
<?php
echo("Session ID " . $_SESSION['session_id'] . "<br>");

if($_SESSION['session_id'])
{
?>
<table class='table dt-responsive nowrap display'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display reset-tabindex'>
                <tr>
                    <td  align=center><b><input type='text' size="10" size="10" value='3'> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
                <tr> 
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                    <td  align=center><b><input type='text' size="10"> 1</b></td>
                    <td  align=center><b><input type='text' size="10"> 2</b></td>
                    <td  align=center><b><input type='text' size="10"> 3</b></td>
                    <td  align=center><b><input type='text' size="10"> 4</b></td>
                    <td  align=center><b><input type='text' size="10"> 5</b></td>
                    <td  align=center><b><input type='text' size="10"> 6</b></td>
                    <td  align=center><b><input type='text' size="10"> 7</b></td>
                    <td  align=center><b><input type='text' size="10"> 8</b></td>
                    <td  align=center><b><input type='text' size="10"> 9</b></td>
                    <td  align=center><b><input type='text' size="10"> 10</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
}
?>
</center>
</body>
</html>

