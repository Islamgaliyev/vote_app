@extends('layouts.app')


@section('content')
<div class="container">
    <span>My votes</span>
    <ul class="list-group">
        @foreach($myVotes as $vote)
            <a href="/vote/{{$vote->id}}"><li class="list-group-item">{{$vote->title}}</li></a>
        @endforeach
    </ul>
</div>
@endsection