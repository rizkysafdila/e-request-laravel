<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLog;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    // public function inbox() {
    //     // $this->authorize('request.approve');
    //     $requests = RequestModel::where('status','submitted')->with('creator')->latest()->paginate(10);
    //     return view('approvals.inbox', compact('requests'));
    // }

    public function approve(Request $req, RequestModel $requestModel) {
        // $this->authorize('request.approve');
        abort_unless($requestModel->status === 'submitted', 422, 'Invalid state');

        $requestModel->update(['status' => 'approved']);
        ApprovalLog::create([
            'request_id' => $requestModel->id,
            'action' => 'approved',
            'approver_id' => Auth::user()->id,
            'note' => $req->input('note'),
        ]);
        return back()->with('ok','Approved.');
    }

    public function reject(Request $req, RequestModel $requestModel) {
        // $this->authorize('request.approve');
        abort_unless($requestModel->status === 'submitted', 422, 'Invalid state');

        $req->validate(['note' => 'required|string|min:3']);
        $requestModel->update(['status' => 'rejected']);
            ApprovalLog::create([
            'request_id' => $requestModel->id,
            'action' => 'rejected',
            'approver_id' => Auth::user()->id,
            'note' => $req->input('note'),
        ]);
        return back()->with('ok','Rejected.');
    }
}
