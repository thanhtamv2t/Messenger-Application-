<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Messenger</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css" integrity="sha256-2pUeJf+y0ltRPSbKOeJh09ipQFYxUdct5nTY6GAXswA=" crossorigin="anonymous" />
  <link rel="stylesheet" href="/css/app.css">  
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Nunito|Poppins" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/5.6.0/firebase-app.js"></script>
  <!-- Auth Component -->
  <script src="https://www.gstatic.com/firebasejs/5.6.0/firebase-auth.js"></script>
  <!-- Emoji -->
  <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
  <script>
    // Initialize Firebase
    var config = {
      apiKey: "AIzaSyCZIeCdPb7T6kUDUecd1ptWAC5V55UmWXk",
      authDomain: "doandvwlthd.firebaseapp.com",
      databaseURL: "https://doandvwlthd.firebaseio.com",
      projectId: "doandvwlthd",
      storageBucket: "doandvwlthd.appspot.com",
      messagingSenderId: "1048676937747"
    };
    firebase.initializeApp(config);    
  </script>
</head>
<body style="overflow: hidden !important; background: #fff;">
  <div id="app">
    <transition name="bounceLeft">
      <router-view></router-view>
    </transition>
  </div>
  <script src="/js/app.js"></script>
</body>
</html>