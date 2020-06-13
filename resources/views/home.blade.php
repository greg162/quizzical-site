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

                    <p>Welcome to Quizzlcal! You can view your quizzes here:</p>
                    <a class="btn btn-primary btn-lg btn-block" href="{{ route('quiz.list') }}" >Build Me Some Quizzes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
