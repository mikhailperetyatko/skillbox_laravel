<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use TestsHttpTrait;
    
    public function testAUserCanCreatePostWithoutTags()
    {
        //$this->withoutExceptionHandling();
        $this->withUser()->withFacrotyRaw(\App\Post::class, ['published' => "1"])->withPostRequest('/posts');        
        $this->assertDatabaseHas('posts', $this->testingModel);
    }
    
    public function testAUserCanCreatePostWithTags()
    {
        $tag = factory(\App\Tag::class)->raw();
        $this->withUser()->withFacrotyRaw(\App\Post::class, ['published' => '1'])->withPostRequest('/posts', ['tags' => $tag['name']]);
        $this->assertDatabaseHas('posts', $this->testingModel);
        $this->assertDatabaseHas('tags', $tag);
    }
    
    public function testAUserCanUpdatePost()
    {
        //$this->withoutExceptionHandling();
        $this->withUser()->withFacrotyCreate(\App\Post::class, ['published' => '1', 'owner_id' => $this->user->id])->withPatchRequest('/posts', ['title' => 'new111111']);
        $this->assertDatabaseHas('posts', $this->testingModel);
    }
    
    public function testAUserCanDeletePost()
    {
        //$this->withoutExceptionHandling();
        $this->withUser()->withFacrotyCreate(\App\Post::class, ['published' => '1', 'owner_id' => $this->user->id])->withDeleteRequest('/posts');
        $this->assertDatabaseMissing('posts', $this->testingModel);
    }
    
    
}
