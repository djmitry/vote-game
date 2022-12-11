<template>
  <div class="alert alert-primary" v-if="message">{{ message }}</div>
  <button @click="showForm = !showForm; message = ''" v-if="!showForm" class="btn btn-primary mb-3">Create vote</button>
  <div class="container" v-if="showForm">
    <div class="row">
      <div class="col-md-4">
        <form @submit.prevent="addVote" method="post" action="/vote/create">
          <div class="mb-2">
            <label for="">Title</label>
            <input type="text" v-model="title" placeholder="Title" class="form-control">
          </div>
          <div class="mb-2">
            <label for="">Description</label>
            <input type="text" v-model="description" placeholder="Description" class="form-control">
          </div>
          <div class="mb-2">
            <label for="">Bet</label>
            <input type="number" v-model="bet" placeholder="Bet" class="form-control">
          </div>
          <div class="mb-2">
            <label>BetCondition</label>
            <div class="form-check">
              <input type="radio" id="Like" :value="1" v-model="betCondition" class="form-check-input">
              <label for="Like" class="form-check-label">Like</label>
            </div>
            <div class="form-check">
              <input type="radio" id="Dislike" :value="2" v-model="betCondition" class="form-check-input">
              <label for="Dislike" class="form-check-label">Dislike</label>
            </div>
            <div class="form-check">
              <input type="radio" id="Equals" :value="3" v-model="betCondition" class="form-check-input">
              <label for="Equals" class="form-check-label">Equals</label>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Add</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>

defineProps({
  // title: String,
});
</script>


<script>
import axios from 'axios';

export default {
  methods: {
    addVote() {
      axios
          .post("/vote/create", {
            title: this.title,
            description: this.description,
            bet: this.bet,
            betCondition: this.betCondition,
          })
          .then((response) => {
            console.log(response)
            this.message = response.data.message;

            if (response.data.success) {
              this.showForm = false;
              this.title = '';
              this.description = '';
              this.bet = '';
              this.betCondition = '';
            }
          });
    },
  },
  data() {
    return {
      showForm: false,
      title: '',
      description: '',
      bet: '',
      betCondition: '',
      message: '',
    }
  }
}
</script>