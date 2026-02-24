<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['Title'] ?? 'Movie Detail' }} - MovieStream</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0a0a0f;
            background-image: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03) 0%, transparent 30%),
                              radial-gradient(circle at 70% 70%, rgba(255, 0, 0, 0.03) 0%, transparent 30%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            color: #ffffff;
        }

        /* Efek overlay film */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0, 0, 0, 0.03) 0px, rgba(0, 0, 0, 0.03) 2px, transparent 2px, transparent 4px);
            pointer-events: none;
            z-index: 1;
        }

        /* Efek film strip di background */
        .film-strip {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.1;
        }

        .film-strip::before,
        .film-strip::after {
            content: '';
            position: fixed;
            width: 100%;
            height: 8px;
            background: repeating-linear-gradient(90deg, #ff0000 0px, #ff0000 20px, transparent 20px, transparent 40px);
            animation: slide 20s linear infinite;
        }

        .film-strip::before {
            top: 20px;
        }

        .film-strip::after {
            bottom: 20px;
            animation-direction: reverse;
        }

        @keyframes slide {
            from { background-position: 0 0; }
            to { background-position: 400px 0; }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes scanline {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            color: white;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
            z-index: 10;
        }

        .navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #ff3b3b, #ff0000, #cc0000);
            box-shadow: 0 0 15px #ff0000;
        }

        .navbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                #ff0000 20%, 
                #ff3b3b 50%, 
                #ff0000 80%, 
                transparent 100%);
            animation: scanline 3s linear infinite;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-right: 20px;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 0, 0, 0.2);
        }

        .navbar a:hover {
            background: rgba(255, 0, 0, 0.2);
            border-color: #ff0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
        }

        .navbar > div:last-child {
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar > div:last-child a {
            margin-right: 5px;
            padding: 4px 10px;
            background: transparent;
            border: 1px solid rgba(255, 0, 0, 0.3);
        }

        /* CONTAINER */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            position: relative;
            z-index: 10;
            animation: fadeIn 0.5s ease-out;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 0, 0, 0.1), transparent 70%);
            border-radius: 18px;
            z-index: -1;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        .container h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #ff3b3b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
            padding-left: 40px;
        }

        .container h1::before {
            content: '▶';
            color: #ff0000;
            font-size: 24px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            animation: pulse 2s infinite;
            -webkit-text-fill-color: #ff0000;
        }

        .container h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 40px;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #ff0000, transparent);
        }

        /* FLEX CONTAINER */
        .flex {
            display: flex;
            gap: 40px;
            margin-top: 30px;
        }

        /* IMAGE */
        img {
            width: 350px;
            border-radius: 12px;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.5),
                        0 0 0 2px rgba(255, 0, 0, 0.3) inset,
                        0 0 20px rgba(255, 0, 0, 0.3);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        img:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 35px rgba(255, 0, 0, 0.4),
                        0 0 0 2px #ff0000 inset;
        }

        /* TEXT CONTENT */
        .flex > div:last-child {
            flex: 1;
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid rgba(255, 0, 0, 0.2);
        }

        .flex p {
            margin-bottom: 15px;
            line-height: 1.6;
            color: #e0e0e0;
            font-size: 16px;
            position: relative;
            padding-left: 15px;
        }

        .flex p::before {
            content: '•';
            color: #ff0000;
            position: absolute;
            left: 0;
            font-size: 20px;
            animation: pulse 2s infinite;
        }

        .flex p strong {
            color: #ff3b3b;
            font-weight: 600;
            margin-right: 10px;
            background: rgba(255, 0, 0, 0.1);
            padding: 3px 8px;
            border-radius: 4px;
            border-left: 2px solid #ff0000;
        }

        /* FAVORITE BUTTON */
        .fav-btn {
            background: linear-gradient(135deg, #ff3b3b, #ff0000);
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
            margin-top: 20px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .fav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .fav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 0, 0, 0.6);
            background: linear-gradient(135deg, #ff4f4f, #ff1a1a);
        }

        .fav-btn:hover::before {
            left: 100%;
        }

        .fav-btn:active {
            transform: translateY(0);
        }

        /* Rating special styling */
        .flex p:contains('Rating') strong {
            background: rgba(255, 215, 0, 0.2);
            border-left-color: gold;
        }

        .flex p:contains('Rating') {
            color: gold;
        }

        /* Plot section */
        .flex p:last-of-type {
            background: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #ff0000;
            font-style: italic;
            margin-top: 10px;
        }

        .flex p:last-of-type strong {
            background: transparent;
            padding: 0;
            border-left: none;
            color: #ff3b3b;
            font-style: normal;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }

            .navbar > div {
                width: 100%;
                text-align: center;
            }

            .navbar a {
                display: inline-block;
                margin: 5px;
            }

            .container {
                padding: 20px;
            }

            .container h1 {
                font-size: 28px;
                padding-left: 35px;
            }

            .container h1::before {
                font-size: 20px;
            }

            .flex {
                flex-direction: column;
                gap: 20px;
            }

            img {
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
                display: block;
            }

            .flex > div:last-child {
                padding: 15px;
            }

            .flex p {
                font-size: 14px;
            }

            .fav-btn {
                padding: 12px 20px;
                font-size: 14px;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #ff0000;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ff3b3b;
        }
    </style>
</head>
<body>
    <div class="film-strip"></div>

    <div class="navbar">
        <div>
            <!-- ✅ HOME BUTTON -->
            <a href="{{ route('movies.index') }}">🏠 {{ app()->getLocale()=='id'?'Home':'Home' }}</a>

            <a href="{{ route('movies.favorites') }}">
                ⭐ {{ app()->getLocale()=='id'?'Favorit':'Favorites' }}
            </a>
        </div>

        <div>
            {{ app()->getLocale()=='id'?'Bahasa':'Language' }}:
            <a href="{{ route('movies.lang','en') }}">EN</a> |
            <a href="{{ route('movies.lang','id') }}">ID</a>
        </div>
    </div>

    <div class="container">
        <h1>{{ $details['Title'] ?? '-' }}</h1>

        <div class="flex">
            <div>
                <img src="{{ $details['Poster']!='N/A' ? $details['Poster'] : 'https://via.placeholder.com/300x450?text=No+Poster' }}">
            </div>

            <div>
                <p><strong>{{ app()->getLocale()=='id'?'Tahun':'Year' }}:</strong> {{ $details['Year'] ?? '-' }}</p>
                <p><strong>{{ app()->getLocale()=='id'?'Genre':'Genre' }}:</strong> {{ $details['Genre'] ?? '-' }}</p>
                <p><strong>{{ app()->getLocale()=='id'?'Aktor':'Actors' }}:</strong> {{ $details['Actors'] ?? '-' }}</p>
                <p><strong>{{ app()->getLocale()=='id'?'Rating':'Rating' }}:</strong> {{ $details['imdbRating'] ?? '-' }} ⭐</p>
                <p>
                    <strong>{{ app()->getLocale()=='id'?'Sinopsis':'Plot' }}:</strong><br>
                    {{ $details['Plot'] ?? '-' }}
                </p>

                <!-- FAVORITE BUTTON -->
                <form action="{{ route('movies.favorite', $details['imdbID']) }}" method="POST">
                    @csrf
                    <button class="fav-btn">
                        {{ $isFav
                            ? (app()->getLocale()=='id'?'★ Hapus dari Favorit':'★ Remove Favorite')
                            : (app()->getLocale()=='id'?'★ Tambah ke Favorit':'★ Add Favorite')
                        }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>