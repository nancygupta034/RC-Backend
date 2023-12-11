import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/testing-types${query}`,
    method: 'get',
  });
}

export function fetchTestingType(id) {
  return request({
    url: `/api/testing-types/${id}`,
    method: 'get',
  });
}

export function deleteTestingType(id) {
  return request({
    url: `/api/testing-types/${id}`,
    method: 'delete',
  });
}


export function deleteMultipleTestingType(data) {
  return request({
    url: `/api/remove-testing-types`,
    method: 'post',
    data
  });
}

export function createTestingType(data) {
  return request({
    url: '/api/testing-types',
    method: 'post',
    data,
  });
}

export function updateTestingType(data, id) {
  return request({
    url: `/api/testing-types/${id}`,
    method: 'put',
    data,
  });
}
