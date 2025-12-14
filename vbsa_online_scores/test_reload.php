<?php

include ("connection.inc");
include ("header.php");

$fullname = "Peter Johnson";
?>
<script type="text/javascript">
    
var methods = [
  "location.reload()",
  "history.go(0)",
  "location.href = location.href",
  "location.href = location.pathname",
  "location.replace(location.pathname)",
  "location.reload(false)"
];

var $body = $("body");
for (var i = 0; i < methods.length; ++i) {
  (function(cMethod) {
    $body.append($("<button>", {
      text: cMethod
    }).on("click", function() {
      eval(cMethod); // don't blame me for using eval
    }));
  })(methods[i]);
}

</script>
<style type="text/css">

button {
  background: #2ecc71;
  border: 0;
  color: white;
  font-weight: bold;
  font-family: "Monaco", monospace;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.5s ease;
  margin: 2px;
}
button:hover {
  background: #27ae60;
}


</style>
<center>
<table class='table dt-responsive nowrap display'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display'>
                <tr>
                    <td colspan=3 align=center><b>Test Page Reload</b></td>
                </tr>
                 <tr> 
                    <td colspan=3 align=center><a class='btn btn-primary btn-xs' href="javascript:;" >Close</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</center>
<?php

include("footer.php"); 

?>
