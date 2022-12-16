<template>
  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-label="Animated striped example" :aria-valuenow="now" aria-valuemin="0" :aria-valuemax="max" :style="{'width': width() + '%'}"></div>
  </div>
</template>

<script>
export default {
  name: 'HP',
  props: {
    currentHp: Number,
    maxHp: Number,
    token: Number,
  },
  mounted() {
    const url = new URL('http://localhost:49150/.well-known/mercure');
    url.searchParams.append('topic', 'user/hp/' + this.token);
    console.log(url)
    const es = new EventSource(url);
    es.onmessage = e => {
      const data = JSON.parse(e.data);
      this.now = data.currentHp
    }
  },
  data() {
    return {
      now: this.currentHp,
      max: this.maxHp,
    }
  },
  methods: {
    width() {
      return this.now / (this.max / 100);
    }
  }
}
</script>

<style scoped>
  .progress {
    width: 200px;
  }
</style>