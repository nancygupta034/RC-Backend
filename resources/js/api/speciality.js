import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/specialities${query}`,
    method: 'get',
  });
}

export function fetchSpeciality(id) {
  return request({
    url: `/api/specialities/${id}`,
    method: 'get',
  });
}

export function deleteSpeciality(id) {
  return request({
    url: `/api/specialities/${id}`,
    method: 'delete',
  });
}


export function deleteMultipleSpeciality(data) {
  return request({
    url: `/api/remove-specialities`,
    method: 'post',
    data
  });
}

export function createSpeciality(data) {
  return request({
    url: '/api/specialities',
    method: 'post',
    data,
  });
}

export function updateSpeciality(data, id) {
  return request({
    url: `/api/specialities/${id}`,
    method: 'put',
    data,
  });
}
