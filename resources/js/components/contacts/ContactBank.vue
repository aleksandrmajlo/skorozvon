<template>
  <div class="mb-3">
    <button
      :disabled="disabled"
      @click.prevent="SendBank"
      class="btn btn-outline-info"
    >
      {{ text }}
      <span v-show="intervalShow" class="interval">{{ start }}</span>
    </button>
  </div>
</template>
<script>
export default {
  name: "ContactBank",
  props: ["contact_id"],
  data() {
    return {
      disabled: false,
      intervalShow: false,
      start: 3,
      text: "Опросить банки",
    };
  },
  mounted() {
    // this.interval();
  },
  methods: {
    SendBank() {
      this.disabled = true;
      axios
        .post("/ajax/contact/sendBankContacDuplicate", {
          contact_id: this.contact_id,
        })
        .then((response) => {

        })
        .catch((err) => {})
        .then(() => {
          this.intervalShow = true;
          this.interval();
          this.text = "Получить результат ";
        });
    },
    interval() {
      let int = setInterval(() => {
        this.start = this.start - 1;
        if (this.start === 0) {
          clearInterval(int);
          document.getElementById("get_result_report").click();
        }
      }, 1000);
    },
  },
};
</script>
<style scoped>
.interval {
  box-sizing: content-box;
  width: 25px;
  height: 25px;
  text-align: center;
  line-height: 25px;
  display: inline-block;
  border: 2px red solid;
  border-radius: 50%;
  padding: 3px;
}
</style>
