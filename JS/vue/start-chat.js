import { createApp, ref, onMounted, } from "https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.12/vue.esm-browser.min.js";
  
createApp({
  setup() {

    let firstTime = ref("Hi, is this still available?");

    const startWarBuyer = async (sender, receiver) => {
      if (sender === receiver ) {
        window.location.href = '../../Frontend/Buyer/message';
      } else {
        try {
            const response = await fetch(`../../Backend/chat/start_chat?sender=${sender}&receiver=${receiver}&initial=${firstTime.value}`);
            if (response.ok) {
                window.location.href = '../../Frontend/Buyer/message';
            }
          } catch (error) {
            console.error("Error fetching data:", error);
          }
      }
    };

    const startWarFarmer = async (sender, receiver) => {
        if (sender === receiver ) {
            window.location.href = '../../Frontend/Farmer/message';
        } else {
            try {
                const response = await fetch(`../../Backend/chat/start_chat?sender=${sender}&receiver=${receiver}&initial=${firstTime.value}`);
                if (response.ok) {
                    window.location.href = '../../Frontend/Farmer/message';
                }
              } catch (error) {
                console.error("Error fetching data:", error);
              }
        }
      };

    return { startWarBuyer, startWarFarmer, firstTime };
  },
}).mount("#declaration-of-chat");
  