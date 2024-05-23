// api/cars.js

import api from './baseUrl';

export const getCarServices = async (lang) => {
  try {
    const response = await api.get('/car_services', {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.carServices;
  } catch (error) {
    console.error('Error fetching cars:', error);
    throw error;
  }
};
