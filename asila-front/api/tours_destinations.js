// api/tours_destinations.js
import api from './baseUrl';

export const getToursDestinations = async (lang) => {
  try {
    const response = await api.get(`/destinations/`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.destinations;
  } catch (error) {
    console.error('Error fetching toursDestinations by ID:', error);
    throw error;
  }
};
export const getDestination = async (destinationId,lang) => {
  try {
    const response = await api.get(`/destinations/${destinationId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.destinations;
  } catch (error) {
    console.error('Error fetching toursDestinations by ID:', error);
    throw error;
  }
};


export const getByCityAndDestinationType = async (cityId, destinationTypeId, lang) => {
  try {
    const response = await api.get(`/destinations/in_city/${cityId}/${destinationTypeId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data;
  } catch (error) {
    console.error('Error fetching data by city and destination type:', error);
    throw error;
  }
};

