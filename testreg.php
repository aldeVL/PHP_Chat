<?php
	ob_start(); //Включает буферизацию вывода
    session_start();//Запускаем сессию
	//Занесение введенного пользователем логина в переменную $login, если он пустой, то уничтожаем переменную
	if (isset($_POST['login'])){
		$login = $_POST['login'];
		if ($login == ''){
			unset($login);
		}
	}
	//Занесение введенного пользователем пароля в переменную $password, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])){
		$password=$_POST['password'];
		if ($password ==''){
			unset($password);
		}
	}
	//если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
	if (empty($login)||empty($password)){
		exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
    }
    $login = stripslashes($login); //Удаляет экранирующие бэкслэши 
    $login = htmlspecialchars($login); //Преобразует специальные символы в HTML сущности (ASCII-код)
	$password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login); //удаляет лишние пробелы
    $password = trim($password);
    include ("bd.php"); //Подключение к бд 
	$result = mysqli_query($db,"SELECT * FROM users WHERE login='$login'"); //извлекает из базы все данные о пользователе с введенным логином
    $myrow = mysqli_fetch_array($result); //Преобразует результат запроса в массив
    if (empty($myrow['password'])){ //если пользователя с введенным логином не существует
		echo "<body>";
		header("Location: index.php");//Редирект на форму входа
		ob_end_flush(); //Очищает буфер вывода и отключает буферизацию вывода
    }
    else{
		if ($myrow['password']==$password){ //если существует, то сверяем пароли
			$_SESSION['login']=$myrow['login']; //Если пароли совпадают, то запускает пользователя в сессию
			$_SESSION['id']=$myrow['id']; //Присваивает пользователю id сессии
			$neban = mysqli_query($db,"SELECT * FROM users WHERE id = $_SESSION[id]"); //извлекает из базы все данные о текущем пользователе
			$arr = mysqli_fetch_array($neban);
			if ($arr['status']==1){ //Проверяем статус пользователя 1-заблокировен/0-Обычный
				echo "Вы заблокированы в данном чате";
			}
			else{
				header ("Location:send.php"); //Редирект на страницу чата
			}
		}
		else { //если пароли не сошлись
			echo "<body>";
			header("Location: index.php"); //Редирект на форму входа
			ob_end_flush(); //Очищает буфер вывода и отключает буферизацию вывода
		}
    }
    ?>