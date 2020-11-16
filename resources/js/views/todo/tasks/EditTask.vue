<template>
  <div>
    <task-details :is-edit="true" />
  </div>
</template>

<script>
import TaskDetails from './components/TaskDetails';
export default {
  name: 'EditTask',
  components: { TaskDetails },
};
</script>

