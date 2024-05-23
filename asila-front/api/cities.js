// api/cities.js
import api from './baseUrl';

export const getCities = async (lang) => {
  try {
    const response = await api.get('/cities', {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.cities;

  } catch (error) {
    console.error('Error fetching cities:', error);
    throw error;
  }
};

export const getCityById = async (id,lang) => {
  try {
    const response = await api.get(`/cities/${id}`,{
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.city;
  } catch (error) {
    console.error('Error fetching city by ID:', error);
  }
};
