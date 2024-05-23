<template>
  <div class="hero-slider">
    <carousel
      :autoplay="true"
      :autoplay-timeout="1000"
      :loop="true"
      :pagination-enabled="true"
      :items-per-page="1"
      >
      <!-- :navigation-enabled="true" -->
      <!-- Repeat the following slide structure for each slide -->
      <slide  v-for="(slide, index) in slides" :key="index">
        <div class="slide-content">
          <div  class="top-of-image topImage"></div>
          <img :src="slide.image" alt="Slide Image" class="slide-image top-of-image" />
          <div class="slide-text">
            <h2>{{ slide.title }}</h2>
            <p>{{ slide.description }}</p>
          </div>
        </div>
      </slide>
    </carousel>
  </div>
</template>

<script>
export default {
  data() {
    return {
    slides: [
      {
        image: require('@/assets/imgs/b242aca9b645dcf93d24e458ad3b83cc.jpg'), // Replace with your image paths
        title: 'Slide 1 Title',
        description: 'Slide 1 Description',
      },
      {
        image: require('@/assets/imgs/a74bcda0620a610b1c602aea72458039.jpg'),
        title: 'Slide 2 Title',
        description: 'Slide 2 Description',
      },
      {
        image: require('@/assets/imgs/9835aba0ddb185984b98720d8ba74c72.jpg'),
        title: 'Slide 3 Title',
        description: 'Slide 3 Description',
      },
      {
        image: require('@/assets/imgs/407dd4141b71c171aa11fb4571bdffbf.jpg'),
        title: 'Slide 4 Title',
        description: 'Slide 4 Description',
      },
      {
        image: require('@/assets/imgs/1.jpg'), // Replace with your image paths
        title: 'Slide 1 Title',
        description: 'Slide 1 Description',
      },
      {
        image: require('@/assets/imgs/9f5a3b2147fa4e3700f513130cc5ed9f.jpg'),
        title: 'Slide 2 Title',
        description: 'Slide 2 Description',
      },
      {
        image: require('@/assets/imgs/car_services.jpg'),
        title: 'Slide 3 Title',
        description: 'Slide 3 Description',
      },
      {
        image: require('@/assets/imgs/1.jpg'),
        title: 'Slide 4 Title',
        description: 'Slide 4 Description',
      },
      // Add more slides as needed
    ],

    };
  },
};
</script>

<style >
/* Add custom styling for your slider here */
.VueCarousel-pagination[data-v-438fd353] {
  width: 100%;
  text-align: center;
  position: absolute;
  bottom:30px;
}
.VueCarousel-navigation-next{
 right: 50px !important
}
.VueCarousel-navigation-prev{
 left: 50px !important
}
.VueCarousel-dot-container button{
  width: 20px !important;
  height: 20px !important;
}
.VueCarousel-dot--active{
  background-color: var(--color-4) !important;
}
.hero-slider {
  width: 100%;
  position: relative;
}
.slide_container{
  width: 100%;
}
.slide-content {
  position: relative;
  width: 100vw;
  height: 100vh;
}

.slide-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
}

.carousel-pagination {
  position: absolute;
  bottom: 20px; /* Adjust the distance from the bottom */
  left: 50%;
  transform: translateX(-50%);
}

.carousel-pagination button {
  margin: 0 5px;
}

.slide-text {
  position: absolute;
  z-index: 2;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #fff; /* Adjust text color */
  text-align: center;
}

.slide-text h2 {
  font-size: 2rem; /* Adjust title font size */
}

.slide-text p {
  font-size: 3rem; /* Adjust description font size */
}
.VueCarousel-dot-container button{
  width: 12px !important;
  height: 12px !important;
}
</style>
