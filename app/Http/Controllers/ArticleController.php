<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    //

    public function insert(Request $request)
    {
        $request-> validate([
            'title' => 'required|min:6',
            'article' => 'required|min:6',
        ]);
        // echo 213;
        $notification = array(
            'message' => 'Successfully Done',
            'alert-type' => 'success'
        );

        if($request->format == 'create'){
            // return $request;
            DB::table('articles')->insert([
                'title'=> $request->title,
                'article'=> $request->article,
                'email' => Auth::user()->email,
            ]);

        } else {
            $art = Article::where('id', $request->id)->first();
            $art->title =$request->title;
            $art->article =$request->article;
            $art->save();
        }
        $notification = array(
            'message' => 'Successfully Done',
            'alert-type' => 'success'
        );
        return $notification;
        // return back()-> with($notification);
    }

    public function getArticles() {
        $data = Article::select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn ='<div class = "d-flex justify-content-around"><button class = "btn btn-primary btn-sm " data-toggle="modal" data-target = "#demoModal" onClick = "editArticle(' .
                $row->id . ')">Edit</button><button class = "btn btn-primary btn-sm " onClick = "deleteArticle(' . $row->id .
                ')">Delete</button></div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        // return DataTables::of(Article::query())->toJson();
    }

    public function getArticle(Request $request) {
        $request->query('id');
        $article = Article::where('id', $request->query('id'))->first();
        return $article;
    }

    public function deleteArticle(Request $request)
    {
        echo $request->index;
        $data = Article::findOrFail($request->index);
        $data->delete();
    //    $del =  Article::where('id' , $request->index)->first()->delete();
    }
}
