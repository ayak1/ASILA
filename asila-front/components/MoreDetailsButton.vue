<!-- components/MoreDetailsButton.vue -->
<template>
    <div @click="emitRouteToDetails" class="pointer border-ra-10 drop-shadow button_container">
      <p>{{$t('more_details_btn')}}</p>
    </div>
</template>

<script>
export default {
  methods: {
    emitRouteToDetails() {
      // Emitting a custom event named 'details-clicked'
      this.$emit('button-clicked');
    }
  }

}
</script>

<style>
.button_container{
  cursor: pointer;
  margin-top: 25px;
  margin-bottom: 12px;
  width: fit-content;
  background: var(--color-1);
}
.button_container p{
  padding: 6px 16px;
  color: var(--color-2);
  letter-spacing: -0.414px;
  font-weight: 700;
  font-size: var(--fs_xxs_700);
}
</style>
