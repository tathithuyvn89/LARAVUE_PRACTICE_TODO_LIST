import request from '@/utils/request';

export function fetchListTask() {
  return request({
    url: '/childTasks',
    method: 'get',

  });
}

export function fetchTask(id) {
  return request({
    url: '/childTasks/' + id,
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
    url: '/childTasks/' + id,
    method: 'put',
    data: data,
  });
}
export function forceDelete(id) {
  return request({
    url: '/childTasks/' + id,
    method: 'delete',
  });
}
