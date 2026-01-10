<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status | Secure Activation</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10F0B2;
            --primary2: #00291dff;
            --bg: #fdfeff;
            --text-dark: #1a1c21;
            --text-light: #64748b;
            --btn: #1f1f1fff;
            --btn-hover: #b30000ff;
            --glass: rgba(255, 255, 255, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-dark);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Mesh Gradient Background */
        .mesh-container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            background: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                        radial-gradient(at 50% 0%, #10F0B2 0, transparent 50%), 
                        radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-color: #f8fafc;
            filter: blur(80px);
            opacity: 0.15;
        }

        /* Glass Card */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 4rem 3rem;
            width: 100%;
            max-width: 520px;
            text-align: center;
            box-shadow: 0 32px 64px -12px #10F0B263;
            transition: transform 0.3s ease;
        }

        /* Status Icon with Ring */
        .status-header {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
        }

        .icon-circle {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px #10F0B2c2;
        }

        .icon-circle svg {
            width: 45px;
            height: 45px;
            color: var(--primary);
        }

        .orbit-ring {
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            border-top-color: transparent;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate { 100% { transform: rotate(360deg); } }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 0.75rem;
            color: #111827;
        }

        p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-light);
            margin-bottom: 2.5rem;
        }

        /* Modern Progress Tracker */
        .tracker {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .tracker::after {
            content: '';
            position: absolute;
            height: 3px;
            background: #e2e8f0;
            width: 100%;
            top: 15px;
            z-index: 0;
        }

        .track-step {
            position: relative;
            z-index: 1;
            background: #f1f5f9;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            color: #94a3b8;
            border: 3px solid var(--bg);
        }

        .track-step.complete {
            background: var(--primary);
            color: white;
        }

        .track-step.active {
            background: white;
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 15px #10F0B2d4;
        }

        .step-label {
            position: absolute;
            top: 40px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* Buttons */
        .actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 1.2rem;
            border-radius: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }

        .btn-main {
            background: #111827;
            color: white;
            box-shadow: 0 10px 20px -5px #5e5e5ecc;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px #10F0B280;
            background: #f20505ff;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-light);
            border: 1px solid #e2e8f0;
        }

        .btn-ghost:hover {
            background: #f8fafc;
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="mesh-container"></div>

    <div class="glass-card">
        <div class="status-header">
            <div class="orbit-ring"></div>
            <div class="icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <h1>Verification Pending</h1>
        <p>Your registration was successful. An administrator is currently verifying your details to activate your dashboard.</p>

        <div class="tracker">
            <div class="track-step complete">
                ✓
                <span class="step-label">Signed Up</span>
            </div>
            <div class="track-step active">
                2
                <span class="step-label">Verifying</span>
            </div>
            <div class="track-step">
                3
                <span class="step-label">Ready</span>
            </div>
        </div>
        <div class="actions">
         <form method="POST" action="{{ route('logout') }}" class="">
                @csrf

                <button type="submit"
                    class="btn btn-main"
                    title="Logout">
                   Logout
                </button>
            </form>
        </div>
        
    </div>

</body>
</html>