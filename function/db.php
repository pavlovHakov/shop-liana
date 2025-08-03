 <?php
   $mysqli = new mysqli("localhost", "oleg", "shop", "shop");
   if ($mysqli->connect_error) {
      die("Ошибка подключения: " . $mysqli->connect_error);
   }
   $mysqli->set_charset("utf8");
