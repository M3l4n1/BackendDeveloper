<!DOCTYPE html>
<html lang="hr">
<head>
    <title>Analiza Riječi</title>
</head>
<body>
    <h1>Upišite željenu riječ </h1>
    <form action="index.php" method="POST">
        <label for="word">Upišite riječ:</label>
        <input type="text" id="word" name="word">
        <input type="submit" value="Pošalji">
    </form>

    <?php
    // Funkcija za brojanje slova u riječi
    function count_letters($word) {
        return strlen($word);
    }

    // Funkcija za brojanje samoglasnika
    function count_vowels($word) {
        $vowels = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $vowelCount = 0;
        foreach (str_split($word) as $letter) {
            if (in_array($letter, $vowels)) {
                $vowelCount++;
            }
        }
        return $vowelCount;
    }

    // Funkcija za brojanje suglasnika
    function count_consonants($word) {
        return count_letters($word) - count_vowels($word);
    }

    // Putanja do datoteke
    $filename = 'words.json';

    // Učitavanje postojećih riječi iz datoteke
    $words = [];
    if (file_exists($filename)) {
        $json_data = file_get_contents($filename);
        $words = json_decode($json_data, true);
    }

    // Ako je poslan POST zahtjev
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_word = $_POST['word'];

        // Provjera da li je unesena riječ
        if (!empty($new_word)) {
            // Obrada nove riječi
            $length = count_letters($new_word);
            $vowels = count_vowels($new_word);
            $consonants = count_consonants($new_word);

            // Kreiranje niza s podacima o riječi
            $word_data = [
                "word" => $new_word,
                "length" => $length,
                "vowels" => $vowels,
                "consonants" => $consonants
            ];

            // Dodavanje nove riječi u postojeći niz
            $words[] = $word_data;

            // Zapisivanje niza u JSON datoteku
            file_put_contents($filename, json_encode($words));
        } else {
            echo "<p style='color:red;'>Polje za unos riječi ne smije biti prazno!</p>";
        }
    }

    // Ispis riječi u tablici
    if (!empty($words)) {
        echo "<h2>Analizirane riječi</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Riječ</th><th>Broj slova</th><th>Broj samoglasnika</th><th>Broj suglasnika</th></tr>";
        foreach ($words as $word) {
            echo "<tr>";
            echo "<td>" . ($word['word']) . "</td>";
            echo "<td>" . ($word['length']) . "</td>";
            echo "<td>" . ($word['vowels']) . "</td>";
            echo "<td>" . ($word['consonants']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>
