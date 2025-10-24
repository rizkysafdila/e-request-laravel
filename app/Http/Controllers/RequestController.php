<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Models\ApprovalLog;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $query = RequestModel::withTrashed()
            ->with('creator')
            ->latest();

        if ($user->isRequestor()) {
            $query->where('created_by', $user->id);
        }

        if ($request->query('show') === 'deleted') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $requests = $query->paginate(10)->appends($request->query());

        return view('requests.index', compact('requests'));
    }

    public function create() { return view('requests.create'); }

    public function store(StoreRequestRequest $req) {
        $data = $req->validated();
        $data['created_by'] = Auth::user()->id;

        if ($req->hasFile('attachment')) {
            $data['attachment_path'] = $req->file('attachment')->store('attachments', 'public');
        }

        $model = RequestModel::create($data);
        return redirect()->route('requests.edit', $model)->with('ok','Saved as draft.');
    }

    public function edit(RequestModel $requestModel) {
        return view('requests.edit', ['r' => $requestModel]);
    }

    public function update(StoreRequestRequest $req, RequestModel $requestModel) {
        // $this->authorize('request.manage', $requestModel);
        $data = $req->validated();

        if ($req->hasFile('attachment')) {
            $data['attachment_path'] = $req->file('attachment')->store('attachments', 'public');
        }

        $requestModel->update($data);
        return back()->with('ok','Draft updated.');
    }

    public function submit(Request $req, RequestModel $requestModel) {
        // $this->authorize('request.submit', $requestModel);
        $requestModel->update(['status' => 'submitted']);
        ApprovalLog::create([
            'request_id' => $requestModel->id,
            'action' => 'submitted',
            'approver_id' => Auth::user()->id,
            'note' => $req->input('note'),
        ]);
        return redirect()->route('requests.show', $requestModel)->with('ok','Submitted.');
    }

    public function show(RequestModel $requestModel) {
        $requestModel->load(['creator','logs.approver']);
        return view('requests.show', ['r' => $requestModel]);
    }

    public function destroy(RequestModel $requestModel) {
        // $this->authorize('request.manage', $requestModel);
        $requestModel->delete();
        ApprovalLog::create([
        'request_id' => $requestModel->id,
        'action' => 'deleted',
        'approver_id' => Auth::user()->id,
        'note' => null,
        ]);
        return redirect()->route('requests.index')->with('ok','Moved to trash.');
    }

    // Trash / restore (Admin only)
    public function restore(int $id) {
        // $this->authorize('request.trash.view');
        $r = RequestModel::onlyTrashed()->findOrFail($id);
        $r->restore();
        ApprovalLog::create([
        'request_id' => $r->id,
        'action' => 'restored',
        'approver_id' => Auth::user()->id,
        'note' => null,
        ]);
        return back()->with('ok','Restored.');
    }
}
