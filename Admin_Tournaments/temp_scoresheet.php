<!--<table class='table table-striped table-bordered'>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'>
      <b><div id="playername_1"></div></b>
      <div id="member_id_1"></div>
      <div id="scores_element_id_1"></div>
      <div hidden id="row_1"></div>
      <div hidden id="column_1"></div>
    </td>
  </tr>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'>Change Player:&nbsp;<input id='tags_1' style='width:200px; height:25px'>
    <div id='autocompleteAppendToMe_1'></div></td>
  </tr>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'><a class='btn btn-default btn-xs' id='newplayer_1'>Save New Player</a></td>
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
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'>Points</td>");
    }
    ?>
    <td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_1' style='width:20px; height:20px'><br><br>
      &nbsp;Forfeit<br><input type='checkbox' id='forfeit_1'><br><br>
      &nbsp;Walkover<br><input type='checkbox' id='walkover_1'></td>
  </tr>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'><input type='text' id='score" . ($i+1) . "_1' style='width:40px; height:20px'></td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'>Breaks 40+</td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'><input type='text' id='brk" . ($i+1) . "_1' style='width:50px; height:20px'></td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      if($i > 0)
      {
        $disabled = 'disabled';
      }
      echo("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" . ($i+1) . "_1' " . $disabled . "></td>");
    }
    ?>
  </tr>
</table>-->

<!--<table class='table table-striped table-bordered'>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'>
      <b><div id="playername_2"></div></b>
      <div id="member_id_2"></div>
      <div hidden id="scores_element_id_2"></div>
      <div hidden id="row_2"></div>
      <div hidden id="column_2"></div>
    </td>
  </tr>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'>Change Player:&nbsp;<input id='tags_2' style='width:200px; height:25px'>
    <div id='autocompleteAppendToMe_2'></div></td>
  </tr>
  <tr>
    <td colspan='<?= $colspan ?>' align='center'><a class='btn btn-default btn-xs' id='newplayer_2'>Save New Player</a></td>
  </tr>
  <tr>
    <?php
    /*for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'>Frame " . ($i+1) . "</td>");
    }*/
    ?>
    <td align='center'>Best Of <?= $best_of ?></td>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'>Points</td>");
    }
    ?>
    <td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_2' style='width:20px; height:20px'><br><br>
      &nbsp;Forfeit<br><input type='checkbox' id='forfeit_2'><br><br>
      &nbsp;Walkover<br><input type='checkbox' id='walkover_2'></td>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'><input type='text' id='score" . ($i+1) . "_2' style='width:40px; height:20px'></td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'>Breaks 40+</td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      echo("<td align='center'><input type='text' id='brk" . ($i+1) . "_2' style='width:50px; height:20px'></td>");
    }
    ?>
  </tr>
  <tr>
    <?php
    for($i = 0; $i < $best_of; $i++)
    {
      if($i > 0)
      {
        $disabled = 'disabled';
      }
      echo("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" . ($i+1) . "_2' " . $disabled . "></td>");
    }
    ?>
  </tr>
</table>-->