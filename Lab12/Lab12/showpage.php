<?php
/**
 * Wyświetla treść strony na podstawie jej ID.
 */
function Show($id, $conn) {
    // Sanitizacja ID strony dla bezpieczeństwa
    $id_clear = htmlspecialchars($id);

    // Przygotowanie zapytania SQL
    $query = "SELECT * FROM page_list WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_clear);

    // Wykonanie zapytania
    $stmt->execute();
    $result = $stmt->get_result();

    // Pobranie wyniku
    $row = $result->fetch_array();

    // Sprawdzenie, czy strona istnieje
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>
