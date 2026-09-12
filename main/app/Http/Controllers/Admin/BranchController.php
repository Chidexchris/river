<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Branch::class);

        $pageTitle = 'Branches';
        $branches  = Branch::searchable(['name'])->orderBy('name')->paginate(getPaginate());

        return view('admin.branch.index', compact('pageTitle', 'branches'));
    }

    public function create()
    {
        $this->authorize('create', Branch::class);

        $pageTitle = 'Create Branch';

        return view('admin.branch.create', compact('pageTitle'));
    }

    public function store(BranchRequest $request)
    {
        Branch::create($request->validated());

        $toast[] = ['success', 'New branch has been successfully created'];

        return to_route('admin.branches.index')->with('toasts', $toast);
    }

    public function edit(Branch $branch)
    {
        $this->authorize('edit', $branch);

        $pageTitle = 'Edit Branch';

        return view('admin.branch.edit', compact('pageTitle', 'branch'));
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        $toast[] = ['success', 'Branch has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStatus', Branch::class);

        return Branch::changeStatus($id);
    }
}
