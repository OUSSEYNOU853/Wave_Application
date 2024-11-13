<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de Transaction</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #6b46c1 0%, #4a148c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .content {
            padding: 40px 30px;
            background-color: white;
        }

        .message {
            background-color: #f8f4ff;
            border-left: 4px solid #6b46c1;
            padding: 20px;
            margin-bottom: 20px;
            color: #2d3748;
            line-height: 1.8;
            font-size: 16px;
        }

        .footer {
            background-color: #1a1a1a;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            display: inline-block;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, #6b46c1, #9f7aea);
            margin: 20px 0;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #6b46c1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #553c9a;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <!-- Remplacez par votre logo -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1>Notification de Transaction</h1>
        </div>

        <div class="content">
            <div class="message">
                {!! nl2br(e($messageContent['title'])) !!}
            </div>
            
            <div class="message">
                {!! nl2br(e($messageContent['body'])) !!}
            </div>

            <div style="text-align: center;">
                <a href="#" class="button">Accéder à mon compte</a>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>© {{ date('Y') }} Votre Entreprise. Tous droits réservés.</p>
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
            </div>
        </div>
    </div>
</body>
</html>