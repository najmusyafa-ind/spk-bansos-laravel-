<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SPK Bantuan Sosial Kab. Purbalingga</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f0f4f8;
            display: flex;
            align-items: stretch;
        }

        /* ===== SPLIT LAYOUT ===== */
        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ===== LEFT PANEL — Branding ===== */
        .left-panel {
            width: 45%;
            background: linear-gradient(160deg, #1a3a6b 0%, #0f2349 50%, #0a1a3d 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* Decorative corner lines — government feel */
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #d4af37, #f5d061, #d4af37);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #d4af37, #f5d061, #d4af37);
        }

        /* Subtle watermark circle */
        .panel-watermark {
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.04);
        }

        .panel-watermark-2 {
            position: absolute;
            top: -60px;
            left: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.04);
        }

        .brand-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .brand-logo-wrap {
            width: 110px;
            height: 110px;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(212, 175, 55, 0.35);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 0 0 6px rgba(212, 175, 55, 0.08), 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .brand-logo-wrap img {
            width: 74px;
            height: 74px;
            object-fit: contain;
        }

        .brand-divider {
            width: 48px;
            height: 2px;
            background: linear-gradient(90deg, #d4af37, #f5d061);
            border-radius: 2px;
            margin: 0 auto 20px;
        }

        .brand-org {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: rgba(212, 175, 55, 0.85);
            margin-bottom: 10px;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.01em;
            margin-bottom: 6px;
        }

        .brand-region {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 0.02em;
            margin-bottom: 28px;
        }

        .brand-desc {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.38);
            line-height: 1.7;
            max-width: 260px;
            margin: 0 auto;
        }

        /* Thin horizontal rule */
        .brand-rule {
            width: 100%;
            max-width: 240px;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 24px auto;
        }

        .brand-year {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.25);
            font-weight: 500;
        }

        /* ===== RIGHT PANEL — Form ===== */
        .right-panel {
            flex: 1;
            background: #f8fafd;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
        }

        .form-box {
            width: 100%;
            max-width: 390px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-greeting {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #1a3a6b;
            margin-bottom: 8px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        /* Error */
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid #ef4444;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 22px;
            font-size: 13px;
            color: #b91c1c;
        }

        /* Input Groups */
        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #344155;
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #94a3b8;
            transition: color 0.2s;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            background: #fff;
            border: 1.5px solid #dde3ee;
            border-radius: 12px;
            padding: 13px 14px 13px 42px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
            -webkit-appearance: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .field-input::placeholder {
            color: #c0ccd8;
        }

        .field-input:focus {
            border-color: #1a3a6b;
            box-shadow: 0 0 0 3px rgba(26, 58, 107, 0.1), 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .field-wrap:focus-within .field-icon {
            color: #1a3a6b;
        }

        /* Remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1a3a6b;
            cursor: pointer;
            flex-shrink: 0;
            border-radius: 4px;
        }

        .remember-row label {
            font-size: 12px;
            color: #64748b;
            cursor: pointer;
            font-weight: 500;
            user-select: none;
        }

        /* Submit */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #1a3a6b, #0f2882);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.22s;
            box-shadow: 0 4px 16px rgba(26, 58, 107, 0.35);
            letter-spacing: 0.01em;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1e4a88, #122fa0);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(26, 58, 107, 0.45);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 12px;
            transition: transform 0.2s;
        }

        .btn-login:hover i {
            transform: translateX(3px);
        }

        /* Hint */
        .cred-hint {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e8eef4;
        }

        .cred-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: #f0f4f8;
            border-radius: 9px;
            padding: 9px 14px;
            font-size: 11px;
            color: #94a3b8;
        }

        .cred-box strong {
            color: #475569;
        }

        /* Footer */
        .right-footer {
            position: absolute;
            bottom: 20px;
            font-size: 11px;
            color: #b0bac7;
            text-align: center;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }

            .left-panel {
                width: 100%;
                padding: 36px 24px 32px;
                min-height: auto;
            }

            .left-panel::before,
            .left-panel::after {
                height: 4px;
            }

            .brand-logo-wrap {
                width: 80px;
                height: 80px;
                margin-bottom: 18px;
            }

            .brand-logo-wrap img {
                width: 54px;
                height: 54px;
            }

            .brand-name {
                font-size: 18px;
            }

            .brand-region {
                margin-bottom: 0;
            }

            .brand-desc,
            .brand-rule,
            .brand-year {
                display: none;
            }

            .right-panel {
                padding: 32px 20px 56px;
                justify-content: flex-start;
            }

            .form-box {
                max-width: 100%;
            }

            .form-title {
                font-size: 22px;
            }

            .right-footer {
                left: 0;
                right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">

        {{-- ===== LEFT — Government Branding ===== --}}
        <div class="left-panel">
            <div class="panel-watermark"></div>
            <div class="panel-watermark-2"></div>

            <div class="brand-content">
                <div class="brand-logo-wrap">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Purbalingga"
                        onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/b/b8/Lambang_Kabupaten_Purbalingga.png/60px-Lambang_Kabupaten_Purbalingga.png'">
                </div>

                <div class="brand-org">Pemerintah Kabupaten Purbalingga</div>
                <div class="brand-divider"></div>
                <div class="brand-name">SPK Bantuan Sosial</div>
                <div class="brand-region">Sistem Pendukung Keputusan</div>

                <div class="brand-rule"></div>

                <div class="brand-desc">
                    Platform pendukung keputusan resmi untuk penentuan penerima bantuan sosial berbasis metode AHP dan
                    SAW.
                </div>

                <div class="brand-rule"></div>
                <div class="brand-year">&copy; {{ date('Y') }} Dinas Sosial Kab. Purbalingga</div>
            </div>
        </div>

        {{-- ===== RIGHT — Login Form ===== --}}
        <div class="right-panel">
            <div class="form-box">

                <div class="form-header">
                    <div class="form-greeting">Portal Masuk</div>
                    <div class="form-title">Selamat Datang</div>
                    <div class="form-subtitle">Masuk menggunakan akun yang telah terdaftar</div>
                </div>

                @if($errors->any())
                    <div class="error-box">
                        <i class="fas fa-circle-xmark" style="color:#ef4444; margin-top:1px;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="field-group">
                        <label class="field-label">Alamat Email</label>
                        <div class="field-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}" class="field-input"
                                placeholder="admin@bansos.id" required autocomplete="email">
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Kata Sandi</label>
                        <div class="field-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password" class="field-input" placeholder="••••••••" required
                                autocomplete="current-password">
                        </div>
                    </div>

                    <div class="remember-row">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya di perangkat ini</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-right-to-bracket"></i>
                        Masuk ke Sistem
                        <i class="fas fa-arrow-right" style="margin-left:4px;"></i>
                    </button>
                </form>

                <div class="cred-hint">
                    <div class="cred-box">
                        <i class="fas fa-circle-info"></i>
                        <span>Default: <strong>admin@bansos.id</strong> / <strong>admin123</strong></span>
                    </div>
                </div>
            </div>

            <div class="right-footer">Akses terbatas untuk petugas berwenang</div>
        </div>

    </div>
</body>

</html>