<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CategoryController extends Controller
{
    use HttpResponses;

    protected Category $category;

    public function __construct(Category $category){
       $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->category->getActiveCategories();
        return $this->success(['categories' => $categories], 'Categories lists  get successfully...!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
         $create = $this->category->create($request->all());
         if($create){
            return $this->success(['category' => $create], 'Category Successfully created...!');
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
        $category= $this->category->singleCategory($id);
        return $this->success(['category' => $category], 'Category get Successfully...!');
      }catch(ModelNotFoundException $e){
         return $this->error([], 'Category not found for this ID...!');
      }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
      $update = $this->category->where('id',$id)->update($request->all());
      $category = $this->category->singleCategory($id);
      if($update){
         return $this->success(['category' => $category], 'Category Successfully updated...!');
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
        $category= $this->category->singleCategory($id);
        $this->category->where('id',$id)->delete();
        return $this->success([], 'Category Deleted Successfully...!');
      }catch(ModelNotFoundException $e){
         return $this->error([], 'Category not found for this ID...!');
      }
    }
}
