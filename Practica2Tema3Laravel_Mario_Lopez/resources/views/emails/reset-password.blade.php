<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restabliment de contrasenya – {{ $appName }}</title>
    <style>
        *  { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            padding: 40px 16px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #0066FF 0%, #FF4D6D 100%);
            padding: 36px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header p {
            color: rgba(255,255,255,0.85);
            font-size: 14px;
            margin-top: 6px;
        }
        .body {
            padding: 36px 40px;
            font-size: 15px;
            line-height: 1.75;
        }
        .body p {
            margin-bottom: 16px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0066FF, #FF4D6D);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.2px;
        }
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 28px 0;
        }
        .url-label {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .url-fallback {
            word-break: break-all;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 12px;
            color: #475569;
            font-family: monospace;
        }
        .security-note {
            background: #fefce8;
            border-left: 4px solid #eab308;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            font-size: 13px;
            color: #713f12;
            margin-top: 24px;
            line-height: 1.6;
        }
        .security-note strong {
            display: block;
            margin-bottom: 4px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🔑 Restableix la teva contrasenya</h1>
            <p>{{ $appName }}</p>
        </div>

        <div class="body">
            <p>Hola,</p>
            <p>
                Hem rebut una sol·licitud per restablir la contrasenya del teu compte.
                Fes clic al botó de sota per crear-ne una nova.
            </p>
            <p>
                Aquest enllaç és vàlid durant <strong>60 minuts</strong> i
                només pot ser usat <strong>una sola vegada</strong>.
            </p>

            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn">Restablir la meva contrasenya</a>
            </div>

            <hr class="divider">

            <p class="url-label">O copia aquest URL al teu navegador</p>
            <div class="url-fallback">{{ $resetUrl }}</div>

            <div class="security-note">
                <strong>⚠️ Nota de seguretat</strong>
                Si no has sol·licitat aquest canvi, ignora aquest correu. La teva contrasenya
                <strong>no canviarà</strong> i l'enllaç caducarà automàticament.
                Si creus que algú ha intentat accedir al teu compte, contacta amb nosaltres.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $appName }} &mdash; Correu automàtic, si us plau no respon a aquest missatge.
        </div>
    </div>
</body>
</html>
