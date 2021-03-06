<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Statistics;

class StatisticsController extends Controller
{    
    public function show(Statistics $statistics)
    {
        return view('statistics', 
            rememberCacheWithTags([Statistics::class, \App\User::class, \App\Information::class, \App\Post::class], 'statistics', function() use ($statistics) {return [
                'amountPosts' => $statistics->getAmountPosts(),
                'amountInformations' => $statistics->getAmountInformations(),
                'userWhoHasTheMostPosts' => $statistics->getUserWhoHasTheMostPosts(),
                'biggestPost' => $statistics->getBiggestPost(),
                'smallerPost' => $statistics->getSmallerPost(),
                'averagePostsOfActiveUsers' => $statistics->getAveragePostsOfActiveUsers(),
                'mostCommentable' => $statistics->getMostCommentable(),
                'mostChangeable' => $statistics->getMostChangeable(),
            ];})
        );
    }
}
