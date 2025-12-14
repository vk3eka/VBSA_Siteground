<div class="modal fade" id="scores_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <br>
        <center>
        <table class='table table-striped table-bordered'>
          <tr>
            <td align='center'>
              <table class='table table-striped table-bordered'>
              <tr>
                <td colspan='<?= $colspan ?>' align='center'>
                  <b><div id="playername_1"></div></b>
                </td>
              </tr>
              <tr>
                <?php
                for($i = 0; $i < $best_of; $i++)
                {
                  echo("<td align='center'>Frame " . ($i+1) . "</td>");
                }
                ?>
                <td align='center'>Best Of <?= $best_of ?></td>
              </tr>
            </table>
            
            <table class='table table-striped table-bordered'>
              <tr>
                <td colspan='<?= $colspan ?>' align='center'>
                  <b><div id="playername_2"></div></b>
                </td>
              </tr>
              <tr>
                <?php
                for($i = 0; $i < $best_of; $i++)
                {
                  echo("<td align='center'>Frame " . ($i+1) . "</td>");
                }
                ?>
                <td align='center'>Best Of <?= $best_of ?></td>
              </tr>
            </table>
            <!--<div id= 'scorestable_2'></div>-->
              </td>
            </tr>
          </td>
          </table>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>