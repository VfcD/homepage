<?php

session_start();

$recipientMail = 'vfcd@vfcd.org';
$allowFileTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

$fields = [
    'required' => [
        'vorname'       => 'Vorname',
        'nachname'      => 'Nachname',
        'geburtsdatum'  => 'Geburtsdatum',
        'email'         => 'E-Mail',
        'beitrag'       => 'Monatlicher Beitrag',
        'captchaCode'       => 'Captcha',
    ],
    'optional' => [
        'wohnort'       => 'Wohnort',
        'selfie'        => 'Profilbild',
        'hashtag1'      => 'Hashtag 1',
        'hashtag2'      => 'Hashtag 2',
        'hashtag3'      => 'Hashtag 3',
    ]
];

$values = [];

$errors = [];

# Required fields
foreach ($fields['required'] as $fieldKey => $fieldName) {
    if (!isset($_POST[$fieldKey])) {
        $errors[] = sprintf('%s fehlt', $fieldName);
        continue;
    }

    $fieldValue = validateHtml($_POST[$fieldKey]);

    if (!validateRequired($fieldValue)) {
        $errors[] = sprintf('%s fehlt', $fieldName);
    }

    $values[$fieldKey] = $fieldValue;
}

// Validate specific fields
if (!validateAlphanumeric($values['vorname'])) {
    $errors[] = sprintf('Vorname ist ungültig');
}

if (!validateAlphanumeric($values['nachname'])) {
    $errors[] = sprintf('Nachname ist ungültig');
}

if (!validateEmail($values['email'])) {
    $errors[] = sprintf('E-Mail ist unültig');
}

if (!validateCaptcha($values['captchaCode'])) {
    $errors[] = sprintf('Der eingegebene Prüfcode ist falsch');
}

# Optional fields
foreach ($fields['optional'] as $fieldKey => $fieldName) {
    if (!isset($_POST[$fieldKey])) {
        continue;
    }

    $values[$fieldKey] = validateHtml($_POST[$fieldKey]);
}

# Selfie Image
$selfieUrl = '';
if (count($errors) <= 0 && $_FILES['selfie']['name']) {
    $targetDir = 'uploads/';
    $fileName = uniqid('uu' ,true) . basename($_FILES['selfie']['name']);
    $targetFilePath = sprintf('%s%s', $targetDir, $fileName);
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileType), $allowFileTypes, true)) {
        echo 'Der Dateityp deines Selfies ist nicht gestattet.';
        exit;
    }

    if (move_uploaded_file($_FILES['selfie']['tmp_name'], $targetFilePath)) {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $selfieUrl = sprintf('%s://%s/%s', $protocol, $_SERVER['HTTP_HOST'], $targetFilePath);
    }
}

if (count($errors) <= 0) {
    sendNotificationMail($recipientMail, $values, $selfieUrl);
}

# Mailing methods
function sendNotificationMail($to, $data, $selfieUrl) {
    $subject = sprintf('Neuer Mitgliedsantrag: %s %s', $data['vorname'], $data['nachname']);
    $replyToMail = '-f' . $data['email'];

    $body = <<<EOD
    <p>
        <b>Ahoi-hoi!</b><br>
        Es ist ein neuer Mitgliedsantrag eingegangen:
    </p>
    <table>
        <tr>
            <th>Vorname</th>
            <td>{$data['vorname']}</td>
        </tr>
        <tr>
            <th>Nachname</th>
            <td>{$data['nachname']}</td>
        </tr>
        <tr>
            <th>Geburtsdatum</th>
            <td>{$data['geburtsdatum']}</td>
        </tr>       
        <tr>
            <th>E-Mail</th>
            <td><a href="mailto:{$data['email']}">{$data['email']}</a></td>
        </tr>
        <tr>
            <th>Wohnort</th>
            <td>{$data['wohnort']}</td>
        </tr>
        <tr>
            <th>Monatlicher Beitrag</th>
            <td>{$data['beitrag']} EUR</td>
        </tr>
        <tr>
            <th>Hashtags</th>
            <td>{$data['hashtag1']}, {$data['hashtag2']}, {$data['hashtag3']}</td>
        </tr>
        <tr>
            <th>Selfie Image</th>
            <td><a href="$selfieUrl" target="_blank">Download (falls vorhanden)</a></td>
        </tr>
    </table>
EOD;

    $headers = [
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html',
        'charset' => 'utf-8',
    ];

    mail($to, $subject, $body, $headers, $replyToMail);
}

# Validation methods
function validateRequired($str) {
    return ($str !== '');
}

function validateHtml($str) {
    return trim(htmlspecialchars($str));
}

function validateAlphanumeric($str) {
    return preg_match('/^[a-zA-Z0-9\s]+$/', $str);
}

function validateEmail($str) {
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}

function validateCaptcha($str) {
    $captchaCode = $_SESSION['captchaCode'];

    return ($str === $captchaCode);
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <link rel="stylesheet" href="css.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="lib/jquery-3.4.1.min.js"></script>
    <title>VfcD - Verein für coole Dinge e.V.</title>
</head>

<body>
<div class="Content tighter">
    <h1 >Mitgliedsantrag</h1>

    <?php if (count($errors) <= 0): ?>
    <div class="introduction">
        <p>
            Wir haben Deinen Antrag erhalten und werden uns schnellstmöglich mit Dir in Verbindung setzen.
        </p>

        <p>
            Wir freuen, dass du an Bord bist!
        </p>

        <p style="text-align: center;">
            <a href="/index.html">Zurück zur Vereinsseite</a>
        </p>
    </div>
    <?php endif; ?>

    <?php if (count($errors) > 0): ?>
        <div class="introduction">
            <p>
                Leider gibt es Probleme bei deinem Antrag:
            </p>

            <p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </p>

            <p style="text-align: center;">
                <a href="#" onclick="window.history.back()">Angaben im Formular korrigieren</a>
            </p>
        </div>
    <?php endif; ?>

</div>

</body>
