<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::all()->groupBy('level');
        return view('exercises.index', compact('exercises'));
    }

    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercises.show', compact('exercise'));
    }

    public function select()
    {
        $levels = ['easy', 'medium', 'hard', 'insane'];
        $categories = ['word', 'sentence'];
        return view('exercises.select', compact('levels', 'categories'));
    }

    public function practice($level, $category)
    {
        $exercise = Exercise::where('level', $level)
            ->where('category', $category)
            ->inRandomOrder()
            ->first();

        if (!$exercise) {
            return redirect()->route('exercises.select')->with('error', 'No exercise found for this combination.');
        }

        return view('exercises.practice', compact('exercise'));
    }
}
