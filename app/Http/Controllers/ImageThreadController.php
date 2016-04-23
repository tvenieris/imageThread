<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Post;

use DB;

use App\Stats;

class ImageThreadController extends Controller
{
    public function index(Request $request) {
        
        $stats = Stats::increaseViews();
        return view('main', [
            'posts_amount' => DB::table('posts')->count(),
            'posts' => Post::all(),
            'views_amount' => $stats->views,
            'last_error' => $request->session()->get('last_error')
        ]);
    }
}
