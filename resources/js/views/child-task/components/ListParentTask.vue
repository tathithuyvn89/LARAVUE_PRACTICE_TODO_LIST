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
      style="width: 100%"
      @sort-change="sortChange"
    >
      <el-table-column :label="$t('table.id')" prop="id" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
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
            :disabled="scope.row.active === 'success'"
            size="mini"
            :type="scope.row.active"
            @click="handleEditAcitive(scope.row.id)"
            >Acitve i18n</el-button
          >
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)"
            >Progress</el-button
          >
        </template>
      </el-table-column>

      <!-- <el-table-column :label="$t('table.importance')" width="80px">
        <template slot-scope="scope">
          <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" class="meta-item__icon" />
        </template>
      </el-table-column> -->
    </el-table>
  </div>
</template>

<script>
import waves from '@/directive/waves'; // Waves directive
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

    handleEditAcitive(id) {
      updateTask({ active: 1 }, id).then((response) => {
        console.log('This is reponse when edit active of task', response);
      });
      this.userData();
    },
  },
};
</script>
