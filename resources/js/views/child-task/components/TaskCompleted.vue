<template>

  <el-table
    :data="tasksCompleted"
    border
    style="width: 100%"
    highlight-current-row
  >
    <el-table-column
      prop="id"
      label="ID i18n"
      sortable
    >
      <template slot-scope="scope">
        <router-link
          :to="'/childtask/prenttasks/' + scope.row.id"
        ><span>{{ scope.row.id }}</span></router-link>
      </template>
    </el-table-column>
    <el-table-column
      prop="title"
      label="Tilte i18n"
    />
    <el-table-column
      prop="content"
      label="Content i18n"
    />
    <el-table-column
      prop="start_date"
      label="Start date i18n"
      sortable
    />
    <el-table-column
      prop="finish_date"
      label="Finish date i18n"
      sortable
    />
    <el-table-column
      prop="complete_date"
      label="Complete date i18n"
      sortable
    />
  </el-table>

</template>

<script>
export default {
  name: 'TasksCompleted',
  props: {
    tasksCompleted: {
      type: Array,
      default: null,
    },
  },

};
</script>

<style scoped>
 .el-table .th {
    background: #f0f9eb;
  }
</style>
