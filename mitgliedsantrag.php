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

        <div class="introduction">
            <p>
                Schön, dass Du Lust auf eine Mitgliedschaft im <strong>VfcD - Verein für coole Dinge e.V.</strong> hast!
            </p>

            <p>
                Bitte trage deine Daten im folgenden Formular ein und entscheide selbst, wie hoch dein jährlicher Mitgliedsbeitrag
                sein soll - der Mindestbeitrag liegt bei gerade mal 12€ pro Jahr. Wir würden uns außerdem über ein Selfie freuen,
                damit wir auch wissen, welches Gesicht wir in unseren Reihen begrüßen dürfen.
            </p>

            <p>
                Wir freuen uns auf Dich!
            </p>
        </div>

        <form action="submitMitgliedsantrag.php" method="POST" enctype="multipart/form-data">
            <div class="formMitgliedsantrag">
                <fieldset>
                    <legend>👋 Persönliche Angaben</legend>

                    <div class="form-row">
                        <label for="inpVorname">Vorname</label>
                        <input type="text" maxlength="50" name="vorname" id="inpVorname" required>
                    </div>

                    <div class="form-row">
                        <label for="inpNachname">Nachname</label>
                        <input type="text" maxlength="50" name="nachname" id="inpNachname" required>
                    </div>

                    <div class="form-row">
                        <label for="inpGeburtsdatum">Geburtsdatum</label>
                        <input type="date" name="geburtsdatum" id="inpGeburtsdatum" required>
                    </div>

                    <div class="form-row">
                        <label for="inpWohnort">Wohnort</label>
                        <input type="text" name="wohnort" id="inpWohnort">
                    </div>

                    <div class="form-row">
                        <label for="inpEmail">E-Mail</label>
                        <input type="email" name="email" id="inpEmail" required>
                    </div>
                </fieldset>

                <br>

                <fieldset>
                    <legend>🤳 Selfie-Area</legend>

                    <div>
                        Wir freuen uns immer ein Gesicht zum Namen zu haben. Bitte füge daher diesem Antrag ein Foto von Dir hinzu!
                    </div>

                    <div style="width: 0px; height: 0px; overflow: hidden;">
                        <input id="inpSelfieImage" type="file" name="selfie">
                    </div>

                    <div class="selfieArea">
                        <div>
                            <img id="selfiePreview" src="#" style="display: none;">
                        </div>

                        <span class="description-empty">Hier klicken, um dein Bild auszuwählen!</span>
                        <span class="description-filled" style="display: none;">Hier klicken, um dein Bild zu ändern!</span>
                    </div>

                    <div>
                        Damit wir Dich und Deine Interessen besser einordnen können, beschreibe dich bitte mit drei Worten:
                    </div>

                    <div class="form-row">
                        <label for="inpHashtag1">Hashtag #1</label>
                        <input type="text" name="hashtag1" id="inpHashtag1" placeholder="z.B. Physik">
                    </div>

                    <div class="form-row">
                        <label for="inpHashtag2">Hashtag #2</label>
                        <input type="text" name="hashtag2" id="inpHashtag2" placeholder="z.B. Wandern">
                    </div>

                    <div class="form-row">
                        <label for="inpHashtag3">Hashtag #3</label>
                        <input type="text" name="hashtag3" id="inpHashtag3" placeholder="z.B. Computer">
                    </div>

                </fieldset>

                <br>

                <fieldset>
                    <legend>💰 Mitgliedsbeitrag</legend>

                    <div>
                        Bitte wähle aus, mit welcher Summe Du dich monatlich beim VfcD einbringen möchtest. Der Mindestbeitrag liegt
                        aktuell bei 1,-€ pro Monat. Die Mitglieder haben gemeinsam entschieden, dass der Beitrag immer für ein
                        Jahr im Voraus beglichen werden muss.
                    </div>

                    <br>

                    <div class="form-row" style="text-align:center;">
                        Ich möchte den Verein mit monatlich
                        <input type="number" name="beitrag" id="inpBeitrag" min="1" max="999" step="0.50" value="1.00" style="text-align: right; width: 70px;" required>
                        € unterstützen.<br>
                        Der ausgewählte Betrag von <span id="selBetrag">1,00€</span> entspricht einer jährlichen Zahlung von <span id="selBetragAnnual">12,00€</span>.
                    </div>

                </fieldset>

                <br>

                <fieldset>
                    <legend>📮 Antrag absenden</legend>

                    <div class="captcha">
                        Mensch oder 🤖?<br>
                        Aktuell nehmen wir nur Menschen im VfcD e.V. auf.
                    </div>

                    <br>

                    <div class="form-row captcha">
                        <label for="inpCaptcha"><img src="lib/captcha.php"></label>
                        <input type="text" id="inpCaptcha" name="captchaCode" value="" placeholder="Übertrage den linken Code" required>
                    </div>

                    <div class="submit">
                        <button>
                            Antrag absenden
                        </button>
                    </div>
                </fieldset>

            </div>
        </form>

    </div>

    <script>
        $(function() {
            console.log('%cDu hast es auch technisch drauf? Komm zu uns - wir haben Ingenieure, Entwickler, RD-ler und viele mehr!', 'color: #48D1CC;');

            $('#inpGeburtsdatum').attr('max', function() {
                let dT = new Date();
                return (dT.getFullYear() - 16) + "-" + (("0" + (dT.getMonth() + 1)).slice(-2)) + "-" + (("0" + dT.getDate()).slice(-2));
            });

            $('#inpBeitrag').on('change', function() {
                // Make sure input is always float with two decimals
                let valMonth = parseFloat($(this).val());

                if (valMonth < 1) {
                    valMonth = 1;
                }

                let valAnnual = valMonth * 12;

                $(this).val(valMonth.toFixed(2));
                $('#selBetrag').text(formatFloat(valMonth) + "€");
                $('#selBetragAnnual').text(formatFloat(valAnnual) + "€");
            });

            let formatFloat = function(value) {
                return (parseFloat(value).toFixed(2).replace('.', ','));
            };

            $('.selfieArea').on('click', function() {
                $('#inpSelfieImage').trigger('click');
            });

            $('#inpSelfieImage').on('change', function() {
                if (!this.files || !this.files[0]) {
                    return;
                }

                let fileReader = new FileReader();

                fileReader.onload = function(e) {
                    $('#selfiePreview').attr('src', e.target.result).fadeIn();
                    $('.selfieArea .description-empty').hide();
                    $('.selfieArea .description-filled').show();
                };

                fileReader.readAsDataURL(this.files[0]);
            });
        });
    </script>
</body>