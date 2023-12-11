import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/clinicians${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/clinicians/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/clinicians/${id}`,
    method: 'delete',
  });
}

export function deleteMultiple(data) {
  return request({
    url: `/api/remove-clinicians`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/clinicians',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/clinicians/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/update-status-clinicians/${id}`,
    method: 'put',
    data,
  });
}


