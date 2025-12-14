<?php

include ("connection.inc");
include ("header.php");

$fullname = "Peter Johnson";
?>
<center>
<table class='table dt-responsive nowrap display'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <tr>
                    <td colspan=3 align=center><b>Player to Player Matchups</b></td>
                </tr>
                <tr> 
                    <td align=center>Yarraville</td>
                    <td align='center'>V</td>
                    <td align=center>Yarraville Geelong</td>
                </tr>
                <tr>
                    <td align='center' valign='top' style='width:250px'><?php echo $fullname; ?></td>
                    <td align='center' valign='top'>&nbsp;</td>
                    <td align='center' valign='top' style='width:250px'><?php echo $fullname; ?></td>
                </tr>
                <tr> 
                    <td colspan=3 align=center>&nbsp;</td>
                </tr>
                 <tr> 
                    <td colspan=3 align=center><a class='btn btn-primary btn-xs' href="javascript:;" >Close</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</center>
<?php

include("footer.php"); 

?>
