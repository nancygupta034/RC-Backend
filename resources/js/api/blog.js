import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/blogs${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/blogs/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/blogs/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-blogs`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/blogs',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/blogs/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/blog-update-status/${id}`,
    method: 'post',
    data,
  });
}
