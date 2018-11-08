<?php
  //Ajanslar listesini Veritabanına Yükleme
  //CSV dosyasının içindeki bilgileri parçalayarak sıralı şekilde array oluşturuyorum.
  preg_match_all('/(.*),"(.*)",(.*),(.*)/i', file_get_contents("agencies.csv"), $agencies);
  //Veritabanı Bağlantısı
  $dbcon = mysqli_connect('localhost','test','mpRukc93A6RnMEGX','istanbay-test');
  $agency_count = count($agencies[1]);
  //Ajans sayısı kere çalıştır
  for($i=1;$i<$agency_count;$i++){
    $cord_total = $agencies[3][$i]+$agencies[4][$i];
    //Sıradaki Ajansı Veritabanına Gir.
    mysqli_query($dbcon,"INSERT INTO agencies(id, name, lat, lng, cord_total) VALUES({$agencies[1][$i]}, '{$agencies[2][$i]}', {$agencies[3][$i]}, {$agencies[4][$i]}, {$cord_total})");
  }
?>