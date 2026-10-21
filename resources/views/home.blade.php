<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $r['name'] }}: {{ $r['tagline'] }}</title>
    <meta name="description" content="{{ $r['description'] }}">
    <meta name="theme-color" content="#E5202E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=DM+Sans:wght@400;500;700&family=Permanent+Marker&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div id="hoursData" data-schedule="{{ $scheduleJson }}" hidden></div>

{{-- ============ NAV ============ --}}
<header class="nav" id="nav">
    <div class="container nav__inner">
        <a href="#home" class="brand">
            <span class="brand__mark">🍴</span>
            <span class="brand__name">{{ $r['name'] }}</span>
        </a>

        <nav class="nav__links" id="navLinks" aria-label="Main">
            <a href="#find-us">Find us</a>
            <a href="#popular">Popular</a>
            <a href="#deals">Deals</a>
            <a href="#menu">Menu</a>
            <a href="#find-us" class="btn btn--yellow btn--sm nav__cta" data-start-route data-ready-label="🧭 Navigate">📍 Take me there</a>
        </nav>

        <button class="nav__toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

{{-- ============ HERO ============ --}}
<section class="hero" id="home">
    <div class="hero__dots"></div>

    <div class="container hero__inner">
        <div class="hero__copy">
            <span class="sticker">👋 You scanned it. Good call.</span>
            <h1>Hungry?<br>We're <mark>right here.</mark></h1>
            <p class="hero__lead">{{ $r['description'] }}</p>

            <div class="gps" id="gps">
                <span class="open-pill" data-open>🕒 Checking opening hours…</span>
                <p class="gps__info" id="heroInfo">Tap the button and we'll show you the quickest way from where you're standing.</p>
                <a href="#find-us" class="btn btn--yellow btn--xl" id="heroGo" data-start-route data-ready-label="🧭 Start navigation">📍 Show me the way</a>
            </div>
        </div>

        <div class="hero__visual">
            <div class="collage">
                @foreach($heroShots as $shot)
                    <figure class="shot {{ $shot['class'] }}">
                        <span class="shot__emoji" aria-hidden="true">{{ $shot['emoji'] }}</span>
                        <img src="{{ $shot['url'] }}" alt="{{ $shot['alt'] }}" decoding="async" referrerpolicy="no-referrer" onerror="this.remove()">
                    </figure>
                @endforeach

                <span class="spin-badge" aria-hidden="true">SCAN ➜ WALK ➜ EAT</span>
                <span class="chip chip--a">🔥 Fresh &amp; hot</span>
                <span class="chip chip--b">⭐ 4.9 loved</span>
            </div>
        </div>
    </div>
</section>

{{-- ============ MARQUEE ============ --}}
<div class="marquee" aria-hidden="true">
    <div class="marquee__track">
        @foreach([1, 2] as $copy)
            <span>Hungry?</span><i>✦</i><span>Scan</span><i>✦</i><span>Walk</span><i>✦</i><span>Eat</span><i>✦</i><span>Repeat</span><i>✦</i>
            <span>Fresh &amp; hot</span><i>✦</i><span>Big flavours</span><i>✦</i>
            <span>Hungry?</span><i>✦</i><span>Scan</span><i>✦</i><span>Walk</span><i>✦</i><span>Eat</span><i>✦</i><span>Repeat</span><i>✦</i>
            <span>Fresh &amp; hot</span><i>✦</i><span>Big flavours</span><i>✦</i>
        @endforeach
    </div>
</div>

{{-- ============ FIND US / ROUTE MAP ============ --}}
<section class="section" id="find-us">
    <div class="container">
        <header class="section__head">
            <span class="tagline">Your way to us 👇</span>
            <h2>Walk or drive. We'll show you.</h2>
            <p>Tap the button and a route line is drawn from where you are to our door.</p>
        </header>

        <div class="visit">
            <div class="visit__mapwrap">
                <div class="visit__map" id="map"
                     data-lat="{{ $r['map']['lat'] }}"
                     data-lng="{{ $r['map']['lng'] }}"
                     data-name="{{ $r['name'] }}"
                     data-address="{{ $r['address'] }}">
                    <div class="map__loading">Loading map…</div>
                </div>

                {{-- Used only if the interactive map can't load --}}
                <template id="mapFallback">
                    <iframe
                        src="{{ $r['map']['embed'] }}"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        title="{{ $r['name'] }} on Google Maps"
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </template>

                <div class="route" id="route">
                    <button type="button" class="btn btn--red route__btn" id="routeBtn">📍 Show route from my location</button>

                    <div class="route__modes" id="routeModes" hidden>
                        <button type="button" class="mode is-active" data-mode="foot">🚶 Walk</button>
                        <button type="button" class="mode" data-mode="car">🚗 Drive</button>
                        <button type="button" class="mode" data-mode="bike">🚴 Bike</button>
                    </div>

                    <div class="route__result" id="routeResult" hidden>
                        <strong id="routeDist"></strong>
                        <span id="routeTime"></span>
                    </div>

                    <p class="route__status" id="routeStatus" role="status" aria-live="polite">We only use your location to draw the route. Nothing is saved.</p>
                </div>
            </div>

            <aside class="visit__card">
                <h3>{{ $r['name'] }}</h3>
                <span class="open-pill" data-open>🕒 Checking opening hours…</span>

                <ul class="visit__list">
                    <li><span>📍</span><div><b>Address</b>{{ $r['address'] }}</div></li>
                    <li><span>🕒</span>
                        <div><b>Opening hours</b>
                            @foreach($r['hours'] as $row)
                                <div class="hours"><span>{{ $row[0] }}</span><span>{{ $row[1] }}</span></div>
                            @endforeach
                        </div>
                    </li>
                    <li><span>📞</span><div><b>Phone</b><a href="{{ $phoneUrl }}">{{ $r['phone'] }}</a></div></li>
                </ul>

                <a href="{{ $directionsUrl }}" id="gmapsLink" class="btn btn--white btn--block" target="_blank" rel="noopener">🧭 Open in Google Maps</a>
            </aside>
        </div>
    </div>
</section>

{{-- ============ CATEGORIES ============ --}}
<section class="section section--tight" id="categories">
    <div class="container">
        <header class="section__head">
            <span class="tagline">What are you craving?</span>
            <h2>Browse by category</h2>
        </header>

        <div class="cats">
            @foreach($categories as $cat)
                <button class="cat" type="button" data-filter="{{ $cat['slug'] }}">
                    <span class="cat__icon">{{ $cat['emoji'] }}</span>
                    <span class="cat__name">{{ $cat['name'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ POPULAR ============ --}}
<section class="section" id="popular">
    <div class="container">
        <header class="section__head">
            <span class="tagline">Crowd favourites 🔥</span>
            <h2>Popular dishes</h2>
        </header>

        <div class="tabs" id="popularTabs" role="tablist">
            @foreach($categories as $cat)
                <button type="button" role="tab" class="tab {{ $loop->first ? 'is-active' : '' }}" data-tab="{{ $cat['slug'] }}">{{ $cat['emoji'] }} {{ $cat['name'] }}</button>
            @endforeach
        </div>

        <div class="grid" id="popularGrid">
            @foreach($popular as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @endforeach
        </div>
    </div>
</section>

{{-- ============ DEALS ============ --}}
<section class="section section--red" id="deals">
    <div class="container">
        <header class="section__head section__head--light">
            <span class="tagline">Better together</span>
            <h2>Combo deals</h2>
            <p>Bring your squad. Share the love (and the fries).</p>
        </header>

        <div class="deals">
            @foreach($deals as $deal)
                <article class="deal deal--{{ $deal['color'] }}">
                    <span class="deal__emoji" aria-hidden="true">{{ $deal['emoji'] }}</span>
                    <span class="deal__tag">{{ $deal['tag'] }}</span>
                    <h3>{{ $deal['title'] }}</h3>
                    <p>{{ $deal['items'] }}</p>
                    <strong class="deal__price">{{ $deal['price_fmt'] }}</strong>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FULL MENU ============ --}}
<section class="section section--yellow" id="menu">
    <div class="container">
        <header class="section__head">
            <span class="tagline">The whole menu</span>
            <h2>Something for every craving</h2>
            <p>Pick a category, or scroll through everything.</p>
        </header>

        <div class="pills" id="menuPills">
            <button type="button" class="pill is-active" data-filter="all">All</button>
            @foreach($categories as $cat)
                <button type="button" class="pill" data-filter="{{ $cat['slug'] }}">{{ $cat['emoji'] }} {{ $cat['name'] }}</button>
            @endforeach
        </div>

        <div class="grid" id="menuGrid">
            @foreach($menu as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FINAL CTA ============ --}}
<section class="cta">
    <div class="container cta__inner">
        <h2>Okay okay… come eat. 😋</h2>
        <p>We're a short trip away. Let us show you the way.</p>
        <a href="#find-us" class="btn btn--yellow btn--xl" data-start-route data-ready-label="🧭 Start navigation">📍 Take me there</a>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="footer">
    <div class="container footer__inner">
        <div>
            <a href="#home" class="brand brand--light">
                <span class="brand__mark">🍴</span>
                <span class="brand__name">{{ $r['name'] }}</span>
            </a>
            <p class="footer__tag">{{ $r['tagline'] }}</p>
        </div>

        <div class="footer__social">
            @foreach($r['socials'] as $label => $link)
                <a href="{{ $link }}" target="_blank" rel="noopener">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="footer__copy">
        © {{ date('Y') }} {{ $r['name'] }}. All rights reserved. ·
        Food photos from <a href="https://unsplash.com" target="_blank" rel="noopener">Unsplash</a> contributors.
    </div>
</footer>

{{-- ============ MOBILE DOCK ============ --}}
<nav class="dock" aria-label="Quick actions">
    <a href="#menu" class="dock__menu"><span>🍽️</span>Menu</a>
    <a href="#find-us" class="dock__main" data-start-route data-ready-label="🧭 Start navigation"><span>📍</span>Take me there</a>
</nav>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script defer src="{{ asset('js/app.js') }}"></script>
<script defer src="{{ asset('js/map.js') }}"></script>
</body>
</html>
