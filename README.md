# Advanced search and filter with Laravel and Livewire

A demo app using Laravel 8 and Livewire 2 showing how to implement a list of articles and tags, via a **polymorphic relationship**, that can be searched and/or filtered by tags. 

The Tag list shows the article count of each tag and works with pagination. Tags are colored* (via a color attribute on the db) to be easier to see they working. You cam select multiple tags and the filter will update the list of items.\

## Choose one of the versions

- Version 1 works with just one model at a time. This is the version on the Main branch. This demo provides a SQLite database (database/database.sqlite) already populated with 100 articles, and 50 tags, so you don't need to migrate the DB to see things working. But you can also seed the database with the provided seeder classes by running `php artisan migrate --seed` after updating your .env file.

- Version 2, a bit more complex, is a **multimodel** version, listing both articles and videos. Get it on branch [search-multiple-models](https://github.com/sjardim/livewire-advanced-filter-by-tags-demo/tree/search-multiple-models). It requires [/protonemedia/laravel-cross-eloquent-search](https://github.com/protonemedia/laravel-cross-eloquent-search)

### Quick demo of version 2 (the multimodel version)

You can see it live here: https://livewire.sergiojardim.com/

https://user-images.githubusercontent.com/125217/147776236-ff18e06c-3aae-4833-9469-592d41b4c71d.mp4


### Screenshots of version 1

<details>
<summary>Light mode</summary>
<img src="https://user-images.githubusercontent.com/125217/147500063-bbe79fb6-4617-4d97-a087-023bcce67564.png" width="800" alt="" />
</details>

<details>
<summary>Dark mode</summary>
<img src="https://user-images.githubusercontent.com/125217/147500417-c146882c-5b7d-426b-8f77-e0080d8af7d2.png" width="800" alt="" />
</details>


## How to run the demo

1. Clone the repo
1. Run `composer install`
1. Rename `.env.example` to `.env` and change the **database settings** according to your machine
1. Run `php artisan migrate --seed` to load fake content into your database
1. Run `php artisan serve` if you are not using Laravel Valet.

You don't need to run `npm install` as we are using Tailwind CSS 3.0 via its CDN on `layouts/app.blade.php`. :)

## Performance

Loading 10 articles (titles + date + tags) per page and the 15 most used tags on my 13" MacBook Pro M1, [Laravel DebugBar](https://github.com/barryvdh/laravel-debugbar) took on **subsequent page loads**:

1. Using the provided SQLite database: less than 50ms using 2MB of RAM.
1. Using the a MySQL 8.0.22 database: less than 80ms using 2MB of RAM.

This demo app is an updated and improved version of my old [Livewire 1 demo](https://github.com/sjardim/laravel-livewire-demo) which was based from [/breadthe/laravel-livewire-demo](https://github.com/breadthe/laravel-livewire-demo).

---
**\*Note**: the tags colors were generated randomly and text contrast sometimes is bad, please on a real app consider fixing this for a better UX.
