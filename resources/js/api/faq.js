import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/faqs${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/faqs/${id}`,
    method: 'get',
  });
}

export function deleteRecord(id) {
  return request({
    url: `/api/faqs/${id}`,
    method: 'delete',
  });
}


export function deleteMultiple(data) {
  return request({
    url: `/api/remove-faq`,
    method: 'post',
    data
  });
}

export function create(data) {
  return request({
    url: '/api/faqs',
    method: 'post',
    data,
  });
}

export function update(data, id) {
  return request({
    url: `/api/faq/${id}`,
    method: 'post',
    data,
  });
}

export function updateStatus(data, id) {
  return request({
    url: `/api/faq-update-status/${id}`,
    method: 'post',
    data,
  });
}
