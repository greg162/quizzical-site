<template>
    <div class="card mt-3">
        <div class="card-body">
            <div class="question">
                <div class="input-group m-2">
                    <select class="form-control" v-on:change="questionTypeChanged()" v-model="question.questionType" >
                        <option value="" >Question Type</option>
                        <option value="multiple-choice" >Mulitple Choice</option>
                        <option value="text" >One Answer</option>
                        <option value="embed" >Embed Something</option>
                        <option value="upload" >Upload Something</option>
                    </select>
                    <div class="input-group-append">
                        <button v-on:click="removeThisQuestion(index);" class="btn btn-warning" >
                            <i class="far fa-window-close"></i>
                        </button>
                    </div>
                </div>
                <input class="form-control m-2" v-model="question.question" type="text" placeholder="Question">

                <div v-show="question.questionType == 'text' ">
                    <textarea class="form-control m-2" v-model="question.answer_1" type="text" placeholder="Answer (leave blank if you want)"></textarea>
                </div>
                <div v-show="question.questionType == 'embed' ">
                    <textarea class="form-control m-2" v-model="question.answer_2" type="text" placeholder="Your Embed Code"></textarea>
                    <textarea class="form-control m-2" v-model="question.answer_1" type="text" placeholder="Answer (leave blank if you want)"></textarea>
                </div>
                <div v-show="question.questionType == 'upload' ">
                    <div v-bind:id="'upload'+question.id" class="dropzone">
                        
                    </div>
                    <textarea class="form-control m-2" v-model="question.answer_1" type="text" placeholder="Answer (leave blank if you want)"></textarea>
                </div>
                <div v-show="question.questionType == 'multiple-choice' ">
                    <div class="input-group m-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="1" type="radio" /></span>
                        </div>
                        <input class="form-control" v-model="question.answer_1" type="text" placeholder="Answer 1">
                    </div>
                    <div class="input-group m-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="2" type="radio" /></span>
                        </div>
                        <input class="form-control " v-model="question.answer_2" type="text" placeholder="Answer 2">
                    </div>
                    <div class="input-group m-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="3" type="radio" /></span>
                        </div>
                        <input class="form-control " v-model="question.answer_3" type="text" placeholder="Answer 3">
                    </div>
                    <div class="input-group m-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="4" type="radio" /></span>
                        </div>
                        <input class="form-control " v-model="question.answer_4" type="text" placeholder="Answer 4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if(this.question.questionType == 'upload') {
                this.createDropZone();
            }
        },
        props: ['question', 'index', 'removeQuestion'],
        methods: {
            removeThisQuestion: function(index) {
                this.$parent.removeQuestion(index);
            },
            createDropZone() {
                var currentFile = null;
                var myDropzone = new Dropzone('#upload'+this.question.id, {
                    url: "/quiz/upload/"+this.$parent.id,
                    maxFilesize: 5, // MB
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    parallelUploads: 1,
                    params: {
                        uuid: this.question.id
                    },
                    addRemoveLinks: true,
                    init: function() {
                        this.on("addedfile", function(file) {
                        if (currentFile) {
                            this.removeFile(currentFile);
                            console.log('here');
                        }
                        currentFile = file;
                        });
                    },
                    removedfile: function(file) {
                        console.log(file);
                        file.previewElement.remove();
                        var updateObject = {
                            uuid: this.question.id,
                        }
                        axios.post('/quiz/remove-upload/'+quizId, updateObject)
                        .then(function (response) {
                        }.bind(this))
                    }
                });
            },
            questionTypeChanged() {
                if(this.question.questionType == 'upload') {
                    this.createDropZone();
                }
            }
        },
    }
</script>