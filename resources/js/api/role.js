import request from '@/utils/request';

export function fetchRoles() {
  return request({
    url: `/api/roles`,
    method: 'get',
  });
}
