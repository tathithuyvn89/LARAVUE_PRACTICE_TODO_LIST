<template>

  <div class="child-task-container">
    <div class="filter-container">
      <el-input
        v-model="name"
        placeholder="Name i18n"
        style="width: 200px"
        class="filter-item"
        clearable
        @keyup.enter.native="createNewChildTask"
      />

      <el-select
        v-model="necessary_time"
        style="width: 17  0px"
        placeholder="Select necessary time"
        clearable
        filterable
        class="filter-item"
        @keyup.enter.native="createNewChildTask"
      >
        <el-option
          v-for="item in listTime"
          :key="item.id"
          :label="item.value"
          :value="item.id"
        />
      </el-select>
      <el-button
        type="success"
        class="filter-item"
        icon="el-icon-plus"
        @click="createNewChildTask"
      >
        Create New Child Task
      </el-button>
    </div>

    <el-table
      :data="childTasks"
      style="width: 70%"
    >
      <el-table-column
        fixed
        prop="id"
        label="ID"
      />
      <el-table-column
        prop="name"
        label="Name"
      />
      <el-table-column
        prop="necessary_time"
        label="Necessary Time"
      />
      <el-table-column
        prop="status"
        label="Status"
      >
        <template slot-scope="scope">
          <el-button v-if="scope.row.done === null ||scope.row.done === 1 " type="warning" round @click="updateStatus(scope.row.id,scope.row.done)">Doing</el-button>
          <el-button v-else type="primary" round @click="updateStatus(scope.row.id,scope.row.done)">Done</el-button>

        </template>
      </el-table-column>
      <el-table-column
        fixed="right"
        label="Operations"
      >
        <template slot-scope="scope">
          <el-button size="small" :disabled="scope.row.done === 2" @click="editChildTask(scope.row.id)">More ...</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="statitic-data">
      <h3>Thoi gian duoc cap:{{ statisticData.diffDayExceptHoliday }} phut</h3>
      <h3>Thoi gian nguoi duoc phan cong uoc tinh: {{ statisticData.totalTimeOfChildTask }} phut</h3>
      <div>
        <div v-if="statisticData.compareTime<0" style="color:red;">Ban da uoc luong qua thoi gian quy dinh {{ statisticData.compareTime }} phut. Neu co the xin hay dieu chinh lai</div>
        <div v-else style="color:green;">>Ban van con {{ statisticData.compareTime }} de thuc hien task nay</div>
      </div>
    </div>
    <!-- +++++++++++++++++++++++   DIALOG EDIT   ++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <el-dialog title="Edit childTask" :visible.sync="dialogFormVisible">
      <div v-loading="childTaskCreating">
        <el-form ref="form" :model="form" :rules="rules">
          <el-form-item label="Promotion name" prop="name">
            <el-input v-model="form.name" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Necessary Time" prop="necessary_time">

            <el-select
              v-model="form.necessary_time"
              placeholder="Select necessary time"
            >
              <el-option
                v-for="item in listTime"
                :key="item.id"
                :label="item.value"
                :value="item.id"
              />
            </el-select>

          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="danger" @click="deleteChildTask">Delete</el-button>
        <el-button type="primary" @click="updateChildTaskInfo">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>

import { fetchTask } from '@/api/task';

import { updateChildTask, fetchChildTask, forceDeleteChildTask, createChildTask } from '@/api/child_task';

export default {
  data(){
    return {
      childTasks: [],
      form: { name: '', necessary_time: '' },
      name: '',
      statisticData: {},
      necessary_time: '',
      selectedItem: {},
      childTaskCreating: false,
      rules: {
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
        necessary_time: [{ required: true, message: 'Name is required', trigger: 'change' }],
      },
      dialogFormVisible: false,
      listTime: [
        { id: 30, value: '30 minutes' }, { id: 60, value: '1 hour' }, { id: 90, value: ' 1 hours 30 minutes' }, { id: 120, value: '2 hours' },
        { id: 150, value: '2 hours 30 munites' }, { id: 180, value: '3 hours' }, { id: 210, value: '3 hours 30 minutes' },
        { id: 240, value: '4 hours' }, { id: 270, value: '4 hours 30 minutes' }, { id: 300, value: '5 hours' }, { id: 330, value: '5 hours 30 munites' },
        { id: 360, value: '6 hours' }, { id: 390, value: '6 hours 30 minutes' }, { id: 420, value: '7 hours' }, { id: 450, value: '7 hours 30 minutes' },
        { id: 480, value: '8 hours' },
      ],
    };
  },

  created(){
    this.getChildTaskByParent();
  },
  methods: {
    createNewChildTask(){
      if (this.name === '' && this.necessary_time !== '') {
        this.$message({
          message: 'Please input Name',
          type: 'warning',
        });
      } else if (this.name !== '' && this.necessary_time === ''){
        this.$message({
          message: 'Please select necessary time',
          type: 'warning',
        });
      } else if (this.name === '' && this.necessary_time === '') {
        this.$message({
          message: 'Please input Name and Select time',
          type: 'warning',
        });
      } else {
        const id = this.$route.params && this.$route.params.id;
        createChildTask({ list: [{ 'name': this.name, 'necessary_time': this.necessary_time, 'parent_task': id }] }).then(res => {
          console.log(res);
          this.$message({
            message: 'Create new ChildTask success!',
            type: 'success',
          });
          this.getChildTaskByParent();
          this.name = '';
          this.necessary_time = '';
        }).catch(err => {
          console.log(err);
        });
      }
    },
    deleteChildTask(){
      forceDeleteChildTask(this.form.id).then(response => {
        console.log('This is from detete function', response);
        this.$message({
          message: 'Delete  success!',
          type: 'success',
        });
      });
      this.dialogFormVisible = false;
      this.getChildTaskByParent();
    },
    updateChildTaskInfo() {
      this.$refs.form.validate((valid) => {
        if (valid){
          this.childTaskCreating = true;
          this.updateChidldTaskForm({ name: this.form.name, necessary_time: this.form.necessary_time }, this.form.id);
        } else {
          console.log('Error Update');
          return false;
        }
      });
      this.childTaskCreating = false;
      this.dialogFormVisible = false;
      this.getChildTaskByParent();
    },
    editChildTask(id){
      // this.$nextTick(() => {
      //   this.$refs['form'].clearValidate();
      // });
      this.dialogFormVisible = true;

      const data = fetchChildTask(id);
      data.then(response => {
        this.form = response.data;
        console.log('This is response from form', this.form);
      });
    },
    updateStatus(id, status){
      console.log('ID = ' + id + ' Status = ' + status);
      if (status === 1 || status === null) {
        this.updateChidldTaskForm({ status: 2 }, id);
      } else {
        this.updateChidldTaskForm({ status: 1 }, id);
      }
      this.getChildTaskByParent();
    },
    async getChildTaskByParent() {
      const id = this.$route.params && this.$route.params.id;

      const responData = await fetchTask(id);
      console.log('This is response data', responData);
      this.statisticData = responData.statistic;
      this.childTasks = responData.data.child_tasks;
      for (let i = 0; i < this.childTasks.length; i++){
        this.childTasks[i].necessary_time = this.tranferTime(this.childTasks[i].necessary_time);
      }
    },
    updateChidldTaskForm(updateObj, id) {
      updateChildTask(updateObj, id).then(response => {
        console.log('This is response', response);
        if (response.message === 'Update success') {
          this.$message({
            message: 'Update Child Task Doing success!',
            type: 'success',
          });
        }
      }).catch(err => {
        if (err.response) {
          this.$message({
            message: 'Update Child Task Fails!',
            type: 'warning',
          });
          console.log('Error is response', err.response);
        } else if (err.request) {
          console.log('Error is request', err.request);
        } else {
          console.log('Khong biet error la gi');
        }
      });
    },
    tranferTime(number) {
      if ((number % 60) === 0) {
        return number / 60 + ' hours';
      } else {
        return Math.floor(number / 60) + ' hours and ' + number % 60 + ' minutes';
      }
    },
  },

};
</script>

<style>

</style>
