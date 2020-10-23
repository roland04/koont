<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = Category::all();
        return Inertia::render('Categories', ['data' => $data]);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
        ])->validate();
  
        Category::create($request->all());
  
        return redirect()->back()
                    ->with('message', 'Categoría creada.');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
        ])->validate();
  
        if ($request->has('id')) {
            Category::find($request->input('id'))->update($request->all());
            return redirect()->back()
                    ->with('message', 'Categoría actualizada.');
        }
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Category::find($request->input('id'))->delete();
            return redirect()->back()
                    ->with('message', 'Categoría borrada.');
        }
    }
}
