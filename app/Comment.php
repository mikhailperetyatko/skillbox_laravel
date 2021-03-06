<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CacheFlushableAfterCreatedModelTrait;

class Comment extends Model
{
    use CacheFlushableAfterCreatedModelTrait;
    
    protected $fillable = [
		'body', 
		'owner_id',
	];
	
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'commentable');
    }
    
    public function informations()
    {
        return $this->morphedByMany(Information::class, 'commentable');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
