(function () {
    'use strict';

    var nav = document.getElementById('nav');
    var navLinks = document.getElementById('navLinks');
    var navToggle = document.getElementById('navToggle');

    /* ---- Sticky nav background ---- */
    function onScroll() {
        nav.classList.toggle('is-scrolled', window.scrollY > 30);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ---- Mobile menu ---- */
    navToggle.addEventListener('click', function () {
        var open = navLinks.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    navLinks.addEventListener('click', function (e) {
        if (e.target.closest('a')) {
            navLinks.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        }
    });

    /* ---- Popular dishes: tabs ---- */
    var popularTabs = document.getElementById('popularTabs');
    var popularCards = document.querySelectorAll('#popularGrid .dish');

    function showPopular(slug) {
        popularCards.forEach(function (card) {
            var show = card.dataset.category === slug;
            card.classList.toggle('is-hidden', !show);
            if (show) { restartAnimation(card); }
        });
        popularTabs.querySelectorAll('.tab').forEach(function (t) {
            var on = t.dataset.tab === slug;
            t.classList.toggle('is-active', on);
            if (on) { keepInView(t); }
        });
    }

    popularTabs.addEventListener('click', function (e) {
        var tab = e.target.closest('.tab');
        if (tab) { showPopular(tab.dataset.tab); }
    });

    var firstTab = popularTabs.querySelector('.tab');
    if (firstTab) { showPopular(firstTab.dataset.tab); }

    /* ---- Full menu: filter pills ---- */
    var menuPills = document.getElementById('menuPills');
    var menuCards = document.querySelectorAll('#menuGrid .dish');

    function filterMenu(slug) {
        menuCards.forEach(function (card) {
            var show = slug === 'all' || card.dataset.category === slug;
            card.classList.toggle('is-hidden', !show);
            if (show) { restartAnimation(card); }
        });
        menuPills.querySelectorAll('.pill').forEach(function (p) {
            var on = p.dataset.filter === slug;
            p.classList.toggle('is-active', on);
            if (on) { keepInView(p); }
        });
    }

    menuPills.addEventListener('click', function (e) {
        var pill = e.target.closest('.pill');
        if (pill) { filterMenu(pill.dataset.filter); }
    });

    /* ---- Category icons jump to the menu, filtered ---- */
    document.querySelectorAll('.cat').forEach(function (cat) {
        cat.addEventListener('click', function () {
            filterMenu(cat.dataset.filter);
            document.getElementById('menu').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // On phones the tabs/pills scroll sideways; keep the active one visible (horizontal only)
    function keepInView(btn) {
        var box = btn.parentElement;
        if (box.scrollWidth <= box.clientWidth) { return; }
        var target = btn.offsetLeft - (box.clientWidth - btn.offsetWidth) / 2;
        box.scrollTo({ left: target, behavior: 'smooth' });
    }

    function restartAnimation(el) {
        el.style.animation = 'none';
        void el.offsetWidth; // reflow
        el.style.animation = '';
    }

    /* ---- Scroll reveal ---- */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('is-visible'); });
    }
})();
