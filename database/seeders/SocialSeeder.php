<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class SocialSeeder extends Seeder
{
    public function run(): void
    {
       $socials = [

    [
        'type'   => 'social',
        'title'  => 'Facebook',
        'url'    => 'https://www.facebook.com/bhaiyahousingbd',
        'extra'  => '#1877F2',
        'status' => 1,
        'name'   => 'Facebook',
        'short'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>',
    ],

    [
        'type'   => 'social',
        'title'  => 'Instagram',
        'url'    => 'https://www.instagram.com/bhaiyahousingbd',
        'extra'  => '#E1306C',
        'status' => 1,
        'name'   => 'Instagram',
        'short'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="white"/></svg>',
    ],

    [
        'type'   => 'social',
        'title'  => 'YouTube',
        'url'    => 'https://www.youtube.com/@bhaiyahousingltd.6218',
        'extra'  => '#FF0000',
        'status' => 1,
        'name'   => 'YouTube',
        'short'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98" fill="red"/></svg>',
    ],

    [
        'type'   => 'social',
        'title'  => 'WhatsApp',
        'url'    => 'https://wa.me/8801922030303',
        'extra'  => '#25D366',
        'status' => 1,
        'name'   => 'WhatsApp',
        'short'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.57 1.37 5.06L2 22l4.94-1.37A9.96 9.96 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/><path d="M17 15.17c0 .18-.04.37-.13.55-.12.23-.28.44-.5.62-.32.27-.67.4-1.04.4-.51 0-1.06-.12-1.63-.37a12.1 12.1 0 01-1.72-.99 12.6 12.6 0 01-3.04-3.04c-.42-.57-.75-1.14-.99-1.72-.25-.57-.37-1.12-.37-1.63 0-.36.13-.71.4-1.04.27-.32.58-.48.92-.48.13 0 .26.03.38.08.13.06.24.15.33.28l1.16 1.64c.09.13.15.24.19.35.04.1.07.2.07.3 0 .12-.04.24-.11.36-.07.12-.16.24-.28.36l-.38.4c-.06.06-.08.12-.08.2 0 .08.02.15.05.22.09.17.25.39.47.64.39.45.81.87 1.26 1.26.25.22.47.37.64.46.07.03.14.05.22.05.09 0 .15-.03.21-.09l.38-.38c.13-.13.25-.22.36-.28.12-.07.23-.1.36-.1.1 0 .2.02.31.07l1.66 1.18c.13.09.22.2.28.32.05.12.08.25.08.38z"/></svg>',
    ],

    [
        'type'   => 'social',
        'title'  => 'X (Twitter)',
        'url'    => 'https://x.com/bhaiyahousing',
        'extra'  => '#000000',
        'status' => 1,
        'name'   => 'X (Twitter)',
        'short'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="white"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
    ],

  

];

        foreach ($socials as $social) {
            Content::create($social);
        }
    }
}
