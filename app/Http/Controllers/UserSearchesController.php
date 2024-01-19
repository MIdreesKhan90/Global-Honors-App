<?php

namespace App\Http\Controllers;

use App\Models\SearchList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserSearchesController extends Controller
{
     public function index(){
         $lists = SearchList::where('customer_id', Auth::id())->get();
         return Inertia::render('SavedSearches/Index', compact('lists'));
     }

     public function addSearchList(Request $request){

         $searchList = new SearchList();
         $searchList->create([
             'name' => $request->name,
             'customer_id' => Auth::id(),
         ]);

         $newSearchList = SearchList::where('customer_id', Auth::id())->orderBy('created_at', 'DESC')->first();

         return  response()->json([
             'successMessage' => 'Search list added successfully',
             'searchList'   => $newSearchList,
             ]);
     }

     public function deleteSearchList($id){
            $list = SearchList::find($id);
            $list->delete();

         return Redirect::route('searches.index');
     }

     public function renameList(Request $request, $id){
         $list = SearchList::find($id);
         $list->update([
            'name' => $request->name
         ]);

         return Redirect::route('searches.index');
     }

     public function addSearchToList(Request $request){
         $searchList = SearchList::find($request->list_id);
         $searchList->update([
            'search_query' => $request->search_query,
         ]);

         return Redirect::route('awards.index')->with('successMessage', 'Search added successfully');
     }

     public function getSearchLists()
     {
        $searchLists = SearchList::where('customer_id',Auth::id())->get();

        return response()->json(['searchLists' => $searchLists]);
     }
}
