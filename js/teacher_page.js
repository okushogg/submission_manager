function myEnter(){
  myPassWord=prompt("パスワードを入力してください。");
  if ( myPassWord == "password" ){
      window.sessionStorage.setItem('teacher', true);
      location.href="../teacher/log_in.php";
  }else{
      alert( "パスワードが違います。" );
  }
}