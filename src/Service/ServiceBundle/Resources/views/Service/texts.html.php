<?php
/**
 * Created by PhpStorm.
 * User: Мастер
 * Date: 13.02.2017
 * Time: 15:12
 */

/** @var mixed $politics */
/** @var mixed $rules */

?>
<script src="//cloud.tinymce.com/stable/tinymce.min.js?apiKey=9ma3bjr5b07uhoi32cugjqnmek7uokdh5curj0lyrj12t3dl"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        height: 500,
        theme: 'modern',
        plugins: [
            'autolink lists link charmap print preview hr anchor pagebreak',
            'code']
    });
</script>

<h1>Тексты</h1>
<table>
    <tbody>
    <tr class="no-border">
        <td><a href="/?action=admin&sub=users">Пользователи</a></td>
        <td><a href="/?action=admin&sub=stat">Статистика</a></td>
        <td><a href="/?action=admin&sub=feedback">Обращения</a></td>
        <td><a href="/?action=admin&sub=flights">Направления</a></td>
        <td><a href="/?action=admin&sub=banned">Забаненные</a></td>
        <td><a href="/?action=admin&sub=texts">Тексты</a></td>
        <td><a href="/?action=admin&sub=push">Push уведомления</a></td>
        <td><a href="/?action=admin&sub=logout">Выход</a></td>
    </tr>
    </tbody>
</table>
<h2>Политика конфиденциальности</h2>
<form action="" method="post">
    <textarea name="politics" class="text"><?php echo $politics;?></textarea>
    <br>
    <button type="submit">Сохранить</button>
</form>
<h2>Соглашение пользователя</h2>
<form action="" method="post">
    <textarea name="rules" class="text"><?php echo $rules;?></textarea>
    <br>
    <button type="submit">Сохранить</button>
</form>
