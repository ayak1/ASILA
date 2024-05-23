// api/hotels.js
import api from './baseUrl';

export const getHotel = async (hotelId,lang) => {
  try {
    const response = await api.get(`/hotels/${hotelId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    console.log(response.data.hotel)
    return response.data.hotel;
  } catch (error) {
    console.error('Error fetching hotel by ID:', error);
    throw error;
  }
};

export const getHotelsByCity = async (cityId,lang) => {
  try {
    const response = await api.get(`/hotels/in_city/${cityId}`,{
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.hotels;
  } catch (error) {
    console.error('Error fetching hotels by city:', error);
    throw error;
  }
};
