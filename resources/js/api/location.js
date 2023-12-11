import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/locations${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/locations/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/locations/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-locations`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/locations',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/locations/${id}`,
    method: 'put',
    data,
  });
}
