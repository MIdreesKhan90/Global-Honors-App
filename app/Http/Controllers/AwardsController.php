<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\BusinessFunction;
use App\Models\Category;
use App\Models\CustomerList;
use App\Models\Industry;
use App\Models\SearchList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class AwardsController extends Controller
{
    public function index(Request $request)
    {
//        dd($request)

        $industries = Cache::remember('industries', 60 * 60 * 24 * 365, function () {
            return Industry::orderBy('name')->select('id', 'name')->get();
        });

        $categories = Cache::remember('categories', 60 * 60 * 24 * 365, function () {
            return Category::orderBy('name')->select('id', 'name')->get();
        });

        $locations = Cache::remember('locations', 60 * 60 * 24 * 365, function () {
            return Award::select('id', 'location')
                ->whereNotNull('location')
                ->groupBy('location')
                ->orderBy('location')
                ->get();
        });

        $businessFunctions = Cache::remember('businessFunctions', 60 * 60 * 24 * 365, function () {
            return BusinessFunction::orderBy('name')->select('id', 'name')->get();
        });

        $awards = Cache::remember('awards_all', 60, function () {
            return Award::with('industries', 'categories', 'businessfunctions', 'sponsors','awardKeywords')
                ->selectRaw('*, DATE_FORMAT(deadline_date, "%c/%e/%y") as deadline_date')
                ->get();
        });

        $is_trial_user = (Auth::user()->user_type === 0) ? 1 : 0;
        if($is_trial_user){
            $awards = $awards->filter(function ($award) use ($is_trial_user) {
                return $award->trial_mode == $is_trial_user;
            });
        }

        $search = request('keywords');
        $industry = request('industry');
        $location = request('location');
        $category = request('category');
        $businessFunction = request('businessFunction');
        $sortOrder = request('sortOrder');

        if (!$search) {
            $search = '[]';
        }
        if (!$industry) {
            $industry = '[]';
        }
        if (!$location) {
            $location = '[]';
        }
        if (!$category) {
            $category = '[]';
        }
        if (!$businessFunction) {
            $businessFunction = '[]';
        }

        $arrayOfIndustriesObjects = json_decode($industry);
        $arrayOfKeywordsObjects = json_decode($search);
        $arrayOfLocationsObjects = json_decode($location);
        $arrayOfcategoriesObjects = json_decode($category);
        $arrayOfBusinessFunctionObjects = json_decode($businessFunction);


        switch ($sortOrder) {
            case 'name-asc':
                $awards_result = $awards->sortBy('name');
                break;
            case 'name-desc':
                $awards_result = $awards->sortByDesc('name');
                break;
            case 'deadline-asc':
                $awards_result = $awards->sortBy(function ($award) {
                    if ($award->deadline_date) {
                        $dateFormated = Carbon::createFromFormat('n/j/y', $award->deadline_date);
                    } else {
                        $dateFormated = Carbon::createFromFormat('n/j/y', '1/1/70');
                    }
                    return $dateFormated->timestamp;
                });
                break;
            case 'deadline-desc':
                $awards_result = $awards->sortByDesc(function ($award) {
                    if ($award->deadline_date) {
                        $dateFormated = Carbon::createFromFormat('n/j/y', $award->deadline_date);
                    } else {
                        $dateFormated = Carbon::createFromFormat('n/j/y', '1/1/70');
                    }
                    return $dateFormated->timestamp;
                });
                break;
            default:
                $awards_result = $awards;
                break;
        }

        if ($arrayOfKeywordsObjects || $arrayOfLocationsObjects || $arrayOfIndustriesObjects || $arrayOfcategoriesObjects || $arrayOfBusinessFunctionObjects) {

            $awards_result = $awards->filter(function ($award) use ($arrayOfKeywordsObjects, $arrayOfLocationsObjects, $arrayOfIndustriesObjects, $arrayOfcategoriesObjects, $arrayOfBusinessFunctionObjects) {
                $nameMatch = true;
                $keywordsMatch = true;
                $descriptionMatch = true;
                $regionMatch = true;
                $locationMatch = true;
                $industriesMatch = true;
                $categoriesMatch = true;
                $businessFunctionMatch = true;

//                if ($search) {
//                    $nameMatch = stripos($award->name, $search) !== false;
//                    if ($award->awardKeywords()->where('keyword', $search )->exists()) {
//                        $keywordsMatch = true;
//                    } else {
//                        $keywordsMatch = false;
//                    }
//                    $descriptionMatch = stripos($award->description, $search) !== false;
//                    $regionMatch = stripos($award->region, $search) !== false;
//                }

                foreach ($arrayOfKeywordsObjects as $searchObject) {
                    $nameMatch = stripos($award->name, $searchObject) !== false;
                    $descriptionMatch = stripos($award->description, $searchObject) !== false;
                    $regionMatch = stripos($award->region, $searchObject) !== false;
                    if ($award->awardKeywords()->where('keyword', $searchObject )->exists()) {
                        $keywordsMatch = true;
                        break;
                    } else {
                        $keywordsMatch = false;
                    }
                }

                foreach ($arrayOfLocationsObjects as $locationObject) {
                    if (stripos($award->location, $locationObject->name) !== false) {
                        $locationMatch = true;
                        break;
                    } else {
                        $locationMatch = false;
                    }
                }

                foreach ($arrayOfIndustriesObjects as $industriesObject) {
                    if ($award->industries()->where('name', 'like', '%' . $industriesObject->name . '%')->exists()) {
                        $industriesMatch = true;
                        break;
                    } else {
                        $industriesMatch = false;
                    }
                }

                foreach ($arrayOfBusinessFunctionObjects as $businessFunctionObject) {
                    if ($award->businessfunctions()->where('name', 'like', '%' . $businessFunctionObject->name . '%')->exists()) {
                        $businessFunctionMatch = true;
                        break;
                    } else {
                        $businessFunctionMatch = false;
                    }
                }

//                if ($award->relationLoaded('categories')) {
//                    $categoryNames = $award->categories->pluck('name')->toArray();
//                    foreach ($arrayOfcategoriesObjects as $categoryObject) {
//                        if (in_array($categoryObject->name, $categoryNames)) {
//                            $categoriesMatch = true;
//                            break;
//                        } else {
//                            $categoriesMatch = false;
//                        }
//                    }
//                }

                return ($nameMatch || $keywordsMatch || $descriptionMatch || $regionMatch) && $locationMatch && $industriesMatch && $businessFunctionMatch;
            });
        }

        $perPage = 99;
        $page = request('page', 1);

        $awardsWithPagination = new LengthAwarePaginator(
            $awards_result->forPage($page, $perPage),
            $awards_result->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
        $queryParameters = request()->query();
        unset($queryParameters['page']);
        $awardsWithPagination->appends($queryParameters);

        $request->session()->put('backUrl', $request->fullUrl());

        return Inertia::render('Awards/Index', [
            'awards' => fn() => $awardsWithPagination,
            'industries' => fn() => $industries,
            'categories' => fn() => $categories,
            'locations' => fn() => $locations,
            'businessFunctions' => fn() => $businessFunctions,
            'keywords' => $search,
            'activeLocation' => $location,
            'activeIndustry' => $industry,
            'activeCategory' => $category,
            'activeBusinessFunction' => $businessFunction,
        ]);
    }

    public function show($id)
    {
        $award = Award::with('industries', 'categories', 'sponsors', 'businessFunctions')->find($id);

        if ($award) {
            $award->early_deadline_date = date('F j, Y', strtotime($award->early_deadline_date));
            $award->deadline_date = date('F j, Y', strtotime($award->deadline_date));
            $award->awarding_date = date('F j, Y', strtotime($award->awarding_date));

            $awardingDate = $award->awarding_date;

            $relatedAwards = Award::where('sponsor_id', $award->sponsor_id)
                ->where(function ($query) use ($award) {
                    $query->whereHas('industries', function ($q) use ($award) {
                        $q->whereIn('id', $award->industries ? $award->industries->pluck('id') : []);
                    })
                        ->orWhereHas('businessFunctions', function ($q) use ($award) {
                            $q->whereIn('id', $award->businessFunctions ? $award->businessFunctions->pluck('id') : []);
                        });
                })
                ->where('id', '!=', $id)
                ->where('awarding_date', '>', $awardingDate)
                ->take(3)
                ->get();

        }
        $backUrl = Session::get('backUrl');
        return Inertia::render('Awards/Show', [
            'award' => $award,
            'relatedAwards' => $relatedAwards,
            'previousURL' => $backUrl,
        ]);
    }

    public function showSponsor(){
        return Inertia::render('Sponsor/Show');
    }

}
