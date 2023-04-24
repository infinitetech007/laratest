<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     *  Get Category
     */
    public function getCategory()
    {
        try{
            $category = $this->category::where('status', 1)->get()->toArray();
            if(!empty($category)){
                return $this->sendResponse($category, 'get category successfully');
            }
            return $this->sendError('there is no data', 500);
        }
        catch(Exception $e){
            return $this->sendError('something went wrong', 500);
        }
    }  
    
    /**
     *  Get Product List
     */
    public function getProduct()
    {
        try{
            $getProduct = $this->product::where('status', 1)->get()->toArray();
            if(!empty($getProduct)){
                return $this->sendResponse($getProduct, 'get product successfully');
            }
            return $this->sendError('there is no data', 500);
        }
        catch(Exception $e){
            return $this->sendError('something went wrong', 500);
        }
    }  

    /**
     *  Search Product
     */
    public function searchProduct(Request $request)
    {
        try{
            $id = $request->id;
            $searchProduct = $this->product::where('id', $id)->get()->toArray();
            if(!empty($searchProduct)){
                return $this->sendResponse($searchProduct, 'get product successfully');
            }
            return $this->sendError('there is no data', 500);
        }
        catch(Exception $e){
            return $this->sendError('something went wrong', 500);
        }
    } 
    
}
