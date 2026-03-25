<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Mars Map - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Interactive Mars Globe - Martian Republic Territory">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    @livewireStyles

    <style>
    body { margin: 0; overflow: hidden; background: #000; }
    .map-page { min-height: 100vh; display: flex; flex-direction: column; }

    /* Globe container - offset below nav */
    #mars-globe {
        position: fixed;
        top: 120px; left: 0; right: 0; bottom: 0;
        z-index: 0;
    }

    /* HUD overlay */
    .mars-hud {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 10;
        pointer-events: none;
    }
    .mars-hud > * {
        pointer-events: auto;
    }

    /* Top info bar */
    .hud-top {
        position: absolute;
        top: 160px; left: 40px;
        animation: fadeSlideDown 0.8s ease-out 0.5s both;
    }
    .hud-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 3px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.8);
    }
    .hud-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-mars, #c84125);
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-top: 6px;
        text-shadow: 0 1px 10px rgba(0,0,0,0.8);
    }

    /* Coordinate display */
    .hud-coords {
        position: absolute;
        bottom: 40px; left: 40px;
        background: rgba(6,6,12,0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px;
        padding: 16px 24px;
        animation: fadeSlideUp 0.6s ease-out 0.8s both;
    }
    .coord-row {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 4px;
    }
    .coord-row:last-child { margin-bottom: 0; }
    .coord-value {
        color: #fff;
        font-weight: 500;
    }

    /* Info panel */
    .hud-info {
        position: absolute;
        bottom: 40px; right: 40px;
        background: rgba(6,6,12,0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px;
        padding: 20px 28px;
        max-width: 320px;
        animation: fadeSlideUp 0.6s ease-out 1s both;
    }
    .hud-info h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #fff;
        margin: 0 0 8px;
    }
    .hud-info p {
        font-size: 13px;
        line-height: 1.6;
        color: var(--mr-text-dim, #8a8998);
        margin: 0;
    }

    /* Controls hint */
    .hud-controls {
        position: absolute;
        top: 140px; right: 40px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: rgba(255,255,255,0.3);
        text-align: right;
        animation: fadeSlideDown 0.6s ease-out 1.2s both;
    }

    /* Vignette */
    .vignette {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        pointer-events: none;
        z-index: 5;
        background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.6) 100%);
    }

    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .hud-title { font-size: 22px; }
        .hud-top { top: 120px; left: 20px; }
        .hud-coords { left: 20px; bottom: 20px; }
        .hud-info { right: 20px; bottom: 20px; display: none; }
    }
    </style>
</head>

<body class="map-page">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner" style="position: relative; z-index: 20;">
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
        @include('wallet.mainnav', array('active'=>'map'))
    </div>

    {{-- Three.js Mars Globe --}}
    <canvas id="mars-globe"></canvas>
    <div class="vignette"></div>

    {{-- HUD Overlay --}}
    <div class="mars-hud">
        <div class="hud-top">
            <div class="hud-subtitle"><span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#34d399;margin-right:8px;animation:pulse 2s infinite;vertical-align:middle;"></span>Planetary Surface — Real Terrain Data</div>
            <div class="hud-title">Mars</div>
        </div>

        <div class="hud-controls">
            Drag to rotate<br>
            Scroll to zoom<br>
            Double-click to reset<br>
            <span style="color:var(--mr-cyan,#00e4ff);">Click globe for NASA data</span>
        </div>

        <div id="loading-indicator" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center;">
            <div style="font-family:'Orbitron',sans-serif; font-size:14px; color:var(--mr-mars,#c84125); letter-spacing:3px; text-transform:uppercase;">Loading Mars Surface</div>
            <div style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim,#8a8998); margin-top:8px;">Streaming NASA imagery...</div>
        </div>

        <div class="hud-coords">
            <div class="coord-row">Latitude: <span class="coord-value" id="lat">18.65°N</span></div>
            <div class="coord-row">Longitude: <span class="coord-value" id="lon">226.20°E</span></div>
            <div class="coord-row">Altitude: <span class="coord-value" id="alt">3,200 km</span></div>
            <div style="margin-top:10px; padding-top:10px; border-top:1px solid rgba(255,255,255,0.06);">
                <a id="nasa-link" href="https://trek.nasa.gov/mars/" target="_blank"
                   style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--mr-cyan,#00e4ff); text-decoration:none;">
                    <i class="fa fa-external-link"></i> Open in NASA Mars Trek
                </a>
            </div>
            <div style="margin-top:8px;">
                <input id="coord-jump" type="text" placeholder="lat, lon (e.g. 18.4, -77.5)"
                    style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:4px; padding:4px 8px; font-family:'JetBrains Mono',monospace; font-size:10px; color:#fff; width:100%; outline:none;"
                    onkeydown="if(event.key==='Enter'){jumpToCoord(this.value)}">
            </div>
        </div>

        <div class="hud-info">
            <h3>Martian Republic Territory</h3>
            <p>
                The entire surface of Mars — 144.8 million km² — is governed by the Martian Republic.
                Citizens may register land claims via blockchain, verified and enforced by the community.
            </p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>

    <script>
    (function() {
        const canvas = document.getElementById('mars-globe');
        const NAV_HEIGHT = 120;
        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight - NAV_HEIGHT);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, window.innerWidth / (window.innerHeight - NAV_HEIGHT), 0.1, 1000);
        camera.position.z = 3.2;

        // Mars sphere
        const geometry = new THREE.SphereGeometry(1, 128, 128);
        const textureLoader = new THREE.TextureLoader();

        // Load progressively: low-res first, then high-res
        // NASA/USGS Mars Viking MDIM21 color mosaic (public domain)
        const marsTexture = new THREE.Texture();
        const bumpTexture = new THREE.Texture();

        // Start with procedural texture while NASA textures load
        (function generateProceduralMars() {
            const c = document.createElement('canvas');
            c.width = 1024; c.height = 512;
            const ctx = c.getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 1024, 512);
            grad.addColorStop(0, '#b5603b');
            grad.addColorStop(0.3, '#c47a52');
            grad.addColorStop(0.5, '#a04e2d');
            grad.addColorStop(0.7, '#d49464');
            grad.addColorStop(1, '#8b3a1f');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 1024, 512);
            for (let i = 0; i < 8000; i++) {
                ctx.fillStyle = `rgba(${Math.random() > 0.5 ? 180 : 100}, ${60 + Math.random()*40}, ${30 + Math.random()*20}, ${Math.random()*0.3})`;
                ctx.fillRect(Math.random()*1024, Math.random()*512, 2+Math.random()*6, 2+Math.random()*6);
            }
            ctx.fillStyle = 'rgba(220,220,230,0.35)';
            ctx.fillRect(0, 0, 1024, 25);
            ctx.fillRect(0, 487, 1024, 25);
            marsTexture.image = c;
            marsTexture.needsUpdate = true;
        })();

        // Load high-res NASA texture (2K Viking color mosaic)
        const hiResLoader = new THREE.TextureLoader();
        // Load local NASA textures (downloaded from trek.nasa.gov)
        hiResLoader.load(
            '/assets/mars/mars_color.jpg',
            (tex) => {
                marsTexture.image = tex.image;
                marsTexture.needsUpdate = true;
                console.log('Mars color texture loaded');
                document.getElementById('loading-indicator')?.remove();

                // Progressive: load higher res textures
                hiResLoader.load('/assets/mars/mars_hires.jpg', (tex2) => {
                    marsTexture.image = tex2.image;
                    marsTexture.needsUpdate = true;
                    console.log('Hi-res Mars texture loaded (1.2MB)');

                    // Final: load 8K texture for maximum detail on zoom
                    hiResLoader.load('/assets/mars/mars_8k.jpg', (tex3) => {
                        marsTexture.image = tex3.image;
                        marsTexture.needsUpdate = true;
                        console.log('8K Mars texture loaded (8.4MB)');
                    });
                });
            },
            undefined,
            () => console.log('Mars texture unavailable, using procedural')
        );

        const material = new THREE.MeshPhongMaterial({
            map: marsTexture,
            bumpScale: 0.02,
            specular: new THREE.Color(0x111111),
            shininess: 5,
        });
        const mars = new THREE.Mesh(geometry, material);
        scene.add(mars);

        // Atmosphere glow
        const atmosGeometry = new THREE.SphereGeometry(1.02, 64, 64);
        const atmosMaterial = new THREE.ShaderMaterial({
            vertexShader: `
                varying vec3 vNormal;
                void main() {
                    vNormal = normalize(normalMatrix * normal);
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
            fragmentShader: `
                varying vec3 vNormal;
                void main() {
                    float intensity = pow(0.65 - dot(vNormal, vec3(0.0, 0.0, 1.0)), 3.0);
                    gl_FragColor = vec4(0.8, 0.4, 0.2, 1.0) * intensity;
                }
            `,
            blending: THREE.AdditiveBlending,
            side: THREE.BackSide,
            transparent: true,
        });
        const atmosphere = new THREE.Mesh(atmosGeometry, atmosMaterial);
        scene.add(atmosphere);

        // Lighting
        const sunLight = new THREE.DirectionalLight(0xffffff, 1.2);
        sunLight.position.set(5, 3, 5);
        scene.add(sunLight);
        scene.add(new THREE.AmbientLight(0x333333));

        // Stars background
        const starGeometry = new THREE.BufferGeometry();
        const starPositions = [];
        for (let i = 0; i < 3000; i++) {
            starPositions.push((Math.random() - 0.5) * 200);
            starPositions.push((Math.random() - 0.5) * 200);
            starPositions.push((Math.random() - 0.5) * 200);
        }
        starGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starPositions, 3));
        const stars = new THREE.Points(starGeometry, new THREE.PointsMaterial({ color: 0xffffff, size: 0.15 }));
        scene.add(stars);

        // Mouse interaction
        let isDragging = false;
        let previousMouse = { x: 0, y: 0 };
        let rotationVelocity = { x: 0, y: 0 };

        canvas.addEventListener('mousedown', (e) => {
            isDragging = true;
            previousMouse = { x: e.clientX, y: e.clientY };
        });
        canvas.addEventListener('mousemove', (e) => {
            if (isDragging) {
                const dx = e.clientX - previousMouse.x;
                const dy = e.clientY - previousMouse.y;
                rotationVelocity.x = dy * 0.002;
                rotationVelocity.y = dx * 0.002;
                previousMouse = { x: e.clientX, y: e.clientY };
            }
            // Update HUD coordinates based on mouse position
            const nx = ((e.clientX / window.innerWidth) * 360 - 180).toFixed(2);
            const ny = ((0.5 - e.clientY / window.innerHeight) * 180).toFixed(2);
            document.getElementById('lon').textContent = Math.abs(nx) + '°' + (nx >= 0 ? 'E' : 'W');
            document.getElementById('lat').textContent = Math.abs(ny) + '°' + (ny >= 0 ? 'N' : 'S');
        });
        canvas.addEventListener('mouseup', () => isDragging = false);
        canvas.addEventListener('mouseleave', () => isDragging = false);

        canvas.addEventListener('wheel', (e) => {
            camera.position.z = Math.max(1.5, Math.min(8, camera.position.z + e.deltaY * 0.003));
            const alt = ((camera.position.z - 1) * 2000).toFixed(0);
            document.getElementById('alt').textContent = Number(alt).toLocaleString() + ' km';
            e.preventDefault();
        }, { passive: false });

        canvas.addEventListener('dblclick', () => {
            camera.position.z = 3.2;
            mars.rotation.x = 0.1;
            mars.rotation.y = 0;
        });

        // Click on globe → open NASA Mars Trek at that location
        canvas.addEventListener('click', (e) => {
            if (Math.abs(rotationVelocity.x) > 0.005 || Math.abs(rotationVelocity.y) > 0.005) return;
            // Raycast to find clicked point on sphere
            const rect = canvas.getBoundingClientRect();
            const mouse = new THREE.Vector2(
                ((e.clientX - rect.left) / rect.width) * 2 - 1,
                -((e.clientY - rect.top) / rect.height) * 2 + 1
            );
            const raycaster = new THREE.Raycaster();
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObject(mars);
            if (intersects.length > 0) {
                const point = intersects[0].point;
                // Convert 3D point to lat/lon
                const spherical = new THREE.Spherical();
                const localPoint = mars.worldToLocal(point.clone());
                spherical.setFromVector3(localPoint);
                const lat = (90 - THREE.MathUtils.radToDeg(spherical.phi)).toFixed(2);
                const lon = (THREE.MathUtils.radToDeg(spherical.theta)).toFixed(2);
                // Update NASA link
                const nasaUrl = `https://trek.nasa.gov/mars/#v=0.1&x=${lon}&y=${lat}&z=5`;
                document.getElementById('nasa-link').href = nasaUrl;
                document.getElementById('lat').textContent = Math.abs(lat) + '°' + (lat >= 0 ? 'N' : 'S');
                document.getElementById('lon').textContent = Math.abs(lon) + '°' + (lon >= 0 ? 'E' : 'W');
            }
        });

        // Touch support
        canvas.addEventListener('touchstart', (e) => {
            isDragging = true;
            previousMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
        });
        canvas.addEventListener('touchmove', (e) => {
            if (isDragging && e.touches.length === 1) {
                const dx = e.touches[0].clientX - previousMouse.x;
                const dy = e.touches[0].clientY - previousMouse.y;
                rotationVelocity.x = dy * 0.002;
                rotationVelocity.y = dx * 0.002;
                previousMouse = { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }
        });
        canvas.addEventListener('touchend', () => isDragging = false);

        // Animate
        mars.rotation.x = 0.1; // Axial tilt
        function animate() {
            requestAnimationFrame(animate);

            // Auto-rotate slowly
            if (!isDragging) {
                mars.rotation.y += 0.001;
            }

            // Apply drag rotation
            mars.rotation.x += rotationVelocity.x;
            mars.rotation.y += rotationVelocity.y;
            rotationVelocity.x *= 0.95;
            rotationVelocity.y *= 0.95;

            atmosphere.rotation.copy(mars.rotation);
            renderer.render(scene, camera);
        }
        animate();

        // Resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / (window.innerHeight - NAV_HEIGHT);
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight - NAV_HEIGHT);
        });
    })();

    // Jump to coordinate from input
    function jumpToCoord(value) {
        const parts = value.split(',').map(s => parseFloat(s.trim()));
        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
            const lat = parts[0], lon = parts[1];
            document.getElementById('lat').textContent = Math.abs(lat).toFixed(2) + '°' + (lat >= 0 ? 'N' : 'S');
            document.getElementById('lon').textContent = Math.abs(lon).toFixed(2) + '°' + (lon >= 0 ? 'E' : 'W');
            document.getElementById('nasa-link').href = `https://trek.nasa.gov/mars/#v=0.1&x=${lon}&y=${lat}&z=5`;
            // Flash the coordinate display
            const coords = document.querySelector('.hud-coords');
            coords.style.borderColor = 'var(--mr-cyan, #00e4ff)';
            setTimeout(() => { coords.style.borderColor = 'rgba(255,255,255,0.08)'; }, 1000);
        }
    }
    </script>

    @livewireScripts

    <style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    </style>
</body>
</html>
