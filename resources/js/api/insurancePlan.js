import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/insurance-plans${query}`,
    method: 'get',
  });
}


