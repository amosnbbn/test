<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MovieStream</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #0a0a0f;
            background-image: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.03) 0%, transparent 30%),
                              radial-gradient(circle at 70% 70%, rgba(255, 0, 0, 0.03) 0%, transparent 30%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
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

        .login-box {
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            padding: 45px 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                        0 0 20px rgba(255, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.5s ease-out;
            margin-bottom: 30px;
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

        /* Dekorasi garis merah khas streaming */
        .login-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #ff3b3b, #ff0000, #cc0000);
            border-radius: 4px 0 0 4px;
            box-shadow: 0 0 15px #ff0000;
        }

        .login-box h2 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: left;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: linear-gradient(135deg, #ffffff 0%, #ff3b3b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            display: inline-block;
        }

        .login-box h2::before {
            content: '▶';
            color: #ff0000;
            font-size: 20px;
            margin-right: 10px;
            -webkit-text-fill-color: #ff0000;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        input {
            display: block;
            width: 100%;
            padding: 15px 18px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(10, 10, 15, 0.8);
            color: #ffffff;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            letter-spacing: 0.3px;
        }

        input:focus {
            border-color: #ff0000;
            background: rgba(20, 20, 25, 0.9);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.4);
            font-weight: 300;
        }

        input:hover {
            border-color: rgba(255, 0, 0, 0.5);
        }

        button {
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #ff3b3b, #ff0000);
            color: white;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.3);
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 0, 0, 0.5);
            background: linear-gradient(135deg, #ff4f4f, #ff1a1a);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            color: #ff6b6b;
            background: rgba(255, 0, 0, 0.1);
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 3px solid #ff0000;
            font-size: 14px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Efek lampu sorot */
        .login-box::before {
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

        /* FOOTER STYLES */
        .login-footer {
            background: rgba(18, 18, 24, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 0, 0, 0.3);
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.8),
                        0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            width: 100%;
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

        /* Efek garis merah di atas footer */
        .login-footer::before {
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

        @keyframes scanline {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        .footer-left {
            color: #ffffff;
            font-size: 14px;
            letter-spacing: 0.3px;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
            position: relative;
            padding-left: 15px;
        }

        /* Efek film strip di samping kiri teks */
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

        /* Efek gradien pada teks */
        .footer-left strong, 
        .footer-left span {
            background: linear-gradient(135deg, #ffffff, #ff3b3b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600;
        }

        .footer-right {
            display: flex;
            gap: 20px;
        }

        .footer-link {
            color: #ffffff;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            position: relative;
            padding: 5px 0;
        }

        .footer-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: #ff0000;
            transition: width 0.3s ease;
        }

        .footer-link:hover {
            color: #ff3b3b;
        }

        .footer-link:hover::before {
            width: 100%;
        }

        .footer-copyright {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            margin-left: 20px;
        }

        /* Efek lampu sorot di footer */
        .login-footer::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 0%, rgba(255, 0, 0, 0.1), transparent 70%);
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-box {
                margin: 20px;
                padding: 30px 20px;
            }

            .login-footer {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
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

            .footer-right {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .footer-copyright {
                margin-left: 0;
                text-align: center;
                width: 100%;
            }
        }

        /* Container untuk konten utama */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
            padding-bottom: 100px; /* Memberi ruang untuk footer */
        }
    </style>
</head>
<body>
    <div class="film-strip"></div>
    
    <div class="main-container">
        <div class="login-box">
            <h2>Login</h2>
            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="login-footer">
        <div class="footer-left">
            🎬 <strong>MovieStream</strong> <span>— {{ date('Y') }}</span>
        </div>
        
        <div class="footer-right">
            
            <span class="footer-copyright">
                {{ app()->getLocale()=='id'?'Dibuat dengan Laravel':'Built with Laravel' }}
            </span>
        </div>
    </footer>
</body>
</html>