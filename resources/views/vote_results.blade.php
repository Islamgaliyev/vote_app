@extends('layouts.app')


@section('content')
<div class="container">
<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{$question->title}}</h3>
        </div>   
        <ul class="list-group">
        @foreach($options as $key => $value)
            <li class="list-group-item">
                <div class="row toggle" id="dropdown-detail-1" data-toggle="detail-1">
                    <div class="col-xs-10">
                        {{$key}}
                    </div>
                    <div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
                </div>
                <div id="detail-1">
                    <hr></hr>
                    <div class="container">
                        <div class="fluid-row">
                        @foreach($value as $v)
                            <div class="col-xs-1">
                                {{$v->name}}
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
	</div>
    </div>
@endsection