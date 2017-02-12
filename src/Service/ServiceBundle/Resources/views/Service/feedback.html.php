<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $feedback */
?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Обращения</h1>
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
            <td>Имя</td>
            <td>Email</td>
            <td>Текст</td>
            <td>Дата обращения</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($feedback as $f){?>
            <tr>
                <td><?php echo $f['name'];?></td>
                <td><?php echo $f['email'];?></td>
                <td><?php echo $f['text'];?></td>
                <td><?php echo $f['created'];?></td>
            </tr>
        <?php }?>
    </tbody>
</table>