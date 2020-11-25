<template>
  <div class="app-container">
    <!-- ====================FILTER-CONTAINER===================================================== -->
    <!-- <div class="filter-container">
      <el-input v-model="listQuery.title" :placeholder="$t('table.title')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.importance" :placeholder="$t('table.importance')" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select>
      <el-select v-model="listQuery.type" :placeholder="$t('table.type')" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in calendarTypeOptions" :key="item.key" :label="item.display_name+'('+item.key+')'" :value="item.key" />
      </el-select>
      <el-select v-model="listQuery.sort" style="width: 140px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="item.label" :value="item.key" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>

      <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
      <el-checkbox v-model="showReviewer" class="filter-item" style="margin-left:15px;" @change="tableKey=tableKey+1">
        {{ $t('table.reviewer') }}
      </el-checkbox>
    </div> -->
    <!-- ==========================================TABLE-TASK================================================== -->
    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="listTasks"
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
            :to="'/childtask/create/' + scope.row.id"
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

      <el-table-column label="Acitve i18n" prop="acitve" align="center">
        <template slot-scope="scope">
          <el-button
            round
            :disabled="scope.row.active === 'success'"
            size="mini"
            :type="scope.row.active"
            @click="handleEditAcitive(scope.row.id)"
          >
            Acitve i18n
          </el-button>
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
            <el-popover v-model="visible" placement="top" width="260">
              <h3>Input Complete date</h3>
              <el-date-picker
                v-model="complete_date_input"
                type="date"
                placeholder="Pick a date i18n"
                style="width: 100%"
              />
              <div style="text-align: right; margin: 0">
                <el-button
                  size="mini"
                  type="text"
                  @click="visible = false"
                >cancel</el-button>
                <el-button
                  type="primary"
                  size="mini"
                  @click="updateCompleteDate(scope.row.id)"
                >confirm</el-button>
              </div>
              <el-button
                slot="reference"
                type="warning"
              >Update complete date i18n</el-button>
            </el-popover>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import waves from '@/directive/waves'; // Waves directive
import moment from 'moment';
import {
  // fetchTask,
  // createTask,
  updateTask,
} from '@/api/task';
import UserResource from '@/api/user';
const userResource = new UserResource();
const typeActive = {
  DANGER: 'danger',
  SUCCESS: 'success',
};
export default {
  name: 'ComplexTable',
  directives: { waves },

  data() {
    return {
      tableKey: 0,
      listTasks: {},
      total: 0,
      listLoading: false,
      onActive: 'danger',
      visible: false,
      complete_date_input: null,
    };
  },
  computed: {
    userId() {
      return this.$store.getters.userId;
    },
  },
  created() {
    this.userData();
  },
  methods: {
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
      updateTask({ progress: percentage }, id).then((response) => {
        console.log('This is reponse when edit active of task', response);
      });
      this.userData();
    },
    decrease(percentage, id) {
      percentage -= 10;
      if (percentage < 0) {
        percentage = 0;
      }
      updateTask({ progress: percentage }, id).then((response) => {
        console.log('This is reponse when edit active of task', response);
      });
      this.userData();
    },
    async userData() {
      //  this.listLoading = true;
      console.log('This user tai task list', this.userId);
      const responData = await userResource.get(this.userId);
      this.listTasks = responData.data.tasks;
      for (let i = 0; i < this.listTasks.length; i++) {
        if (this.listTasks[i].active === 1) {
          this.listTasks[i].active = typeActive.SUCCESS;
        } else {
          this.listTasks[i].active = typeActive.DANGER;
        }
      }
      console.log('This is list Task by Id', this.listTasks);
      //  this.listLoading = false;
    },

    updateCompleteDate(id) {
      this.complete_date_input = moment(this.complete_date_input).format('YYYY-MM-DD');
      console.log('This date complete', this.complete_date_input);
      updateTask({ complete_date: this.complete_date_input }, id).then((response) => {
        if (response.data !== null) {
          this.userData();
        } else {
          this.$message({
            message: 'Update fail, try again i18n',
            type: 'warning',
          });
        }
      });
      this.complete_date_input = null;
      this.visible = false;
    },
    handleEditAcitive(id) {
      updateTask({ active: 1 }, id).then((response) => {
        if (response.data !== null) {
          this.$store.dispatch('task/listTasksById');
          this.userData();
        } else {
          this.$message({
            message: 'Update fail, try again i18n',
            type: 'warning',
          });
        }
      });
    },
  },
};
</script>
