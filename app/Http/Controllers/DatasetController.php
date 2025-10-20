<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetItem;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::orderBy('name')->get();
        return view('datasets.index', compact('datasets'));
    }

    public function random(Dataset $dataset, Request $request)
    {
        $difficulty = $request->get('difficulty');
        $q = $dataset->items();
        if ($difficulty) $q->where('difficulty', $difficulty);
        $item = $q->inRandomOrder()->first();

        return response()->json([
            'dataset' => $dataset->only(['name','slug','type','level']),
            'item' => $item ? [
                'id' => $item->id,
                'type' => $item->type,
                'text' => $item->text,
                'ipa' => $item->ipa,
                'category' => $item->category,
                'difficulty' => $item->difficulty,
                'audio_url' => $item->audio_path ? asset('storage/'.$item->audio_path) : null,
            ] : null,
        ]);
    }
}
