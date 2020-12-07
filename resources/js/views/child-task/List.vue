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
          @emitData="createChildTask"
        />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import TaskCompleted from './components/TaskCompleted';
import TaskNotComplete from './components/TaskNotComplete';

import UserResoure from '@/api/user';
const userResource = new UserResoure();
import { updateTask } from '@/api/task';
import { createChildTask } from '@/api/child_task';
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
    // this.getChildTaskNotDone();
  },
  methods: {
    async createChildTask(value) {
      console.log('Thi is value emit', value);
      const responseData = await createChildTask({ list: value });
      if (responseData.message === 'Create success') {
        this.$message({
          message: 'Create new task success',
          type: 'success',
        });
      } else {
        this.$message({
          message: 'Create new task fails',
          type: 'warning',
        });
      }
    },
    updateCompleteDate(dataFromChildComponent) {
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
      updateTask({ progress: dataFromChildComponent.percentage }, dataFromChildComponent.taskId).then((response) => {
      });
      this.getTasksNotComplete();
      this.getTasksCompleted();
    },
    increaseProccess(dataFromChildComponent){
      updateTask({ progress: dataFromChildComponent.percentage }, dataFromChildComponent.taskId).then((response) => {

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
      // for (let i = 0; i < data.length; i++) {
      //   const diffDate = this.calculateCompleteLevels(data[i].complete_date, data[i].finish_date);
      //   this.tasksCompleted.push({ id: data[i].id, title: data[i].title, content: data[i].content, start_date: data[i].start_date, finish_date: data[i].finish_date, complete_date: data[i].complete_date, complete_level: diffDate });
      // }
    },
    handleClick(tab, event) {
      console.log('this is tab selected', tab._props);
      console.log('this is tab selected', event);
      console.log('This us v-model value', this.activeName);
    },
    handleFilter(){

    },
    calculateCompleteLevels(dateStr1, dateStr2) {
      var date1 = new Date(dateStr1);
      var date2 = new Date(dateStr2);
      const Difference_In_Time = date2.getTime() - date1.getTime();
      const Difference_In_Day = Difference_In_Time / (1000 * 3600 * 24);
      var dates = [];
      var countDate = 0;
      for (let i = 1; i <= Difference_In_Day; i++){
        dates.push(this.addDays(date1, i * 24 * 60 * 60 * 1000));
      }
      console.log('Nhung ngay trong mang', dates);
      for (let i = 0; i < dates.length; i++){
        if (dates[i].getDay() !== 0 || dates[i].getDay() !== 0){
          countDate++;
        }
      }
      return countDate;
    },
    addDays(theDate, days) {
      return new Date(theDate.getTime() + days * 24 * 60 * 60 * 1000);
    },
  },
};
</script>

<style>
</style>
