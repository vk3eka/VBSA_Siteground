
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Modal + Timepicker Test</title>

  <!-- Bootstrap CSS (use v4 here, v5 also works) -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- jQuery Timepicker CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

  <style>
    /* ✅ Force timepicker dropdown above Bootstrap modal */
    .ui-timepicker-container {
      z-index: 99999 !important;
    }
    .modal .ui-timepicker-container {
      position: absolute !important;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <button class="btn btn-primary" data-toggle="modal" data-target="#testModal">
    Open Modal
  </button>
</div>

<!-- Modal -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Timepicker Test</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control timepicker" placeholder="Pick a time">
      </div>
    </div>
  </div>
</div>

<!-- jQuery + Bootstrap JS + Timepicker -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script>
$(function() {
  $('.timepicker').timepicker({
    timeFormat: 'H:mm',
    interval: 15,
    minTime: '8',
    dynamic: false,
    dropdown: true,
    scrollbar: true,
    appendTo: function() {
      // ✅ attach dropdown to modal-content if inside a modal
      let $modal = $(this).closest('.modal-content');
      return $modal.length ? $modal : 'body';
    }
  });
});
</script>

</body>
</html>
