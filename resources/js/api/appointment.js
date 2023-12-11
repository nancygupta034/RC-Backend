import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: `/api/appointments${query}`,
    method: 'get',
  });
}

export function fetch(id) {
  return request({
    url: `/api/appointments/${id}`,
    method: 'get',
  });
}

export function getSlots(query) {
  return request({
    url: `/api/slot-list${query}`,
    method: 'get',
  });
}

export function rescheduleAppointment(data) {
  return request({
    url: '/api/reschedule-appointment',
    method: 'post',
    data,
  });
}

export function updateAppointment(data) {
  return request({
    url: '/api/update-appointment',
    method: 'post',
    data,
  });
}
