// api/apartments.js

import api from './baseUrl';

export const getApartmentsForSale = async (lang) => {
  try {
    const response = await api.get('/apartments/for-sell', {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.apartments;
  } catch (error) {
    console.error('Error fetching apartments:', error);
    throw error;
  }
};
export const getApartmentsForRent = async (lang) => {
  try {
    const response = await api.get('/apartments/for-rent', {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.apartments;
  } catch (error) {
    console.error('Error fetching apartments:', error);
    throw error;
  }
};

export const getApartment = async (apartmentId,lang) => {
  try {
    const response = await api.get(`/apartments/${apartmentId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.apartment;
  } catch (error) {
    console.error('Error fetching apartment by ID:', error);
    throw error;
  }
};
