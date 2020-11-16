<template>
  <TaskDetails :is-edit="false" />
</template>

<script>
import TaskDetails from './components/TaskDetails';
export default {
  name: 'EditTask',
  components: { TaskDetails },
};
</script>
