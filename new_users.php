<?php
  session_start();
  require_once('sql.php');
  require_once('../function.php');

  $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
  $work = trim(filter_var($_POST['intro'], FILTER_SANITIZE_STRING));
  $tel = trim(filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT));
  $address = trim(filter_var($_POST['address'], FILTER_SANITIZE_STRING));
  $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
  $pass = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
  $status = trim(filter_var($_POST['status'], FILTER_SANITIZE_STRING));
  $vk = trim(filter_var($_POST['vk'], FILTER_SANITIZE_URL));
  $telegram = trim(filter_var($_POST['telegram'], FILTER_SANITIZE_URL));
  $insta = trim(filter_var($_POST['insta'], FILTER_SANITIZE_URL));
  $file = $_FILES['img'];

 

  //Если есть такой email true
  $is_email = if_the_user($email, $pass, $pdo)['email'];


  //Существует ли такой email в бд
  if (isset($is_email)) {
    set_flash_message('danger', 'Email занят!!!');
    redirect_to('../create_user.php');
    die;
  }

  //Получаем id пользователя
  $new_user_id = add_user($email, $pass, $pdo);

  //
  if (!isset($new_user_id)) {
    set_flash_message('danger', 'Не удалось сохранить пользователя!!!');
    redirect_to('../create_user.php'); 
    die;
  }
  else{
    //Общая информация
    user_info($name, $work, $tel, $address, $new_user_id, $pdo);
    //Media
    $img = md5(uniqid($file['name'])) . '.' . explode('.', $file['name'])['1'];
    move_uploaded_file($file['tmp_name'], "../img/user/" . $img);
    user_media($status, $img, $vk, $telegram, $insta, $new_user_id, $pdo);
    
    set_flash_message('success', 'Пользователь зарегистрирован!');
    redirect_to('../users.php'); 
  }