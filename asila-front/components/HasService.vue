<template>
  <div class="it_has_container" >
    <div class="image_wrapper border-ra-10">
      <slot></slot>
    </div>
    <span class="content">{{content}}</span>
    <span class="text smallTextColorGradient">{{text}}</span>
  </div>
</template>
<script>
export default {
  props:{
    content:Number,
    text:String
  },
  computed: {
    isRTL() {
      return this.$i18n.locale === 'ar';
    },
    },
}
</script>

<style >
.it_has_container{
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 4px;
}
.image_wrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 5px;
  background: var(--primary_light_color);
}
.it_has_container .image_wrapper svg path{
  width: 24px;
  height: 24px;
  fill: var(--color-4);
}
.it_has_container .content{
  font-size: var(--fs_xs_700);
  color: var(--color-4);
  font-weight: 700;
}
@media (max-width:1500px){
  .it_has_container .image_wrapper svg{
    width: 20px;
    height: 20px;
  }
}
@media (max-width:1000px){
  .it_has_container .image_wrapper svg{
    width: 16px;
    height: 16px;
  }
}
</style>
