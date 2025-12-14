<?php 
include('connection.inc');
include('header.php'); 
?>
<!--Content--> 
<div class="">
  <div class="page-title"></div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="x_content"> 
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <?php
        $players = array();
        $sql = "Select FirstName, Surname from tbl_players Order By Surname";
        $result_players = $dbcnx_client->query($sql);
        echo($sql);
        $num_rows = $result_players->num_rows;
        if ($num_rows != 0) 
        {
          while($build_data = $result_players->fetch_assoc()) 
          {
            $players[] = $build_data['Surname'] . " " . $build_data['FirstName']; 
          }
          $player_data = json_encode($players);
        }
        ?>
        <script>
        $(document).ready(function() 
        { 
          var availableTags = <?php echo $player_data; ?>;
          $("#tags").autocomplete({
            source:  availableTags,
            appendTo: "#autocompleteAppendToMe"
          });
        });
        </script>
        <br>
        <div class="text-center ui-widget">
          <label for="tags">Select Player from Dropdown: </label>
          <input id="tags">
          <div id='autocompleteAppendToMe'></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>

<!--
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
  $( function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#tags" ).autocomplete({
      source: availableTags,
      appendTo: "#autocompleteAppendToMe"
    });
  } );
  </script>
</head>
<body>
 
<div class="ui-widget">
  <label for="tags">Tags: </label>
  <input id="tags">
  <div id='autocompleteAppendToMe'></div>
</div>
 
 
</body>
</html>
-->