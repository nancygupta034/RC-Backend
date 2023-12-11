import request from '@/utils/request';

export function fetch(query) {
  return request({
    url: `/api/page-data?type=${query}`,
    method: 'get',
  });
}

export function create(data) {
  return request({
    url: '/api/add-page',
    method: 'post',
    data,
  });
}
