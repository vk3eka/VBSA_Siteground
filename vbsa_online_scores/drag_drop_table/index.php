<?php

  require('../connection.inc');
  include('../header_fixture.php'); 

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Drag 'n Drop</title>
    <!--<link rel="stylesheet" href="style.css" />-->
    <script
      src="https://kit.fontawesome.com/3da1a747b2.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <h1>Test Table</h1>
    <table class='table table-striped table-bordered draggable-list' id='draggable-list' width='70%'>
      <thead>
          <th>ID</th>
          <th>Name</th>
          <th>Player No.</th>
          <th>Access</th>
      </thead>
      <tbody>
<?php
$sql = "SELECT * FROM tbl_authorise";
$users = $dbcnx_client->query($sql);
$i = 0;
while($user = $users->fetch_assoc())
{
?>
    <tr data-index='<?= $i ?>' class='draggable' draggable='true'>
      <td class='number'><?= $i ?></td>
      <td class='person-name'><?php echo $user['Name'] ?></td>
      <td class='index'><?php echo $user['PlayerNo'] ?></i></td>
      <td><?php echo $user['Access'] ?></td>
    </tr>
<?php 
$i++;
} 
?>
      </tbody>
    </table>
<script>
const draggable_list = document.getElementById('draggable-list');
//const check = document.getElementById('check');

/*
const richestPeople = [
  'Jeff Bezos',
  'Bill Gates',
  'Warren Buffett',
  'Bernard Arnault',
  'Carlos Slim Helu',
  'Amancio Ortega',
  'Larry Ellison',
  'Mark Zuckerberg',
  'Michael Bloomberg',
  'Larry Page'
];
*/
// Store listitems
const listItems = [];

let dragStartIndex;

//createList();

// Insert list items into DOM
/*
function createList() {
  [...richestPeople]
    .map(a => ({ value: a, sort: Math.random() }))
    .sort((a, b) => a.sort - b.sort)
    .map(a => a.value)
    .forEach((person, index) => {
      const listItem = document.createElement('li');

      listItem.setAttribute('data-index', index);

      listItem.innerHTML = `
        <span class="number">${index + 1}</span>
        <div class="draggable" draggable="true">
          <p class="person-name">${person}</p>
          <i class="fas fa-grip-lines"></i>
        </div>
      `;

      listItems.push(listItem);

      draggable_list.appendChild(listItem);
    });

  addEventListeners();
}
*/

addEventListeners();

var dragCol = null;

function dragStart() {
  console.log('Event: ', 'dragstart');
  dragStartIndex = +this.closest('tr').getAttribute('data-index');
}

function dragEnter() {
  console.log('Event: ', 'dragenter');
  this.classList.add('over');
}

function dragLeave() {
  console.log('Event: ', 'dragleave');
  this.classList.remove('over');
}

function dragOver(e) {
  console.log('Event: ', 'dragover');
  e.preventDefault();
}

/*
function dragDrop(e) {
  console.log('Event: ', 'drop');
  if(e.stopPropagation){
      e.stopPropagation();
    }
    if(dragCol !== this){
      var sourceIndex = Array.from(dragCol.parentNode.children).indexOf(dragCol);
      var targetIndex = Array.from(this.parentNode.children).indexOf(this);

      var table = document.getElementById('draggable-list');
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
*/


function dragDrop() {
  console.log('Event: ', 'drop');
  const dragEndIndex = +this.getAttribute('data-index');
  console.log('Start: ', dragStartIndex);
  console.log('End: ', dragEndIndex);
  swapItems(dragStartIndex, dragEndIndex);

  this.classList.remove('over');
}

// Swap list items that are drag and drop
function swapItems(fromIndex, toIndex) {

  var table = document.getElementById('draggable-list');
  var rows = table.rows;
  for(var i = 0; i < rows.length; i++){
    var sourceCell = rows[i].cells[fromIndex];
    var targetCell = rows[i].cells[toIndex];
    console.log('Source: ', sourceCell);
    console.log('Target: ', targetCell);

    var tempHTML = sourceCell.innerHTML;
    sourceCell.innerHTML = targetCell.innerHTML;
    targetCell.innerHTML = tempHTML;

  }


  //const itemOne = fromIndex.querySelector('.draggable');
  //const itemTwo = toIndex.querySelector('.draggable');
  const ElementOne = document.querySelector('.draggable').value;
  const ElementTwo = document.querySelector('.draggable').value;
  console.log('Element 1: ', ElementOne);
  console.log('Element 2: ', ElementTwo);
  itemOne = ElementOne[fromIndex];
  itemTwo = ElementOne[toIndex];
  console.log('Item 1: ', itemOne);
  console.log('Item 2: ', itemTwo);
  //listItems[fromIndex].appendChild(itemTwo);
  //listItems[toIndex].appendChild(itemOne);
}
/*
// Check the order of list items
function checkOrder() {
  listItems.forEach((listItem, index) => {
    const personName = listItem.querySelector('.draggable').innerText.trim();

    if (personName !== richestPeople[index]) {
      listItem.classList.add('wrong');
    } else {
      listItem.classList.remove('wrong');
      listItem.classList.add('right');
    }
  });
}
*/


function addEventListeners() {
  const draggables = document.querySelectorAll('.draggable');
  const dragListItems = document.querySelectorAll('.draggable-list tr');

  draggables.forEach(draggable => {
    draggable.addEventListener('dragstart', dragStart);
  });

  dragListItems.forEach(item => {
    item.addEventListener('dragover', dragOver);
    item.addEventListener('drop', dragDrop);
    item.addEventListener('dragenter', dragEnter);
    item.addEventListener('dragleave', dragLeave);
  });
}

//check.addEventListener('click', checkOrder);

</script>
  </body>
</html>