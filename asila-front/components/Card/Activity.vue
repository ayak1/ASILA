<template>
  <div class="activity_card" >
  <!-- <div class="activity_card" :class="[{'w-50':is_50},{'w-30': !is_50}]"> -->
    <img :src="img" :alt="name" class="border-ra-10" >
    <p class="img_description">{{name}}</p>
  </div>
</template>
<script>
export default {
  props:{
    img:String,
    name:String,
    is_50: {
      type:Boolean,
      default: false
    }
  }
}
</script>

<style scoped>
.activity_card{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  width: 100%;
}
/* .w-30{
  width: calc( 100% / 3  - 16px);
}
.w-50{
  width: calc(50% - 12px);
} */
.activity_card img{
  width: 100%;
  height: 277px;
  object-fit: cover;
  margin-bottom: 12px;
  border-radius: 10px;
}
.activity_card p{
  color: var(--color-2);
  font-size: 1rem;
  font-weight: 700;
  line-height: 180%; /* 28.8px */
  letter-spacing: -0.368px;
}
</style>
