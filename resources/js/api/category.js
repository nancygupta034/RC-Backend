import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/categories${query}`,
    method: 'get',
  });
}

export function fetchBlogCategory(id) {
  return request({
    url: `/api/categories/${id}`,
    method: 'get',
  });
}

export function deleteCategories(id) {
  return request({
    url: `/api/categories/${id}`,
    method: 'delete',
  });
}


export function deleteMultipleCategories(data) {
  return request({
    url: `/api/remove-categories`,
    method: 'post',
    data
  });
}

export function createBlogCategory(data) {
  return request({
    url: '/api/categories',
    method: 'post',
    data,
  });
}

export function updateCategories(data, id) {
  return request({
    url: `/api/categories/${id}`,
    method: 'put',
    data,
  });
}
