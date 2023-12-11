import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/news${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/news/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/news/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-news`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/news',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/news/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/news-update-status/${id}`,
    method: 'post',
    data,
  });
}
