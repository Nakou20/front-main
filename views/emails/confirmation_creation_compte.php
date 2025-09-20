<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez CDS 49</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px 0;
        }

        .content h1 {
            color: #333;
        }

        .content p {
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #777;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <h1>Bienvenue sur la plateforme CDS 49, <?= htmlspecialchars($prenomeleve ?? 'Nouvel utilisateur') ?> !</h1>
            <p>Nous sommes ravis de vous accueillir parmi nous.</p>
            <p>Votre compte a été créé avec succès. Vous pouvez dès à présent explorer toutes les fonctionnalités que nous offrons pour faciliter votre apprentissage de la conduite.</p>

            <br>
            <br>

            <p>À très bientôt sur les routes !</p>
            <p>L'équipe CDS 49</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> CDS 49. Tous droits réservés.</p>
            <p>Ceci est un email automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>

</html>