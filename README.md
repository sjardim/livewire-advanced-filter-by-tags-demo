# Advanced search and filter with Laravel and Livewire

A demo app using Laravel 8 and Livewire 2 showing how to implement a list of articles, videos and tags, via a **polymorphic relationship**, that can be searched and/or filtered by tags. 

The Tag list shows the article/video count of each tag and works with pagination. Tags are colored* (via a color attribute on the db) to be easier to see they working. You cam select multiple tags and the filter will update the list of items.

This version 2 does NOT work on a SQLite database. You can also seed the database with the provided seeder classes by running `php artisan migrate --seed` after updating your .env file.

This demo app is an updated and improved version of my old [Livewire 1 demo](https://github.com/sjardim/laravel-livewire-demo) which was based from [/breadthe/laravel-livewire-demo](https://github.com/breadthe/laravel-livewire-demo).

## Preview

https://user-images.githubusercontent.com/125217/147776236-ff18e06c-3aae-4833-9469-592d41b4c71d.mp4

## Demo

https://livewire.sergiojardim.com/

## How to run the demo

1. Clone the repo
1. Run `composer install`
1. Rename `.env.example` to `.env` and change the **database settings** according to your machine. **This version won't work with SQLite.**
1. Run `php artisan migrate --seed` to load fake content into your database. 
1. Run `php artisan serve` if you are not using Laravel Valet.

You don't need to run `npm install` as we are using Tailwind CSS 3.0 via its CDN on `layouts/app.blade.php`. :)

## Performance

Loading 100 items (500 articles, 500 videos), 10 per page, and the 15 most used tags on my 13" MacBook Pro M1, took on **subsequent page loads**:

1. Using the a MySQL 8.0.22 database: less than 80ms using 2MB of RAM.

Measured using [Laravel DebugBar](https://github.com/barryvdh/laravel-debugbar).

Note: The aforementioned [demo](https://livewire.sergiojardim.com/) is running on a 4GB Hetzner VPS using MariaDB.

---
**\*Note**: the tags colors were generated randomly and text contrast sometimes is bad, please on a real app consider fixing this for a better UX.
