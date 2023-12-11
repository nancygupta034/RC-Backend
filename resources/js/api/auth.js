import request from '@/utils/request';

export function login(data) {
  return request({
    url: '/auth/login',
    method: 'post',
    data: data,
  });
}

export function updatePassword(data) {
  return request({
    url: '/api/auth/update-password',
    method: 'post',
    data: data,
  });
}

export function getInfo() {
  return request({
    url: '/api/auth/user',
    method: 'get',
  });
}

export function logout() {
  return request({
    url: '/api/auth/logout',
    method: 'post',
  });
}

export function csrf() {
  return request({
    url: '/sanctum/csrf-cookie',
    method: 'get',
  });
}
