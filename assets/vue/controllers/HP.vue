<template>
  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-label="Animated striped example" :aria-valuenow="now" aria-valuemin="0" :aria-valuemax="max" :style="{'width': width + '%'}"></div>
  </div>
</template>

<script>
export default {
  props: {
    currentHp: Number,
    maxHp: Number,
  },
  data() {
    return {
      now: this.currentHp,
      max: this.maxHp,
      width: this.currentHp / (this.maxHp / 100),
    }
  }
}
</script>

<style scoped>
  .progress {
    width: 200px;
  }
</style>