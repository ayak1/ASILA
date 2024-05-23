// api/restaurants.js
import api from './baseUrl';

export const getRestaurant = async (restaurantId,lang) => {
  try {
    const response = await api.get(`/restaurants/${restaurantId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.restaurant;
  } catch (error) {
    console.error('Error fetching restaurant by ID:', error);
    throw error;
  }
};

export const getRestaurantsByCity = async (cityId,lang) => {
  try {
    const response = await api.get(`/restaurants/in_city/${cityId}`,{
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.restaurants;
  } catch (error) {
    console.error('Error fetching restaurants by city:', error);
    throw error;
  }
};
