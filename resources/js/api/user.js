import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/users${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/users/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/users/${id}`,
    method: 'delete',
  });
}

export function deleteMultiple(data) {
  return request({
    url: `/api/remove-users`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/users',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/users/${id}`,
    method: 'put',
    data,
  });
}
