<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DispatcherController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');

        $requests = RepairRequest::query()
            ->with('assignee')
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->latest()
            ->get();

        $masters = User::query()
            ->where('role', User::ROLE_MASTER)
            ->orderBy('name')
            ->get();

        return view('dispatcher.index', [
            'requests' => $requests,
            'masters' => $masters,
            'statusFilter' => $statusFilter,
            'statuses' => [
                RepairRequest::STATUS_NEW,
                RepairRequest::STATUS_ASSIGNED,
                RepairRequest::STATUS_IN_PROGRESS,
                RepairRequest::STATUS_DONE,
                RepairRequest::STATUS_CANCELED,
            ],
        ]);
    }

    public function assign(Request $request, RepairRequest $repairRequest): RedirectResponse
    {
        $validated = $request->validate([
            'master_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $master = User::query()->findOrFail($validated['master_id']);
        if ($master->role !== User::ROLE_MASTER) {
            return back()->withErrors(['master_id' => 'Выбранный пользователь не является мастером.']);
        }

        if (in_array($repairRequest->status, [RepairRequest::STATUS_DONE, RepairRequest::STATUS_CANCELED], true)) {
            return back()->withErrors(['assign' => 'Нельзя назначить мастера для завершенной или отмененной заявки.']);
        }

        $repairRequest->update([
            'assigned_to' => $master->id,
            'status' => RepairRequest::STATUS_ASSIGNED,
        ]);

        return back()->with('success', 'Мастер назначен.');
    }

    public function cancel(RepairRequest $repairRequest): RedirectResponse
    {
        if ($repairRequest->status === RepairRequest::STATUS_DONE) {
            return back()->withErrors(['cancel' => 'Завершенную заявку нельзя отменить.']);
        }

        $repairRequest->update([
            'status' => RepairRequest::STATUS_CANCELED,
        ]);

        return back()->with('success', 'Заявка отменена.');
    }
}
