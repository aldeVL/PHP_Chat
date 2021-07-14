<?php
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
    $login = stripslashes($login);//Удаляет экранирующие бэкслэши 
    $login = htmlspecialchars($login);//Преобразует специальные символы в HTML сущности (ASCII-код)
	$password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login); //удаляет лишние пробелы
    $password = trim($password);
    include ("bd.php");//Подключение к бд
    $result = mysqli_query($db,"SELECT id FROM users WHERE login='$login'");//Запрашиваем из бд id пользователя, соответствующий введенному логину
    $myrow = mysqli_fetch_array($result);//Преобразует результат запроса в массив
    if (!empty($myrow['id'])){ //Если в базе есть пользователь с таким логином - ошибка
		exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
    }
    $result2 = mysqli_query ($db,"INSERT INTO users (login,password) VALUES('$login','$password')");//если такого нет, то сохраняем данные
    if ($result2=='TRUE'){ // Проверяем, есть ли ошибки
		echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='index.php'>Главная страница</a>";
    }
	else{
		echo "Ошибка! Вы не зарегистрированы.";
    }
    ?>