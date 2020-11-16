<template>
  <div class="app-container">
    <!-- Top tag -->
    <div class="filter-container">
      <el-input
        v-model="listQuery.keyword"
        placeholder="Name Group i18n"
        style="width: 200px"
        class="filter-item"
        clearable
        @keyup.enter.native="handleFilter"
      />
      <el-select
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
      </el-select>
      <el-button
        type="primary"
        class="filter-item"
        icon="el-icon-search"
        @click="handleFilter"
      >
        Search i18n
      </el-button>
      <el-button
        type="success"
        class="filter-item"
        icon="el-icon-plus"
        @click="create"
      >
        Create i18n
      </el-button>
      <el-button
        type="danger"
        class="filter-item"
        icon="el-icon-delete"
        @click="handleDeleteMany"
      >
        Delete Many i18n
      </el-button>
    </div>
    <!-- Group Table -->
    <el-table
      :v-loading="listLoading"
      :data="listTasks"
      border
      fit
      highlight-current-row
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" align="center" />

      <el-table-column align="center" label="ID">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Title i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>
       <el-table-column align="center" label="Content i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.content }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Persion Incharge i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.user.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Start Date i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.start_date }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Finish Date i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.finish_date }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Group i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.group.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Edit i18n">
        <template slot-scope="scope">
          <router-link :to="'/todo/tasks/edit/'+scope.row.id">
            <el-button
              type="primary"
              size="small"
              icon="el-icon-edit"
              @click="openPopCreate(scope.row.id)"
            >Edit i18n</el-button>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Delete i18n">
        <template slot-scope="scope">
          <el-button
            type="danger"
            size="small"
            icon="el-icon-delete"
            @click="handleDeleteOne(scope.row)"
          >Delete i18n</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- Pagination at here. This is import form laravue framework -->

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
    <!-- Pop-up Delete Task -->

    <el-dialog
      title="Delete dialog i18n"
      :visible.sync="dialogVisibleDelete"
      center
      width="50%"
    >
      <center>Do you want delete i18n</center>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisibleDelete = false">
          {{ $t('button.close') }}
        </el-button>
        <el-button type="danger" @click="submitConfirmDelete">
          {{ $t('button.delete') }}
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  fetchListTask,
  // fetchAll,
  // fetchTask,
  // createTask,
  // updateTask,
  forceDelete,
} from '@/api/task';

import { fetchList } from '@/api/group';
import Pagination from '@/components/Pagination';

export default {
  name: 'ListGroup',
  components: { Pagination },
  data() {
    return {
      listLoading: false,
      groups: [],
      listQuery: {
        page: 1,
        limit: 15,
        keyword: '',
        group: '',
      },
      listTasks: [],
      createOrEditTitle: '',
      total: 0,
      selectedItem: {},
      isProcess: false,
      tempActionDelete: '',
      dialogVisibleDelete: false,

      ruleForm: {
        id: undefined,
        name: '',
        list_userId: [],
      },

      rules: {
        name: [
          {
            required: true,
            message: 'Name is required i18n',
            trigger: ['blur', 'change'],
          },
        ],
      },
    };
  },
  created() {
    this.getList();
    this.getListGroup();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const response = await fetchListTask(this.listQuery);
      this.listTasks = response.data;

      this.total = response.meta.total;
      // this.listQuery.limit = response.meta.per_page;
      console.log('List Group', this.listTasks);
      console.log('this.total', this.total);
    },
    async getListGroup() {
      const response = await fetchList({});
      this.groups = response;
      console.log('List Groups', this.groups);
    },
    // Create feature for Search Button
    handleFilter() {
      console.log('This query keyword', this.listQuery.keyword);
      this.getList();
    },
    // When click button create => Dialog Create or Edit open
    create() {
      this.$router.push({ path: '/todo/tasks/create' });
    },
    // Create feature delete many item
    handleDeleteMany() {
      this.tempActionDelete = 'Delete Many';
      this.dialogVisibleDelete = true;
    },
    handleDeleteOne(row) {
      console.log('This is delete funtion with id = ', row);
      this.tempActionDelete = 'Delete One';
      this.dialogVisibleDelete = true;
      this.selectedItem = row;
    },
    // When click button create => Dialog Create or Edit open
    openPopCreate(row) {},
    toggleSection(rows) {
      if (rows) {
        rows.forEach((row) => {
          this.$refs.multipleTable.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTable.clearSelection();
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = [];
      for (let i = 0; i < val.length; i++) {
        this.multipleSelection.push(val[i].id);
      }
    },
    submitConfirmDelete() {
      if (this.tempActionDelete === 'Delete Many') {
        this.deleteMany();
      } else {
        this.deleteOne(this.selectedItem);
      }
    },
    async deleteMany() {
      var lengthData = 0;
      var dataDelete = Object.values(this.multipleSelection);
      if (this.multipleSelection.length === 0) {
        this.$message({
          message: 'No Task selected i18n',
          type: 'warning',
        });
      } else if (this.multipleSelection.length > 0) {
        lengthData = this.listQuery.limit - this.multipleSelection.length;
        if (lengthData < 1) {
          this.listQuery.page -= this.listQuery.page;
        }

        const resDeleteMany = await forceDelete({ list_id: dataDelete });
        console.log('Delete list', resDeleteMany);
        if (resDeleteMany.message === 'Delete Success') {
          this.$message({
            message: 'Delete many success i18n',
            type: 'success',
          });
          this.listLoading = true;
          this.handleFilter();
          this.listLoading = false;
          this.dialogVisibleDelete = false;
        } else {
          this.$message({
            message: 'Delete many fail i18n',
            type: 'error',
          });
        }
      }
    },
    async deleteOne(row) {
      const resDeleteMany = await forceDelete({ list_id: [row.id] });
      console.log('Delete list', resDeleteMany);
      if (resDeleteMany.message === 'Delete Success') {
        this.$message({
          message: 'Delete one success i18n',
          type: 'success',
        });
        this.listLoading = true;
        this.handleFilter();
        this.listLoading = false;
        this.dialogVisibleDelete = false;
      } else {
        this.$message({
          message: 'Delete one fail i18n',
          type: 'error',
        });
      }
    },
    submit() {},
    submitForm(formName) {},
  },
};
</script>

<style>
</style>

<style scoped>
.inputSearch {
  margin-left: 10px;
  width: 200px;
}

.inputClassname {
  margin-top: 30px;
}

.btnForm {
  text-align: right;
}

.pagination {
  margin-top: 10px;
  text-align: right;
}
::v-deep .el-dialog__title {
  font-size: 24px;
  font-weight: bold !important;
}
</style>
