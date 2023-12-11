import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/insurances${query}`,
    method: 'get',
  });
}


