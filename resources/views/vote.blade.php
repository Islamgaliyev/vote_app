@extends('layouts.app')

@section('content')
<div class="container">
    <vote :question="{{ $question }}" :is_voted="{{ $isVoted }}"></vote>
</div>
@endsection