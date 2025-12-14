<?php
require_once('../Connections/connvbsa.php'); 
mysql_select_db($database_connvbsa, $connvbsa);
error_reporting(0);
include '../vbsa_online_scores/header_vbsa.php';
?>
</div>
<div id="Wrapper">
<div class="row"> 
<div class="Page_heading_container">
    <div class="page_title"><?php echo date("Y"); ?> Tournaments</div>
</div>  	
<div style="clear:both"><hr style="width: 90%; color: #999; height: 1px; background-color: #999; " /> </div>
</div>
<?php 
include '../includes/prev_page.php';

include '../Admin_Tournaments/tournament_draw_include.php';
?>
</div>
</body>
</html>

