<?php
/** @var mixed $wrong */
?>
<h1>Авторизация</h1>
<form action="" method="POST">
    <table>
        <tbody>
            <tr>
                <td>Логин</td>
                <td><input type="text" name="user"/></td>
            </tr>
            <tr>
                <td>Пароль</td>
                <td><input type="text" name="pass"/></td>
            </tr>
            <tr>
                <td>
                    <button type="submit">Войти</button>
                </td>
            </tr>
            <tr><td style="text-align: center; color:red" colspan="2"><?php echo $wrong ? 'Неверный логин/пароль' : '';?></td></tr>
        </tbody>
    </table>
</form>