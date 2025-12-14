I have the following code that is part of a tournament draw web page.

Add a score (integer) to the input box to the right of the first name. Next add a score to the next score input box underneath the first.

If the score in the first box is greater than the second, the winners name is input to the empty box in the second column.

Similarly, if the score in the second box is greater than the first, the winners name is input to the empty box in the second column.

All works well. However, if I change the score in the first box (1) to something less than box 2, the winners name is added to the top box, not the bottom box. Similary if the bottom box score is increased the name is input in the bottom box.

I need to keep the original name (Luv Barco) in place and only change the name in the bottom box however many times I change the scores.

In other cells the name (Luv Barco) or similar may be in the bottom box and the winners name should go in the top box.


How can I do that?

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<table align='center' border='1'>
<tr>
<td colspan='10'>&nbsp;</td>
<tr>
<td id='1_1'>
</td>
<td nowrap><input type='text' class='player' id='1_3_3' value=''></td>
<td id='1_4'><input type='text' class='enter_score' size='3px' id='1_4_4' value=''></td>
<td id='1_5'>
</td>
<td nowrap><input type='text' class='player' id='1_7_7' value=''></td>
<td id='1_8'><input type='text' class='enter_score' size='3px' id='1_8_8' value=''></td>
</tr>
</table>

<table align='center' border='1'>
<td id='2_1'>
</td>
<td nowrap><input type='text' class='player' id='2_3_3' value=''></td>
<td id='2_4'><input type='text' class='enter_score' size='3px' id='2_4_4' value=''></td>
<td id='2_5'>
</td>
<td nowrap><input type='text' class='player' id='2_7_7' value=''></td>
<td id='2_8'><input type='text' class='enter_score' size='3px' id='2_8_8' value=''></td>
</tr>
<tr>
<td colspan='10'>&nbsp;</td>
<tr>
</table>
<script>
document.getElementById('1_3_3').value = 'Adam Hung';
document.getElementById('1_7_7').value = 'Luv Barco';
document.getElementById('2_3_3').value = 'Ronnie Biggs';
document.getElementById('2_7_7').value = '';

$(document).ready(function() 
{
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

        $.fn.get_score_2 = function (str) 
        {
            var subStr = '_';
            var first = (str.split(subStr, 1).join(subStr).length);
            var row = parseInt(str.substring(0, first));
            var column = str.substring(first+1);
            var col_split = column.split('_');
            var col_1 = col_split[0];
            var col_2 = col_split[1];
            var score_detail= [];
            var score_value_1 = $('#' + (row+1) + "_" + column).val();
            var score_value_3 = $('#' + (row-1) + "_" + column).val();
            if(score_value_1 == undefined)
            {
              var score_value = $('#' + (row-1) + "_" + column).val();
              score_detail['row'] = (row-1);
            }
            else if(score_value_3 == undefined)
            {
              var score_value = $('#' + (row+1) + "_" + column).val();
              score_detail['row'] = (row+1);
            }
            score_detail['value'] = score_value;
            score_detail['col1'] = col_1;
            score_detail['col2'] = col_2;
            return score_detail;
        }

        $('.enter_score').focusout(function() 
        {
            var current_score = '';
            var next_score = '';
            var winner = '';
            var id = $(this).attr('id');
            var subStr = '_';
            var first = (id.split(subStr, 1).join(subStr).length);
            var row = parseInt(id.substring(0, first));
            var col = parseInt(id.substring(first+1));
            current_score = $.fn.get_score_1(id);
            current_value = parseInt(current_score['value']);
            next_score = $.fn.get_score_2(id);
            next_value = parseInt(next_score['value']);
            var player_1 = $('#' + (current_score['row']) + '_' + parseInt(current_score['col1']-1) + '_' + parseInt(current_score['col1']-1)).val();
            var player_2 = $('#' + (current_score['row']+1) + '_' + parseInt(current_score['col1']-1) + '_' + parseInt(current_score['col1']-1)).val();
            var player_3 = $('#' + (current_score['row']-1) + '_' + parseInt(current_score['col1']-1) + '_' + parseInt(current_score['col1']-1)).val();
            if((current_value != '') && (next_value != ''))
            {
              if(current_value > next_value)
              {
                winner = $('#' + (current_score['row']) + '_' + parseInt(current_score['col1']-1) + '_' + parseInt(current_score['col1']-1)).val();

                // Always place the winner into the *lower* box of the next column
                $('#' + (Math.max(current_score['row'], next_score['row'])) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner);

                /*
                if($('#' + (row) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val() != undefined)
                {
                  $('#' + (row) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner);
                }
                else if($('#' + (row+1) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val() != undefined)
                {
                  $('#' + (row+1) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner);
                }
                */
              }
              if(current_value < next_value)
              {
                winner  = $('#' + (next_score['row']) + '_' + parseInt(next_score['col1']-1) + '_' + parseInt(next_score['col1']-1)).val();

                // Always place the winner into the *lower* box of the next column
                $('#' + (Math.max(current_score['row'], next_score['row'])) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner);

                /*
                if($('#' + (row) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val() != undefined) 
                {
                  $('#' + (row) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner)
                }
                else if($('#' + (row+1) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val() != undefined) 
                {
                  $('#' + (row+1) + '_' + parseInt(col+3) + '_' + parseInt(col+3)).val(winner)
                }
                */
              }
            }
        });

});

</script>