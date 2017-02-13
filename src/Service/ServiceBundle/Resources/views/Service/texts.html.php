<?php
/**
 * Created by PhpStorm.
 * User: Мастер
 * Date: 13.02.2017
 * Time: 15:12
 */



?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/app/Resources/jeditor/jquery-te-1.4.0.min.js"></script>
<script src="/app/Resources/jeditor/jquery-te-1.4.0.css"></script>
<script>
    $(".text").jqte();
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
    </tr>
    </tbody>
</table>
<textarea class="text">

    text

</textarea>

