<template>
  <div class="child-task-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.keyword"
        placeholder="Name Group i18n"
        style="width: 200px"
        class="filter-item"
        clearable
        @keyup.enter.native="handleFilter"
      />
      <!-- <el-select
        v-model="listQuery.group"
        placeholder="Please choose group i18n"
        clearable
        style="width: 90px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in groups"
          :key="item.id"
          :label="item.name | uppercaseFirst"
          :value="item.name"
        />
      </el-select> -->
      <el-button
        type="primary"
        class="filter-item"
        icon="el-icon-search"
        @click="handleFilter"
      >
        Search i18n
      </el-button>

    </div>
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane
        label="Completed Task"
        name="first"
      >
        <TaskCompleted :tasks-completed="tasksCompleted" />
      </el-tab-pane>
      <el-tab-pane
        label="Not Complete Task"
        name="second"
      >
        <TaskNotComplete
          :tasks-not-complete="tasksNotComplete"
          @increase="increaseProccess"
          @decrease="decreaseProccess"
          @completeDate_input="updateCompleteDate"
        />
      </el-tab-pane>
      <el-tab-pane
        label="Task doing"
        name="third"
      >Task Not Complete table</el-tab-pane>
      <el-tab-pane
        label="Task complete"
        name="fourth"
      >Task Complete Table</el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import TaskCompleted from './components/TaskCompleted';
import TaskNotComplete from './components/TaskNotComplete';
import UserResoure from '@/api/user';
const userResource = new UserResoure();
import { updateTask } from '@/api/task';

export default {
  components: { TaskCompleted, TaskNotComplete },
  data() {
    return {
      activeName: 'first',
      tasksCompleted: [],
      tasksNotComplete: [],
      userId: this.$store.getters.userId,
      listQuery: {
        keyword: '',
        group: '',
      },
    };
  },
  created() {
    this.getTasksCompleted();
    this.getTasksNotComplete();
  },
  methods: {
    updateCompleteDate(dataFromChildComponent) {
      console.log('this is data tu con chuyen sang', dataFromChildComponent);
      updateTask({ complete_date: dataFromChildComponent.complete_date }, dataFromChildComponent.taskId).then((response) => {
        if (response.data !== null) {
          this.getTasksNotComplete();
          this.getTasksCompleted();
        } else {
          this.$message({
            message: 'Update fail, try again i18n',
            type: 'warning',
          });
        }
      });
    },
    decreaseProccess(dataFromChildComponent) {
      console.log('This is data from decrease proccess', dataFromChildComponent);

      updateTask({ progress: dataFromChildComponent.percentage }, dataFromChildComponent.taskId).then((response) => {
        console.log('This is reponse when edit active of task', response);
      });
      this.getTasksNotComplete();
      this.getTasksCompleted();
    },
    increaseProccess(dataFromChildComponent){
      console.log('This is data from increase proccess', dataFromChildComponent);
      updateTask({ progress: dataFromChildComponent.percentage }, dataFromChildComponent.taskId).then((response) => {
        console.log('This is reponse when edit active of task', response);
      });
      this.getTasksNotComplete();
    },
    async getTasksNotComplete(){
      const tasksNotCompleteData = await userResource.tasksNotCompleteByUserId(this.userId);
      this.tasksNotComplete = tasksNotCompleteData.data;
    },
    async getTasksCompleted(){
      const tasksCompletedData = await userResource.tasksCompletedByUserId(this.userId);
      this.tasksCompleted = tasksCompletedData.data;
    },
    handleClick(tab, event) {
      console.log('this is tab selected', tab._props);
      console.log('this is tab selected', event);
      console.log('This us v-model value', this.activeName);
    },
    handleFilter(){

    },
  },
};
</script>

<style>
</style>
