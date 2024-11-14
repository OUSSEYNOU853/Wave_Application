<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Utilisateur</title>
    <style>
        @page {
            margin: 0;
            size: 85.6mm 53.98mm; /* Taille standard d'une carte bancaire */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .card-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .card-back {
            position: relative;
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path("images.jpeg") }}');
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            padding: 10px;
        }

        .company-logo {
            position: absolute;
            bottom: 10px;
            right: 20px;
            width: 48px;
            height: 64px;
        }

        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .qr-box {
            background: white;
            width: 50%;
            height: 85%;
            border-radius: 24px;
            padding: 12px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .qr-code {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .scan-text {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #333;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .user-info {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .user-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .user-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .profile-image {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-back">
            <!-- Logo entreprise -->
            <img src="{{ public_path('images/design.png') }}" alt="Logo" class="company-logo">
            
            <!-- Informations utilisateur -->
            <div class="user-info">
                <h2>{{ $data['user']->firsname }} {{ $data['user']->lastname }}</h2>
                <p>ID: {{ $data['user']->phone }}</p>
                <p>{{ $data['user']->email }}</p>
            </div>

            <!-- Photo de profil si disponible -->
            @if($data['profile_image'])
                <img src="{{ public_path($data['profile_image']) }}" alt="Profile" class="profile-image">
            @endif

            <!-- Container QR Code -->
            <div class="qr-container">
                <div class="qr-box">
                    <img src="{{ $qrCodePath }}" alt="QR Code" class="qr-code">
                    <div class="scan-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span>Scanner</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>