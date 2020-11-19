import Layout from '@/layout';

const childTaskRoutes = {
  path: '/childtask',
  component: Layout,
  redirect: '/childtask/list',
  name: 'childTask',
  alwaysShow: true,
  meta: {
    title: 'childTask',
    icon: 'tree',
    permission: ['edit-task'],
  },
  children: [
    {
      path: '/list',
      component: () => import('@/views/child-task/List'),
      meta: {
        title: 'child_task',
        icon: 'skill',
        permission: ['edit-task'],
      },
    },
    {
      path: '/prenttasks',
      component: () => import('@/views/child-task/components/ListParentTask'),
      meta: {
        title: 'Parent task',
        icon: 'skill',
        permission: ['edit-task'],
      },
    },
  ],
};

export default childTaskRoutes;
