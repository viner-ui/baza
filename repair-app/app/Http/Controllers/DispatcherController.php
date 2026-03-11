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
        $query = RepairRequest::with('assignedUser')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->withQueryString();
        $masters = User::where('role', User::ROLE_MASTER)->orderBy('name')->get();

        return view('dispatcher.index', compact('requests', 'masters'));
    }

    public function assign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:repair_requests,id',
            'master_id' => 'required|exists:users,id',
        ]);

        $repairRequest = RepairRequest::findOrFail($validated['request_id']);
        $master = User::findOrFail($validated['master_id']);

        if ($master->role !== User::ROLE_MASTER) {
            return back()->withErrors(['master_id' => 'Выберите мастера.']);
        }

        if ($repairRequest->status !== RepairRequest::STATUS_NEW) {
            return back()->withErrors(['request_id' => 'Назначить можно только заявку со статусом «Новая».']);
        }

        $repairRequest->update([
            'status' => RepairRequest::STATUS_ASSIGNED,
            'assigned_to' => $master->id,
        ]);

        return back()->with('success', 'Мастер назначен.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $repairRequest = RepairRequest::findOrFail($id);

        if (!in_array($repairRequest->status, [RepairRequest::STATUS_NEW, RepairRequest::STATUS_ASSIGNED], true)) {
            return back()->withErrors(['cancel' => 'Отменить можно только новую или назначенную заявку.']);
        }

        $repairRequest->update(['status' => RepairRequest::STATUS_CANCELED]);

        return back()->with('success', 'Заявка отменена.');
    }
}
