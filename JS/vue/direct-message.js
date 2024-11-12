import { createApp, ref, onMounted } from "https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.12/vue.esm-browser.min.js";

createApp({
  setup() {
    let convo = ref([]);
    let message = ref("");
    let image = ref("");

    const urlParams = new URLSearchParams(window.location.search);
    const chat_id = urlParams.get("c");
    const fullname = urlParams.get("n");
    const profile_picture = urlParams.get("p");
    const status = urlParams.get("s");
    const current_user = urlParams.get("m");

    const fetchData = async () => {
      const eventSource = new EventSource(`../../Backend/chat/message_sse?uid=${chat_id}`);
      eventSource.onmessage = event => {
        const data = JSON.parse(event.data);
        convo.value = data;
      };
    };

    const sendMessage = async () => {
      // if (message.value.trim() === "") return;

      const messageData = {
        "user_id": current_user,
        "content": message.value,
        "status": status,
        "image": image.value
      };

      try {
        const response = await fetch(`../../Backend/chat/send_message?uuid=${chat_id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(messageData)
        });

        const data = await response.json();
        if (data.success) {
          console.log("Message sent successfully");
        } else {
          console.error("Failed to send message");
        }
      } catch (error) {
        console.error("Error sending message:", error);
      }

      message.value = "";
      image.value = "";
    };

    const handleFileUpload = (event) => {
      const files = event.target.files;
      if (files.length === 0) {
        console.error('No file selected!');
        return;
      }

      const formData = new FormData();
      formData.append('img', files[0]);

      fetch('../../Backend/chat/upload_photo', {
        method: 'POST',
        body: formData 
      })
      .then(response => response.json())
      .then(data => {
        image.value = data.filename
      })
      .catch(error => {
        console.error('Error during file upload:', error);
      });
    };

    onMounted(() => {
      fetchData();
    });

    return { 
      message, convo, fullname, profile_picture, current_user, 
      sendMessage, handleFileUpload, image
    };
  },
}).mount("#direct-convo");
