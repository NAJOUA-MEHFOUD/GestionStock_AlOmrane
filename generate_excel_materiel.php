<?php
    require_once 'database.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    require 'vendor/autoload.php';
    
    // Définissez la requête SQL pour récupérer les données de la table utilisateur
    $sql = "SELECT Inventaire, Type, Marque, Date_achat, Etat, id_fiscale FROM materiel";
    
    // Exécutez la requête SQL et récupérez les résultats dans un objet PDOStatement
    $result = $pdo->query($sql);
    
    // Vérifiez si des résultats ont été trouvés
    if ($result->rowCount() > 0) {
        // Initialisez le tableau de données
        $data = array();
    
        // Parcourez les résultats et ajoutez chaque ligne de données dans le tableau $data
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data[] = array(
                $row['Inventaire'],
                $row['Type'],
                $row['Marque'],
                $row['Date_achat'],
                $row['Etat'],
                $row['id_fiscale'],
            );
        }
    } else {
        // Si aucun résultat n'a été trouvé, initialisez le tableau $data avec une ligne vide pour indiquer qu'aucune donnée n'a été trouvée.
        $data = array(array());
    }
    
    // Maintenant, vous pouvez utiliser le tableau $data pour générer le fichier Excel ou pour afficher les données dans votre tableau HTML.

// Créez un nouvel objet Spreadsheet
$spreadsheet = new Spreadsheet();

// Sélectionnez la feuille active
$sheet = $spreadsheet->getActiveSheet();

// Définissez les en-têtes de colonnes
$sheet->setCellValue('A1', 'Inventaire');
$sheet->setCellValue('B1', 'Type');
$sheet->setCellValue('C1', 'Marque');
$sheet->setCellValue('D1', 'Date achat');
$sheet->setCellValue('E1', 'Etat');
$sheet->setCellValue('E1', 'id fiscale');


$row = 2; // Commencez à la ligne 2 (après les en-têtes de colonnes)
foreach ($data as $rowData) {
    $column = 'A';
    foreach ($rowData as $value) {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }
    $row++;
}

// Créez un objet Writer pour enregistrer le fichier Excel
$writer = new Xlsx($spreadsheet);

// Définissez les en-têtes de réponse pour le téléchargement du fichier Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Liste_Materiel.xlsx"');
header('Cache-Control: max-age=0');

// Enregistrez le fichier Excel sur la sortie de réponse
$writer->save('php://output');

// Terminer le script pour éviter tout autre rendu de page HTML
exit;
?>