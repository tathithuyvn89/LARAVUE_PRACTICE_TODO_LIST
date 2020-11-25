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
    </el-table>
  </div>
</template>

<script>
import waves from '@/directive/waves'; // Waves directive
import moment from 'moment';
export default {
  name: 'TaskNotComplete',
  directives: { waves },
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
    };
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

  },
};
</script>
