<template>
  <div class="mine" @click="click()">
    <div class="score" v-show="score">{{ score }}</div>
  </div>
</template>

<script>
export default {
  name: "Mine",
  notifier: null,
  props: {
    token: String,
  },
  data() {
    return {
      score: null,
    }
  },
  mounted() {
    this.notifier = this.createNotifier();
  },
  methods: {
    createNotifier() {
      let parent = this;
      let notif = new WebSocket("ws://localhost:6001");

      notif.onmessage = function (event) {
        console.log(event.data);

        if (!event.data) {
          return;
        }

        let data = JSON.parse(event.data);
        if (data.score) {
          parent.score = data.score;
        } else if (data.error === 'Hp is zero.') {
          setTimeout(() => {
            location.href = '/';
          }, 3000);
        }
      }

      notif.onerror = function (error) {
        console.log(error);
      }

      return notif;
    },
    click() {
      this.notifier.send(JSON.stringify({token: this.token}));
    },
  }
}
</script>

<style scoped>
.mine {
  background-image: url('../images/mine.jpg');
  width: 100%;
  height: 100vh;
  background-size: cover;
  display: flex;
  flex-flow: row;
  align-items: center;
  justify-content: center;
}
.score {
  background: #d5d500;
  font-size: 50px;
  font-weight: bold;
  padding: 20px 40px;
  opacity: 0.7;
  user-select: none;
}
</style>