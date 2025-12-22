<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Luxid Framework' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .logo {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .tagline {
            font-size: 24px;
            color: #666;
            margin-bottom: 40px;
            font-weight: 300;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .feature {
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .feature h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .feature p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f1f3f9;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .tech-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .tech-item {
            text-align: center;
        }

        .tech-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .tech-value {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .footer {
            margin-top: 40px;
            color: #888;
            font-size: 14px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .welcome-card {
                padding: 40px 20px;
            }

            .logo {
                font-size: 36px;
            }

            .tagline {
                font-size: 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <div class="logo">LUXID</div>
        <div class="tagline">A Modern PHP Framework for Web Artisans</div>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">🚀</div>
                <h3>Lightning Fast</h3>
                <p>Built for performance with minimal overhead and maximum efficiency.</p>
            </div>

            <div class="feature">
                <div class="feature-icon">✨</div>
                <h3>Elegant Syntax</h3>
                <p>Expressive, beautiful syntax that makes development a joy.</p>
            </div>

            <div class="feature">
                <div class="feature-icon">🔧</div>
                <h3>Powerful Tools</h3>
                <p>Everything you need to build modern web applications.</p>
            </div>
        </div>

        <div class="cta-buttons">
            <a href="https://github.com/yourusername/luxid" class="btn btn-primary" target="_blank">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
                View on GitHub
            </a>

            <a href="https://luxid.dev/docs" class="btn btn-secondary" target="_blank">
                📚 View Documentation
            </a>
        </div>

        <div class="tech-info">
            <div class="tech-item">
                <div class="tech-label">Luxid Version</div>
                <div class="tech-value"><?= $version ?? '1.0.0' ?></div>
            </div>

            <div class="tech-item">
                <div class="tech-label">PHP Version</div>
                <div class="tech-value"><?= $phpVersion ?? PHP_VERSION ?></div>
            </div>

            <div class="tech-item">
                <div class="tech-label">Server</div>
                <div class="tech-value"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Development Server' ?></div>
            </div>
        </div>

        <div class="footer">
            <p>© <?= date('Y') ?> Luxid Framework. Created with ❤️ by the Luxid Community.</p>
            <p>Ready to start building? Check out the <a href="https://luxid.dev/docs">documentation</a>.</p>
        </div>
    </div>
</body>
</html>
