<?php
$kuvan_lataus_virhe = 0;
$kuvatiedosto = "";

$target_dir = "kuvat/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    $kuvan_lataus_virhe = "Tiedosto ei ole kuva.";
    $uploadOk = 0;
  }
}

// Tämä koodi estää samannimisen kuvatiedoston lataamisen palvelimelle. Jos samanniminen
// tiedosto on palvelimella, tallennetaan tiedosto toisella nimellä, jonka edessä on jokin 
// (yksilöllinen) numero.
$i = 1;

// Suorita silmukkaa niin kauan kuin löytyy yksilöllinen tiedostonimi. Jokaisella silmukan
// kierroksella kokeillaan tiedostonimeä, jonka edessä on numero, joka on pykälää isompi
// kuin silmukan aiemmalla kierroksella. Kun sopiva tiedostonimi löytyy, silmukan suoritus
// lopetetaan.
while (file_exists($target_file)) {
  // Lisää numero tiedostonimen eteen.
  $target_file = $target_dir . $i . "_" . basename($_FILES["fileToUpload"]["name"]);
  // Seuraavalla silmukan kierroksella lisätään pykälää suurempi numero.
  $i++;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  $kuvan_lataus_virhe = "Valitettavasti tiedoston koko on liian suuri.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $kuvan_lataus_virhe = "Valitettavasti vain JPG, JPEG, PNG ja GIF-tiedostomuodot ovat sallittuja.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $kuvan_lataus_virhe = "Valitettavasti kuvaa ei voitu ladata.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $kuvatiedosto = basename($target_file);
  }
}
?>