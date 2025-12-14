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
<td id='7_18'>
</td>
<td nowrap><input type='text' class='player' id='7_19_19' value=''></td>
<td id='7_20'><input type='text' class='enter_score' size='3px' id='7_20_20' value=''></td>
<td id='7_21'>
</td>
<td nowrap><input type='text' class='player' id='7_23_23' value=''></td>
<td id='7_23'><input type='text' class='enter_score' size='3px' id='7_24_24' value=''></td>
</tr>
</table>

<table align='center' border='1'>
<td id='8_18'>
</td>
<td nowrap><input type='text' class='player' id='8_19_19' value=''></td>
<td id='8_20'><input type='text' class='enter_score' size='3px' id='8_20_20' value=''></td>
<td id='8_21'>
</td>
<td nowrap><input type='text' class='player' id='8_23_23' value=''></td>
<td id='8_23'><input type='text' class='enter_score' size='3px' id='8_24_24' value=''></td>
</tr>
<tr>
<td colspan='10'>&nbsp;</td>
<tr>
</table>

<table align='center' border='1'>
<td id='10_18'>
</td>
<td nowrap><input type='text' class='player' id='10_19_19' value=''></td>
<td id='10_20'><input type='text' class='enter_score' size='3px' id='10_20_20' value=''></td>
<td id='10_21'>
</td>
<td nowrap><input type='text' class='player' id='10_23_23' value=''></td>
<td id='10_23'><input type='text' class='enter_score' size='3px' id='10_24_24' value=''></td>
</tr>
</table>

<table align='center' border='1'>
<td id='11_18'>
</td>
<td nowrap><input type='text' class='player' id='11_19_19' value=''></td>
<td id='11_20'><input type='text' class='enter_score' size='3px' id='11_20_20' value=''></td>
<td id='11_21'>
</td>
<td nowrap><input type='text' class='player' id='11_23_23' value=''></td>
<td id='11_23'><input type='text' class='enter_score' size='3px' id='11_24_24' value=''></td>
</tr>
<tr>
<td colspan='10'>&nbsp;</td>
<tr>
</table>

<script>
document.getElementById('7_19_19').value = 'Adam Hung';
document.getElementById('7_23_23').value = 'Luv Barco';
document.getElementById('8_19_19').value = 'Ronnie Biggs';
document.getElementById('8_23_23').value = '';

document.getElementById('10_19_19').value = 'Ray Brooks';
document.getElementById('10_23_23').value = '';
document.getElementById('11_19_19').value = 'Fred Bloggs';
document.getElementById('11_23_23').value = 'Adam West';

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

            console.log(player_1);
            console.log(player_2);
            console.log(player_3);


            if (!isNaN(current_value) && !isNaN(next_value)) {
                var winner_name = '';
                var winner_player_row = 0;

                if (current_value > next_value) {
                    winner_player_row = current_score['row'];
                } else if (next_value > current_value) {
                    winner_player_row = next_score['row'];
                }
                console.log(winner_player_row);
                //console.log(player_2);
                if (winner_player_row > 0) {
                    winner_name = $('#' + winner_player_row + '_' + (parseInt(current_score['col1']) - 1) + '_' + (parseInt(current_score['col1']) - 1)).val();

                    var r1 = Math.min(current_score['row'], next_score['row']);
                    var r2 = Math.max(current_score['row'], next_score['row']);
                    var dest_row;

                    console.log(r1);
                    console.log(r2);

                    // Determine the destination row based on the match's position in the bracket.
                    // This implements the alternating pattern you described:
                    // The 1st match (e.g., rows 1&2) sends the winner to the bottom slot.
                    // The 2nd match (e.g., rows 3&4) sends the winner to the top slot.
                    var match_index = Math.ceil(r1 / 2);
                    if (match_index % 2 === 1) { // 1st, 3rd, 5th match
                        dest_row = r1; // Bottom slot
                    } else { // 2nd, 4th, 6th match
                        dest_row = r2; // Top slot
                    }

                    var dest_col_part = parseInt(col) + 3;
                    var dest_id = dest_row + '_' + dest_col_part + '_' + dest_col_part;
                    $('#' + dest_id).val(winner_name);
                }
            }
        });

});

</script>