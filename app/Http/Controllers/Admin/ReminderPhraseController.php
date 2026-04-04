<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReminderPhraseController extends Controller
{
    public function index()
    {
        $phrases = \App\Models\ReminderPhrase::all();
        return view('admin.reminder_phrases.index', compact('phrases'));
    }

    public function create()
    {
        $events = collect(\App\Enums\NotificationEvent::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->availableVariables()];
        });

        return view('admin.reminder_phrases.form', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_key' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\NotificationEvent::class)],
            'text_en' => 'required|string',
            'text_ar' => 'required|string',
        ]);

        \App\Models\ReminderPhrase::create($validated);
        return redirect()->route('admin.reminder-phrases.index')->with('success', 'Phrase saved successfully.');
    }
}
