<?php
/**
 *
 * Created by PhpStorm.
 * User: User
 * Date: 12.02.2017
 * Time: 19:59
 */
/** @var mixed $man */
/** @var mixed $woman */
/** @var mixed $age18 */
/** @var mixed $age25 */
/** @var mixed $age38 */
/** @var mixed $age50 */
/** @var mixed $age100 */
/** @var mixed $from */
/** @var mixed $to */



?>
<style>
	body {
	font-family: 'Open Sans', Arial, sans-serif;
	}
	h1 {
	text-align: center;
	}
    table {
	border-spacing: 0 10px;
	width: 80%;
	margin: 0 auto;
	}
	table th, table thead {
	font-weight: bold;
	}
	table th {
	padding: 10px 20px;
	background: #56433D;
	color: #F9C941;
	border-right: 2px solid; 
	font-size: 0.9em;
	}
	table th:first-child {
	text-align: left;
	}
	table th:last-child {
	border-right: none;
	}
	table td {
	vertical-align: middle;
	padding: 10px;
	font-size: 14px;
	text-align: center;
	border-top: 2px solid #56433D;
	border-bottom: 2px solid #56433D;
	border-right: 2px solid #56433D;
	background:white;
	}
	table td:first-child {
	border-left: 2px solid #56433D;
	}
	table td:nth-child(2){
	text-align: left;
	}
	table td.caption {
	border: none;
	margin: 20px 0;
	}
	table tr:hover td, .menu td {
	border-color: #C61359;
	}
	.menu td {
	background:#ededed;
	}
</style>
<h1>Статистика</h1>
<table>
    <tbody>
    <tr class="no-border menu">
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
    <tbody>
        <tr><td style="text-align: center" class="caption" colspan="2">М / Ж</td></tr>
        <tr>
            <td>Мужчин</td>
            <td><?php echo $man;?></td>
        </tr>
        <tr>
            <td>Женщин</td>
            <td><?php echo $woman;?></td>
        </tr>
		<tr>
			<td colspan="2">
				<div id="chart_div"></div>
			</td>
		</tr>
        <tr><td style="text-align: center" class="caption" colspan="2">Возрастные группы</td></tr>
        <tr>
            <td>< 18</td>
            <td><?php echo $age18;?></td>
        </tr>
        <tr>
            <td>18-25</td>
            <td><?php echo $age25;?></td>
        </tr>
        <tr>
            <td>25-38</td>
            <td><?php echo $age38;?></td>
        </tr>
        <tr>
            <td>38-50</td>
            <td><?php echo $age50;?></td>
        </tr>
        <tr>
            <td>> 50</td>
            <td><?php echo $age100;?></td>
        </tr>
		<tr>
			<td colspan="2">
				<div id="pie_div"></div>
			</td>
		</tr>
        <tr><td style="text-align: center" class="caption" colspan="2">Направления</td></tr>
        <tr>
            <td><b>Города отбытия:</b></td>
            <td><b>Кол-во путешественников:</b></td>
        </tr>
        <?php foreach($from as $city => $n){?>
            <tr>
                <td><?php echo $city;?></td>
                <td><?php echo $n;?></td>
            </tr>
        <?php }?>
        <tr>
            <td><b>Города прибытия:</b></td>
            <td><b>Кол-во путешественников:</b></td>
        </tr>
        <?php foreach($to as $city => $n){?>
            <tr>
                <td><?php echo $city;?></td>
                <td><?php echo $n;?></td>
            </tr>
        <?php }?>
    </tbody>
</table>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawBarColors);

function drawBarColors() {
      var data = google.visualization.arrayToDataTable([
        ['Пол', 'М', 'Ж'],
        ['', <?php echo $man;?>, <?php echo $woman;?>]
      ]);

      var options = {
        title: 'М / Ж',
        chartArea: {width: '50%'},
        colors: ['#4862A3', '#ffab91'],
      };
      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
      chart.draw(data, options);
      
      var data2 = google.visualization.arrayToDataTable([
          ['Возраст', ''],
          ['< 18',     <?php echo $age18;?>],
          ['18-25',      <?php echo $age25;?>],
          ['26-30',  <?php echo $age38;?>],
          ['31-40', <?php echo $age50;?>],
          ['> 40',    <?php echo $age100;?>]
        ]);

        var options2 = {
          title: 'Возрастные группы',
          pieHole: 0.4,
        };

        var chart2 = new google.visualization.PieChart(document.getElementById('pie_div'));
        chart2.draw(data2, options2);

    }
 </script>