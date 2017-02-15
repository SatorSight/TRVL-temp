<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var $pushes $feedback */
?>
<style xmlns="http://www.w3.org/1999/html">
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Push уведомления</h1>
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
<table style="width: 100%;">
    <thead>
    <tr>
        <td>Назначение</td>
        <td>Текст</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($pushes as $p){?>
        <tr>
            <td><?php echo $p['label'];?></td>
            <form action="" method="post">
                <td>

                    <input type="hidden" name="push_id" value="<?php echo $p['id'];?>">
                    <textarea style="width: 100%;" name="push_val"><?php echo $p['value'];?></textarea>

                </td>
                <td style="width: 20px; border: none;"><button type="submit">Сохранить</button> </td>
            </form>
        </tr>
    <?php }?>
    </tbody>
</table>
<span style="color: red">* Вместо #VAR# будет подставлено имя.</span>