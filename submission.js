var a = 1 ;
function addForm($classes_info) {
  var select_class = document.createElement('select');
  select_class.id = 'class';
  select_class.name = 'class_id';
  // for(var i = 0; i<$classes_info.length; i++) {
  //   let op = document.createElement("option");
  //   op.value = $classes_info[i].id;
  //   op.text = $classes_info[i].grade;
  //   document.getElementById('class').appendChild(op);
  // }
  var parent = document.getElementById('class_select');
  parent.appendChild(select_class);
  i++ ;
}
