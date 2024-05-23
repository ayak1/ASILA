<template>
  <h2 class="sectionTitle" :class="{ 'rtl': isRTL, 'ltr': !isRTL }">{{title}}</h2>

</template>
<script>
export default {
  props:{
    title:String
  },
  computed:{
    isRTL() {
      return this.$i18n.locale === 'ar';
    },
  },
}
</script>
<style>
.sectionTitle{
  margin-top: 80px;
}
</style>
