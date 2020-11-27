<template>
  <el-table
    :data="childtasks"
    style="width: 100%"
  >
    <el-table-column
      fixed
      prop="id"
      label="ID"
    />
    <el-table-column
      prop="name"
      label="Name i18n"
    />
    <el-table-column
      prop="necessary_time"
      label="Necessary Time i18n"
    />
    <el-table-column
      prop="status"
      label="Complete Or No"
    />
    <el-table-column
      fixed="right"
      label="Operations"
    >
      <template slot-scope="scope">
        <el-button type="text" size="small">Detail</el-button>
        <el-button type="text" size="small" @click="updateChildTask(scope.row.id)">Edit</el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    childtasks: {
      type: Array,
      default: null,
    },
  },
  methods: {
    updateChildTask() {

    },
  },
};
</script>

<style>

</style>
