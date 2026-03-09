<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MasterController extends Controller
{
    public function index(Request $request): View
    {
        $requests = RepairRequest::query()
            ->where('assigned_to', $request->user()->id)
            ->orderByDesc('updated_at')
            ->get();

        return view('master.index', [
            'requests' => $requests,
        ]);
    }

    public function take(Request $request, RepairRequest $repairRequest): RedirectResponse|JsonResponse
    {
        if ((int) $repairRequest->assigned_to !== (int) $request->user()->id) {
            abort(403, 'Заявка назначена другому мастеру.');
        }

        $updated = DB::table('repair_requests')
            ->where('id', $repairRequest->id)
            ->where('assigned_to', $request->user()->id)
            ->where('status', RepairRequest::STATUS_ASSIGNED)
            ->update([
                'status' => RepairRequest::STATUS_IN_PROGRESS,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            $message = 'Заявка уже взята в работу или недоступна.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 409);
            }

            return back()->withErrors(['take' => $message]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Заявка взята в работу.']);
        }

        return back()->with('success', 'Заявка взята в работу.');
    }

    public function complete(Request $request, RepairRequest $repairRequest): RedirectResponse|JsonResponse
    {
        if ((int) $repairRequest->assigned_to !== (int) $request->user()->id) {
            abort(403, 'Заявка назначена другому мастеру.');
        }

        $updated = DB::table('repair_requests')
            ->where('id', $repairRequest->id)
            ->where('assigned_to', $request->user()->id)
            ->where('status', RepairRequest::STATUS_IN_PROGRESS)
            ->update([
                'status' => RepairRequest::STATUS_DONE,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            $message = 'Можно завершить только заявку со статусом in_progress.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 409);
            }

            return back()->withErrors(['complete' => $message]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Заявка завершена.']);
        }

        return back()->with('success', 'Заявка завершена.');
    }
}
