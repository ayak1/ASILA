// api/programs.js

import api from './baseUrl';

export const getProgramsByCity = async (cityId,lang) => {
  try {
    const response = await api.get(`/programs/in_city/${cityId}`, {
      headers: {
        'Accept-Language': lang,
      },
    }
    );
    return response.data.programs;
  } catch (error) {
    console.error('Error fetching programs:', error);
    throw error;
  }
};
export const getProgram = async (programId,lang) => {
  try {
    const response = await api.get(`/programs/${programId}`, {
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.program;
  } catch (error) {
    console.error('Error fetching program by ID:', error);
    throw error;
  }
};
