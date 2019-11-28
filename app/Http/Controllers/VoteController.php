<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Vote;
use App\VoteOption;
use App\UserVote;

use App\Events\UserVotedEvent;

class VoteController extends Controller
{
    private $authUserId;
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        
    }

    public function storeVote(Request $request) {
        try {
            $question = $request->poll['question'];
            $answers = $request->poll['answers'];

            $vote = Vote::create([
                'title' => $question,
                'user_id' => auth()->user()->id
            ]);

            
            foreach ($answers as $key => $value) {
                if ($value['answer'] != "") {
                    VoteOption::create([
                        'question_id' => $vote->id,
                        'title'     => $value['answer'],
                        'votes' => 0
                    ]);
                }
            }
            return response()->json(['success'=>true], 200);   
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>$e->getMessage()],500);
        }
    }

    public function createVote() {
        return view('create_vote');
    }

    public function myVotes() {

        $myVotes = Vote::where('user_id', auth()->user()->id)->get();
        return view('my_votes', compact('myVotes'));
    }

    public function getVote($id) {
            

            $question = Vote::where('id', $id)->with('options', 'user')->first();
            $isVoted = UserVote::where('user_id', auth()->user()->id)
                        ->where('question_id', $id)
                        ->first() ? 1 : 0;
            
            return view('vote', compact('question', 'isVoted'));
    }


    public function postVote(Request $request) {
        try {
            $optionId = $request->optionId;
            $questionId = $request->questionId;

            $userPreviousVote = UserVote::where('user_id', auth()->user()->id)
                                    ->where('question_id', $questionId)
                                    ->first();
            if ($userPreviousVote) {

                $oldUsersOptionId = $userPreviousVote->option_id;
                $userPreviousVote->option_id = $optionId;
                $userPreviousVote->save();

                VoteOption::find($optionId)->increment('votes', 1);
                VoteOption::find($oldUsersOptionId)->decrement('votes', 1);

            } else {
                UserVote::create([
                    'user_id' => auth()->user()->id,
                    'question_id' => $questionId,
                    'option_id' => $optionId
                ]);

                VoteOption::find($optionId)->increment('votes', 1);
            }
            $question = Vote::where('id', $questionId)->with('options')->first();
            broadcast(new UserVotedEvent($question))->toOthers();
            return response()->json(['success'=>true], 200);    
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>$e->getMessage()], 500);
        }
    }

    public function unvote(Request $request) {

        try {
            $questionId = $request->questionId;
            $usersOptionId = null;

            $userPreviousVote = UserVote::where('user_id', auth()->user()->id)
                                ->where('question_id', $questionId)
                                ->first();
            
            if ($userPreviousVote) {
                $usersOptionId = $userPreviousVote->option_id;
                $userPreviousVote->delete();

                VoteOption::find($usersOptionId)->decrement('votes', 1);
            }
            $question = Vote::where('id', $questionId)->with('options')->first();
            broadcast(new UserVotedEvent($question))->toOthers();
            return response()->json(['success'=>true], 200);    
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>$e->getMessage()], 500);
        }
            
    }

    public function results($id) {
        $options = VoteOption::select('users.name', 'vote_options.title')
                    ->where('vote_options.question_id', $id)
                    ->join('user_votes', 'user_votes.option_id', '=', 'vote_options.id')
                    ->join('users', 'user_votes.user_id', '=', 'users.id')
                    ->get()
                    ->groupBy('title');
        $question = Vote::where('id', $id)->first();
        return view('vote_results', compact('options', 'question'));
    }
}
