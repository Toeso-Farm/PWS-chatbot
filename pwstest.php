<html>
<head>
    <title>Formulier verwerken</title>
</head>
<body>
    <?php
        $zoekterm = filter_var($_POST['zoekterm'], FILTER_SANITIZE_STRING);
        
        try {
            $db = new PDO('mysql:host=localhost;dbname=pws', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $query = "SELECT jaarlaag, vak, hoofdstuk, basisstof, video 
                      FROM materiaal 
                      WHERE jaarlaag LIKE :zoekterm 
                         OR vak LIKE :zoekterm 
                         OR hoofdstuk LIKE :zoekterm 
                         OR basisstof LIKE :zoekterm";
            
            $stmt = $db->prepare($query);
            $zoektermWild = "%" . $zoekterm . "%";
            $stmt->bindParam(':zoekterm', $zoektermWild, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultaten = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($resultaten) {
                echo "<table border='1'>";
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
</body>
</html>
