<!-- NavBar -->
<template>
  <div class="navBar">
    <nav class="main_container">
      <NavChangeLocale v-if="!isSmallSize"/>
      <div class="mb_size toggle-button" @click="toggleNav" :class="{ 'active': isNavOpen }">
        <span class="mb_size line1"></span>
        <span class="mb_size line2"></span>
        <span class="mb_size line3"></span>
      </div>
      <NavCallButton v-if="!isSmallSize"/>
      <ul class="desktop_size " :class="[{ 'sideBar': isNavOpen },{ 'rtl': $isRTL(), 'ltr': !$isRTL() }]">
        <li @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/')">
            {{ $t('nav_home') }}
          </NuxtLink>
        </li>
        <li>
          <NavDropdownList :title="$t('nav_destination')" :list="cities" />
        </li>
        <li  @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/blog')">
            {{$t('nav_blog')}}
          </NuxtLink>
        </li>
        <li  @click="toggleNav">
          <NuxtLink class="nav_item" :to="localePath('/about')">
            {{$t('nav_about')}}
          </NuxtLink>
        </li>
        <li v-if="isSmallSize">
          <NavChangeLocale />
        </li>
        <li v-if="isSmallSize">
          <NavCallButton/>
        </li>
      </ul>
      <NuxtLink class="logo" :to="localePath('/')">
        <img src="@/assets/imgs/logo.svg" alt="">
      </NuxtLink>

    </nav>
  </div>
</template>

<script>
import { mapMutations, mapActions, mapState } from 'vuex';

export default {
  data() {
    return {
      isNavOpen: false, // Track if the sidebar is open or closed
      isWindowLarge: false, // Track if the window size is larger than 1150 pixels
      isSmallSize: false // Track if the window size is smaller than or equal to 650 pixels
    };
  },
  computed: {
    isRTL() {
      return this.$i18n.locale === 'ar';
    },
    ...mapState('cities', ['cities'])
  },
  watch: {
    '$i18n.locale': function(newVal, oldVal) {
      this.fetchCities();
    },
    isWindowLarge(newValue) {
      if (newValue) {
        this.isNavOpen = false; // Close the navigation bar if the window size becomes larger than 1150 pixels
      }
    }
  },
  methods: {
    ...mapMutations('cities', ['setCities']),
    ...mapActions('cities', ['fetchCities']),
    toggleNav() {
      if (!this.isWindowLarge) {
        // Only toggle the navigation bar if the window size is not larger than 1150 pixels
        this.isNavOpen = !this.isNavOpen;
        if (this.isSmallSize) {
          // Do something specific for small screens
        }
      }
    },
    checkWindowSize() {
      if (process.client) {
        this.isWindowLarge = window.innerWidth > 1150;
        this.isSmallSize = window.innerWidth <= 650; // Update isSmallSize based on the window size
      }
    }
  },
  async created() {
    await this.fetchCities();
    this.checkWindowSize(); // Check window size on component creation
  },
  mounted() {
    if (process.client) {
      window.addEventListener('resize', this.checkWindowSize);
    }
    this.checkWindowSize(); // Check window size on component creation
  },
  beforeDestroy() {
    if (process.client) {
      window.removeEventListener('resize', this.checkWindowSize);
    }
  }
};
</script>


<style>

.navBar{
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9;
}
nav{
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: var(--color-1);
  position: relative;
}
nav ul{
  display: flex;
  justify-content: center;
  align-items: center;
  column-gap: 4vw;
}
.nav_item{
  color:var(--color-3);
  font-size: var(--fs_xxxs_800);
  font-weight: 800;
  line-height: 150%;
  letter-spacing: -0.414px;

}

.toggle-button {
  display: block; /* Show the toggle button */
  cursor: pointer;
}

.toggle-button.active .line1 {
  transform: rotate(-45deg) translate(-4px, 8px);
}

.toggle-button.active .line2 {
  opacity: 0;
}

.toggle-button.active .line3 {
  transform: rotate(45deg) translate(-3px, -7px);
}
.toggle-button span {
  display: block;
  background-color: var(--color-4);
  width: 25px;
  height: 3px;
  margin: 5px auto;
  transition: transform 0.3s, opacity 0.3s; /* Add transition for smooth animation */
}
.mb_size{
  display: none;
}
@media (max-width:1500px){

}
@media (max-width:1200px){

}
@media (max-width:1150px){
  nav{
    position: relative;
  }
  .mb_size{
    display: block;
  }
  .desktop_size{
    display: none;
  }
  .nav_item{
    color:var(--primary_light_color);
    font-size: var(--fs_xs_800);
    font-weight: 800;
    line-height: 150%;
    letter-spacing: -0.414px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .toggle-button{
    position: absolute;
    left: 50px;
  }

}
@media (max-width: 800px) {
  .toggle-button{
    left: 20px;
  }
}
@media (max-width: 650px) {
  .toggle-button{
    position: static;
    left: 0;
  }
}
.sideBar {
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  flex-direction: column;
  gap: 20px;
  width: 300px; /* Adjust the width as needed */
  height: 100vh;
  position: fixed;
  top: 8vh;
  left: 0;
  background-color: var(--color-1); /* Light gray background color */
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle box shadow */
  padding: 20px;
  padding-top: 100px;
  z-index: 10; /* Ensure the sidebar is above other elements */
}
.sideBar li{
  margin: 0 20px;
}
.sideBar .nav_item{
  color: var(--color-3);
}
.sideBar .change_lang_container{
  position: static;
}
@media (max-width: 350px) {
  .sideBar{
    width: 100%;
  }
}
</style>
