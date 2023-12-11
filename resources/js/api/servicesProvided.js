import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/services-provided${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/services-provided/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/services-provided/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-services-provided`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/services-provided',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/services-provided/${id}`,
    method: 'put',
    data,
  });
}
