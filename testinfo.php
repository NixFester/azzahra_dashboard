<!--<?php-->
<!--phpinfo();-->
<!--?>-->

<?php
$url = "https://api.azzahracomputertegal.com/api/produk";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

echo "RESULT:<br>";
var_dump($result);

echo "<br><br>ERROR:<br>";
var_dump($error);
