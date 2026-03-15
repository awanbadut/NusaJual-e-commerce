<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\User;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:build';
    protected $description = 'Generate sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'))
            ->add(Url::create('/katalog')->setPriority(0.9)->setChangeFrequency('daily'));

        Product::all()->each(fn($p) => $sitemap->add(
            Url::create('/produk/' . $p->id)->setPriority(0.8)->setChangeFrequency('weekly')
        ));

        User::where('role', 'seller')->get()->each(fn($u) => $sitemap->add(
            Url::create('/mitra/' . $u->id)->setPriority(0.7)
        ));

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated: ' . count($sitemap->getTags()) . ' URLs');
    }
}
