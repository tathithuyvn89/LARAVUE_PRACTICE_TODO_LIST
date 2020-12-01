import request from '@/utils/request';

export function fetchListTask() {
  return request({
    url: '/childTasks',
    method: 'get',

  });
}

export function fetchChildTask(id) {
  return request({
    url: '/childTasks/' + id,
    method: 'get',
  });
}
export function createChildTask(data) {
  return request({
    url: '/childTasks',
    method: 'post',
    data: data,
  });
}
export function updateChildTask(data, id) {
  return request({
    url: '/childTasks/' + id,
    method: 'put',
    data: data,
  });
}
export function forceDeleteChildTask(id) {
  return request({
    url: '/childTasks/' + id,
    method: 'delete',
  });
}
