import request from '@/utils/request';

export function fetchListTask(query) {
  return request({
    url: '/tasks',
    method: 'get',
    params: query,
  });
}
export function fetchAll() {
  return request({
    url: '/list',
    method: 'get',

  });
}
export function fetchTask(id) {
  return request({
    url: '/tasks/' + id,
    method: 'get',
  });
}
export function createTask(data) {
  return request({
    url: '/tasks',
    method: 'post',
    data: data,
  });
}
export function updateTask(data, id) {
  return request({
    url: '/tasks/' + id,
    method: 'put',
    data: data,
  });
}
export function forceDelete(data) {
  return request({
    url: '/tasks/delete',
    method: 'delete',
    data: data,
  });
}
