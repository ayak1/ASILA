// api/areas.js

import api from './baseUrl';

export const getPopularArea = async (cityId,lang) => {
  try {
    const response = await api.get(`/areas/popular/${cityId}`,{
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.popularAreas;
  } catch (error) {
    console.error('Error fetching popular areas:', error);
    throw error;
  }
};



