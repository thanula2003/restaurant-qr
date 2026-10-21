<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportStatic extends Command
{
    protected $signature = 'site:export {--out=dist : Output folder, relative to the project root}';

    protected $description = 'Export the website as plain static files for free hosting (Netlify, Cloudflare Pages, GitHub Pages)';

    public function handle(): int
    {
        $out = base_path($this->option('out'));

        File::deleteDirectory($out);
        File::ensureDirectoryExists($out);

        $html = app(HomeController::class)->index()->render();

        // asset() builds absolute URLs from APP_URL. Make them relative so the
        // folder works on any domain or sub-path.
        $base = rtrim(config('app.url'), '/') . '/';
        $html = str_replace($base, '', $html);

        File::put($out . '/index.html', $html);
        File::put($out . '/.nojekyll', '');

        foreach (['css', 'js', 'images'] as $dir) {
            if (File::isDirectory(public_path($dir))) {
                File::copyDirectory(public_path($dir), $out . '/' . $dir);
            }
        }

        if (File::exists(public_path('favicon.ico'))) {
            File::copy(public_path('favicon.ico'), $out . '/favicon.ico');
        }

        $this->info('Static site exported to: ' . $out);
        $this->line('Upload the contents of that folder to Netlify / Cloudflare Pages / GitHub Pages.');

        return self::SUCCESS;
    }
}
