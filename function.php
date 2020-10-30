<?php



  //Подготовка сообщения 
  function set_flash_message($name, $message){
    $_SESSION[$name] = $message;
  };

  function display_flash_message($name){ 
    if (isset($_SESSION[$name])) {
      echo "<div class=\"alert alert-$name\">$_SESSION[$name]</div>";
      unset($_SESSION[$name]);
    }
  };

  function redirect_to($path){
    header("Location: $path");
  };
  ////////////////////////// 

  /*
    Проверяем если такой email БД 
    return array [email, pass]
  */
  function if_the_user($email, $pass, $pdo){
    $prepare = $pdo->prepare("SELECT `email`,`pass` FROM `users` WHERE `email` = :email ");
    $prepare->execute(['email' => $email]);
    return $prepare->fetch(PDO::FETCH_ASSOC); 
  }





  //Возвращает role пользователя по email
  function role_verification($email,$pdo){
    $prepare = $pdo->prepare("SELECT `role` FROM `users` WHERE `email` = ?");
    $prepare->execute([$email]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }




    //Проверяем сушествует ли такой пользыватель, если да то return email и password
  function get_user_by_email ($email, $pdo){
    $prepare = $pdo->prepare("SELECT email, pass FROM `users` WHERE `email` = :email");
    $prepare->execute(['email' => $email]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  };

  // Добавляет пользователя return id user
  function add_user($email, $pass, $pdo){
    $pass =  password_hash($pass, PASSWORD_DEFAULT);
    $prepare = $pdo->prepare("INSERT INTO `users`(`email`, `pass`) VALUES(:email, :pass)");
    $prepare->execute(['email' => $email, 'pass' => $pass]);
    return $pdo->lastInsertId();
  };
  // Общая информация
  function user_info($name, $work, $tel, $address, $new_user_id, $pdo){
    $prepare = $pdo->prepare("INSERT INTO `user_info`(`name`, `work`, `tel`, `address`, `id_user`) VALUES(?,?,?,?,?)");
    $prepare->execute([$name, $work, $tel, $address, $new_user_id]);
  };
  // MEDIA
  function user_media($status, $img, $vk, $telegram, $insta, $new_user_id, $pdo){
    $prepare = $pdo->prepare("INSERT INTO `media`(`status`, `img`, `vk`, `telegram`, `insta`, `id_user`) VALUES(?,?,?,?,?,?)");
    $prepare->execute([$status, $img, $vk, $telegram, $insta, $new_user_id]);
  };

