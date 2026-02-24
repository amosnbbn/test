<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() == 'id' ? 'Daftar Movie' : 'Movie List' }} - MovieStream</title>
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

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .content { 
            flex: 1; 
            animation: fadeIn 0.5s ease-out;
        }

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

        @keyframes scanline {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
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

        /* Headings */
        h1 {
            text-align: center;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #ff3b3b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
            padding-left: 50px;
        }

        h1::before {
            content: '▶';
            color: #ff0000;
            font-size: 30px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            animation: pulse 2s infinite;
            -webkit-text-fill-color: #ff0000;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ff0000, transparent);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* SEARCH BAR */
        .search-bar {
            text-align: center;
            margin-bottom: 40px;
            background: rgba(18, 18, 24, 0.7);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 16px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .search-bar form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .search-bar input, .search-bar select, .search-bar button {
            padding: 12px 20px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(10, 10, 15, 0.8);
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            flex: 1;
            min-width: 150px;
        }

        .search-bar input:focus, .search-bar select:focus {
            border-color: #ff0000;
            background: rgba(20, 20, 25, 0.9);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        }

        .search-bar input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .search-bar select option {
            background: #0a0a0f;
            color: white;
        }

        .search-bar button {
            background: linear-gradient(135deg, #ff3b3b, #ff0000);
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
            min-width: 120px;
        }

        .search-bar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 0, 0, 0.5);
            background: linear-gradient(135deg, #ff4f4f, #ff1a1a);
        }

        /* MOVIES GRID */
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .movie-box {
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        .movie-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent, rgba(255, 0, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .movie-box:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 30px rgba(255, 0, 0, 0.3);
            border-color: #ff0000;
        }

        .movie-box:hover::before {
            opacity: 1;
        }

        .movie-box img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
            border-bottom: 2px solid rgba(255, 0, 0, 0.3);
        }

        .movie-box:hover img {
            transform: scale(1.05);
        }

        .movie-title {
            padding: 12px;
            font-weight: 700;
            font-size: 16px;
            color: white;
            background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.8));
            position: relative;
        }

        .movie-title::before {
            content: '▶';
            color: #ff0000;
            font-size: 12px;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }

        .movie-info {
            padding: 12px;
            font-size: 13px;
            color: #ccc;
            text-align: left;
            max-height: 120px;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 0, 0, 0.2);
        }

        .movie-info p {
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .movie-info p strong {
            color: #ff3b3b;
        }

        /* Scrollbar styling */
        .movie-info::-webkit-scrollbar {
            width: 5px;
        }

        .movie-info::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        .movie-info::-webkit-scrollbar-thumb {
            background: #ff0000;
            border-radius: 5px;
        }

        /* Favorite Star */
        .fav-star {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 28px;
            color: #ccc;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .fav-star:hover {
            transform: scale(1.2) rotate(72deg);
            background: rgba(255, 0, 0, 0.3);
            border-color: #ff0000;
        }

        .fav-star.active {
            color: gold;
            text-shadow: 0 0 20px gold;
            border-color: gold;
        }

        /* No movies found */
        p[style*="text-align:center"] {
            text-align: center;
            font-size: 18px;
            color: #ccc;
            padding: 50px;
            background: rgba(18, 18, 24, 0.7);
            border-radius: 12px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            margin-top: 30px;
        }

        /* FOOTER */
        .footer {
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 0, 0, 0.3);
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.8),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            padding: 20px 40px;
            border-radius: 12px;
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 10;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -2px;
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

        .footer::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 0%, rgba(255, 0, 0, 0.1), transparent 70%);
            pointer-events: none;
        }

        .footer-left {
            color: #ffffff;
            font-size: 14px;
            letter-spacing: 0.3px;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
            position: relative;
            padding-left: 15px;
        }

        .footer-left::before {
            content: '▶';
            color: #ff0000;
            font-size: 12px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            animation: pulse 2s infinite;
            opacity: 0.8;
        }

        .footer-left strong, 
        .footer-left span {
            background: linear-gradient(135deg, #ffffff, #ff3b3b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 0, 0, 0.5);
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 0, 0, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #ff3b3b, #ff0000);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 0, 0, 0.5);
        }

        .logout-btn:hover::before {
            left: 100%;
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
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

            h1 {
                font-size: 32px;
                padding-left: 40px;
            }

            h1::before {
                font-size: 24px;
            }

            .search-bar form {
                flex-direction: column;
            }

            .search-bar input, .search-bar select, .search-bar button {
                width: 100%;
            }

            .footer {
                padding: 20px;
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .footer-left {
                padding-left: 0;
                padding-top: 15px;
            }

            .footer-left::before {
                left: 50%;
                top: 0;
                transform: translateX(-50%) rotate(90deg);
            }

            .logout-btn {
                width: 100%;
                justify-content: center;
            }

            .movies-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }
        }

        /* Link styling in navbar */
        .navbar a:last-child {
            margin-right: 0;
        }

        /* Favorite count styling */
        .navbar > div:last-child a {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="film-strip"></div>

    <div class="page-wrapper">
        <div class="content">
            <!-- NAVBAR -->
            <div class="navbar">
                <div>
                    <a href="{{ route('movies.index') }}">🏠 Home</a>
                    <a href="{{ route('movies.favorites') }}">
                        ⭐ {{ app()->getLocale()=='id'?'Favorit':'Favorites' }}
                    </a>
                </div>

                <div>
                    ⭐ {{ count(session('favorites', [])) }}
                    {{ app()->getLocale()=='id'?'Favorit':'Favorites' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    {{ app()->getLocale()=='id'?'Bahasa':'Language' }}:
                    <a href="{{ route('movies.lang','en') }}">EN</a> |
                    <a href="{{ route('movies.lang','id') }}">ID</a>
                </div>
            </div>

            <h1>
                {{ app()->getLocale()=='id'?'Daftar Movie':'Movie List' }}
            </h1>

            <div class="search-bar">
                <form action="{{ route('movies.index') }}" method="GET">
                    <input type="text" name="search" placeholder="{{ app()->getLocale()=='id'?'Cari movie...':'Search movie...' }}" value="{{ $query ?? '' }}">
                    <input type="text" name="year" placeholder="{{ app()->getLocale()=='id'?'Tahun':'Year' }}" value="{{ $year ?? '' }}">

                    <select name="genre">
                        <option value="">{{ app()->getLocale()=='id'?'Pilih Genre':'Select Genre' }}</option>
                        <option value="Action" {{ ($genre ?? '')=='Action'?'selected':'' }}>Action</option>
                        <option value="Comedy" {{ ($genre ?? '')=='Comedy'?'selected':'' }}>Comedy</option>
                        <option value="Documentary" {{ ($genre ?? '')=='Documentary'?'selected':'' }}>Documentary</option>
                        <option value="Horror" {{ ($genre ?? '')=='Horror'?'selected':'' }}>Horror</option>
                        <option value="Drama" {{ ($genre ?? '')=='Drama'?'selected':'' }}>Drama</option>
                    </select>

                    <button type="submit">
                        {{ app()->getLocale()=='id'?'Filter':'Filter' }}
                    </button>
                </form>
            </div>

            @if(count($movies) > 0)
            <div class="movies-grid">
            @foreach($movies as $movie)
            @php
                $isFav = in_array($movie['imdbID'], session('favorites', []));
            @endphp

            <div class="movie-box">
                <a href="{{ route('movies.show', $movie['imdbID']) }}" style="text-decoration: none; color: inherit;">
                    <img src="{{ $movie['Poster'] != 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/200x300?text=No+Image' }}">
                    <div class="movie-title">{{ $movie['Title'] }} ({{ $movie['Year'] }})</div>

                    <div class="movie-info">
                        <p><strong>{{ app()->getLocale()=='id'?'Aktor':'Actors' }}:</strong> {{ $movie['Actors'] ?? 'N/A' }}</p>
                        <p><strong>{{ app()->getLocale()=='id'?'Sinopsis':'Plot' }}:</strong> {{ $movie['Plot'] ?? 'N/A' }}</p>
                    </div>
                </a>

                <form action="{{ route('movies.favorite', $movie['imdbID']) }}" method="POST" style="position:absolute;top:0;right:0;">
                    @csrf
                    <button class="fav-star {{ $isFav?'active':'' }}">★</button>
                </form>
            </div>
            @endforeach
            </div>
            @else
            <p style="text-align:center;">
                {{ app()->getLocale()=='id'?'Tidak ada movie ditemukan':'No movies found' }}
            </p>
            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-left">
                🎬 <strong>Movie App</strong> <span>— {{ date('Y') }}</span> <br>
                <span>{{ app()->getLocale()=='id'?'Dibuat dengan Laravel':'Built with Laravel' }}</span>
            </div>

            <form action="{{ route('logout') }}" method="GET">
                @csrf
                <button class="logout-btn" type="submit">
                    🚪 <span>{{ app()->getLocale()=='id'?'Logout':'Logout' }}</span>
                    <span style="font-size: 10px; opacity: 0.7;">⏻</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>