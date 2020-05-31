@extends('layouts.main-site')


@section('header')
<script > const quizId = null; </script>
    <link href="{{ asset('css/rocker-switch.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div id="create-quiz-app" >
                        <div v-show="success" class="alert alert-success show-nl" role="alert">@{{success}}</div>
                        <div v-show="errors" class="alert alert-warning show-nl" role="alert">@{{errors}}</div>
                        <input class="form-control form-control-lg m-2" v-model="title" type="text" placeholder="Your Quiz Name">
                        <textarea class="form-control form-control-lg m-2" v-model="description" placeholder="Short Description (if you want)" id="exampleFormControlTextarea1" rows="3"></textarea>
                        <input class="form-control form-control-lg m-2" v-model="password" type="text" placeholder="Quiz Master Password">
                        <question-component v-for="(question, index) in questions" v-bind:index="index" v-bind:question="question"></question-component>
                        <button class="btn btn-success" v-on:click="addQuestion();" >Add a Question</button>
                        <br><br>
                        <button class="btn btn-success" v-on:click="saveQuiz();" >Save Question</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
