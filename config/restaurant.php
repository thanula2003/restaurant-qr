<?php

/*
|--------------------------------------------------------------------------
| Restaurant settings & menu
|--------------------------------------------------------------------------
| Everything shown on the website lives here. Edit, save, refresh.
| (Run `php artisan config:clear` if you have cached the config.)
|
| PHOTOS  ('image' field of each dish)
|   * 'photo-1234-abcd'  -> a free Unsplash photo (loaded from their CDN)
|   * 'my-kottu.jpg'     -> your own photo saved in public/images/menu/
|   * null               -> a coloured emoji tile is shown instead
| Tip: your own photos of your real dishes always beat stock photos.
*/

return [

    'name'        => env('RESTAURANT_NAME', 'Golden Fork'),
    'tagline'     => 'Hungry? Scan. Walk. Eat.',
    'description' => 'Burgers, pizza, kottu, shakes and more. Made fresh, served hot, and just around the corner from where you are.',

    'currency'    => 'Rs.',

    // ---- Contact (replace with your real details) ----
    'phone'       => '+94 77 123 4567',
    'email'       => 'hello@goldenfork.lk',
    'address'     => '123 Main Street, Your Town, Sri Lanka',

    'socials' => [
        'Instagram' => '#',
        'TikTok'    => '#',
        'Facebook'  => '#',
    ],

    // ---- Opening hours ----
    // 'hours' is the text shown to visitors.
    'hours' => [
        ['Monday – Friday',   '10:00 AM – 10:00 PM'],
        ['Saturday – Sunday', '9:00 AM – 11:00 PM'],
    ],

    // 'schedule' powers the live "Open now / Closed" badge (24h format).
    // 0 = Sunday ... 6 = Saturday. Keep it in line with 'hours' above.
    'timezone' => 'Asia/Colombo',
    'schedule' => [
        0 => ['09:00', '23:00'],
        1 => ['10:00', '22:00'],
        2 => ['10:00', '22:00'],
        3 => ['10:00', '22:00'],
        4 => ['10:00', '22:00'],
        5 => ['10:00', '22:00'],
        6 => ['09:00', '23:00'],
    ],

    // ---- Google Maps ----
    'map' => [
        'lat'   => 7.486878,
        'lng'   => 80.366486,
        'embed' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d583.6833886845259!2d80.36648572688642!3d7.486878401246619!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sen!2slk!4v1792267045376!5m2!1sen!2slk',
    ],

    // ---- Hero photo collage (free Unsplash photos) ----
    'hero_photos' => [
        ['id' => 'photo-1568901346375-23c9450c58cd', 'emoji' => '🍔', 'alt' => 'Fresh burger',      'class' => 'shot--a'],
        ['id' => 'photo-1513104890138-7c749659a591', 'emoji' => '🍕', 'alt' => 'Pizza on a board',  'class' => 'shot--b'],
        ['id' => 'photo-1623065422902-30a2d299bbe4', 'emoji' => '🥭', 'alt' => 'Mango smoothie',    'class' => 'shot--c'],
    ],

    // ---- Categories ----
    'categories' => [
        ['slug' => 'pizza',    'name' => 'Pizza',        'emoji' => '🍕'],
        ['slug' => 'burgers',  'name' => 'Burgers',      'emoji' => '🍔'],
        ['slug' => 'rice',     'name' => 'Rice & Kottu', 'emoji' => '🍛'],
        ['slug' => 'pasta',    'name' => 'Pasta',        'emoji' => '🍝'],
        ['slug' => 'wraps',    'name' => 'Wraps',        'emoji' => '🌯'],
        ['slug' => 'bites',    'name' => 'Bites',        'emoji' => '🍗'],
        ['slug' => 'desserts', 'name' => 'Sweet Stuff',  'emoji' => '🧇'],
        ['slug' => 'drinks',   'name' => 'Drinks',       'emoji' => '🥤'],
    ],

    // ---- Combo deals (SAMPLE deals: edit or delete) ----
    // color: yellow | red | white
    'deals' => [
        ['title' => 'Student Combo', 'emoji' => '🎒', 'items' => 'Burger + Fries + Drink',          'price' => 1490, 'tag' => 'Pocket friendly', 'color' => 'yellow'],
        ['title' => 'Squad Deal',    'emoji' => '🍕', 'items' => 'Large Pizza + Fries + 2 Drinks',   'price' => 3290, 'tag' => 'Feeds 3',         'color' => 'red'],
        ['title' => 'Date Night',    'emoji' => '💛', 'items' => '2 Pastas + Shake to share',        'price' => 3190, 'tag' => 'For two',         'color' => 'white'],
    ],

    // ---- Menu ----
    // badge: 'Best Seller' | 'Hot' | 'New' | 'Spicy' | 'Veg' | null
    // popular: true  -> also shown in "Popular Dishes"
    'menu' => [

        // ===== Pizza =====
        ['name' => 'Margherita Pizza',  'category' => 'pizza', 'price' => 1850, 'rating' => 4.8, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🍕', 'image' => 'photo-1513104890138-7c749659a591', 'desc' => 'Stone-baked base, tomato, fresh mozzarella and basil.'],
        ['name' => 'Pepperoni Feast',   'category' => 'pizza', 'price' => 2250, 'rating' => 4.9, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🍕', 'image' => 'photo-1534308983496-4fabb1a015ee', 'desc' => 'Loaded with crispy pepperoni, mozzarella and chilli honey.'],
        ['name' => 'Meat Lovers Pizza', 'category' => 'pizza', 'price' => 2650, 'rating' => 4.8, 'badge' => 'New',         'popular' => true,  'emoji' => '🍕', 'image' => 'photo-1628840042765-356cda07504e', 'desc' => 'Pepperoni, ham and sausage under a blanket of cheese.'],
        ['name' => 'Supreme Pizza',     'category' => 'pizza', 'price' => 2450, 'rating' => 4.7, 'badge' => null,          'popular' => false, 'emoji' => '🍕', 'image' => 'photo-1604382354936-07c5d9983bd3', 'desc' => 'Sausage, mushrooms, olives and peppers. The works.'],
        ['name' => 'Spicy Devil Pizza', 'category' => 'pizza', 'price' => 2350, 'rating' => 4.6, 'badge' => 'Spicy',       'popular' => false, 'emoji' => '🌶️', 'image' => 'photo-1564128442383-9201fcc740eb', 'desc' => 'Hot pepperoni, jalapeños and a fiery chilli drizzle.'],

        // ===== Burgers =====
        ['name' => 'Classic Beef Burger',  'category' => 'burgers', 'price' => 1450, 'rating' => 4.7, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🍔', 'image' => 'photo-1568901346375-23c9450c58cd', 'desc' => 'Juicy beef patty, cheddar, lettuce and our secret sauce.'],
        ['name' => 'Double Cheese Stack',  'category' => 'burgers', 'price' => 1750, 'rating' => 4.9, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🍔', 'image' => 'photo-1572802419224-296b0aeee0d9', 'desc' => 'Two patties, double cheese, zero regrets.'],
        ['name' => 'Crispy Chicken Burger','category' => 'burgers', 'price' => 1350, 'rating' => 4.8, 'badge' => null,          'popular' => true,  'emoji' => '🍔', 'image' => 'photo-1637710847214-f91d99669e18', 'desc' => 'Buttermilk-fried chicken, slaw and chilli mayo.'],
        ['name' => 'Smash Burger',         'category' => 'burgers', 'price' => 1650, 'rating' => 4.7, 'badge' => 'New',         'popular' => false, 'emoji' => '🍔', 'image' => 'photo-1607013251379-e6eecfffe234', 'desc' => 'Crispy-edged smashed patties, pickles and melty cheese.'],
        ['name' => 'Burger & Fries Combo', 'category' => 'burgers', 'price' => 1890, 'rating' => 4.8, 'badge' => 'Best Seller', 'popular' => false, 'emoji' => '🍟', 'image' => 'photo-1551782450-a2132b4ba21d', 'desc' => 'Our classic burger with a heap of golden fries.'],

        // ===== Rice & Kottu =====
        // Kottu has no photo yet: add your own, e.g. 'image' => 'chicken-kottu.jpg'
        ['name' => 'Chicken Kottu',     'category' => 'rice', 'price' => 1250, 'rating' => 4.9, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🥘', 'image' => null, 'desc' => 'Chopped roti, chicken, egg and veg, sizzled on the hot plate.'],
        ['name' => 'Cheese Kottu',      'category' => 'rice', 'price' => 1450, 'rating' => 4.8, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🧀', 'image' => null, 'desc' => 'The classic, smothered in melted cheese.'],
        ['name' => 'Rice & Curry',      'category' => 'rice', 'price' => 1100, 'rating' => 4.7, 'badge' => null,          'popular' => true,  'emoji' => '🍛', 'image' => 'photo-1743674453123-93356ade2891', 'desc' => 'Rice with a spread of curries, sambol and papadam.'],
        ['name' => 'Chicken Biryani',   'category' => 'rice', 'price' => 1550, 'rating' => 4.8, 'badge' => null,          'popular' => false, 'emoji' => '🍛', 'image' => 'photo-1782541370275-6761522e39d1', 'desc' => 'Fragrant spiced rice with tender chicken and lemon.'],
        ['name' => 'Chicken Fried Rice','category' => 'rice', 'price' => 1250, 'rating' => 4.6, 'badge' => null,          'popular' => false, 'emoji' => '🍚', 'image' => 'photo-1603133872878-684f208fb84b', 'desc' => 'Wok-tossed rice with chicken, egg, peas and carrots.'],
        ['name' => 'Egg Fried Rice',    'category' => 'rice', 'price' => 950,  'rating' => 4.5, 'badge' => null,          'popular' => false, 'emoji' => '🍚', 'image' => 'photo-1512058564366-18510be2db19', 'desc' => 'Simple, savoury and always satisfying.'],

        // ===== Pasta =====
        ['name' => 'Truffle Mushroom Pasta','category' => 'pasta', 'price' => 1650, 'rating' => 4.8, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🍝', 'image' => 'photo-1608219992759-8d74ed8d76eb', 'desc' => 'Creamy wild mushroom sauce finished with truffle oil.'],
        ['name' => 'Creamy Penne Alfredo',  'category' => 'pasta', 'price' => 1550, 'rating' => 4.7, 'badge' => null,          'popular' => true,  'emoji' => '🍝', 'image' => 'photo-1555949258-eb67b1ef0ceb', 'desc' => 'Penne in a silky parmesan and garlic cream sauce.'],
        ['name' => 'Spicy Arrabbiata',      'category' => 'pasta', 'price' => 1450, 'rating' => 4.6, 'badge' => 'Spicy',       'popular' => true,  'emoji' => '🍝', 'image' => 'photo-1598866594230-a7c12756260f', 'desc' => 'Fiery tomato, garlic and chilli sauce.'],
        ['name' => 'Spaghetti Pomodoro',    'category' => 'pasta', 'price' => 1350, 'rating' => 4.6, 'badge' => 'Veg',         'popular' => false, 'emoji' => '🍝', 'image' => 'photo-1713561058969-793049b01712', 'desc' => 'Fresh tomato sauce, parsley and olive oil.'],
        ['name' => 'Roasted Pepper Cream Pasta','category' => 'pasta', 'price' => 1600, 'rating' => 4.5, 'badge' => 'New',     'popular' => false, 'emoji' => '🍝', 'image' => 'photo-1570549986390-6bd150ac3515', 'desc' => 'Silky cream sauce topped with roasted red pepper.'],

        // ===== Wraps =====
        ['name' => 'Beef Shawarma Wrap', 'category' => 'wraps', 'price' => 1250, 'rating' => 4.7, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🌯', 'image' => 'photo-1529006557810-274b9b2fc783', 'desc' => 'Spiced shaved beef, pickles and garlic sauce.'],
        ['name' => 'Loaded Chicken Wrap','category' => 'wraps', 'price' => 1150, 'rating' => 4.6, 'badge' => null,          'popular' => true,  'emoji' => '🌯', 'image' => 'photo-1719282431565-3b30bb7d2658', 'desc' => 'Grilled chicken, crunchy veg and a creamy dressing.'],
        ['name' => 'Street Tacos (3 pcs)','category' => 'wraps','price' => 1350, 'rating' => 4.7, 'badge' => 'New',         'popular' => true,  'emoji' => '🌮', 'image' => 'photo-1574343635105-4cf2ea136b8b', 'desc' => 'Three soft tacos with salsa, lime and fresh coriander.'],
        ['name' => 'Club Sandwich',      'category' => 'wraps', 'price' => 1250, 'rating' => 4.5, 'badge' => null,          'popular' => false, 'emoji' => '🥪', 'image' => 'photo-1676300184084-de35d56a9a70', 'desc' => 'Triple-decker toasted sandwich served with fries.'],

        // ===== Bites =====
        ['name' => 'Crispy Chicken Wings', 'category' => 'bites', 'price' => 1250, 'rating' => 4.8, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🍗', 'image' => 'photo-1766589221324-656419e4c844', 'desc' => 'Double-fried wings with a dipping sauce.'],
        ['name' => 'BBQ Wings',            'category' => 'bites', 'price' => 1350, 'rating' => 4.7, 'badge' => null,          'popular' => true,  'emoji' => '🍗', 'image' => 'photo-1766589221606-08bc21fa1acd', 'desc' => 'Sticky, smoky barbecue glaze.'],
        ['name' => 'Crispy Fries',         'category' => 'bites', 'price' => 650,  'rating' => 4.6, 'badge' => 'Veg',         'popular' => true,  'emoji' => '🍟', 'image' => 'photo-1630384060421-cb20d0e0649d', 'desc' => 'Golden, salty and impossible to stop eating.'],
        ['name' => 'Fried Chicken (2 pcs)','category' => 'bites', 'price' => 1100, 'rating' => 4.7, 'badge' => 'Best Seller', 'popular' => false, 'emoji' => '🍗', 'image' => 'photo-1585703900468-13c7a978ad86', 'desc' => 'Crunchy, juicy and seasoned just right.'],
        ['name' => 'Steamed Momos',        'category' => 'bites', 'price' => 900,  'rating' => 4.6, 'badge' => 'New',         'popular' => false, 'emoji' => '🥟', 'image' => 'photo-1496116218417-1a781b1c416c', 'desc' => 'Soft dumplings with a spicy dipping sauce.'],

        // ===== Sweet Stuff =====
        ['name' => 'Lava Cake & Ice Cream','category' => 'desserts', 'price' => 950,  'rating' => 4.9, 'badge' => 'Best Seller', 'popular' => true,  'emoji' => '🍫', 'image' => 'photo-1673551490812-eaee2e9bf0ef', 'desc' => 'Warm chocolate cake with a scoop of ice cream.'],
        ['name' => 'Blueberry Cheesecake', 'category' => 'desserts', 'price' => 1050, 'rating' => 4.8, 'badge' => null,          'popular' => true,  'emoji' => '🍰', 'image' => 'photo-1695088957420-c3b97d1f1138', 'desc' => 'Creamy baked cheesecake with blueberry topping.'],
        ['name' => 'Pancake Stack',        'category' => 'desserts', 'price' => 850,  'rating' => 4.7, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🥞', 'image' => 'photo-1757635964104-67218db55bca', 'desc' => 'Fluffy stack with maple syrup and butter.'],
        ['name' => 'Waffle & Ice Cream',   'category' => 'desserts', 'price' => 900,  'rating' => 4.8, 'badge' => 'New',         'popular' => false, 'emoji' => '🧇', 'image' => 'photo-1562513872-634b8fae6dbe', 'desc' => 'Crisp waffle topped with a big scoop of vanilla.'],
        ['name' => 'Choco Waffle',         'category' => 'desserts', 'price' => 850,  'rating' => 4.7, 'badge' => null,          'popular' => false, 'emoji' => '🧇', 'image' => 'photo-1634215751955-5bdb12db6c0c', 'desc' => 'Warm waffle drowned in chocolate sauce.'],
        ['name' => 'Choco Ice Cream',      'category' => 'desserts', 'price' => 450,  'rating' => 4.6, 'badge' => null,          'popular' => false, 'emoji' => '🍨', 'image' => 'photo-1501118914527-baeb72850258', 'desc' => 'Rich chocolate scoops. Simple and perfect.'],

        // ===== Drinks =====
        ['name' => 'Mango Smoothie',  'category' => 'drinks', 'price' => 650, 'rating' => 4.8, 'badge' => 'Hot',         'popular' => true,  'emoji' => '🥭', 'image' => 'photo-1623065422902-30a2d299bbe4', 'desc' => 'Sweet ripe mango blended with ice.'],
        ['name' => 'Strawberry Shake','category' => 'drinks', 'price' => 700, 'rating' => 4.7, 'badge' => null,          'popular' => true,  'emoji' => '🍓', 'image' => 'photo-1579954115545-a95591f28bfc', 'desc' => 'Thick, creamy and pink.'],
        ['name' => 'Cookie Frappe',   'category' => 'drinks', 'price' => 750, 'rating' => 4.8, 'badge' => 'New',         'popular' => true,  'emoji' => '🍪', 'image' => 'photo-1572490122747-3968b75cc699', 'desc' => 'Chocolate cookie crumble blended with ice and cream.'],
        ['name' => 'Brown Sugar Boba','category' => 'drinks', 'price' => 800, 'rating' => 4.7, 'badge' => 'Best Seller', 'popular' => false, 'emoji' => '🧋', 'image' => 'photo-1745883949374-baeba0ed57c3', 'desc' => 'Milk tea with chewy tapioca pearls.'],
        ['name' => 'Iced Latte',      'category' => 'drinks', 'price' => 600, 'rating' => 4.6, 'badge' => null,          'popular' => false, 'emoji' => '☕', 'image' => 'photo-1461023058943-07fcbe16d735', 'desc' => 'Espresso over cold milk and ice.'],
        ['name' => 'Iced Milk Coffee','category' => 'drinks', 'price' => 550, 'rating' => 4.7, 'badge' => null,          'popular' => false, 'emoji' => '🥛', 'image' => 'photo-1562447457-579fc34967fb', 'desc' => 'Cold coffee with creamy milk poured over ice.'],
        ['name' => 'Lime Mint Cooler','category' => 'drinks', 'price' => 450, 'rating' => 4.6, 'badge' => 'Veg',        'popular' => false, 'emoji' => '🍋', 'image' => 'photo-1555949366-819808d99159', 'desc' => 'Zesty lime, crushed mint and sparkling water.'],
    ],
];
