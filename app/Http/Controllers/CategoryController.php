<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use View;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display Category
     */
    public function index(Request $request)
    {
        try{
            if($request->ajax()){
                $result = $this->category;
                if(!empty($request->search))
                {
                    $result = $result->where('title','like','%'.$request->search.'%')
                                     ->orWhere('slug','like','%'.$request->search.'%');
                }
                $result = $result->paginate(20);
                $data = View::make('category.data', compact('result'))->render();
    
                return response()->json(['data' => $data]);
            }
            return view('category.index');
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Add & Update Category
     */
    public function addCategory(Request $request)
    {
        try{
            $message = "";  
            $addCategory = $this->category;
            $categoryId = $request->categoryid;
            $message = "category added successfully";
            if($categoryId != null){
                $addCategory = $this->category::find($categoryId);
                $message = "category updated successfully";
            }

            $addCategory->title = $request->title;
            $addCategory->slug = str_slug($request->title);
            $addCategory->status = $request->status;
            $addCategory->save();
            
            return redirect()->back()->with('message', $message);
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Delete Category
     */
    public function deleteCategory($id)
    {
        try{
            $id = decrypt($id);
            $category = $this->category::find($id)->delete();
            return [
                'status' => 200
            ];
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Edit Category
     */
    public function editCategory($id)
    {
        try{
            $id = decrypt($id);
            $data = $this->category::where('id', $id)->first();
            return [
                'status' => 'true',
                'data' => $data,     
            ];
        }
        catch(Exception $e){
            abort(500);
        }
    }   
}
