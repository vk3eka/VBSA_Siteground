<script>
var id = $(this).attr('id');
var subStr = '_';
var first = (id.split(subStr, 1).join(subStr).length);
var row = parseInt(id.substring(0, first));
var col = parseInt(id.substring(first+1));
var tourn_id = <?= $tourn_id ?>;
var tourn_size = <?= $tourn_size ?>;
var move_to_row = 0;
var move_to_col = 0;
var winner_row = '';
var winner_name = '';
var loser_name = '';

var score_array = $.fn.get_next_id(id, 1, 'SS');
var col = score_array['column'];
var row_1 = score_array['current_row'];
var row_2 = score_array['next_row'];
player_name_1 = score_array['current_name'];
player_name_2 = score_array['next_name'];

var current_score = $.fn.get_score_1(id);
var game_score_1 = parseInt(current_score['value']);

var next_score = $.fn.get_score_2(id, 1);
var game_score_2 = parseInt(next_score['value']);

if(row_1 > row_2)
{
  var top_row = row_2;
  var bottom_row = row_1;
}
else
{
  var top_row = row_1;
  var bottom_row = row_2;
}
var move_to_col = (col+2);

if(col < 16)
{
  var player_1 = $('#' + row_1 + '_' + (col) + '_' + (col)).val();
  var player_2 = $('#' + row_2 + '_' + (col) + '_' + (col)).val();

  // get winners name and move to row
  if (game_score_1 > game_score_2) 
  {
      var winner_player_row = top_row;
      var loser_player_row = bottom_row;
      var winner_score = game_score_1;
      var loser_score = game_score_2;

      winner_name = player_1;
      winner_row = winner_player_row;
  } 
  else if (game_score_2 > game_score_1) 
  {
      var winner_player_row = bottom_row;
      var loser_player_row = top_row;
      var winner_score = game_score_2;
      var loser_score = game_score_1;

      winner_name = player_2;
      winner_row = winner_player_row;
  }
  move_to_row = bottom_row;

  if(col == 13)
  {
    if((top_row == 10) || (top_row == 20) || (top_row == 31) || (top_row == 41) || (top_row == 54) || (top_row == 64) || (top_row == 75) || (top_row == 85))
    {
      move_to_row = top_row;
    }
  }
}

// add match scores to tourn page
$('#' + row_1 + '_' + (col+1) + '_' + (col+1)).val(game_score_1);
$('#' + row_2 + '_' + (col+1) + '_' + (col+1)).val(game_score_2);

// add winner name to tourn page next round
$('#' + (move_to_row) + '_' + (col+2) + '_' + (col+2)).val(winner_name);

new_winner_row = move_to_row;
new_winner_col = move_to_col;

// save here
console.log("1 " + player_1);
console.log("2 " + player_2);
console.log("3 " + winner_player_row);
console.log("4 " + loser_player_row);
console.log("5 " + winner_score);
console.log("6 " + loser_score);
console.log("7 " + col);
console.log("8 " + winner_name);
console.log("9 " + new_winner_row);
console.log("10 " + new_winner_col);

if(!isNaN(current_value) && !isNaN(next_value)) 
{
  $.ajax({
    url:"save_match_score.php?tourn_id=" + tourn_id + "&player_1=" + player_1 + "&player_2=" + player_2 + "&winner_row_no=" + winner_player_row + "&opp_row_no=" + loser_player_row + "&winner_score=" + winner_score + "&opp_score=" + loser_score + "&common_col=" + (col) + "&winner_name=" + winner_name + "&move_to_row=" + new_winner_row + "&move_to_col=" + new_winner_col,
    method: 'GET',
    success:function(response)
    {
      console.log(response);
      //location.reload(true);
    },
  });
}
/////


    if(col < 16)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);

      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);

      var next_id = $.fn.get_next_id(id, 1, 'ES');

      var current_name = $.fn.get_player_1(id);
      var next_name = $.fn.get_player_2(id, 1);

      var current_row = current_score['row'];
      var current_col = current_score['col1'];

      var next_row = next_id['next_row'];
      var next_col = next_id['column'];

      var next_round_player_1 = $('#' + (current_score['row']) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val();
      var next_round_player_2 = $('#' + (next_id['next_row']) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val();

      if(next_value != NaN)
      {
          var winner_name;
          var loser_name;
          var winner_player_row;
          var loser_player_row;
          var winner_score;
          var loser_score;

          if(current_row < next_row)
          {
            var top_row = next_row;
            var bottom_row = current_row;
          }
          else
          {
            var top_row = current_row;
            var bottom_row = next_row;
          }

          console.log("Top Row " + top_row);
          console.log("Bottom Row " + bottom_row);
          if(current_value > next_value)
          {
            winner_name = current_name;
            loser_name = next_name;
            winner_player_row = top_row;
            loser_player_row = bottom_row;
            winner_score = current_value;
            loser_score = next_value;
          }

          if(current_value < next_value)
          {
            winner_name = next_name;
            loser_name = current_name;
            winner_player_row = bottom_row;
            loser_player_row = top_row;
            winner_score = next_value;
            loser_score = current_value;
          }
          //console.log("Winner Row " + winner_player_row);
          //console.log("Loser Row " + loser_player_row);
          
          move_to_row = bottom_row;
          console.log(col);
          if(col == 14)
          {
            if((top_row == 10) || (top_row == 20) || (top_row == 31) || (top_row == 41) || (top_row == 54) || (top_row == 64) || (top_row == 75) || (top_row == 85))
            {
              move_to_row = top_row;
            }
          }
          var current_row = current_score['row'];
          var col = current_score['col1'];
          var new_winner_row = move_to_row;
          var new_winner_col = (parseInt(current_score['col1'])+1);
          //var player_1 = $('#' + (winner_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
          //var player_2 = $('#' + (loser_player_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
          var player_1 = $('#' + (top_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
          var player_2 = $('#' + (bottom_row) + '_' + (parseInt(current_score['col1'])-1) + '_' + (parseInt(current_score['col1'])-1)).val();
          console.log("Player 1 " + player_1);
          console.log("Player 2 " + player_2);
          //if(player_1 === 'Bye')
          //{
          //  $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
          //}
          $('#' + (move_to_row) + '_' + (parseInt(current_score['col1'])+1) + '_' + (parseInt(current_score['col1'])+1)).val(winner_name);
        }
    }
    


    
</script>
