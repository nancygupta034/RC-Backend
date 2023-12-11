import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/our-story${query}`,
    method: 'get',
  });
}

export function create(data) {
  return request({
    url: '/api/our-story',
    method: 'post',
    data,
  });
}
