import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/states-covered${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/states-covered/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/states-covered/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-states-covered`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/states-covered',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/states-covered/${id}`,
    method: 'put',
    data,
  });
}
