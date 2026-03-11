<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Services\RepairRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MasterController extends Controller
{
    public function __construct(
        private RepairRequestService $requestService
    ) {}

    public function index(): View
    {
        $requests = RepairRequest::where('assigned_to', Auth::id())
            ->orderByRaw("CASE status WHEN 'assigned' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'done' THEN 3 ELSE 4 END")
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('master.index', compact('requests'));
    }

    public function takeInWork(int $id): RedirectResponse
    {
        $result = $this->requestService->takeInWork($id, Auth::user());

        if ($result === false) {
            return back()->withErrors(['take' => 'Заявку нельзя взять в работу (уже взята или недоступна).']);
        }

        return back()->with('success', 'Заявка взята в работу.');
    }

    public function complete(int $id): RedirectResponse
    {
        $request = RepairRequest::where('id', $id)->where('assigned_to', Auth::id())->firstOrFail();

        if ($request->status !== RepairRequest::STATUS_IN_PROGRESS) {
            return back()->withErrors(['complete' => 'Завершить можно только заявку в работе.']);
        }

        $request->update(['status' => RepairRequest::STATUS_DONE]);

        return back()->with('success', 'Заявка завершена.');
    }
}
