/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const todoRoutes = {
  path: '/todo',
  component: Layout,
  redirect: '/todo/groups',
  name: 'To do',
  alwaysShow: true,
  meta: {
    title: 'todo',
    icon: 'skill',
    permissions: ['view menu task'],
  },
  children: [
    {
      path: 'groups',
      component: () => import('@/views/todo/groups/List'),
      name: 'ListGroup',
      meta: { title: 'groups', icon: 'peoples', permissions: ['manage task'] },
    },
    {
      path: 'tasks',
      component: () => import('@/views/todo/tasks/List'),
      name: 'ListGroup',
      meta: { title: 'tasks', icon: 'clipboard', permissions: ['manage task'] },
    },
  ],
};

export default todoRoutes;
