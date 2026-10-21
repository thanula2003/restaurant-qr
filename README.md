# Golden Fork – "Hungry? Scan here" Laravel website (Red & Yellow)

Built for QR stickers placed near the restaurant: a visitor scans, lands on the
site, sees how far the restaurant is and gets a route line (walk / drive / bike)
from their location to the door. No ordering anywhere: the goal is to get them to walk in.

Sections: hero with live distance + "Open now" badge → route map → categories →
popular dishes (tabs) → combo deals → full filterable menu → footer.
On phones a bottom bar has **Menu** and **Take me there** (turns into **Start navigation**
and opens Google Maps once the route is known).

## Run locally
    composer create-project laravel/laravel restaurant
Copy these folders from this project into `restaurant/` (merge / replace):

    app/   config/   public/   resources/   routes/

    cd restaurant
    php artisan serve          # http://127.0.0.1:8000

(Or `bash setup.sh restaurant` on Mac/Linux/Git Bash.)

## Customise (everything is in `config/restaurant.php`)
* Name, phone, address, social links, opening hours.
* `schedule` drives the live **Open now / Closed** badge. Keep it in line with `hours`.
* Menu: name, price, rating, badge, description, `popular` flag.
* Combo `deals` are SAMPLES: edit or delete them.
* Colours: variables at the top of `public/css/style.css`.
* Map location: `map.lat` / `map.lng` (already set).

## Photos
* Dish `'image' => 'photo-…'` loads a free Unsplash photo from their CDN.
* Your own photo: save it in `public/images/menu/` and use `'image' => 'chicken-kottu.jpg'`.
* `null` shows a coloured emoji tile. Kottu currently uses this: add real kottu photos.
* If any photo fails to load, the emoji tile shows instead (no broken images).

## Free https hosting (static export)
Location only works on https. The site has no database, so export it to plain files:

    php artisan site:export        # creates the dist/ folder

Upload the **contents** of `dist/` to Netlify or Cloudflare Pages (both free, https included).
Re-run the export and re-upload whenever you change `config/restaurant.php`.

## Files
    routes/web.php                                route
    app/Http/Controllers/HomeController.php       prepares the data
    app/Console/Commands/ExportStatic.php         php artisan site:export
    config/restaurant.php                         settings + menu
    resources/views/home.blade.php                page
    resources/views/partials/dish-card.blade.php  dish card
    public/css/style.css, public/js/app.js        styling + menu interactions
    public/js/map.js                              map, route, open/closed badge
