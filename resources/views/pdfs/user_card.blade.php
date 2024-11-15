<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Card</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        :root {
            --primary-color: #2563eb;
            --secondary-color: #6b7280;
            --background-color: #f3f4f6;
            --card-width: 500px;
            --card-height: 300px;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Conteneur des cartes recto/verso */
        .flip-card {
            position: relative;
            width: var(--card-width);
            height: var(--card-height);
            perspective: 1000px;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 1s;
            transform-style: preserve-3d;
            animation: flip 22s infinite;
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 2px 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Face avant de la carte */
        .flip-card-front {
            background-color: white;
        }

        /* Face arrière de la carte */
        .flip-card-back {
            background-color: white;
            transform: rotateY(180deg);
        }

        /* Ajout de l'animation de flip avec une pause */
        @keyframes flip {

            0%,
            25% {
                transform: rotateY(0);
            }

            /* Pause sur le recto */
            50%,
            75% {
                transform: rotateY(180deg);
            }

            /* Pause sur le verso */
            100% {
                transform: rotateY(0);
            }

            /* Retour au début */
        }

        /* Styles pour le contenu des cartes */
        .card-background {
            width: 100%;
            height: 100%;
            background-image: url('images.jpeg');
            background-size: cover;
            background-position: center;
        }

        .card-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info h2 {
            font-size: 16px;
            font-weight: 600;
            color: white;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
            margin: 0;
        }

        .user-info p {
            font-size: 14px;
            color: white;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
            margin: 4px 0 0 0;
            font-weight: 600;
        }

        .qr-code {
            background-color: white;
            border-radius: 12px 0 0 12px;
            padding: 12px;
            display: flex;
            justify-content: start;
            align-items: center;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            width: 50%;
            height: 50%;
            position: absolute;
            right: 0;
            top: 55%;
            transform: translateY(-50%);
        }

        .qrCode {
            position: relative;
            top: -45%;
            left: 80%;
            width: 8%;
        }

        .qr-code img {
            width: 85%;
            height: 100%;
        }

        .scan-text {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 10%;
            color: white;
            position: relative;
            top: -10%;
        }

        /* Styles pour la deuxième carte */
        .app-name {
            font-size: 24px;
            font-weight: 600;
            color: white;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        }

        .social-icons {
            display: flex;
            gap: 4px;
            color: black;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        }

        .user-name {
            position: relative;
            bottom: 10%;
            right: -60%;
            font-size: 16px;
            font-weight: 600;
            color: black;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            top: -10%;
            left: -10%;
            overflow: hidden;
        }

        .logo img {
            width: 40%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="flip-card">
        <div class="flip-card-inner">
            <!-- Face avant -->
            <div class="flip-card-front">
                <div class="card-background"></div>
                <div class="card-content">
                    <div class="user-info">
                        @if($data['profile_image'])
                            <img src="{{ public_path($data['profile_image']) }}" alt="Profile" class="profile-image">
                        @endif
                        <div class="user-details">
                            <h2>{{ $data['user']->firsname }} {{ $data['user']->lastname }}</h2>
                            <p>Number: {{ $data['user']->phone }}</p>
                        </div>
                    </div>
                    <img src="design.png" alt="" class="qrCode">
                    <div class="qr-code">
                        <img src="{{ $qrCodePath }}" alt="QR Code">
                    </div>
                    <div class="scan-text">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                            </path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span style="font-size: 10px;">Scan</span>
                    </div>
                </div>
            </div>
            <!-- Face arrière -->
            <div class="flip-card-back">
                <div class="card-background"></div>
                <div class="card-content">
                    <div class="app-name">Wave</div>
                    <div class="social-icons">
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-linkedin"></i>
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="logo">
                        <img src="design.png" alt="Logo">
                    </div>
                    <div class="user-name">© wave corporation</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>