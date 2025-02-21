<html>
<head>
    <title>Formulier verwerken</title>
	<style> 
/* Opmaak voor de terug knop*/
.terug-knop {
    border-radius: 5px;
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: cornflowerblue;
    color: #fff;
    padding: 10px;
    border: none;
    cursor: pointer;
    z-index: 10;
}
.terug-knop:hover {
    background-color: blue;
}
	</style>
</head>
<body style="background-image: url('https://i.imgur.com/itYrHlb.jpeg'); background-size: cover; background-position: center;">
<table>
<?php
// Hier worden de variabelen gemaakt
$velden = ['jaarlaag', 'vak', 'hoofdstuk', 'basisstof'];
$waarden = [];

// Hier word de variabel veld gefilterd
foreach ($velden as $veld) {
    if (!empty($_POST[$veld])) {
        $waarden[$veld] = filter_var($_POST[$veld], FILTER_SANITIZE_STRING);
    }
}

try {
    // Hier word een conectie met de database gemaakt
    $db = new PDO('mysql:host=localhost;dbname=pws', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Dit is de query
    $query = "SELECT jaarlaag, vak, hoofdstuk, basisstof, video FROM materiaal";
    
    // Als er iets moeten gefilterd dan komt er een where bij
    if (!empty($waarden)) {
        $filters = [];
        foreach ($waarden as $key => $value) {
            $filters[] = "$key = :$key";
        }
        $query .= " WHERE " . implode(" AND ", $filters);
    }

    // De Qeury word uitgevoerd
    $stmt = $db->prepare($query);
    foreach ($waarden as $key => &$value) {
        $stmt->bindParam(":$key", $value, PDO::PARAM_STR);
    }
    $stmt->execute();
    
    // Hier worden de resultaten opgehaald
    $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // De restultaten worden in een tabel gezet
    if ($resultaten) {
        echo "<table border='1' style= 'background-color: white'>";
        echo "<tr><th>Jaarlaag</th><th>Vak</th><th>Hoofdstuk</th><th>Basisstof</th><th>Video</th></tr>";
        foreach ($resultaten as $rij) {
            echo "<tr>
                    <td>{$rij['jaarlaag']}</td>
                    <td>{$rij['vak']}</td>
                    <td>{$rij['hoofdstuk']}</td>
                    <td>{$rij['basisstof']}</td>
                    <td>{$rij['video']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Geen resultaten gevonden.";
    }
} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>
<button class="terug-knop"; onclick="window.history.back()">Terug</button>
</body>
</html>
