<?php

require '../vendor/autoload.php';

$api = new \Yandex\Geo\Api();

$address = isset($_POST['address']) ? $_POST['address'] : null;
$api->setQuery($address);

// Настройка фильтров
$api
    ->setLimit(5) // кол-во результатов
    ->setLang(\Yandex\Geo\Api::LANG_US) // локаль ответа
    ->load();

$response = $api->getResponse();
$response->getFoundCount(); // кол-во найденных адресов
$response->getQuery(); // исходный запрос
$response->getLatitude(); // широта для исходного запроса
$response->getLongitude(); // долгота для исходного запроса

// Список найденных точек
$collection = $response->getList();
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Поиск координат</title>
    <style>
     </style>
	  <script src="https://api-maps.yandex.ru/2.1/?lang=tr_TR" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        var myMap, 
            myPlacemark;

        function init(){ 
				
            myMap = new ymaps.Map("map", {
			
                center: [<?php if(isset($_GET['point'])) echo $_GET['point'];?>],
                zoom: 7
            }); 
						
            myPlacemark = new ymaps.Placemark([<?php if(isset($_GET['point'])) echo $_GET['point']; ?>], {
			
               
            });
            
            myMap.geoObjects.add(myPlacemark);
        }
    </script>
</head>
 <body>

    <h2>Адреса на карте</h2>

   <form method="post">

<input type="text" name ="address" placeholder = "Введите адрес">
<input type="submit" value="go">

</form>
<br>

<?php foreach ($collection as $item) : ?> 

    <p><a href ="?point=<?= $item->getLatitude().', '.$item->getLongitude();?>&addr=<?= $item->getAddress();?>" ><?=$item->getAddress();?></a></p>
   
 <?php endforeach; ?>
 <?php if(isset($_GET['addr'])): ?>
	<p><?= $_GET['addr']; ?></p>
 <?php endif; ?>
 <br>
 <div id="map" style="width: 600px; height: 400px"></div>
  </body>
</html>
