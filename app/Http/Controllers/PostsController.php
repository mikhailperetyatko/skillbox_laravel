<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;

class PostsController extends Controller
{
    const AMOUNT_LIMIT = 3;
    
    protected function getValidateRulesForCreate() : array
    {
        return [
            'title' => 'required|min:5|max:100',
            'description' => 'required|max:255',
            'body' => 'required',
            'slug' => 'required|regex:/^[0-9A-z_-]+$/|unique:posts'
        ];
    }
    
    protected function getValidateRulesForUpdate(Post $post) : array
    {
        $rules = $this->getValidateRulesForCreate();
        $rules['slug'] .= ',slug,' . $post->id;
        return $rules;
    }
    
    public function index()
    {
        $posts = Post::with('tags')->latest()->simplePaginate(self::AMOUNT_LIMIT);
        return view('posts', compact('posts'));
    }
    
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
    
    public function create()
    {
        return auth()->check() ? view('posts.create') : redirect('/login');
    }
    
    public function store()
    {
        $this->authorize(new Post);
        
        $attr = request()->validate($this->getValidateRulesForCreate());
        $attr['published'] = request()->has('published');
        $attr['owner_id'] = auth()->id();
        $this->getSyncTags(Post::create($attr));
        
        flash('success');
        return redirect('/');
    }
    
    public function edit(Post $post)
    {
        $this->authorize($post);
        return view('posts.edit', compact('post'));
    }
    
    public function update(Post $post)
    {
        $this->authorize($post);
        
        $attr = request()->validate($this->getValidateRulesForUpdate($post));
        $attr['published'] = request()->has('published');
        $post->update($attr);
        $this->getSyncTags($post);
        
        flash('success');
        return redirect('/posts/' . $post->slug);
    }
    
    public function destroy(Post $post)
    {
        $this->authorize($post);
        
        $post->delete();
        
        flash('warning', 'Статья удалена');
        return redirect('/');
    }
    
    public function getSyncTags(Post $post)
    {
        $postTags = $post->tags->keyBy('name');
        $tags = collect(explode(',', request('tags')))->keyBy(function ($item) {
            return $item;
        });
        
        $syncIds = $postTags->intersectByKeys($tags)->pluck('id')->toArray();
        $tagsToAttach = $tags->diffKeys($postTags);
        foreach ($tagsToAttach as $tag) {
            if (!empty($tag)) {
                $tag = Tag::firstOrCreate(['name' => $tag]);
                $syncIds[] = $tag->id;
            }
        }
        
        $post->tags()->sync($syncIds);
    }
}
