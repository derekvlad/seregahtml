<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'Exception.php';
require 'PHPMailer.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->setLanguage('ru', 'phpmailer/language/'); // ошибки будут на русском языке
$mail->IsHTML(true); // включение html тегов в письме

//От кого письмо
$mail->setFrom('site@name.ru', 'от name.ru');
$mail->addAddress('info@мосрембыт.рус', 'Лев');


// тема письма
$mail->Subject = 'Данные с ' . $_SERVER['SERVER_NAME'];



$body = '<h2 style="margin-top: 0;">Форма с сайта"' . $_SERVER['SERVER_NAME'] . '"</h2>';

if (trim(!empty($_POST['user-name']))) {
  $body .= '<p><strong>Name:</strong> ' . $_POST['user-name'] . '</p>';
}

if (trim(!empty($_POST['user-email']))) {
  $body .= '<p><strong>E-mail:</strong> ' . $_POST['user-email'] . '</p>';
}

if (trim(!empty($_POST['user-phone']))) {
  $body .= '<p><strong>Phone number:</strong> ' . $_POST['user-phone'] . '</p>';
}
if (trim(!empty($_POST['user-message']))) {
  $body .= '<p><strong>Comment:</strong> ' . $_POST['user-message'] . '</p>';
}
if (trim(!empty($_POST['flexRadioDefault']))) {
  $body .= '<p><strong>Есть коньки?:</strong> ' . $_POST['flexRadioDefault'] . '</p>';
}
if (trim(!empty($_POST['drone']))) {
  $body .= '<p><strong>Время?: </strong> ' . $_POST['drone'] . '</p>';
}



if (trim(!empty($_POST['source']))) {
  $body .= '<p><strong>Где нашли нас?:</strong> ' . $_POST['source'] . '</p>';
}

if (trim(!empty($_POST['page_wp']))) {
  $body .= '<br/><p><strong>Страница: </strong> ' . $_POST['page_wp'] . '</p>';
}

if (trim(!empty($_POST['name_form_wp']))) {

  $body .= '<p><strong>Заявка: </strong> ' . $_POST['name_form_wp'] . '</p>';
}


// прикрепить файл
if (!empty($_FILES['quiz__file']['tmp_name'])) {

  //путь звгрузки файла
  $filePath = __DIR__ . "/files/" . $_FILES['quiz__file']['name'];

  // грузим файл
  if (copy($_FILES['quiz__file']['tmp_name'][0], $filePath)) {

    $body .= '<p><strong>Документ в приложении</strong>: Добавлен.</p>';

    foreach ($_FILES['quiz__file']['name'] as $key => $value) {
      $out_files[] = array("name" => $_FILES['quiz__file']['name'][$key], "tmp_name" => $_FILES['quiz__file']['tmp_name'][$key]);
    }

    foreach ($out_files as $k => $v) {
      $mail->AddAttachment($out_files[$k]['tmp_name'], $out_files[$k]['name']);
    }
  }
}



$mail->Body = $body;

// ООтправляем
if (!$mail->send()) {
  $message = 'Ошибка';
} else {
  $message = 'Данные отправлены!';
}

$response = ['message' => $message];

header('Content-type: application/json');
echo json_encode($response);
