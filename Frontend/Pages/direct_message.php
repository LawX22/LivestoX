<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Message Page</title>
    <script type="module" src="../../js/vue/direct-message.js" async></script>
    <link rel="stylesheet" href="../../css/message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .mull {
            text-align: right;
        }
        .mull_img {
            float: right;
        }
        .start-info {
            text-align: center;
            color: grey;
        }
    </style>
</head>

<body>
    <!-- Chat window -->
    <div id="direct-convo" class="chat-window">
        <div class="chat-header">
            <div class="profile-info">
                <img :src="'../../uploads/profile_pictures/'+profile_picture" alt="?" class="main-profile-circle">
                <h3 id="username">{{ fullname }}</h3>
            </div>
            <div class="calendar-icon">
                <i class="fas fa-calendar"></i>
            </div>
        </div>
        <p class="start-info">A legendary conversation has been started</p>
        <div v-for="conversation in convo" :key="conversation.message_id" class="chat-message">
            <p :class="{ mull: String(conversation.user_id) !== String(current_user) }">
                {{ conversation.content }}
            </p>
            <img 
                :class="{ mull_img: String(conversation.user_id) !== String(current_user) }"
                v-if="conversation.image_url && conversation.image_url !== ''" 
                :src="'../../uploads/livestock_posts/' + conversation.image_url" 
                alt="upload">

        </div>
        <div v-if="convo.length > 0 && convo[convo.length - 1].status === 'accepted'" class="chat-footer">
            <input
                type="text"
                placeholder="Type something..."
                v-model="message"
                @keyup.enter="sendMessage"
            >
            <input type="file" @change="handleFileUpload">
            <input type="hidden" v-model="image">
            <button class="send-button" @click="sendMessage">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <div v-else-if="convo.length > 0 && convo[convo.length - 1].status === 'request'" class="chat-footer">
            <button @click="acceptMessage">Accept</button>
            <button>Block</button>
        </div>
    </div>
</body>

</html>
