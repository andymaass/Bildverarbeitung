<!DOCTYPE html>
<html>
<head>
    <title>Bild-Upload und Größenanpassung</title>
</head>
<body>

<h1>Bild-Upload und Größenanpassung</h1>

<?php
// Zielverzeichnis für hochgeladene Bilder
$uploadDir = 'uploads/';

// Maximale Dateigröße (in Bytes)
$maxFileSize = 5 * 1024 * 1024; // 5 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];

    // Überprüfen, ob keine Fehler aufgetreten sind
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Überprüfen, ob die Datei ein JPG-Bild ist
        $imageType = exif_imagetype($image['tmp_name']);
        if ($imageType === IMAGETYPE_JPEG) {
            // Bildpfad erstellen
            $imagePath = $uploadDir . basename($image['name']);

            // Bild hochladen
            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                echo "Das Bild wurde erfolgreich hochgeladen.<br>";

                // Größe des hochgeladenen Bildes anpassen
                $newWidth = 800; // Neue Breite in Pixeln
                $newHeight = 600; // Neue Höhe in Pixeln

                list($width, $height) = getimagesize($imagePath);
                $imageResized = imagecreatetruecolor($newWidth, $newHeight);
                $sourceImage = imagecreatefromjpeg($imagePath);

                imagecopyresized($imageResized, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                $resizedImagePath = $uploadDir . 'resized_' . basename($image['name']);
                imagejpeg($imageResized, $resizedImagePath);

                echo "Die Größe des Bildes wurde angepasst. <br>";
                echo "Originalgröße: {$width}x{$height} <br>";
                echo "Neue Größe: {$newWidth}x{$newHeight} <br>";
                echo "<img src='$resizedImagePath' alt='Angepasstes Bild'>";
            } else {
                echo "Beim Hochladen des Bildes ist ein Fehler aufgetreten.";
            }
        } else {
            echo "Es sind nur JPG-Bilder erlaubt.";
        }
    } else {
        echo "Beim Hochladen des Bildes ist ein Fehler aufgetreten.";
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <label for="image">Wähle ein JPG-Bild (max. 5 MB):</label>
    <input type="file" name="image" accept=".jpg" required>
    <br>
    <input type="submit" value="Bild hochladen und anpassen">
</form>

</body>
</html>