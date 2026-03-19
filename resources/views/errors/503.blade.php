<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Maintenance - Nusa Belanja</title>
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0fdf4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(15, 76, 32, 0.10);
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            border: 1px solid #dcfce7;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 800;
            color: #0F4C20;
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(15,76,32,0.2); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 12px rgba(15,76,32,0); }
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            color: #0F4C20;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 999px;
            margin-bottom: 28px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: blink 1.2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        /* ===== COUNTDOWN ===== */
        .countdown-label {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .countdown-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 14px;
            padding: 12px 18px;
            min-width: 72px;
        }

        .countdown-box .num {
            font-size: 28px;
            font-weight: 800;
            color: #0F4C20;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .countdown-box .lbl {
            font-size: 10px;
            font-weight: 600;
            color: #6b7280;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .countdown-sep {
            font-size: 26px;
            font-weight: 800;
            color: #0F4C20;
            align-self: center;
            margin-top: -6px;
        }

        /* Progress bar */
        .progress-wrap {
            background: #f3f4f6;
            border-radius: 999px;
            height: 6px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #0F4C20, #22c55e);
            border-radius: 999px;
            transition: width 1s linear;
        }

        .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 24px 0;
        }

        .info-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
            color: #9ca3af;
        }

        .footer {
            margin-top: 32px;
            font-size: 12px;
            color: #9ca3af;
        }

        .footer span {
            color: #0F4C20;
            font-weight: 700;
        }

        @media (max-width: 480px) {
            .card { padding: 36px 24px; }
            h1 { font-size: 19px; }
            .countdown-box { min-width: 58px; padding: 10px 12px; }
            .countdown-box .num { font-size: 22px; }
        }
    </style>
</head>
<body>

    <div class="card">

        {{-- Logo --}}
        <div class="logo">
            <span class="logo-text">Nusa Belanja</span>
        </div>

        {{-- Animated Icon --}}
        <div class="icon-wrap">
            <svg width="38" height="38" fill="none" stroke="#0F4C20" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
        </div>

        {{-- Title --}}
        <h1>Sedang Dalam Pemeliharaan</h1>

        {{-- Subtitle --}}
        <p class="subtitle">
            Kami sedang melakukan pembaruan untuk memberikan pengalaman belanja yang lebih baik.
            Mohon bersabar, kami akan segera kembali.
        </p>

        {{-- Status Badge --}}
        <div class="badge">
            <div class="dot"></div>
            Tim kami sedang bekerja
        </div>

        {{-- Countdown Label --}}
        <p class="countdown-label">Estimasi selesai dalam</p>

        {{-- Countdown Timer --}}
        <div class="countdown">
            <div class="countdown-box">
                <span class="num" id="cd-hours">02</span>
                <span class="lbl">Jam</span>
            </div>
            <div class="countdown-sep">:</div>
            <div class="countdown-box">
                <span class="num" id="cd-minutes">00</span>
                <span class="lbl">Menit</span>
            </div>
            <div class="countdown-sep">:</div>
            <div class="countdown-box">
                <span class="num" id="cd-seconds">00</span>
                <span class="lbl">Detik</span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="progress-wrap">
            <div class="progress-bar" id="progress-bar" style="width: 0%"></div>
        </div>

        <div class="divider"></div>

        {{-- Info --}}
        <div class="info-row">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Halaman akan otomatis diperbarui setelah selesai proses maintenance
        </div>

    </div>

    <div class="footer">
        &copy; {{ date('Y') }} <span>Nusa Belanja</span> &mdash; Terima kasih atas kesabaranmu
    </div>

    @php
    $startFile = storage_path('framework/maintenance_start.txt');
    $startTime = file_exists($startFile) ? (int) trim(file_get_contents($startFile)) : time();
    $duration  = 2 * 60 * 60; // 2 jam dalam detik
    $endTime   = $startTime + $duration;

    // Jika sudah lewat 2 jam, hitung siklus berikutnya
    $now = time();
    while ($endTime <= $now) {
        $endTime += $duration;
    }
@endphp

<script>
    // End time dari server (akurat)
    const END_TIME = {{ $endTime }};
    const DURATION = {{ $duration }};

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function tick() {
        const now       = Math.floor(Date.now() / 1000);
        let   remaining = END_TIME - now;

        if (remaining < 0) remaining = 0;

        const hours   = Math.floor(remaining / 3600);
        const minutes = Math.floor((remaining % 3600) / 60);
        const seconds = remaining % 60;

        document.getElementById('cd-hours').textContent   = pad(hours);
        document.getElementById('cd-minutes').textContent = pad(minutes);
        document.getElementById('cd-seconds').textContent = pad(seconds);

        // Progress bar
        const elapsed  = DURATION - remaining;
        const progress = Math.min((elapsed / DURATION) * 100, 100);
        document.getElementById('progress-bar').style.width = progress + '%';
    }

    tick();
    setInterval(tick, 1000);

    // Auto reload cek setiap 2 menit
    setInterval(() => {
        fetch(window.location.href, { method: 'HEAD' })
            .then(res => {
                if (res.status !== 503) window.location.reload();
            })
            .catch(() => {});
    }, 2 * 60 * 1000);
</script>

</body>
</html>