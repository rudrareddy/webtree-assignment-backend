<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\Expense;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use DB;
class ExpenseController extends Controller
{
    use HttpResponses;
    protected Expense $expense;

    public function __construct(Expense $expense){
       $this->expense = $expense;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses['lists'] = $this->expense->getAllExpenses(Auth::user()->id);
        $expenses['charts']= $this->expense->select('category_id')->selectRaw("SUM(amount) as total")->with('category:id,category_name')->groupBy('category_id')->where('user_id',Auth::user()->id)->get();
       //return $this->expense->select(DB::raw('sum(amount) as `total`'),'categories.category_name')->groupBy('category_id')->get();
       return $this->success(['expenses' => $expenses], 'Expenses lists  get successfully...!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
      $data = $request->all();
      $data['user_id']=Auth::user()->id;
      $create = $this->expense->create($data);
      if($create){
         return $this->success(['expense' => $create], 'New Expense Successfully created...!');
      }else{
         return $this->error(['error' => "Something went wrong"], 'Something went wrong...!');
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      try{
        $expense= $this->expense->singleExpense($id);
        return $this->success(['expense' => $expense], 'Expense Detail get Successfully...!');
      }catch(ModelNotFoundException $e){
         return $this->error([], 'Expense not found for this ID...!');
      }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $data = $request->all();
      $data['user_id']=Auth::user()->id;
      $update = $this->expense->where('id',$id)->update($data);
      if($update){
         return $this->success([], 'Category Successfully updated...!');
      }else{
         return $this->error(['error' => "Something went wrong"], 'Something went wrong...!');
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      try{
         $this->expense->singleExpense($id);
        $this->expense->where('id',$id)->delete();
        return $this->success([], 'Expense Deleted Successfully...!');
      }catch(ModelNotFoundException $e){
         return $this->error([], 'Expense not found for this ID...!');
      }
    }

    public function filter_category($id){
        $expenses = $this->expense->filterByCategory(Auth::user()->id,$id);
        if($expenses){
          return $this->success(['expenses'=>$expenses], 'Data fecthed Successfully ...!');
        }else{
          return $this->error(['error' => "Something went wrong"], 'Something went wrong...!');
        }
    }

    public function filter_date_range(Request $request){
      //return $request->start_date;
      $expenses = $this->expense->filterByDateRange(Auth::user()->id,$request->all());
      if($expenses){
        return $this->success(['expenses'=>$expenses], 'Data fecthed Successfully ...!');
      }else{
        return $this->error(['error' => "Something went wrong"], 'Something went wrong...!');
      }
    }
}
