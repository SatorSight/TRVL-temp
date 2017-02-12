<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $banned */
?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Забаненные пользователи</h1>
<table>
    <tbody>
    <tr class="no-border">
        <td><a href="/?action=admin&sub=users">Пользователи</a></td>
        <td><a href="/?action=admin&sub=stat">Статистика</a></td>
        <td><a href="/?action=admin&sub=feedback">Обращения</a></td>
        <td><a href="/?action=admin&sub=flights">Направления</a></td>
        <td><a href="/?action=admin&sub=banned">Забаненные</a></td>
    </tr>
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <td>ID</td>
        <td>Зарегистрирован</td>
        <td>Соц. сеть</td>
        <td>ID чата</td>
        <td>Имя</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($banned as $f){?>
        <tr>
            <td><?php echo $f['id'];?></td>
            <td><?php echo $f['created'];?></td>
            <td><?php echo $f['app_type'];?></td>
            <td><?php echo $f['chat_id'];?></td>
            <td><?php echo $f['name'];?></td>
        </tr>
    <?php }?>
    </tbody>
</table>