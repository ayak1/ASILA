// api/ExtraServices.js

import api from './baseUrl';

// export const getExtraService = async (serviceId,lang) => {
//   try {
//     console.log("api",serviceId)
//     const response = await api.get(`/extra-services/${serviceId}`,{
//       headers: {
//         'Accept-Language': lang,
//       },
//     });
//     return response.data.extra_service;
//   } catch (error) {
//     console.error('Error fetching ExtraServices:', error);
//     throw error;
//   }
// };
export const getExtraService = async (slug,lang) => {
  try {
    const response = await api.get(`/extra-services/${slug}`,{
      headers: {
        'Accept-Language': lang,
      },
    });
    return response.data.extra_service;
  } catch (error) {
    console.error('Error fetching ExtraServices:', error);
    throw error;
  }
};



