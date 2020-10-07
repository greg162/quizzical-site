@extends('layouts.main-site')


@section('header')
    <script > const quizId = null; </script>
    <link href="/css/dropzone.css" rel="stylesheet">
@endsection

@section('content')
<div class="container" id="create-quiz-app" >
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3>Create a Quiz</h3>
            <div class="card">
                <div class="card-body">
                    <div v-show="success" class="alert alert-success show-nl" role="alert">@{{success}}</div>
                    <div v-show="errors" class="alert alert-warning show-nl" role="alert">@{{errors}}</div>
                    <input class="form-control form-control-lg m-2" v-model="title" type="text" placeholder="Your Quiz Name">
                    <textarea class="form-control form-control-lg m-2" v-model="description" placeholder="Short Description (if you want)" id="exampleFormControlTextarea1" rows="3"></textarea>
                    <input class="form-control form-control-lg m-2" v-model="password" type="text" placeholder="Quiz Master Password">
                </div>
            </div>
            <draggable v-model="questions" handle=".grabber" >
                <question-component v-for="(question, index) in questions"  @remove="removeQuestion" v-bind:quizId="''" v-bind:question="question"></question-component>
            </draggable>
            <button class="btn btn-success mt-3" v-on:click="addQuestion();" >Add a Question</button>
            <button class="btn btn-success mt-3" v-on:click="saveQuiz();" >Save Quiz</button>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="/js/quiz.js" ></script>
@endsection