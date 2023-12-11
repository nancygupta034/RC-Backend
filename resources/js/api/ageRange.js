import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/age-ranges${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/age-ranges/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/age-ranges/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-age-ranges`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/age-ranges',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/age-ranges/${id}`,
    method: 'put',
    data,
  });
}
