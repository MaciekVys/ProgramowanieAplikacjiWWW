<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Wyświetla formularz kontaktowy.
 */
function PokazKontakt() {
    echo '<form action="contact.php" method="post">
            Temat: <input type="text" name="temat"><br>
            Email: <input type="text" name="email"><br>
            Treść: <textarea name="tresc"></textarea><br>
            <input type="submit" value="Wyślij">
          </form>';
}

/**
 * Obsługuje wysyłanie e-maila kontaktowego.
 */
function WyslijMailKontakt($odbiorca) {
    // Sprawdzenie, czy formularz został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sprawdzenie, czy wszystkie pola formularza zostały wypełnione
        if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
            echo '[nie_wypelniles_pola]';
            PokazKontakt();
        } else {
            // Przygotowanie i wysłanie e-maila
            $mail['subject'] = $_POST['temat'];
            $mail['body'] = $_POST['tresc'];
            $mail['sender'] = $_POST['email'];
            $mail['recipient'] = $odbiorca;

            $header = "From: Formularz kontaktowy<" . $mail['sender'] . ">\r\n";
            $header .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\r\n";
            $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $header .= "X-Priority: 3\r\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">";

            // Wysłanie maila
            mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

            echo '[wiadomosc_wyslana]';
        }
    }
}

/**
 * Obsługuje formularz przypomnienia hasła.
 */
function PrzypomnijHaslo($odbiorca, $linkResetujacy) {
    // Sprawdzenie, czy formularz został wysłany
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sprawdzenie, czy pole e-mail zostało wypełnione
        if (isset($_POST['email_do_resetu']) && !empty($_POST['email_do_resetu'])) {
            $email_do_resetu = $_POST['email_do_resetu'];

            // Przygotowanie i wysłanie e-maila do resetowania hasła
            $mail['subject'] = "Resetowanie hasła";
            $mail['body'] = "Kliknij ten link, aby zresetować hasło: " . $linkResetujacy;
            $mail['sender'] = "noreply@twojadomena.com";
            $mail['recipient'] = $email_do_resetu;

            $header = "From: System resetowania hasła <" . $mail['sender'] . ">\r\n";
            $header .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n";
            $header .= "X-Sender: <" . $mail['sender'] . ">\r\n";
            $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $header .= "X-Priority: 3\r\n";
            $header .= "Return-Path: <" . $mail['sender'] . ">";

            // Wysłanie maila
            if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
                echo '[link_do_resetowania_hasla_wyslany]';
            } else {
                echo '[blad_wysylania_email]';
            }
        } else {
            echo '[nie_wypelniles_pola_email]';
        }
    }
}

/**
 * Wyświetla formularz do przypomnienia hasła.
 */
function PokazFormularzPrzypomnieniaHasla() {
    echo '<form action="contact.php" method="post">
            Email do resetu hasła: <input type="text" name="email_do_resetu"><br>
            <input type="submit" name="resetuj_haslo" value="Resetuj hasło">
          </form>';
}

// Wywołanie funkcji, aby wyświetlić formularze
PokazKontakt();
PokazFormularzPrzypomnieniaHasla();

// Obsługa wysyłania formularzy
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['temat'])) {
    WyslijMailKontakt('adres@odbiorcy.com');
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email_do_resetu'])) {
    PrzypomnijHaslo('adres@odbiorcy.com', 'link_resetujacy.com');
}
?>
