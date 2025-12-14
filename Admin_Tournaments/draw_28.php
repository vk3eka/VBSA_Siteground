    <script>

    if(col < 28)
    {
      var current_score = $.fn.get_score_1(id);
      var current_value = parseInt(current_score['value']);
      var next_score = $.fn.get_score_2(id, 1);
      var next_value = parseInt(next_score['value']);

      var row = current_score['row'];
      var next_col = (parseInt(current_score['col1'])+3);
      var name_col = (parseInt(current_score['col1'])-1);

      if(next_value != NaN)
      {
        if(!isNaN(current_value) && !isNaN(next_value)) 
        {
          var winner_name = '';
          var winner_player_row = 0;
          var loser_player_row = 0;
          if (current_value > next_value) 
          {
              winner_player_row = current_score['row'];
              loser_player_row = next_score['row'];
              winner_score = current_value;
              loser_score = next_value;
          } 
          else if (next_value > current_value) 
          {
              winner_player_row = next_score['row'];
              loser_player_row = current_score['row'];
              winner_score = next_value;
              loser_score = current_value;
          }
          var opposition_player;
          var opposition_row;
          var player_10 = $('#' + row + '_' + next_col + '_' + next_col).val();
          var player_20 = $('#' + (row+1) + '_' + next_col + '_' + next_col).val();
          var player_30 = $('#' + (row-1) + '_' + next_col + '_' + next_col).val();
          if((player_10 != undefined) && (player_10 != ''))
          {
            opposition_player = player_10;
            opposition_row = (current_score['row']);
          }
          if((player_20 != undefined) && (player_20 != ''))
          {
            opposition_player = player_20;
            opposition_row = (row+1);
          }
          if((player_30 != undefined) && (player_30 != ''))
          {
            opposition_player = player_30;
            opposition_row = (row-1);
          }
          var new_winner_row;
          if (winner_player_row > 0) 
          {
            winner_name = $('#' + winner_player_row + '_' + name_col + '_' + name_col).val();
            loser_name = $('#' + loser_player_row + '_' + name_col + '_' + name_col).val();
            if(winner_player_row === opposition_row)
            {
              if($('#' + (loser_player_row) + '_' + next_col.length > 0)
              {
                $('#' + (loser_player_row) + '_' + next_col + '_' + next_col).val(winner_name);
                var new_winner_row = loser_player_row;
              }
              else if($('#' + (winner_player_row) + '_' + next_col.length > 0)
              {
                $('#' + (winner_player_row) + '_' + next_col + '_' + next_col).val(winner_name);
                var new_winner_row = winner_player_row;
              }
            }
            else if(loser_player_row === opposition_row)
            {
              if($('#' + (winner_player_row) + '_' + next_col.length > 0)
              {
                $('#' + (winner_player_row) + '_' + next_col + '_' + next_col).val(winner_name);
                var new_winner_row = winner_player_row;
              }
              else if($('#' + (loser_player_row) + '_' + next_col.length > 0)
              {
                $('#' + (loser_player_row) + '_' + next_col + '_' + next_col).val(winner_name);
                var new_winner_row = loser_player_row;
              }
            }
          }
        }
        var new_winner_col = (col+3);
        var player_1 = $('#' + (winner_player_row) + '_' + next_col + '_' + next_col).val();
        var player_2 = $('#' + (loser_player_row) + '_' + next_col + '_' + next_col).val();

        console.log("Winner " + winner_name);
        console.log("Opposition " + loser_name);

        console.log("Player 1 " + player_1);
        console.log("Player 2 " + player_2);

        var move_to_row = 4; // need to initialise
        var move_to_col = 5; // need to initialise

        if(player_1 == '')
        {
          console.log("Player 1 is empty");
          console.log("Move to Row " + winner_player_row);
        }
        if(player_2 == '')
        {
          console.log("Player 2 is empty");
          console.log("Move to Row " + loser_player_row);
        }
      }
    }

    if(!isNaN(current_value) && !isNaN(next_value)) 
    {
      $.ajax({
        url:"save_match_score.php?tourn_id=" + tourn_id + "&player_1=" + player_1 + "&player_2=" + player_2 + "&winner_row_no=" + winner_player_row + "&opp_row_no=" + loser_player_row + "&winner_score=" + winner_score + "&opp_score=" + loser_score + "&common_col=" + (col) + "&winner_name=" + winner_name + "&move_to_row=" + new_winner_row + "&move_to_col=" + new_winner_col,
        method: 'GET',
        success:function(response)
        {
          console.log(response);
        },
      });
    }

  </script>