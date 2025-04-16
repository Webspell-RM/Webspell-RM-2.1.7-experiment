<?php
/**
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 *                  Webspell-RM      /                        /   /                                          *
 *                  -----------__---/__---__------__----__---/---/-----__---- _  _ -                         *
 *                   | /| /  /___) /   ) (_ `   /   ) /___) /   / __  /     /  /  /                          *
 *                  _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/_____/_____/__/__/_                          *
 *                               Free Content / Management System                                            *
 *                                           /                                                               *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @version         webspell-rm                                                                              *
 *                                                                                                           *
 * @copyright       2018-2023 by webspell-rm.de                                                              *
 * @support         For Support, Plugins, Templates and the Full Script visit webspell-rm.de                 *
 * @website         <https://www.webspell-rm.de>                                                             *
 * @forum           <https://www.webspell-rm.de/forum.html>                                                  *
 * @wiki            <https://www.webspell-rm.de/wiki.html>                                                   *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @license         Script runs under the GNU GENERAL PUBLIC LICENCE                                         *
 *                  It's NOT allowed to remove this copyright-tag                                            *
 *                  <http://www.fsf.org/licensing/licenses/gpl.html>                                         *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
 * @author          Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at)                        *
 * @copyright       2005-2011 by webspell.org / webspell.info                                                *
 *                                                                                                           *
 *¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯*
*/

namespace webspell;

class Captcha
{
    private $hash;
    private $length = 5;
    private $type;
    private $noise = 100;
    private $linenoise = 10;
    private $valide_time = 20; /* captcha oder Transaktion ist für x Minuten gültig */
    private $math;
    private $math_max = 30;
    private $bgcol = array("r" => 255, "g" => 255, "b" => 255);
    private $fontcol = array("r" => 0, "g" => 0, "b" => 0);

    // Konvertiert Hex-Farben in RGB
    private function hex2rgb($col)
    {
        $col = str_replace("#", "", $col);
        $int = hexdec($col);
        return array(
            "r" => 0xFF & $int >> 0x10,
            "g" => 0xFF & ($int >> 0x8),
            "b" => 0xFF & $int
        );
    }

    // Konstruktor: Setze Captcha-Typ
    public function __construct()
    {
        $ds = mysqli_fetch_assoc(
            safe_query(
                "SELECT
                captcha_math,
                captcha_bgcol,
                captcha_fontcol,
                captcha_type,
                captcha_noise,
                captcha_linenoise
                FROM settings"
            )
        );

        // Farben aus der DB setzen, falls vorhanden
        if (mb_strlen($ds['captcha_bgcol']) == 7) {
            $this->bgcol = $this->hex2rgb($ds['captcha_bgcol']);
        }
        if (mb_strlen($ds['captcha_fontcol']) == 7) {
            $this->fontcol = $this->hex2rgb($ds['captcha_fontcol']);
        }

        // Setze Mathe-Captcha
        if ($ds['captcha_math'] == 1) {
            $this->math = 1;
        } elseif ($ds['captcha_math'] == 2) {
            $this->math = rand(0, 1);
        } else {
            $this->math = 0;
        }

        // Setze Captcha-Typ
        if ($ds['captcha_type'] == 1) {
            $this->type = 'g';
        } elseif (function_exists('imagecreatetruecolor') && ($ds['captcha_type'] == 2)) {
            $this->type = 'g';
        } else {
            $this->type = 't';
        }

        // Setze Noise-Werte
        $this->noise = $ds['captcha_noise'];
        $this->linenoise = $ds['captcha_linenoise'];

        // Alte Captchas löschen
        $this->clearOldCaptcha();
    }

    // Generiere den Text für das Captcha
    private function generateCaptchaText()
    {
        $captcha_shown = "";
        if ($this->math == 1) {
            $this->length = 6;
            $first = rand(1, $this->math_max);
            $captcha_result = $first;
            while (mb_strlen($first) < mb_strlen($this->math_max)) {
                $first = ' ' . $first;
            }
            $captcha_shown = (string)$first;
            if (rand(0, 1)) {
                $captcha_shown .= "+";
                $next = rand(1, $this->math_max);
                $captcha_result += $next;
            } else {
                $captcha_shown .= "-";
                $next = rand(1, $first - 1);
                $captcha_result -= $next;
            }
            while (mb_strlen($next) < mb_strlen($this->math_max)) {
                $next = ' ' . $next;
            }
            $captcha_shown .= $next;
            $captcha_shown .= "=";
        } else {
            for ($i = 0; $i < $this->length; $i++) {
                $int = rand(0, 9);
                $captcha_shown .= $int;
            }
            $captcha_result = $captcha_shown;
        }
        return array('text' => $captcha_shown, 'result' => $captcha_result);
    }

    // Erstelle das Captcha-Bild
    private function createCaptchaImage($text)
    {
        global $_language;
        $_language->readModule('captcha', true);
        global $new_chmod;

        // Erstelle das Bild
        $imgziel = imagecreatetruecolor(($this->length * 15) + 10, 25);
        $bgcolor = imagecolorallocate($imgziel, $this->bgcol['r'], $this->bgcol['g'], $this->bgcol['b']);
        $fontcolor = imagecolorallocate($imgziel, $this->fontcol['r'], $this->fontcol['g'], $this->fontcol['b']);
        $xziel = imagesx($imgziel);
        $yziel = imagesy($imgziel);
        imagefilledrectangle($imgziel, 0, 0, $xziel, $yziel, $bgcolor);

        // Noise hinzufügen
        for ($i = 0; $i < $this->linenoise; $i++) {
            $color = imagecolorallocate($imgziel, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($imgziel, rand(0, $xziel), rand(0, $yziel), rand(0, $xziel), rand(0, $yziel), $color);
        }

        for ($i = 0; $i < $this->noise; $i++) {
            imagesetpixel($imgziel, rand(0, $xziel), rand(0, $yziel), $fontcolor);
        }

        // Zeichen hinzufügen
        $length = mb_strlen($text);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            if ($char == "-" || $char == "+" || $char == "=") {
                imagesetthickness($imgziel, 2);
                if ($char == "-") {
                    imageline($imgziel, $i * 15, 13, $i * 15 + 8, 13, $fontcolor);
                }
                if ($char == "+") {
                    imageline($imgziel, $i * 15, 13, $i * 15 + 9, 13, $fontcolor);
                    imageline($imgziel, ($i * 15) + 5, 8, ($i * 15) + 5, 18, $fontcolor);
                }
                if ($char == "=") {
                    imageline($imgziel, $i * 15, 11, $i * 15 + 9, 11, $fontcolor);
                    imageline($imgziel, $i * 15, 15, $i * 15 + 9, 15, $fontcolor);
                }
            } else {
                $font = rand(2, 5);
                imagestring($imgziel, $font, $i * 15 + 5, 5, $char, $fontcolor);
            }
        }

        // Bild speichern
        imagejpeg($imgziel, 'tmp/' . $this->hash . '.jpg');
        @chmod('tmp/' . $this->hash . '.jpg', $new_chmod);

        // Bild-Tag zurückgeben
        return '<img src="tmp/' . $this->hash . '.jpg" alt="' . $_language->module['security_code'] . '" />';
    }

    // Erstelle Captcha-Hash und Bild
    public function createCaptcha()
    {
        $this->hash = md5(time() . rand(0, 10000));

        $captcha = $this->generateCaptchaText();
        $captcha_result = $captcha['result'];
        $captcha_text = $captcha['text'];

        if ($this->type == 'g') {
            $captcha_text = $this->createCaptchaImage($captcha_text);
        }

        // Captcha in der DB speichern
        safe_query(
            "INSERT INTO captcha (
                `hash`, `captcha`, `deltime`
            ) VALUES (
                '" . $this->hash . "',
                '" . $captcha_result . "',
                '" . (time() + ($this->valide_time * 60)) . "'
            )"
        );
        return $captcha_text;
    }

    // Erstelle Transaktions-Hash für Formulare
    public function createTransaction()
    {
        $this->hash = md5(time() . rand(0, 10000));
        safe_query(
            "INSERT INTO captcha (
                `hash`, `captcha`, `deltime`
            ) VALUES (
                '" . $this->hash . "',
                '0',
                '" . (time() + ($this->valide_time * 60)) . "'
            )"
        );
        return true;
    }

    // Gib den erstellten Hash zurück
    public function getHash()
    {
        return $this->hash;
    }

    // Überprüfe, ob die Eingabe mit dem Captcha übereinstimmt
    public function checkCaptcha($input, $hash)
    {
        if (mysqli_num_rows(
            safe_query(
                "SELECT `hash`
                    FROM captcha
                    WHERE `captcha` = '" . $input . "' AND `hash` = '" . $hash . "'"
            )
        )) {
            safe_query(
                "DELETE FROM captcha
                WHERE `captcha` = '" . $input . "' AND `hash` = '" . $hash . "'"
            );
            $file = 'tmp/' . $hash . '.jpg';
            if (file_exists($file)) {
                unlink($file);
            }
            return true;
        } else {
            return false;
        }
    }

    // Lösche alte Captcha-Dateien
    public function clearOldCaptcha()
    {
        $time = time();
        $ergebnis = safe_query("SELECT `hash` FROM captcha WHERE `deltime` < " . $time);
        while ($ds = mysqli_fetch_array($ergebnis)) {
            $file = 'tmp/' . $ds['hash'] . '.jpg';
            if (file_exists($file)) {
               @unlink($file);
            } else {
               @unlink('../' . $file);
            }
        }
        safe_query("DELETE FROM captcha WHERE `deltime` < " . $time);
    }
}
