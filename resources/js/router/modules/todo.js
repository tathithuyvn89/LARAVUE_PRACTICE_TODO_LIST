/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const todoRoutes = {
  path: '/setuptask',
  component: Layout,
  redirect: '/todo/groups',
  name: 'To do',
  alwaysShow: true,
  meta: {
    title: 'admin_setup',
    icon: 'skill',
    permissions: ['view menu task'],
  },
  children: [
    {
      path: 'groups',
      component: () => import('@/views/todo/groups/List'),
      name: 'ListGroup',
      meta: { title: 'Group', icon: 'peoples', permissions: ['manage task'] },
    },
    {
      path: 'tasks',
      component: () => import('@/views/todo/tasks/List'),
      name: 'ListTask',
      meta: { title: 'Task', icon: 'clipboard', permissions: ['manage task'] },
    },
    {
      path: 'tasks/create',
      component: () => import('@/views/todo/tasks/CreateTask'),
      name: 'CreateTask',
      meta: { title: 'Create Task', icon: 'plus', permissions: ['manage task'] },
      hidden: true,
    },
    {
      path: 'tasks/edit/:id',
      component: () => import('@/views/todo/tasks/EditTask'),
      name: 'EditTask',
      meta: { title: 'Edit Task', icon: 'plus', permissions: ['manage task'] },
      hidden: true,
    },
    // {
    //   path: 'childtask',
    //   component: () => import('@/views/child-task/List'),
    //   name: 'UserEditTask',
    //   meta: { title: 'Edit Task For User', icon: 'plus', permissions: ['edit-task'] },
    //   hidden: false,
    // },
  ],
};

export default todoRoutes;
