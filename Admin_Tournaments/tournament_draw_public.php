<!-- jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>

<?php 
require_once('../Connections/connvbsa.php'); 
//error_reporting(0);

mysql_select_db($database_connvbsa,$connvbsa);

if(isset($_GET['tourn_id']))
{
  $tourn_id = $_GET['tourn_id'];
  $tourn_caption = "(Tournament ID " . $tourn_id . ")";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" /> <!--// added to removew mobile telephopne number format from mobile view -->
<title>Home Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<link rel="icon" type="image/x-icon" href="images/image001.png">
</head>
<body id="home">

<div class="container"> 
    <!-- Include header -->
<?php include '../includes/header.php';?>
    <!-- Include navigation -->
<?php include '../includes/nav_vbsa.php';?>
</div><!--End Bootstrap Container-->

</head>
<body>
<?php 

$playDatesArr = [];

function GetMemberName($memberID)
{
  global $connvbsa;
  $sql = "Select MemberID, FirstName, LastName FROM members WHERE MemberID = " . $memberID;
  $result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
  $build_member = $result_member->fetch_assoc();
  $fullname = ($build_member['FirstName'] . " " . $build_member['LastName']);
  return $fullname;
}

function formatTime($time)
{
  // Format a specific timestamp
  $timestamp = strtotime($time_1);
  $new_time = date("h:i", $timestamp); // Output: 03:30 PM
  return $new_time;
}

echo("<script type='text/javascript'>");
echo("$(document).ready(function() { ");
echo("$.fn.fillelementarray = function () {");

//get player results from tournament_results table
$query_scores = 'Select * FROM vbsa3364_vbsa2.tournament_results where tourn_id = ' . $tourn_id;
$result_scores = mysql_query($query_scores, $connvbsa) or die(mysql_error());

while($build_scores = $result_scores->fetch_assoc())
{
  $id_scores = ($build_scores['row_no'] . '_' . ($build_scores['col_no']+1) . '_' . ($build_scores['col_no']+1));
  $id_name = $build_scores['row_no'] . '_' . ($build_scores['col_no']) . '_' . ($build_scores['col_no']);
  if(($build_scores['forfeit_1'] == 1) || ($build_scores['forfeit_2'] == 1))
  {
    $game_score = 'FF';
  }
  else if(($build_scores['walkover_1'] == 1) || ($build_scores['walkover_2'] == 1))
  {
    $game_score = 'WO';
  }
  else
  {
    $game_score = $build_scores['game_1'];
  }
  echo("$('#" . $id_scores . "').val('" . $game_score . "');\n");
  echo("$('#" . $id_name . "').val('" . GetMemberName($build_scores['memb_id']) . "');\n");
}

//get day/times from tournament_day_time table
$query_times = 'Select * FROM vbsa3364_vbsa2.tournament_day_time where tourn_id = ' . $tourn_id;
$result_times = mysql_query($query_times, $connvbsa) or die(mysql_error());

while($build_times = $result_times->fetch_assoc())
{
  $id_day = ("D_" . $build_times['row_no'] . '_' . $build_times['col_no']);
  $id_time = ("T_" . $build_times['row_no'] . '_' . $build_times['col_no']);
  echo("$('#$id_day').val('{$build_times['day']}');\n");

  $new_time = date("h:i", strtotime($build_times['time']));

  echo("$('#$id_time').val('{$new_time}');\n");
  $key = ("D_" . $build_times['row_no'] . '_' . $build_times['col_no']);
  $playDatesArr[$key] = $build_times['day'];
}

echo("}");
echo("});");

echo("let play_date_arr = '" . json_encode($playDatesArr, JSON_UNESCAPED_UNICODE) . "';");
echo("let play_date = JSON.parse(play_date_arr);");
echo("window.onload = function()"); 
echo("{");
echo("$.fn.fillelementarray();");
echo("}");

echo("</script>");
?>

<form name='tournament_draw_public' id='tournament_draw_public' method="post" action='tournament_draw_public.php?tourn_id=<?= $tourn_id ?>'>
<center>
<br>
<?php

// get tournament name
$query_tourn_name = 'Select *, tournaments.tourn_type as type FROM vbsa3364_vbsa2.tournaments LEFT JOIN calendar ON tournaments.tourn_id = calendar.tourn_id where tournaments.tourn_id = ' . $tourn_id;

$result_tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
$build_tourn_name = $result_tourn_name->fetch_assoc();
$tourn_type = $build_tourn_name['type'];

// get best of values from tournaments table
$sql_best_of = 'Select * from tournaments where tourn_id = ' . $tourn_id;
$result_best_of = mysql_query($sql_best_of, $connvbsa) or die(mysql_error());
$build_best_of = $result_best_of->fetch_assoc(); 

$redirect_url = 'https://vbsa.org.au/Archives/ArchiveIndex.php';
echo("<div hidden align='center' id='tourn_name'>" . $build_tourn_name['tourn_name'] . "</div>");
echo("<div align='center'><h3>" . $build_tourn_name['tourn_name'] . "</h3></div>");
echo("<br>");
echo("<br>");
echo("<div align='center'>");
echo("  <a href='" . $redirect_url . "' ><button type='button' class='btn btn-primary' style='width: 200px;'>Draws & Results</button></a>");
echo("</div>");
echo("<br>");
echo("<div align='center'>");
echo('  <input type="button" value="PDF of the draw."  class="btn btn-primary" style="width: 200px;" onclick="generatePDF()">');
echo("</div>");
echo("<br>");
echo("<br>");

$sort_order = '';
if($build_tourn_name['sort'] == 'rank')
{
  $sort_order = 'rank';
  $sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
  CASE WHEN seed = 1 
  THEN 0 
  ELSE 1 
  END,
  CASE WHEN ranknum IS NULL OR ranknum = 0 
  THEN 1 
  ELSE 0 
  END, 
  CASE WHEN ranknum IS NULL OR ranknum = 0 THEN LastName 
  END ASC,
  CASE WHEN ranknum IS NULL OR ranknum = 0 THEN FirstName 
  END ASC,
  ranknum ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed, ranknum, tourn_type FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id Left Join rank_S_open_tourn on members.MemberID = rank_S_open_tourn.memb_id where tournament_number = '$tourn_id' Order By row_num";
}
else
{
  $sort_order = 'seed';
  $sql_players = "Select ROW_NUMBER() OVER (ORDER BY 
  CASE WHEN seed IS NULL OR seed = 0 
  THEN 1 
  ELSE 0 
  END, 
  seed ASC) AS row_num, tourn_memb_id, FirstName, LastName, ranked, rank_pts, seed, tourn_type FROM tourn_entry Left join members on members.memberID = tourn_entry.tourn_memb_id where tournament_number = '$tourn_id' Order By row_num";
}

$result_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
$build_tourn = $result_players->fetch_assoc();
$tourn_type = $build_tourn['tourn_type'];
$no_of_players = $result_players->num_rows;

if($no_of_players <= 64)
{
  $tourn_size = 64;
  $no_of_rounds = 7;
}
if(($no_of_players > 64) && ($no_of_players <= 128))
{
  $tourn_size = 128;
  $no_of_rounds = 10;
}

function isMobileDevice() 
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    // Common mobile device keywords
    $mobileKeywords = '/(android|iphone|ipod|blackberry|iemobile|opera mobile|palmos|webos|mobile|tablet)/i';
    if (preg_match($mobileKeywords, $userAgent)) 
    {
        return true;
    } 
    else 
    {
        return false;
    }
}

if(isMobileDevice()) 
{
  echo('<table width="300" border="0" align="center">');
  echo('<tr>');
  echo('<td align="left"><button type="button" class="btn btn-primary" id="prevColumn"><< Back</button></td>');
  echo('<td align="right"><button type="button" class="btn btn-primary" id="nextColumn">Next >></button></td>');
  echo('</tr>');
  echo('<tr>');
  echo('<td colspan="2">&nbsp;</td>');
  echo('</tr>');
  echo('</table>');
} 
else 
{
    //echo "You are using a desktop device.";
}

?>

<div id="fixtureTable"></div>
<?php

echo("<table align='center' border='0' cellpadding='0' cellspacing='0' class='table table-striped table-bordered table-responsive' style='width: 50%;''>");
echo('<tr>');
echo('<td colspan="3" align="center"><h4>Breaks</h4></td>');
echo('</tr>');
echo('<tr>');
echo('<td align="center" style="width: 200px;"><b>Name</b></td>');
echo('<td align="center" style="width: 200px;"><b>Breaks</b></td>');
echo('</tr>');

$sql_select = "Select distinct memb_id FROM vbsa3364_vbsa2.tournament_results where (breaks_1 > 0 OR  breaks_2 > 0 OR  breaks_3 > 0 OR  breaks_4 > 0 OR  breaks_5 > 0 OR  breaks_6 > 0 OR  breaks_7 > 0) AND tourn_id = " . $tourn_id;
$result_players_results = mysql_query($sql_select, $connvbsa) or die(mysql_error());
//echo($sql_select . "<br>");
while($build_players = $result_players_results->fetch_assoc())
{
  $highest_break = [];
  $sql_players = "Select * from tournament_results where memb_id = " . $build_players['memb_id'] . " AND tourn_id = " . $tourn_id;;
  $result_count_players = mysql_query($sql_players, $connvbsa) or die(mysql_error());
  $count_players = $result_count_players->num_rows;
  while($build_data = $result_count_players->fetch_assoc())
  {
    $id = 0;
    $highest_break[] = [
      'ID' => $id,
      'Name' => GetMemberName($build_players['memb_id']),
      'Breaks'  => [
          $build_data['breaks_1'],
          $build_data['breaks_2'],
          $build_data['breaks_3'],
          $build_data['breaks_4'],
          $build_data['breaks_5'],
          $build_data['breaks_6'],
          $build_data['breaks_7']
      ]
    ];
    $id++;
  }

  //echo("<pre>");
  //echo(var_dump($highest_break));
  //echo("</pre>");

  $combined = [];

  foreach ($highest_break as $entry) 
  {
    // Handle either associative or numeric indexes
    $id   = $entry['ID']   ?? $entry[0] ?? null;
    $name = $entry['Name'] ?? $entry[1] ?? 'Unknown';
    $breaks = $entry['Breaks'] ?? $entry[2] ?? [];
    if (!isset($combined[$id])) {
      $combined[$id] = [
          'Name'   => ($name),
          'Breaks' => []
      ];
    }
    foreach ($breaks as $b) {
      //$b = trim($b);
      if ($b !== '') {
          $combined[$id]['Breaks'][] = $b;
      }
    }
  }
  //Sort players by highest break
  usort($combined, function($a, $b) {
    $maxA = !empty($a['Breaks']) ? max($a['Breaks']) : 0;
    $maxB = !empty($b['Breaks']) ? max($b['Breaks']) : 0;
    return $maxB <=> $maxA;
  });

  unset($player);
  foreach ($combined as $player) {
    $name = $player['Name'];
    $breaks = $player['Breaks'];
    $breaks_list = implode(', ', $breaks); // join breaks to create a string
    $breaks_list = str_replace(",", " ", $breaks_list); // remove any comma and create array delimited by spaces
    $charArray = explode(" " , $breaks_list); // create an array from a string
    rsort($charArray, SORT_NUMERIC); // sort the array
    $breaks_list = implode(" ", $charArray); // Convert the array back to a string
    echo '<tr>';
    echo '<td>' . $name . '</td>';
    echo '<td>' . $breaks_list . '</td>';
    echo '</tr>';
  }
}
echo("</table>");

?>
</table>

<script>
  tourn_size = <?= $tourn_size ?>;

  function hideEmptyRows(activeGroups) {

    $("#fixtureTable tbody tr").each(function () 
    {
        let row = $(this);
        let hasData = false;

        // Check only the column groups that are currently visible
        activeGroups.forEach(function (group) {
            row.find('td.' + group).each(function () {
                let input = $(this).find('input');

                if (input.length) {
                    if (input.val().trim() !== "") {
                        hasData = true;
                    }
                } else {
                    let text = $(this).html().replace(/&nbsp;/g, '').trim();
                    if (text !== "") {
                        hasData = true;
                    }
                }
            });
        });

        if (hasData) row.show();
        else row.hide();
    });
  }

  function setupColumnNavigation() 
  {
    // --- REMOVE old click handlers (prevents stacking if table reloads)
    $('#nextColumn').off('click');
    $('#prevColumn').off('click');
    // --- Collect unique column class groups (column_5 → column_12)
    let columnGroups = [...new Set(
        $('#fixtureTable [class^="column_"]').map(function () {
            return $(this).attr('class');
        }).get()
    )];
    let showCount = 2;        // we always show 2 groups
    let currentIndex = 0;     // left-most visible group

    // remove day/time row
    let columnDTGroups = [...new Set(
        $('#fixtureTable [class^="DT_column_"]').map(function () {
            return $(this).attr('class');
        }).get()
    )];
    columnDTGroups.forEach(g => $('.' + g).hide());


    // --- Initial update
    // --- Function to show correct groups
    function updateColumns() 
    {
      columnGroups.forEach(g => $('.' + g).hide());

      let g1 = columnGroups[currentIndex];
      let g2 = columnGroups[currentIndex + 1];

      $('.' + g1).show();
      $('.' + g2).show();

      hideEmptyRows([g1, g2]);
    }


    updateColumns();
    // --- NEXT button
    $('#nextColumn').on('click', function () 
    {
        if (currentIndex < columnGroups.length - showCount) 
        {
            currentIndex++;
            updateColumns();
        }
    });
    // --- PREV button
    $('#prevColumn').on('click', function () 
    {
        if (currentIndex > 0) 
        {
            currentIndex--;
            updateColumns();
        }
    });
  }

  function DateSelect(play_date, index)
  {
    let parts = index.split('_');
    let row = parts[1] || '';
    let col = parts[2] || '';
    let play_date_text = play_date[index];
    if((play_date_text === undefined) || (play_date_text === null))
    {
      play_date_text = '';
    }
    html_Date = "<input type='text' id='" + index + "' class='date_select' data-row=" + row + " data-col=" + col + " style='width: 40px;' value=" + play_date_text + "  readonly>";
    return html_Date;
  }

  var match_no = 0;

  function R7(index, tourn_size) 
  {
    html_R7 = "";
    if(tourn_size == 128)
    {
      html_R7 += "<td nowrap align='center' class='column_1'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R7 += "<div id='match_" + index + "_1'></div></td>";
      html_R7 += "<td id='" + index + "_2' align='center' class='column_1'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_2'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R7 += "<div id='match_" + index + "_3'></div></td>";
      html_R7 += "<td id='" + index + "_4' align='center' class='column_2'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_3'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R7 += "<div id='match_" + index + "_5'></div></td>";
      html_R7 += "<td id='" + index + "_6' align='center' class='column_3'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value=''></td>";
      html_R7 += "<td nowrap align='center' class='column_4'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R7 += "<div id='match_" + index + "_7'></div></td>";
      html_R7 += "<td id='" + index + "_8' align='center' class='column_4'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value=''></td>";
    }
    html_R7 += "<td nowrap align='center' class='column_5'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_9'></div></td>";
    html_R7 += "<td id='" + index + "_10' align='center' class='column_5'><input type='text' style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R7 += "<td nowrap align='center' class='column_6'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_11'></div></td>";
    html_R7 += "<td id='" + index + "_12' align='center' class='column_6'><input type='text' style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R7 += "<td nowrap align='center' class='column_7'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R7 += "<div id='match_" + index + "_13'></td>";
    html_R7 += "<td id='" + index + "_14' align='center' class='column_7'><input type='text' style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R7 += "<td align='center' class='column_8'>";
    html_R7 += DateSelect(play_date, ('D_' + index + '_8'));
    html_R7 += "&nbsp;";
    html_R7 += "<input type='text' id='T_" + index + "_8' data-row='" + index + "' data-col='8' value='' readonly size='6' /></td>";
    html_R7 += "<td class='column_8'>&nbsp;</td>";
    html_R7 += "<td class='column_9'>&nbsp;</td>";
    html_R7 += "<td class='column_9'>&nbsp;</td>";
    html_R7 += "<td class='column_10'>&nbsp;</td>";
    html_R7 += "<td class='column_10'>&nbsp;</td>";
    html_R7 += "<td class='column_11'>&nbsp;</td>";
    html_R7 += "<td class='column_11'>&nbsp;</td>";
    html_R7 += "<td class='column_12'>&nbsp;</td>";
    html_R7 += "<td class='test_column'>R7</td>";
    return html_R7;
  }

  function R8(index, tourn_size) 
  {
    html_R8 = "";
    if(tourn_size == 128)
    {
      html_R8 += "<td nowrap class='column_1' align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R8 += "<div id='match_" + index + "_1'></div></td>";
      html_R8 += "<td id='" + index + "_2' class='column_1' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R8 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R8 += "<div id='match_" + index + "_3'></div></td>";
      html_R8 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R8 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R8 += "<div id='match_" + index + "_5'></div></td>";
      html_R8 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text' style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R8 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R8 += "<div id='match_" + index + "_7'></div></td>";
      html_R8 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R8 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value=''></td>";
    html_R8 += "<div id='match_" + index + "_9'></td>";
    html_R8 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R8 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_11'></td>";
    html_R8 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R8 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_13''></td>";
    html_R8 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R8 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R8 += "<div id='match_" + index + "_15'></td>";
    html_R8 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R8 += "<td class='column_9' >&nbsp;</td>";
    html_R8 += "<td class='column_9' >&nbsp;</td>";
    html_R8 += "<td class='column_10' >&nbsp;</td>";
    html_R8 += "<td class='column_10' >&nbsp;</td>";
    html_R8 += "<td class='column_11' >&nbsp;</td>";
    html_R8 += "<td class='column_11' >&nbsp;</td>";
    html_R8 += "<td class='column_12' >&nbsp;</td>";
    html_R8 += "<td class='test_column'>R8</td>";
    return html_R8;
  }

  function R9(index, tourn_size) 
  {
    html_R9 = "";
    if(tourn_size == 128)
    {
      html_R9 += "<td class='column_1' >&nbsp;</td>";
      html_R9 += "<td class='column_1' >&nbsp;</td>";
      html_R9 += "<td class='column_2' >&nbsp;</td>";
      html_R9 += "<td class='column_2' >&nbsp;</td>";
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_3' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
      html_R9 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R9 += "<td class='column_5' >&nbsp;</td>";
    html_R9 += "<td class='column_5' >&nbsp;</td>";
    html_R9 += "<td class='column_6' >&nbsp;</td>";
    html_R9 += "<td class='column_6' >&nbsp;</td>";
    html_R9 += "<td class='column_7' >&nbsp;</td>";
    html_R9 += "<td class='column_7' >&nbsp;</td>";
    html_R9 += "<td class='column_8' >&nbsp;</td>";
    html_R9 += "<td class='column_8' >&nbsp;</td>";
    html_R9 += "<td class='column_9'  nowrap align='center'><input type='text' class='player' id='" + index + "_17_17' value='' readonly></td>";
    html_R9 += "<div id='match_" + index + "_17'></div></td>";
    html_R9 += "<td class='column_9'  id='" + index + "_18' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_18_18' value='' readonly></td>";
    html_R9 += "<td class='column_10' >&nbsp;</td>";
    html_R9 += "<td class='column_10' >&nbsp;</td>";
    html_R9 += "<td class='column_11' >&nbsp;</td>";
    html_R9 += "<td class='column_11' >&nbsp;</td>";
    html_R9 += "<td class='column_12' >&nbsp;</td>";
    html_R9 += "<td class='test_column'>R9</td>";
    return html_R9;
  }

  function R10(index, tourn_size) 
  {
    html_R10 = "";
    if(tourn_size == 128)
    {
      html_R10 += "<td class='column_1' >&nbsp;</td>";
      html_R10 += "<td class='column_1' >&nbsp;</td>";
      html_R10 += "<td class='column_2' >&nbsp;</td>";
      html_R10 += "<td class='column_2' >&nbsp;</td>";
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_3' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
      html_R10 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R10 += "<td class='column_5' >&nbsp;</td>";
    html_R10 += "<td class='column_5' >&nbsp;</td>";
    html_R10 += "<td class='column_6' >&nbsp;</td>";
    html_R10 += "<td class='column_6' >&nbsp;</td>";
    html_R10 += "<td class='column_7' >&nbsp;</td>";
    html_R10 += "<td class='column_7' >&nbsp;</td>";
    html_R10 += "<td class='column_8' >&nbsp;</td>";
    html_R10 += "<td class='column_8' >&nbsp;</td>";
    html_R10 += "<td class='column_9' >&nbsp;</td>";
    html_R10 += "<td class='column_9' >&nbsp;</td>";
    html_R10 += "<td class='column_10'  nowrap align='center'><input type='text' class='player' id='" + index + "_19_19' value='' readonly></td>";
    html_R10 += "<div id='match_" + index + "_19'></div></td>";
    html_R10 += "<td class='column_10'  id='" + index + "_20' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_20_20' value='' readonly></td>";
    html_R10 += "<td class='column_11' >&nbsp;</td>";
    html_R10 += "<td class='column_11' >&nbsp;</td>";
    html_R10 += "<td class='column_12' >&nbsp;</td>";
    html_R10 += "<td class='test_column'>R10</td>";
    return html_R10;
  }

  function R11(index, tourn_size) 
  {
    html_R11 = "";
    if(tourn_size == 128)
    {
      html_R11 += "<td class='column_1' >&nbsp;</td>";
      html_R11 += "<td class='column_1' >&nbsp;</td>";
      html_R11 += "<td class='column_2' >&nbsp;</td>";
      html_R11 += "<td class='column_2' >&nbsp;</td>";
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_3' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
      html_R11 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R11 += "<td class='column_5' >&nbsp;</td>";
    html_R11 += "<td class='column_5' >&nbsp;</td>";
    html_R11 += "<td class='column_6' >&nbsp;</td>";
    html_R11 += "<td class='column_6' >&nbsp;</td>";
    html_R11 += "<td class='column_7' >&nbsp;</td>";
    html_R11 += "<td class='column_7' >&nbsp;</td>";
    html_R11 += "<td class='column_8' >&nbsp;</td>";
    html_R11 += "<td class='column_8' >&nbsp;</td>";
    html_R11 += "<td class='column_9' >&nbsp;</td>";
    html_R11 += "<td class='column_9' >&nbsp;</td>";
    html_R11 += "<td class='column_10' >&nbsp;</td>";
    html_R11 += "<td class='column_10' >&nbsp;</td>";
    html_R11 += "<td class='column_11'  nowrap align='center'><input type='text' class='player' id='" + index + "_21_21' value='' readonly></td>";
    html_R11 += "<div id='match_" + index + "_21'></div></td>";
    html_R11 += "<td class='column_11'  id='" + index + "_22' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_22_22' value='' readonly></td>";
    html_R11 += "<td class='column_12' >&nbsp;</td>";
    html_R11 += "<td class='test_column'>R11</td>";
    return html_R11;
  }

  function R12(index, tourn_size) 
  {
    html_R12 = "";
    if(tourn_size == 128)
    {
      html_R12 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R12 += "<div id='match_" + index + "_1'></div></td>";
      html_R12 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R12 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R12 += "<div id='match_" + index + "_3'></div></td>";
      html_R12 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R12 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R12 += "<div id='match_" + index + "_5'></div></td>";
      html_R12 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R12 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R12 += "<div id='match_" + index + "_7'></div></td>";
      html_R12 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R12 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_9'></div></td>";
    html_R12 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R12 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_11'></div></td>";
    html_R12 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R12 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_13'></div></td>";
    html_R12 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R12 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R12 += "<div id='match_" + index + "_15'></div></td>";
    html_R12 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R12 += "<td class='column_9' >&nbsp;</td>";
    html_R12 += "<td class='column_9' >&nbsp;</td>";
    html_R12 += "<td class='column_10' >&nbsp;</td>";
    html_R12 += "<td class='column_10' >&nbsp;</td>";
    html_R12 += "<td class='column_11' >&nbsp;</td>";
    html_R12 += "<td class='column_11' >&nbsp;</td>";
    html_R12 += "<td class='column_12' >&nbsp;</td>";
    html_R12 += "<td class='test_column'>R12</td>";
    return html_R12;
  }

  function R13(index, tourn_size) 
  {
    html_R13 = "";
    if(tourn_size == 128)
    {
      html_R13 += "<td class='column_1' >&nbsp;</td>";
      html_R13 += "<td class='column_1' >&nbsp;</td>";
      html_R13 += "<td class='column_2' >&nbsp;</td>";
      html_R13 += "<td class='column_2' >&nbsp;</td>";
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_3' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
      html_R13 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R13 += "<td class='column_5' >&nbsp;</td>";
    html_R13 += "<td class='column_5' >&nbsp;</td>";
    html_R13 += "<td class='column_6' >&nbsp;</td>";
    html_R13 += "<td class='column_6' >&nbsp;</td>";
    html_R13 += "<td class='column_7' >&nbsp;</td>";
    html_R13 += "<td class='column_7' >&nbsp;</td>";
    html_R13 += "<td class='column_8' >&nbsp;</td>";
    html_R13 += "<td class='column_8' >&nbsp;</td>";
    html_R13 += "<td class='column_9'  align='center'>";
    html_R13 += DateSelect(play_date, ("D_" + index + '_9'));
    html_R13 += "&nbsp;";
    html_R13 += "<input type='text' id='T_" + index + "_9' data-row='" + index + "' data-col='9' value='' readonly size='6' /></td>";
    html_R13 += "<td class='column_9' >&nbsp;</td>";
    html_R13 += "<td class='column_10' >&nbsp;</td>";
    html_R13 += "<td class='column_10' >&nbsp;</td>";
    html_R13 += "<td class='column_11' >&nbsp;</td>";
    html_R13 += "<td class='column_11' >&nbsp;</td>";
    html_R13 += "<td class='column_12' >&nbsp;</td>";
    html_R13 += "<td class='test_column'>R13</td>";
    return html_R13;
  }

  function R14(index, tourn_size) 
  {
    html_R14 = "";
    if(tourn_size == 128)
    {
      html_R14 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R14 += "<div id='match_" + index + "_1'></div></td>";
      html_R14 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R14 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R14 += "<div id='match_" + index + "_3'></div></td>";
      html_R14 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R14 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R14 += "<div id='match_" + index + "_5'></div></td>";
      html_R14 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R14 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R14 += "<div id='match_" + index + "_7'></div></td>";
      html_R14 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R14 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_9'></div></td>";
    html_R14 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R14 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_11'></div></td>";
    html_R14 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R14 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_13'></div></td>";
    html_R14 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R14 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R14 += "<div id='match_" + index + "_15'></div></td>";
    html_R14 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R14 += "<td class='column_9'  align='center'>";
    html_R14 += DateSelect(play_date, ("D_" + index + '_9'));
    html_R14 += "&nbsp;";
    html_R14 += "<input type='text' id='T_" + index + "_9' data-row='" + index + "' data-col='9' value='' readonly size='6' /></td>";
    html_R14 += "<td class='column_9' >&nbsp;</td>";
    html_R14 += "<td class='column_10' >&nbsp;</td>";
    html_R14 += "<td class='column_10' >&nbsp;</td>";
    html_R14 += "<td class='column_11' >&nbsp;</td>";
    html_R14 += "<td class='column_11' >&nbsp;</td>";
    html_R14 += "<td class='column_12' >&nbsp;</td>";
    html_R14 += "<td class='test_column'>R14</td>";
    return html_R14;
  }

  function R15(index, tourn_size) 
  {
    html_R15 = "";
    if(tourn_size == 128)
    {
      html_R15 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R15 += "<div id='match_" + index + "_1'></div></td>";
      html_R15 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R15 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R15 += "<div id='match_" + index + "_3'></div></td>";
      html_R15 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R15 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R15 += "<div id='match_" + index + "_5'></div></td>";
      html_R15 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R15 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R15 += "<div id='match_" + index + "_7'></div></td>";
      html_R15 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R15 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_9'></div></td>";
    html_R15 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R15 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_11'></div></td>";
    html_R15 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R15 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_13'></div></td>";
    html_R15 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R15 += "<td class='column_8'  nowrap align='center'><input type='text' class='player' id='" + index + "_15_15' value='' readonly></td>";
    html_R15 += "<div id='match_" + index + "_15'></div></td>";
    html_R15 += "<td class='column_8'  id='" + index + "_16' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_16_16' value='' readonly></td>";
    html_R15 += "<td class='column_9' >&nbsp;</td>";
    html_R15 += "<td class='column_9' >&nbsp;</td>";
    html_R15 += "<td class='column_10' >&nbsp;</td>";
    html_R15 += "<td class='column_10' >&nbsp;</td>";
    html_R15 += "<td class='column_11' >&nbsp;</td>";
    html_R15 += "<td class='column_11' >&nbsp;</td>";
    html_R15 += "<td class='column_12' >&nbsp;</td>";
    html_R15 += "<td class='test_column'>R15</td>";
    return html_R15;
  }

  function R16(index, tourn_size) 
  {
    html_R16 = "";
    if(tourn_size == 128)
    {
      html_R16 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R16 += "<div id='match_" + index + "_1'></div></td>";
      html_R16 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R16 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R16 += "<div id='match_" + index + "_3'></div></td>";
      html_R16 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R16 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R16 += "<div id='match_" + index + "_5'></div></td>";
      html_R16 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R16 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R16 += "<div id='match_" + index + "_7'></div></td>";
      html_R16 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R16 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_9'></div></td>";
    html_R16 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R16 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_11'></div></td>";
    html_R16 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R16 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R16 += "<div id='match_" + index + "_13'></div></td>";
    html_R16 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R16 += "<td class='column_8' >&nbsp;</td>";
    html_R16 += "<td class='column_8' >&nbsp;</td>";
    html_R16 += "<td class='column_9' >&nbsp;</td>";
    html_R16 += "<td class='column_9' >&nbsp;</td>";
    html_R16 += "<td class='column_10'  align='center'>";
    html_R16 += DateSelect(play_date, ("D_" + index + '_10'));
    html_R16 += "&nbsp;";
    html_R16 += "<input type='text' id='T_" + index + "_10' data-row='" + index + "' data-col='10' value='' readonly size='6' /></td>";
    html_R16 += "<td class='column_10' >&nbsp;</td>";
    html_R16 += "<td class='column_11' >&nbsp;</td>";
    html_R16 += "<td class='column_11' >&nbsp;</td>";
    html_R16 += "<td class='column_12' >&nbsp;</td>";
    html_R16 += "<td class='test_column'>R16</td>";
    return html_R16;
  }

  function R17(index, tourn_size) 
  {
    html_R17 = "";
    if(tourn_size == 128)
    {
      html_R17 += "<td class='column_1'  nowrap align='center'><input type='text' class='player' id='" + index + "_1_1' value=''></td>";
      html_R17 += "<div id='match_" + index + "_1'></div></td>";
      html_R17 += "<td class='column_1'  id='" + index + "_2' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_2_2' value='' readonly></td>";
      html_R17 += "<td class='column_2'  nowrap align='center'><input type='text' class='player' id='" + index + "_3_3' value=''></td>";
      html_R17 += "<div id='match_" + index + "_3'></div></td>";
      html_R17 += "<td class='column_2'  id='" + index + "_4' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_4_4' value='' readonly></td>";
      html_R17 += "<td class='column_3'  nowrap align='center'><input type='text' class='player' id='" + index + "_5_5' value=''></td>";
      html_R17 += "<div id='match_" + index + "_5'></div></td>";
      html_R17 += "<td class='column_3'  id='" + index + "_6' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_6_6' value='' readonly></td>";
      html_R17 += "<td class='column_4'  nowrap align='center'><input type='text' class='player' id='" + index + "_7_7' value=''></td>";
      html_R17 += "<div id='match_" + index + "_7'></div></td>";
      html_R17 += "<td class='column_4'  id='" + index + "_8' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_8_8' value='' readonly></td>";
    }
    html_R17 += "<td class='column_5'  nowrap align='center'><input type='text' class='player' id='" + index + "_9_9' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_9'></div></td>";
    html_R17 += "<td class='column_5'  id='" + index + "_10' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_10_10' value='' readonly></td>";
    html_R17 += "<td class='column_6'  nowrap align='center'><input type='text' class='player' id='" + index + "_11_11' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_11'></div></td>";
    html_R17 += "<td class='column_6'  id='" + index + "_12' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_12_12' value='' readonly></td>";
    html_R17 += "<td class='column_7'  nowrap align='center'><input type='text' class='player' id='" + index + "_13_13' value='' readonly></td>";
    html_R17 += "<div id='match_" + index + "_13'></div></td>";
    html_R17 += "<td class='column_7'  id='" + index + "_14' align='center'><input type='text'  style='text-align: center;' size='3px' id='" + index + "_14_14' value='' readonly></td>";
    html_R17 += "<td class='column_8' >&nbsp;</td>";
    html_R17 += "<td class='column_8' >&nbsp;</td>";
    html_R17 += "<td class='column_9' >&nbsp;</td>";
    html_R17 += "<td class='column_9' >&nbsp;</td>";
    html_R17 += "<td class='column_10' >&nbsp;</td>";
    html_R17 += "<td class='column_10' >&nbsp;</td>";
    html_R17 += "<td class='column_11' >&nbsp;</td>";
    html_R17 += "<td class='column_11' >&nbsp;</td>";
    html_R17 += "<td class='column_12' >&nbsp;</td>";
    html_R17 += "<td class='test_column'>R17</td>";
    return html_R17;
  }

  function R18(index, tourn_size) 
  {
    html_R18 = "";
    if(tourn_size == 128)
    {
      html_R18 += "<td class='column_1' >&nbsp;</td>";
      html_R18 += "<td class='column_1' >&nbsp;</td>";
      html_R18 += "<td class='column_2' >&nbsp;</td>";
      html_R18 += "<td class='column_2' >&nbsp;</td>";
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_3' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
      html_R18 += "<td class='column_4' >&nbsp;</td>";
    }
    html_R18 += "<td class='column_5' >&nbsp;</td>";
    html_R18 += "<td class='column_5' >&nbsp;</td>";
    html_R18 += "<td class='column_6' >&nbsp;</td>";
    html_R18 += "<td class='column_6' >&nbsp;</td>";
    html_R18 += "<td class='column_7' >&nbsp;</td>";
    html_R18 += "<td class='column_7' >&nbsp;</td>";
    html_R18 += "<td class='column_8' >&nbsp;</td>";
    html_R18 += "<td class='column_8' >&nbsp;</td>";
    html_R18 += "<td class='column_9' >&nbsp;</td>";
    html_R18 += "<td class='column_9' >&nbsp;</td>";
    html_R18 += "<td class='column_10' >&nbsp;</td>";
    html_R18 += "<td class='column_10' >&nbsp;</td>";
    html_R18 += "<td class='column_11'  align='center'>";
    html_R18 += DateSelect(play_date, ("D_" + index + '_11'));
    html_R18 += "&nbsp;";
    html_R18 += "<input type='text' id='T_" + index + "_11' data-row='" + index + "' data-col='11' value='' readonly size='6' /></td>";
    html_R18 += "<td class='column_11' >&nbsp;</td>";
    html_R18 += "<td class='column_12' >&nbsp;</td>";
    html_R18 += "<td class='test_column'>R18</td>";
    return html_R18;
  }

  function Finals(index, tourn_size) 
  {
    html_Finals = "";
    if(tourn_size == 128)
    {
      html_Finals += "<td class='column_1' >&nbsp;</td>";
      html_Finals += "<td class='column_1' >&nbsp;</td>";
      html_Finals += "<td class='column_2' >&nbsp;</td>";
      html_Finals += "<td class='column_2' >&nbsp;</td>";
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_3' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
      html_Finals += "<td class='column_4' >&nbsp;</td>";
    }
    html_Finals += "<td class='column_5' >&nbsp;</td>";
    html_Finals += "<td class='column_5' >&nbsp;</td>";
    html_Finals += "<td class='column_6' >&nbsp;</td>";
    html_Finals += "<td class='column_6' >&nbsp;</td>";
    html_Finals += "<td class='column_7' >&nbsp;</td>";
    html_Finals += "<td class='column_7' >&nbsp;</td>";
    html_Finals += "<td class='column_8' >&nbsp;</td>";
    html_Finals += "<td class='column_8' >&nbsp;</td>";
    html_Finals += "<td class='column_9' >&nbsp;</td>";
    html_Finals += "<td class='column_9' >&nbsp;</td>";
    html_Finals += "<td class='column_10' >&nbsp;</td>";
    html_Finals += "<td class='column_10' >&nbsp;</td>";
    html_Finals += "<td class='column_11' >&nbsp;</td>";
    html_Finals += "<td class='column_11' >&nbsp;</td>";
    html_Finals += "<td class='column_12'  nowrap align='center'><b><input type='text' class='player' id='" + index + "_23_23' value=''  readonly style='width:164px; height:32x'></b></td>";
    html_Finals += "<div id='match_" + index + "_23'></div></td>";
    html_Finals += "<td class='test_column'>R_Finals;</td>";
    return html_Finals;
  }

  function Blank(index, tourn_size) 
  {
    html_Blank = "";
    if(tourn_size == 128)
    {
      html_Blank += "<td class='column_1' >&nbsp;</td>";
      html_Blank += "<td class='column_1' >&nbsp;</td>";
      html_Blank += "<td class='column_2' >&nbsp;</td>";
      html_Blank += "<td class='column_2' >&nbsp;</td>";
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_3' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
      html_Blank += "<td class='column_4' >&nbsp;</td>";
    }
    html_Blank += "<td class='column_5' >&nbsp;</td>";
    html_Blank += "<td class='column_5' >&nbsp;</td>";
    html_Blank += "<td class='column_6' >&nbsp;</td>";
    html_Blank += "<td class='column_6' >&nbsp;</td>";
    html_Blank += "<td class='column_7' >&nbsp;</td>";
    html_Blank += "<td class='column_7' >&nbsp;</td>";
    html_Blank += "<td class='column_8' >&nbsp;</td>";
    html_Blank += "<td class='column_8' >&nbsp;</td>";
    html_Blank += "<td class='column_9' >&nbsp;</td>";
    html_Blank += "<td class='column_9' >&nbsp;</td>";
    html_Blank += "<td class='column_10' >&nbsp;</td>";
    html_Blank += "<td class='column_10' >&nbsp;</td>";
    html_Blank += "<td class='column_11' >&nbsp;</td>";
    html_Blank += "<td class='column_11' >&nbsp;</td>";
    html_Blank += "<td class='column_12' >&nbsp;</td>";
    html_Blank += "<td class='test_column'>R_Blank</td>";
    return html_Blank;
  }

  function TimeDay1(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_1'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' id='T_" + index + "_1' data-row='" + index + "' data-col='1' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_2'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' id='T_" + index + "_2' data-row='" + index + "' data-col='2' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_3'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' id='T_" + index + "_3' data-row='" + index + "' data-col='3' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4'  align='center'>";
      html_TimeDay += DateSelect(play_date, ("D_" + index + '_4'));
      html_TimeDay += "&nbsp;";
      html_TimeDay += "<input type='text' id='T_" + index + "_4' data-row='" + index + "' data-col='4' value='' readonly size='6' /></td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5'  align='center' class='column_5'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_5'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' id='T_" + index + "_5' data-row='" + index + "' data-col='5' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_6'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' id='T_" + index + "_6' data-row='" + index + "' data-col='6' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_7'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' id='T_" + index + "_7' data-row='" + index + "' data-col='7' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay1</td>";
    return html_TimeDay;
  }

  function TimeDay2(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_10'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' id='T_" + index + "_10' data-row='" + index + "' data-col='10' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay2</td>";
    return html_TimeDay;
  }

  function TimeDay3(index, tourn_size) 
  {
    html_TimeDay = "";
    if(tourn_size == 128)
    {
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_1' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_2' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_3' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
      html_TimeDay += "<td class='column_4' >&nbsp;</td>";
    }
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_5' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_6' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_7' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_8' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_9' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_10' >&nbsp;</td>";
    html_TimeDay += "<td class='column_11'  align='center'>";
    html_TimeDay += DateSelect(play_date, ("D_" + index + '_11'));
    html_TimeDay += "&nbsp;";
    html_TimeDay += "<input type='text' id='T_" + index + "_11' data-row='" + index + "' data-col='11' value='' readonly size='6' /></td>";
    html_TimeDay += "<td class='column_11' >&nbsp;</td>";
    html_TimeDay += "<td class='column_12' >&nbsp;</td>";
    html_TimeDay += "<td class='test_column'>TimeDay3</td>";
    return html_TimeDay;
  }

  function Header(index, tourn_size) 
  {
    html_Header = "";
    if(tourn_size == 128)
    {
      html_Header += "<td class='DT_column_1'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_1'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_2'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_2'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_3'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_3'  align='center'><b>Score</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Day/Time</b></td>";
      html_Header += "<td class='DT_column_4'  align='center'><b>Score</b></td>";
    }
    html_Header += "<td class='DT_column_5'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_5'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_6'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_6'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_7'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_7'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_8'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_8'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_9'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_9'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_10'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_10'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_11'  align='center'><b>Day/Time</b></td>";
    html_Header += "<td class='DT_column_11'  align='center'><b>Score</b></td>";
    html_Header += "<td class='DT_column_12' >&nbsp;</td>";
    html_Header += "<td class='test_column'>Header</td>";
    return html_Header;
  }

const rowPatterns = [
// header id first three rows
  "Header",
  "Blank",
  "TimeDay1",
  "R7",
  "R8",
  "R13",
  "R9",
  "TimeDay1",
  "R8",
  "R16",
  "R10",
  "Blank",
  "TimeDay1",
  "R7",
  "R12",
  "Blank",
  "R9",
  "TimeDay1",
  "R8",
  "R17",
  "Blank",
  "R18",
  "R11",
  "TimeDay1",
  "R7",
  "R12",
  "R13",
  "R9",
  "TimeDay1",
  "R15",
  "R17",
  "R10",
  "Blank",
  "TimeDay1",
  "R7",
  "R12",
  "Blank",
  "R9",
  "TimeDay1",
  "R8",
  "R17",
  "Blank",
  "Finals",
  "Blank",
];

let rounds = 0;
if(tourn_size === 128)
{
    rounds = 12; // number of rounds (inc finals)
}
else
{
    rounds = 8; // number of rounds (inc finals)
}
let html = "<table align='center' border='0' cellpadding='0' cellspacing='0' id='tourn_table' class='table table-striped table-bordered table-responsive' style='width: 50%;'>";

// ---- Top Header ----
index = 1;
rnd = 3;// for 128 player tournament
html += "<thead>";
html += "<tr>";

for (let r = 1; r <= rounds; r++) 
{
  if((tourn_size === 128) && (r <= 4))
  {
      html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 1 (Best of " + <?= $build_best_of['best_of_1'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r > 4) && (r <= 6))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_" + (r+1) + "'><b>Round 2 (Best of " + <?= $build_best_of['best_of_2'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 7))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_6'><b>Round " + (r-4) + " (Best of " + <?= $build_best_of['best_of_3'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 8))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_7'><b>Round " + (r-4) + " (Best of " + <?= $build_best_of['best_of_4'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 9))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_9'><b>Quarter Finals <b>(Best of " + <?= $build_best_of['best_of_6'] ?> + ")</b></th>";
  }
  else if(((tourn_size === 128) && r === 10))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_10'><b>Semi Finals (Best of " + <?= $build_best_of['best_of_7'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 11))
  {
    html += "<th colspan='2'  style='text-align: center' class='column_11'><b>Finals (Best of " + <?= $build_best_of['best_of_8'] ?> + ")</b></th>";
  }
  else if((tourn_size === 128) && (r === 12))
  {
    html += "<th  style='text-align: center' class='column_12'><h5><b>Winner</b></h5></th>";
  }
  if(tourn_size === 64)
  {
    if(r === 1)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_5' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_1'] ?> + ")</b></th>";
    }
    else if(r === 2)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_6' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_2'] ?> + ")</b></th>";
    }
    else if(r === 3)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_7' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_3'] ?> + ")</b></th>";
    }
    else if(r === 4)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_8' value=" + r + "><b>Round " + r + " (Best of " + <?= $build_best_of['best_of_4'] ?> + ")</b></th>";
    }
    else if(r === 5)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_9' value=" + r + "><b>Quarter Finals (Best of " + <?= $build_best_of['best_of_5'] ?> + ")</b></th>";
    }
    else if(r === 6)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_10' value=" + r + "><b>Semi Finals (Best of " + <?= $build_best_of['best_of_6'] ?> + ")</b></th>";
    }
    else if(r === 7)
    {
      html += "<th colspan='2'  style='text-align: center;' class='column_11' value=" + r + "><b>Finals (Best of " + <?= $build_best_of['best_of_7'] ?> + ")</b></th>";
    }
    else if(r === 8)
    {
      html += "<th style='text-align: center;' class='column_12'><b>Winner</b></th>";
    }
  }
}
html += "</tr>";
html += "</thead>";
index++;
html += "<tbody>";
html += "<tr>";

// ---- Body ----
rowPatterns.forEach((pattern, rowIndex) => {
    if (pattern === "R7") 
    {
      html += R7(index, tourn_size);
    } 
    else if (pattern === "R8") 
    {
      html += R8(index, tourn_size);
    } 
    else if (pattern === "R9") 
    {
      html += R9(index, tourn_size);
    }
    else if (pattern === "R10") 
    {
      html += R10(index, tourn_size);
    }
    else if (pattern === "R11") 
    {
      html += R11(index, tourn_size);
    }
    else if (pattern === "R12") 
    {
      html += R12(index, tourn_size);
    }
    else if (pattern === "R13") 
    {
      html += R13(index, tourn_size);
    }
    else if (pattern === "R14") 
    {
      html += R14(index, tourn_size);
    }
    else if (pattern === "R15") 
    {
      html += R15(index, tourn_size);
    }
    else if (pattern === "R16") 
    {
      html += R16(index, tourn_size);
    }
    else if (pattern === "R17") 
    {
      html += R17(index, tourn_size);
    }
    else if (pattern === "R18") 
    {
      html += R18(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
    else if (pattern === "TimeDay1") 
    {
      html += TimeDay1(index, tourn_size);
    }
    else if (pattern === "TimeDay2") 
    {
      html += TimeDay2(index, tourn_size);
    }
    else if (pattern === "TimeDay3") 
    {
      html += TimeDay3(index, tourn_size);
    }
    else if (pattern === "Finals") 
    {
      html += Finals(index, tourn_size);
    }
    else if (pattern === "Header") 
    {
      html += Header(index, tourn_size);
    }
  html += "</tr>";
  index++;
});

// ---- bottom of table ----
html += "<tr>";

// ---- Bottom Body ----
rowPatterns.forEach((pattern, rowIndex) => {
    if (pattern === "R7") 
    {
      html += R7(index, tourn_size);
    } 
    else if (pattern === "R8") 
    {
      html += R8(index, tourn_size);
    } 
    else if (pattern === "R9") 
    {
      html += R9(index, tourn_size);
    }
    else if (pattern === "R10") 
    {
      html += R10(index, tourn_size);
    }
    else if (pattern === "R11") 
    {
      html += R11(index, tourn_size);
    }
    else if (pattern === "R12") 
    {
      html += R12(index, tourn_size);
    }
    else if (pattern === "R13") 
    {
      html += R13(index, tourn_size);
    }
    else if (pattern === "R14") 
    {
      html += R14(index, tourn_size);
    }
    else if (pattern === "R15") 
    {
      html += R15(index, tourn_size);
    }
    else if (pattern === "R16") 
    {
      html += R16(index, tourn_size);
    }
    else if (pattern === "R17") 
    {
      html += R17(index, tourn_size);
    }
    else if (pattern === "R18") 
    {
      html += R18(index, tourn_size);
    }
    else if (pattern === "Blank") 
    {
      html += Blank(index, tourn_size);
    }
    else if (pattern === "TimeDay1") 
    {
      html += TimeDay1(index, tourn_size);
    }
    else if (pattern === "TimeDay2") 
    {
      html += TimeDay2(index, tourn_size);
    }
    else if (pattern === "TimeDay3") 
    {
      html += TimeDay3(index, tourn_size);
    }
    else if (pattern === "Header") 
    {
      html += Header(index, tourn_size);
    }
  html += "</tr>";
  index++;
});
html += "</tbody>";
html += "</table>";
html += "<br><br>";

document.getElementById("fixtureTable").innerHTML = html;
/*
if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
{
    if(tourn_size == 64)
    {
      setupColumnNavigation();
      // re-run the column update once more shortly after population likely finishes
      setTimeout(function(){
        // safe re-run: re-collect groups and re-apply update
        if (typeof setupColumnNavigation === 'function') {
          setupColumnNavigation(); // re-init (it .off() handlers)
        }
      }, 300); // adjust 200-600ms based on how long populate usually takes
    }
}
*/
</script>
<?php
// map seeds/ranks to table position
if($tourn_size === 64)
{
  $idMap = [
    // rows 1 to 4 are headers
    1 => "5_13_13",
    2 => "85_13_13",
    3 => "49_13_13",
    4 => "41_13_13",
    5 => "26_13_13",
    6 => "64_13_13",
    7 => "70_13_13",
    8 => "20_13_13",
    9 => "15_13_13",
    10 => "75_13_13",
    11 => "59_13_13",
    12 => "31_13_13",
    13 => "36_13_13",
    14 => "54_13_13",
    15 => "80_13_13",
    16 => "10_13_13",
    17 => "10_11_11",
    18 => "80_11_11",
    19 => "54_11_11",
    20 => "36_11_11",
    21 => "31_11_11",
    22 => "59_11_11",
    23 => "75_11_11",
    24 => "15_11_11",
    25 => "20_11_11",
    26 => "70_11_11",
    27 => "64_11_11",
    28 => "26_11_11",
    29 => "41_11_11",
    30 => "49_11_11",
    31 => "85_11_11",
    32 => "5_11_11",
    33 => "5_9_9",
    34 => "86_9_9",
    35 => "49_9_9",
    36 => "42_9_9",
    37 => "27_9_9",
    38 => "64_9_9",
    39 => "71_9_9",
    40 => "20_9_9",
    41 => "15_9_9",
    42 => "76_9_9",
    43 => "59_9_9",
    44 => "32_9_9",
    45 => "37_9_9",
    46 => "54_9_9",
    47 => "81_9_9",
    48 => "10_9_9",
    49 => "11_9_9",
    50 => "80_9_9",
    51 => "55_9_9",
    52 => "36_9_9",
    53 => "31_9_9",
    54 => "60_9_9",
    55 => "75_9_9",
    56 => "16_9_9",
    57 => "21_9_9",
    58 => "70_9_9",
    59 => "65_9_9",
    60 => "26_9_9",
    61 => "41_9_9",
    62 => "50_9_9",
    63 => "85_9_9",
    64 => "6_9_9"
  ];
}
else if($tourn_size == 128)
{
  $idMap = [
    // rows 1 to 4 are headers
    1 => "5_13_13",
    2 => "85_13_13",
    3 => "49_13_13",
    4 => "41_13_13",
    5 => "26_13_13",
    6 => "64_13_13",
    7 => "70_13_13",
    8 => "20_13_13",
    9 => "15_13_13",
    10 => "75_13_13",
    11 => "59_13_13",
    12 => "31_13_13",
    13 => "36_13_13",
    14 => "54_13_13",
    15 => "80_13_13",
    16 => "10_13_13",
    17 => "10_9_9",
    18 => "80_9_9",
    19 => "54_9_9",
    20 => "36_9_9",
    21 => "31_9_9",
    22 => "59_9_9",
    23 => "75_9_9",
    24 => "15_9_9",
    25 => "20_9_9",
    26 => "70_9_9",
    27 => "64_9_9",
    28 => "26_9_9",
    29 => "41_9_9",
    30 => "49_9_9",
    31 => "85_9_9",
    32 => "5_9_9",
    33 => "5_11_11",
    34 => "85_11_11",
    35 => "49_11_11",
    36 => "41_11_11",
    37 => "26_11_11",
    38 => "64_11_11",
    39 => "70_11_11",
    40 => "20_11_11",
    41 => "15_11_11",
    42 => "75_11_11",
    43 => "59_11_11",
    44 => "31_11_11",
    45 => "36_11_11",
    46 => "54_11_11",
    47 => "80_11_11",
    48 => "10_11_11",
    49 => "10_5_5",
    50 => "80_5_5",
    51 => "54_5_5",
    52 => "36_5_5",
    53 => "31_5_5",
    54 => "59_5_5",
    55 => "75_5_5",
    56 => "15_5_5",
    57 => "20_5_5",
    58 => "70_5_5",
    59 => "64_5_5",
    60 => "26_5_5",
    61 => "41_5_5",
    62 => "49_5_5",
    63 => "85_5_5",
    64 => "5_5_5",
    65 => "5_7_7",
    66 => "85_7_7",
    67 => "49_7_7",
    68 => "41_7_7",
    69 => "26_7_7",
    70 => "64_7_7",
    71 => "70_7_7",
    72 => "20_7_7",
    73 => "15_7_7",
    74 => "75_7_7",
    75 => "59_7_7",
    76 => "31_7_7",
    77 => "36_7_7",
    78 => "54_7_7",
    79 => "80_7_7",
    80 => "10_7_7",
    81 => "10_1_1",
    82 => "81_1_1",
    83 => "54_1_1",
    84 => "37_1_1",
    85 => "32_1_1",
    86 => "59_1_1",
    87 => "76_1_1",
    88 => "15_1_1",
    89 => "20_1_1",
    90 => "71_1_1",
    91 => "64_1_1",
    92 => "27_1_1",
    93 => "42_1_1",
    94 => "49_1_1",
    95 => "86_1_1",
    96 => "5_1_1",
    97 => "5_3_3",
    98 => "85_3_3",
    99 => "49_3_3",
    100 => "41_3_3",
    101 => "26_3_3",
    102 => "64_3_3",
    103 => "70_3_3",
    104 => "20_3_3",
    105 => "15_3_3",
    106 => "75_3_3",
    107 => "59_3_3",
    108 => "31_3_3",
    109 => "36_3_3",
    110 => "54_3_3",
    111 => "80_3_3",
    112 => "10_3_3",
    113 => "11_1_1",
    114 => "80_1_1",
    115 => "55_1_1",
    116 => "36_1_1",
    117 => "31_1_1",
    118 => "60_1_1",
    119 => "75_1_1",
    120 => "16_1_1",
    121 => "21_1_1",
    122 => "70_1_1",
    123 => "65_1_1",
    124 => "26_1_1",
    125 => "41_1_1",
    126 => "50_1_1",
    127 => "85_1_1",
    128 => "6_1_1"
  ];
}
echo("<script>");
$player_index = 0;
mysqli_data_seek($result_players, 0);
while($build_table = $result_players->fetch_assoc())
{
    $rowNum = $build_table['row_num'];
    if(isset($idMap[$rowNum]))
    {
      $name = addslashes($build_table['FirstName'] . " " . $build_table['LastName']);
      $elementId = $idMap[$rowNum];
      echo "document.getElementById('$elementId').value = '$name';";
    }
    $player_index++;
}

for($i = ($player_index); $i < $tourn_size; $i++)
{
  $name = 'Bye';
  $elementId = $idMap[$i+1];
  echo "document.getElementById('$elementId').value = '$name';";
}

// add player as rounds progress
$sql_added_player = 'Select * from tournament_players where tourn_id = ' . $tourn_id . ' and added_player = 1';
$result_added_player = mysql_query($sql_added_player, $connvbsa) or die(mysql_error());
while($build_added = $result_added_player->fetch_assoc())
{
  echo("document.getElementById('" . $build_added['row_no'] . "_" . $build_added['col_no'] . "_" . $build_added['col_no'] . "').value = '" . $build_added['fullname'] . "';");
}
echo("</script>");
?>
</center>

<script>
$(document).ready(function() 
{
  
  $('.test_column').hide();

  $.fn.get_round_no = function (column, tourn_size) {
    if(tourn_size = 64)
    {
      switch (column) {
        case 9:
            round_no = 1;
            break;
        case 11:
            round_no = 2;
            break;
        case 13:
            round_no = 3;
            break;
        case 15:
            round_no = 4;
            break;
        case 17:
            round_no = 5;
            break;
        case 19:
            round_no = 6;
            break;
        case 21:
            round_no = 7;
            break; 
        case 23:
            round_no = 8;
            break; 
        default:
            $round_no = 0;
            break; 
        }
    }
    return round_no;
  }

  $.fn.get_player_1 = function (str) {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = str.substring(0, first);
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));
    return $('#' + row + '_' + (column-1) + '_' + (column-1)).val();
  }

  $.fn.get_player_2 = function (str, i) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));

    var player_name_1 = $('#' + (row) + "_" + (column-1) + "_" + (column-1)).val();
    var player_name_2 = $('#' + (row+i) + "_" + (column-1) + "_" + (column-1)).val();
    var player_name_3 = $('#' + (row-i) + "_" + (column-1) + "_" + (column-1)).val();
  
    if(player_name_1 == undefined)
    {
      var row_no = (row);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    if(player_name_2 == undefined)
    {
      var row_no = (row-i);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    else if(player_name_3 == undefined)
    {
      var row_no = (row+i);
      var player_name = $('#' + (row_no) + "_" + (column-1) + "_" + (column-1)).val();
    }
    return player_name;
  }

  $.fn.get_score_1 = function (str) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var col_split = column.split('_');
    var col_1 = col_split[0];
    var col_2 = col_split[1];
    var score_value = $('#' + row + "_" + column).val();
    var score_detail= [];
    score_detail['value'] = score_value;
    score_detail['row'] = row;
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_score_2 = function (str, i) 
  {
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var column = str.substring(first+1);
    var col_split = column.split('_');
    var col_1 = col_split[0];
    var col_2 = col_split[1];
    var score_detail= [];
    var score_value_1 = $('#' + (row+i) + '_' + column).val();
    var score_value_2 = $('#' + (row) + '_' + column).val();
    var score_value_3 = $('#' + (row-i) + '_' + column).val();
    if((score_value_1 == undefined) || (score_value_1 == ''))
    {
      var score_value = $('#' + (row-i) + '_' + column).val();
      score_detail['row'] = (row-i);
    }
    else if((score_value_3 == undefined) || (score_value_3 == ''))
    {
      var score_value = $('#' + (row+i) + '_' + column).val();
      score_detail['row'] = (row+i);
    }
    score_detail['value'] = score_value;
    score_detail['col1'] = col_1;
    score_detail['col2'] = col_2;
    return score_detail;
  }

  $.fn.get_next_id = function (str, i, call) 
  {
    if(call == 'SS') // call from scoresheet
    {
      column = column;
    }
    else if(call == "ES") // call from enter_score
    {
      column = (column-1);
    }
    var subStr = '_';
    var first = (str.split(subStr, 1).join(subStr).length);
    var row = parseInt(str.substring(0, first));
    var second = (str.split(subStr, 2).join(subStr).length);
    var column = parseInt(str.substring(second+1));

    var id_detail= [];
    var id_value_1 = $('#' + (parseInt(row)+i) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();
    var id_value_2 = $('#' + parseInt(row) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();
    var id_value_3 = $('#' + (parseInt(row)-i) + '_' + (parseInt(column)) + '_' + (parseInt(column))).val();

    if((id_value_1 != undefined) || (id_value_1 == ''))
    {
      var name_next = $('#' + (row+i) + '_' + (column) + '_' + (column)).val();
      var id_next = (row+i) + '_' + column + '_' + column;
      var row_next = (row+i);
    }
    else if((id_value_3 != undefined) || (id_value_3 == ''))
    {
      var name_next = $('#' + (row-i) + '_' + (column) + '_' + (column)).val();
      var id_next = (row-i) + '_' + column + '_' + column;
      var row_next = (row-i);
    }
    var name_current = id_value_2;
    var id_current = row + '_' + column + '_' + column;
    id_detail['column'] = column;
    id_detail['current_id'] = id_current;
    id_detail['next_id'] = id_next;
    id_detail['current_row'] = row;
    id_detail['next_row'] = row_next;
    id_detail['current_name'] = name_current;
    id_detail['next_name'] = name_next;
    return id_detail;
  }

  $.fn.get_draw = function (element_id) 
  {
    var subStr = '_';
    var first = (element_id.split(subStr, 1).join(subStr).length);
    var row = parseInt(element_id.substring(0, first));
    var col = element_id.substring(first+1);
    var score_1 = $('#' + row + '_' + col + '_' + col).val();
    var score_2 = $('#' + (row+1) + '_' + col + '_' + col).val();
    if(score_2 < score_1)
    {
      var player_name = $('#' + (row) + "_" + col + "_" + col).val();
    }
    else if(score_1 < score_2)
    {
      var player_name = $('#' + (row+1) + "_" + col + "_" + col).val();
    }
    var next_player = (player_name + ", " + row + ", " + col);
    return next_player;
  }

  $.fn.get_member_id = function (fullname) 
  {
    var response;
    $.ajax({
      url:"get_member_id.php?player_name=" + fullname,
      method: 'GET',
      async: false,
      success:function(response)
      {
        result = response;
      }
    });
    return result;
  }

  $('.player').on('click', function(e) 
  {
    e.preventDefault();
    $('#scorestable_1').empty();
    $('#scorestable_2').empty();
    var id = $(this).attr('id');
    var tourn_id = <?= $tourn_id ?>;
    var tourn_size = <?= $tourn_size ?>;
    var subStr = '_';
    var first_1 = (id.split(subStr, 1).join(subStr).length);
    var row_1 = parseInt(id.substring(0, first_1));
    var col_1 = parseInt(id.substring(first_1+1));
    var round_no = $.fn.get_round_no(col_1, tourn_size);
    $('#round').html(round_no);
    $('#match_no').html($('#match_' + row_1 + '_' + col_1).val());
    $('#scores_modal').modal('show'); 

    if(col_1 < 15)
    {
      score_array = $.fn.get_next_id(id, 1, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 15)
    {
      score_array = $.fn.get_next_id(id, 4, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 17)
    {
      score_array = $.fn.get_next_id(id, 10, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 19)
    {
      score_array = $.fn.get_next_id(id, 21, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 21)
    {
      score_array = $.fn.get_next_id(id, 44, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    // ajax to get best of tables
    $.ajax({
      url:"get_best_of_data.php?tourn_id=" + tourn_id + "&round=" + round_no,
      method: 'GET',
      success:function(data)
      {
        var best_of = data;
        var colspan = (best_of+1);
        var disabled = '';
      
        if(best_of > 7)
        {
          $('#modal_size').addClass('modal-lg');
        }
        else
        {
          $('#modal_size').removeClass('modal-lg');
        }
        
        output_1 = '';
        if(data === 'No Data')
        {
          output_1 += ("<div>No Best of data available!</div>");
        }
        else
        {
          obj = jQuery.parseJSON(data);
          output_1 += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
          output_1 += ("<tr>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'>");
          output_1 += ("<b><div id='playername_1'>" + player_name_1 + "</div></b>");
          output_1 += ("<div hidden id='member_id_1'>" + $.fn.get_member_id(player_name_1) + "</div>");
          output_1 += ("<div hidden id='scores_element_id_1'>" + score_array['current_id'] + "</div>");
          output_1 += ("<div hidden id='row_1'>" + row_1 + "</div>");
          output_1 += ("<div hidden id='column_1'>" + col + "</div>");
          output_1 += ("<div hidden id='best'>" + best_of + "</div>");
          output_1 += ("</td>");
          output_1 += ("</tr>");
          /*output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'>Change Player:&nbsp;<input id='tags_1' style='width:200px; height:25px'>");
          output_1 += ("<div id='autocompleteAppendToMe_1'></div></td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'><a class='btn btn-default btn-xs' id='newplayer_1'>Save New Player</a></td>");
          output_1 += ("</tr>");*/
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Frame " + (i+1) + "</td>");
          }
          //output_1 += ("<td align='center'>Best Of " + best_of + "</td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Points</td>");
          }
          //output_1 += ("<td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_1' style='text-align: center; width:20px; height:20px'><br><br>");
          //output_1 += ("&nbsp;Forfeit<br><input type='checkbox' id='forfeit_1'><br><br>");
          //output_1 += ("&nbsp;Walkover<br><input type='checkbox' id='walkover_1'></td>");
          output_1 += ("</tr>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' class='score_input_1' id='score" + (i+1) + "_1' style='text-align: center; width:30px; height:20px' tabindex=" + (i+1) + "></td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Breaks 40+</td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' id='brk" + (i+1) + "_1' style='width:50px; height:20px; text-align: left' tabindex=" + (i+8) + "></td>");
          }
          output_1 += ("</tr>");
          /*output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            if(i > 0)
            {
              disabled = 'disabled';
            }
            output_1 += ("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" + (i+1) + "_1' " + disabled + "></td>");
          }
          output_1 += ("</tr>"); */
          output_1 += ("</table>"); 
          $($.parseHTML(output_1)).appendTo('#scorestable_1');
/*
          output_2 = '';
          output_2 += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
          output_2 += ("<tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'>");
          output_2 += ("<b><div id='playername_2'>" + player_name_2 + "</div></b>");
          output_2 += ("<div hidden id='member_id_2'>" + $.fn.get_member_id(player_name_2) + "</div>");
          output_2 += ("<div hidden id='scores_element_id_2'>" + score_array['current_id'] + "</div>");
          output_2 += ("<div hidden id='row_2'>" + row_2 + "</div>");
          output_2 += ("<div hidden id='column_2'>" + col + "</div>");
          output_2 += ("</td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'>Change Player:&nbsp;<input id='tags_2' style='width:200px; height:25px'>");
          output_2 += ("<div id='autocompleteAppendToMe_2'></div></td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          output_2 += ("<td colspan='" + colspan + "' align='center'><a class='btn btn-default btn-xs' id='newplayer_2'>Save New Player</a></td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Frame " + (i+1) + "</td>");
          }
          output_2 += ("<td align='center'>Best Of " + best_of + "</td>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Points</td>");
          }
          output_2 += ("<td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score_2' style='text-align: center; width:20px; height:20px'><br><br>");
          output_2 += ("&nbsp;Forfeit<br><input type='checkbox' id='forfeit_2'><br><br>");
          output_2 += ("&nbsp;Walkover<br><input type='checkbox' id='walkover_2'></td>");
          output_2 += ("</tr>");
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'><input type='text' class='score_input_2' id='score" + (i+1) + "_2' style='text-align: center; width:30px; height:20px' tabindex=" + (i+15) + "></td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'>Breaks 40+</td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_2 += ("<td align='center'><input type='text' id='brk" + (i+1) + "_2' style='width:50px; height:20px' tabindex=" + (i+23) + " style='text-align: left';></td>");
          }
          output_2 += ("</tr>");
          output_2 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            if(i > 0)
            {
              disabled = 'disabled';
            }
            output_2 += ("<td align='center'>To Break <input type='checkbox' class='to_break' id='to_brk" + (i+1) + "_2' " + disabled + "></td>");
          }
          output_2 += ("</tr>"); 
          output_2 += ("</table>"); 
          $($.parseHTML(output_2)).appendTo('#scorestable_2');*/
        }
      },
    });
    $.ajax({
      url:"get_result_data.php?tourn_id=" + tourn_id + "&player_name_1=" + player_name_1 + "&row_1=" + row_1 + "&col=" + col_1 + "&player_name_2=" + player_name_2 + "&row_2=" + row_2 + "&col=" + col_1,
      method: 'GET',
      success:function(data)
      {
        console.log(data);
        if(data === 'No Data')
        {
          for (var i = 0; i < 23; i++) 
          {
            $('#member_id_1').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_1').val(0);
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_1').val('');
            }
            if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_1').val(0);
            }
            $('#game_score_1').val('0');
            $('#referee').val('');
            $('#roving').val('');
            $('#self').val('');
            $('#marker').val('');
            $('#table_no').val('0');
            $('#round').val('0');
            $('#start').val('');
            $('#finish').val('');
            $('#match_no').val('0');
          }
          for (var i = 0; i < 26; i++) 
          {
            $('#member_id_2').val('');
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i) + '_2').val(0);
            }
            if((i > 7) && (i <= 14))
            {
              $('#brk' + (i-7) + '_2').val('');
            }
             if((i > 14) && (i <= 21))
            {
              $('#to_brk' + (i-7) + '_2').val(0);
            }
            $('#game_score_2').val('');
          }
        }
        else
        {
          var newData = data.split(';');
          var member_1_data = newData[0].split(", ");
          var member_2_data = newData[1].split(", ");
          if((member_1_data.length > 1) || (member_2_data.length > 1))
          {
            var cb_index = 1;
            for (var i = 0; i < member_1_data.length; i++) 
            {
              $('#member_id_1').val(member_1_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + i + '_1').val(member_1_data[i]);
              }

              if(member_1_data[8] == 1)
              {
                $('#forfeit_1').prop('checked', true);
              }
              else if(member_1_data[8] == 0)
              {
                $('#forfeit_1').prop("checked", false);
              }
              if(member_1_data[9] == 1)
              {
                $('#walkover_1').prop('checked', true);
              }
              else if(member_1_data[9] == 0)
              {
                $('#walkover_1').prop("checked", false);
              }

              if((i > 9) && (i <= 16))
              {
                $('#brk' + (i-9) + '_1').val(jQuery.trim(member_1_data[i]));
              }
              if((i > 16) && (i <= 23))
              {
                if(member_1_data[i] == 1)
                {
                  $('#to_brk' + cb_index + '_1').prop('checked', true);
                }
                else if(member_1_data[i] == 0)
                {
                  $('#to_brk' + cb_index + '_1').prop("checked", false);
                }
                cb_index++;
              }
              $('#game_score_1').val(member_1_data[(24)]);

              $('#referee').val(member_1_data[(25)]);
              if(member_1_data[(26)] == 1)
              {
                $('#roving').prop('checked', true);
              }
              else if(member_1_data[26] == 0)
              {
                $('#roving').prop("checked", false);
              }
              if(member_1_data[27] == 1)
              {
                $('#self').prop('checked', true);
              }
              else if(member_1_data[27] == 0)
              {
                $('#self').prop("checked", false);
              }
              $('#marker').val($.trim(member_1_data[(28)]));
              $('#table_no').val(member_1_data[(29)]);
              $('#round').val(member_1_data[(30)]);
              $('#start').val(jQuery.trim(member_1_data[(31)]));
              $('#finish').val(jQuery.trim(member_1_data[(32)]));
              $('#match_no').val(member_1_data[(33)]);
            }
            cb_index = 1;
            for (var i = 0; i < member_2_data.length; i++) 
            {
              $('#member_id_2').val(member_2_data[0]);
              if((i > 0) && (i <= 7))
              {
                $('#score' + (i) + '_2').val(member_2_data[(i)]);
              }

              if(member_2_data[8] == 1)
              {
                $('#forfeit_2').prop('checked', true);
              }
              else if(member_2_data[8] == 0)
              {
                $('#forfeit_2').prop("checked", false);
              }
              if(member_2_data[9] == 1)
              {
                $('#walkover_2').prop('checked', true);
              }
              else if(member_2_data[9] == 0)
              {
                $('#walkover_2').prop("checked", false);
              }
          
              if((i > 9) && (i <= 16))
              {
                $('#brk' + (i-9) + '_2').val(jQuery.trim(member_2_data[i]));
              }
              if((i > 16) && (i <= 23))
              {
                if(member_2_data[i] == 1)
                {
                  $('#to_brk' + cb_index + '_2').prop('checked', true);
                }
                else if(member_2_data[i] == 0)
                {
                  $('#to_brk' + cb_index + '_2').prop("checked", false);
                }
                cb_index++;
              }
              $('#game_score_2').val(member_2_data[(24)]);
            }
          }
        }
        $('#scores_modal').modal('show');
      },
      error: function() 
      {
        alert("No data available!");
      }
    });
  });

/*
  $('.player').on('click', function(e) 
  {
    //alert("Not yet implemented!");
    //return;

    e.preventDefault();
    $('#scorestable_1').empty();
    $('#scorestable_2').empty();
    var id = $(this).attr('id');
    var tourn_id = <?= $tourn_id ?>;
    var tourn_size = <?= $tourn_size ?>;
    var subStr = '_';
    var first_1 = (id.split(subStr, 1).join(subStr).length);
    var row_1 = parseInt(id.substring(0, first_1));
    var col_1 = parseInt(id.substring(first_1+1));
    var round_no = $.fn.get_round_no(col_1, tourn_size);
    $('#round').html(round_no);
    $('#match_no').html($('#match_' + row_1 + '_' + col_1).val());
    $('#scores_modal').modal('show'); 

    if(col_1 < 15)
    {
      score_array = $.fn.get_next_id(id, 1, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row = score_array['current_row'];
      $('#row').html(row);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      alert(col + ", " + player_name_1);

      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
      //
    }
    else if(col_1 == 15)
    {
      score_array = $.fn.get_next_id(id, 4, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 17)
    {
      score_array = $.fn.get_next_id(id, 10, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 19)
    {
      score_array = $.fn.get_next_id(id, 21, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    else if(col_1 == 21)
    {
      score_array = $.fn.get_next_id(id, 44, 'SS');
      var col = score_array['column'];
      $('#column_1').html(col);
      $('#column_2').html(col);
      var row_1 = score_array['current_row'];
      $('#row_1').html(row_1);
      var row_2 = score_array['next_row'];
      $('#row_2').html(row_2);
      player_name_1 = score_array['current_name'];
      $('#playername_1').html(player_name_1);
      $('#member_id_1').html($.fn.get_member_id(player_name_1));
      player_name_2 = score_array['next_name'];
      $('#playername_2').html(player_name_2);
      $('#member_id_2').html($.fn.get_member_id(player_name_2));
      $('#scores_element_id_1').html(score_array['current_id']);
      $('#scores_element_id_2').html(score_array['next_id']);
    }
    //alert(player_name + ", " + tourn_id + ", " + round_no);
    // ajax to get best of tables
    $.ajax({
      url:"get_best_of_data.php?tourn_id=" + tourn_id + "&round=" + round_no,
      method: 'POST',
      success:function(data)
      {
        //alert("Best Of Data " + data);
        var best_of = data;
        var colspan = (best_of+1);
        var disabled = '';
      
        //alert("Best Of Data " + data);
        //console.log("Player Name  " + player_name);
        //console.log("Column  " + col);
        //console.log("Col Span  " + colspan);

        if(best_of > 7)
        {
          $('#modal_size').addClass('modal-lg');
        }
        else
        {
          $('#modal_size').removeClass('modal-lg');
        }
        
        output_1 = '';
        if(data === 'No Data')
        {
          output_1 += ("<div>No Best of data available!</div>");
        }
        else
        {
          obj = jQuery.parseJSON(data);
          output_1 += ("<table class='table table-striped table-bordered dt-responsive nowrap display'>");
          output_1 += ("<tr>");
          output_1 += ("<td colspan='" + colspan + "' align='center'>");
          output_1 += ("<b><div id='playername'>" + player_name_1 + "</div></b>");
          output_1 += ("<div hidden id='column'>" + col + "</div>");
          output_1 += ("<div hidden id='best'>" + best_of + "</div>");
          output_1 += ("</td>");
          output_1 += ("</tr>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Frame " + (i+1) + "</td>");
          }
          output_1 += ("<td align='center'>Best Of " + best_of + "</td>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Points</td>");
          }
          output_1 += ("<td rowspan='5' valign='center' align='center'><br><br>Frames:<br><input type='text' id='game_score' style='text-align: center; width:20px; height:20px' readonly><br><br>");
          output_1 += ("&nbsp;Forfeit<br><input type='checkbox' id='forfeit' disabled><br><br>");
          output_1 += ("&nbsp;Walkover<br><input type='checkbox' id='walkover' disabled></td>");
          output_1 += ("</tr>");
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' class='score_input' id='score" + (i+1) + "' style='text-align: center; width:40px; height:20px' readonly></td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'>Breaks 40+</td>");
          }
          output_1 += ("</tr>");
          output_1 += ("<tr>");
          for(i = 0; i < best_of; i++)
          {
            output_1 += ("<td align='center'><input type='text' id='brk" + (i+1) + "' style='width:50px; height:20px' readonly></td>");
          }
          output_1 += ("</tr>");
          output_1 += ("</table>"); 
          $($.parseHTML(output_1)).appendTo('#scorestable_1');
          //alert(output_1);
        }
      }
    });

    $.ajax({
      url:"get_modal_data.php?tourn_id=" + tourn_id + "&player_name=" + player_name_1 + "&row=" + row + "&col=" + col,
      method: 'GET',
      success:function(data)
      {
        //alert("Modal Data " + data);
        if(data === 'No Data')
        {
          for (var i = 0; i < 17; i++) 
          {
            if((i > 0) && (i <= 7))
            {
              $('#score' + (i)).val(0);
            }
            if((i > 9) && (i <= 16))
            {
              $('#brk' + (i-7)).val('');
            }
            $('#forfeit').prop("checked", false);
            $('#walkover').prop("checked", false);
            $('#game_score').val('0');
          }
        }
        else
        {
          var member_data = data.split(", ");
          for (var i = 0; i < member_data.length; i++) 
          {
            console.log("Member Data " + member_data[i]);
            //console.log("Player Name  " + player_name);
            //console.log("Column  " + col);
            //console.log("Player Name  " + player_name);

            if((i > 0) && (i <= 7))
            {
              $('#score' + i).val(member_data[i]);
            }
            if(member_data[8] == 1)
            {
              $('#forfeit').prop('checked', true);
            }
            else if(member_data[8] == 0)
            {
              $('#forfeit').prop("checked", false);
            }
            if(member_data[9] == 1)
            {
              $('#walkover').prop('checked', true);
            }
            else if(member_data[9] == 0)
            {
              $('#walkover').prop("checked", false);
            }
            if((i > 9) && (i <= 16))
            {
              $('#brk' + (i-9) + '').val(member_data[i]);
            }
            $('#game_score').val(member_data[17]);
          }
        }
        console.log("Show");
        $('#scores_modal').modal('show');
      },
      error: function() 
      {
        alert("There was an error. Try again please!");
      }
    });

    //}
  //});
});
*/

if(/iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(navigator.userAgent) || screen.availWidth < 480)
{
    if(tourn_size == 64)
    {
      setupColumnNavigation();
      // re-run the column update once more shortly after population likely finishes
      //setTimeout(function(){
        // safe re-run: re-collect groups and re-apply update
        //if (typeof setupColumnNavigation === 'function') {
        //  setupColumnNavigation(); // re-init (it .off() handlers)
        //}
      //}, 300); // adjust 200-600ms based on how long populate usually takes
    }
}
//setupColumnNavigation();

});

</script>

<!-- Add scores Modal -->
<!--<div class="modal fade" id="scores_modal" role="dialog">
  <div id='modal_size' class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body">
        <br>
        <center>
            <div id='scorestable_1'></div>
        </center>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>-->

<!-- Add scores Modal -->
<div class="modal fade" id="scores_modal" role="dialog">
  <div id='modal_size' class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<h4 class="modal-title">Player Score Entry</h4>-->
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <br>
        <center>
        <table class='table table-striped table-bordered'>
          <!--<tr>
            <td align='center'><font color="red"><h5>Breaks should be separated by a space.</h5></font></td>
          </tr>-->
          <tr>
            <td align='center'>
              <div id= 'scorestable_1'></div>
              <!--<table>
                <tr>
                  <th align='center'>&nbsp;</th>
                </tr>
                <tr>
                  <th align='center'>&nbsp;</th>
                </tr>
              </table>-->
              <!--<div id= 'scorestable_2'></div>-->
              </td>
            </tr>
          </td>
          </table>
          <!--<table class='table table-striped table-bordered'>
            <tr>
                <td colspan='8' align='center'><b>To be entered by the Referee.</b></td>
            </tr>
            <tr>
              <td align='center'>Table</td>
              <td align='center'>Round</td>
              <td colspan='2' align='center'>Match No.</td>
              <td colspan='2' align='center'>Start</td>
              <td colspan='2' align='center'>Finish</td>
            </tr>
            <tr>
              <td align='center'><input type='text' id='table_no' value=0 style='text-align: center; width:30px; height:20px'></td>
              <td align='center' id='round'></td> 
              <td colspan='2' align='center' id='match_no'></td>
              <td colspan='2' align='center'><input type='text' class='timepicker' id='start' value='' style='width:50px; height:20px;'></td>
              <td colspan='2' align='center'><input type='text' class='timepicker' id='finish' value='' style='width:50px; height:20px'></td>
            </tr>
            <tr>
              <td colspan='4' align='center' style='width:100px; height:20px'><font color="red">*</font> Referee - select this OR Roving Referee</td>
              <td colspan='4' align='center'><select id="referee" style='width:160px; height:20px'>
              <?php
              // get list of referees
              $query_referees = 'Select Concat(FirstName, " ",  LastName) as fullname FROM vbsa3364_vbsa2.members Where referee = 1 order by LastName';
              $result_referees = mysql_query($query_referees, $connvbsa) or die(mysql_error());
              echo("<option value=''>&nbsp;</option>");
              while($build_referees = $result_referees->fetch_assoc())
              {
                 echo("<option value='" . $build_referees['fullname'] . "'>" . $build_referees['fullname'] . "</option>");
              }
              ?>
              </select></td>
            </tr>
            <tr>
              <td colspan='4' align='center'><font color="red">*</font> Roving Referee <input type='checkbox' id='roving'></td>
              <td colspan='4' align='center'>Self Referee <input type='checkbox' id='self'></td>
            </tr>
            <tr>
              <td colspan='4' align='center'>Marker</td>
              <td colspan='4' align='center'><select id="marker" style='width:160px; height:20px'>
              <?php
              // get list of markers
              mysqli_data_seek($result_players, 0);
              echo("<option value=''>&nbsp;</option>");
              while($build_markers = $result_players->fetch_assoc())
              {
                 echo("<option value='" . $build_markers['FirstName'] . " " . $build_markers['LastName'] . "'>" . $build_markers['FirstName'] . " " . $build_markers['LastName'] . "</option>");
              }
              ?>
              </select></td>
            </tr>
          </table>
        <div><a class='btn btn-primary btn-xs' id='interim_save_modal_button'>Interim Save</a>
        <a class='btn btn-primary btn-xs' id='finished_save_modal_button'>Match Complete</a></div>-->
        </center>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() 
{
  $('#referee').on('change', function()
  {
    if($(this).val() == '')
    {
      $('#roving').prop('disabled', false);
    }
    else
    {
      $('#roving').prop('disabled', true);
    }
  });

  $('#roving').change(function()
  {
    if($("#roving").is(":checked"))
    {
      $('#referee').prop('disabled', true);
    }
    else
    {
      $('#referee').prop('disabled', false);
    }
  });
});
</script>  

<script type="text/javascript">

window.jsPDF = window.jspdf.jsPDF;

function generatePDF() {
  alert("Not yet implemented!");
  /*
    //const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      orientation: 'landscape'
    });
    var elementHTML = document.querySelector("#fixtureTable");

    doc.html(elementHTML, {
        callback: function(doc) {
            // Save the PDF
            doc.save('Tournament_<?= $tournament_id ?>.pdf');
        },
        x: 15,
        y: 15,
        width: 170, //target width in the PDF document
        windowWidth: 650 //window width in CSS pixels
    });         
  */       
}            
</script>        
</center>
</form>
</body>
</html>



