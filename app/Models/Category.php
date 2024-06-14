<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name','status'];


    public function getActiveCategories(){
        return $this->latest()->get();
    }

    public function singleCategory($id){
        return $this->where('id',$id)->firstOrFail();
    }

    public function expenses(){
      return $this->hasMany(Expense::class,'category_id');
    }
}
