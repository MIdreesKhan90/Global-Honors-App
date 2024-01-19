<?php

namespace App\Http\Controllers;

use App\Models\AwardList;
use App\Exports\AwardsExport;
use App\Models\CustomerList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ListsController extends Controller
{
    public function index(){
        $lists = CustomerList::where('customer_id',Auth::id())->withCount('awards')
            ->with(['awards' => function($query) {
                $query->latest('pivot_created_at'); // Assuming 'pivot_created_at' is the name of the timestamp column in the pivot table
            }])
            ->get()
            ->map(function ($list) {
                // Assuming that 'awards' is the name of the relationship
                // and 'award_list' is the pivot table
                $latestAward = $list->awards->first(); // Get the latest award
                $list->latest_update = $latestAward ? Carbon::parse($latestAward->pivot->created_at)->format('m/d/Y') : Carbon::parse($list->updated_at)->format('m/d/Y');
                unset($list->awards); // Remove the awards if you don't need them
                return $list;
            });

        return Inertia::render('Lists/Index',['lists' => $lists]);
    }

    public function show(CustomerList $list){
        // Load the awards related to the list
        $list->load(['awards.sponsors','awards.businessFunctions','awards.industries']);

        // Optionally, if you want to add more data like the count of awards or other related models
        $list->loadCount('awards');

        return Inertia::render('Lists/Show',[
            'list' => $list,
            'awards' => $list->awards,
        ]);
    }

    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $request->user()->lists()->create($validated);

        $newList = CustomerList::where('customer_id', Auth::id())->orderBy('created_at', 'DESC')->first();

        return response()->json([
            'message' => 'New List Created.',
            'listId'  => $newList->id
        ]);
    }

    public function renameList(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rename = CustomerList::where('id',$request->id)->update(['name' => $validated['name']]);

        if ($rename){
            return response()->json(['message' => 'List Name Updated.']);
        }else{
            return response()->json(['message' => 'Something Went Wrong.']);
        }

    }

    public function deleteList(Request $request)
    {
        $delete = DB::table('customer_lists')->where('id',$request->id)->delete();

        $getListAwards = AwardList::where('list_id',$request->id)->pluck('id')->toArray();

        $deleteLists = DB::table('award_lists')->whereIn('id',$getListAwards)->delete();

        if ($delete){
            return response()->json(['message' => 'List Deleted.']);
        }else{
            return response()->json(['message' => 'Something Went Wrong.']);
        }
    }

    public function addAwardsToList(Request $request)
    {
        $validated = $request->validate([
            'list_id' => 'required|exists:customer_lists,id',
            'award_ids' => 'required|array',
            'award_ids.*' => 'exists:awards,id'
        ]);

        $count = count($validated['award_ids']);

        $list = CustomerList::findOrFail($validated['list_id']);
        // Using syncWithoutDetaching to add awards without removing existing ones
        $list->awards()->syncWithoutDetaching($validated['award_ids']);

        return response()->json(['message' => $count.' awards have been added to '.$list->name]);
    }

    public function removeAwardsFromList(Request $request)
    {
        $validated = $request->validate([
            'list_id' => 'required|exists:customer_lists,id',
            'award_ids' => 'required|array',
            'award_ids.*' => 'exists:awards,id'
        ]);

        $count = count($validated['award_ids']);

        $list = CustomerList::findOrFail($validated['list_id']);
        // Using detach to remove the specified awards from the list
        $list->awards()->detach($validated['award_ids']);

        return response()->json(['message' => $count.' awards have been removed from '.$list->name,'awards' => $list->awards]);
    }

    public function exportAwards(CustomerList $list)
    {
        $cleanListName = preg_replace('/[^A-Za-z0-9\-]/', '', $list->name); // Remove special characters
        $cleanListName = str_replace(' ', '_', $cleanListName); // Replace spaces with underscores
        $filename = $cleanListName . '.xlsx'; // Construct the filename
        return Excel::download(new AwardsExport($list), $filename);
    }

    public function customerLists(){
        $lists = CustomerList::where('customer_id',Auth::id())->get();

        return response()->json(['lists' => $lists]);
    }

}
