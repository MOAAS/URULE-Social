<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\User;
use App\Content;
use Illuminate\Database\Eloquent\Model;

class SearchController extends Controller
{
    public function getResults(Request $request, $offsetUsers, $offsetPosts, $limit, $isJson) {
        $query = $request->keywords;
        $showUsers = $request->input('users');
        $showPosts = $request->input('posts');

        if(!$showUsers && !$showPosts) {
            $showUsers = false;
            $showPosts = true;
        }

        if(!$request->exists('startDate')) {
            $startDate = date('Y-m-d');
        } else $startDate = date('Y-m-d', strtotime($request->input('startDate')));

        if(!$request->exists('endDate')) {
            $endDate = date('Y-m-d');
        } else $endDate = date('Y-m-d', strtotime($request->input('endDate')));

        if ($showUsers)
            $people = $this->getUsers($query, $showUsers, $offsetUsers, $limit);
        else $people = collect();

        if($showPosts)
            $posts = $this->getPosts($query, $showPosts, $startDate, $endDate, $offsetPosts, $limit);
        else $posts = collect();

        $results = $people->merge($posts);
        $results = $results->sortByDesc('rank');

        if ($isJson) {
            return response()->json($results);
        }
        else return view('pages.search', [
            'user' => Auth::user(),
            'keywords' => $query,
            'showUsers' => $showUsers,
            'showPosts' => $showPosts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'results' => $results,
            'isQuery' => true,
        ]);

    }

    public function search(Request $request) {

        if (!$request->exists('keywords')) {
            return view('pages.search', [
                'user' => Auth::user(), 
                'keywords' => "", 
                'showUsers' => true, 
                'showPosts' => true, 
                'startDate' => date('Y-m-d', strtotime("-1 week")), 
                'endDate' => date('Y-m-d', strtotime("today")), 
                'results' => [],
                'isQuery' => false,
            ]);
        }

        return $this->getResults($request, 0, 0, 4, false);
    }

    public function search_api(Request $request) {
        $request->validate([
            'keywords' => 'required',
            'users' => 'required|boolean',
            'posts' => 'required|boolean',
            'userOffset' => 'required|integer|min:0',
            'postOffset' => 'required|integer|min:0',
            'limit' => 'integer|min:1|max:500'
        ]);

        return $this->getResults($request, $request->userOffset, $request->postOffset, $request->limit, true);
    }

    private function getPosts($query, $showPosts, $startDate, $endDate, $offset, $limit) {
        if($showPosts && $query) { //Search for public posts whose content contains the keywords (and postDates are between the ones specified)
            $posts = DB::select(
                "SELECT comments, content, content_date, content_id, dislikes, likes, private, author_id, ts_rank_cd(post_search, plainto_tsquery('english', :keywords)) AS rank
                FROM content JOIN post ON (post_id = content_id) JOIN post_comments_view USING (post_id)
                WHERE content_date >= :startDate 
                    AND content_date <= :endDate 
                    AND post_search @@ plainto_tsquery('english', :keywords)
                    AND can_view_post(:id, author_id, private)                    
                ORDER BY rank DESC
                OFFSET :offset
                LIMIT :limit",
                [
                    'keywords' => $query,
                    'startDate' => $startDate,
                    'endDate' => date('Y-m-d', strtotime("+1 day", strtotime($endDate))),
                    'id' => Auth::check() ? Auth::user()->user_id : null,
                    'offset' => $offset,
                    'limit' => $limit
                ]
            );


            $posts = Content::hydrate($posts);
        }
        else $posts = collect();

        foreach($posts as $post) $post->author;

        return $posts;
    }

    private function getUsers($query, $showUsers, $offset, $limit) {
        if($showUsers && $query) { //Search for people whose name contains the keywords
            $people = DB::select(
                "SELECT user_id, name, location, ts_rank_cd(user_search, plainto_tsquery('english', :keywords)) AS rank
                FROM users
                WHERE user_search @@ plainto_tsquery('english', :keywords)
                ORDER BY rank DESC
                OFFSET :offset
                LIMIT :limit",
                [
                    'keywords' => $query,
                    'offset' => $offset,
                    'limit' => $limit
                ]
            );
            $people = User::hydrate($people);
        }
        else $people = collect();

        return $people;
    }
}
