<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $maxCreatedDate = $request->maxCreatedDate; // zakładam na sztywno że wartości 'today', '7days', '30days'
        $dateThreshold = null;

        switch ($maxCreatedDate) {
            case 'today':
                $dateThreshold = Carbon::today();
                break;
            case '7days':
                $dateThreshold = Carbon::now()->subDays(7);
                break;
            case '30days':
                $dateThreshold = Carbon::now()->subDays(30);
                break;
            default:
                $dateThreshold = null;
                break;
        }

        // Dla aktywności jak i zwracanej ilości aktywności uwzględniaj filtry
        $categories = Auth::user()
            ->categories()
            ->with(['activities' => function ($query) use ($dateThreshold) {
                if ($dateThreshold) {
                    $query->where('created_date', '>=', $dateThreshold);
                }
            }])
            ->withCount(['activities' => function ($query) use ($dateThreshold) {
                if ($dateThreshold) {
                    $query->where('created_date', '>=', $dateThreshold);
                }
            }])
            ->get();

        $categories->each(function ($category) {
            $totalSeconds = $category->activities->reduce(function ($carry, $activity) {
                $timeParts = explode(':', $activity->spent_time);
                $seconds = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + (isset($timeParts[2]) ? $timeParts[2] : 0);
                return $carry + $seconds;
            }, 0);

            $activityCount = $category->activities->count();

            if ($activityCount > 0) {
                $averageSeconds = $totalSeconds / $activityCount;
                $category->activities_avg_spent_time = gmdate('H:i:s', $averageSeconds);
            } else {
                $category->activities_avg_spent_time = null;
            }
        });

        return Inertia::render('Category', ['categories' => $categories]);
    }


    public function store(Request $request){
        $newCategory = new Category;
        $newCategory->name = $request->name;
        $newCategory->user_id = auth()->id();
        $newCategory->save();

        // Próba zapisu do bazy danych
        if ($newCategory->save()) {
            // Przekierowanie do listy kategorii po pomyślnym zapisaniu - dzięki temu unikamy zduplikowanej logiki z ponownym pobieraniem kategorii, zamiast tego odwołujemy się do funkcji która już to robi
            return redirect()->route('category.index');
        }
    }

    public function show(Category $category, Request $request){

        $maxCreatedDate = $request->maxCreatedDate; // zakładam na sztywno że wartości 'today', '7days', '30days'
        $dateThreshold = null;

        switch ($maxCreatedDate) {
            case 'today':
                $dateThreshold = Carbon::today();
                break;
            case '7days':
                $dateThreshold = Carbon::now()->subDays(7);
                break;
            case '30days':
                $dateThreshold = Carbon::now()->subDays(30);
                break;
            default:
                $dateThreshold = null;
                break;
        }

        $category->load(['activities' => function ($query) use ($dateThreshold) {
                if ($dateThreshold) {
                    $query->where('created_date', '>=', $dateThreshold);
                }
            }]);
        return Inertia::render('Activity', compact('category'));
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return redirect()->back()->with('message', 'Kategoria została usunięta!');
        } else {
            return redirect()->back()->with('error', 'Nie znaleziono kategorii!');
        }
    }

    //krósza wersja usuwania kategorii - nie przekazujemy id kategorii, tylko cały obiekt kategorii(tak, tak można), dlatego nie musimy pisać dodatkowej logiki żeby sprawdzić czy kategoria z danym id istnieje, czy można ją pobrać z bazy itp
    // public function destroy2(Category $category)
    // {
    //     $category->delete();
    // }
}
