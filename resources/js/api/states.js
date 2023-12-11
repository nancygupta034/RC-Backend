import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/states${query}`,
    method: 'get',
  });
}

export function fetchState(id) {
  return request({
    url: `/api/states/${id}`,
    method: 'get',
  });
}

export function deleteState(id) {
  return request({
    url: `/api/states/${id}`,
    method: 'delete',
  });
}


export function deleteMultipleState(data) {
  return request({
    url: `/api/remove-states`,
    method: 'post',
    data
  });
}

export function createState(data) {
  return request({
    url: '/api/states',
    method: 'post',
    data,
  });
}

export function updateState(data, id) {
  return request({
    url: `/api/states/${id}`,
    method: 'put',
    data,
  });
}
