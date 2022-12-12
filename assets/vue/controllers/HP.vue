<template>
  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-label="Animated striped example" :aria-valuenow="now" aria-valuemin="0" :aria-valuemax="max" :style="{'width': width() + '%'}"></div>
  </div>
</template>

<script>
export default {
  props: {
    currentHp: Number,
    maxHp: Number,
    token: Number,
  },
  mounted() {
    const es = new EventSource('http://localhost:49150/.well-known/mercure?topic=' + encodeURIComponent('http://vote/user/' + this.token + '/hp'));
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