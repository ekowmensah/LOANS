<?php

namespace Modules\Loan\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Modules\Loan\Entities\LoanProvisioning;
use Yajra\DataTables\Facades\DataTables;

class LoanProvisioningController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:loan.loans.loan_provisioning.index'])->only(['index', 'show']);
        $this->middleware(['permission:loan.loans.loan_provisioning.create'])->only(['create', 'store']);
        $this->middleware(['permission:loan.loans.loan_provisioning.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:loan.loans.loan_provisioning.destroy'])->only(['destroy']);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?: 20;
        $orderBy = $request->order_by;
        $orderByDir = $request->order_by_dir;
        $search = $request->s;
        $data = LoanProvisioning::when($orderBy, function (Builder $query) use ($orderBy, $orderByDir) {
            $query->orderBy($orderBy, $orderByDir);
        })
            ->when($search, function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate($perPage)
            ->appends($request->input());
        return theme_view('loan::loan_provisioning.index',compact('data'));
    }

    public function get_loan_provisionings(Request $request)
    {
        $query = LoanProvisioning::query();
        return DataTables::of($query)->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-navicon"></i></button> <ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Auth::user()->hasPermissionTo('loan.loans.loan_provisionings.edit')) {
                $action .= '<li><a href="' . url('loan/loan_provisioning/' . $data->id . '/edit') . '" class="">' . trans_choice('core::general.edit', 2) . '</a></li>';
            }
            if (Auth::user()->hasPermissionTo('loan.loans.loan_provisionings.destroy')) {
                $action .= '<li><a href="' . url('loan/loan_provisioning/' . $data->id . '/destroy') . '" class="confirm">' . trans_choice('core::general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></li></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('loan/loan_provisioning/' . $data->id . '/show') . '">' . $data->id . '</a>';

        })->rawColumns(['id', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return theme_view('loan::loan_provisioning.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);
        $provisioning = new LoanProvisioning();
        $provisioning->name = $request->name;
        $provisioning->lower_limit = $request->lower_limit;
        $provisioning->upper_limit = $request->upper_limit;
        $provisioning->percentage = $request->percentage;
        $provisioning->description = $request->description;
        $provisioning->save();
        activity()->on($provisioning)
            ->withProperties(['id' => $provisioning->id])
            ->log('Create Loan Provisioning');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('loan/loan_provisioning');
    }

    /**
     * Show the specified resource.
     * @param LoanProvisioning $provisioning
     * @return Response
     */
    public function show(LoanProvisioning $provisioning)
    {

        return theme_view('loan::loan_provisioning.show', compact('provisioning'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param LoanProvisioning $provisioning
     * @return Response
     */
    public function edit(LoanProvisioning $provisioning)
    {

        return theme_view('loan::loan_provisioning.edit', compact('provisioning'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param LoanProvisioning $provisioning
     * @return Response
     */
    public function update(Request $request, LoanProvisioning $provisioning)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        $provisioning->name = $request->name;
        $provisioning->lower_limit = $request->lower_limit;
        $provisioning->upper_limit = $request->upper_limit;
        $provisioning->percentage = $request->percentage;
        $provisioning->description = $request->description;
        $provisioning->save();
        activity()->on($provisioning)
            ->withProperties(['id' => $provisioning->id])
            ->log('Update Loan Provisioning');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('loan/loan_provisioning');
    }

    /**
     * Remove the specified resource from storage.
     * @param LoanProvisioning $provisioning
     * @return Response
     */
    public function destroy(LoanProvisioning $provisioning)
    {
        $provisioning->delete();
        activity()->on($provisioning)
            ->withProperties(['id' => $provisioning->id])
            ->log('Delete Loan Provisioning');
        \flash(trans_choice("core::general.successfully_deleted", 1))->success()->important();
        return redirect()->back();
    }
}
