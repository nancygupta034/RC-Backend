import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/testing-requests${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/testing-requests/${id}`,
    method: 'get',
  });
}
