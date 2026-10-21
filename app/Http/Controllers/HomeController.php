<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    private const BADGES = [
        'Best Seller' => ['best',  '⭐ Best Seller'],
        'Hot'         => ['hot',   '🔥 Hot'],
        'New'         => ['new',   '✨ New'],
        'Spicy'       => ['spicy', '🌶️ Spicy'],
        'Veg'         => ['veg',   '🌱 Veg'],
    ];

    public function index()
    {
        $r = config('restaurant');

        $menu = collect($r['menu'])->map(function (array $dish) use ($r) {
            $dish['image_url'] = $this->photoUrl($dish['image'] ?? null, 560, 420);
            $dish['price_fmt'] = $r['currency'] . ' ' . number_format($dish['price']);

            $badge = self::BADGES[$dish['badge'] ?? ''] ?? null;
            $dish['badge_class'] = $badge[0] ?? '';
            $dish['badge_label'] = $badge[1] ?? '';

            return $dish;
        });

        $categories = collect($r['categories'])->map(function (array $cat) use ($menu) {
            $cat['count'] = $menu->where('category', $cat['slug'])->count();

            return $cat;
        });

        $deals = collect($r['deals'] ?? [])->map(function (array $deal) use ($r) {
            $deal['price_fmt'] = $r['currency'] . ' ' . number_format($deal['price']);

            return $deal;
        });

        $heroShots = collect($r['hero_photos'] ?? [])->map(function (array $shot) {
            $shot['url'] = $this->photoUrl($shot['id'], 760, 760);

            return $shot;
        });

        return view('home', [
            'r'             => $r,
            'menu'          => $menu,
            'categories'    => $categories,
            'popular'       => $menu->where('popular', true)->values(),
            'deals'         => $deals,
            'heroShots'     => $heroShots,
            'phoneUrl'      => 'tel:' . preg_replace('/\s+/', '', $r['phone']),
            'directionsUrl' => 'https://www.google.com/maps/dir/?api=1&destination=' . $r['map']['lat'] . ',' . $r['map']['lng'],
            'scheduleJson'  => json_encode(['tz' => $r['timezone'], 'days' => $r['schedule']]),
        ]);
    }

    /**
     * 'photo-…'  => free Unsplash photo (hot-linked from their CDN)
     * 'file.jpg' => your own file in public/images/menu/
     * null       => no photo (the page shows an emoji tile)
     */
    private function photoUrl(?string $image, int $w, int $h): ?string
    {
        if (! $image) {
            return null;
        }

        if (str_starts_with($image, 'photo-')) {
            return "https://images.unsplash.com/{$image}?auto=format&fit=crop&w={$w}&h={$h}&q=75";
        }

        return file_exists(public_path('images/menu/' . $image))
            ? asset('images/menu/' . $image)
            : null;
    }
}
