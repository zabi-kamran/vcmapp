<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Payment;
use App\Model\PayRecord;
use App\Model\PayClient;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;
use Excel;
use Input;
use Carbon\Carbon;
class PaymentController extends Controller
{
    public function index()
    {
        return view('payment.index');
    }

    public function getData(Request $request){

        if(Auth::user()->isadmin==1)
        {

        $datas = Payment::join('lgas','lgas.id','payments.lga')
          ->join('categories','categories.id','payments.category')
          ->join('wards','wards.id','payments.ward')
          ->join('gsms','gsms.id','payments.gsm_net')
          ->join('states','states.id','payments.state')
          ->select([
            DB::raw('payments.id as id'),
            'fname',
            'lname',
            'gsm_name',
            'gsm_no',
            'state_name',
            'lga_name',
            'ward_name',
            'category_name',
            'total']);
          $datatables = Datatables::of($datas)
          ->addColumn('status', function ($datatables){
           if($datatables->status==1)
               return "<b>Active</b>";

              else if($datatables->status==0)
                  return "<b> In Active </b>";

              else if($datatables->stauts==-1)
                  return "<b>Delisted</b>";
          })
          ->addColumn('action', function ($datatables) {
            return '<a href="'.route('payment.edit',$datatables->id).'" class="btn btn-primary btn-icon btn-rounded btn-xs"><i class="icon-pencil"></i></a>&nbsp&nbsp
            <button class="btn btn-danger btn-icon btn-rounded btn-xs" onclick="deleteit('.$datatables->id.')"><i class="icon-trash"></i></button>';
        })->rawColumns(['status','action']);

        }

        else
        {

            $datas = Payment::join('lgas','lgas.id','payments.lga')
                ->join('categories','categories.id','payments.category')
                ->join('wards','wards.id','payments.ward')
                ->join('gsms','gsms.id','payments.gsm_net')
                ->join('states','states.id','payments.state')
                ->join('state_users','state_users.state_id','=','states.id')
                ->select([
                    DB::raw('payments.id as id'),
                    'fname',
                    'lname',
                    'gsm_name',
                    'gsm_no',
                    'state_name',
                    'lga_name',
                    'ward_name',
                    'category_name',
                    'total']);
            $datatables = Datatables::of($datas)
                ->addColumn('status', function ($datatables){
                    if($datatables->status==1)
                        return "<b>Active</b>";

                    else if($datatables->status==0)
                        return "<b> In Active </b>";

                    else if($datatables->stauts==-1)
                        return "<b>Delisted</b>";
                })
                ->addColumn('action', function ($datatables) {
                    return '<a href="'.route('payment.edit',$datatables->id).'" class="btn btn-primary btn-icon btn-rounded btn-xs"><i class="icon-pencil"></i></a>&nbsp&nbsp
            <button class="btn btn-danger btn-icon btn-rounded btn-xs" onclick="deleteit('.$datatables->id.')"><i class="icon-trash"></i></button>';
                })->rawColumns(['status','action']);

        }

        return $datatables->make(true);
    }

    function exportexcel(){
       $payments = Payment::join('lgas','lgas.id','payments.lga')
          ->join('categories','categories.id','payments.category')
          ->join('wards','wards.id','payments.ward')
          ->join('gsms','gsms.id','payments.gsm_net')
          ->join('states','states.id','payments.state')
          ->select([
            'fname',
            'lname',
            'mother_name',
            'dob',
            'gsm_name',

            'gsm_no',
            'state_name',
            'lga_name',
            'ward_name',
            'category_name',
            'settlement',
            'created_at'
            ])->get();
      
        $paymentsArray = []; 

    // Define the Excel spreadsheet headers
        $paymentsArray[] = ['First Name', 'Last Name', 'Mother Name','DOB','GSM NET ','GSM No','State','LGA','WARD','Category','Settlements','created_at'];

    // Convert each member of the returned collection into an array,
    // and append it to the payments array.
        foreach ($payments as $payment) {
            $paymentsArray[] = $payment->toArray();
        }

    // Generate and return the spreadsheet
        Excel::create('Invoice Report', function($excel) use ($paymentsArray) {

        // Set the spreadsheet title, creator, and description
            $excel->setTitle('Invoice Report');
            $excel->setCreator('Laravel')->setCompany('Payment Excel VCM, LLC');
            $excel->setDescription('Invoice file');

        // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($paymentsArray) {
                $sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });

        })->download('xlsx');
    }
  
    public function create()
    {
        return view('payment.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'state'=>'required',
            'lga'=>'required',
            'ward'=>'required',
            'gsm_net'=>'required',
            'fname'=>'required|alpha',
            'lname'=>'required|alpha',
            'mother_name'=>'required',
            'dob'=>'required',
            'gsm_no'=>'required|digits:13',
            'category'=>'required',
            'gender'=>'required',
            'pay_amt'=>'required',
            'other'=>'required',
            'phone'=>'required|digits:13',
            'name'=>'required|alpha',
//            'relation'=>'required',

        ]);
        
        $data=new Payment();
        $data->state=$request->state;
        $data->lga=$request->lga;
        $data->ward=$request->ward;
        $data->gsm_net=$request->gsm_net;
        $data->fname=$request->fname;
        $data->lname=$request->lname;
        $data->gender=$request->gender;
        $data->mother_name=$request->mother_name;
        $data->dob=date('Y-m-d', strtotime($request->dob));
        $data->gsm_no=$request->gsm_no;
        $data->category=$request->category;
        $data->pay_amt=$request->pay_amt;
        $data->other=$request->other;
        $data->total=$request->pay_amt+$request->other;

        $data->status=$request->status;
        $data->phone = $request->phone;
        $data->comments = $request->comments;
        $data->setellment=0;
        $data->name= $request->name;
        $data->relation= $request->relation;

        // if ($request->hasFile('certificate')) {
        //     $image = $request->file('certificate');
        //     $filename = time().'.'.$image->getClientOriginalExtension();
        //     $destinationPath = public_path('/certificates');
        //     $image->move($destinationPath, $filename);
        //     $data->certificate="certificates/".$filename;
        // }
        $data->save();
        return redirect('payment');
    }

    public function show($id)
    {
       
    }

    public function edit($id)
    {
        $row=Payment::find($id);
        return view('payment.edit',compact('row'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'state'=>'required',
            'lga'=>'required',
            'ward'=>'required',
            'gsm_net'=>'required',
            'fname'=>'required',
            'lname'=>'required',
            'mother_name'=>'required',
            'dob'=>'required',
            'gsm_no'=>'required',
            'category'=>'required',
            'gender'=>'required',
            'pay_amt'=>'required',
            'other'=>'required',
            'phone'=>'required',
            'name'=>'required',
            'comments'=>'required'
        ]);
        $data=Payment::find($id);
        $data->state=$request->state;
        $data->lga=$request->lga;
        $data->ward=$request->ward;
        $data->gsm_net=$request->gsm_net;
        $data->fname=$request->fname;
        $data->lname=$request->lname;
        $data->gender=$request->gender;
        $data->mother_name=$request->mother_name;
        $data->dob=date('Y-m-d', strtotime($request->dob));
        $data->gsm_no=$request->gsm_no;
        $data->category=$request->category;
        $data->pay_amt=$request->pay_amt;
        $data->other=$request->other;
        $data->total=$request->pay_amt+$request->other;


        $data->status=$request->status;
        $data->phone = $request->phone;
        $data->comments = $request->comments;
        $data->setellment=0;
        $data->name= $request->name;
        $data->relation= $request->relation;
        // $data->status=0;
        // if ($request->hasFile('certificate')) {
        //     $image = $request->file('certificate');
        //     $filename = time().'.'.$image->getClientOriginalExtension();
        //     $destinationPath = public_path('/certificates');
        //     $image->move($destinationPath, $filename);
        //     $data->certificate="certificates/".$filename;
        // }
        $data->save();
        return redirect('payment');
    }

    public function payrecord()
    {
        return view('payment.payment');
        $data->other=$request->other;
    }

    public function payrecordsave(Request $request){
         $this->validate($request,[
            'create_name'=>'required|alpha'
        ]);
        $paydata=new PayRecord();
        $paydata->create_name=$request->create_name;
        $paydata->description=$request->description;
        if ($request->hasFile('certificate')) {
            $image = $request->file('certificate');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/certificates');
            $image->move($destinationPath, $filename);
            $paydata->certificate="certificates/".$filename;
        }
        $paydata->save();
        $id=$paydata->id;
        $cust_id=$request->cust_id;
        foreach ($cust_id as $key => $row) {
            $client=new PayClient();
            $client->cust_id=$cust_id[$key];
            $client->pay_id=$id;
            $client->save();
        }
        return redirect('payment');

    }

    public function GetPayList(){
         $lga_id=Input::get('lga_id');
         $ward=Input::get('ward');
         $data=Payment::where('lga',$lga_id)->where('ward',$ward)->get();
         return view('payment.paylist',compact('data'));

    }

    public function destroy($id)
    {
         $data=Payment::find($id);
         $data->delete();
    }

    public function all_payments(Request $request)
    {

        if(Auth::user()->isadmin==1) {

            $data = \Illuminate\Support\Facades\DB::table('pay_records')
                ->select('payments.fname as fname', 'pay_records.create_name as create_name', 'payments.id as id',
                    'payments.lname as lname',
                    'pay_records.certificate as certificate',
                    'pay_records.description as description',
                    'pay_records.created_at as created_at',
                    'states.state_name as state_name',
                    'payments.phone as phone',
                    'payments.ward as ward_id')
                ->join('pay_clients', 'pay_clients.pay_id', '=', 'pay_records.id')
                ->join('payments', 'payments.id', '=', 'pay_clients.cust_id')
                ->join('states', 'states.id', '=', 'payments.state')
                ->get();
        }

        else
        {
            $data = \Illuminate\Support\Facades\DB::table('pay_records')
                ->select('payments.fname as fname', 'pay_records.create_name as create_name', 'payments.id as id',
                    'payments.lname as lname',
                    'pay_records.certificate as certificate',
                    'pay_records.description as description',
                    'pay_records.created_at as created_at',
                    'states.state_name as state_name',
                    'payments.phone as phone',
                    'payments.ward as ward_id')
                ->join('pay_clients', 'pay_clients.pay_id', '=', 'pay_records.id')
                ->join('payments', 'payments.id', '=', 'pay_clients.cust_id')
                ->join('states', 'states.id', '=', 'payments.state')
                ->join('categories','categories.id','=','payments.category')
                ->get();



        }





        return view("payment.all")->with('payments',$data);

    }


}
