<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicRequestController extends Controller
{
    public function create(): View
    {
        return view('requests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'problem_text' => ['required', 'string', 'max:2000'],
        ]);

        RepairRequest::query()->create([
            ...$validated,
            'status' => RepairRequest::STATUS_NEW,
        ]);

        return redirect()->route('requests.create')->with('success', 'Заявка успешно создана.');
    }
}
