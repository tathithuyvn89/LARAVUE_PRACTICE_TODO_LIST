import UserResource from '@/api/user';
import store from '@/store';
const userResource = new UserResource();

const state = {
  GET_ALL_TASK_NEED_ACTIVE: 0,
};

const mutations = {
  GET_ALL_TASK_NEED_ACTIVE: (state, totalTaskNeedActive) => {
    state.GET_ALL_TASK_NEED_ACTIVE = totalTaskNeedActive;
  },
};

const actions = {
  listTasksById({ commit }) {
    const userId = store.getters.userId;
    userResource.get(userId).then(result => {
      const tasksList = result.data.tasks;
      const tasks = tasksList.filter(task => task.active === 0);
      commit('GET_ALL_TASK_NEED_ACTIVE', tasks.length);
    }).catch(error => {
      throw new Error(`API ${error}`);
    });
  },
};

// return new Promise((resolve, reject) => {
//   userResource.get(userId)
//     .then(response => {
//       const { result } = response;
//       if (!result) {
//         reject('Cannot get Data');
//       }
//       console.log('This is console log trong state', result);
//       const tasks = result.data.tasks;
//       const totalTaskNeedActive = tasks.filter(task => task.active === 0);
//       commit('GET_ALL_TASK_NEED_ACTIVE', totalTaskNeedActive.length);
//       const getAllTaskNotCompleteById = tasks.filter(task => task.complete_date === null);
//       commit('GET_ALL_TASK_NOT_COMPLETE', getAllTaskNotCompleteById);
//       const getAllTaskCompleteById = tasks.filter(task => task.complete_date !== null);
//       commit('GET_ALL_TASK_COMPLETE', getAllTaskCompleteById);
//       resolve(result);
//     }).catch(error => {
//       reject(error);
//     });
// });
// },

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
