import Layout from '@/layout';

const childTaskRoutes = {
  path: '/childtask',
  component: Layout,
  redirect: '/childtask/list',
  name: 'childTask',

  meta: {
    title: 'childTask',
    icon: 'tree',
    permission: ['edit-task'],
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/child-task/List'),
      meta: {
        title: 'child_task',
        icon: 'skill',
        permission: ['edit-task'],
      },
    },
    {
      path: 'prenttasks',
      component: () => import('@/views/child-task/components/ListParentTask'),
      meta: {
        title: 'Parent task',
        icon: 'skill',
        permission: ['edit-task'],
      },
    },
    {
      path: 'prenttasks/:id',
      component: () => import('@/views/child-task/ListChildTaskByParentTask'),
      meta: {
        title: 'Child task by parent',
        icon: 'skill',
        permission: ['edit-task'],
      },
      hidden: true,
    },
    {
      path: 'edit/:id',
      component: () => import('@/views/child-task/EditChildTask'),
      meta: {
        title: 'Child Task Create',
        icon: 'skill',
        permission: ['edit-task'],
      },
      hidden: true,
    },

  ],
};

export default childTaskRoutes;
