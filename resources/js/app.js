/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

const axios = require('axios');
general = require('./general-library');



window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('question-component', require('./components/QuestionTemplate.vue').default);
Vue.component('question-component', require('./components/QuestionTemplate.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#create-quiz-app',
    data: {
        success: "",
        errors: "",
        title: "",
        password: "",
        description: "",
        questions: [],
        id: 0,
        updatePassword: false,
    },
    methods: {
        addQuestion: function () {
            this.questions.push({
                question: "",
                questionType: "",
                answer_1: "",
                answer_2: "",
                answer_3: "",
                answer_4: "",
                correct_answer: 1,
                id: general.uuidv4(),
            });
        },
        removeQuestion: function(index) {
            this.questions.splice(index, 1);
        },
        setCorrectAnswer(question, selected_answer) {
            return question = this.correct_answer = selected_answer;
        },
        validateQuestions: function() {
            let errors = "";
            if(!this.title)    { errors += "You must enter a quiz name\r\n"; }
            if(!this.password) { errors += "You must enter a quiz password\r\n"; }
            this.questions.forEach( (question, key) => {
                questionNum = key + 1;
                if(!question.question)     { errors  += `You must enter a question for ${questionNum}. \n`; }
                if(!question.questionType) { errors  += `You must select a question type for question ${questionNum}\n`; }
                else {
                  if(question.questionType == 'multiple-choice') {
                    if(!question.answer_1) { errors += `You need to enter something in answer 1 for question ${questionNum}\n`; }
                    if(!question.answer_2) { errors += `You need to enter something in answer 2 for question ${questionNum}\n`; }
                  } else if(question.questionType == 'text')  {

                  }
                }
            });
            this.errors = errors;

        },
        saveQuiz: function (){
          this.validateQuestions();
          if(!this.errors) {
            axios.post('/quiz/store', {
                title: this.title,
                password: this.password,
                description: this.description,
                questions: this.questions,
            })
            .then(function (response) {
                if(response.data.errors) {
                    this.errors = response.data.errors;
                }
                if(response.data.success) {
                    this.success = response.data.success;
                }
            }.bind(this))
            .catch(function (error) {
                console.log(error);
            }.bind(this));
          }
        },
        updateQuiz: function (){
            this.validateQuestions();
            if(!this.errors) {
              axios.post('/quiz/update/'+quizId, {
                title: this.title,
                password: this.password,
                description: this.description,
                  questions: this.questions,
              })
              .then(function (response) {
                  if(response.data.errors) {
                      this.errors = response.data.errors;
                  }
                  if(response.data.success) {
                      this.success = response.data.success;
                  }
              }.bind(this))
              .catch(function (error) {
                  console.log(error);
              }.bind(this));
            }
          },
        loadQuiz: function (quizId) {
            axios.post('/quiz/api-show/'+quizId)
            .then(function (response) {
                console.log(response.data.questions);
                this.title       = response.data.quiz.name;
                this.description = response.data.quiz.description;
                this.id          = response.data.quiz.id;
                this.questions   = response.data.questions;

            }.bind(this))
            .catch(function (error) {
                console.log(error);
            }.bind(this));
        }

    },
    mounted() {
        if(quizId) {
            this.loadQuiz(quizId);
        }
    
      }
});
