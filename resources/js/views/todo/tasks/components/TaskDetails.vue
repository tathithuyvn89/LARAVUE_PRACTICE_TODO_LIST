<template>
  <div class="form-container">
    <el-form ref="taskForm" :model="taskForm" :rules="rules" label-width="180px">

      <el-form-item label="Group i18n">
        <el-select
          v-model="taskForm.group"
          placeholder="Select group i18n"
          filterable
          @change="groupFilter"
        >
          <el-option
            v-for="item in groups"
            :key="item.id"
            :label="item.name | uppercaseFirst"
            :value="item.id"
          />
        </el-select>
      </el-form-item>

      <el-form-item label="User i18n">
        <el-select
          v-model="taskForm.user"
          placeholder="Select Persion Incharge i18n"
          filterable
        >
          <el-option
            v-for="item in users"
            :key="item.id"
            :label="item.name | uppercaseFirst"
            :value="item.id"
          />
        </el-select>
      </el-form-item>

      <el-form-item label="Title i18n">
        <el-input v-model="taskForm.title" />
      </el-form-item>
      <el-form-item label="Start Date i18n">
        <el-date-picker
          v-model="taskForm.start_date"
          type="date"
          placeholder="Pick a date i18n"
          style="width: 100%"
        />

      </el-form-item>
      <el-form-item label="Finish Date i18n">
        <el-date-picker
          v-model="taskForm.finish_date"
          type="date"
          placeholder="Pick a date i18n"
          style="width: 100%;"
        />
      </el-form-item>
      <el-form-item label="Content and Note i18n">
        <el-input v-model="taskForm.content" />
      </el-form-item>
      <h2>{{ taskForm }}</h2>
      <el-form-item>
        <el-button type="primary" @click="submitForm()">SAVE i18n</el-button>
        <el-button>Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { fetchList, fetchGroup } from '@/api/group';
import moment from 'moment';
import {
  fetchTask,
  createTask,
  updateTask,

} from '@/api/task';

const defaultForm = {
  id: undefined,
  title: '',
  content: '',
  user: undefined,
  group: undefined,
  start_date: '',
  finish_date: '',
};
export default {
  name: 'TaskDetails',
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      taskForm: Object.assign({}, defaultForm),
      groups: [],
      users: [],
      rules: {
        group_id: [{ required: true, message: 'Group is required i18n', trigger: 'change' }],
        title: [{ required: true, message: 'Title is required i18n', trigger: 'blur' }],
        content: [{ required: true, message: 'Content is required i18n', trigger: 'blur' }],
        // Tiep tuc validate cho ngay thang
      },
      formCreating: false,

    };
  },
  // conputed:{
  // changeGroup(){
  // return this.taskForm.group;
  // },
  // watch:{
  //   changeGroup(){
  //    const group = fetchGroup(this.taskForm.group);
  //     group.then((res) => {
  //       console.log('This is group selected', res);
  //       this.users = res.users_list;
  //       console.log('This is  users_list of group selected', this.users);
  //     });
  //   }
  // }
  // },
  created() {
    this.listGroup();
    if (this.isEdit){
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.taskForm = Object.assign({}, defaultForm);
    }
  },
  methods: {
    async fetchData(id){
      fetchTask(id).then((result) => {
        this.taskForm.id = result.data.id;
        this.taskForm.title = result.data.title;
        this.taskForm.content = result.data.content;
        this.taskForm.finish_date = result.data.finish_date;
        this.taskForm.start_date = result.data.start_date;
        this.taskForm.user = result.data.user.id;
        this.taskForm.group = result.data.group.id;
        const group = fetchGroup(result.data.group.id);
        group.then((res) => {
          console.log('This is group selected 1', res);
          this.users = res.users_list;
          console.log('This is  users_list of group selected1', this.users);
        });
      });
    },
    async listGroup() {
      const response = await fetchList({});
      this.groups = response;
      console.log('List Groups', this.groups);
    },
    groupFilter() {
      this.taskForm.user = '';
      console.log('this is selected group', this.taskForm.group);
      const group = fetchGroup(this.taskForm.group);
      group.then((res) => {
        console.log('This is group selected', res);
        this.users = res.users_list;
        console.log('This is  users_list of group selected', this.users);
      });

      console.log('This is taskForm', this.taskForm);
    },
    submitForm(){
      this.taskForm.start_date = moment(this.taskForm.start_date).format('YYYY-MM-DD');
      this.taskForm.finish_date = moment(this.taskForm.finish_date).format('YYYY-MM-DD');
      console.log('This is taskForm', this.taskForm);

      this.$refs['taskForm'].validate((valid) => {
        if (valid){
          this.formCreating = true;
          // if edit task
          if (this.isEdit) {
            console.log('Da vao day');
            // Because form create list is list tasks
            updateTask(this.taskForm, this.taskForm.id).then((result) => {
              console.log('This result from edit Task', result);
              this.$message({
                message: 'Edit task success i18n' + result,
                type: 'success',
              });
              this.resetNewTask();
            })
              .catch(error => {
                console.log(error);
              }).finally(() => {
                this.formCreating = false;
              });
          } else {
            const request = { list: [this.taskForm] };
            console.log('This form from request', request);
            createTask(request).then((response) => {
              console.log('Response when create new task', response);
              this.$message({
                message: 'New Task created i18n',
                type: 'success',
              });
              this.resetNewTask();
            })
              .catch(error => {
                console.log(error);
              }).finally(() => {
                this.formCreating = false;
              });
          }

          // if create new task
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewTask(){
      return defaultForm;
    },
  },
};
</script>

