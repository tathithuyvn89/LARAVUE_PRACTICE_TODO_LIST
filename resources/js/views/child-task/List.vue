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
        />
      </el-tab-pane>
      <el-tab-pane
        label="Task doing"
        name="third"
      >
        <ChildTaskList :childtasks="listChildTask" />
      </el-tab-pane>
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
import ChildTaskList from './components/ChildTaskList';
import UserResoure from '@/api/user';
const userResource = new UserResoure();
import { updateTask } from '@/api/task';
import { fetchListTask } from '@/api/child_task';
export default {
  components: { TaskCompleted, TaskNotComplete, ChildTaskList },
  data() {
    return {
      activeName: 'first',
      tasksCompleted: [],
      tasksNotComplete: [],
      listChildTask: [],
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
    this.getChildTaskNotDone();
  },
  methods: {

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
    async getChildTaskNotDone(){
      const childTasksNotDone = await fetchListTask();
      this.listChildTask = childTasksNotDone.data;
      console.log('Thi is not complete Done', childTasksNotDone);
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
