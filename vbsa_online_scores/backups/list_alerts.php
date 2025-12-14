<?php

include('connection.inc');
include('header.php');

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
                <tr>
                    <td colspan=6 align=center><h2>Alert Listing</h2></td>
                </tr>
                <tr>
                    <td align='center' width="60">Email ID</td>
                    <td align='center' width="200">Login Time</td>
                    <td align='center' width="60">IP</td>
                    <td align='center' width="500">Description</td>
                    <td align='center' width="500">Error</td>
                </tr>
                <?php
                $result_rego = $dbcnx_client->query("Select * from tbl_alertlog order by login_date_time DESC");
                while ($next_rego = $result_rego->fetch_assoc()) 
                {
                    echo ("<tr>");
                    echo ("<td align='center'>" . $next_rego['username'] . "</td>");
                    echo ("<td align='center'>" . $next_rego['login_date_time'] . "</td>");
                    echo ("<td align='center'>" . $next_rego['login_ip'] . "</td>");
                    echo ("<td align='center'>" . $next_rego['login_comments'] . "</td>");
                    echo ("<td align='center'>" . $next_rego['error_generated'] . "</td>");
                    echo ("</tr>");
                }
                ?>
            </table>
        </td>
    </tr>
</table>

<?php include('footer.php'); ?>


