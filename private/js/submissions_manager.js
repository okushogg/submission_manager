var i = 1 ;
function addForm() {
  var add_select = document.createElement('select');
  add_select.id = 'class_' + i;
  add_select.name = 'class_id';

  var add_option = document.createElement('option');
    add_option.text = dataset
    // add_option.value = class;

  var parent = document.getElementById('class_select');
  parent.appendChild(add_select);
  add_select.appendChild(add_option);
  i++ ;
}