import { createApp, ref, onMounted, } from "https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.12/vue.esm-browser.min.js";
  
createApp({
  setup() {
    const conversations = ref([]);

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("convo");

    if (!id) {
      console.error("No ID found in the URL");
      return;
    }

    const fetchData = async () => {
      try {
        const response = await fetch(`../../Backend/chat/get_chat.php?user_id=${id}`);
        if (response.ok) {
          conversations.value = await response.json();
        } else {
          conversations.value = [];
        }
      } catch (error) {
        console.error("Error fetching data:", error);
        conversations.value = [];
      }
    };

    onMounted(() => {
      fetchData();
    });

    return { conversations };
  },
}).mount("#buyer-convo");
  