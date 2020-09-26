@extends('layouts.main-site')

@section('header')
<script > const quizId = {{ intval($id) }}</script>
<link href="/css/dropzone.css" rel="stylesheet">
@endsection

@section('content')
<div class="container" id="create-quiz-app" >
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1>Edit Quiz</h1>
            <div class="card">
                <div class="card-body">
                    <div v-show="success" class="alert alert-success show-nl" role="alert">@{{success}}</div>
                    <div v-show="errors" class="alert alert-warning show-nl" role="alert">@{{errors}}</div>
                    <input class="form-control form-control m-2" v-model="title" type="text" placeholder="Your Quiz Name">
                    <div class="form-check m-2">
                        <input class="form-check-input" v-model="updatePassword" type="checkbox" value="" id="updatePassword">
                        <label class="form-check-label" for="updatePassword">
                            Update Password
                        </label>
                    </div>
                    <div v-if="updatePassword" >
                        <input class="form-control form-control m-2" v-model="password" type="text" placeholder="Update your quiz password (leave blank to use the current one)">
                    </div>
                    <textarea class="form-control form-control m-2" v-model="description" placeholder="Short Description (if you want)" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
            </div>
            <draggable v-model="questions" @start="drag=true" @end="drag=false" handle=".grabber" >
                <question-component v-for="(question, index) in questions" v-bind:index="index"  :key="question.id" v-bind:quizId='id' v-bind:question="question"></question-component>
            </draggable>
            <button class="btn btn-success mt-3" v-on:click="addQuestion();" >Add a Question</button>
            <button class="btn btn-success mt-3" v-on:click="updateQuiz();" >Update</button>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    <script src="/js/quiz.js" ></script>
@endsection