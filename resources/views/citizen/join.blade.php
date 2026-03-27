<!DOCTYPE html>
<html lang="en">
<head>
    <title>Join Mars - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.public-head')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
    <style>
    /* ============================================ */
    /* THE GATEWAY: Citizen Onboarding Wizard       */
    /* ============================================ */
    body, .mr-theme { background: var(--mr-void, #06060c) !important; }
    .gateway-wizard { max-width: 620px; margin: 0 auto; padding: 16px 20px 60px; }
    main.mr-auth-page { padding: 0 !important; margin: 0 !important; min-height: auto !important; background: var(--mr-void, #06060c) !important; }
    #wrapper { min-height: 100%; height: auto; margin: 0 auto -60px; padding: 0 0 60px; }
    #wrapper > main { flex: 1; }
    .gateway-step { display: none; animation: gateIn 0.4s ease-out; }
    .gateway-step.active { display: block; }
    @keyframes gateIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    /* Progress */
    .gateway-progress { display: flex; align-items: center; justify-content: center; gap: 6px; margin-bottom: 36px; }
    .gate-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--mr-border-bright, rgba(255,255,255,0.12)); transition: all 0.3s; }
    .gate-dot.done { background: var(--mr-green, #34d399); }
    .gate-dot.current { background: var(--mr-cyan, #00e4ff); box-shadow: 0 0 8px rgba(0,228,255,0.4); }
    .gate-conn { width: 20px; height: 2px; background: var(--mr-border-bright); transition: background 0.3s; }
    .gate-conn.done { background: var(--mr-green); }

    /* Card */
    .gate-card { background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 16px; padding: 36px 32px; }
    .gate-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; }
    .gate-title { font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #fff; margin-bottom: 8px; text-align: center; }
    .gate-sub { font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim, #8a8998); margin-bottom: 28px; text-align: center; line-height: 1.6; }

    /* Buttons */
    .gate-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px 24px; border-radius: 10px; border: none; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.2s; }
    .gate-btn.primary { background: var(--mr-mars, #c84125); color: #fff; }
    .gate-btn.primary:hover { background: #d94e30; box-shadow: 0 4px 20px rgba(200,65,37,0.3); }
    .gate-btn.secondary { background: transparent; border: 1px solid var(--mr-border-bright); color: var(--mr-text-dim); margin-top: 10px; }
    .gate-btn.success { background: var(--mr-green, #34d399); color: #000; }
    .gate-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Inputs */
    .gate-label { font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 6px; text-align: left; }
    .gate-input { width: 100%; padding: 14px 16px; background: var(--mr-void, #06060c); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)); border-radius: 10px; color: #fff; font-family: 'JetBrains Mono', monospace; font-size: 14px; outline: none; transition: border-color 0.2s; box-sizing: border-box; }
    .gate-input:focus { border-color: var(--mr-cyan, #00e4ff); box-shadow: 0 0 0 3px rgba(0,228,255,0.08); }
    .gate-input::placeholder { color: var(--mr-text-faint); }
    textarea.gate-input { min-height: 100px; resize: vertical; }
    .gate-field { margin-bottom: 16px; text-align: left; }
    .gate-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Camera/Video */
    .gate-camera-box { position: relative; width: 100%; aspect-ratio: 4/3; background: var(--mr-void); border: 2px dashed var(--mr-border-bright); border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .gate-camera-box video { width: 100%; height: 100%; object-fit: cover; }
    .gate-camera-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .gate-camera-placeholder { text-align: center; color: var(--mr-text-faint); }
    .gate-camera-placeholder i { font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.3; }

    /* Review section */
    .gate-review-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.04)); }
    .gate-review-label { font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint); }
    .gate-review-value { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #fff; text-align: right; max-width: 60%; word-break: break-all; }

    /* Why box */
    .gate-why { background: rgba(0,228,255,0.04); border: 1px solid rgba(0,228,255,0.1); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; text-align: left; }
    .gate-why-title { font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-cyan); margin-bottom: 4px; }
    .gate-why-text { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim); line-height: 1.6; }
    </style>
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/dashboard/dashboard.css?v=4.4">
    <script>var current_blob = null;</script>
    @livewireStyles
</head>
<body style="background: #06060c !important; margin: 0; padding: 0; overflow-x: hidden;">
<style>html { background: #06060c !important; margin: 0; padding: 0; height: 100%; } body { min-height: 100%; }</style>
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div>
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div>
        </header>
        @include('wallet.mainnav', ['active' => 'citizen'])

    <main class="mr-auth-page" style="padding: 0 !important;">
        <div class="gateway-wizard">

            {{-- Progress --}}
            <div class="gateway-progress" id="gate-progress">
                <div class="gate-dot current" data-step="1"></div>
                <div class="gate-conn"></div>
                <div class="gate-dot" data-step="2"></div>
                <div class="gate-conn"></div>
                <div class="gate-dot" data-step="3"></div>
                <div class="gate-conn"></div>
                <div class="gate-dot" data-step="4"></div>
                <div class="gate-conn"></div>
                <div class="gate-dot" data-step="5"></div>
            </div>

            {{-- ===== STEP 1: WELCOME ===== --}}
            <div class="gateway-step active" id="gate-1">
                <div class="gate-card">
                    <div class="gate-icon" style="background: rgba(0,228,255,0.1); border: 1px solid rgba(0,228,255,0.2); color: var(--mr-cyan);">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <div class="gate-title">Welcome to Mars</div>
                    <div class="gate-sub">You're about to register as a member of the General Martian Public — the first step toward full citizenship.</div>

                    <div class="gate-why">
                        <div class="gate-why-title"><i class="fa fa-info-circle" style="margin-right: 4px;"></i> What you'll need</div>
                        <div class="gate-why-text">
                            <strong style="color: #fff;">1.</strong> A photo of yourself (camera access)<br>
                            <strong style="color: #fff;">2.</strong> Your name and a short bio<br>
                            <strong style="color: #fff;">3.</strong> A short video proving you're human<br>
                            <strong style="color: #fff;">4.</strong> ~0.01 MARS for the blockchain registration fee
                        </div>
                    </div>

                    <div class="gate-why" style="border-color: rgba(52,211,153,0.15); background: rgba(52,211,153,0.03);">
                        <div class="gate-why-title" style="color: var(--mr-green);"><i class="fa fa-shield-halved" style="margin-right: 4px;"></i> Why this matters</div>
                        <div class="gate-why-text">
                            Your identity will be anchored to the Marscoin blockchain via IPFS — creating a permanent, decentralized, tamper-proof civic record. Once registered, citizens can endorse you for full citizenship.
                        </div>
                    </div>

                    <button class="gate-btn primary" onclick="gateGo(2)">
                        <i class="fa fa-arrow-right"></i> Begin Registration
                    </button>
                </div>
            </div>

            {{-- ===== STEP 2: PHOTO ===== --}}
            <div class="gateway-step" id="gate-2">
                <div class="gate-card">
                    <div class="gate-icon" style="background: rgba(200,65,37,0.1); border: 1px solid rgba(200,65,37,0.2); color: var(--mr-mars);">
                        <i class="fa fa-camera"></i>
                    </div>
                    <div class="gate-title">Your Photo</div>
                    <div class="gate-sub">Take a clear photo of yourself. This will be your public identity on Mars.</div>

                    <div class="gate-camera-box" id="photo-box">
                        <div class="gate-camera-placeholder" id="photo-placeholder">
                            <i class="fa fa-camera"></i>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px;">Click below to start camera</div>
                        </div>
                        <video id="gate-photo-video" style="display:none;" autoplay playsinline></video>
                        <img id="gate-photo-preview" style="display:none;">
                        <canvas id="gate-photo-canvas" hidden></canvas>
                    </div>

                    <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                        <button class="gate-btn secondary" id="gate-start-camera" style="flex:1;">
                            <i class="fa fa-video"></i> Start Camera
                        </button>
                        <button class="gate-btn primary" id="gate-take-photo" style="flex:1; display:none;">
                            <i class="fa fa-camera"></i> Take Photo
                        </button>
                        <button class="gate-btn secondary" id="gate-retake-photo" style="flex:1; display:none;">
                            <i class="fa fa-rotate"></i> Retake
                        </button>
                    </div>

                    <div id="gate-photo-saving" style="display:none; text-align:center; margin-bottom:16px;">
                        <i class="fa fa-spinner fa-spin" style="color:var(--mr-cyan);"></i>
                        <span style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim); margin-left:8px;">Saving to IPFS...</span>
                    </div>

                    <button class="gate-btn primary" id="gate-photo-next" disabled>
                        <i class="fa fa-arrow-right"></i> Continue
                    </button>
                    <button class="gate-btn secondary" onclick="gateGo(1)">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>

            {{-- ===== STEP 3: IDENTITY ===== --}}
            <div class="gateway-step" id="gate-3">
                <div class="gate-card">
                    <div class="gate-icon" style="background: rgba(0,228,255,0.1); border: 1px solid rgba(0,228,255,0.2); color: var(--mr-cyan);">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <div class="gate-title">Your Identity</div>
                    <div class="gate-sub">Tell the Martian community who you are</div>

                    <div class="gate-row">
                        <div class="gate-field">
                            <div class="gate-label">First Name *</div>
                            <input class="gate-input" id="gate-firstname" placeholder="First name" value="{{ $citcache['firstname'] ?? '' }}">
                        </div>
                        <div class="gate-field">
                            <div class="gate-label">Last Name *</div>
                            <input class="gate-input" id="gate-lastname" placeholder="Last name" value="{{ $citcache['lastname'] ?? '' }}">
                        </div>
                    </div>
                    <div class="gate-field">
                        <div class="gate-label">Display Name *</div>
                        <input class="gate-input" id="gate-displayname" placeholder="How you'll be known on Mars" value="{{ $citcache['displayname'] ?? '' }}">
                    </div>
                    <div class="gate-field">
                        <div class="gate-label">Short Bio *</div>
                        <textarea class="gate-input" id="gate-bio" placeholder="Tell us about yourself — your skills, interests, why Mars..." rows="4">{{ $citcache['shortbio'] ?? '' }}</textarea>
                    </div>

                    <button class="gate-btn primary" id="gate-identity-next">
                        <i class="fa fa-arrow-right"></i> Continue
                    </button>
                    <button class="gate-btn secondary" onclick="gateGo(2)">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>

            {{-- ===== STEP 4: LIVENESS VIDEO ===== --}}
            <div class="gateway-step" id="gate-4">
                <div class="gate-card">
                    <div class="gate-icon" style="background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.2); color: var(--mr-green);">
                        <i class="fa fa-video"></i>
                    </div>
                    <div class="gate-title">Proof of Humanity</div>
                    <div class="gate-sub">Record a short video of yourself. Say your name and your Marscoin address to prove you're a real person.</div>

                    <div class="gate-camera-box" id="video-box">
                        <div class="gate-camera-placeholder" id="video-placeholder">
                            <i class="fa fa-video"></i>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px;">Click below to start camera</div>
                        </div>
                        <video id="gate-live-video" style="display:none;" autoplay playsinline muted></video>
                        <video id="gate-finished-video" style="display:none;" controls></video>
                    </div>

                    <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                        <button class="gate-btn secondary" id="gate-vid-start-cam" style="flex:1;">
                            <i class="fa fa-video"></i> Start Camera
                        </button>
                        <button class="gate-btn primary" id="gate-vid-record" style="flex:1; display:none;">
                            <i class="fa fa-circle" style="color:#ef4444;"></i> Record
                        </button>
                        <button class="gate-btn secondary" id="gate-vid-stop" style="flex:1; display:none;">
                            <i class="fa fa-stop"></i> Stop
                        </button>
                    </div>

                    <div id="gate-vid-saving" style="display:none; text-align:center; margin-bottom:16px;">
                        <i class="fa fa-spinner fa-spin" style="color:var(--mr-cyan);"></i>
                        <span style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim); margin-left:8px;">Saving video to IPFS...</span>
                    </div>

                    <button class="gate-btn primary" id="gate-vid-next" disabled>
                        <i class="fa fa-arrow-right"></i> Continue
                    </button>
                    <button class="gate-btn secondary" onclick="gateGo(3)">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>

            {{-- ===== STEP 5: REVIEW & PUBLISH ===== --}}
            <div class="gateway-step" id="gate-5">
                <div class="gate-card">
                    <div class="gate-icon" style="background: rgba(200,65,37,0.1); border: 1px solid rgba(200,65,37,0.2); color: var(--mr-mars);">
                        <i class="fa fa-globe"></i>
                    </div>
                    <div class="gate-title">Review & Publish</div>
                    <div class="gate-sub">Verify your information before publishing to the Marscoin blockchain</div>

                    <div style="margin-bottom: 20px;">
                        <div class="gate-review-row">
                            <span class="gate-review-label">Name</span>
                            <span class="gate-review-value" id="review-name"></span>
                        </div>
                        <div class="gate-review-row">
                            <span class="gate-review-label">Display Name</span>
                            <span class="gate-review-value" id="review-displayname"></span>
                        </div>
                        <div class="gate-review-row">
                            <span class="gate-review-label">Bio</span>
                            <span class="gate-review-value" id="review-bio"></span>
                        </div>
                        <div class="gate-review-row">
                            <span class="gate-review-label">Photo</span>
                            <span class="gate-review-value" id="review-photo" style="color: var(--mr-green);"></span>
                        </div>
                        <div class="gate-review-row">
                            <span class="gate-review-label">Video</span>
                            <span class="gate-review-value" id="review-video" style="color: var(--mr-green);"></span>
                        </div>
                        <div class="gate-review-row">
                            <span class="gate-review-label">Address</span>
                            <span class="gate-review-value" style="color: var(--mr-cyan);">{{ $public_address }}</span>
                        </div>
                        <div class="gate-review-row" style="border: none;">
                            <span class="gate-review-label">Fee</span>
                            <span class="gate-review-value">~0.02 MARS</span>
                        </div>
                    </div>

                    <div class="gate-why" style="border-color: rgba(200,65,37,0.2); background: rgba(200,65,37,0.04);">
                        <div class="gate-why-title" style="color: var(--mr-mars);"><i class="fa fa-cube" style="margin-right: 4px;"></i> What happens next</div>
                        <div class="gate-why-text">
                            Your data will be uploaded to IPFS and anchored to the Marscoin blockchain. This creates a permanent, verifiable record of your identity. Citizens can then endorse you for full citizenship.
                            <br><br><em style="color: var(--mr-mars, #c84125);">We intend to take these records to Mars. Ad Astra, Martian.</em>
                        </div>
                    </div>

                    <div id="gate-publish-status" style="display:none; text-align:center; margin-bottom:16px; font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim);">
                        <i class="fa fa-spinner fa-spin" style="color:var(--mr-mars); margin-right:8px;"></i>
                        <span id="gate-publish-msg">Publishing to blockchain...</span>
                    </div>

                    <div id="gate-publish-success" style="display:none; text-align:center; padding:24px 0;">
                        <div style="width:56px;height:56px;border-radius:50%;background:rgba(52,211,153,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <i class="fa fa-check" style="font-size:24px;color:var(--mr-green);"></i>
                        </div>
                        <div style="font-family:'Orbitron',sans-serif;font-size:14px;font-weight:700;color:var(--mr-green);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;">
                            Welcome to Mars!
                        </div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--mr-text-dim);margin-bottom:16px;">
                            Your identity has been published to the blockchain.
                        </div>
                        <a href="/citizen/all" class="gate-btn primary" style="text-decoration:none;display:inline-flex;width:auto;padding:12px 28px;">
                            <i class="fa fa-users"></i> View Citizen Registry
                        </a>
                    </div>

                    <div id="gate-publish-buttons">
                        <button class="gate-btn primary" id="gate-publish">
                            <i class="fa fa-globe"></i> Publish My Application
                        </button>
                        <button class="gate-btn secondary" onclick="gateGo(4)">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    </div> {{-- /#wrapper --}}
    <footer class="footer" style="border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 20px 0; background: #06060c; height: 60px;">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const publicAddress = '{{ $public_address }}';
    let photoIPFS = '{{ $citcache["avatar_link"] ?? "" }}';
    let videoIPFS = '{{ $citcache["liveness_link"] ?? "" }}';
    let photoStream = null;
    let videoStream = null;
    let audioStream = null;
    let mediaRecorder = null;
    let videoBlobs = [];

    // Navigation
    function gateGo(step) {
        document.querySelectorAll('.gateway-step').forEach(s => s.classList.remove('active'));
        document.getElementById('gate-' + step).classList.add('active');
        document.querySelectorAll('.gate-dot').forEach(d => {
            const s = parseInt(d.dataset.step);
            d.className = 'gate-dot' + (s < step ? ' done' : s === step ? ' current' : '');
        });
        document.querySelectorAll('.gate-conn').forEach((c, i) => {
            c.className = 'gate-conn' + (i < step - 1 ? ' done' : '');
        });
        window.scrollTo(0, 0);
    }

    // ===== STEP 2: PHOTO =====
    document.getElementById('gate-start-camera').addEventListener('click', async () => {
        try {
            photoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            const vid = document.getElementById('gate-photo-video');
            vid.srcObject = photoStream;
            vid.style.display = 'block';
            document.getElementById('photo-placeholder').style.display = 'none';
            document.getElementById('gate-photo-preview').style.display = 'none';
            document.getElementById('gate-start-camera').style.display = 'none';
            document.getElementById('gate-take-photo').style.display = 'flex';
            document.getElementById('gate-retake-photo').style.display = 'none';
        } catch(e) { alert('Camera access denied: ' + e.message); }
    });

    document.getElementById('gate-take-photo').addEventListener('click', () => {
        const vid = document.getElementById('gate-photo-video');
        const canvas = document.getElementById('gate-photo-canvas');
        const preview = document.getElementById('gate-photo-preview');
        canvas.width = vid.videoWidth;
        canvas.height = vid.videoHeight;
        canvas.getContext('2d').drawImage(vid, 0, 0);
        const dataUrl = canvas.toDataURL('image/png');
        preview.src = dataUrl;
        preview.style.display = 'block';
        vid.style.display = 'none';
        // Stop camera
        photoStream.getTracks().forEach(t => t.stop());
        document.getElementById('gate-take-photo').style.display = 'none';
        document.getElementById('gate-retake-photo').style.display = 'flex';

        // Save to IPFS
        document.getElementById('gate-photo-saving').style.display = 'block';
        $.post('/api/permapinpic', { picture: dataUrl, address: publicAddress }, function(data) {
            photoIPFS = 'https://ipfs.marscoin.org/ipfs/' + data.Hash;
            document.getElementById('gate-photo-saving').style.display = 'none';
            document.getElementById('gate-photo-next').disabled = false;
        }).fail(function() {
            document.getElementById('gate-photo-saving').innerHTML = '<span style="color:var(--mr-mars);">Failed to save photo. Please retake.</span>';
        });
    });

    document.getElementById('gate-retake-photo').addEventListener('click', () => {
        document.getElementById('gate-photo-preview').style.display = 'none';
        document.getElementById('gate-retake-photo').style.display = 'none';
        document.getElementById('gate-start-camera').style.display = 'flex';
        document.getElementById('gate-photo-next').disabled = true;
    });

    document.getElementById('gate-photo-next').addEventListener('click', () => {
        if (!photoIPFS) { alert('Please take a photo first.'); return; }
        gateGo(3);
    });

    // ===== STEP 3: IDENTITY =====
    document.getElementById('gate-identity-next').addEventListener('click', () => {
        const fn = document.getElementById('gate-firstname').value.trim();
        const ln = document.getElementById('gate-lastname').value.trim();
        const dn = document.getElementById('gate-displayname').value.trim();
        const bio = document.getElementById('gate-bio').value.trim();
        if (!fn || !ln || !dn || !bio) { alert('Please fill in all fields.'); return; }

        // Save to citizen cache via AJAX
        $.post('/api/cacheonboarding', { firstname: fn, lastname: ln, displayname: dn, shortbio: bio, address: publicAddress });
        gateGo(4);
    });

    // ===== STEP 4: VIDEO =====
    document.getElementById('gate-vid-start-cam').addEventListener('click', async () => {
        try {
            audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            const vid = document.getElementById('gate-live-video');
            vid.srcObject = videoStream;
            vid.style.display = 'block';
            document.getElementById('video-placeholder').style.display = 'none';
            document.getElementById('gate-finished-video').style.display = 'none';
            document.getElementById('gate-vid-start-cam').style.display = 'none';
            document.getElementById('gate-vid-record').style.display = 'flex';
        } catch(e) { alert('Camera/microphone access denied: ' + e.message); }
    });

    document.getElementById('gate-vid-record').addEventListener('click', () => {
        videoBlobs = [];
        const combined = new MediaStream([...videoStream.getVideoTracks(), ...audioStream.getAudioTracks()]);
        mediaRecorder = new MediaRecorder(combined, { mimeType: 'video/webm' });
        mediaRecorder.addEventListener('dataavailable', e => videoBlobs.push(e.data));
        mediaRecorder.addEventListener('stop', () => {
            current_blob = new Blob(videoBlobs, { type: 'video/webm' });
            const url = URL.createObjectURL(current_blob);
            document.getElementById('gate-live-video').style.display = 'none';
            const finished = document.getElementById('gate-finished-video');
            finished.src = url;
            finished.style.display = 'block';
            document.getElementById('gate-vid-stop').style.display = 'none';
            document.getElementById('gate-vid-start-cam').style.display = 'flex';

            // Save to IPFS
            document.getElementById('gate-vid-saving').style.display = 'block';
            const formData = new FormData();
            formData.append('file', current_blob, 'liveness.webm');
            formData.append('address', publicAddress);
            $.ajax({
                url: '/api/permapinvideo', type: 'POST', data: formData,
                processData: false, contentType: false,
                success: function(data) {
                    videoIPFS = 'https://ipfs.marscoin.org/ipfs/' + data.Hash;
                    document.getElementById('gate-vid-saving').style.display = 'none';
                    document.getElementById('gate-vid-next').disabled = false;
                },
                error: function() {
                    document.getElementById('gate-vid-saving').innerHTML = '<span style="color:var(--mr-mars);">Failed to save video. Please re-record.</span>';
                }
            });
        });
        mediaRecorder.start(1000);
        document.getElementById('gate-vid-record').style.display = 'none';
        document.getElementById('gate-vid-stop').style.display = 'flex';
    });

    document.getElementById('gate-vid-stop').addEventListener('click', () => {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
        videoStream.getTracks().forEach(t => t.stop());
        audioStream.getTracks().forEach(t => t.stop());
    });

    document.getElementById('gate-vid-next').addEventListener('click', () => {
        if (!videoIPFS) { alert('Please record a video first.'); return; }
        // Populate review
        document.getElementById('review-name').textContent = document.getElementById('gate-firstname').value + ' ' + document.getElementById('gate-lastname').value;
        document.getElementById('review-displayname').textContent = document.getElementById('gate-displayname').value;
        document.getElementById('review-bio').textContent = document.getElementById('gate-bio').value.substring(0, 80) + '...';
        document.getElementById('review-photo').innerHTML = '<i class="fa fa-check-circle"></i> Uploaded';
        document.getElementById('review-video').innerHTML = '<i class="fa fa-check-circle"></i> Uploaded';
        gateGo(5);
    });

    // ===== STEP 5: PUBLISH =====
    document.getElementById('gate-publish').addEventListener('click', async () => {
        document.getElementById('gate-publish-buttons').style.display = 'none';
        document.getElementById('gate-publish-status').style.display = 'block';

        try {
            // Build application JSON
            const appData = {
                firstname: document.getElementById('gate-firstname').value,
                lastname: document.getElementById('gate-lastname').value,
                displayname: document.getElementById('gate-displayname').value,
                shortbio: document.getElementById('gate-bio').value,
                avatar_link: photoIPFS,
                liveness_link: videoIPFS,
                address: publicAddress,
                timestamp: new Date().toISOString()
            };

            // Upload JSON to IPFS
            document.getElementById('gate-publish-msg').textContent = 'Uploading application to IPFS...';
            const ipfsResp = await $.ajax({
                url: '/api/permapinjson', type: 'POST',
                data: { type: 'application', payload: JSON.stringify(appData), address: publicAddress }
            });
            const cid = ipfsResp.Hash;

            // Build and sign blockchain transaction
            document.getElementById('gate-publish-msg').textContent = 'Signing blockchain transaction...';
            const mnemonic = WalletKey.get();
            if (!mnemonic) { throw new Error('Wallet not unlocked. Please go back and unlock your wallet.'); }

            // Get UTXO
            const utxoResp = await fetch(`https://pebas.marscoin.org/api/mars/utxo?sender_address=${publicAddress}&receiver_address=${publicAddress}&amount=0.01`);
            const io = await utxoResp.json();

            // Sign with OP_RETURN
            const Marscoin = {
                mainnet: { messagePrefix: "\x19Marscoin Signed Message:\n", bech32: "M", bip44: 2,
                    bip32: { public: 0x043587cf, private: 0x04358394 }, pubKeyHash: 0x32, scriptHash: 0x32, wif: 0x80 }
            };
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
            const child = root.derivePath("m/44'/2'/0'/0/0");
            const key = my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);

            const psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
            psbt.setVersion(1);
            psbt.setMaximumFeeRate(100000);

            const opReturnData = my_bundle.Buffer.from('GP_' + cid, 'utf8');
            const opReturnScript = my_bundle.bitcoin.script.compile([my_bundle.bitcoin.opcodes.OP_RETURN, opReturnData]);

            io.inputs.forEach(input => {
                psbt.addInput({ hash: input.txId, index: input.vout, nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex') });
            });
            io.outputs.forEach(output => {
                if (!output.address) output.address = publicAddress;
                psbt.addOutput({ address: output.address, value: output.value });
            });
            psbt.addOutput({ script: opReturnScript, value: 0 });

            io.inputs.forEach((_, i) => psbt.signInput(i, key));
            const txhex = psbt.finalizeAllInputs().extractTransaction().toHex();

            // Broadcast
            document.getElementById('gate-publish-msg').textContent = 'Broadcasting to network...';
            const broadcastResp = await fetch('https://pebas.marscoin.org/api/mars/broadcast', {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ a: 1, txhash: txhex })
            });
            const txResult = await broadcastResp.json();

            // Save to local DB
            await $.post('/api/setfeed', {
                tag: 'GP', txid: txResult.tx_hash, message: '',
                embedded_link: 'https://ipfs.marscoin.org/ipfs/' + cid, address: publicAddress
            });

            // Success!
            document.getElementById('gate-publish-status').style.display = 'none';
            document.getElementById('gate-publish-success').style.display = 'block';

        } catch(err) {
            document.getElementById('gate-publish-msg').textContent = 'Error: ' + err.message;
            document.getElementById('gate-publish-msg').style.color = 'var(--mr-mars)';
            document.getElementById('gate-publish-buttons').style.display = 'block';
        }
    });
    </script>
</body>
</html>
