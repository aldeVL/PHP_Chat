<?php
if (isset($_GET['delid'])) //Проверям была ли нажата кнопка удалить
{
	include ("bd.php");//Подключение к бд
	$delid=$_GET['delid']; //Присваиваем переменной значение - id выбранного сообщения для удаления
	mysqli_query($db, "DELETE FROM messages WHERE msg_id=$delid;"); //Делаем запрос на удаление выбранного сообщения
	header('Location: /send.php'); // переадресовываем на главную страницу, что бы при нажатии F5 повторного удаления небыло
	exit;
}
?>