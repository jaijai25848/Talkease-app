<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dataset;
use App\Models\DatasetItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use ZipArchive;

class ImportDataset extends Command
{
    protected $signature = 'dataset:import 
        {csv : Path to CSV file} 
        {--name= : Dataset name (new or existing)} 
        {--language=en} 
        {--type=word : word|sentence} 
        {--level=Easy} 
        {--zip= : Optional path to a ZIP containing audio files}';

    protected $description = 'Import dataset items from CSV (and optional audio ZIP).';

    public function handle(): int
    {
        $csvPath = $this->argument('csv');
        if (!file_exists($csvPath)) {
            $this->error("CSV not found: {$csvPath}");
            return self::FAILURE;
        }

        $name = $this->option('name') ?? pathinfo($csvPath, PATHINFO_FILENAME);
        $type = $this->option('type');
        $language = $this->option('language');
        $level = $this->option('level');

        $dataset = Dataset::firstOrCreate(
            ['name' => $name],
            compact('language','type','level') + ['is_public' => true, 'description' => 'Imported via CLI']
        );
        $slug = $dataset->slug;

        // Build audio map if ZIP provided
        $audioMap = [];
        if ($zipFile = $this->option('zip')) {
            if (file_exists($zipFile)) {
                $tmp = storage_path('app/tmp-'.$slug.'-audio');
                @mkdir($tmp, 0775, true);
                $zip = new ZipArchive();
                if ($zip->open($zipFile) === true) {
                    $zip->extractTo($tmp);
                    $zip->close();
                    $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tmp));
                    foreach ($rii as $f) {
                        if ($f->isFile()) $audioMap[strtolower($f->getFilename())] = $f->getPathname();
                    }
                } else {
                    $this->warn('Failed to open ZIP; continuing without audio');
                }
            } else {
                $this->warn("ZIP not found: {$zipFile}");
            }
        }

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $stored = 0;
        foreach ($records as $row) {
            $text = trim($row['text'] ?? '');
            if ($text === '') continue;

            $rowType    = strtolower($row['type'] ?? $type);
            $category   = $row['category'] ?? null;
            $difficulty = $row['difficulty'] ?? $level;
            $ipa        = $row['ipa'] ?? null;
            $ttsVoice   = $row['tts_voice'] ?? null;
            $audioCol   = $row['audio'] ?? null;

            $audioPath = null;
            if ($audioCol) {
                $key = strtolower(basename($audioCol));
                if (isset($audioMap[$key])) {
                    $dest = "datasets/{$slug}/audio/".Str::random(8).'_'.$key;
                    Storage::disk('public')->put($dest, file_get_contents($audioMap[$key]));
                    $audioPath = $dest;
                } elseif (file_exists($audioCol)) {
                    $dest = "datasets/{$slug}/audio/".Str::random(8).'_'.basename($audioCol);
                    Storage::disk('public')->put($dest, file_get_contents($audioCol));
                    $audioPath = $dest;
                }
            }

            DatasetItem::updateOrCreate(
                ['dataset_id' => $dataset->id, 'text' => $text],
                [
                    'type' => in_array($rowType, ['word','sentence']) ? $rowType : 'word',
                    'category' => $category,
                    'difficulty' => $difficulty,
                    'ipa' => $ipa,
                    'tts_voice' => $ttsVoice,
                    'audio_path' => $audioPath,
                    'metadata' => ['imported' => true],
                ]
            );
            $stored++;
        }

        $dataset->update(['item_count' => $dataset->items()->count()]);
        $this->info("Imported {$stored} items into dataset '{$dataset->name}' (slug: {$slug}).");
        return self::SUCCESS;
    }
}
