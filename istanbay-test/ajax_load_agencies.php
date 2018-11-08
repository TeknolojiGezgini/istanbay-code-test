<?php
  // bu dosya bir konum arandığında veya seçildiğinde javascript içinden ajax ile çağırılıyor.
  if(isset($_GET)){
    //Veritabanı Bağlantısı
    $dbcon = mysqli_connect('localhost','test','mpRukc93A6RnMEGX','istanbay-test');
    // 5km'yi geo lokasyon'daki eşdeğeri sayıya getiriyorum.
    $range = 5/111;
    //sql injection koruması için real_escape_string.
    $lat = mysqli_real_escape_string($dbcon,$_GET['lat']);
    $lng = mysqli_real_escape_string($dbcon,$_GET['lng']);
    //seçilen konumun 5km yarıçapında etrafını kapsayan bir kare.
    $lat_min = $lat-$range;
    $lat_max = $lat+$range;
    $lng_min = $lng-$range;
    $lng_max = $lng+$range;
    //Mesafenin içindeki ajans listesini veritabanından getiriyorum.
    $agencies = mysqli_query($dbcon,"SELECT * FROM agencies WHERE lat>$lat_min AND lat<$lat_max AND lng>$lng_min AND lng<$lng_max");
    // Javascriptte parçalayarak kullanabilmek için JSON formatında ajans listesini yazdırıyorum.
    $string = '{"agencies":[';
    while($agency = mysqli_fetch_assoc($agencies)){
      $string .= '{"name":"'.$agency['name'].'","lat":"'.$agency['lat'].'","lng":"'.$agency['lng'].'"},';
    }
    $string .= ']}';
    $string = str_replace(',]',']',$string);
    echo $string;
  }
?>