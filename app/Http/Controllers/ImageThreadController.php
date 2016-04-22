<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Post;

use DB;

use App\Stats;

class ImageThreadController extends Controller
{
    public function index() {
        
        $stats = Stats::find(1);
        if (empty($stats)) {
            $stats = new Stats();
            $stats->id = 1;
            $stats->views = 1;
            $stats->save();
        } else {
            $stats->views++;
            $stats->save();
        }
        return view('main', [
            'posts_amount' => DB::table('posts')->count(),
            'posts' => Post::all(),
             'views_amount' => $stats->views
        ]);
    }
}
