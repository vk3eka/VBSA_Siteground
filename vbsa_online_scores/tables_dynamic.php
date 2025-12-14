<?php 

$url = "http://172.16.10.16/VBSA_Siteground/vbsa_online_scores";

include ("connection.inc");
include ("header.php");

?>
<!-- Bootstrap -->
<link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link href="<?= $url ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="<?= $url ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- NProgress -->
<link href="<?= $url ?>/vendor/nprogress/nprogress.css" rel="stylesheet">
<!-- iCheck -->
<link href="<?= $url ?>/vendor/iCheck/skins/flat/green.css" rel="stylesheet">
<!-- Datatables -->
<link href="<?= $url ?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="<?= $url ?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="<?= $url ?>/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="<?= $url ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="<?= $url ?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- Custom Theme Style -->
<link href="<?= $url ?>/build/css/custom.min.css" rel="stylesheet">

<div class="col-md-12 col-sm-12 ">
  <div class="x_panel">
    <div class="x_title text-center"><h2>Authorised Users</h2>
      <br>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
            <form name=edit method=post action='authorise.php'>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap text-center">
              <thead>
                <tr>
                <th>Players Name</th>
                <th>Access Type</th>
                <th>Password</th>
                <th>Club Administered</th>
                <th>Email Address</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
            <?php
              $sql = "Select * from tbl_authorise, members Where (tbl_authorise.PlayerNo=members.MemberID) Order By PlayerNo";
              $result = $dbcnx_client->query($sql);
              //echo($sql . "<br>");
              $i = 0;
              $num_rows = $result->num_rows;
              if($num_rows > 0)
              {
                while ($build_data = $result->fetch_assoc()) {
                  echo("<tr>");
                  echo("<td id='player_name_" . $i . "' style='text-transform:capitalize'>" . $build_data['Name'] . "</td>");
                  echo("<td id='access_" . $i . "'>" . $build_data['Access'] . "</td>");
                  $password = "********";
                  echo("<td>" . $password . "</td>");
                  $sql = "Select * from tbl_authorise where PlayerNo  = " . $build_data['PlayerNo'];
                  $result_select_club = $dbcnx_client->query($sql);
                  $build_club_data = $result_select_club->fetch_assoc();
                  $club = $build_club_data['Team'];
                  echo("<td id='club_" . $i . "'>" . $club . "</td>");
                  echo("<td id='email_" . $i . "'>" . $build_data['Email'] . "</td>");
                  echo("<td><a class='btn btn-primary btn-xs' onclick='EditButton(" . $build_data['PlayerNo'] . ")'>Edit Record</a></td>");   
                  echo("<td><a class='btn btn-primary btn-xs' onclick='DeleteButton(" . $build_data['PlayerNo'] . ")'>Delete Record</a></td>");   
                  echo("</tr>");
                }
              }
            ?>
              </tbody>
            </table>
            </form>          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
           

        <!-- jQuery -->
  

<!--<script src="<?= $url ?>/vendor/jquery/dist/jquery.min.js"></script>-->
<script src="<?= $url ?>/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?= $url ?>/vendor/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!--<script src="<?= $url ?>/vendor/jszip/dist/jszip.min.js"></script>
<script src="<?= $url ?>/vendor/pdfmake/build/pdfmake.min.js"></script>
<script src="<?= $url ?>/vendor/pdfmake/build/vfs_fonts.js"></script>-->

<!-- Custom Theme Scripts -->
<script src="<?= $url ?>/build/js/custom.min.js"></script>

  </body>

</html>