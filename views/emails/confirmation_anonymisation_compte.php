<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de suppression de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }

        .content p {
            margin: 0 0 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Confirmation de suppression de compte</h2>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Nous vous confirmons que votre compte a été supprimé avec succès de notre plateforme.</p>
            <p>Toutes vos données personnelles associées à ce compte ont été effacées de nos systèmes, conformément à votre demande et à notre politique de confidentialité.</p>
            <p>Nous vous remercions d'avoir utilisé nos services.</p>
            <p>Cordialement,</p>
            <p>L'équipe CDS49</p>
        </div>
        <div class="footer">
            <p>&copy; <?php echo date("Y"); ?> CDS49. Tous droits réservés.</p>
        </div>
    </div>
</body>

</html>