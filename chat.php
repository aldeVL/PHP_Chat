<?php
session_start(); //Запускаем сессию
include ("bd.php");//Подключение к бд
//Проверка полученных из send.php данных и создание переменных
if(isset($_POST['act'])){
	$act = $_POST['act'];
}
if(isset($_POST['var1'])){
	$var1 = $_POST['var1'];
}
if(isset($_POST['var2'])){
	$var2 = $_POST['var2'];
}
switch($_POST['act']) {//В зависимости от значения act вызываем разные функции
	case 'load': 
		$echo = load($db); //Загружаем сообщения
	break;	
	case 'send': //Заносим сообщения в бд
	if ($var2==''){ //Если получатель не введен, то сообщение - общее
		if(isset($var1)) {
			$echo = send($db,$var1); //Вызываем функцию отправкии сообщения
		}
	}
	else { //если введен получатель, то сообщение - личное
		if(isset($var1)) { 
			$echo = sendPrivate($db,$var1,$var2); //Вызываем функцию отправкии личных сообщений
		}
	}
	break;
}
function send($db,$message){ //Функция отправки сообщения в бд
		$message = htmlspecialchars($message);//Преобразует специальные символы в HTML сущности (ASCII-код)
		$message = trim($message); //Удаляем лишние пробелы
		$message = addslashes($message); //Экранируем запрещенные символы
		$result = mysqli_query($db,"INSERT INTO messages (id,message,d,reciever) VALUES ('$_SESSION[id]','$message', NOW(), 'NULL()')");//Заносим сообщение в базу данных
		return load($db); //Вызываем функцию загрузки сообщений
}

function sendPrivate($db,$message,$reciever){ //Функция отправки личных сообщения в бд
		$message = htmlspecialchars($message);//Преобразует специальные символы в HTML сущности (ASCII-код)
		$message = trim($message); //Удаляем лишние пробелы
		$message = addslashes($message); //Экранируем запрещенные символы
		$reciever = htmlspecialchars($reciever);
		$reciever = trim($reciever); 
		$reciever = addslashes($reciever);
		$user_res = mysqli_query($db,"SELECT id FROM users WHERE login='$reciever'");//Выбираем из базы id получателя
		$roww = mysqli_fetch_array($user_res);
		$result = mysqli_query($db,"INSERT INTO messages (id,message,d,reciever) VALUES ('$_SESSION[id]','$message', NOW(),$roww[id])");//Заносим личное сообщение в базу данных
		return load($db); //Вызываем функцию загрузки сообщений
}

function load($db){ //Функция загрузки сообщений
	$echo = "";
		$result = mysqli_query($db,"SELECT * FROM messages"); //Запрашиваем сообщения из базы
		if($result) { //Если запрос выполнен
			if(mysqli_num_rows($result) >= 1) { //Проверка на наличие сообщений в базе
				while($array = mysqli_fetch_array($result)) {//Выводим сообщения с помощью цикла
					$user_result = mysqli_query($db,"SELECT * FROM users WHERE id='$array[id]'");//Получаем данные об авторе сообщения
					if(mysqli_num_rows($user_result) == 1) { //Проверка на наличие пользователя в базе
						$user = mysqli_fetch_array($user_result);
					//Проверка на админа и является ли он получателем/отправителем (наличие личных сообщений)
					if ("$_SESSION[id]" == 6 && ("$_SESSION[id]"=="$user[id]"||"$_SESSION[id]"=="$array[reciever]")){
						$array['message'] = smile($array['message']);
						$user_resuu = mysqli_query($db,"SELECT login FROM users WHERE id='$array[reciever]'");//Получаем данные о получателе сообщения
						$userrrr = mysqli_fetch_array($user_resuu);
						if ($userrrr['login']==''){ //Если получателя нет выводим остальные сообщения с кнопкой удаления сообщения
							$array['message'] = smile($array['message']);
							$echo .="<div class='chat__message'><b>$user[login]:</b><span> $array[message]</span><div class='mind'><input class='delbtn' type=\"submit\" value=\"Удалить\" onclick=\"location.href='/delete.php?delid=$array[msg_id]';\" /></div></div><div class='data'><sup>$array[d]</sup></div>";
						}
						else{ //Иначе если админ является получателем/отправителем выводим личное сообщение с кнопкой удаления сообщения
						$array['message'] = smile($array['message']);
						$echo .= "<div class='chat__message private'><b>$user[login]-->$userrrr[login]:</b><span class='private'> $array[message]</span><div class='mind'><input class='delbtn' type=\"submit\" value=\"Удалить\" onclick=\"location.href='/delete.php?delid=$array[msg_id]';\" /></div></div><div class='data'><sup>$array[d]</sup></div>";}
					}
					//Проверка на админа
					else if ("$_SESSION[id]" == 6 && ("$array[reciever]"==''||"$array[reciever]"=='0')){
						$array['message'] = smile($array['message']);
					//Вывод сообщений с кнопкой удаления сообщения
					$echo .= "<div class='chat__message'><b>$user[login]:</b><span> $array[message]</span><div class='mind'><input class='delbtn' type=\"submit\" value=\"Удалить\" onclick=\"location.href='/delete.php?delid=$array[msg_id]';\" /></div></div><div class='data'><sup>$array[d]</sup></div>";
					}
					//Проверка есть ли получатель в базе
					else if ("$array[reciever]"==''||"$array[reciever]"=='0'){
						$array['message'] = smile($array['message']);
						//Вывод сообщений без кнопки удаления сообщения
						$echo .= "<div class='chat__message'><b>$user[login]:</b><span> $array[message]</span></div><div class='data'><sup>$array[d]</sup></div>";
					}
					//Если текущий пользователь является получателем/отправителем выводим личное сообщение
					else if ("$_SESSION[id]"=="$user[id]"||"$_SESSION[id]"=="$array[reciever]"){
						$array['message'] = smile($array['message']);
						$user_resu = mysqli_query($db,"SELECT login FROM users WHERE id='$array[reciever]'");//Получаем данные о получателе сообщения
						$userrr = mysqli_fetch_array($user_resu);
						//Вывод личных сообщений без кнопки удаления сообщения
						$echo .= "<div class='chat__message private'><b>$user[login]-->$userrr[login]:</b><span class='private'> $array[message]</span></div><div class='data'><sup>$array[d]</sup></div>";
					}
					}
					}
			} else {
				$echo = "Нет сообщений!";//В базе ноль записей
			}
		}
	return $echo;//Возвращаем результат работы функции
}

function smile($var){
 $symbol = array(':laugh:',
 ':smile:',
 ':podmig:',
 ':poker:');
 $graph = array('<img width="20" height = "20" src="images/1.png">',
 '<img width="20" height = "20" src="images/2.png">',
 '<img width="20" height = "20" src="images/3.png">',
 '<img width="20" height = "20" src="images/4.png">');
 return str_replace($symbol, $graph, $var);
}
echo $echo;
?>