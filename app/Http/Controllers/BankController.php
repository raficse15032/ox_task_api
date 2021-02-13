<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BankAccount;
use App\FinancialOrganization;

class BankController extends Controller
{


    public function organization()
    {
        return FinancialOrganization::all();
    }

    public function index()
    {
        $accounts = BankAccount::with('bank')->orderBy('id','DESC')->paginate(5);
        return response()->json(['accounts'=>$accounts],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'financial_organization_id' => 'required',
            'store_id'                  => 'required',
            'account_name'              => 'required',
            'account_no'                => 'required',
            'branch'                    => 'required',
            'account_type'              => 'required',
            'swift_code'                => 'required',
            'route_no'                  => 'required'
        ]);

        try{
            $account = BankAccount::create($request->all());
            $bank = FinancialOrganization::find($request->financial_organization_id);
            $account->bank = $bank;
            return response()->json($account,201);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Some thing error in server'] , 401);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'financial_organization_id' => 'required',
            'store_id'                  => 'required',
            'account_name'              => 'required',
            'account_no'                => 'required',
            'branch'                    => 'required',
            'account_type'              => 'required',
            'swift_code'                => 'required',
            'route_no'                  => 'required'
        ]);
        
        try{
            $account = BankAccount::find($request->id)->update($request->except(['id','bank']));
            $account = BankAccount::with('bank')->find($request->id);
            return response()->json($account,200);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Some thing error in server'] , 401);
        }
    }

    public function destroy($id)
    {
        try{
            $account = BankAccount::find($id);

            if($account->delete()){
                return response()->json(['product'=>'deleted successfully'],200);
            }
            else{
               return response()->json(['errors'=>'something went wrong'],204); 
            }
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Some thing error in server'] , 401);
        }

    }

}
