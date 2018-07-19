<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
    	$posts = Post::all();
    	return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
    	$categories =  Category::all();
    	$tags = Tag::all();
    	return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
    	$this->validate($request,[
    		'title' 	=> 'required',
    		'body'  	=> 'required',
    		'category'  => 'required',
    		'excerpt'  	=> 'required',
    		'tags'  	=> 'required',
    	]);

    	$post = new Post;
    	$post->title = $request->get('title');
    	$post->body = $request->get('body');
    	$post->excerpt = $request->get('excerpt');
    	$post->published_at = $request->has('published_at') ? Carbon::parse($request->get('published_at')) : null;
    	$post->category_id = $request->get('category');

    	$post->save();

    	$post->tags()->attach($request->get('tags'));

    	return back()->with('flash', 'Tu publicación ha sido creada');
    }
}
