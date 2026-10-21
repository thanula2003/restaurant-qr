<article class="dish cat-{{ $dish['category'] }}" data-category="{{ $dish['category'] }}">
    <div class="dish__media">
        @if($dish['badge'])
            <span class="badge badge--{{ $dish['badge_class'] }}">{{ $dish['badge_label'] }}</span>
        @endif
        <span class="dish__emoji" aria-hidden="true">{{ $dish['emoji'] }}</span>
        @if($dish['image_url'])
            <img src="{{ $dish['image_url'] }}" alt="{{ $dish['name'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer" onerror="this.remove()">
        @endif
        <span class="dish__price">{{ $dish['price_fmt'] }}</span>
    </div>
    <div class="dish__body">
        <div class="dish__top">
            <h3 class="dish__name">{{ $dish['name'] }}</h3>
            <span class="dish__rating"><span class="star">★</span> {{ number_format($dish['rating'], 1) }}</span>
        </div>
        <p class="dish__desc">{{ $dish['desc'] }}</p>
    </div>
</article>
