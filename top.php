<?php
$classes_info = array(
  '0' => array(
    'id' => "1",
    'year' => "2022",
    'grade' => "1",
    'class' => "A"
  ),

  '1' => array(
    'id' => "2",
    'year' => "2022",
    'grade' => "1",
    'class' => "B"
  ),

  '2' => array(
    'id' => "3",
    'year' => "2022",
    'grade' => "2",
    'class' => "A"
  ),

  '3' => array(
    'id' => "4",
    'year' => "2022",
    'grade' => "2",
    'class' => "B"
  ),

  '4' => array(
    'id' => "5",
    'year' => "2022",
    'grade' => "3",
    'class' => "A"
  ),

  '5' => array(
    'id' => "6",
    'year' => "2022",
    'grade' => "3",
    'class' => "B"
  )

);



  foreach($classes_info as $a){
    $all_classes_array = array(
      $a['grade'] => array(
          'id' =>$a['id'],
          'year' =>$a['year'],
          'grade' =>$a['grade'],
          'class' =>$a['class']
        )
      );
  }

  print_r($all_classes_array);