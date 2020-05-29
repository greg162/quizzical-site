@extends('layouts.main-site')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <h2>Your Quizzes</h2>
                    <div class="row">
                        <div class="col" >
                            <div class="btn-group btn-group-lg">
                                @foreach($userQuizzes as $userQuiz)
                                    <a href="{{ route('quiz.edit',[ 'id' => $userQuiz->id]) }}" style="width:500px;" class="btn btn-primary" >{{$userQuiz->name}}</a>
                                    <a href="{{ route('quiz.start',[ 'id' => $userQuiz->id]) }}" type="button" class="btn btn-success">Start Game</a>
                                    <a href="{{ route('quiz.delete',[ 'id' => $userQuiz->id]) }}" type="button" class="btn btn-warning">Delete</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col" >
                            
                        </div>
                    </div>

                    <a class="btn btn-primary btn-lg mt-3" href="{{ route('quiz.create',) }}" >Create a Quiz</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
