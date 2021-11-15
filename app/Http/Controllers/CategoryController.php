<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Show all categories
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Category::all();
        return response()->json([
            'ok'=> true,
            'categories'=> $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required']);

        $newCategory = Category::create(['title'=> $request['title']]);

        return response()->json(['ok'=> true, 'category'=>$newCategory, 'message'=> 'Category created successfully']);
        
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId)
    {
        $category = Category::find($categoryId);

        if($category == null) {
            return response()->json(['ok'=> false, "message"=> "Can't find this category."]);
        }


        return response()->json(['ok'=> true, 'category'=> $category]);
    }


    /**
     * Update category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $categoryId)
    {
        $request->validate(['title'=> 'required']);

       $category = Category::findOrFail($categoryId);

       $category->title = $request['title'];

       $category->save();

       return response()->json(['ok'=> true, 'category'=> $category, 'message'=> 'Category updated successfully']);

    }


    /**
     * Remove category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId)
    {
        Category::findOrFail($categoryId)->delete();

        return response()->json([
            'ok'=> true,
            'message'=> 'Category deleted successfully',
        ]);
    }

}