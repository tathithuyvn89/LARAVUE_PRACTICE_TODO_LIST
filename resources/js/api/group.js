import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/groups',
    method: 'get',
    params: query,
  });
}
export function fetchGroup(id) {
  return request({
    url: '/groups/' + id,
    method: 'get',
  });
}
export function createGroup(data) {
  return request({
    url: '/groups',
    method: 'post',
    data: data,
  });
}
export function updateGroup(data, id) {
  return request({
    url: '/groups/' + id,
    method: 'put',
    data: data,
  });
}
export function forceDelete(data) {
  return request({
    url: '/groups/delete',
    method: 'delete',
    data: data,
  });
}
