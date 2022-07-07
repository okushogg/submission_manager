<html>

<head>
  <title>JavaScript select</title>
  <script type="text/javascript">
    function dispType() {

      var f = document.fm.type;

      for (var i = 0; i < f.options.length; i++) {
        if (f.options[i].selected) {
          alert('選択した血液型：' + f.options[i].value);
        }
      }
    }

    function createSelectBox() {
      var classes = [{
          val: 1,
          txt: "1-A"
        },
        {
          val: 2,
          txt: "1-B"
        },
        {
          val: 3,
          txt: "2-A"
        },
        {
          val: 4,
          txt: "2-B"
        },
        {
          val: 5,
          txt: "3-A"
        },
        {
          val: 6,
          txt: "3-B"
        }
      ];


      for (var i = 0; i < classes.length; i++) {
        let op = document.createElement("option");
        op.value = classes[i].val;
        op.text = classes[i].txt;
        document.getElementById('sel1').appendChild(op);
      }
    }
  </script>
</head>

<body>


  <select id="sel1"></select>
    <input type="button" value=" ボタン " onclick="createSelectBox();" />
</body>

</html>