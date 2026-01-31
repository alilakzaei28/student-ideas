<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VoteCast;

class IdeaController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $query = Idea::withCount('votes')->with(['user', 'category']);

        if (request()->has('category')) {
            $slug = request()->get('category');
            $query->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        if (request()->has('search')) {
            $search = request()->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = request('sort', 'votes');
        if ($sort === 'newest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->orderBy('votes_count', 'desc');
        }

        $ideas = $query->paginate(5);

        return view('ideas.index', compact('ideas', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        Idea::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('ideas.index')->with('success', 'ایده ثبت شد.');
    }

    public function vote(Idea $idea, Request $request)
    {
        $user = Auth::user();
        $isUpvote = $request->input('is_upvote') == '1';

        $existingVote = $idea->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->is_upvote == $isUpvote) {
                $existingVote->delete();
            } else {
                $existingVote->update(['is_upvote' => $isUpvote]);
            }
        } else {
            $idea->votes()->create([
                'user_id' => $user->id,
                'is_upvote' => $isUpvote
            ]);

            if ($idea->user_id !== $user->id) {
                $idea->user->notify(new VoteCast($user, $idea));
            }
        }
        
        return back();
    }

    public function destroy(Idea $idea)
    {
        if (Auth::id() !== $idea->user_id) {
            return back()->with('error', 'عدم دسترسی');
        }
        
        $idea->delete();
        return redirect()->route('ideas.index')->with('success', 'ایده حذف شد.');
    }
    
    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}