import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/mental-health${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/mental-health/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/mental-health/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-mental-health`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/mental-health',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/mental-health/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/mental-health-update-status/${id}`,
    method: 'post',
    data,
  });
}
