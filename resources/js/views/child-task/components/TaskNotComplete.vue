<template>
  <div class="app-container">

    <el-table

      :data="tasksNotComplete"
      border
      fit
      highlight-current-row
    >
      <el-table-column
        :label="$t('table.id')"
        prop="id"
        align="center"
        width="50"
      >
        <template slot-scope="scope">
          <router-link
            :to="'/childtask/prenttasks/' + scope.row.id"
          ><span>{{ scope.row.id }}</span></router-link>
        </template>
      </el-table-column>
      <el-table-column label="Title i18n" prop="title" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="content i18n" prop="content" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.content }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Start date i18n" prop="start_date" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.start_date }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Finish date i18n"
        prop="finish_date"
        align="center"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.finish_date }}</span>
        </template>
      </el-table-column>

      <el-table-column
        label="Progress i18n"
        prop="progress"
        align="center"
        width="250"
      >
        <template slot-scope="scope">
          <el-progress
            :percentage="scope.row.progress"
            :color="customColorMethod"
          />

          <div>
            <el-button-group>
              <el-button
                icon="el-icon-minus"
                @click="decrease(scope.row.progress, scope.row.id)"
              />
              <el-button
                icon="el-icon-plus"
                @click="increase(scope.row.progress, scope.row.id)"
              />
            </el-button-group>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        label="Complete date i18n"
        prop="complete_date"
        align="center"
      >
        <template slot-scope="scope">
          <div v-if="scope.row.complete_date !== null">
            <el-button type="success">{{ scope.row.complete_date }}</el-button>
          </div>
          <div v-else>
            <el-dialog
              title="Edit complete date"
              :visible.sync="visible"
              width="30%"
              center
            >
              <span>Xin chuc mung ban da hoan thanh cong viec. Hay cho chung toi biet ve ngay ma ban hoan thanh cong viec do</span>
              <el-date-picker
                v-model="complete_date_input"
                type="date"
                placeholder="Pick a date i18n"
                style="width: 100%"
              />
              <el-button @click="visible = false">Cancel</el-button>
              <el-button
                type="primary"
                size="mini"
                @click="updateCompleteDate(scope.row.id)"
              >confirm</el-button>

            </el-dialog>

            <el-button
              slot="reference"
              type="warning"
              @click="visible = true"
            >Update complete datedf i18n</el-button>

          </div>
        </template>
      </el-table-column>
      <el-table-column label="Create childTask i18n">
        <template slot-scope="scope">
          <el-button type="primary" @click="createNewChildTaskDialogOpen(scope.row.id)">Create new child task</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- +++++++++++++++++++++++++++++Dialog for create child task +++++++++++++++++++++++++++++++++++++++++++++++++-->
    <el-dialog title="Create new child task" :visible.sync="createNewChildTaskDialog" @close="closeDialog">
      <el-table :data="list">
        <el-table-column label="Name" prop="name">
          <template slot-scope="scope">
            <el-input
              v-model="scope.row.name"
              placeholder="Input Name Task"
              @change="textChange(scope.row.name,'name',scope.row.position)"
            />
          </template>
        </el-table-column>
        <el-table-column label="Necessary Time" prop="necessary_time">
          <template slot-scope="scope">
            <el-select
              v-model="scope.row.necessary_time"
              placeholder="Select necessary time"
              @change="textChange(scope.row.necessary_time,'necessary_time',scope.row.position)"
            >
              <el-option
                v-for="item in listTime"
                :key="item.id"
                :label="item.value"
                :value="item.id"
              />
            </el-select>
            <!-- <el-input
              v-model="scope.row.necessary_time"
              placeholder="Input Necessary Time for Task"
              @change="textChange(scope.row.necessary_time,'necessary_time',scope.row.position)"
            /> -->
          </template>
        </el-table-column>
      </el-table>
      <el-button type="success" @click="addMoreItemForm"><i class="el-icon-plus" /></el-button>

      <span slot="footer" class="dialog-footer">
        <el-button @click="cancelCreateForm">Cancel</el-button>
        <el-button type="primary" @click="submitFormChildTask(form)">Confirm</el-button>
      </span>

    </el-dialog>
    <!-- <div v-for="item in forms" :key="item.id">
      <template class="item.randomValue">
        <el-form ref="newChildTask" :inline="true" :model="form" :rules="rules">
          <el-form-item label="Promotion name">
            <el-input v-model="form.name" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Zones">
            <el-select v-model="form.necessary_time" placeholder="Please select a zone">
              <el-option label="Zone No.1" value="shanghai" />
              <el-option label="Zone No.2" value="beijing" />
            </el-select>
          </el-form-item>
        </el-form>
      </template>
      </div> -->

    <!-- ======================Dialog for create child task =================================================================-->

  </div>
</template>

<script>
import moment from 'moment';
export default {
  name: 'TaskNotComplete',

  props: {
    tasksNotComplete: {
      type: Array,
      default: null,
    },
  },

  data(){
    return {
      complete_date_input: null,
      visible: false,
      createNewChildTaskDialog: false,
      selectedTaskId: undefined,
      list: [{ name: '', necessary_time: '', position: 0 }],
      data: [],
      listTime: [
        { id: 30, value: '30 minutes' }, { id: 60, value: '1 hour' }, { id: 90, value: ' 1 hours 30 minutes' }, { id: 120, value: '2 hours' },
        { id: 150, value: '2 hours 30 munites' }, { id: 180, value: '3 hours' }, { id: 210, value: '3 hours 30 minutes' },
        { id: 240, value: '4 hours' }, { id: 270, value: '4 hours 30 minutes' }, { id: 300, value: '5 hours' }, { id: 330, value: '5 hours 30 munites' },
        { id: 360, value: '6 hours' }, { id: 390, value: '6 hours 30 minutes' }, { id: 420, value: '7 hours' }, { id: 450, value: '7 hours 30 minutes' },
        { id: 480, value: '8 hours' },
      ],
    };
  },

  methods: {
    closeDialog(){
      this.createNewChildTaskDialog = false;
      this.resetCreateForm();
    },

    cancelCreateForm(){
      this.closeDialog();
    },

    textChange(modelValue, property, position) {
    // this.data.push({position:position,property:property,value:modelValue})
    // console.log('This is change value', property + 'is ' + modelValue + ' at '+ position);

    },
    addMoreItemForm(){
      const indexAtNextPosition = this.list.length - 1;
      this.list.push({ name: '', necessary_time: '', position: this.list[indexAtNextPosition].position + 1 });
    },
    submitFormChildTask() {
      const emitData = [];
      for (let i = 0; i < this.list.length; i++) {
        emitData.push({ name: this.list[i].name, necessary_time: +this.list[i].necessary_time, parent_task: this.selectedTaskId });
      }
      console.log('This is emitData', emitData);
      this.$emit('emitData', emitData);
      this.createNewChildTaskDialog = false;
      this.resetCreateForm();
    },

    createNewChildTaskDialogOpen(id){
      console.log('This is selected id', id);
      this.createNewChildTaskDialog = true;
      this.selectedTaskId = id;
    },
    customColorMethod(percentage) {
      if (percentage < 30) {
        return '#909399';
      } else if (percentage < 70) {
        return '#e6a23c';
      } else {
        return '#67c23a';
      }
    },
    increase(percentage, id) {
      percentage += 10;
      if (percentage > 100) {
        percentage = 100;
      }
      this.$emit('increase', { percentage: percentage, taskId: id });
    },
    decrease(percentage, id) {
      percentage -= 10;
      if (percentage < 0) {
        percentage = 0;
      }

      this.$emit('decrease', { percentage: percentage, taskId: id });
    },

    updateCompleteDate(id) {
      this.complete_date_input = moment(this.complete_date_input).format('YYYY-MM-DD');
      this.$emit('completeDate_input', { complete_date: this.complete_date_input, taskId: id });
      this.complete_date_input = null;
      this.visible = false;
    },
    resetCreateForm(){
      this.list = [{ name: '', necessary_time: '', position: 0 }];
    },
  },
};
</script>
