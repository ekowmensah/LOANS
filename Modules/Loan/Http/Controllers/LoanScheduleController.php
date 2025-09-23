<?php

namespace Modules\Loan\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Laracasts\Flash\Flash;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanCreditCheck;
use Modules\Loan\Events\TransactionUpdated;

class LoanScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
        $this->middleware(['permission:loan.loans.index'])->only(['index', 'show']);
        $this->middleware(['permission:loan.loans.schedules.edit'])->only(['edit', 'update']);
        $this->middleware(['permission:loan.loans.credit_checks.destroy'])->only(['destroy']);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = LoanCreditCheck::all();
        return theme_view('loan::credit_check.index', compact('data'));
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $loan_credit_check = LoanCreditCheck::find($id);
        return theme_view('loan::credit_check.show', compact('loan_credit_check'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $loan = Loan::with('repayment_schedules')->find($id);
        return theme_view('loan::loan_schedule.edit', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::with('repayment_schedules')->find($id);
        $loan->repayment_schedules->each(function ($schedule) use ($request) {
            $schedule->due_date = $request->due_date[$schedule->id][0];
            $schedule->principal = $request->principal[$schedule->id][0];
            $schedule->interest = $request->interest[$schedule->id][0];
            $schedule->fees = $request->fees[$schedule->id][0];
            $schedule->penalties = $request->penalties[$schedule->id][0];
            $schedule->save();
        });

        event(new TransactionUpdated($loan));
        activity()->on($loan)
            ->withProperties(['id' => $loan->id])
            ->log('Update Loan Schedule');
        \flash(trans_choice("core::general.successfully_saved", 1))->success()->important();
        return redirect('loan/' . $loan->id . '/show');
    }

    public function email_schedule($id)
    {
        $loan = Loan::with('repayment_schedules')->find($id);
        //return theme_view('loan::loan_schedule.email', compact('loan'));
    }

    public function pdf($id)
    {
        $loan = Loan::with('repayment_schedules')->find($id);
        $pdf = PDF::loadView(theme_view_file('loan::loan_schedule.pdf'), compact('loan'))->setPaper('a4', 'landscape');
        return $pdf->download(trans_choice('loan::general.repayment', 1) . ' ' . trans_choice('loan::general.schedule', 1) . ".pdf");
    }

    public function print_schedule($id)
    {
        $loan = Loan::with('repayment_schedules')->find($id);
        return theme_view('loan::loan_schedule.print', compact('loan'));
    }
}
