<?php include('header.php'); ?>

<!--Content--> 
<div class="">
  <div class="page-title"></div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="x_content"> 
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
      <tr>
       <td align="center"><h1>Fixtures Played</h1></td>
      </tr>
      <tr> 
       <td >&nbsp;</td>
      </tr>      
      </tr>
      </table>
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <thead>
        <tr>
        <th class='text-center'>Date</th>
        <th class='text-center'>Home Team</th>
        <th class='text-center'>Away Team</th>
        <th class='text-center'>Approved</th>
        <th class='text-center'>Action</th>
        </tr>
        </thead>
        <tbody>
          <tr>
          <td class='text-center'>&nbsp;</td>
          <td align='center'>&nbsp;</a>
          <td align='center'>&nbsp;</a>
          <td class='text-center'><input type='checkbox'  name='active' id='active' ></td>
          <td align='center'><a class='btn btn-primary btn-xs'  href='<?= $url ?>/scoresheet.php'>Select Fixture</a></td>
          </tr>
        </tbody>
        </table>
        <br>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <tr>
         <td align="center"><h1>Fixtures to Play</h1></td>
        </tr>
        <tr> 
         <td >&nbsp;</td>
        </tr>      
        </tr>
        </table>
        <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
          <thead>
          <tr>
          <th class='text-center'>Date</th>
          <th class='text-center'>Home Team</th>
          <th class='text-center'>Away Team</th>
          <th class='text-center'>Approved</th>
          <th class='text-center'>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr>
            <td class='text-center'>&nbsp;</td>
            <td align='center'>&nbsp;</a>
            <td align='center'>&nbsp;</a>
            <td class='text-center'><input type='checkbox'  name='active' id='active' ></td>
            <td align='center'><a class='btn btn-primary btn-xs'  href='<?= $url ?>/scoresheet.php'>Select Fixture</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>
