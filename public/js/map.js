/* =========================================================
   "Find us": restaurant pin + route from the visitor's location,
   live open/closed badge, and the hero distance read-out.
   Leaflet + OpenStreetMap tiles; routing via the free OSM (FOSSGIS) OSRM servers.
   No API keys needed.
   ========================================================= */
(function () {
    'use strict';

    var el = document.getElementById('map');
    if (!el) { return; }

    var lat = parseFloat(el.dataset.lat);
    var lng = parseFloat(el.dataset.lng);
    var placeName = el.dataset.name || 'Our restaurant';
    var placeAddress = el.dataset.address || '';

    var routeBtn = document.getElementById('routeBtn');
    var modesBox = document.getElementById('routeModes');
    var resultBox = document.getElementById('routeResult');
    var distEl = document.getElementById('routeDist');
    var timeEl = document.getElementById('routeTime');
    var statusEl = document.getElementById('routeStatus');
    var gmapsLink = document.getElementById('gmapsLink');
    var heroInfo = document.getElementById('heroInfo');
    var goEls = document.querySelectorAll('[data-start-route]');

    var ROUTERS = { car: 'routed-car', bike: 'routed-bike', foot: 'routed-foot' };
    var GMAPS_MODE = { car: 'driving', bike: 'bicycling', foot: 'walking' };
    var ICON = { car: '🚗', bike: '🚴', foot: '🚶' };
    var VERB = { car: 'drive', bike: 'ride', foot: 'walk' };
    var SPEED = { foot: 1.35, bike: 4.2 };   // metres per second
    var WALK_RADIUS = 1500;                  // closer than this => default to walking

    var map = null;
    var routeLayer = null;
    var userMarker = null;
    var userPos = null;
    var mode = 'foot';
    var busy = false;
    var routeReady = false;

    /* ---------------- helpers ---------------- */
    function setStatus(text, kind) {
        statusEl.textContent = text;
        statusEl.className = 'route__status' + (kind ? ' is-' + kind : '');
    }

    function setBusy(isBusy, label) {
        busy = isBusy;
        routeBtn.disabled = isBusy;
        routeBtn.classList.toggle('is-busy', isBusy);
        if (label) { routeBtn.textContent = label; }
    }

    function fmtDist(m) {
        if (m < 1000) { return Math.max(10, Math.round(m / 10) * 10) + ' m'; }
        return (m / 1000).toFixed(m < 10000 ? 1 : 0) + ' km';
    }

    function fmtTime(s) {
        var m = Math.max(1, Math.round(s / 60));
        if (m < 60) { return m + ' min'; }
        var h = Math.floor(m / 60), r = m % 60;
        return h + ' h' + (r ? ' ' + r + ' min' : '');
    }

    function haversine(a, b) {
        var R = 6371000, rad = Math.PI / 180;
        var dLat = (b[0] - a[0]) * rad, dLng = (b[1] - a[1]) * rad;
        var x = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(a[0] * rad) * Math.cos(b[0] * rad) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
        return 2 * R * Math.asin(Math.sqrt(x));
    }

    function gmapsUrl() {
        var url = 'https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng + '&travelmode=' + GMAPS_MODE[mode];
        if (userPos) { url += '&origin=' + userPos[0] + ',' + userPos[1]; }
        return url;
    }

    function updateGoogleLink() {
        if (gmapsLink) { gmapsLink.href = gmapsUrl(); }
        // After a route exists, every "Take me there" button becomes "Start navigation"
        if (routeReady) {
            goEls.forEach(function (a) {
                a.href = gmapsUrl();
                a.target = '_blank';
                a.rel = 'noopener';
            });
        }
    }

    function eta(meters, osrmSeconds) {
        return SPEED[mode] ? meters / SPEED[mode] : osrmSeconds;
    }

    /* ---------------- open / closed badge ---------------- */
    var schedule = null;
    try {
        var node = document.getElementById('hoursData');
        if (node && node.dataset.schedule) { schedule = JSON.parse(node.dataset.schedule); }
    } catch (e) { schedule = null; }

    function toMins(s) { var p = s.split(':'); return (+p[0]) * 60 + (+p[1]); }
    function fmt12(s) {
        var p = s.split(':'), h = +p[0], ap = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return h + (p[1] === '00' ? '' : ':' + p[1]) + ' ' + ap;
    }

    function openState() {
        if (!schedule || !schedule.days) { return null; }
        var o = {};
        new Intl.DateTimeFormat('en-US', {
            timeZone: schedule.tz || 'Asia/Colombo', weekday: 'short', hour: 'numeric', minute: 'numeric', hourCycle: 'h23'
        }).formatToParts(new Date()).forEach(function (p) { o[p.type] = p.value; });

        var dayIdx = { Sun: 0, Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6 }[o.weekday];
        var now = (parseInt(o.hour, 10) % 24) * 60 + parseInt(o.minute, 10);
        var today = schedule.days[dayIdx];
        if (!today) { return { cls: 'is-closed', text: '🔴 Closed today' }; }

        var open = toMins(today[0]);
        var close = toMins(today[1]);
        if (close <= open) { close += 1440; }  // closes after midnight

        var isOpen = (now >= open && now < close) || (now + 1440 >= open && now + 1440 < close);
        if (isOpen) {
            var left = (now >= open ? close - now : close - (now + 1440));
            return left <= 60
                ? { cls: 'is-soon', text: '🟡 Closing soon · until ' + fmt12(today[1]) }
                : { cls: 'is-open', text: '🟢 Open now · until ' + fmt12(today[1]) };
        }
        if (now < open) { return { cls: 'is-closed', text: '🔴 Closed · opens ' + fmt12(today[0]) }; }
        var next = schedule.days[(dayIdx + 1) % 7];
        return { cls: 'is-closed', text: '🔴 Closed · opens ' + (next ? 'tomorrow ' + fmt12(next[0]) : 'soon') };
    }

    function updateOpen() {
        var st = null;
        try { st = openState(); } catch (e) { st = null; }
        document.querySelectorAll('[data-open]').forEach(function (p) {
            if (!st) { p.hidden = true; return; }
            p.hidden = false;
            p.className = 'open-pill ' + st.cls;
            p.textContent = st.text;
        });
    }
    updateOpen();
    setInterval(updateOpen, 60000);

    /* ---------------- map setup ---------------- */
    function useFallback() {
        var tpl = document.getElementById('mapFallback');
        if (tpl) {
            el.innerHTML = '';
            el.appendChild(tpl.content.cloneNode(true));
        }
        routeBtn.hidden = true;
        setStatus('The interactive route is unavailable right now. Use "Open in Google Maps" for directions.');
    }

    function initMap() {
        if (map) { return true; }
        if (typeof L === 'undefined') { useFallback(); return false; }

        el.innerHTML = '';
        var touch = L.Browser.mobile || ('ontouchstart' in window);

        map = L.map(el, {
            center: [lat, lng],
            zoom: 16,
            scrollWheelZoom: false,
            dragging: !touch,   // on phones one finger scrolls the page, not the map
            tap: false
        });

        var street = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Imagery &copy; Esri'
        });

        L.control.layers({ 'Map': street, 'Satellite': satellite }, null, { position: 'topright' }).addTo(map);

        var pin = L.divIcon({
            className: 'pin',
            html: '<div class="pin__shape"><i>🍴</i></div>',
            iconSize: [44, 54],
            iconAnchor: [22, 52],
            popupAnchor: [0, -46]
        });

        var popup = document.createElement('div');
        var strong = document.createElement('strong');
        strong.textContent = placeName;
        popup.appendChild(strong);
        if (placeAddress) {
            popup.appendChild(document.createElement('br'));
            popup.appendChild(document.createTextNode(placeAddress));
        }

        L.marker([lat, lng], { icon: pin, title: placeName }).addTo(map).bindPopup(popup);

        if (touch) {
            var hint = document.createElement('button');
            hint.type = 'button';
            hint.className = 'map__hint';
            hint.textContent = '👆 Tap to explore the map';
            hint.addEventListener('click', function () {
                map.dragging.enable();
                hint.remove();
            });
            el.appendChild(hint);
        } else {
            map.on('click', function () { map.scrollWheelZoom.enable(); });
        }

        setTimeout(function () { map.invalidateSize(); }, 250);
        window.addEventListener('resize', function () { map.invalidateSize(); });
        return true;
    }

    /* ---------------- routing ---------------- */
    function startRoute() {
        if (busy) { return; }
        if (!initMap()) { return; }

        if (!window.isSecureContext) {
            setStatus('Location sharing needs a secure (https) connection. Use "Open in Google Maps" instead.', 'error');
            return;
        }
        if (!navigator.geolocation) {
            setStatus("This browser can't share your location. Use \"Open in Google Maps\" instead.", 'error');
            return;
        }

        setBusy(true, 'Finding you…');
        setStatus('Allow location access when your browser asks.');
        if (heroInfo && !routeReady) { heroInfo.textContent = '🛰️ Finding you…'; }

        navigator.geolocation.getCurrentPosition(function (pos) {
            var first = !userPos;
            userPos = [pos.coords.latitude, pos.coords.longitude];

            // First fix: pick the sensible mode. Close by => walk, further => drive.
            if (first) { setMode(haversine(userPos, [lat, lng]) < WALK_RADIUS ? 'foot' : 'car'); }

            updateGoogleLink();
            drawRoute();
        }, function (err) {
            setBusy(false, '📍 Show route from my location');
            var msg;
            if (err && err.code === 1) {
                msg = 'Location access is blocked. Allow it in your browser settings and try again, or use "Open in Google Maps".';
            } else if (err && err.code === 3) {
                msg = 'Finding your location took too long. Please try again.';
            } else {
                msg = "We couldn't find your location. Please try again.";
            }
            setStatus(msg, 'error');
            if (heroInfo) { heroInfo.textContent = "We couldn't get your location. Tap again, or open Google Maps below."; }
        }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 30000 });
    }

    function setMode(m) {
        mode = m;
        modesBox.querySelectorAll('.mode').forEach(function (b) {
            b.classList.toggle('is-active', b.dataset.mode === m);
        });
    }

    function drawRoute() {
        setBusy(true, 'Drawing route…');
        setStatus('Finding the best ' + VERB[mode] + ' route…');

        var url = 'https://routing.openstreetmap.de/' + ROUTERS[mode] + '/route/v1/driving/' +
            userPos[1] + ',' + userPos[0] + ';' + lng + ',' + lat +
            '?overview=full&geometries=geojson';

        var ctrl = ('AbortController' in window) ? new AbortController() : null;
        var timer = setTimeout(function () { if (ctrl) { ctrl.abort(); } }, 12000);

        fetch(url, ctrl ? { signal: ctrl.signal } : undefined)
            .then(function (res) {
                if (!res.ok) { throw new Error('HTTP ' + res.status); }
                return res.json();
            })
            .then(function (data) {
                clearTimeout(timer);
                if (!data.routes || !data.routes.length) { throw new Error('No route'); }
                var r = data.routes[0];
                var coords = r.geometry.coordinates.map(function (c) { return [c[1], c[0]]; });
                paint(coords, r.distance, eta(r.distance, r.duration), false);
            })
            .catch(function () {
                clearTimeout(timer);
                // Routing service unreachable: show a straight dotted line so they still see where we are.
                var d = haversine(userPos, [lat, lng]);
                paint([userPos, [lat, lng]], d, null, true);
            });
    }

    function paint(coords, meters, seconds, approx) {
        if (routeLayer) { map.removeLayer(routeLayer); }
        if (userMarker) { map.removeLayer(userMarker); }

        var casing = L.polyline(coords, { color: '#ffffff', weight: 12, opacity: 1, lineCap: 'round', lineJoin: 'round' });
        var line = L.polyline(coords, {
            color: '#E5202E', weight: 6, opacity: 1, lineCap: 'round', lineJoin: 'round',
            dashArray: approx ? '1 12' : null
        });
        routeLayer = L.layerGroup([casing, line]).addTo(map);

        var youIcon = L.divIcon({ className: 'you', html: '<span></span>', iconSize: [24, 24], iconAnchor: [12, 12] });
        userMarker = L.marker(userPos, { icon: youIcon, keyboard: false, title: 'You are here' })
            .addTo(map)
            .bindTooltip('You', { permanent: true, direction: 'top', offset: [0, -12], className: 'you-tip' });

        var narrow = window.innerWidth < 700;
        map.fitBounds(L.latLngBounds(coords), { padding: narrow ? [36, 36] : [60, 60], maxZoom: 17 });

        if (!approx) { animateLine(line); }

        /* panel under the map */
        routeReady = true;
        setBusy(false, '🔄 Update my location');
        modesBox.hidden = false;
        resultBox.hidden = false;
        distEl.textContent = fmtDist(meters);
        timeEl.textContent = approx ? 'in a straight line' : fmtTime(seconds) + ' ' + (mode === 'car' ? 'by car' : mode === 'bike' ? 'by bike' : 'on foot');
        setStatus(approx
            ? "Couldn't load road directions, so this dotted line just shows the direction. Use \"Open in Google Maps\" for turn-by-turn."
            : 'Your route to ' + placeName + ' is on the map.', approx ? 'warn' : 'ok');

        /* hero read-out + buttons */
        if (heroInfo) {
            if (meters < 120) {
                heroInfo.textContent = "🎉 You're basically at our door!";
            } else if (approx) {
                heroInfo.textContent = '📍 ' + fmtDist(meters) + ' away. Tap below for directions.';
            } else {
                heroInfo.textContent = ICON[mode] + ' ' + fmtTime(seconds) + ' ' + VERB[mode] + ' · ' + fmtDist(meters) + ' away';
            }
        }
        goEls.forEach(function (a) {
            if (a.dataset.readyLabel) { a.textContent = a.dataset.readyLabel; }
        });
        updateGoogleLink();
    }

    /* Draw-on effect for the route line */
    function animateLine(line) {
        var path = line.getElement && line.getElement();
        if (!path || !path.getTotalLength) { return; }
        try {
            var len = path.getTotalLength();
            path.style.transition = 'none';
            path.style.strokeDasharray = len + ' ' + len;
            path.style.strokeDashoffset = len;
            void path.getBoundingClientRect();
            path.style.transition = 'stroke-dashoffset 1.4s ease-out';
            path.style.strokeDashoffset = 0;
            setTimeout(function () {
                path.style.transition = '';
                path.style.strokeDasharray = '';
                path.style.strokeDashoffset = '';
            }, 1500);
        } catch (e) { /* purely cosmetic */ }
    }

    /* ---------------- wiring ---------------- */
    routeBtn.addEventListener('click', startRoute);

    modesBox.addEventListener('click', function (e) {
        var b = e.target.closest('.mode');
        if (!b || busy) { return; }
        setMode(b.dataset.mode);
        updateGoogleLink();
        if (userPos) { drawRoute(); }
    });

    // "Take me there" / "Show me the way" buttons: scroll to the map (their href) AND start the route.
    // Once the route exists they turn into "Start navigation" links that open Google Maps instead.
    goEls.forEach(function (a) {
        a.addEventListener('click', function () {
            if (routeReady) { return; }
            startRoute();
        });
    });

    // Load the map when the section is about to be seen
    var section = document.getElementById('find-us');
    if ('IntersectionObserver' in window && section) {
        var io = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) { io.disconnect(); initMap(); }
        }, { rootMargin: '400px 0px' });
        io.observe(section);
    } else {
        initMap();
    }

    // Returning visitor who already allowed location: show the distance straight away
    try {
        if (navigator.permissions && navigator.permissions.query) {
            navigator.permissions.query({ name: 'geolocation' }).then(function (s) {
                if (s.state === 'granted' && !userPos && !busy) { startRoute(); }
            }).catch(function () {});
        }
    } catch (e) { /* ignore */ }
})();
