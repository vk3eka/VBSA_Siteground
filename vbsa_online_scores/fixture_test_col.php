<!doctype html>
<head>
<style>
  table{
    border-collapse: collapse;
  }
  th,td{
    border: 1px solid black;
    padding: 8px;
    text-align: center;
  }
  th{
    background: linear-gradient(to right, tomato, white, tomato);
    color: black;

  }

</style>
</head>
<body>
  <center>
  <h1>Swapping Columns in Table</h1>
  <hr>
  <table>

  <table id='mytable'>
  <thead>
    <tr>
      <th draggable='true' ondragstart='drag(event)'>
        Column - 1
      </th>
      <th draggable='true' ondragstart='drag(event)'>
        Column - 2
      </th>
      <th draggable='true' ondragstart='drag(event)'>
        Column - 3
      </th>
      <th draggable='true' ondragstart='drag(event)'>
        Column - 4
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>4</td>
    </tr>
    <tr>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>4</td>
    </tr>
    <tr>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>4</td>
    </tr>
  </tbody>
</table>
</center>

<script>
  var dragCol = null;
  function handleDragStart(e){
    console.log('Event: ', 'dragstart');
    dragCol = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);
  }

  function handleDragOver(e){
    console.log('Event: ', 'dragover');
   if(e.preventDefault){
    e.preventDefault();
   }
    e.dataTransfer.dropEffect = 'move';
    return false;
  }

  function handleDrop(e){
    console.log('Event: ', 'drop');
    if(e.stopPropagation){
      e.stopPropagation();
    }
    if(dragCol !== this){
      var sourceIndex = Array.from(dragCol.parentNode.children).indexOf(dragCol);
      var targetIndex = Array.from(this.parentNode.children).indexOf(this);

      var table = document.getElementById('mytable');
      var rows = table.rows;
      for(var i = 0; i < rows.length; i++){
        var sourceCell = rows[i].cells[sourceIndex];
        var targetCell = rows[i].cells[targetIndex];

        var tempHTML = sourceCell.innerHTML;
        sourceCell.innerHTML = targetCell.innerHTML;
        targetCell.innerHTML = tempHTML;

      }
    }
    return false;
  }

  var cols = document.querySelectorAll('th');
  [].forEach.call(cols, function(col) {
    col.addEventListener('dragstart', handleDragStart, false);
    col.addEventListener('dragover', handleDragOver, false);
    col.addEventListener('drop', handleDrop, false);
  });

</script>

</body>

</html>

