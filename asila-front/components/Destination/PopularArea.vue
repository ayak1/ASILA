<template>
    <div class="popular_area">
        <div class="imageWrapper "  v-for="(image,index) in popularArea.images" :key="index">
        <img v-if="image.is_cover" :src="image.path" :alt="popularArea.name">
      </div>
      <p>{{popularArea.name}}</p>
    </div>
</template>

<script>
export default{
  props:{
    popularArea:Object,
  }
}
</script>
<style>
  .popular_area{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    row-gap: 24px;
    border-radius: 50%;
  }
  .popular_area .imageWrapper{
    width: 174px;
    height: 174px;
    border-radius: 50%;
  }
  .popular_area .imageWrapper img {
    border: solid 4px transparent;
    border-radius: 50%;
    background-image: linear-gradient(white, white),
                      linear-gradient(90deg, rgba(191,149,63,1) 0%, rgba(252,246,186,1) 18%, rgba(179,135,40,1) 31%, rgba(179,135,40,1) 75%, rgba(251,245,183,1) 87%, rgba(170,119,28,1) 98%);
    background-origin: border-box;
    background-clip: content-box, border-box;
    width: 100%;
    height: 100%;
  }
  .popular_area p{
    font-size: 1.25rem;
    font-weight: 800;
  }
  @media (max-width:800px){
    .popular_area .imageWrapper{
      width: 120px;
      height: 120px;
    }
  }
</style>
