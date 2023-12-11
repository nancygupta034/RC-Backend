import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/teams${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/teams/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/teams/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-teams`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/teams',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/teams/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/teams-update-status/${id}`,
    method: 'post',
    data,
  });
}
