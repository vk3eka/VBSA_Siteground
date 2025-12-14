<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test Modal Operation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container"> 
  <div>
    <h1 align='center'>Test Modal Operation</h1> 
  </div>  
</div>
<script>
$(document).ready(function()
{
  $.fn.displayplayers = function () {
        var output = "";
        output += ("<table class='table table-striped table-bordered dt-responsive nowrap display fetched-data' width='100%'>");
        output += ("<thead>");
        output += ("<tr>");
        output += ("<th></th>");
        output += ("<th>1</th>");
        output += ("<th>2</th>");
        output += ("<th>3</th>");
        output += ("</thead>");
        output += ("<tbody>");
        output += ("</tr>");
        output += ("<tr>");
        output += ("<td>Item 1</td>");
        output += ('<td><input type="radio" name="row-1" data-col="1"></td>');
        output += ('<td><input type="radio" name="row-1" data-col="2"></td>');
        output += ('<td><input type="radio" name="row-1" data-col="3"></td>');
        output += ("</tr>");
        output += ("<tr>");
        output += ("<td>Item 2</td>");
        output += ('<td><input type="radio" name="row-2" data-col="1"></td>');
        output += ('<td><input type="radio" name="row-2" data-col="2"></td>');
        output += ('<td><input type="radio" name="row-2" data-col="3"></td>');
        output += ("</tr>");
        output += ("<tr>");
        output += ("<td>Item 3</td>");
        output += ('<td><input type="radio" name="row-3" data-col="1"></td>');
        output += ('<td><input type="radio" name="row-3" data-col="2"></td>');
        output += ('<td><input type="radio" name="row-3" data-col="3"></td>');
        output += ("</tr>");
        output += ("</tbody>");
        output += ("</table>");
        $($.parseHTML(output)).appendTo('#add');
    //}

        var col, el;
        $("input[type=radio]").click(function() {
            el = $(this);
            col = el.data("col");
            $("input[data-col=" + col + "]").prop("checked", false);
            el.prop("checked", true);
        });
    }

    $('#modal_ok').click(function(){
    $.fn.displayplayers();
    $('#myModal').modal('show');
  });

});
</script>
<center>
<table class='table table-striped table-bordered dt-responsive' style='width:500px'>
<tr>
    <th></th>
    <th>1</th>
    <th>2</th>
    <th>3</th>
</tr>
<tr>
    <td>Item 1</td>
    <td><input type="radio" name="row-1" data-col="1"></td>
    <td><input type="radio" name="row-1" data-col="2"></td>
    <td><input type="radio" name="row-1" data-col="3"></td>
</tr>
<tr>
    <td>Item 2</td>
    <td><input type="radio" name="row-2" data-col="1"></td>
    <td><input type="radio" name="row-2" data-col="2"></td>
    <td><input type="radio" name="row-2" data-col="3"></td>
</tr>
<tr>
    <td>Item 3</td>
    <td><input type="radio" name="row-3" data-col="1"></td>
    <td><input type="radio" name="row-3" data-col="2"></td>
    <td><input type="radio" name="row-3" data-col="3"></td>
</tr>
</table>
<div class='text-center' colspan='4'><a class='btn btn-primary btn-xs' id='modal_ok'>Open Modal</a></div>
</center>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Test Modal</h4>
            </div>
            <div class="modal-body">
              <div id='add'></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>