<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
          jsPDF - Create PDFs with HTML5 JavaScript Library
      </title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
 
<body>
    <h2 style="color:green">
          GeeksforGeeks
      </h2>
    <h3>
          Generate PDF file using jsPDF library
      </h3>
    <div class="container">                    
        <input type="button" value="Create PDF" onclick="generatePDF()">
    </div> 
 
    <script type="text/javascript">
        function generatePDF() {
            //const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            var elementHTML = document.querySelector("#contnet");

            doc.html(elementHTML, {
                callback: function(doc) {
                    // Save the PDF
                    doc.save('sample-document.pdf');
                },
                x: 15,
                y: 15,
                width: 170, //target width in the PDF document
                windowWidth: 650 //window width in CSS pixels
            });                
        }            
    </script>        
</body>
</html>