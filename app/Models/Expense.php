<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','category_id','amount','description','expense_date'];


    public function user(){
       return $this->belongsTo(User::class,'user_id');
    }

    public function category(){
       return $this->belongsTo(Category::class,'category_id');
    }

    public function getAllExpenses($user_id){
        return $this->where('user_id',$user_id)->with(['category:id,category_name'])->latest()->get();
    }
    //get single row by id
    public function singleExpense($id){
       return $this->where('id',$id)->firstOrFail();
    }
    //filters by categories
    public function filterByCategory($user_id,$id){
        if($id =="all"){
          return $this->where('user_id',$user_id)->with(['category:id,category_name'])->latest()->get();
        }else{
          return $this->where('user_id',$user_id)->where('category_id',$id)->with(['category:id,category_name'])->latest()->get();
        }

    }
    //filters by date range
    public function filterByDateRange($user_id,$data){
      return $this->where('user_id',$user_id)->whereBetween('expense_date',[$data['start_date'],$data['end_date']])->with(['category:id,category_name'])->latest()->get();
    }
}
