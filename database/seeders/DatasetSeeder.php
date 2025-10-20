<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use App\Models\DatasetItem;

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        $ds = Dataset::firstOrCreate(
            ['name' => 'English Easy Words v1'],
            [
                'language' => 'en',
                'type' => 'word',
                'level' => 'Easy',
                'is_public' => true,
                'description' => 'Starter words for TalkEase practice.',
            ]
        );

        $words = [
            ['text' => 'cat', 'category' => 'Animals', 'ipa' => 'kæt'],
            ['text' => 'dog', 'category' => 'Animals', 'ipa' => 'dɔːg'],
            ['text' => 'sun', 'category' => 'Nature',  'ipa' => 'sʌn'],
            ['text' => 'book','category' => 'Objects', 'ipa' => 'bʊk'],
            ['text' => 'water','category' => 'Everyday','ipa' => 'ˈwɔːtə'],
        ];

        foreach ($words as $w) {
            DatasetItem::firstOrCreate(
                ['dataset_id' => $ds->id, 'text' => $w['text']],
                [
                    'type' => 'word',
                    'category' => $w['category'] ?? null,
                    'difficulty' => 'Easy',
                    'ipa' => $w['ipa'] ?? null,
                    'metadata' => ['source' => 'seed'],
                ]
            );
        }

        $ds->update(['item_count' => $ds->items()->count()]);
    }
}
