<?php

namespace Database\Seeders;

use App\Models\languageSuported;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSuportedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages[] = [
            'name' => "Danish",
            'short_name' => "dk",
            ];
        $languages[] = [
            'name' => "Dutch",
            'short_name' => "nl",
            ];
        $languages[] = [
            'name' => "English",
            'short_name' => "en",
            ];
        $languages[] = [
            'name' => "French",
            'short_name' => "fr",
            ];
        $languages[] = [
            'name' => "German",
            'short_name' => "de",
            ];
        $languages[] = [
            'name' => "Italian",
            'short_name' => "it",
            ];
        $languages[] = [
            'name' => "Norwegian",
            'short_name' => "no",
            ];
        $languages[] = [
            'name' => "Spanish",
            'short_name' => "es",
            ];
        $languages[] = [
            'name' => "Swedish",
            'short_name' => "se",
            ];
        languageSuported::create($languages);
        foreach ($languages as $language) {

            //languageSuported::create(['name' => $language]);
        }
    }
}
