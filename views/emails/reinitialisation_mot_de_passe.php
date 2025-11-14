<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de mot de passe</title>
</head>
<body>
    <h2>Réinitialisation de votre mot de passe</h2>

    <p>Bonjour,</p>

    <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>

    <p>Veuillez cliquer sur le lien suivant pour définir un nouveau mot de passe :</p>

    <p><a href="<?= htmlspecialchars($resetLink ?? '') ?>" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Réinitialiser mon mot de passe</a></p>

    <p>Ou copiez ce lien dans votre navigateur :</p>
    <p><?= htmlspecialchars($resetLink ?? '') ?></p>

    <p><strong>Ce lien est valide pendant 24 heures.</strong></p>

    <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>

    <hr>
    <p style="color: #666; font-size: 12px;">
        Cordialement,<br>
        L'équipe CDS49
    </p>
</body>
</html>
