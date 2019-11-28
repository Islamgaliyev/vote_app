<template>
    <div class="row">
        <div class="col-8">
            <vue-poll v-bind="options" @addvote="addVote" :key="componentKey" />
            <div class="col-12">
                <span>Question by: {{this.question.user.email}}</span>
                <a class="btn-sm btn-success" :href="'/vote/results/'+this.questionId">Show results</a>
                <button class="btn-sm btn-danger" v-on:click="unvote">Unvote</button>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-default">
                <div class="card-header">Users online</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index">
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VuePoll from 'vue-poll'

    export default {     
        props:['question', 'is_voted'],

        data() {
            return {
                componentKey: 0,
                answers: [],
                questionId: this.question.id,
                options: {
                    question: this.question.title,
                    answers: [],
                    showResults: false,
                    customId: 1
                },
                
                users: []
            }
        },
        created() {
            this.buildVoteResult(this.question)
            this.isShowResults()

            Echo.join('vote')
                .here(user => {
                    this.users = user;
                })
                .joining(user => {

                    this.users.push(user);
                })
                .leaving(user => { 
                     this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('UserVotedEvent',(event) => {
                    this.buildVoteResult(event.question)
                })
        },
        components: {
            VuePoll
        },
        methods: {
            addVote(obj){
                axios.post('/vote', {optionId: obj.value, questionId: this.questionId})
                    .then((response) => {
                        console.log(response.data);
                    }).catch((error) => {
                        console.log(error);
                    });
            },
            buildVoteResult(question) {
                let options = question.options;
                this.options.answers = []
                options.forEach(element => {
                    this.options.answers.push({value:element.id, text: element.title, votes: element.votes})
                });
            },
            isShowResults() {
                if (this.is_voted) {
                    this.options.showResults = true
                } 
            },
            unvote() {
                axios.post('/unvote', {questionId: this.questionId})
                    .then((response) => {
                        location.reload()
                    }).catch((error) => {
                        console.log(error);
                    });

            },
        },
    }
</script>