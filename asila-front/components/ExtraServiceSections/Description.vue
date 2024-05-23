<template>
  <p  class="section_description" :class="{ 'rtl': isRTL, 'ltr': !isRTL }">{{description}}</p>

</template>
<script>
export default {
  props:{
    description:String
  },
  computed:{
    isRTL() {
      return this.$i18n.locale === 'ar';
    },
  },
}
</script>
<style>
.section_description{
  font-size: var(--fs_xs_500);
  font-weight: 500;
  margin-bottom: 56px;
}
</style>
