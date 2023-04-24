<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use View;
use Auth;

class ProductController extends Controller
{
    /**
     * Display Product
     */
    public function index(Request $request)
    {
        // try{
            if($request->ajax()){
                $result = $this->product;
                if(!empty($request->search))
                {
                    $result = $result->where('title','like','%'.$request->search.'%')
                                     ->orWhere('slug','like','%'.$request->search.'%');
                }
                $result = $result->paginate(20);
                $data = View::make('product.data', compact('result'))->render();
    
                return response()->json(['data' => $data]);
            }
            $category = $this->category::get();
            return view('product.index', compact('category'));
        // }
        // catch(Exception $e){
        //     abort(500);
        // }
    }

    /**
     *  Add & Update Product
     */
    public function addProduct(Request $request)
    {
        // try{
            $message = "";  
            $addProduct = $this->product;
            $productId = $request->productid;
            $message = "product added successfully";
            if($productId != null){
                $addProduct = $this->product::find($productId);
                $message = "product updated successfully";
            }

            // Store and Update Featured Image
            if($request->hasfile('featured_image')){
                // Update Featured Image 
                if(!empty($addProduct) && !empty($productId) && !empty($addProduct->featured_image)){
                    if(file_exists('public/'.$addProduct->featured_image)){
                        unlink('public/'.$addProduct->featured_image);
                    }
                }
                $file = $request->file('featured_image');
                $imageName = time().rand(111, 999);
                $imageExt = $file->getClientOriginalExtension();
                $featuredImage = $imageName.'.'.$imageExt;
                $file->move(public_path('featured_image/'), $featuredImage);

                $addProduct->featured_image = 'featured_image/'.$featuredImage;
            }


            // Store and Update Gallery Image
            if($request->hasfile('gallery')){
                if(!empty($addProduct) && !empty($productId) && !empty($addProduct->gallery)){
                    $gallery = explode(',', $addProduct->gallery);
                    foreach($gallery as $img){
                        if(file_exists('public/'.$img)){
                            unlink('public/'.$img);
                        }
                    }
                }

                $m_files = [];
                foreach($request->gallery as $file){
                    $gallery = time().rand(111, 999).'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('gallery/'), $gallery);  
                    $m_files[] = 'gallery/'.$gallery;  
                }
                $addProduct->gallery = implode(',', $m_files);
            }

            $addProduct->category = implode(',',$request->category);
            $addProduct->title = $request->title;
            $addProduct->slug = str_slug($request->title);
            $addProduct->description = $request->description;
            $addProduct->status = $request->status;
            $addProduct->save();
            
            return redirect()->back()->with('message', $message);
        // }
        // catch(Exception $e){
        //     abort(500);
        // }
    }

    /**
     *  Delete Product
     */
    public function deleteProduct($id)
    {
        try{
            $id = decrypt($id);
            $product = $this->product::find($id);
            if(file_exists('public/'.$product->featured_image)){
                unlink('public/'.$product->featured_image);
            }
            $gallery = explode(',', $product->gallery);
            foreach($gallery as $img){
                if(file_exists('public/'.$img)){
                    unlink('public/'.$img);
                }
            }

            $product->delete();
            return [
                'status' => 200
            ];
        }
        catch(Exception $e){
            abort(500);
        }
    }

    /**
     *  Edit Product
     */
    public function editProduct($id)
    {
        try{
            $id = decrypt($id);
            $data = $this->product::where('id', $id)->first();
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
