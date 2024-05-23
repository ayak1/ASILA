<template>
  <div class="day_details">
    <p class="day_number" :class="{ 'rtl': isRTL, 'ltr': !isRTL }">
      {{ $t('day') }} {{day_activities.day_number}}:
    </p>
    <h2 class="day_content">
      {{ day_activities.content }}
    </h2>
    <div class="day_activities" v-if="day_activities.activities.length!=0">
      <p class="includes_activities">
        {{ $t('includes_activities') }}
      </p>
      <ul>
        <li  v-for="(activity, index) in day_activities.activities" :key="index">{{activity.name}}</li>
      </ul>
      <div class="activity_imgs">
         <CardActivity :img="activity.image" :name="activity.name" v-for="(activity, index) in day_activities.activities" :key="index"/>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props:{
    day_activities: Object
  },
  computed:{
    isRTL() {
    return this.$i18n.locale === 'ar';
  },
  }
}
</script>
<style>
.day_details{
  margin-bottom: 40px;
}
.day_number{
  color: var(--primary_dark_color);
  font-size: var(--fs_s_500);
  font-weight: 500;
  line-height: 180%;
  letter-spacing: -0.92px;
  margin-bottom: 12px;
}
.day_content{
  color: var(--primary_dark_color);
  font-size: var(--fs_xs_500);
  font-weight: 500;
  line-height: 180%;
  letter-spacing: -0.69px;
}
.day_details .day_activities{
  display: flex;
  flex-direction: column;
}
.day_details .day_activities .includes_activities{
  color: var(--primary_dark_color);
  font-size: var(--fs_xs_500);
  font-weight: 700;
  line-height: 180%;
  letter-spacing: -0.69px;
  margin-top: 8px;
}
.day_details .day_activities ul{
  display: flex;
  align-items: center;
  gap: 89px;
  margin-right: 68px;
  margin-left: 68px;
  list-style-type: disc;
  margin-top: 15px;
  margin-bottom: 45px;
}
.day_details .day_activities ul li{
  color: var(--primary_dark_color);
  font-size: var(--fs_xs_500);
  font-weight: 500;
  line-height: 180%;
  letter-spacing: -0.69px;
}
.day_details .activity_imgs{
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
}
</style>
