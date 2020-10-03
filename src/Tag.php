<?php

namespace RoniEstein\Press;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
    
    protected $table = 'press_tags';
    
    /**
     * Get the posts associated with the Tag.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class,'press_post_tags');
    }
    
    public static function prune()
    {
        
        $test = Tag::doesntHave('posts')->delete();
        return $test;
        
//        DB::table('press_tags')
//            ->whereNotExists(function ($query) {
////                $query->select(DB::raw(1))
//                    $query->select('tag_id')
//                    ->from('press_post_tags')
//                    ->whereRaw('press_tags.id = press_post_tags.tag_id');
//            })
//            ->get();
    }
}