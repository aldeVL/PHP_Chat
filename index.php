<?php
	session_start(); //Запускаем сессию
?>
<html>
	<head>
    <title>Главная страница</title>
    </head>
    <body>
		<h2>Главная страница</h2>
		<form action="testreg.php" method="post">
	<p>
		<label>Ваш логин:<br></label>
		<input name="login" type="text" size="15" maxlength="15"> <!-- Поле для логина-->
    </p>
    <p>
		<label>Ваш пароль:<br></label>
		<input name="password" type="password" size="15" maxlength="15"> <!-- Поле для паролей -->
    </p>
    <p>
		<input type="submit" name="submit" value="Войти"> <!-- Отправляет данные в файл testreg.php --> 
		<br>
		<a href="reg.php">Зарегистрироваться</a> <!-- Ссылка на форму регистрации -->
    </p>
		</form>
    </body>
</html>