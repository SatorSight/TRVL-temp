<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $users */
?>
<style>
    td{
        border: 1px solid black;
    }

    .no-border td{
        border: none;
    }
</style>
<h1>Пользователи</h1>
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
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Добавлен</td>
            <td>Забанен</td>
            <td>Соц. сеть</td>
            <td>ID чата</td>
            <td>О себе</td>
            <td>Имя</td>
            <td>Ориентация</td>
            <td>Внешность</td>
            <td>Возраст</td>
            <td>Город</td>
            <td>Пол</td>
            <td>Хочет общаться</td>
            <td>Найти попутчика</td>
            <td>Найти пару</td>
            <td>Найти друзей</td>
            <td>Отношения</td>
            <td>Бан</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user){?>
            <tr>
                <td><?php echo $user['id'];?></td>
                <td><?php echo $user['created'];?></td>
                <td><?php echo $user['banned'] ? 'Да' : 'Нет';?></td>
                <td><?php
                    if($user['app_type'] == 'vk') echo 'Вконтакте';
                    if($user['app_type'] == 'fb') echo 'Facebook';
                    if($user['app_type'] == 'in') echo 'Instagram';
                    if($user['app_type'] == 'ok') echo 'Одноклассники';
                    ?>
                </td>
                <td><?php echo $user['chat_id'];?></td>
                <td><?php echo $user['about'];?></td>
                <td><?php echo $user['name'];?></td>
                <td>
                    <?php
                    if($user['orientation'] == 0) echo 'Гетеро';
                    if($user['orientation'] == 1) echo 'Гей';
                    if($user['orientation'] == 2) echo 'Би';
                    ?>
                </td>
                <td><?php echo $user['appearance'];?></td>
                <td><?php echo $user['age'];?></td>
                <td><?php echo $user['city'];?></td>
                <td><?php echo $user['sex'] ? 'Ж' : 'М';?></td>
                <td><?php echo $user['wannaCommunicate'] ? 'Да' : 'Нет';?></td>
                <td><?php echo $user['findCompanion'] ? 'Да' : 'Нет';?></td>
                <td><?php echo $user['findCouple'] ? 'Да' : 'Нет';?></td>
                <td><?php echo $user['findFriends'] ? 'Да' : 'Нет';?></td>
                <td>
                    <?php
                    if($user['free'] == 0) echo 'Свободен';
                    if($user['free'] == 1) echo 'Св. отношения';
                    if($user['free'] == 2) echo 'В паре';
                    ?>
                </td>
                <td>
                    <?php if(!$user['banned']){?>
                        <form action="" method="post">
                            <input type="hidden" name="ban_him" value="<?php echo $user['id'];?>"/>
                            <button type="submit">Забанить</button>
                        </form>
                    <?php }else{ ?>
                        Забанен
                    <?php }?>
                </td>
            </tr>
        <?php }?>
    </tbody>
</table>
<?php