<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function store(Request $request, Category $category){

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $validatedData['user_id'] = Auth::id();
        $validatedData['category_id'] = $category->id;
        $validatedData['spent_time'] = $request->spent_time;

        Activity::create($validatedData);

        return redirect()->back();
    }

    public function destroy(Activity $activity){
        $activity->delete();

        return redirect()->back();
    }
}
