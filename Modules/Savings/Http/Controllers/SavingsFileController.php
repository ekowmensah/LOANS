<?php

namespace Modules\Savings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Modules\Savings\Entities\SavingsFile;

class SavingsFileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:savings.savings.files.index'])->only(['index', 'show']);
        $this->middleware(['permission:savings.savings.files.create'])->only(['create', 'store']);
        $this->middleware(['permission:savings.savings.files.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:savings.savings.files.destroy'])->only(['destroy']);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return theme_view('savings::savings_file.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($id)
    {
        return theme_view('savings::savings_file.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'file' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ods,csv'],
        ]);
        $savings_file = new SavingsFile();
        $savings_file->created_by_id = Auth::id();
        $savings_file->savings_id = $id;
        $savings_file->name = $request->name;
        $savings_file->savings_file_type_id = $request->savings_file_type_id;
        $savings_file->status = $request->status;
        $savings_file->description = $request->description;
        if ($request->hasFile('file')) {
            $file_name = $request->file('file')->store('public/uploads/savings');
            $savings_file->link = basename($file_name);
        }
        $savings_file->save();
        activity()->on($savings_file)
            ->withProperties(['id' => $savings_file->id])
            ->log('Create Savings File');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('savings/' . $id . '/show');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $savings_file = SavingsFile::find($id);
        return theme_view('savings::savings_file.show', compact('savings_file'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $savings_file = SavingsFile::find($id);
        return theme_view('savings::savings_file.edit', compact('savings_file'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'file' => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ods,csv'],
        ]);
        $savings_file = SavingsFile::find($id);
        $savings_file->name = $request->name;
        $savings_file->savings_file_type_id = $request->savings_file_type_id;
        $savings_file->status = $request->status;
        $savings_file->description = $request->description;
        if ($request->hasFile('file')) {
            $file_name = $request->file('file')->store('public/uploads/savings');
            if ($savings_file->link) {
                Storage::delete('public/uploads/savings/' . $savings_file->link);
            }
            $savings_file->link = basename($file_name);
        }
        $savings_file->save();
        activity()->on($savings_file)
            ->withProperties(['id' => $savings_file->id])
            ->log('Update Savings File');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('savings/' . $savings_file->savings_id . '/show');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $savings_file = SavingsFile::find($id);
        if ($savings_file->link) {
            Storage::delete('public/uploads/savings/' . $savings_file->link);
        }
        $savings_file->delete();
        activity()->on($savings_file)
            ->withProperties(['id' => $savings_file->id])
            ->log('Delete Savings File');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }
}
