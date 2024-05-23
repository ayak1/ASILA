<template>
  <div class="drop_down" >
    <p class="drop_down_title nav_item" @click="toggleDropDown">
      {{ title }}
      <svg :class="{ rotated: isShowen, unrotated: !isShowen }" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 16L3 7H21L12 16Z" fill="#000"/>
      </svg>
    </p>
    <div :class="{ show: isShowen, hide: !isShowen }" v-show="isShowen" class="drop_down_list border-ra-10" @click.stop>
      <p v-for="item in list" :key="item.id" @click="selectCity(item)" class="pointer">
          {{ item.name }}
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions, mapMutations } from 'vuex';

export default {
  props: {
    title: String,
    list: Array
  },
  data() {
    return {
      isShowen: false,
    };
  },
  methods: {
    ...mapMutations('cities', ['setSelectedCity']),
    ...mapActions('cities', ['fetchSelectedCity']),
    toggleDropDown(event) {
      this.isShowen = !this.isShowen;
    },
    async selectCity(city) {
      await this.fetchSelectedCity(city);
      this.toggleDropDown();
      this.$parent.toggleNav();
      const locale = this.$i18n.locale;
      this.$router.push({
        path: `/${locale}/${city.name.toLowerCase()}`,
        query: { cityId: city.id },
      });
    },
    closeDropDownOnOutsideClick(event) {
      if (!this.$el.contains(event.target) && this.isShowen) {
        this.isShowen = false;
      }
    },
    closeDropDownOnScroll() {
      if (this.isShowen) {
        this.isShowen = false;
      }
    },
  },
  mounted() {
    if(process.client){
      window.addEventListener('click', this.closeDropDownOnOutsideClick);
      window.addEventListener('scroll', this.closeDropDownOnScroll);
    }
  },
  beforeDestroy() {
    if(process.client){
      window.removeEventListener('click', this.closeDropDownOnOutsideClick);
      window.removeEventListener('scroll', this.closeDropDownOnScroll);
    }
  },
};
</script>
<style>
.rotated {
  transform: rotate(180deg);
  transition: ease-in-out 0.5s;
}
.unrotated {
  transform: rotate(0deg);
}
.show {
  opacity: 1;
  transition: opacity 5s ease-in-out;
  z-index: 9;
}
.hide {
  opacity: 0;
  transition: opacity 1.5s ease-in-out;
}
.drop_down {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  position: relative;
}
.drop_down .drop_down_title {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 4px;
  cursor: pointer;
}
.drop_down .drop_down_list {
    position: absolute;
    top: calc(100% + 5px);
    z-index: 1000;
    background-color: var(--color-1);
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 25px;
}
.drop_down .drop_down_list p {
  font-size: var(--fs_xs_500);
  font-weight: 500;
  letter-spacing: -0.552px;
  text-align: center;
  color: var(--color-3);
}
@media (max-width:1150px) {
  .drop_down .drop_down_list {
    position: static;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    padding: 0px;
    background: var(--primary_light_color);
    width: 100%;
    box-shadow: 0px 0px 0px 0px rgba(0,0,0,0.2);
  }
  .drop_down .drop_down_list p {
    color: var(--color-2);
    font-size: var(--fs_xs_700);
    font-weight: 700;
    padding: 10px;
  }
}
@media (max-width: 1000px) {
  .drop_down svg{
    width: 16px;
    height: 16px;
  }
}
</style>
