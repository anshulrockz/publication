<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\CustomerDetail;
use App\ClaimCategory;

class ReportController extends Controller
{
    public function deposit()
    {
        try{
        	$deposit = Report::all_deposits();
            return view('report.deposit')->with('report', $deposit);
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }
	
	public function expense()
    {
        try{
            $expense = Report::all_expenses(); 
            return view('report.expense')->with('report', $expense);
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }

	public function asset()
    {
        $asset = Report::all_assets();
        $assetnew = Report::all_asset_news();
        return view('report.asset')->with(array('report' => $asset, 'reportNew' => $assetnew ));
    }
    
    public function expiry()
    {
        try{
            $expiry = Report::all_assets_expiry();
            $expiry2 = Report::all_asset_news_expiry();
            return view('report.asset-expiry')->with(array('report' => $expiry, 'report2' => $expiry2 ));
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }
    
	public function received_payment()
    {
        $transaction = Report::all_received_payment();
        return view('report.received-payment')->with('report', $transaction);
    }

    public function claim()
    {
        $customer_detail = Report::all_claims();
        $doc_verification = ClaimCategory::where('type_of_category','3')->get();
        $kyc_verification = ClaimCategory::where('type_of_category','4')->get();   
        return view('report.claim')->with(array('doc_verification' => $doc_verification,
            'kyc_verification' => $kyc_verification, 'report'=> $customer_detail ));
    }

    public function tds()
    {
        $vendor_tds = Report::all_expenses_tds();
        return view('report.vendor-tds')->with(array('report'=> $vendor_tds ));
    }

	public function overall()
    {
        try{
            $tests = Report::all();
            return view('report.overall')->with('report', $expense);
        }
        catch(\Exception $e){
            $error = $e->getMessage();
            return back()->with('error', 'Something went wrong! Please contact admin');
        }
    }
    
}
