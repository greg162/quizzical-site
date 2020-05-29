<template>
    <div class="question">
        <select v-model="question.questionType" >
            <option value="multiple-choice" >Mulitple Choice</option>
            <option value="text" >One Answer</option>
        </select>
        <i v-on:click="removeThisQuestion(index);" class="far fa-window-close"></i>
        <input class="form-control form-control-lg m-2" v-model="question.question" type="text" placeholder="Question">

        <div v-show="question.questionType == 'text' ">
            <textarea class="form-control form-control-lg m-2" v-model="question.answer_1" type="text" placeholder="Answer (leave blank if you want to find your own answer)"></textarea>
        </div>
        <div v-show="question.questionType == 'multiple-choice' ">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="1" type="radio" /></span>
                </div>
                <input class="form-control form-control-lg " v-model="question.answer_1" type="text" placeholder="Answer 1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="2" type="radio" /></span>
                </div>
                <input class="form-control form-control-lg " v-model="question.answer_2" type="text" placeholder="Answer 1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="3" type="radio" /></span>
                </div>
                <input class="form-control form-control-lg " v-model="question.answer_3" type="text" placeholder="Answer 1">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><input v-model="question.correct_answer" value="4" type="radio" /></span>
                </div>
                <input class="form-control form-control-lg " v-model="question.answer_4" type="text" placeholder="Answer 1">
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Question created' )
        },
        props: ['question', 'index', 'removeQuestion'],
        methods: {
            removeThisQuestion: function(index) {
                this.$parent.removeQuestion(index);
            }
        },
    }
</script>