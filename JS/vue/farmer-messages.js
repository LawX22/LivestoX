import { createApp, ref, onMounted  } from "https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.12/vue.esm-browser.min.js";

createApp({
  setup() {
    const conversations = ref([]);

    const fetchData = async () => {
        try {
          const response = await fetch('http://localhost:3030/c/1');
          if (response.ok) {
            conversations.value = await response.json();
          } else {
            conversations.value = [];
          }
        } catch (error) {
          console.error('Error fetching data:', error);
          conversations.value = [];
        }
      };

      onMounted(() => {
        fetchData();
      });

      return { conversations };
  },
}).mount("#farmer");

