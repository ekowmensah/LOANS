<?php

namespace Modules\Loan\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laracasts\Flash\Flash;
use Modules\Loan\Entities\LoanFile;
use Modules\Loan\Entities\LoanFileType;

class LoanFileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:loan.loans.files.index'])->only(['index', 'show']);
        $this->middleware(['permission:loan.loans.files.create'])->only(['create', 'store']);
        $this->middleware(['permission:loan.loans.files.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:loan.loans.files.destroy'])->only(['destroy']);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return theme_view('loan::loan_file.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($id)
    {
        $loan_file_types = LoanFileType::get();
        return theme_view('loan::loan_file.create', compact('id', 'loan_file_types'));
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
        $loan_file = new LoanFile();
        $loan_file->created_by_id = Auth::id();
        $loan_file->loan_id = $id;
        $loan_file->name = $request->name;
        $loan_file->loan_file_type_id = $request->loan_file_type_id;
        $loan_file->status = $request->status;
        $loan_file->description = $request->description;
        if ($request->hasFile('file')) {
            $file_name = $request->file('file')->store('public/uploads/loans');
            $loan_file->link = basename($file_name);
        }
        $loan_file->save();
        activity()->on($loan_file)
            ->withProperties(['id' => $loan_file->id])
            ->log('Create Loan File');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('loan/' . $id . '/show');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $loan_file = LoanFile::find($id);
        return theme_view('loan::loan_file.show', compact('loan_file'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $loan_file = LoanFile::find($id);
        $loan_file_types = LoanFileType::get();
        return theme_view('loan::loan_file.edit', compact('loan_file', 'loan_file_types'));
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
        $loan_file = LoanFile::find($id);
        $loan_file->name = $request->name;
        $loan_file->loan_file_type_id = $request->loan_file_type_id;
        $loan_file->status = $request->status;
        $loan_file->description = $request->description;
        if ($request->hasFile('file')) {
            $file_name = $request->file('file')->store('public/uploads/loans');
            if ($loan_file->link) {
                Storage::delete('public/uploads/loans/' . $loan_file->link);
            }
            $loan_file->link = basename($file_name);
        }
        $loan_file->save();
        activity()->on($loan_file)
            ->withProperties(['id' => $loan_file->id])
            ->log('Update Loan File');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('loan/' . $loan_file->loan_id . '/show');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $loan_file = LoanFile::find($id);
        if ($loan_file->link) {
            Storage::delete('public/uploads/loans/' . $loan_file->link);
        }
        $loan_file->delete();
        activity()->on($loan_file)
            ->withProperties(['id' => $loan_file->id])
            ->log('Delete Loan File');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }
}
