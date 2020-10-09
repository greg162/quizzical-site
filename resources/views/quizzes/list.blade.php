@extends('layouts.main-site')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <h2>Your Quizzes</h2>
                    <div class="row">
                        <div class="col" >
                                @foreach($quizzes as $quiz)
                                    <div class="btn-group btn-group-lg mt-3 mb-3">
                                        <a href="{{ route('quiz.edit',[ 'id' => $quiz->id]) }}" style="width:500px;" class="btn btn-primary" >{{$quiz->name}}</a>
                                        <a href="{{ route('quiz.start',[ 'id' => $quiz->id]) }}" type="button" class="btn btn-success">Start Game</a>
                                        <a href="{{ route('quiz.delete',[ 'id' => $quiz->id]) }}" type="button" class="btn btn-warning">Delete</a>
                                    </div>
                                    <h4>Recent Games</h4>
                                    <table class="table" >
                                        @foreach($quiz->games as $game)
                                            @if ($game->quiz_id === $quiz->id)
                                                <tr>
                                                    <td>{{ $game->game_id }}<td>
                                                    <td><a target="_blank" href="{{ $game->url }}">{{ $game->url }}</a><td>
                                                    <td>@if( $game->game_started) <i class="fas fa-running"></i> @endif</td>
                                                    <td>@if( $game->game_completed) <i class="fas fa-flag-checkered"></i> @endif</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endforeach
                        </div>

                    </div>

                    <a class="btn btn-primary btn-lg mt-3" href="{{ route('quiz.create',) }}" >Create a Quiz</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
