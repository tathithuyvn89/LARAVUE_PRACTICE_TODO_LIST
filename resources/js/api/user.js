import request from '@/utils/request';
import Resource from '@/api/resource';

class UserResource extends Resource {
  constructor() {
    super('users');
  }
  tasksNotYetActiveByUserId(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/tasksNotActive',
      method: 'get',
    });
  }
  tasksNotCompleteByUserId(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/tasksNotComplete',
      method: 'get',
    });
  }
  tasksCompletedByUserId(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/tasksCompleted',
      method: 'get',
    });
  }
  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }
  listUser() {
    return request({
      url: '/userslist',
      method: 'get',
    });
  }

  updatePermission(id, permissions) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'put',
      data: permissions,
    });
  }
}

export { UserResource as default };

