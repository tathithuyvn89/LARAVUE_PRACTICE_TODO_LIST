<template>
  <div class="app-container">
    <!-- Top tag -->
    <div class="filter-container">
      <el-input
        v-model="listQuery.keyword"
        placeholder="Name Group i18n"
        style="width: 200px"
        class="filter-item"
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
      :data="listGroup"
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
      <el-table-column align="center" label="Name i18n">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Edit i18n">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="small"
            icon="el-icon-edit"
            @click="openPopCreate(scope.row.id)"
          >Edit i18n</el-button>
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
    <!-- Pop-up Create and Edit Group -->
    <el-dialog
      :title="createOrEditTitle"
      :visible.sync="diglogCreateOrEdit"
      width="400px"
      @close="closeDialog('ruleForm')"
    >
      <div v-loading="isProcess" class="form-container">
        <el-form
          ref="ruleForm"
          :model="ruleForm"
          :rules="rules"
          class="ruleForm"
        >
          <el-form-item label="Name i18n" prop="name">
            <el-input
              v-model="ruleForm.name"
              placeholder="Entern Name of group i18n"
            />
          </el-form-item>
          <el-form-item label="Member i18n" prop="list_userId">
            <el-select
              v-model="ruleForm.list_userId"
              multiple
              filterable
              default-first-option
              style="width: 100%"
              placeholder="Please chose member i18n"
            >
              <el-option
                v-for="(item, index) in users"
                :key="index"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="diglogCreateOrEdit = false">
            Close i18n
          </el-button>
          <el-button type="success" :disabled="isProcess" @click="submit">
            SUBMIT i18n
          </el-button>
        </div>
      </div>
    </el-dialog>
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
import UserResource from '@/api/user';
import {
  fetchList,
  fetchGroup,
  createGroup,
  updateGroup,
  forceDelete,
} from '@/api/group';
const userResource = new UserResource();
export default {
  name: 'ListGroup',
  data() {
    return {
      listLoading: true,
      listGroup: [],
      listQuery: {
        keyword: '',
      },

      createOrEditTitle: '',
      users: [],
      selectedItem: {},
      diglogCreateOrEdit: false,
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
    this.getListUser();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const data = await fetchList(this.listQuery);
      this.listGroup = data;
      console.log('List Group', this.listGroup);
    },
    async getListUser() {
      const response = await userResource.listUser();
      this.users = response.data;
      console.log('ListUser', this.users);
    },
    // Create feature for Search Button
    handleFilter() {
      console.log('This query keyword', this.listQuery.keyword);
      this.getList();
    },
    // When click button create => Dialog Create or Edit open
    create() {
      this.diglogCreateOrEdit = true;
      this.createOrEditTitle = 'Create new Group i18n';
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
    openPopCreate(row) {
      console.log('This is row id', row);
      this.diglogCreateOrEdit = true;
      this.createOrEditTitle = 'Edit Group i18n';
      const editGroup = fetchGroup(row);
      editGroup.then((response) => {
        this.ruleForm.id = response.data.id;
        this.ruleForm.name = response.data.name;
        this.ruleForm.list_userId = response.data.users;
        console.log('This is edit Item', this.ruleForm);
      });
    },
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
      }

      if (this.tempActionDelete === 'Delete One') {
        this.deleteOne(this.selectedItem);
      }
    },
    deleteMany() {
      var deleteData = Object.values(this.multipleSelection);
      console.log('Delete many item =', deleteData);
      if (this.multipleSelection.length === 0) {
        this.$message({
          message: 'No Item selected i18n',
          type: 'warning',
        });
        this.dialogVisibleDelete = false;
      } else {
        forceDelete({ list_id: deleteData }).then((result) => {
          console.log('Reponse when delete many', result);
          if (result.message === 'Delete success') {
            this.$message({
              message: 'Delete success i18n',
              type: 'success',
            });
          } else {
            this.$message({
              message: 'Delete Fail i18n',
              type: 'warning',
            });
          }
          this.dialogVisibleDelete = false;
          this.listLoading = true;
        });
      }
      this.handleFilter();
    },
    deleteOne(groupValue) {
      if (groupValue === null) {
        this.$message({
          message: 'No Item selected i18n',
          type: 'warning',
        });
      }
      forceDelete({ list_id: [groupValue.id] }).then((result) => {
        console.log('Reponse when delete many', result);
        if (result.message === 'Delete success') {
          this.$message({
            message: 'Delete success i18n',
            type: 'success',
          });
        } else {
          this.$message({
            message: 'Delete Fail i18n',
            type: 'warning',
          });
        }
      });
      this.dialogVisibleDelete = false;
      this.listLoading = true;
      this.handleFilter();
    },
    submit() {
      this.$refs['ruleForm'].validate((valid) => {
        if (valid) {
          this.isProcess = true;
          this.createGroup = true;
          if (this.createOrEditTitle === 'Create new Group i18n') {
            console.log('This is RuleForm in create', this.ruleForm);
            const resCreate = createGroup({
              name: this.ruleForm.name,
              list_userId: this.ruleForm.list_userId,
            });
            resCreate
              .then((response) => {
                if (response.message === 'Create success') {
                  this.isProcess = false;
                  this.diglogCreateOrEdit = false;
                  this.$message({
                    message: 'Create new Group success i18n',
                    type: 'success',
                  });
                  this.listLoading = true;
                } else {
                  this.$message({
                    message: 'Create new Group fail i18n',
                    type: 'error',
                  });
                }
              })
              .catch((error) => {
                this.$message({
                  message: error,
                  type: 'danger',
                });
              })
              .finally(() => {
                this.isProcess = false;
                this.diglogCreateOrEdit = false;
                this.handleFilter();
                this.listLoading = true;
              });
          }
        }
        if (this.createOrEditTitle === 'Edit Group i18n') {
          const resEdit = updateGroup(
            {
              name: this.ruleForm.name,
              list_userId: this.ruleForm.list_userId,
            },
            this.ruleForm.id
          );
          resEdit.then((response) => {
            if (response.message === 'Update success') {
              this.isProcess = false;
              this.diglogCreateOrEdit = false;
              this.$message({
                message: 'Edit Group success i18n',
                type: 'success',
              });
              this.listLoading = true;
            } else {
              this.isProcess = false;
              this.diglogCreateOrEdit = false;
              this.$message({
                message: 'Edit Group fail i18n',
                type: 'error',
              });
            }
          });
        } else {
          return false;
        }
      });
      this.handleFilter();
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          return true;
        } else {
          return false;
        }
      });
    },
    closeDialog(formName) {
      this.$refs[formName].resetFields();
      this.ruleForm.name = '';
      this.ruleForm.list_userId = [];
    },
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
