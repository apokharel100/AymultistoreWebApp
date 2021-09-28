<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use Carbon\Carbon;

class SiteSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_time = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        SiteSetting::create([
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'sitetitle' => 'My Site',
            'siteemail' => 'info@yoursite.com',
            'sitekeyword' => 'About Your Site',
            'facebookurl' => 'https://www.facebook.com/',
            'twitterurl' => 'https://twitter.com/',
            'googleplusurl' => 'https://plus.google.com/',
            'linkedinurl' => 'https://www.linkedin.com/',
            'instagramurl' => 'https://www.instagram.com/',
            'phone' => '9800000000',
            'mobile' => '9800000000',
            'fax' => '4422',
            'address' => 'Kathmandu, Nepal',
            'created_at' => $current_time,
            'updated_at' => $current_time
        ]);
    }
}
