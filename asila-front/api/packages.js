// api/packages.js

import api from './baseUrl';

export const getPackagesByCity = async (cityId, page = 1, lang) => {
  try {
    const response = await api.get(`/packages/in_city/${cityId}?page=${page}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.packages;
  } catch (error) {
    console.error('Error fetching packages by city:', error);
    throw error;
  }
};
export const getPackage = async (packageId,lang) => {
  try {
    const response = await api.get(`/packages/${packageId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.package;
  } catch (error) {
    console.error('Error fetching package by ID:', error);
    throw error;
  }
};


// const response = await $axios.$get(`/your-api-endpoint/${params.id}`, {
//   headers: {
//     lang: lang,
//   },
// });
